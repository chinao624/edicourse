<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>記事作成</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
    <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">記事作成</h1>

             <!-- バリデーションエラーの表示 -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white bg-red-500 hover:bg-red-700 px-4 py-2 rounded">
                    ログアウト
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('articles.preview') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" enctype="multipart/form-data">
            @csrf
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">タイトル</label>
                <input name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" value="{{ old('title') }}" required>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>タイトルは20-30文字程度で、何のことを書いたのかが伝わるように簡潔に書きましょう。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="mainimg">ヘッダー画像</label>
                <input name="mainimg" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="mainimg" type="file" required>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>横位置の画像が必要です。最も印象的な記事の意図にマッチする画像を選びましょう。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="lead">リード</label>
                <textarea name="lead" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lead" rows="3" value="{{ old('lead') }}"required></textarea>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>リードは、この記事で何を知らせたいのか、「どこで」「いつ」「だれが」「何をする」「何が行われる」など肝になる部分を明記しながら、400文字程度でまとめましょう。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="img1">画像1</label>
                <input name="img1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="img1" type="file" required>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>必ず伝えたいと思ったことに即した１枚を選びます。シェアしたい印象的なシーンやこの記事で言いたいことの特徴を表すものなど。横位置、縦位置、スクエアの画像を使用できます。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cap1">キャプション1</label>
                <textarea name="cap1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cap1" rows="2" required>{{ old('cap1') }}</textarea>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像１の説明となるような文章を150-300文字程度で記入しましょう。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="img2">画像2</label>
                <input name="img2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="img2" type="file" required>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像１と合わせて、伝えておきたいと思った内容を表す１枚を選びます。縦位置、横位置、スクエアの画像を使用できます。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cap2">キャプション2</label>
                <textarea name="cap2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cap2" rows="2" required>{{ old('cap2') }}</textarea>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>画像２の説明となるような文章を150-300文字程度で記入しましょう。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>

            </div>
            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="closing">クロージング</label>
                <textarea name="closing" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="closing" rows="3" required>{{ old('closing') }}</textarea>
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>最後のまとめ部分です。自分の意見や感想を必ず入れるようにしましょう。なぜこの記事を書きたいと思ったのかが伝わるといいですね。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>
            </div>

            <div class="mb-4" x-data="{ tooltip: false }">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="genre">ジャンル</label>
                <select name="genre" id="genre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
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
                <span class="text-gray-500 text-xs" x-show="tooltip" x-cloak>今回の記事のテーマやジャンルとして一番近いものを選んでください。</span>
                <span @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="cursor-pointer text-blue-500">[?]</span>

            </div>
            <div class="flex justify-end mt-4">
                <button class="bg-[#4682b4] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    記事イメージを確認
                </button>
            </div>
        </form>
    </div>
</body>
</html>
