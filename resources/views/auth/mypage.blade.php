<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>マイページ</title>
</head>
<body class="bg-[#fdf5e6] flex justify-center items-start min-h-screen">
    <div class="container mt-20 mx-auto p-4" style="max-width: 800px;">
        <h1 class="text-2xl font-bold text-center mb-20">{{ $user->nickname }}さんのマイページ</h1>
        <div class="absolute top-4 right-4">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edicourse Media Top
                </a>
            </div>

        <div class="mt-4">
            <a href="{{ route('mypage.edit') }}" class="text-blue-500 hover:underline">設定／登録情報の変更</a>
        </div>

        <div class="mt-4">
            <a href="{{ route('articles.create') }}" class="text-blue-500 hover:underline">マイ記事を作成する</a>
        </div>

       
        <h2 class="text-xl font-bold mt-6">マイ記事</h2>
        @if($articles->isEmpty())
            <p>まだ記事がありません。</p>
        @else
            <ul>
                @foreach($articles as $article)
                    <li class="mt-2">
                        <a href="{{ route('articles.show', $article->id) }}" class="text-blue-500 hover:underline">{{ $article->title }}</a>
                        @if(Auth::id() === $article->user_id)
                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 ml-2 hover:underline" onclick="return confirmDelete()">削除</button>
                </form>
            @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-20 text-center">
    <form action="{{ route('user.delete') }}" method="POST" onsubmit="return confirmDeleteAccount()">
        @csrf
        <button type="submit" class="text-red-500 hover:underline">登録情報を削除して退会する</button>
    </form>
</div>
    </div>
</body>
</html>

<script>
    function confirmDelete() {
        return confirm('本当に削除しますか？');
    }

    function confirmDeleteAccount() {
            return confirm('作成した記事とユーザー情報がすべて削除されます。本当に退会しますか？');
        }
</script>