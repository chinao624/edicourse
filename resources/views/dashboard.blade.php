<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Edicourse Media Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto p-6 max-w-6xl">
        <header class="flex flex-col md:flex-row justify-between items-center mb-8">
            <a href="{{ route('dashboard') }}" class="text-4xl font-josefin font-bold text-[#ff6347] hover:text-[#ff7f50] transition duration-300 mb-4 md:mb-0">Edicourse Media</a>
            <div class="flex flex-wrap justify-center md:justify-end space-x-4">
                @if(Auth::guard('web')->check())
                    <a href="{{ route('mypage') }}" class="bg-[#6495ed] hover:bg-[#4169e1] text-white font-normal py-2 px-4 rounded-full transition duration-300">
                        マイページ
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-[#ff6347] hover:bg-[#ff4500] text-white font-normal py-2 px-4 rounded-full transition duration-300" type="submit">
                            ログアウト
                        </button>
                    </form>
                @elseif(Auth::guard('professor')->check())
                    <a href="{{ route('professor.mypage') }}" class="bg-[#6495ed] hover:bg-[#4169e1] text-white font-normal py-2 px-4 rounded-full transition duration-300">
                        オブザーバーマイページ
                    </a>
                    <form action="{{ route('professor.logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-[#ff6347] hover:bg-[#ff4500] text-white font-normal py-2 px-4 rounded-full transition duration-300" type="submit">
                            ログアウト
                        </button>
                    </form>
                @endif
            </div>
        </header>

        @if(Auth::guard('web')->check())
            <p class="text-gray-600 mb-6">ようこそ、{{ Auth::user()->nickname }}さん!</p>
        @elseif(Auth::guard('professor')->check())
            <p class="text-gray-600 mb-6">ようこそ、{{ Auth::guard('professor')->user()->name }}さん!</p>
        @endif

        <nav class="flex flex-wrap justify-center bg-white shadow-md rounded-full p-2 mb-8">
            @foreach ($genres as $genreItem)
                <a href="{{ route('articles.genre', ['genre' => urlencode($genreItem)]) }}" class="text-[#6495ed] hover:bg-[#f0f8ff] px-4 py-2 rounded-full transition duration-300">{{ $genreItem }}</a>
            @endforeach
        </nav>

        <main>
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ isset($decodedGenre) ? $decodedGenre . 'の記事' : 'すべての記事' }}</h2>
            @if ($articles->isEmpty())
                <p class="text-gray-600">まだ記事がありません。</p>
            @else
                <div class="space-y-6">
                    @foreach ($articles as $article)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <div class="flex items-center p-4">
                                @if ($article->mainimg)
                                    <img src="{{ asset('storage/' . $article->mainimg) }}" alt="メイン画像" class="w-24 h-24 object-cover rounded-md mr-4">
                                @endif
                                <div>
                                    <a href="{{ route('articles.show', $article->id) }}" class="text-lg font-semibold text-[#4682b4] hover:text-[#4169e1] transition duration-300">{{ $article->title }}</a>
                                    <p class="text-sm text-gray-600 mt-2">
                                        {{ $article->user->nickname ?? 'Unknown' }} - 
                                        {{ $article->user->pref ?? '都道府県情報なし' }}在住
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</body>
</html>