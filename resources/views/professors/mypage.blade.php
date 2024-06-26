<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Professor Mypage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-200">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-light text-gray-700 mb-8 text-center">Professor Dashboard</h1>
            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('status') }}</p>
                </div>
            @endif
            <div class="mb-6">
                <h2 class="text-xl font-medium text-gray-700 mb-2">Welcome, {{ $professor->name }}</h2>
                <p class="text-gray-600">Email: {{ $professor->email }}</p>
            </div>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Your Courses</h3>
                <ul class="list-disc list-inside text-gray-600">
                    <!-- ここにProfessorの担当コースをループで表示 -->
                    <li>Course 1</li>
                    <li>Course 2</li>
                    <li>Course 3</li>
                </ul>
            </div>
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('professor.edit_profile') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md">
                    プロフィール編集
                </a>
                <form action="{{ route('professor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 transition duration-300">ログアウト</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>