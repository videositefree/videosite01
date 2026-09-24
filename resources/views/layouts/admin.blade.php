<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/admin.blade.js') }}?v={{ @filemtime(public_path('js/admin.blade.js')) }}" defer></script>
        <script src="{{ asset('js/marquee-preview.js') }}?v={{ @filemtime(public_path('js/marquee-preview.js')) }}" defer></script>
        <script src="{{ asset('js/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/jquery.form.js') }}" defer></script>
        <script src="{{ asset('js/bootstrap4-toggle.min.js') }}" defer></script>

        <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.blade.css') }}?v={{ @filemtime(public_path('css/app.blade.css')) }}" rel="stylesheet"><!-- komponenty współdzielone: navbar, drawer, przyciski -->
        <link href="{{ asset('css/admin.blade.css') }}?v={{ @filemtime(public_path('css/admin.blade.css')) }}" rel="stylesheet"><!-- specyfika panelu: tabele, formularze -->
        <link href="{{ asset('css/star.css') }}?v={{ @filemtime(public_path('css/star.css')) }}" rel="stylesheet">

        <link rel="shortcut icon" href="#">

    </head>

    <body class="body">

        <a href="#mq-content" class="skip-link">Przejdź do treści</a>

        <!-- ============ NAVBAR ============ -->
        <header class="marquee-nav">
            <div class="marquee-nav__bar">

                <button type="button" class="hamburger-btn" id="mq-hamburger" aria-label="Otwórz menu" aria-expanded="false" aria-controls="mq-drawer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>

                <span class="marquee-nav__brand">Panel Administracyjny</span>

                <ul class="marquee-nav__links">

                    <li class="nav-dd {{ request()->is('admin_index') || request()->is('admin_database_copy') || request()->is('admin_folder_copy') || request()->is('operation_database') || request()->is('absence_films') || request()->is('absence_files_films') || request()->is('unique_tags') || request()->is('admin_help') ? 'active' : '' }}">
                        <a href="#" aria-haspopup="true">
                            Ustawienia
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </a>
                        <div class="nav-dd__panel">
                            <div class="nav-dd__panel-inner">
                                <a href="{{ url('/admin_index') }}">Szczegóły</a>
                                <a href="{{ url('/admin_database_copy') }}">Baza Danych</a>
                                <a href="{{ url('/admin_folder_copy') }}">Kopia Folderów</a>
                                <a href="{{ url('/operation_database') }}">Operacje na bazie danych</a>
                                <a href="{{ url('/absence_films') }}">Braki w plikach</a>
                                <a href="{{ url('/absence_files_films') }}">Braki w bazie danych</a>
                                <a href="{{ url('/unique_tags') }}">Duplikaty</a>
                                <a href="{{ url('/admin_help') }}">Pomoc</a>
                            </div>
                        </div>
                    </li>

                    <li class="{{ request()->is('admin_films*') ? 'active' : '' }}"><a href="{{ url('/admin_films') }}">Filmy</a></li>

                    <li class="nav-dd {{ request()->is('admin_tags*') ? 'active' : '' }}">
                        <a href="#" aria-haspopup="true">
                            Tagi
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </a>
                        <div class="nav-dd__panel">
                            <div class="nav-dd__panel-inner">
                                <a href="{{ url('/admin_tags') }}">Tagi Filmów</a>
                                <a href="{{ url('/admin_tags_stars') }}">Tagi Gwiazd</a>
                                <a href="{{ url('/admin_tags_studios') }}">Tagi Wytwórnii</a>
                                <a href="{{ url('/admin_tags_sites') }}">Tagi Stron</a>
                            </div>
                        </div>
                    </li>

                    <li class="{{ request()->is('admin_stars*') ? 'active' : '' }}"><a href="{{ url('/admin_stars') }}">Gwiazdy</a></li>
                    <li class="{{ request()->is('admin_studios*') ? 'active' : '' }}"><a href="{{ url('/admin_studios') }}">Wytwórnie</a></li>
                    <li class="{{ request()->is('admin_sites*') ? 'active' : '' }}"><a href="{{ url('/admin_sites') }}">Strony</a></li>

                    <li class="nav-dd {{ request()->is('cut_films') || request()->is('join_films') || request()->is('cut_image') || request()->is('simply_conversion') || request()->is('conversion') ? 'active' : '' }}">
                        <a href="#" aria-haspopup="true">
                            Video
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </a>
                        <div class="nav-dd__panel">
                            <div class="nav-dd__panel-inner">
                                <a href="{{ url('/cut_films') }}">Wytnij fragment filmu</a>
                                <a href="{{ url('/join_films') }}">Połącz fragment filmu</a>
                                <a href="{{ url('/cut_image') }}">Wytnij miniaturę z filmu</a>
                                <a href="{{ url('/simply_conversion') }}">Zmiana rozszerzenia</a>
                                <a href="{{ url('/conversion') }}">Konwersja filmu</a>
                            </div>
                        </div>
                    </li>

                </ul>

                <div class="marquee-nav__spacer"></div>

                <div class="marquee-nav__actions">

                    <div class="nav-icon-btn desktop-only" style="width:auto;">
                        <div class="custom-control custom-switch mode-switch" style="display:flex; align-items:center;">
                            <input type="checkbox" class="custom-control-input" id="darkSwitch">
                            <label class="custom-control-label" for="darkSwitch" style="margin-bottom:0;">Dark</label>
                        </div>
                    </div>

                    <div class="nav-dd desktop-only">
                        <button type="button" class="nav-icon-btn" aria-haspopup="true" aria-label="Konto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
                        </button>
                        <div class="nav-dd__panel">
                            <div class="nav-dd__panel-inner">
                                @guest
                                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                    @endif
                                @else
                                    <a href="{{ url('/') }}">Strona Główna</a>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Wyloguj się') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                @endguest
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- ============ DRAWER (mobile nav) ============ -->
        <div class="drawer-backdrop" id="mq-drawer-backdrop"></div>
        <nav class="drawer" id="mq-drawer" aria-hidden="true" aria-label="Menu admina">
            <div class="drawer__head">
                <span class="marquee-nav__brand" style="font-size:20px;">Panel Admina</span>
                <button type="button" class="drawer__close" id="mq-drawer-close" aria-label="Zamknij menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <ul class="drawer__nav">

                <li class="drawer__accordion">
                    <button type="button" class="drawer__accordion-btn" aria-expanded="false">
                        Ustawienia
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="drawer__accordion-panel">
                        <a href="{{ url('/admin_index') }}">Szczegóły</a>
                        <a href="{{ url('/admin_database_copy') }}">Baza Danych</a>
                        <a href="{{ url('/admin_folder_copy') }}">Kopia Folderów</a>
                        <a href="{{ url('/operation_database') }}">Operacje na bazie danych</a>
                        <a href="{{ url('/absence_films') }}">Braki w plikach</a>
                        <a href="{{ url('/absence_files_films') }}">Braki w bazie danych</a>
                        <a href="{{ url('/unique_tags') }}">Duplikaty</a>
                        <a href="{{ url('/admin_help') }}">Pomoc</a>
                    </div>
                </li>

                <li class="{{ request()->is('admin_films*') ? 'active' : '' }}"><a href="{{ url('/admin_films') }}">Filmy</a></li>

                <li class="drawer__accordion">
                    <button type="button" class="drawer__accordion-btn" aria-expanded="false">
                        Tagi
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="drawer__accordion-panel">
                        <a href="{{ url('/admin_tags') }}">Tagi Filmów</a>
                        <a href="{{ url('/admin_tags_stars') }}">Tagi Gwiazd</a>
                        <a href="{{ url('/admin_tags_studios') }}">Tagi Wytwórnii</a>
                        <a href="{{ url('/admin_tags_sites') }}">Tagi Stron</a>
                    </div>
                </li>

                <li class="{{ request()->is('admin_stars*') ? 'active' : '' }}"><a href="{{ url('/admin_stars') }}">Gwiazdy</a></li>
                <li class="{{ request()->is('admin_studios*') ? 'active' : '' }}"><a href="{{ url('/admin_studios') }}">Wytwórnie</a></li>
                <li class="{{ request()->is('admin_sites*') ? 'active' : '' }}"><a href="{{ url('/admin_sites') }}">Strony</a></li>

                <li class="drawer__accordion">
                    <button type="button" class="drawer__accordion-btn" aria-expanded="false">
                        Video
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="drawer__accordion-panel">
                        <a href="{{ url('/cut_films') }}">Wytnij fragment filmu</a>
                        <a href="{{ url('/join_films') }}">Połącz fragment filmu</a>
                        <a href="{{ url('/cut_image') }}">Wytnij miniaturę z filmu</a>
                        <a href="{{ url('/simply_conversion') }}">Zmiana rozszerzenia</a>
                        <a href="{{ url('/conversion') }}">Konwersja filmu</a>
                    </div>
                </li>

            </ul>

            <div class="drawer__foot">
                <div class="custom-control custom-switch mode-switch">
                    <input type="checkbox" class="custom-control-input" id="darkSwitchMobile">
                    <label class="custom-control-label" for="darkSwitchMobile">Tryb ciemny</label>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-success" style="text-align:center;">{{ __('Login') }}</a>
                @else
                    <a href="{{ url('/') }}" class="btn btn-info" style="text-align:center;">Strona Główna</a>
                    <a href="#" class="btn btn-delete" style="text-align:center;"
                       onclick="event.preventDefault(); document.getElementById('logout-form-m').submit();">{{ __('Wyloguj się') }}</a>
                    <form id="logout-form-m" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                @endguest
            </div>
        </nav>

        <div class="baner col-sm-12"></div>

        <!-- ============ CONTENT ============ -->
        <main id="mq-content" class="content">
            <div class="wrap">

                <div class="d-flex justify-content-center">@yield('filtr_text')</div>
                <div class="d-flex justify-content-center">@yield('filtr_link')</div>
                <div class="d-flex justify-content-center">@yield('filtr2_link')</div>

                <nav class="col-sm-12" aria-label="breadcrumb">
                    @yield('direction')
                </nav>

                @if ($__env->hasSection('extra_content'))
                <div class="extra-toolbar">@yield('extra_content')</div>
                @endif

                @yield('extra_content2')

                @yield('content')

            </div>
        </main>

        <div class="page">
            <div class="wrap">
                @yield('pagi')
            </div>
        </div>

        <button onclick="topFunction()" id="myBtn" title="Do góry" aria-label="Przewiń do góry">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="19" x2="12" y2="5"/><path d="M5 12l7-7 7 7"/></svg>
        </button>

    </body>
</html>
