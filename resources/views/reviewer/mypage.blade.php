<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>レビュワーマイページ</title>
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

           
            <div class="text-center pt-6 border-t">
                <form action="{{ route('reviewer.delete') }}" method="POST" onsubmit="return confirmDeleteAccount()">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300 text-sm">登録情報を削除して退会する</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteAccount() {
            return confirm('作成したレビューとユーザー情報がすべて削除されます。本当に退会しますか？');
        }
    </script>
</body>
</html>