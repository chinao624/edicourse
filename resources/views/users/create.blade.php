<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Create User</title>
</head>
<body>
    <div class="container mx-auto p-4">
    <div class="flex justify-end mb-4">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">登録済みの方はログイン</a>
        </div>

        <h1 class="text-2xl font-bold mb-4">学生エディター登録</h1>
        <form action="{{ route('users.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nickname">ユーザーID（表示用）</label>
                <input name="nickname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nickname" type="text"  value="{{ old('nickname') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">パスワード*英数字８文字以上</label>
                <input name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">氏名</label>
                <input name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" value="{{ old('name') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name_kana">氏名（カナ）</label>
                <input name="name_kana" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name_kana" type="text" value="{{ old('name_kana') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">メールアドレス</label>
                <input name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="school">所属の学校</label>
                <select name="school" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="school" required>
                   <option value="">学校種別を選んでください</option>
                    <option value="Middle"{{ old('school') == 'Middle' ? 'selected' : '' }}>中学生</option>
                    <option value="High"{{ old('school') == 'High' ? 'selected' : '' }}>高校生・高専</option>
                    <option value="College"{{ old('school') == 'College' ? 'selected' : '' }}>大学生・大学院生</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="pref">都道府県</label>
                <select name="pref" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="pref" required>
                    <option value="">都道府県を選んでください</option>
                    @foreach(['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'] as $pref)
                        <option value="{{ $pref }}"{{ old('pref') == $pref ? 'selected' : '' }}>{{ $pref }}</option>
                    @endforeach
                </select>
            </div>

            <!-- プロフィールテーブル分 -->

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="school_name">学校名 ※任意</label>
                <input name="school_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="school_name" type="text" value="{{ old('school_name') }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="birth_year">生年月日</label>
                <div class="flex">
                    <select name="birth_year" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" id="birth_year">
                        <option value="">年</option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select name="birth_month" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" id="birth_month">
                        <option value="">月</option>
                        @for ($month = 1; $month <= 12; $month++)
                            <option value="{{ $month }}" {{ old('birth_month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                        @endfor
                    </select>
                    <select name="birth_day" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" id="birth_day">
                        <option value="">日</option>
                        @for ($date = 1; $date <= 31; $date++)
                            <option value="{{ $date }}" {{ old('birth_day') == $date ? 'selected' : '' }}>{{ $date }}</option>
                        @endfor
                    </select>
</div>
</div>

                    <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="introduction">自己紹介</label>
    <textarea name="introduction" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="introduction">{{ old('introduction') }}</textarea>
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="icon">アイコン画像</label>
    <input name="icon" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="icon" type="file">
</div>

<div class="flex justify-end mt-4">
    <button class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
        学生エディター登録
    </button>
</div>
        </form>
    </div>
</body>
</html>