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

        .canvas-container {
        max-width: 100%;
        max-height: 80vh;
        overflow: auto;
        margin-bottom: 20px;
    }
    #canvas {
        border: 1px solid #ccc;
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
             <div class="canvas-container">
            <canvas id="canvas" ></canvas>
             </div>

            <!-- 描画モード選択ボタン -->
            <div class="mt-8 mb-8">
            <button id="draw-mode" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">描画モード</button>
    <button id="erase-mode" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-full">消しゴムモード</button>
    <button id="text-mode" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">テキストモード</button>
    <button id="delete-selected" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full">選択項目を削除</button>
    <button id="zoom-in" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">拡大</button>
    <button id="zoom-out" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">縮小</button>
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

    <!-- 最新のFabric.jsのCDNリンクを追加 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
let canvas, zoomLevel = 1;
let isDrawingMode = false;
let isErasing = false;

document.addEventListener('DOMContentLoaded', () => {
    canvas = new fabric.Canvas('canvas', {
        isDrawingMode: false,
        freeDrawingBrush: new fabric.PencilBrush(canvas)
    });

    canvas.freeDrawingBrush.color = 'red';
    canvas.freeDrawingBrush.width = 10; 

    const screenshotUrl = "{{ asset('storage/' . $article->screenshot_path) }}";
    if ("{{ $article->screenshot_path }}") {
        fabric.Image.fromURL(screenshotUrl, function(img) {
            const containerWidth = document.querySelector('.canvas-container').offsetWidth;
            const scale = containerWidth / img.width;
            
            canvas.setWidth(containerWidth);
            canvas.setHeight(img.height * scale);
            
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                scaleX: scale,
                scaleY: scale
            });
        }, {crossOrigin: 'anonymous'});
    }

    // ズーム機能
    document.getElementById('zoom-in').addEventListener('click', () => zoom(1.1));
    document.getElementById('zoom-out').addEventListener('click', () => zoom(0.9));


// 描画モードの切り替え
document.getElementById('draw-mode').addEventListener('click', () => {
    isDrawingMode = !isDrawingMode;
    isEraserMode = false;
    canvas.isDrawingMode = isDrawingMode;
    if (isDrawingMode) {
        canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
        canvas.freeDrawingBrush.color = 'red';
        canvas.freeDrawingBrush.width = 10;
        document.getElementById('draw-mode').innerText = '描画モード終了';
    } else {
        document.getElementById('draw-mode').innerText = '描画モード';
    }
    canvas.renderAll();
});

// 消しゴムモード
document.getElementById('erase-mode').addEventListener('click', () => {
    isEraserMode = true;
    isDrawingMode = false;
    canvas.isDrawingMode = false;
    document.getElementById('draw-mode').innerText = '描画モード';
    canvas.renderAll();
});

// マウスイベントの処理
canvas.on('mouse:down', startErasing);
canvas.on('mouse:move', eraseObjects);
canvas.on('mouse:up', stopErasing);

let isErasing = false;

function startErasing(event) {
    if (!isEraserMode) return;
    isErasing = true;
}

function eraseObjects(event) {
    if (!isErasing || !isEraserMode) return;
    const pointer = canvas.getPointer(event.e);
    const objects = canvas.getObjects();
    objects.forEach(obj => {
        if (obj.containsPoint(pointer)) {
            canvas.remove(obj);
        }
    });
    canvas.renderAll();
}

function stopErasing() {
    isErasing = false;
}
    // テキストモード
    document.getElementById('text-mode').addEventListener('click', () => {
        canvas.on('mouse:down', function(options) {
        const text = new fabric.IText('テキストを入力', {
            left: options.pointer.x,
            top: options.pointer.y,
            fill: 'red',
            fontSize: 20
        });
        canvas.add(text);
        canvas.setActiveObject(text);
        text.enterEditing();
        text.selectAll();
        canvas.off('mouse:down');
    });
});

   // テキスト選択項目を削除
   document.getElementById('delete-selected').addEventListener('click', () => {
        const activeObject = canvas.getActiveObject();
        if (activeObject) {
            canvas.remove(activeObject);
            canvas.renderAll();
        }
    });

    function zoom(factor) {
        zoomLevel *= factor;
        canvas.setZoom(zoomLevel);
        canvas.setWidth(canvas.width * factor);
        canvas.setHeight(canvas.height * factor);
        canvas.renderAll();
    }

    // オブジェクトの選択を有効にする
    canvas.selection = true;
});
</script>
</body>
</html>
