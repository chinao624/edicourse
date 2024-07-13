<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Observer Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-purple-100">
<div class="absolute right-10 top-10 space-x-4 z-10">
    <a href="{{ url('/') }}" class="px-3 py-1.5 bg-[#ffa07a] text-white text-sm font-semibold rounded-full hover:bg-[#fa8072] transition duration-300 shadow-md">Edicourse TOPへ</a>
  </div>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-light text-gray-700 mb-8 text-center">Observer Login</h1>
            @if($errors->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ $errors->first('error') }}</p>
                </div>
            @endif
            <form action="{{ route('professor.login') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-medium mb-2" for="email">メールアドレス</label>
                    <input name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300" id="email" type="email" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-medium mb-2" for="password">パスワード</label>
                    <input name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300" id="password" type="password" required>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-500 shadow-md" type="submit">
                        ログイン
                    </button>
                    <a href="{{ route('professors.create') }}" class="text-sm text-purple-600 hover:text-purple-800 transition duration-300">アカウント登録</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>