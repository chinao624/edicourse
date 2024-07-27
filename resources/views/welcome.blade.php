<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Welcome to Edicourse</title>
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  <style>
    .header-image-container {
      position: relative;
      overflow: hidden;
      height: 400px; /* 画像の高さに合わせる！のちほど！ */
    }
    #header-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover; /* コンテナを覆う */
    }
  </style>
</head>
<body class="bg-pastel">

  <div class="header-image-container">
  <div class="absolute right-10 top-10 space-x-4 z-10">
    <a href="{{ route('login') }}" class="px-3 py-1.5 bg-[#ffa07a] text-white text-sm font-semibold rounded-full hover:bg-[#fa8072] transition duration-300 shadow-md">学生エディターLogin</a>
    <a href="{{ route('professor.login') }}" class="px-3 py-1.5 bg-[#e0ffff] text-[#ff6347] text-sm font-semibold rounded-full hover:bg-white hover:text-[#ffa07a] transition duration-300 shadow-md">オブザーバーLogin</a>
  </div>
  <div class="absolute right-10 top-20 mt-4 z-10">
    <a href="{{ route('reviewer.login') }}" class="text-white hover:text-[#98fb98] transition duration-300 text-sm">レビュワーの方はこちら</a>
  </div>
    <img id="header-image" src="{{ asset('header.jpg') }}" alt="header">
    <div class="absolute left-10 top-1/2 transform -translate-y-1/2"> 
        <h1 class="text-[#ff6347] hover:text-[#ff7f50] transition-colors duration-300 font-josefin text-8xl font-bold">
      Edicourse
    </h1>
    <h2 class="text-[#e0ffff] transition-colors duration-300 text-2xl font-semibold mb-2">  
    発信がガクチカになる<br>
      学生エディター養成MEDIA<br> </h2>
  </div>
  </div>

  <!-- edicours説明 -->

  <div class="flex justify-center items-center mt-12">
    <div class="text-center">
<h2 class="font-josefin text-4xl">What's Edicourse？</h2>
<h3 class="text-l">Edicourseは、学生のための発信メディア。<br>
会員登録制のコミュニティで、見たこと感じたことを自由に発信。<br>
社会で活躍するエディターやオブザーバーからレビューやコメントをもらい<br>
文章力や編集力を磨くことができます</h3>
  </div>
  </div>

  <div class="flex justify-center items-center mt-16 mb-12">
    <div class="text-center">
      <h2 class="text-2xl font-bold">こんな学生におすすめ</h2>
      <h3 class="text-l leading-loose mt-8">
        ・安心して好きなこと興味あることを発信できる場所がほしい<br>
        ・信頼できるプロに文章をチェックしてもらいたい<br>
        ・全国の同世代が発信する記事で視野を広げたい<br>
        ・編集者や記者の仕事に興味がある<br>
        ・自分の記事に、社会で活躍しているオブザーバーからコメントがほしい<br>
      </h3>
  </div>
  </div>

  <!-- 登録ルートに-->

  <div class="container mx-auto px-4 mt-16 mb-20">
  <div class="flex flex-col md:flex-row justify-center items-stretch gap-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex-1 flex flex-col max-w-sm transition-transform duration-300 hover:scale-105">
      <div class="p-6 flex flex-col justify-between h-full">
        <div>
          <h3 class="font-josefin text-lg
           text-[#ff6347] mb-4">＜学生エディター新規登録はこちら＞</h3>
          <p class="text-gray-600 mb-6">
            SNSのように手軽にSNSより安心な場所で。信用できる社会人と繋がることができるEdicourseで、AIに駆逐されない編集力・文章力を磨いてみませんか？
          </p>
        </div>
        <div class="text-center">
          <a href="{{ route('users.create') }}" class="inline-block bg-[#ff6347] text-white py-2 px-6 rounded-full hover:bg-[#ff7f50] transition duration-300">新規登録</a>
        </div>
      </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex-1 flex flex-col max-w-sm transition-transform duration-300 hover:scale-105">
      <div class="p-6 flex flex-col justify-between h-full">
        <div>
          <h3 class="font-josefin text-lg text-[#4682b4] mb-4">＜オブザーバー新規登録はこちら＞</h3>
          <p class="text-gray-600 mb-6">
            学生の今考えていること、感じていることがリアルタイムに閲覧できるEdicourse Media。オブザーバーとしてのコメントで次世代の才能の育成に貢献してください。
          </p>
        </div>
        <div class="text-center">
          <a href="{{ route('professors.create') }}" class="inline-block bg-[#4682b4] text-white py-2 px-6 rounded-full hover:bg-[#5f9ea0] transition duration-300">新規登録</a>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- footer -->
  <footer class="bg-gradient-to-r from-[#ffb3ba] to-[#ffd1dc] text-gray-800 py-12">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="space-y-4">
        <h2 class="text-3xl font-josefin font-bold text-[#ff6347]">Edicourse</h2>
        <p class="text-sm">発信がガクチカになる学生エディター養成MEDIA</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-600 hover:text-[#ff6347] transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
            </svg>
          </a>
          <a href="#" class="text-gray-600 hover:text-[#ff6347] transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01" />
            </svg>
          </a>
          <a href="#" class="text-gray-600 hover:text-[#ff6347] transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
            </svg>
          </a>
        </div>
      </div>
      <div class="space-y-4">
        <h3 class="text-xl font-semibold text-[#ff6347]">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-[#ff6347] transition-colors duration-300">About Us</a></li>
          <li><a href="#" class="hover:text-[#ff6347] transition-colors duration-300">Services</a></li>
          <li><a href="#" class="hover:text-[#ff6347] transition-colors duration-300">Contact</a></li>
          <li><a href="#" class="hover:text-[#ff6347] transition-colors duration-300">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="space-y-4">
        <h3 class="text-xl font-semibold text-[#ff6347]">Newsletter</h3>
        <p class="text-sm">最新情報を受け取りましょう</p>
        <form class="flex">
          <input
            type="email"
            placeholder="あなたのメールアドレス"
            class="flex-grow px-4 py-2 rounded-l-full text-gray-800 focus:outline-none border-2 border-[#ffb3ba] focus:border-[#ff6347]"
          />
          <button
            type="submit"
            class="bg-[#ff6347] hover:bg-[#ff7f50] text-white px-6 py-2 rounded-r-full transition-colors duration-300"
          >
            登録
          </button>
        </form>
      </div>
    </div>
    <div class="mt-8 pt-8 border-t border-gray-300 text-center">
      <p class="text-gray-600">&copy; <span id="current-year"></span> Edicourse. All rights reserved.</p>
    </div>
  </div>
</footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(window).on('load', function() {
      $(window).on('scroll', function() {
        var scrollPosition = $(window).scrollTop();
        $('#header-image').css('transform', 'translateY(-' + scrollPosition * 0.5 + 'px)');
      });
    });

    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
</body>
</html>
