<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ReviewArticle;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


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

         // 記事が公開中だった場合、ステータスを 'draft' に変更
         if ($article->status == 'published') {
            $article->status = 'draft';
        }
        $article->save();

        return response()->json([
            'success' => true, 
            'message' => '記事を更新しました。記事は下書き状態になりました。',
            'status' => $article->status
        ]);
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
    $user = Auth::user() ?? Auth::guard('professor')->user() ?? Auth::guard('reviewer')->user();
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
 
             return response()->json(['success' => true, 'message' => '記事を公開しました']);
            } else {
                return response()->json(['success' => false, 'message' => '記事を公開する権限がありません'], 403);
            }
     }

    //  再投稿のメソッド
    public function repost($id)
{
    $article = Article::findOrFail($id);

    if(Auth::check() && Auth::user()->id == $article->user_id){
        $article->status = 'published';
        $article->save();

        return response()->json(['success' => true, 'message' => '記事を再投稿しました']);
    } else {
        return response()->json(['success' => false, 'message' => '記事を再投稿する権限がありません'], 403);
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

// professorのコメント削除・更新機能
public function editComment(ArticleComment $comment)
{
    if (Auth::guard('professor')->check() && Auth::guard('professor')->user()->id === $comment->professor_id) {
        $article = $comment->article;
        return view('professors.comment_edit', compact('comment', 'article'));
    } else {
        return redirect()->back()->with('error', 'コメントの編集権限がありません。');
    }
}

public function updateComment(Request $request, ArticleComment $comment)
{
    if (Auth::guard('professor')->check() && Auth::guard('professor')->user()->id === $comment->professor_id) {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update(['comment' => $request->comment]);
        return redirect()->route('articles.show', $comment->article)->with('success', 'コメントが更新されました。');
    } else {
        return redirect()->back()->with('error', 'コメントの更新権限がありません。');
    }
}

public function destroyComment(ArticleComment $comment)
{
    if (Auth::guard('professor')->check() && Auth::guard('professor')->user()->id === $comment->professor_id) {
        $article = $comment->article;
        $comment->delete();
        return redirect()->route('articles.show', $article)->with('success', 'コメントが削除されました。');
    } else {
        return redirect()->back()->with('error', 'コメントの削除権限がありません。');
    }
}

// レビュー依頼メソッド
public function requestReview(Article $article)
{
    if (Auth::id() !== $article->user_id) {
        return response()->json(['success' => false, 'message' => 'レビューを依頼する権限がありません。'], 403);
    }

    if ($article->status === 'review_requested') {
        return response()->json(['success' => false, 'message' => 'この記事は既にレビューが依頼されています。'], 400);
    }

    $article->update(['status' => 'review_requested']);

    ReviewArticle::updateOrCreate(
        ['article_id' => $article->id],
        [
            'status' => 'pending',
            'limit_time' => now()->addHours(24),
            'reviewer_id' => null
        ]
    );
    return response()->json(['success' => true, 'message' => 'レビューが依頼されました']);
}

// レビュー用スクリーンショット作成メソッド
public function saveScreenshot(Request $request)
{
    Log::info('Screenshot save attempt started');
    
    $request->validate([
        'screenshot' => 'required|image',
        'article_id' => 'required|exists:articles,id'
    ]);

    Log::info('Validation passed');

    $article = Article::findOrFail($request->article_id);

    if (Auth::id() !== $article->user_id) {
        Log::warning('Unauthorized attempt to save screenshot');
        return response()->json(['success' => false, 'message' => 'スクリーンショットを保存する権限がありません。'], 403);
    }

    Log::info('Authorization passed');

   try {
        $path = $request->file('screenshot')->store('screenshots', 'public');
        Log::info('Screenshot stored at: ' . $path);
        
        $article->screenshot_path = $path;
        $article->save();

        Log::info('Screenshot path saved to database');

        return response()->json(['success' => true, 'message' => 'スクリーンショットが保存されました','path' => Storage::url($path)]);
    } catch (\Exception $e) {
        Log::error('Error saving screenshot: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'スクリーンショットの保存中にエラーが発生しました'], 500);
    }
}

// 記事ステータス表示メソッド
public function getUserArticlesWithStatus()
    {
        $user = Auth::user();
        $articles = Article::where('user_id', $user->id)
                          ->with('review')
                           ->select('id', 'title', 'status', 'created_at')
                           ->orderBy('created_at', 'desc')
                           ->get();
    
        return view('auth.mypage', compact('user', 'articles'));

    }

    // 下書きに戻すメソッドを追加
    public function unpublish(Article $article)
{
    if (Auth::id() !== $article->user_id) {
        return response()->json(['success' => false, 'message' => '記事を下書きに戻す権限がありません。'], 403);
    }

    $article->update(['status' => 'draft']);
    return response()->json(['success' => true, 'message' => '記事を下書きに戻しました']);
}




// レビュー取り下げメソッド
public function withdrawReviewRequest(Article $article)
{
    if (Auth::id() !== $article->user_id) {
        return response()->json(['success' => false, 'message' => 'この操作を行う権限がありません。'], 403);
    }

    if ($article->status !== 'review_requested') {
        return response()->json(['success' => false, 'message' => 'この記事はレビュー依頼中ではありません。'], 400);
    }

    // トランザクションを使用してデータの整合性を保証
    DB::transaction(function () use ($article) {
        // レビュー依頼の取り下げ処理
        $article->status = 'draft';
        $article->save();

        // ReviewArticleの状態を「取り下げ」に変更
        $review = $article->review;
        if ($review) {
            $review->status = ReviewArticle::STATUS_WITHDRAWN;
            $review->withdrawn_at = now();
            $review->save();

            // ここでレビュワーへの通知を送信させたいが。。。
            // Notification::send($reviewArticle->reviewer, new ReviewWithdrawnNotification($article));
        }
    });

    return response()->json(['success' => true, 'message' => 'レビュー依頼を取り下げました。']);
}

// レビュー返却確認メソッド
public function acknowledgeReview(ReviewArticle $review)
{
    if (auth()->id() !== $review->article->user_id) {
        abort(403, 'Unauthorized action.');
    }

    $review->update(['status' => ReviewArticle::STATUS_THANKED]);

    return redirect()->back()->with('success', 'レビューへの感謝が記録されました。');
}

public function viewReview(ReviewArticle $review)
{
    if (auth()->id() !== $review->article->user_id) {
        abort(403, 'Unauthorized action.');
    }

    $article = $review->article;
    $draftData = $review->draft_data;
    $reviewComment = $review->feedback;

    return view('reviewer.review', compact('review','article', 'draftData', 'reviewComment'));
}
}

