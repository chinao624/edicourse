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

            <!-- Fabric.jsを使って画像編集するためのキャンバスをここに追加 -->
            <canvas id="canvas" width="800" height="600"></canvas>

            <!-- 描画モード選択ボタン -->
            <div class="mt-4">
                <button id="draw-mode" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">描画モード</button>
                <button id="text-mode" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">テキストモード</button>
            </div>

            <!-- レビュー用のフォーム -->
            <form action="{{ route('reviewer.submit-review', $review->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700">レビュー</label>
                    <textarea id="review" name="review" rows="4" class="mt-1 block w-full border-gray-300 shadow-sm"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    レビューを送信
                </button>
            </form>
        </div>
    </div>

    <!-- 正しいFabric.jsのCDNリンクを追加 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOMContentLoaded event fired'); // デバッグログ

            const canvas = new fabric.Canvas('canvas', {
                isDrawingMode: false,
                freeDrawingBrush: {
                    color: 'red',
                    width: 5
                }
            });

            // スクリーンショット画像をキャンバスに追加
            const screenshotUrl = "{{ asset('storage/' . $article->screenshot_path) }}";
            console.log('Screenshot URL:', screenshotUrl); // デバッグログ
            if ("{{ $article->screenshot_path }}") {
                fabric.Image.fromURL(screenshotUrl, function(img) {
                    console.log('Image loaded'); // デバッグログ
                    img.set({
                        left: 0,
                        top: 0,
                        scaleX: canvas.width / img.width,
                        scaleY: canvas.height / img.height,
                        selectable: false // 画像の選択を無効にする
                    });
                    canvas.add(img);
                    canvas.sendToBack(img); // 背景として設定
                }, {
                    crossOrigin: 'anonymous' // CORS設定
                });
            } else {
                console.log('No screenshot path available'); // デバッグログ
            }

            // 描画モードの切り替え
            document.getElementById('draw-mode').addEventListener('click', () => {
                canvas.isDrawingMode = !canvas.isDrawingMode;
                canvas.selection = !canvas.isDrawingMode; // オブジェクトの選択を無効化
                if (canvas.isDrawingMode) {
                    document.getElementById('draw-mode').innerText = '描画モード終了';
                } else {
                    document.getElementById('draw-mode').innerText = '描画モード';
                }
            });

            // テキストモード
            document.getElementById('text-mode').addEventListener('click', () => {
                const text = new fabric.IText('ここにテキスト', {
                    left: 50,
                    top: 50,
                    fill: 'red'
                });
                canvas.add(text);
                canvas.setActiveObject(text);
                text.enterEditing();
                text.selectAll();
            });
        });
    </script>
</body>
</html>
