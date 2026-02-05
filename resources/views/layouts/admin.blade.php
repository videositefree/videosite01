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
    <script src="{{ asset('js/admin.blade.js') }}" defer></script> <!------- Script for all site ---------->
    <script src="{{ asset('js/jquery-ui.js') }}" ></script><!------- Script for autocomplete ---------->
    <script src="{{ asset('js/jquery.form.js') }}" defer></script><!------- Script for autocomplete ---------->  
    <script src="{{ asset('js/bootstrap4-toggle.min.js') }}" defer></script><!------- Script for toggler edit_films_blade ---------->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.blade.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet"><!------- style for autocomplete ---------->
    <link href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet"><!------- style for toggler edit_films_blade ---------->

    <!-- Delete error favicon.ico -->
    <link rel="shortcut icon" href="#">
    
</head>
<body class="body" >     

    <nav class="navbar navbar-expand-xl sticky-top navbar-dark">
        <div class="site-name tw" >Panel Administracyjny</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto ">


            <li class="dropdown open" style="padding-left: 5px;">
                <a href="#" class="dropdown-toggle fas fa-cog" data-toggle="dropdown"> Ustawienia</a>
                <ul class="dropdown-menu login-menu-dropdown" role="menu">

                    <a class="dropdown-item" href="{{ url('/admin_index') }}" id="drop_down_id">Szczegóły</a>

                    <a class="dropdown-item" href="{{ url('/admin_database_copy') }}" id="drop_down_id"> Baza Danych</a>

                    <a class="dropdown-item" href="{{url('/admin_folder_copy')}}" id="drop_down_id"> Kopia Folderów</a>

                    <a class="dropdown-item" href="{{url('/operation_database')}}" id="drop_down_id">Operacje na bazie danych</a>

                    <a class="dropdown-item" href="{{url('/absence_films')}}" id="drop_down_id">Braki w plikach</a>

                    <a class="dropdown-item" href="{{url('/absence_files_films')}}" id="drop_down_id">Braki w bazie danych</a>

                    <a class="dropdown-item" href="{{url('/unique_tags')}}" id="drop_down_id">Duplikaty</a>

                    <a class="dropdown-item" href="{{ url('/admin_help') }}" id="drop_down_id">Pomoc</a>

                </ul>
            </li>


           


          <li><a href="{{url('/admin_films')}}" class="fas fa-film"> Filmy</a></li>


          <li class="dropdown open" style="padding-left: 5px;">
                <a href="#" class="dropdown-toggle fas fa-tags" data-toggle="dropdown"> Tagi</a>
                <ul class="dropdown-menu login-menu-dropdown" role="menu">

                    <a class="dropdown-item" href="{{ url('/admin_tags') }}" id="drop_down_id"> Tagi Filmów</a>

                    <a class="dropdown-item" href="{{ url('/admin_tags_stars') }}" id="drop_down_id"> Tagi Gwiazd</a>
                    
                    <a class="dropdown-item" href="{{url('/admin_tags_studios')}}" id="drop_down_id"> Tagi Wytwórnii</a>

                    <a class="dropdown-item" href="{{url('/admin_tags_sites')}}" id="drop_down_id"> Tagi Stron</a>

                   
                   
                    

                </ul>
            </li>

          <li><a href="{{ url('/admin_stars') }}" class="fas fa-star"> Gwiazdy</a></li>

          <li><a href="{{ url('/admin_studios') }}" class="fas fa-folder"> Wytwórnie</a></li>
          <li><a href="{{ url('/admin_sites') }}" class="fas fa-list-alt"> Strony</a></li>


          <li class="dropdown open" style="padding-left: 5px;">
                <a href="#" class="dropdown-toggle fas fa-toolbox" data-toggle="dropdown"> Video</a>
                <ul class="dropdown-menu login-menu-dropdown" role="menu">

                    <a class="dropdown-item" href="{{url('/cut_films')}}" id="drop_down_id"> Wytnij fragment filmu</a>

                    <a class="dropdown-item" href="{{url('/join_films')}}" id="drop_down_id"> Połącz fragment filmu</a>

                    <a class="dropdown-item" href="{{url('/cut_image')}}" id="drop_down_id"> Wytnij miniaturę z filmu</a>
                    
                    <a class="dropdown-item" href="{{url('/simply_conversion')}}" id="drop_down_id">Zmiana rozszerzenia</a>

                    <a class="dropdown-item" href="{{url('/conversion')}}" id="drop_down_id">Konwersja filmu</a>

                   
                   
                    

                </ul>
            </li>
        


          </ul>

            
            <ul class="nav navbar-nav navbar-right">
    
   
    
                  <li class="dropdown open" style="padding-left: 5px;">
                    <a href="#" class="dropdown-toggle fas fa-user" data-toggle="dropdown"></a>
                    <ul class="dropdown-menu login-menu-dropdown" role="menu">
                        @guest
                                <a class="dropdown-item" href="{{ route('login') }}" id="drop_down_id">{{ __('Login') }}</a>
                                
                        @if (Route::has('register'))
                                    
                            <a class="dropdown-item" href="{{ route('register') }}" id="drop_down_id">{{ __('Register') }}</a>
                                    
                        @endif
                        @else

                            <a class="dropdown-item" href="{{url('/')}}" id="drop_down_id">
                                Strona Główna <span class="caret"></span>
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();" id="drop_down_id">
                                {{ __('Wyloguj się') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" id="drop_down_id">
                                @csrf
                            </form>

                        @endguest
            
                    </ul>
                  </li>
                  <li>
                    <div id="dark-switch" class="custom-control custom-switch tw mode-switch" style="color: white !important; padding-top: 27px; padding-left: 45px;">
                      <input type="checkbox" class="custom-control-input"  id="darkSwitch">
                      <label class="custom-control-label" for="darkSwitch">Dark Mode</label>
                    </div>
                  </li>
                </div>
            </ul>
    </nav>



   
          
        <div class="baner col-sm-12"><!-- <img src="{{ asset('icon/app.blade/baner.png') }}" class="img-fluid"> --></div>
   

        <div class="container content col-xl-10" style="padding-top: 20px;">
        
        <div class="col-sm-3 col-lg-3"></div>

        <!-- Filtr database -->
            <div class="d-flex justify-content-center"> @yield('filtr_text')</div>
            <div class=" d-flex justify-content-center" >@yield('filtr_link')</div>
            <div class=" d-flex justify-content-center" >@yield('filtr2_link')</div>
        <!-- END -->

        
       

        <!-- Display all content -->
            <div class="row ">

            <nav class="col-sm-12" aria-label="breadcrumb">
                @yield('direction')
            </nav>
                
                <!-- filtr database in table -->
                    <div class="col-sm-4" style="padding-bottom: 5px;"> @yield('extra_content')</div>
                    <div style="width: 100%; padding-bottom: 5px;">@yield('extra_content2')</div>
                <!-- END -->



                @yield('content')
                

            </div>
        <!-- END -->
    </div>



    
  

  <div class="page d-flex justify-content-center">
      
  @yield('pagi')

  </div>


 
 
 
</body>
</html>
