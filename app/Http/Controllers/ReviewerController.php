<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ReviewArticle;
use App\Models\Reviewer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    public function create()
    {
        return view('reviewer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:reviewers,email',
            'title' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $reviewer = new Reviewer();
        $reviewer->name = $request->name;
        $reviewer->email = $request->email;
        $reviewer->title = $request->title;
        $reviewer->password = Hash::make($request->password);
    
        $reviewer->save();
    
        // ログインページにリダイレクト
        return redirect()->route('reviewer.login')->with('success', 'アカウントが正常に作成されました。ログインしてください。');
}

//showMypageとdeleteメソッドは仮！→修正中
public function showMypage()
{
    $reviewer = Auth::guard('reviewer')->user();
    $reviewRequestedArticles = Article::where('status', 'review_requested')->get();
    $myReviews = ReviewArticle::where('reviewer_id', $reviewer->id)
                              ->where('status', 'completed')
                              ->with('article')
                              ->get();

    // 「レビューします」といった本人には記事は表示させておく
    $ongoingReviews = ReviewArticle::where('reviewer_id', $reviewer->id)
                              ->where('status', 'pending')
                              ->with('article')
                              ->get();

    return view('reviewer.mypage',compact('reviewer','reviewRequestedArticles','myReviews','ongoingReviews'));
}

public function acceptReview(Article $article)
{
    $reviewer = auth()->guard('reviewer')->user();
    
    $article->status = 'under_review';
    $article->save();

    ReviewArticle::create([
        'reviewer_id' => $reviewer->id,
        'article_id' => $article->id,
        'status' => 'pending',
        'limit_time' => now()->addHours(24),
    ]);

    return redirect()->route('reviewer.review', ['review' => $article->id])->with('success', 'レビューを受け付けました。');
}

// レビューページ用メソッド
public function showReviewPage($id)
{
    $review = ReviewArticle::with('article')->findOrFail($id);
    $article = $review->article;

    return view('reviewer.review', compact('review','article'));
}

public function delete(Request $request)
{
    $reviewer = Auth::guard('reviewer')->user();

    // レビューアーに関連するコメントの削除
    // $reviewer->articleComments()->delete();

    // レビューアーの削除
    $reviewer->delete();

    Auth::guard('reviewer')->logout();

    return redirect()->route('home')->with('success', '退会処理が完了しました。');
}

public function edit()
{
    $reviewer = Auth::guard('reviewer')->user();
    return view('reviewer.mypage_edit', compact('reviewer'));
}

public function update(Request $request)
{
    $reviewer = Auth::guard('reviewer')->user();

    $request->validate([
        'email' => 'required|email|unique:reviewers,email,'.$reviewer->id,
        'title' => 'required|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $reviewer->email = $request->email;
    $reviewer->title = $request->title;

    if ($request->filled('password')) {
        $reviewer->password = Hash::make($request->password);
    }

    $reviewer->save();

    return redirect()->route('reviewer.mypage.edit')->with('success', 'プロフィールが更新されました。');
}
}
