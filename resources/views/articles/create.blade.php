<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>記事作成</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto p-8">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-light text-gray-800 tracking-wide">記事作成</h1>
            <div class="space-x-4">
                <a href="{{ route('mypage') }}" class="text-indigo-600 bg-white border border-indigo-600 hover:bg-indigo-50 px-6 py-2 rounded-full transition duration-300 ease-in-out text-sm font-medium">
                    マイページに戻る
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 px-6 py-2 rounded-full transition duration-300 ease-in-out text-sm font-medium">
                        ログアウト
                    </button>
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-medium">入力エラー</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-medium">成功</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('articles.preview') }}" method="POST" class="bg-white shadow-xl rounded-lg px-8 pt-8 pb-10 mb-4" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="title">タイトル</label>
                <input name="title" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="title" type="text" value="{{ old('title') }}" required>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>タイトルは20-30文字程度で、何のことを書いたのかが伝わるように簡潔に書きましょう。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="mainimg">ヘッダー画像</label>
                <input name="mainimg" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="mainimg" type="file" required>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>横位置の画像が必要です。最も印象的な記事の意図にマッチする画像を選びましょう。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>
            
            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="lead">リード</label>
                <textarea name="lead" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="lead" rows="3" required>{{ old('lead') }}</textarea>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>リードは、この記事で何を知らせたいのか、「どこで」「いつ」「だれが」「何をする」「何が行われる」など肝になる部分を明記しながら、400文字程度でまとめましょう。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="img1">画像1</label>
                <input name="img1" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="img1" type="file" required>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>必ず伝えたいと思ったことに即した１枚を選びます。シェアしたい印象的なシーンやこの記事で言いたいことの特徴を表すものなど。横位置、縦位置、スクエアの画像を使用できます。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="cap1">キャプション1</label>
                <textarea name="cap1" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="cap1" rows="2" required>{{ old('cap1') }}</textarea>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像１の説明となるような文章を150-300文字程度で記入しましょう。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="img2">画像2</label>
                <input name="img2" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="img2" type="file" required>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像１と合わせて、伝えておきたいと思った内容を表す１枚を選びます。縦位置、横位置、スクエアの画像を使用できます。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="cap2">キャプション2</label>
                <textarea name="cap2" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="cap2" rows="2" required>{{ old('cap2') }}</textarea>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像２の説明となるような文章を150-300文字程度で記入しましょう。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="closing">クロージング</label>
                <textarea name="closing" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" id="closing" rows="3" required>{{ old('closing') }}</textarea>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>最後のまとめ部分です。自分の意見や感想を必ず入れるようにしましょう。なぜこの記事を書きたいと思ったのかが伝わるといいですね。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="mb-6" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="genre">ジャンル</label>
                <select name="genre" id="genre" class="shadow-sm appearance-none border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" required>
                    <option value="" disabled selected>記事ジャンル・テーマを選択してください</option>
                    <option value="trend" {{ old('genre') == 'trend' ? 'selected' : '' }}>トレンド</option>
                    <option value="spot" {{ old('genre') == 'spot' ? 'selected' : '' }}>スポット</option>
                    <option value="travel" {{ old('genre') == 'travel' ? 'selected' : '' }}>トラベル</option>
                    <option value="sports" {{ old('genre') == 'sports' ? 'selected' : '' }}>スポーツ</option>
                    <option value="event" {{ old('genre') == 'event' ? 'selected' : '' }}>イベント</option>
                    <option value="interview" {{ old('genre') == 'interview' ? 'selected' : '' }}>インタビュー</option>
                    <option value="opinion" {{ old('genre') == 'opinion' ? 'selected' : '' }}>オピニオン</option>
                    <option value="others" {{ old('genre') == 'others' ? 'selected' : '' }}>その他</option>
                </select>
                <div class="mt-1 relative">
                    <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>今回の記事のテーマやジャンルとして一番近いものを選んでください。</span>
                    <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-indigo-500 ml-1">[?]</span>
                </div>
            </div>

            <div class="flex justify-end mt-10">
                <button class="bg-gradient-to-r from-teal-400 to-blue-500 hover:from-teal-500 hover:to-blue-600 text-white font-medium py-2 px-6 rounded-full focus:outline-none focus:shadow-outline transition duration-300 ease-in-out text-sm" type="submit">
                    記事イメージを確認
                </button>
            </div>
        </form>
    </div>
</body>
</html>