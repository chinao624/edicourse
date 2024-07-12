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
<header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">レビュー作成</h1>
            <a href="{{ route('reviewer.mypage') }}" class="text-blue-600 hover:text-blue-800 transition duration-300">
                マイページに戻る
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="container max-w-4xl mx-auto p-8">
        <main class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-light text-gray-700 mb-6 pb-2 border-b">
                {{ $article->title }}
            </h1>

            <!-- Fabric.jsを使って画像編集するためのキャンバスをここに追加 -->
             <div class="canvas-container">
            <canvas id="canvas"></canvas>
             </div>

            <!-- 描画モード選択ボタン -->
            <div class="flex flex-wrap justify-center gap-4 mt-8">
    <div class="flex gap-2">
        <button id="draw-mode" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out bg-blue-500 text-white hover:bg-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            描画
        </button>
        <button id="erase-mode" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out bg-gray-500 text-white hover:bg-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L11.414 12l3.293 3.293a1 1 0 01-1.414 1.414L10 13.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 12 5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            消しゴム
        </button>
    </div>
    <div class="flex gap-2">
        <button id="text-mode" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out bg-teal-500 text-white hover:bg-teal-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
            </svg>
            テキスト
        </button>
        <button id="delete-selected" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out bg-red-500 text-white hover:bg-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            削除
        </button>
    </div>
    <div class="flex gap-2">
        <button id="zoom-in" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </button>
        <button id="zoom-out" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
</main>


            <!-- レビュー用のフォーム -->
            <form action="{{ route('reviewer.submit-review', $review->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700 mt-8">レビューコメント</label>
                    <textarea id="review" name="review" rows="4" class="mt-1 block w-full border-gray-300 shadow-sm"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    レビューを送信
                </button>
                <button type="button" id="save-draft" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    下書き保存
                </button>
            </form>
        </div>
    </div>

    <div id="raw-draft-data" style="display:none;">{{ $draftData ?? 'No draft data' }}</div>

<!-- 最新のFabric.jsのCDNリンクを追加 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded event fired');

    const rawDraftDataElement = document.getElementById('raw-draft-data');
    const rawDraftData = rawDraftDataElement ? rawDraftDataElement.textContent : null;
    console.log('Raw draft data:', rawDraftData);

    let canvas, zoomLevel = 1;
    let isDrawingMode = false;
    let isEraserMode = false;
    let isErasing = false;

    canvas = new fabric.Canvas('canvas', {
        isDrawingMode: false
    });

    canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
    canvas.freeDrawingBrush.color = 'red';
    canvas.freeDrawingBrush.width = 10;


    let draftData;
    try {
        draftData = rawDraftData ? JSON.parse(rawDraftData) : null;
        console.log('Parsed draft data:', draftData);
    } catch (error) {
        console.error('Error parsing draft data:', error);
        console.log('Problematic draft data:', rawDraftData);
        draftData = null;
    }

    if (draftData) {
        canvas.loadFromJSON(draftData, canvas.renderAll.bind(canvas));
    }
    
    const screenshotUrl = "{{ asset('storage/' . $article->screenshot_path) }}";
if (screenshotUrl) {
    fabric.Image.fromURL(screenshotUrl, function(img) {
        if (!img) {
            console.error('Failed to load screenshot');
            return;
        }
        const containerWidth = document.querySelector('.canvas-container').offsetWidth;
        const scale = containerWidth / img.width;
        
        canvas.setWidth(containerWidth);
        canvas.setHeight(img.height * scale);
        
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
            scaleX: scale,
            scaleY: scale
        });
    }, {crossOrigin: 'anonymous'});
} else {
    console.log('No screenshot URL provided');
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
        document.getElementById('erase-mode').innerText = '消しゴムモード';
        canvas.renderAll();
    });

    // 消しゴムモード
    document.getElementById('erase-mode').addEventListener('click', () => {
        isEraserMode = !isEraserMode;
        isDrawingMode = false;
        canvas.isDrawingMode = false;
        document.getElementById('draw-mode').innerText = '描画モード';
        document.getElementById('erase-mode').innerText = isEraserMode ? '消しゴムモード終了' : '消しゴムモード';
        canvas.renderAll();
    });

    // マウスイベントの処理
    canvas.on('mouse:down', startErasing);
    canvas.on('mouse:move', eraseObjects);
    canvas.on('mouse:up', stopErasing);

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

    // レビューを下書き保存する
    document.getElementById('save-draft').addEventListener('click', () => {
        const draftData = JSON.stringify(canvas.toJSON());
        fetch('{{ route("reviewer.save-draft", $review->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ draft: draftData })
        }).then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        }).then(data => {
            if (data.success) {
                alert('下書きが保存されました。');
            } else {
                alert('下書きの保存に失敗しました。');
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('エラーが発生しました: ' + error.message);
        });
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
