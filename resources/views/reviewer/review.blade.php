<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー作成</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
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

<script>
    window.isReviewer = JSON.parse(`{!! json_encode(Auth::guard('reviewer')->check()) !!}`);
    window.isUser = JSON.parse(`{!! json_encode(Auth::guard('web')->check()) !!}`);
</script>




</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen text-gray-800">
<header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">{{ Auth::guard('reviewer')->check() ? 'レビュー作成' : '記事レビュー' }}</h1>
            <a href="{{ Auth::guard('reviewer')->check() ? route('reviewer.mypage') : route('mypage') }}" class="text-blue-600 hover:text-blue-800 transition duration-300">
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

             @if(Auth::guard('reviewer')->check())
             <!-- 描画モード選択ボタン -->
             <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <div class="flex gap-2">
                        <button id="draw-mode" class="flex items-center font-bold py-2 px-4 rounded transition duration-300 ease-in-out bg-blue-500 text-white hover:bg-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            描画
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
@endif
</main>

@if(Auth::guard('reviewer')->check())
            <!-- レビュー用のフォーム -->
            <form id="reviewForm">
    @csrf
    <div class="mb-4">
        <label for="review-comment" class="block text-sm font-medium text-gray-700 mt-8">レビューコメント</label>
        <textarea id="review-comment" name="review" rows="4" class="mt-1 block w-full border-gray-300 shadow-sm">{{ $reviewComment ?? '' }}</textarea>
    </div>
    <div class="flex space-x-4">
        <button type="button" id="save-draft" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
            下書き保存
        </button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
            レビューを提出
        </button>
    </div>
</form>

@elseif(Auth::guard('web')->check())
    <!-- ユーザー用の表示 -->
    <div class="mb-4">
        <label for="review-comment" class="block text-sm font-medium text-gray-700 mt-8">レビューコメント</label>
        <textarea id="review-comment" name="review" rows="4" class="mt-1 block w-full border-gray-300 shadow-sm" readonly>{{ $reviewComment ?? '' }}</textarea>
    </div>
    <div class="flex justify-center mt-8">
        <button id="send-thanks" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
            レビュワーに感謝
        </button>
    </div>
    @else
    <p>このページを表示する権限がありません。</p>
@endif
    </div>

    <div id="raw-draft-data" style="display:none;">{{ $article->draft ?? json_encode(['message' => 'No draft data']) }}</div>
<div id="raw-review-comment" style="display:none;">{{ $article->review_comment ?? '' }}</div>

<!-- 最新のFabric.jsのCDNリンクを追加 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded');
    console.log('isUser:', window.isUser);
    console.log('isReviewer:', window.isReviewer);

    const rawDraftDataElement = document.getElementById('raw-draft-data');
    const rawDraftData = rawDraftDataElement ? rawDraftDataElement.textContent : null;
    console.log('Raw draft data:', rawDraftData);

    const reviewCommentElement = document.getElementById('review-comment');
    const rawReviewCommentElement = document.getElementById('raw-review-comment');
    const savedReviewComment = rawReviewCommentElement ? rawReviewCommentElement.textContent : '';
    
    if (reviewCommentElement && savedReviewComment) {
        reviewCommentElement.value = savedReviewComment;
    }
    console.log('Loaded review comment:', savedReviewComment);

    let canvas, zoomLevel = 1;
    let isDrawingMode = false;

    canvas = new fabric.Canvas('canvas', {
        isDrawingMode: false
    });

    canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
    canvas.freeDrawingBrush.color = 'red';
    canvas.freeDrawingBrush.width = 2; // 描画線の太さを2pxに変更

    let draftData;
    try {
        draftData = rawDraftData && rawDraftData !== 'No draft data' ? JSON.parse(rawDraftData) : null;
        console.log('Parsed draft data:', draftData);
    } catch (error) {
        console.error('Error parsing draft data:', error);
        console.log('Problematic draft data:', rawDraftData);
        draftData = null;
        alert('下書きデータの読み込みに失敗しました。新しいレビューを始めます。');
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
    const zoomInButton = document.getElementById('zoom-in');
    const zoomOutButton = document.getElementById('zoom-out');
    
    if (zoomInButton && zoomOutButton) {
        zoomInButton.addEventListener('click', () => zoom(1.1));
        zoomOutButton.addEventListener('click', () => zoom(0.9));
    }

    // レビュワー用の機能を追加
    if (window.isReviewer) {
        // 描画モードの切り替え
        document.getElementById('draw-mode').addEventListener('click', () => {
            isDrawingMode = !isDrawingMode;
            canvas.isDrawingMode = isDrawingMode;
            if (isDrawingMode) {
                canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
                canvas.freeDrawingBrush.color = 'red';
                canvas.freeDrawingBrush.width = 2;
                document.getElementById('draw-mode').innerText = '描画モード終了';
            } else {
                document.getElementById('draw-mode').innerText = '描画モード';
            }
            canvas.renderAll();
        });

        // テキストモード
        document.getElementById('text-mode').addEventListener('click', () => {
            canvas.on('mouse:down', function(options) {
                const maxWidth = canvas.width * 0.3;
                const text = new fabric.IText('テキストを入力', {
                    left: options.pointer.x,
                    top: options.pointer.y,
                    fill: 'red',
                    fontSize: 16,
                    fontWeight: 'bold', // フォントを太字に
            backgroundColor: 'rgba(255, 255, 255, 0.7)', // 半透明の白色背景追加
            padding: 5, // テキストの周りにパディング追加
            breakWords: true, // 単語の途中でも折り返す
            width: maxWidth, // テキストボックスの幅を制限
            splitByGrapheme: true 
                });
                canvas.add(text);
                canvas.setActiveObject(text);
                text.enterEditing();
                text.selectAll();
                canvas.off('mouse:down');
            });
        });

        // 選択項目を削除
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
            const reviewComment = document.getElementById('review-comment').value;
            
            fetch('{{ route("reviewer.save-draft", $review->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    draft: draftData,
                    review_comment: reviewComment
                })
            }).then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                    });
                }
                return response.json();
            }).then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert('下書きとレビューコメントが保存されました。');
                } else {
                    throw new Error(data.message || '下書きとレビューコメントの保存に失敗しました。');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました: ' + error.message);
            });
        });

        // レビュー返却メソッド
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const feedback = document.getElementById('review-comment').value;
            const draftData = JSON.stringify(canvas.toJSON());

            fetch(`{{ route('reviewer.submit-review', $review->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    feedback: feedback,
                    draft: draftData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("reviewer.mypage") }}';
                } else {
                    alert('レビュー返却に失敗しました: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('レビュー返却中にエラーが発生しました。');
            });
        });
    }

    // ユーザー用のアクション
    else if (window.isUser) {
        // ユーザー用の機能を初期化
        const sendThanksButton = document.getElementById('send-thanks');
        console.log('sendThanksButton:', sendThanksButton);
        
        if (sendThanksButton) {
            sendThanksButton.addEventListener('click', function() {
                fetch('{{ route("articles.acknowledgeReview", $review->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('レビュワーに感謝の意を示しました。');
                        window.location.href = '{{ route("mypage") }}';
                    } else {
                        alert('感謝の意を示すことに失敗しました: ' + (data.message || '不明なエラー'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('感謝の意を示す際にエラーが発生しました。');
                });
            });
        } else {
            console.error('Send Thanks button not found in DOM');
        }
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
</script>

</body>
</html>

