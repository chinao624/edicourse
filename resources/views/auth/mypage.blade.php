<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>マイページ</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen text-gray-800">
    <div class="container max-w-4xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-12 border-b pb-6">
                <h1 class="text-3xl font-light text-gray-700">
                    <span class="block text-sm text-teal-600 mb-1">ようこそ</span>
                    {{ $user->nickname }}さんのマイページ
                </h1>
                <a href="{{ route('dashboard') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-teal-500 shadow-md text-sm">
                    Edicourse Media Top
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <a href="{{ route('mypage.edit') }}" class="group block p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300 border border-gray-200 hover:border-teal-200">
                    <h2 class="text-xl font-normal text-teal-600 mb-2 group-hover:text-teal-700">設定／登録情報の変更</h2>
                    <p class="text-gray-600 text-sm">プロフィールや設定を更新する</p>
                </a>
                <a href="{{ route('articles.create') }}" class="group block p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300 border border-gray-200 hover:border-teal-200">
                    <h2 class="text-xl font-normal text-teal-600 mb-2 group-hover:text-teal-700">記事を作成する</h2>
                    <p class="text-gray-600 text-sm">新しい記事を書き始める</p>
                </a>
            </div>

            <div class="mb-12">
    <h2 class="text-2xl font-light text-gray-700 mb-6 pb-2 border-b">
        <span class="block text-sm text-gray-500 mb-1">自分の作成記事</span>
        My Articles
    </h2>
    @if($articles->isEmpty())
        <p class="text-gray-600 italic">まだ記事がありません。</p>
    @else
        <ul class="space-y-4">
        @foreach($articles as $article)
        <li class="flex items-center justify-between bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition duration-300">
    <div>
        <a href="{{ route('articles.show', $article->id) }}" class="text-teal-600 hover:text-teal-800 transition duration-300">{{ $article->title }}</a>
        <span class="ml-2 text-sm text-gray-500">
            @switch($article->status)
                @case('draft')
                    (下書き)
                    @break
                @case('published')
                    (公開中)
                    @break
                @case('review_requested')
                    (レビュー依頼中)
                    @break
                @case('under_review')
                    (レビュー中)
                    @break
                @default
                    ({{ $article->status }})
            @endswitch
        </span>
    </div>
    <div class="flex items-center">
    @if($article->status == 'draft')
        <button data-article-id="{{ $article->id }}" class="review-request-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full mr-2">レビュー依頼</button>
        @elseif($article->status == 'review_requested')
    <button data-article-id="{{ $article->id }}" class="withdraw-review-btn text-sm bg-amber-100 hover:bg-amber-200 text-amber-800 font-medium py-1 px-3 rounded-full border border-amber-300 transition duration-300 ease-in-out flex items-center mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        レビュー取り下げ
    </button>
@endif
        <a href="{{ route('articles.edit', $article->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-300 text-sm mr-4">編集</a>
        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300 text-sm" onclick="return confirmDelete()">削除</button>
        </form>
    </div>
</li>
@endforeach
        </ul>
    @endif
</div>

            <div class="text-center pt-6 border-t">
                <form action="{{ route('user.delete') }}" method="POST" onsubmit="return confirmDeleteAccount()">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300 text-sm">登録情報を削除して退会する</button>
                </form>
            </div>
        </div>
    </div>

    <script>
function confirmDelete() {
    return confirm('本当に削除しますか？');
}

function confirmDeleteAccount() {
    return confirm('作成した記事とユーザー情報がすべて削除されます。本当に退会しますか？');
}

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }

    document.querySelectorAll('.review-request-btn').forEach(button => {
        button.addEventListener('click', function() {
            const articleId = this.getAttribute('data-article-id');
            if (articleId) {
                requestReview(articleId, csrfToken);
            } else {
                console.error('Article ID is missing for review request');
            }
        });
    });

    document.querySelectorAll('.withdraw-review-btn').forEach(button => {
        button.addEventListener('click', function() {
            const articleId = this.getAttribute('data-article-id');
            if (articleId) {
                withdrawReviewRequest(articleId, csrfToken);
            } else {
                console.error('Article ID is missing for withdraw request');
            }
        });
    });
});

function requestReview(articleId, csrfToken) {
    if (confirm('この記事のレビューを依頼しますか？')) {
        sendRequest(`/articles/${articleId}/request-review`, csrfToken, 'レビュー依頼');
    }
}

function withdrawReviewRequest(articleId, csrfToken) {
    if (confirm('レビュー依頼を取り下げますか？この操作は取り消せません。')) {
        sendRequest(`/articles/${articleId}/withdraw-review`, csrfToken, '取り下げ');
    }
}

function sendRequest(url, csrfToken, action) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(`${action}に失敗しました: ` + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`${action}中にエラーが発生しました。`);
    });
}
</script>
</body>
</html>