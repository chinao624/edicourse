<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\Auth\ProfessorLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ReviewerLoginController;
use App\Http\Controllers\ReviewerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ユーザー登録ルート
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// ユーザー認証ルート
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Professor認証ルート
Route::get('professor/login', [ProfessorLoginController::class, 'showLoginForm'])->name('professor.login');
Route::post('professor/login', [ProfessorLoginController::class, 'login']);
Route::post('professor/logout', [ProfessorLoginController::class, 'logout'])->name('professor.logout');

// Professor登録ルート
Route::get('professors/create', [ProfessorController::class, 'create'])->name('professors.create');
Route::post('professors', [ProfessorController::class, 'store'])->name('professors.store');

// Reviewer認証ルート
Route::get('reviewer/login', [ReviewerLoginController::class, 'showLoginForm'])->name('reviewer.login');
Route::post('reviewer/login', [ReviewerLoginController::class, 'login']);
Route::post('reviewer/logout', [ReviewerLoginController::class, 'logout'])->name('reviewer.logout');

// Reviewer登録ルート
Route::get('reviewer/create', [ReviewerController::class, 'create'])->name('reviewer.create');
Route::post('reviewer', [ReviewerController::class, 'store'])->name('reviewer.store');

// 共通のダッシュボードルート（ユーザーまたはProfessorまたはreviewer）
Route::middleware(['auth:web,professor,reviewer'])->group(function () {
    Route::get('/dashboard', [ArticleController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/genre/{genre}', [ArticleController::class, 'showByGenre'])->name('articles.genre');
});

// ユーザー専用のルート
Route::middleware(['auth:web'])->group(function () {
    // 記事作成ルート
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

    // 記事編集ルート
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');

    // 記事削除ルート
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // マイページプロフィール更新削除ルート
    Route::get('/mypage', [UserController::class, 'showMypage'])->name('mypage');
    Route::get('/mypage/edit', [UserController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/update', [UserController::class, 'update'])->name('mypage.update');

    // 記事公開ルート
    Route::put('/articles/{id}/publish', [ArticleController::class, 'publish'])->name('articles.publish');

    // 記事status表示ルート
    Route::get('/mypage', [ArticleController::class, 'getUserArticlesWithStatus'])->name('mypage');

    // レビュー依頼ルート
    Route::post('/articles/{article}/request-review', [ArticleController::class, 'requestReview'])->name('articles.request-review');

    // 下書きに戻すルート
    Route::put('/articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');

    // 再投稿ルート
    Route::put('/articles/{id}/repost', [ArticleController::class, 'repost'])->name('articles.repost');
});

// Professor専用のルート
Route::middleware(['auth:professor'])->group(function () {
    // マイページルート
    Route::get('/professor/mypage', [ProfessorController::class, 'showMypage'])->name('professor.mypage');
    Route::get('/professor/mypage/edit', [ProfessorController::class, 'edit'])->name('professor.mypage.edit');
    Route::post('/professor/delete', [ProfessorController::class, 'delete'])->name('professor.delete');
    Route::get('/professor/mypage/edit', [ProfessorController::class, 'edit'])->name('professor.mypage.edit');
    Route::post('/professor/mypage/update', [ProfessorController::class, 'update'])->name('professor.mypage.update');

    // professorのコメントルート
Route::get('/articles/{article}/comment/create', [ArticleController::class, 'createComment'])->name('article.comment.create');
Route::post('/articles/{article}/comment', [ArticleController::class, 'storeComment'])->name('article.comment.store');
//コメント編集・削除ルート
Route::get('/comments/{comment}/edit', [ArticleController::class, 'editComment'])->name('article.comment.edit');
Route::put('/comments/{comment}', [ArticleController::class, 'updateComment'])->name('article.comment.update');
Route::delete('/comments/{comment}', [ArticleController::class, 'destroyComment'])->name('article.comment.destroy');

});

// reviewer専用ルート
Route::middleware(['auth:reviewer'])->group(function () {
    Route::get('/reviewer/mypage', [ReviewerController::class, 'showMypage'])->name('reviewer.mypage');
    Route::get('/reviewer/mypage/edit', [ReviewerController::class, 'edit'])->name('reviewer.mypage.edit');
    Route::post('/reviewer/mypage/update', [ReviewerController::class, 'update'])->name('reviewer.mypage.update');
    Route::post('/reviewer/delete', [ReviewerController::class, 'delete'])->name('reviewer.delete');
    Route::post('/reviewer/accept-review/{article}', [ReviewerController::class, 'acceptReview'])->name('reviewer.accept-review');
    Route::get('/reviewer/review/{review}', [ReviewerController::class, 'showReviewPage'])->name('reviewer.review');
});

// 記事表示ルート（認証不要）
Route::post('/articles/preview', [ArticleController::class, 'preview'])->name('articles.preview');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// 退会ルート
Route::post('/user/delete', [UserController::class, 'deleteAccount'])->name('user.delete');

// パスワードリセット関連ルート
Route::middleware('guest')->group(function () {
    Route::get('/forgot_password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot_password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset_password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset_password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

