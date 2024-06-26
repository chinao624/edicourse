<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
    <div class="container mx-auto p-4">
    @if(Auth::guard('web')->check())
        <p>ようこそ、 {{ Auth::user()->nickname }}さん!</p>
        <!-- Userのダッシュボード内容 -->
    @elseif(Auth::guard('professor')->check())
        <p>ようこそ、 {{ Auth::guard('professor')->user()->name }}さん!</p>
        <!-- Professorのダッシュボード内容 -->
    @endif

    <div class= "flex items-center justify-between">   
    <a href="{{ route('dashboard') }}" class="text-4xl font-josefin font-bold  text-[#ff6347]  hover:pointer mt-6 mb-4">Edicourse Media</a>
    <div class="flex items-center space-x-4">
    @if(Auth::guard('web')->check())
                <a href="{{ route('mypage') }}" class="bg-[#6495ed] hover:bg-[#b0e0e6] text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    マイページ
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-[#ff6347] hover:bg-[#ffa07a] text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        ログアウト
                    </button>
                </form>
    @elseif(Auth::guard('professor')->check())
    <a href="{{ route('professor.mypage') }}" class="bg-[#6495ed] hover:bg-[#b0e0e6] text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    オブザーバーマイページ
                </a>
                <form action="{{ route('professor.logout') }}" method="POST">
                    @csrf
                    <button class="bg-[#ff6347] hover:bg-[#ffa07a] text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        ログアウト
                    </button>
                </form>
            @endif
            </div>
</div>

<!-- 共通のダッシュボード内容 -->

<div class="mt-4">
            <nav class="flex space-x-4 bg-[#f0f8ff] p-3" >
                @foreach ($genres as $genreItem)
                    <a href="{{ route('articles.genre', ['genre' => urlencode($genreItem)]) }}" class="text-[#6495ed] hover:underline">{{ $genreItem }}</a>
                @endforeach
            </nav>
        </div>
        <div class="mt-6">
            <h2 class="text-xl font-bold">{{ isset($decodedGenre) ? $decodedGenre . 'の記事' : 'すべての記事' }}</h2>
            @if ($articles->isEmpty())
                <p>まだ記事がありません。</p>
            @else
                <ul>
                    @foreach ($articles as $article)
                        <li class="mt-4 flex items-center">
                        @if ($article->mainimg)
                        <img src="{{ asset('storage/' . $article->mainimg) }}" alt="メイン画像" class="w-24 h-12 object-cover mr-8">
                    @endif
                    <div>
                        <a href="{{ route('articles.show', $article->id) }}" class="text-black hover:underline">{{ $article->title }}</a>
                        <p class="text-sm text-gray-600">
                        {{ $article->user->nickname ?? 'Unknown' }} - 
                        {{ $article->user->pref ?? '都道府県情報なし' }}在住
                        </p>
                    </div>
                </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>
