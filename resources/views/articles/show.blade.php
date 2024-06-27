<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
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

          <!-- mainimgのセット -->
            <div class="relative mb-8">
                <img id="mainimg" src="{{ asset('storage/' . $article->mainimg) }}" alt="{{ $article->title }}" class="w-full object-cover rounded-lg shadow-lg" style="height: 400px;">
                
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
                    <img src="{{ asset('storage/' . $article->user->profile->icon) }}" alt="{{ $article->user->nickname }}" class="w-16 h-16 rounded-full mr-4 border-2 border-gray-300">
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
                        <img id="img1" src="{{ asset('storage/' . $article->img1) }}" alt="Image 1" class="max-w-full max-h-full object-contain rounded-lg shadow">
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
                        <img id="img2" src="{{ asset('storage/' . $article->img2) }}" alt="Image 2" class="max-w-full max-h-full object-contain rounded-lg shadow">
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

            @if(Auth::check() && Auth::user()->id == $article->user_id)
                @if($article->status == 'draft')
                    <form id="publishForm" action="{{ route('articles.publish',$article->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="title" id="hiddenTitleInput" value="{{ $article->title }}">
                        <input type="hidden" name="lead" id="hiddenLeadInput" value="{{ $article->lead }}">
                        <input type="hidden" name="closing" id="hiddenClosingInput" value="{{ $article->closing }}">
                        <input type="hidden" name="cap1" id="hiddenCap1Input" value="{{ $article->cap1 }}">
                        <input type="hidden" name="cap2" id="hiddenCap2Input" value="{{ $article->cap2 }}">
                
                        <div class="flex justify-end mt-4">
                            <button type="submit" id="publishButton" class="btn bg-[#f08080] hover:bg-red-400 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                                これで投稿する
                            </button>
                        </div>
                    </form>
                @else
                    <form id="updateForm" action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex justify-end mt-4">
                            <button type="submit" id="repostButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline">
                                再投稿する
                            </button>
                        </div>
                    </form>
                @endif
        
                <div class="flex justify-end mt-4 mb-10">
                    <button id="editButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white px-6 py-2 rounded-full mr-2" onclick="enableEditing()">編集する</button>
                    <button id="saveButton" class="btn bg-[#4682b4] hover:bg-blue-600 text-white px-6 py-2 rounded-full" onclick="saveChanges()" data-update-url="{{ route('articles.update', $article->id) }}" style="display: none;">保存する</button>
                </div>
            @endif
        </div>
    </div>

        <!-- JQueryでその場で編集できるように -->
    <script>
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
                location.reload();
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

// ドキュメント読み込み完了後に実行
$(document).ready(function() {
    $('#editButton').on('click', enableEditing);
    $('#saveButton').on('click', saveChanges);

    // 画像アップロードのイベントリスナー
    $('#mainimgInput, #img1Input, #img2Input').on('change', function() {
        updateImage(this.id.replace('Input', ''), this);
    });
});
</script>
          
</body>
</html>
