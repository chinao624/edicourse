<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー作成</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        textarea {
            border-radius: 8px; 
            border: 2px solid #3490dc; 
            padding: 10px; 
            transition: border-color 0.3s; 
        }
        textarea:focus {
            border-color: #6574cd; 
            outline: none; /* デフォルトのフォーカススタイルを削除 */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen text-gray-800">
    <div class="container max-w-4xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-light text-gray-700 mb-6 pb-2 border-b">
                {{ $article->title }}
            </h1>
            
            @if($article->screenshot_path)
                <div class="mb-6">
                    <a href="{{ asset('storage/' . $article->screenshot_path) }}" target="_blank" class="text-teal-600 hover:text-teal-800 transition duration-300">スクリーンショットを見る</a>
                </div>
            @endif

            <!-- Fabric.jsを使って画像編集するためのキャンバスをここに追加 -->
            <canvas id="canvas"></canvas>

            <!-- レビュー用のフォーム -->
            <form action="{{ route('reviewer.submit-review', $review->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700">レビュー</label>
                    <textarea id="review" name="review" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    レビューを送信
                </button>
            </form>
        </div>
    </div>

    <!-- Fabric.jsのスクリプトを追加 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.1/fabric.min.js"></script>
    <script>
        // Fabric.jsを使って画像編集機能を実装
        const canvas = new fabric.Canvas('canvas', {
            width: 800,
            height: 600,
            backgroundColor: '#f3f3f3'
        });

        // スクリーンショット画像をキャンバスに追加
        const screenshotUrl = "@{{ asset('storage/' . $article->screenshot_path) }}";
        fabric.Image.fromURL(screenshotUrl, function(img) {
            img.set({
                left: 100,
                top: 100,
                angle: 0,
                padding: 10,
                cornersize: 10
            });
            canvas.add(img);
        });
    </script>
</body>
</html>
