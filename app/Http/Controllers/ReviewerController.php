<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ReviewArticle;
use App\Models\Reviewer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    $reviewRequestedArticles = Article::where('status', 'review_requested')->distinct()->get();
    $myReviews = ReviewArticle::where('reviewer_id', $reviewer->id)
                              ->where('status', 'completed')
                              ->with('article')
                              ->distinct()
                              ->get();

    // 「レビューします」といった本人には記事は表示させておく
    $ongoingReviews = ReviewArticle::where('reviewer_id', $reviewer->id)
                              ->where('status', 'pending')
                              ->with('article')
                              ->distinct()
                              ->get();
    // 完了したレビューと感謝の表示
    $completedReviews = ReviewArticle::where('reviewer_id', $reviewer->id)
                              ->whereIn('status', ['completed', 'thanked'])
                              ->with('article')
                              ->distinct()
                              ->get();


    return view('reviewer.mypage',compact('reviewer','reviewRequestedArticles','completedReviews','ongoingReviews'));
}

public function acceptReview(Article $article)
{
    Log::info('Accepting review for article: ' . $article->id);
    $reviewer = Auth::guard('reviewer')->user();
    
    Log::info('Updating article status to under_review');
    $article->update(['status' => 'under_review']);

    Log::info('Creating or updating ReviewArticle');
    $review = ReviewArticle::updateOrCreate(
        ['article_id' => $article->id],
        [
            'reviewer_id' => $reviewer->id,
            'status' => 'pending',
            'limit_time' => now()->addHours(24),
        ]
    );

    Log::info('ReviewArticle created/updated: ' . $review->id);

    return response()->json([
        'success' => true,
        'message' => 'レビューを受け付けました。',
        'redirect' => route('reviewer.review', ['review' => $review->id])
    ]);
}

// レビューページ用メソッド
public function showReviewPage(ReviewArticle $review)
{
        try {
            $article = $review->article;
            
            if (!$article) {
                Log::error('Article not found for review', ['review_id' => $review->id]);
                abort(404, 'Article not found');
            }
    
            // レビューが現在のレビュワーに属しているか確認
            if ($review->reviewer_id !== Auth::guard('reviewer')->id()) {
                Log::warning('Unauthorized access attempt to review', ['review_id' => $review->id]);
                abort(403, 'Unauthorized access');
            }
    
            $draftData = $article->draft ?? null;
            $reviewComment = $article->review_comment ?? '';
    
            Log::info('Showing review page', [
                'review_id' => $review->id,
                'article_id' => $article->id,
                'has_draft_data' => !is_null($draftData),
                'has_review_comment' => !empty($reviewComment)
            ]);
    
            return view('reviewer.review', compact('review', 'article', 'draftData', 'reviewComment'));
        } catch (\Exception $e) {
            Log::error('Error in showReviewPage', [
                'review_id' => $review->id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'An error occurred while loading the review page');
        }
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

public function saveDraft(Request $request, ReviewArticle $review)
{
    Log::info('Saving draft attempt started for review ID: ' . $review->id);

    if ($review->reviewer_id !== Auth::guard('reviewer')->id()) {
        Log::warning('Unauthorized attempt to save draft for review ID: ' . $review->id);
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $validatedData = $request->validate([
        'draft' => 'required|json',
        'review_comment' => 'nullable|string',
    ]);

    try {
        $review->article->update([
            'draft' => $validatedData['draft'],
            'review_comment' => $validatedData['review_comment'],
        ]);

        Log::info('Draft and review comment saved successfully');

        return response()->json(['success' => true, 'message' => 'Draft saved successfully']);
    } catch (\Exception $e) {
        Log::error('Failed to save draft: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to save draft'], 500);
    }
}

public function submitReview(Request $request, ReviewArticle $review)
{
    if (Auth::guard('reviewer')->id() !== $review->reviewer_id) {
        return response()->json(['success' => false, 'message' => 'この操作を行う権限がありません。'], 403);
    }

    $validatedData = $request->validate([
        'feedback' => 'required|string',
        'draft' => 'required|json',
    ]);

    try {
        DB::transaction(function () use ($review, $validatedData) {
            $review->update([
                'status' => ReviewArticle::STATUS_COMPLETED,
            ]);

            $review->article->update([
                'status' => 'reviewed',
                'draft' => $validatedData['draft'],
                'review_comment' => $validatedData['feedback'],
            ]);
        });

        return response()->json(['success' => true, 'message' => 'レビューが返却されました。']);
    } catch (\Exception $e) {
        Log::error('Failed to submit review: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'レビューの返却に失敗しました。'], 500);
    }
}
}


