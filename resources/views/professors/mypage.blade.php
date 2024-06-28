<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>オブザーバーマイページ</title>
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
                    {{ $professor->name }}さんのマイページ
                </h1>
                <a href="{{ route('dashboard') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-teal-500 shadow-md text-sm">
                    Edicourse Media Top
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-12">
                <a href="{{ route('professor.mypage.edit') }}" class="group block p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300 border border-gray-200 hover:border-teal-200">
                    <h2 class="text-xl font-normal text-teal-600 mb-2 group-hover:text-teal-700">設定／登録情報の変更</h2>
                    <p class="text-gray-600 text-sm">プロフィールや設定を更新する</p>
                </a>
            </div>

            <div class="mb-12">
                <h2 class="text-2xl font-light text-gray-700 mb-6 pb-2 border-b">
                    <span class="block text-sm text-gray-500 mb-1">自分のコメント記事</span>
                    My Commented Articles
                </h2>
                @if($commentedArticles->isEmpty())
                    <p class="text-gray-600 italic">まだコメントした記事がありません。</p>
                @else
                    <ul class="space-y-4">
                        @foreach($commentedArticles as $article)
                            <li class="flex items-center justify-between bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition duration-300">
                                <a href="{{ route('articles.show', $article->id) }}" class="text-teal-600 hover:text-teal-800 transition duration-300">{{ $article->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="text-center pt-6 border-t">
                <form action="{{ route('professor.delete') }}" method="POST" onsubmit="return confirmDeleteAccount()">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300 text-sm">登録情報を削除して退会する</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteAccount() {
            return confirm('作成したコメントとユーザー情報がすべて削除されます。本当に退会しますか？');
        }
    </script>
</body>
</html>