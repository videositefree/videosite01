<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/app.blade.js') }}" defer></script> <!------- Script for all site ---------->
    <script src="{{ asset('js/starrr.js') }}" defer></script>
    <script src="{{ asset('js/jquery-ui.js') }}" ></script><!------- Script for autocomplete ---------->
    <script src="{{ asset('js/jquery.form.js') }}" defer></script><!------- Script for autocomplete ---------->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.blade.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fonts-style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/starrr.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet"><!------- style for autocomplete ---------->


    
    
</head>
<body class="body" >        
 
    <nav class="navbar navbar-expand-xl sticky-top navbar-dark">
        <div class="site-name tw" >Video Site!</div>


        
         
        </ul>
    </nav>

    <div class="search form-inline tw ">
      
            @yield('search')
            
      <div class="search form-inline tw col-sm-12">
        @yield('search_extra')    
      </div>
        
    </div>
  

 
<!--       
    <div class="baner col-sm-12"><img src="{{ asset('icon/app.blade/baner.png') }}" class="img-fluid"></div>
-->
     
    <div class="container content" style="padding-top: 20px;">
      <div class="row ">

          @yield('content')

      </div>
    </div>



    
  

  <div class="page d-flex justify-content-center">
      
      @yield('pagi')

  </div>
  
<!-- 

  <div class="baner-down col-sm-12"><img src="{{ asset('icon/app.blade/baner_down.png') }}" class="img-fluid"></div>

  <div class="footer">
      <div class="container-fluid">
          <div class="row">
                <div class="footer-heading tw col-sm-12 "><h3> Nagłówek</h3></div>
          </div>
      </div>

    <div class="footer-column container">
        <div class="row d-flex justify-content-center">
            <div class="ads-footer col-sm-12 col-lg-4 "><img src="{{ asset('icon/app.blade/ads_1.png') }}" class="img-fluid"></div>
            <div class="ads-footer col-sm-12 col-lg-4"><img src="{{ asset('icon/app.blade/ads_2.png') }}" class="img-fluid"></div>
            <div class="ads-footer col-sm-12 col-lg-4"><img src="{{ asset('icon/app.blade/ads_3.png') }}" class="img-fluid"></div>
        </div>
    </div>

    <div class="footer-under-column container">
        <div class="row ">
            <div class="footer-logo col-sm-6 offset-md-3 d-flex justify-content-center" ><img src="{{ asset('icon/app.blade/logo_down.png') }}" class="img-fluid"></div>
        </div>
    </div>

    <div class="footer-under-logo container">
        <div class="row ">
            <div class="footer-logo col-sm-6 offset-md-3 d-flex justify-content-center" >
                <a href="#">Prawa autorskie</a>
                <a href="#">Kontakt</a>
                <a href="#">Działanie strony</a>


            </div>
        </div>
    </div> 



  </div>

--> 
 

 
 
 
</body>
</html>
