<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <title>{{ $article->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .container {
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .article-header {
            background-color: #f0f0f0;
            border-radius: 8px 8px 0 0;
            padding: 2rem;
        }
        .article-content {
            padding: 2rem;
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4 mt-8 mb-8" style="max-width: 1000px;">
        <div class="article-header">
        <div class="flex justify-between items-center mb-6">
    <div id="genreDisplay" class="bg-blue-200 text-black px-4 py-2 rounded-full text-sm font-semibold">
        {{ $japaneseGenre }}
    </div>
    @if(Auth::check() && Auth::user()->id == $article->user_id)
        <select id="genreInput" class="hidden bg-blue-200 text-black px-4 py-2 rounded-full text-sm font-semibold" style="display: none;">
            @foreach(['trend', 'spot', 'travel', 'sports', 'event', 'interview', 'opinion', 'others'] as $genreOption)
                <option value="{{ $genreOption }}" {{ $article->genre == $genreOption ? 'selected' : '' }}>
                    {{ $genreMapping[$genreOption] ?? ucfirst($genreOption) }}
                </option>
            @endforeach
        </select>
    @endif
    <a href="{{ route('dashboard') }}" class="btn bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">
        記事一覧に戻る
    </a>
</div>

<div id="article-content-for-screenshot">

          <!-- mainimgのセット -->
            <div class="relative mb-8">
                <img id="mainimg" src="{{ asset('storage/' . $article->mainimg) }}" crossorigin="anonymous" alt="{{ $article->title }}" class="w-full object-cover rounded-lg shadow-lg" style="height: 400px;">
                
                @if(Auth::check() && Auth::user()->id == $article->user_id)
                    <input type="file" id="mainimgInput" class="hidden" onchange="updateImage('mainimg', this)">
                @endif
            </div>

            <!-- Titleのセット -->

            <h1 id="title" class="text-4xl font-bold mb-4">{{ $article->title }}</h1>
            @if(Auth::check() && Auth::user()->id == $article->user_id)
                <input type="text" id="titleInput" class="input input-bordered w-full mb-6 text-3xl" value="{{ $article->title }}" style="display: none;">
            @endif

            <!-- オーサー&編集日セット -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                <img src="{{ $article->user->profile->icon ? asset('storage/' . $article->user->profile->icon) : asset('default/user_icon.png') }}" alt="{{ $article->user->nickname }}" class="w-16 h-16 rounded-full mr-4 border-2 border-gray-300">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600">この記事を書いたのは…</h3>
                        <h3 class="text-lg font-semibold">{{ $article->user->nickname }}</h3>
                        <p class="text-sm text-gray-600">
            @switch($article->user->school)
                @case('Middle')
                    中学生
                    @break
                @case('High')
                    高校生・高専
                    @break
                @case('college')
                    大学・大学院生
                    @break
                @default
                    {{ $article->user->school }}
            @endswitch
        </p>
        <p class="text-sm text-gray-600 mt-2">{{ $article->user->profile->introduction }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">{{ $formattedDate }}</p>
                </div>
            </div>
        

        <!-- リード -->
        <div class="article-content">
            <p id="lead" class="mb-8 text-xl leading-relaxed">{{ $article->lead }}</p>
            @if(Auth::check() && Auth::user()->id == $article->user_id)
                <textarea id="leadInput" class="textarea textarea-bordered w-full mb-8 text-xl" style="display: none;">{{ $article->lead }}</textarea>
            @endif
            
            <!-- img1とcapのセット -->
            <div class="flex flex-col md:flex-row mb-12">
                <div class="w-full md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex items-center justify-center bg-gray-100 rounded-lg" style="height: 300px;">
                        <img id="img1" src="{{ asset('storage/' . $article->img1) }}" crossorigin="anonymous" alt="Image 1" class="max-w-full max-h-full object-contain rounded-lg shadow">
                        @if(Auth::check() && Auth::user()->id == $article->user_id)
                            <input type="file" id="img1Input" class="hidden" onchange="updateImage('img1', this)">
                        @endif
                    </div>
                </div>
                <div class="w-full md:w-1/2 pl-0 md:pl-4">
                    <p id="cap1" class="text-base leading-relaxed">{{ $article->cap1 }}</p>
                    @if(Auth::check() && Auth::user()->id == $article->user_id)
                        <textarea id="cap1Input" class="textarea textarea-bordered w-full" style="display: none;">{{ $article->cap1 }}</textarea>
                    @endif
                </div>
            </div>

            <!-- img2とcapのセット -->
            <div class="flex flex-col md:flex-row mb-12">
                <div class="w-full md:w-1/2 order-2 md:order-2 pl-0 md:pl-4 mb-4 md:mb-0">
                    <div class="flex items-center justify-center bg-gray-100 rounded-lg" style="height: 300px;">
                        <img id="img2" src="{{ asset('storage/' . $article->img2) }}" crossorigin="anonymous" alt="Image 2" class="max-w-full max-h-full object-contain rounded-lg shadow">
                        @if(Auth::check() && Auth::user()->id == $article->user_id)
                            <input type="file" id="img2Input" class="hidden" onchange="updateImage('img2', this)">
                        @endif
                    </div>
                </div>
                <div class="w-full md:w-1/2 order-1 md:order-1 pr-0 md:pr-4">
                    <p id="cap2" class="text-base leading-relaxed">{{ $article->cap2 }}</p>
                    @if(Auth::check() && Auth::user()->id == $article->user_id)
                        <textarea id="cap2Input" class="textarea textarea-bordered w-full" style="display: none;">{{ $article->cap2 }}</textarea>
                    @endif
                </div>
            </div>

            <!-- closing -->
            <p id="closing" class="mb-8 text-xl leading-relaxed">{{ $article->closing }}</p>
            @if(Auth::check() && Auth::user()->id == $article->user_id)
                <textarea id="closingInput" class="textarea textarea-bordered w-full mb-8 text-xl" style="display: none;">{{ $article->closing }}</textarea>
            @endif
        </div>
    </div>

            @if(Auth::check() && Auth::user()->id == $article->user_id)
    <div class="flex justify-end mt-4 space-x-4">
        @if($article->status == 'draft')
            <form id="publishForm" action="{{ route('articles.publish', $article->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" id="publishButton" class="btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                    これで投稿する
                </button>
            </form>

            <form id="reviewRequestForm" action="{{ route('articles.request-review', $article) }}" method="POST">
                @csrf
                <button type="submit" class="btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                    レビュー依頼する
                </button>
            </form>
        @elseif($article->status == 'review_requested')
            <p class="text-blue-600 font-semibold mt-4 mr-4">レビュー依頼中です</p>
            <form id="publishForm" action="{{ route('articles.publish', $article->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" id="publishButton" class="btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                    レビューを待たずに公開する
                </button>
            </form>
        @elseif($article->status == 'published')
            <form id="unpublishForm" action="{{ route('articles.unpublish', $article->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" id="unpublishButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                    下書きに戻す
                </button>
            </form>
            <form id="repostForm" action="{{ route('articles.publish', $article->id) }}" method="POST" style="display: none;">
                @csrf
                @method('PUT')
                <button type="submit" id="repostButton" class="btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                    再投稿する
                </button>
        @endif
    </div>

    <div class="flex justify-end mt-4 mb-10">
        <button id="editButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white px-6 py-2 rounded-full mr-2" onclick="enableEditing()">編集する</button>
        <button id="saveButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white px-6 py-2 rounded-full" onclick="saveChanges()" data-update-url="{{ route('articles.update', $article->id) }}" style="display: none;">保存する</button>
    </div>
@endif

            
    </div>

    <!-- コメント欄 -->
    <div class="container mx-auto px-4 mt-16 mb-16">
    <h3 class="text-3xl font-bold pt-8 mb-8 text-center text-gray-800">コメント</h3>

    @if($article->comments && $article->comments->count() > 0)
        <div class="space-y-8">
            @foreach($article->comments as $comment)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($comment->professor && $comment->professor->icon)
                                <img src="{{ asset('storage/' . $comment->professor->icon) }}" alt="{{ $comment->professor->name }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <img src="{{ asset('default-icon.png') }}" alt="Default Icon" class="h-16 w-16 rounded-full object-cover">
                            @endif
                        </div>
                        <div class="ml-6 flex-grow">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xl font-semibold text-gray-800">{{ $comment->professor ? $comment->professor->name : 'Unknown Professor' }}</h4>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $comment->professor ? $comment->professor->business . ' | ' . $comment->professor->title : '' }}</p>
                            <p class="mt-4 text-gray-800 leading-relaxed">{{ $comment->comment }}</p>
                            @if(Auth::guard('professor')->check() && Auth::guard('professor')->user()->id === $comment->professor_id)
                    <div class="ml-auto">
                        <a href="{{ route('article.comment.edit', $comment) }}" class="text-blue-500 hover:text-blue-700 mr-2">編集</a>
                        <form action="{{ route('article.comment.destroy', $comment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('本当に削除しますか？');">削除</button>
                        </form>
                    </div>
                @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 text-center text-lg">この記事にはまだコメントがありません。</p>
    @endif

    @auth('professor')
        <div class="mt-12 pb-8 text-center">
            <a href="{{ route('article.comment.create', $article) }}" class="bg-[#d8bfd8] hover:bg-[#e6e6fa] text-white font-bold py-3 px-6 rounded-full focus:outline-none focus:shadow-outline inline-block transition transform hover:-translate-y-1 hover:scale-110">
                コメントを送る
            </a>
        </div>
    @else
        <p class="mt-12 text-gray-600 text-center text-lg">コメントを投稿するには、オブザーバーとしてログインしてください。</p>
    @endauth
</div>


    

        <!-- JQueryでその場で編集、更新、レビュー依頼できるように -->
    <script>
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // // 画像の表示確認ー＞あとでコメントアウト！
    // $(document).ready(function() {
    //     $('img').each(function() {
    //         console.log('Image URL:', $(this).attr('src'));
    //         $(this).on('error', function() {
    //             console.error('Image failed to load:', $(this).attr('src'));
    //         });
    //     });
    // });

       // グローバルスコープで関数を定義
function enableEditing() {
    $('#editButton').hide();
    $('#saveButton').show();
    $('#repostButton').hide();
    $('#publishButton').hide();
    $('#genreDisplay').hide();
    $('#genreInput').show();
    toggleElementDisplay('#title', '#titleInput');
    toggleElementDisplay('#lead', '#leadInput');
    toggleElementDisplay('#closing', '#closingInput');
    toggleElementDisplay('#cap1', '#cap1Input');
    toggleElementDisplay('#cap2', '#cap2Input');
    
    $('#mainimg').on('click', function() {
        $('#mainimgInput').click();
    });
    $('#img1').on('click', function() {
        $('#img1Input').click();
    });
    $('#img2').on('click', function() {
        $('#img2Input').click();
    });
}

function toggleElementDisplay(viewElementSelector, editElementSelector) {
    $(viewElementSelector).hide();
    $(editElementSelector).show();
}

function updateImage(imgElementId, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + imgElementId).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function saveChanges() {
    var updateUrl = $('#saveButton').data('update-url');
    var formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('title', $('#titleInput').val());
    formData.append('lead', $('#leadInput').val());
    formData.append('closing', $('#closingInput').val());
    formData.append('cap1', $('#cap1Input').val());
    formData.append('cap2', $('#cap2Input').val());
    formData.append('genre', $('#genreInput').val()); 
    
    if ($('#mainimgInput')[0].files.length > 0) {
        formData.append('mainimg', $('#mainimgInput')[0].files[0]);
    }
    if ($('#img1Input')[0].files.length > 0) {
        formData.append('img1', $('#img1Input')[0].files[0]);
    }
    if ($('#img2Input')[0].files.length > 0) {
        formData.append('img2', $('#img2Input')[0].files[0]);
    }
    
    $.ajax({
        url: updateUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
// ステータス表示の更新
var statusDisplay = $('<p>').addClass('text-blue-600 font-semibold mt-4').text('下書き');
$('.flex.justify-end.mt-4.space-x-4').html(statusDisplay);

// ボタンの更新
var publishForm = createPublishForm();
var reviewRequestForm = createReviewRequestForm();
$('.flex.justify-end.mt-4.space-x-4').append(publishForm).append(reviewRequestForm);

// 編集モードを終了
disableEditing();
                
// 成功メッセージの表示
                alert('記事を更新しました。記事は下書き状態になりました。');
            } else {
                alert('更新に失敗しました: ' + (data.message || '不明なエラー'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseJSON);
            alert('更新に失敗しました: ' + (xhr.responseJSON.message || error));
        }
    });
    }


    // 編集終了関連
    function disableEditing() {
    $('#saveButton').hide();
    $('#editButton').show();
    toggleElementDisplay('#titleInput', '#title');
    toggleElementDisplay('#leadInput', '#lead');
    toggleElementDisplay('#closingInput', '#closing');
    toggleElementDisplay('#cap1Input', '#cap1');
    toggleElementDisplay('#cap2Input', '#cap2');
    $('#genreInput').hide();
    $('#genreDisplay').show();
}

function createPublishForm() {
    var publishForm = $('<form>').attr('id', 'publishForm').attr('action', "{{ route('articles.publish', $article->id) }}").attr('method', 'POST');
    publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
    publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_method').val('PUT'));
    publishForm.append($('<button>').attr('type', 'submit').attr('id', 'publishButton').addClass('btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('これで投稿する'));
    return publishForm;
}

function createReviewRequestForm() {
    var reviewRequestForm = $('<form>').attr('id', 'reviewRequestForm').attr('action', "{{ route('articles.request-review', $article) }}").attr('method', 'POST');
    reviewRequestForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
    reviewRequestForm.append($('<button>').attr('type', 'submit').addClass('btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('レビュー依頼する'));
    return reviewRequestForm;
}

// レビュー依頼関数
function requestReview() {
    var reviewUrl = $('#reviewRequestForm').attr('action');
    $.ajax({
        url: reviewUrl,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data.success) {
                alert(data.message); 
                
                var statusDisplay = $('<p>').addClass('text-blue-600 font-semibold mt-4').text('レビュー依頼中です');
                $('.flex.justify-end.mt-4.space-x-4').html(statusDisplay);

                // 「レビューを待たずに公開する」ボタンの追加
                var publishForm = $('<form>').attr('id', 'publishForm').attr('action', "{{ route('articles.publish', $article->id) }}").attr('method', 'POST');
                publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
                publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_method').val('PUT'));
                publishForm.append($('<button>').attr('type', 'submit').attr('id', 'publishButton').addClass('btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('レビューを待たずに公開する'));
            } else {
                alert('レビュー依頼に失敗しました: ' + (data.message || '不明なエラー'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseJSON);
            alert('レビュー依頼に失敗しました: ' + (xhr.responseJSON.message || error));
        }
    });
}

// 記事を下書きに戻す関数
function unpublishArticle() {
    var unpublishUrl = $('#unpublishForm').attr('action');
    $.ajax({
        url: unpublishUrl,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT'
        },
        success: function(data) {
            if (data.success) {
                alert(data.message);
                var statusDisplay = $('<p>').addClass('text-blue-600 font-semibold mt-4').text('下書き');
                $('.flex.justify-end.mt-4.space-x-4').html(statusDisplay);

                // 「これで投稿する」ボタンと「レビュー依頼する」ボタンの追加
                var publishForm = $('<form>').attr('id', 'publishForm').attr('action', "{{ route('articles.publish', $article->id) }}").attr('method', 'POST');
                publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
                publishForm.append($('<input>').attr('type', 'hidden').attr('name', '_method').val('PUT'));
                publishForm.append($('<button>').attr('type', 'submit').attr('id', 'publishButton').addClass('btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('これで投稿する'));

                var reviewRequestForm = $('<form>').attr('id', 'reviewRequestForm').attr('action', "{{ route('articles.request-review', $article) }}").attr('method', 'POST');
                reviewRequestForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
                reviewRequestForm.append($('<button>').attr('type', 'submit').addClass('btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('レビュー依頼する'));

                $('.flex.justify-end.mt-4.space-x-4').append(publishForm).append(reviewRequestForm);
            } else {
                alert('記事を下書きに戻すのに失敗しました: ' + (data.message || '不明なエラー'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseJSON);
            alert('記事を下書きに戻すのに失敗しました: ' + (xhr.responseJSON.message || error));
        }
    });
}

//記事公開関数

function publishArticle() {
    var publishUrl = $('#publishForm').attr('action');
    $.ajax({
        url: publishUrl,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT'
        },
        success: function(data) {
            if (data.success) {
                alert(data.message);
                
                var statusDisplay = $('<p>').addClass('text-green-600 font-semibold mt-4').text('公開中');
                $('.flex.justify-end.mt-4.space-x-4').html(statusDisplay);

                // 「下書きに戻す」ボタンの追加
                var unpublishForm = $('<form>').attr('id', 'unpublishForm').attr('action', "{{ route('articles.unpublish', $article->id) }}").attr('method', 'POST');
                unpublishForm.append($('<input>').attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content')));
                unpublishForm.append($('<input>').attr('type', 'hidden').attr('name', '_method').val('PUT'));
                unpublishForm.append($('<button>').attr('type', 'submit').attr('id', 'unpublishButton').addClass('btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline').text('下書きに戻す'));

                $('.flex.justify-end.mt-4.space-x-4').append(unpublishForm);
            } else {
                alert('記事の公開に失敗しました: ' + (data.message || '不明なエラー'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseJSON);
            alert('記事の公開に失敗しました: ' + (xhr.responseJSON.message || error));
        }
    });
}


// ドキュメント読み込み完了後に実行
$(document).ready(function() {
    $('#editButton').on('click', enableEditing);
    $('#saveButton').on('click', saveChanges);

    // 画像アップロード
    $('#mainimgInput, #img1Input, #img2Input').on('change', function() {
        updateImage(this.id.replace('Input', ''), this);
    });

    $(document).on('submit', '#reviewRequestForm', function(e) {
        e.preventDefault();
        takeScreenshot();
        requestReview();
    });

    $(document).on('submit', '#unpublishForm', function(e) {
        e.preventDefault();
        unpublishArticle();
    });

    $(document).on('submit', '#publishForm', function(e) {
        e.preventDefault();
        publishArticle();  
    });
});

// レビュー依頼でスクリーンショット
async function takeScreenshot() {
    console.log('Starting screenshot capture');
    const targetElement = document.querySelector("#article-content-for-screenshot");
    console.log('Target element:', targetElement);

    // スクリーンショット前に要素を可視化
    const originalStyle = targetElement.style.cssText;
    targetElement.style.cssText = `
        position: relative;
        width: 100%;
        visibility: visible;
    `;

    try {
        // すべての画像の読み込みを待つ
        await Promise.all(Array.from(targetElement.getElementsByTagName('img'))
            .filter(img => !img.complete)
            .map(img => new Promise((resolve, reject) => {
                img.onload = resolve;
                img.onerror = reject;
            }))
        );
        console.log('All images loaded');

        // HTML要素から OKLCH 色を削除または置換
        removeOKLCHColors(targetElement);

        const canvas = await html2canvas(targetElement, {
            useCORS: true,
            allowTaint: true,
            backgroundColor: null,
            scale: window.devicePixelRatio,
            scrollX: 0,
            scrollY: -window.scrollY,
            logging: true
        });

        console.log('Canvas created successfully');

        // Blobの生成
        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
        console.log('Blob created:', blob);
        console.log('Blob size:', blob.size);

        const formData = new FormData();
        formData.append('screenshot', blob, 'article_screenshot.png');
        formData.append('article_id', '{{ $article->id }}');

        // AJAXリクエストの送信
        const response = await $.ajax({
            url: '{{ route("articles.save-screenshot") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (response.success) {
            console.log('Success response:', response);
            alert('スクリーンショットが保存されました');
            // スクリーンショットのリンクをレビューページに表示
            window.location.reload();
        } else {
            console.error('Error response:', response);
            alert('スクリーンショットの保存に失敗しました: ' + response.message);
        }

    } catch (error) {
        console.error('Error in takeScreenshot:', error);
        alert('スクリーンショットの作成または保存に失敗しました: ' + error.message);
    } finally {
        // 元のスタイルを復元
        targetElement.style.cssText = originalStyle;
    }
}

// HTML要素から OKLCH 色を削除または置換する関数
function removeOKLCHColors(element) {
    const elements = element.querySelectorAll('*');
    elements.forEach(el => {
        const style = window.getComputedStyle(el);
        if (style.color.includes('oklch')) {
            el.style.color = 'black';  // oklch色を黒に置き換え
            console.log('Replaced oklch color:', el);
        }
        if (style.backgroundColor.includes('oklch')) {
            el.style.backgroundColor = 'white';  // oklch背景色を白に置き換え
            console.log('Replaced oklch background:', el);
        }
    });
}

</script>
          
</body>
</html>
