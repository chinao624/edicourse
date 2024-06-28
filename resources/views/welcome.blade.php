<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Welcome to Edicourse</title>
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

  <style>
    .header-image-container {
      position: relative;
      overflow: hidden;
      height: 400px; /* 画像の高さに合わせて調整 */
    }
    #header-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover; /* 画像の縦横比を維持しながらコンテナを覆う */
    }
  </style>
</head>
<body class="bg-pastel">
  <div class="header-image-container">
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


  <footer class="p-10 bg-neutral text-neutral-content">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <nav>
        <h6 class="footer-title font-bold mb-2">Services</h6> 
        <a class="link link-hover text-white">Branding</a>
        <a class="link link-hover text-white">Design</a>
        <a class="link link-hover text-white">Marketing</a>
        <a class="link link-hover text-white">Advertisement</a>
      </nav> 
      <nav>
        <h6 class="footer-title font-bold mb-2">Company</h6> 
        <a class="link link-hover text-white">About us</a>
        <a class="link link-hover text-white">Contact</a>
        <a class="link link-hover text-white">Jobs</a>
        <a class="link link-hover text-white">Press kit</a>
      </nav> 
      <nav>
        <h6 class="footer-title font-bold mb-2">Legal</h6> 
        <a class="link link-hover text-white">Terms of use</a>
        <a class="link link-hover text-white">Privacy policy</a>
        <a class="link link-hover text-white">Cookie policy</a>
      </nav>
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
  </script>
</body>
</html>
