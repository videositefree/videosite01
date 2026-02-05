<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VideoSite') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" ></script>
        <script src="{{ asset('js/app.blade.js') }}" defer></script> <!-- stars system -->
        <script src="{{ asset('js/jquery-ui.js') }}" defer></script> <!-- stars system -->
        <script src="{{ asset('js/jquery.form.js') }}" defer></script> <!-- stars system -->
        <script src="{{ asset('js/video.min.js') }}" defer></script> <!-- player "video.js" - script -->


    
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet"><!------- style for autocomplete ---------->


        <!-- Fonts -->
        <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
       
        
        <link href="{{ asset('css/app.blade.css') }}" rel="stylesheet">
        <link href="{{ asset('css/star.css') }}" rel="stylesheet">
        <link href="{{ asset('css/video-js.css') }}" rel="stylesheet"> <!-- player "video.js" - css -->

        <!-- Delete error favicon.ico -->
        <link rel="shortcut icon" href="#">

    </head>

    <body class="body" >        
    
        <nav class="navbar navbar-expand-xl sticky-top navbar-dark">
            <div class="site-name" >Video Site!</div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto bg-dark">
            
            <li><a class="fas fa-home" href="{{ url('') }}"> Strona Główna</a></li>

            <li class="dropdown open" style="padding-left: 5px;">
                    <a href="#" class="dropdown-toggle fas fa-tags" data-toggle="dropdown"> Tagi</a>
                    <ul class="dropdown-menu dropdown-login-menu" role="menu">

                    <a class="dropdown-item" href="{{ url('/tags') }}" id="drop_down_id"> Tagi Filmów </a>
                    <a class="dropdown-item" href="{{ url('/tags_stars') }}" id="drop_down_id"> Tagi Gwiazd </a>
                    <a class="dropdown-item" href="{{ url('/tags_studios') }}" id="drop_down_id"> Tagi Wytwórni</a>
            
                    </ul>
                </li>
            
            <li><a class="fas fa-star" href="{{ url('/stars') }}"> Gwiazdy</a></li>
            <li><a class="fas fa-folder" href="{{ url('/studios') }}"> Wytwórnie</a></li>
        
                @guest
                @else
                <li><a class="fas fa-upload" href="{{ url('/add_films') }}"> Prześlij</a></li>
                @endguest

                </ul>
            <ul class="navbar-nav navbar-right bg-dark">

            <form action="{{ url('/search') }}" method="GET" enctype="multipart/form-data">
                <div class="col-sm-12">            
                    <div class="input-group" style="padding-top: 18px; margin-bottom:10px">
                    <input type="text" name="search" class="form-control" placeholder="Wyszukaj..." >
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">
                        <i class="fa fa-search"></i>
                        </button>
            
                    </div>
                    </div>
            
                </div>
            </form>
                <li class="dropdown open" style="padding-left: 5px;">
                    <a href="#" class="dropdown-toggle fas fa-user" data-toggle="dropdown"></a>
                    <ul class="dropdown-menu dropdown-login-menu" role="menu">
                        @guest
                                <a class="dropdown-item" href="{{ route('login') }}" id="drop_down_id">{{ __('Zaloguj się') }}</a>
                                
                        @if (Route::has('register'))
                                    
                            <a class="dropdown-item" href="{{ route('register') }}" id="drop_down_id">{{ __('Rejestracja') }}</a>
                                    
                        @endif
                        @else

                            <a class="dropdown-item" href="{{ url('/admin_index') }}" id="drop_down_id">
                                Zarządzaj <span class="caret"></span>
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();" id="drop_down_id">
                                {{ __('Wyloguj') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" id="drop_down_id">
                                @csrf
                            </form>

                        @endguest
            
                    </ul>
                </li>
                <li>
                    <div id="dark-switch" class="custom-control custom-switch tw mode-switch" style="padding-top: 27px; padding-left: 45px;">
                    <input type="checkbox" class="custom-control-input"  id="darkSwitch">
                    <label class="custom-control-label" for="darkSwitch" style="color: white;">Dark Mode</label>
                    </div>
                </li>
                </div>
            </ul>
        </nav>


        <!-- Extra search div -->

            <div class="search form-inline tw ">
            
                @yield('search')
                    
                <div class="search form-inline tw col-sm-12">
                    @yield('search_extra')    
                </div>
                
            </div>

        <!-- END -->

        
        <div class="container content col-xl-10 " style="padding-top: 20px;">
        
            <div class="col-sm-3 col-lg-3"></div>

            <!-- Filtr database -->
                <div class="d-flex justify-content-end"> @yield('filtr_text')</div>
                <div class=" d-flex justify-content-center" >@yield('filtr_link')</div>
                <div class=" d-flex justify-content-center" >@yield('filtr2_link')</div>
            <!-- END -->

            
            <!-- Return Mesage --> 
                <div class="col-sm-12 text-center" style="padding-top: 15px;">


                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                </div>
            <!-- END -->

            <!-- Display all content -->
                <div class="row ">
                    
                    <!-- filtr database in table -->
                        <div class="col-sm-4" style="padding-bottom: 5px;"> @yield('extra_content')</div>
                        <div style="width: 100%; padding-bottom: 5px;">@yield('extra_content2')</div>
                    <!-- END -->

                    @yield('content')
                    

                </div>
            <!-- END -->
        </div>


        <div class="page d-flex justify-content-center col-sm-11">
            
            @yield('pagi')

        </div>
    




    </body>
</html>
