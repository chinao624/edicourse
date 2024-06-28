<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mainimg' => 'required|image|max:5120',
            'title' => 'required|string|max:500',
            'lead' => 'required|string|max:1000',
            'img1' => 'required|image|max:5120',
            'cap1' => 'required|string|max:1000',
            'img2' => 'required|image|max:5120',
            'cap2' => 'required|string|max:1000',
            'closing' => 'required|string|max:1000',
            'genre' => 'required|string|max:255',
        ]);

        $article = new Article();
        $article->user_id = Auth::id();
        $article->title = $validatedData['title'];
        $article->lead = $validatedData['lead'];
        $article->cap1 = $validatedData['cap1'];
        $article->cap2 = $validatedData['cap2'];
        $article->closing = $validatedData['closing'];
        $article->genre = $validatedData['genre'];

        if ($request->hasFile('mainimg')) {
            $article->mainimg = $request->file('mainimg')->store('images', 'public');
        }

        if ($request->hasFile('img1')) {
            $article->img1 = $request->file('img1')->store('images', 'public');
        }

        if ($request->hasFile('img2')) {
            $article->img2 = $request->file('img2')->store('images', 'public');
        }

        $article->status = 'draft';
        $article->save();

        return redirect()->route('articles.show', $article->id);
    }


    /**
     * Display the specified resource.
     */

     public function getJapaneseGenre($englishGenre)
     {
    return $this->genreMapping[$englishGenre] ?? $englishGenre;
     }
    public function show(string $id)
    {
        $article = Article::with(['user','comments.professor'])->findOrFail($id);
    $japaneseGenre = $this->getJapaneseGenre($article->genre);

    //日付の表示追加
    $formattedDate = $article->updated_at->format('Y年m月d日');

    // $genreMappingをビューに渡す
    $genreMapping = $this->genreMapping;

    return view('articles.show', compact('article', 'japaneseGenre','formattedDate','genreMapping'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $article = Article::findOrFail($id);
        return redirect()->route('articles.show', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        if(Auth::check() && Auth::user()->id == $article->user_id){
            
        //バリデーション
        $validatedData = $request->validate([
            'title' => 'required|string|max:500',
            'lead' => 'required|string|max:1000',
            'cap1' => 'required|string|max:1000',
            'cap2' => 'required|string|max:1000',
            'closing' => 'required|string|max:1000',
            'mainimg' => 'image|max:5120',
            'img1' => 'image|max:5120',
            'img2' => 'image|max:5120',
            'genre' => 'required|string|max:255',
        ]);

        //記事の更新
        $article->title = $validatedData['title'];
        $article->lead = $validatedData['lead'];
        $article->cap1 = $validatedData['cap1'];
        $article->cap2 = $validatedData['cap2'];
        $article->closing = $validatedData['closing'];
        $article->genre = $validatedData['genre'] ?? $article->genre;

        //画像の更新
        if ($request->hasFile('mainimg')) {
            if ($article->mainimg) {
                Storage::disk('public')->delete($article->mainimg);
            }
            $article->mainimg = $request->file('mainimg')->store('images', 'public');
        }
        if ($request->hasFile('img1')) {
            if ($article->img1) {
                Storage::disk('public')->delete($article->img1);
            }
            $article->img1 = $request->file('img1')->store('images', 'public');
        }
        if ($request->hasFile('img2')) {
            if ($article->img2) {
                Storage::disk('public')->delete($article->img2);
            }
            $article->img2 = $request->file('img2')->store('images', 'public');
        }

        // ステータスの更新
        if ($request->has('status')) {
            $article->status = $request->input('status');
        }

        $article->save();

        return response()->json(['success' => true, 'message' => '記事を更新しました']);
    } else {
        return response()->json(['success' => false, 'message' => '記事を更新できませんでした'], 403);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        
    if (!$article) {
        return abort(404); // 記事が見つからない場合は404エラー
    }

    if ($article->user_id !== Auth::id()) {
        return redirect()->back()->with('error', '記事の削除権限がありません。'); 
    }

    $article->delete();

    // 削除後、リダイレクト
    return redirect()->route('mypage')->with('success', '記事を削除しました。');
    }

    //ダッシュボードでgenreごとの記事取得

    private $genreMapping = [
        'trend' => 'トレンド',
        'spot' => 'スポット',
        'travel' => 'トラベル',
        'sports' => 'スポーツ',
        'event' => 'イベント',
        'interview' => 'インタビュー',
        'opinion' => 'オピニオン',
        'others' => 'その他'
    ];

    public function dashboard()
    {
    $genres = array_values($this->genreMapping);
    $articles = Article::where('status', 'published')->with('user:id,nickname,pref,school')->orderBy('updated_at', 'desc')
    ->paginate(6);
    $user = Auth::user() ?? Auth::guard('professor')->user();
    return view('dashboard', compact('genres', 'articles','user'));
    }

    public function showByGenre($genre)
    {
    $decodedGenre = urldecode($genre);
    $genres = array_values($this->genreMapping);
    $englishGenre = array_search($decodedGenre, $this->genreMapping);
    $articles = Article::where('genre', $englishGenre)->where('status', 'published')->with('user:id,nickname,pref,school')->orderBy('updated_at', 'desc')
    ->paginate(6);
    $user = Auth::user() ?? Auth::guard('professor')->user();
    return view('dashboard', compact('genres', 'articles', 'decodedGenre','user'));
    }

    //プレビューメソッド
    public function preview(Request $request)
{
    $validatedData = $request->validate([
        'mainimg' => 'image|max:5120',
        'title' => 'required|string|max:500',
        'lead' => 'required|string|max:1000',
        'img1' => 'image|max:5120',
        'cap1' => 'required|string|max:1000',
        'img2' => 'image|max:5120',
        'cap2' => 'required|string|max:1000',
        'closing' => 'required|string|max:1000',
        'genre' => 'required|string|max:255',
    ]);

    // 記事をドラフトとしてデータベースに保存
    $article = new Article();
    $article->user_id = Auth::id();
    $article->title = $validatedData['title'];
    $article->lead = $validatedData['lead'];
    $article->cap1 = $validatedData['cap1'];
    $article->cap2 = $validatedData['cap2'];
    $article->closing = $validatedData['closing'];
    $article->genre = $validatedData['genre'];
    $article->status = 'draft';

    if ($request->hasFile('mainimg')) {
        $article->mainimg = $request->file('mainimg')->store('images', 'public');
    }
    if ($request->hasFile('img1')) {
        $article->img1 = $request->file('img1')->store('images', 'public');
    }
    if ($request->hasFile('img2')) {
        $article->img2 = $request->file('img2')->store('images', 'public');
    }

    $article->save();

    return redirect()->route('articles.show', $article->id);
}


     // 記事を公開する
     public function publish($id)
     {
         $article = Article::findOrFail($id);
 
         if(Auth::check() && Auth::user()->id == $article->user_id){
             $article->status = 'published';
             $article->save();
 
             return redirect()->route('articles.show', $id)->with('success', '記事を公開しました。');
         } else {
             return redirect()->route('articles.show', $id)->with('error', '記事を公開する権限がありません。');
         }
     }

    //  professorのコメント
    public function createComment(Article $article)
{
    return view('professors.comment_create', compact('article'));
}

public function storeComment(Request $request, Article $article)
{
    $request->validate([
        'comment' => 'required|string'
    ]);

    $article->comments()->create([
        'professor_id' => auth('professor')->id(),
        'comment' => $request->comment
    ]);

    return redirect()->route('articles.show', $article)->with('success', 'コメントが投稿されました。');
}

}

