<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Edicourse Media</title>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background-color: #f8f8f8;
        }
        .article-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.3s ease;
        }
        .article-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen">
    <header class="bg-white border-b border-gray-200 mt-4">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-4xl font-bold text-[#ff6347] font-josefin">Edicourse Media</a>
                <nav>
                    @if(Auth::guard('web')->check())
                        <a href="{{ route('mypage') }}" class="text-blue-600 hover:text-blue-800 mr-4">マイページ</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="text-gray-600 hover:text-gray-800" type="submit">ログアウト</button>
                        </form>
                    @elseif(Auth::guard('professor')->check())
                        <a href="{{ route('professor.mypage') }}" class="text-blue-600 hover:text-blue-800 mr-4">オブザーバーマイページ</a>
                        <form action="{{ route('professor.logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="text-gray-600 hover:text-gray-800" type="submit">ログアウト</button>
                        </form>
                    @elseif(Auth::guard('reviewer')->check())
                        <a href="{{ route('reviewer.mypage') }}" class="text-blue-600 hover:text-blue-800 mr-4">レビュワーマイページ</a>
                        <form action="{{ route('reviewer.logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="text-gray-600 hover:text-gray-800" type="submit">ログアウト</button>
                        </form>
                    @endif
                </nav>
            </div>
            <div class="flex flex-wrap gap-2 mt-2 mb-2">
                @foreach ($genres as $genreItem)
                    <a href="{{ route('articles.genre', ['genre' => urlencode($genreItem)]) }}" class="text-sm bg-gray-200 text-gray-700 px-3 py-1 rounded-full hover:bg-gray-300 transition duration-300">{{ $genreItem }}</a>
                @endforeach
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <p class="text-gray-600 mb-6">ようこそ、
            @if(Auth::guard('web')->check())
                {{ Auth::guard('web')->user()->nickname }}
            @elseif(Auth::guard('professor')->check())
                {{ Auth::guard('professor')->user()->name }}
            @elseif(Auth::guard('reviewer')->check())
                {{ Auth::guard('reviewer')->user()->name }}
            @endif
            さん!
        </p>

        @if ($articles->where('status', 'published')->isNotEmpty())
            @php
                $latestArticle = $articles->where('status', 'published')->first();
            @endphp
            <section class="mb-12">
                <h2 class="text-2xl font-normal text-gray-800 mb-4">新着記事</h2>
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                    <div class="flex flex-col md:flex-row">
                        @if ($latestArticle->mainimg)
                            <img src="{{ asset('storage/' . $latestArticle->mainimg) }}" alt="メイン画像" class="w-full md:w-1/2 h-64 object-cover mb-4 md:mb-0 md:mr-6 rounded-lg">
                        @endif
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-2">{{ $latestArticle->updated_at->format('Y年m月d日') }}</p>
                            <a href="{{ route('articles.show', $latestArticle->id) }}" class="text-2xl font-bold text-gray-800 hover:text-blue-600 mb-3 block">{{ $latestArticle->title }}</a>
                            <p class="text-gray-600 mb-4">{{ Str::limit($latestArticle->lead, 200) }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $latestArticle->user->nickname }} - 
                                {{ $latestArticle->user->pref }}
                                @if($latestArticle->user->school)
                                    - 
                                    @switch($latestArticle->user->school)
                                        @case('Middle')
                                            中学生
                                            @break
                                        @case('High')
                                            高校生・高専
                                            @break
                                        @case('college')
                                            大学・大学院生
                                            @break
                                        @default
                                            {{ $latestArticle->user->school }}
                                    @endswitch
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section>
    <h2 class="text-2xl font-normal text-gray-800 mb-6">
        @if(isset($decodedGenre))
            {{ $decodedGenre }}の記事
        @else
            すべての記事
        @endif
    </h2>
    @if ($articles->where('status', 'published')->count() > 1)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($articles->where('status', 'published')->skip(1) as $article)
                        <div class="article-card rounded-lg overflow-hidden">
                            @if ($article->mainimg)
                                <img src="{{ asset('storage/' . $article->mainimg) }}" alt="メイン画像" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <p class="text-sm text-gray-500 mb-2">{{ $article->updated_at->format('Y年m月d日') }}</p>
                                <a href="{{ route('articles.show', $article->id) }}" class="text-xl font-bold text-gray-800 hover:text-blue-600 mb-2 block">{{ $article->title }}</a>
                                <p class="text-gray-600 mb-4 text-sm">{{ Str::limit($article->lead, 100) }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $article->user->nickname }} - 
                                    {{ $article->user->pref }}
                                    @if($article->user->school)
                                        - 
                                        @switch($article->user->school)
                                            @case('Middle')
                                                中学生
                                                @break
                                            @case('High')
                                                高校生・高専
                                                @break
                                            @case('college')
                                                大学・大学院生
                                                @break
                                            @default
                                                {{ $article->user->school }}
                                        @endswitch
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                    </div>
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <p class="text-gray-600">
            @if(isset($decodedGenre))
                {{ $decodedGenre }}の記事はまだありません。
            @else
                追加の記事はありません。
            @endif
        </p>
    @endif
</section>
    </main>
</body>
</html>