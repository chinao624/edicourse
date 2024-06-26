<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>
<body>
    <div class="container mx-auto p-4">
    <div class="flex justify-end mb-4">
            <a href="{{ route('professors.create') }}" class="text-blue-500 hover:underline">登録がお済みでない方はこちら</a>
        </div>
        <h1 class="text-2xl font-bold mb-4">オブザーバーログイン</h1>
        @if($errors->has('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif
        <form action="{{ route('professor.login') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">メールアドレス</label>
                <input name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">パスワード</label>
                <input name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-[#4682b4] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    ログイン
                </button>
            </div>
        </form>
    </div>
</body>
</html>
