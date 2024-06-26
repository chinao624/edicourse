<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <title>{{ $article->title }}</title>
</head>
<body>
    <div class="container mx-auto p-4" style="max-width: 1200px;">
       <!-- ジャンル表示と記事一覧に戻るボタン -->
    <div class="flex justify-between items-center mb-4">
        <div class="bg-blue-200 text-black px-3 py-1 rounded">
            {{ $japaneseGenre }}
        </div>
        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            記事一覧に戻る
        </a>
    </div>

    <!-- mainimg -->
    <div class="relative mb-4">
        <img id="mainimg" src="{{ asset('storage/' . $article->mainimg) }}" alt="{{ $article->title }}" class="w-full object-cover rounded" style="height: 30vw; max-height: 500px; min-height: 300px;">
        
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <input type="file" id="mainimgInput" style="display: none;" onchange="updateImage('mainimg', this)">
        @endif
    </div>

    @if(Auth::check() && Auth::user()->id == $article->user_id)
        <input type="file" id="mainimgInput" style="display: none;" onchange="updateImage('mainimg', this)">
    @endif
</div>
        
        
        <div class="mx-auto" style="max-width: 800px;">
        
        <!-- エディター情報と日付 -->
        <div class="flex items-center justify-between mb-4">
        <div class="flex items-center mb-4">
            <img src="{{ asset('storage/' . $article->user->profile->icon) }}" alt="{{ $article->user->nickname }}" class="w-16 h-16 rounded-full mr-4">
            <div>
                <h3 class="text-sm font-semibold">この記事を書いたのは…</h3>
                <h3 class="text-lg font-semibold">{{ $article->user->nickname }}</h3>
                <p class="text-sm text-gray-600">{{ $article->user->profile->school }}</p>
                <p class="text-sm">{{ $article->user->profile->introduction }}</p>
            </div>
        </div>
        <div class="text-right">
        <p class="text-sm text-gray-600">{{ $formattedDate }}</p>
    </div>
</div>

        <!-- Article Title -->
        <h1 id="title" class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <input type="text" id="titleInput" class="input input-bordered w-full mb-6" value="{{ $article->title }}" style="display: none;">
        @endif

        <!-- リード -->
        <p id="lead" class="mb-8 text-lg">{{ $article->lead }}</p>
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <textarea id="leadInput" class="textarea textarea-bordered w-full mb-8" style="display: none;">{{ $article->lead }}</textarea>
        @endif

        <!-- img1のセット -->
        <div class="flex flex-col md:flex-row mb-12">
    <div class="w-full md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
        <div class="flex items-center justify-center" style="max-height:400px;">
        <img id="img1" src="{{ asset('storage/' . $article->img1) }}" alt="Image 1" class="max-w-full max-h-full object-contain rounded">
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <input type="file" id="img1Input" style="display: none;" onchange="updateImage('img1', this)">
        @endif
    </div>
</div>
    <div class="w-full md:w-1/2 pl-0 md:pl-4">
        <p id="cap1" class="text-base">{{ $article->cap1 }}</p>
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <textarea id="cap1Input" class="textarea textarea-bordered w-full" style="display: none;">{{ $article->cap1 }}</textarea>
        @endif
    </div>
</div>

        <!-- img2のセット -->
        <div class="flex flex-col md:flex-row mb-12">
    <div class="w-full md:w-1/2 order-2 md:order-2 pl-0 md:pl-4 mb-4 md:mb-0">
    <div class="flex items-center justify-center" style="max-height: 400px;">
        <img id="img2" src="{{ asset('storage/' . $article->img2) }}" alt="Image 2" class="max-w-full max-h-full object-contain rounded">
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <input type="file" id="img2Input" style="display: none;" onchange="updateImage('img2', this)">
        @endif
    </div>
</div>
    <div class="w-full md:w-1/2 order-1 md:order-1 pr-0 md:pr-4">
        <p id="cap2" class="text-base">{{ $article->cap2 }}</p>
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <textarea id="cap2Input" class="textarea textarea-bordered w-full" style="display: none;">{{ $article->cap2 }}</textarea>
        @endif
    </div>
</div>

        <!-- Closing -->
        <p id="closing" class="mb-8 text-lg">{{ $article->closing }}</p>
        @if(Auth::check() && Auth::user()->id == $article->user_id)
            <textarea id="closingInput" class="textarea textarea-bordered w-full mb-8" style="display: none;">{{ $article->closing }}</textarea>
        @endif

        <!-- 投稿ボタン *リダイレクト先未指定！ -->
         <!-- 投稿ボタンと編集・保存ボタンはarticleのオーサーのみに表示 -->
         @if(Auth::check() && Auth::user()->id == $article->user_id)
         @if($article->status == 'draft')
            <form id="publishForm" action="{{ route('articles.publish',$article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- 必要な入力フィールドをすべて含める -->
                <input type="hidden" name="title" id="hiddenTitleInput" value="{{ $article->title }}">
                <input type="hidden" name="lead" id="hiddenLeadInput" value="{{ $article->lead }}">
                <input type="hidden" name="closing" id="hiddenClosingInput" value="{{ $article->closing }}">
                <input type="hidden" name="cap1" id="hiddenCap1Input" value="{{ $article->cap1 }}">
                <input type="hidden" name="cap2" id="hiddenCap2Input" value="{{ $article->cap2 }}">
        
                <div class="flex justify-end mt-4">
                    <button type="submit" id="publishButton" class="bg-[#f08080] hover:bg-gray-200 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        これで投稿する
                    </button>
                </div>
            </form>
            @else
            <form id="updateForm" action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="flex justify-end mt-4">
                <button type="submit" id ="repostButton" class="bg-[#4682b4] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    再投稿する
                </button>
            </div>
        </form>
           @endif
        
            <!-- 編集・保存ボタンを表示 -->
            <div class="flex justify-end mt-4 mb-10">
                <button id="editButton" class="bg-[#4682b4] text-white px-4 hover:bg-gray-200 rounded" onclick="enableEditing()">編集する</button>
                <button id="saveButton" class="bg-[#4682b4] text-white px-4 hover:bg-gray-200 rounded" onclick="saveChanges()" data-update-url="{{ route('articles.update', $article->id) }}" style="display: none;">保存する</button>
            </div>
        @endif
</div>
</div>

        <!-- JQueryでその場で編集できるように -->
    <script>
        $(document).ready(function() {
  $('#editButton').on('click', enableEditing);
  
  function enableEditing() {
    $('#editButton').hide();
    $('#saveButton').show();
    $('#repostButton').hide();
    $('#publishButton').hide();
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
  
  $('#saveButton').on('click', saveChanges);
  
  function saveChanges() {
    var updateUrl = $('#saveButton').data('update-url');
    var formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('title', $('#titleInput').val());
    formData.append('lead', $('#leadInput').val());
    formData.append('closing', $('#closingInput').val());
    formData.append('cap1', $('#cap1Input').val());
    formData.append('cap2', $('#cap2Input').val());
    
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
          alert('更新に失敗しました。');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        alert('更新に失敗しました。');
      }
    });
  }
});
</script>
          
</body>
</html>
