<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>レビュワーマイページ</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
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
                    {{ $reviewer->name }}さんのマイページ
                </h1>
                <a href="{{ route('dashboard') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-teal-500 shadow-md text-sm">
                    Edicourse Media Top
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-12">
                <a href="{{ route('reviewer.mypage.edit') }}" class="group block p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300 border border-gray-200 hover:border-teal-200">
                    <h2 class="text-xl font-normal text-teal-600 mb-2 group-hover:text-teal-700">設定／登録情報の変更</h2>
                    <p class="text-gray-600 text-sm">プロフィールや設定を更新する</p>
                </a>
            </div>

           <!-- レビューリクエスト記事 -->
           <div class="mb-12">
        <h2 class="text-2xl font-light text-gray-700 mb-6 pb-2 border-b">
            <span class="block text-sm text-gray-500 mb-1">レビューリクエスト記事</span>
            Review-Requested
        </h2>
        @if($reviewRequestedArticles->isEmpty())
            <p class="text-gray-600 italic">現在、レビューリクエストされている記事はありません。</p>
        @else
            <ul class="space-y-4">
            @foreach($reviewRequestedArticles as $article)
                <li class="flex items-center justify-between bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition duration-300">
                    <a href="{{ route('articles.show', $article->id) }}" class="text-teal-600 hover:text-teal-800 transition duration-300">{{ $article->title }}</a>
                    <button data-article-id="{{ $article->id }}" class="accept-review-btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                        私がレビューします！
                    </button>
                </li>
            @endforeach
            </ul>
        @endif
    </div>

    <!-- 自分がレビュー中の記事 -->
<div class="mb-12">
    <h2 class="text-2xl font-light text-gray-700 mb-6 pb-2 border-b">
        <span class="block text-sm text-gray-500 mb-1">レビュー中の記事</span>
        Ongoing Reviews
    </h2>
    @if($ongoingReviews->isEmpty())
        <p class="text-gray-600 italic">現在レビュー中の記事はありません。</p>
    @else
        <ul class="space-y-4">
        @foreach($ongoingReviews as $review)
    <li class="flex items-center justify-between bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition duration-300">
        <div>
            <a href="{{ route('articles.show', $review->article->id) }}" class="text-teal-600 hover:text-teal-800 transition duration-300">{{ $review->article->title }}</a>
            <p class="text-sm text-red-500">あと{{ remainingTime($review->limit_time) }}以内にレビューを返却してください</p>
        </div>
        @if($review->article->draft)
            <form action="{{ route('reviewer.review', $review->id) }}" method="GET">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    下書きを続ける
                </button>
            </form>
        @else
            <form action="{{ route('reviewer.review', $review->id) }}" method="GET">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    レビューを作成する
                </button>
            </form>
        @endif
    </li>
@endforeach
        </ul>
    @endif
</div>



    <!-- My Review（自分がレビューした記事） -->
    <div class="mb-12">
        <h2 class="text-2xl font-light text-gray-700 mb-6 pb-2 border-b">
            <span class="block text-sm text-gray-500 mb-1">自分がレビューした記事</span>
            My Reviews
        </h2>
        @if($completedReviews->isEmpty())
            <p class="text-gray-600 italic">まだレビューした記事はありません。</p>
        @else
            <ul class="space-y-4">
                @foreach($completedReviews as $review)
                    <li class="flex items-center justify-between bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition duration-300">
                    <div>
                        <a href="{{ route('articles.show', $review->article->id) }}" class="text-teal-600 hover:text-teal-800 transition duration-300">{{ $review->article->title }}</a>
                        @if($review->status === 'thanked')
                            <span class="ml-2 text-sm text-green-500 font-semibold">感謝されました</span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-500">{{ $review->updated_at->format('Y/m/d H:i') }}</span>
                </li>
            @endforeach
            </ul>
        @endif
    </div>



            <div class="text-center pt-6 border-t">
                <form action="{{ route('reviewer.delete') }}" method="POST" onsubmit="return confirmDeleteAccount()">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300 text-sm">登録情報を削除して退会する</button>
                </form>
            </div>
        </div>
    </div>

    <script>

document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.accept-review-btn').forEach(button => {
            button.addEventListener('click', function() {
                const articleId = this.getAttribute('data-article-id');
                acceptReview(articleId);
            });
        });
    });

    function acceptReview(articleId) {
        console.log('Accepting review for article:', articleId);
        if (confirm('このレビューを受け付けますか？')) {
            const url = "{{ route('reviewer.accept-review', ':articleId') }}".replace(':articleId', articleId);
        console.log('Request URL:', url);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('レビュー受付に失敗しました: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('レビュー受付中にエラーが発生しました。');
        });
    }
}


        function confirmDeleteAccount() {
            return confirm('作成したレビューとユーザー情報がすべて削除されます。本当に退会しますか？');
        }
    </script>
</body>
</html>