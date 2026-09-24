<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'VideoSite'))</title>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/app.blade.js') }}?v={{ @filemtime(public_path('js/app.blade.js')) }}" defer></script>
        <script src="{{ asset('js/jquery-ui.js') }}" defer></script>
        <script src="{{ asset('js/jquery.form.js') }}" defer></script>
        <script src="{{ asset('js/video.min.js') }}" defer></script>
        <script src="{{ asset('js/marquee-preview.js') }}?v={{ @filemtime(public_path('js/marquee-preview.js')) }}" defer></script>

        <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.blade.css') }}?v={{ @filemtime(public_path('css/app.blade.css')) }}" rel="stylesheet"><!-- Marquee theme -->
        <link href="{{ asset('css/star.css') }}?v={{ @filemtime(public_path('css/star.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/video-js.css') }}" rel="stylesheet">

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

                <a href="{{ url('') }}" class="marquee-nav__brand">VideoSite</a>

                <ul class="marquee-nav__links">
                    <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ url('') }}">Strona Główna</a></li>

                    <li class="nav-dd {{ request()->is('tags*') ? 'active' : '' }}">
                        <a href="{{ url('/tags') }}" aria-haspopup="true">
                            Tagi
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </a>
                        <div class="nav-dd__panel">
                            <div class="nav-dd__panel-inner">
                                <a href="{{ url('/tags') }}">Tagi Filmów</a>
                                <a href="{{ url('/tags_stars') }}">Tagi Gwiazd</a>
                                <a href="{{ url('/tags_studios') }}">Tagi Wytwórni</a>
                            </div>
                        </div>
                    </li>

                    <li class="{{ request()->is('stars*') ? 'active' : '' }}"><a href="{{ url('/stars') }}">Gwiazdy</a></li>
                    <li class="{{ request()->is('studios*') ? 'active' : '' }}"><a href="{{ url('/studios') }}">Wytwórnie</a></li>

                    @guest
                    @else
                    <li class="{{ request()->is('add_films*') ? 'active' : '' }}"><a href="{{ url('/add_films') }}">Prześlij</a></li>
                    @endguest
                </ul>

                <div class="marquee-nav__spacer"></div>

                <div class="marquee-nav__actions">

                    <button type="button" class="nav-icon-btn" data-search-toggle aria-label="Szukaj">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>

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
                                    <a href="{{ route('login') }}">{{ __('Zaloguj się') }}</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">{{ __('Rejestracja') }}</a>
                                    @endif
                                @else
                                    <a href="{{ url('/admin_index') }}">Zarządzaj</a>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Wyloguj') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                @endguest
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- ============ SEARCH OVERLAY ============ -->
        <div class="search-overlay" id="mq-search-overlay">
            <form action="{{ url('/search') }}" method="GET">
                <input type="text" name="search" id="mq-search-input" placeholder="Szukaj filmów, gwiazd, tagów…" autocomplete="off">
                <button type="submit">Szukaj</button>
            </form>
        </div>

        <!-- ============ DRAWER (mobile nav) ============ -->
        <div class="drawer-backdrop" id="mq-drawer-backdrop"></div>
        <nav class="drawer" id="mq-drawer" aria-hidden="true" aria-label="Menu główne">
            <div class="drawer__head">
                <span class="marquee-nav__brand" style="font-size:22px;">VideoSite</span>
                <button type="button" class="drawer__close" id="mq-drawer-close" aria-label="Zamknij menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <ul class="drawer__nav">
                <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ url('') }}">Strona Główna</a></li>

                <li class="drawer__accordion {{ request()->is('tags*') ? 'is-open' : '' }}">
                    <button type="button" class="drawer__accordion-btn" aria-expanded="false">
                        Tagi
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="drawer__accordion-panel">
                        <a href="{{ url('/tags') }}">Tagi Filmów</a>
                        <a href="{{ url('/tags_stars') }}">Tagi Gwiazd</a>
                        <a href="{{ url('/tags_studios') }}">Tagi Wytwórni</a>
                    </div>
                </li>

                <li class="{{ request()->is('stars*') ? 'active' : '' }}"><a href="{{ url('/stars') }}">Gwiazdy</a></li>
                <li class="{{ request()->is('studios*') ? 'active' : '' }}"><a href="{{ url('/studios') }}">Wytwórnie</a></li>

                @guest
                @else
                <li><a href="{{ url('/add_films') }}">Prześlij</a></li>
                @endguest
            </ul>

            <div class="drawer__foot">
                <div class="custom-control custom-switch mode-switch">
                    <input type="checkbox" class="custom-control-input" id="darkSwitchMobile">
                    <label class="custom-control-label" for="darkSwitchMobile">Tryb ciemny</label>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-success" style="text-align:center;">{{ __('Zaloguj się') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-info" style="text-align:center;">{{ __('Rejestracja') }}</a>
                    @endif
                @else
                    <a href="{{ url('/admin_index') }}" class="btn btn-info" style="text-align:center;">Zarządzaj</a>
                    <a href="#" class="btn btn-delete" style="text-align:center;"
                       onclick="event.preventDefault(); document.getElementById('logout-form-m').submit();">{{ __('Wyloguj') }}</a>
                    <form id="logout-form-m" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                @endguest
            </div>
        </nav>

        <!-- ============ FILTER PANEL (yield used by search.blade.php etc.) ============ -->
        @if ($__env->hasSection('search') || $__env->hasSection('search_extra'))
        <div class="search-filters-panel">
            <div class="wrap">
                @yield('search')
                <div class="search-filters-panel__extra">@yield('search_extra')</div>
            </div>
        </div>
        @endif

        <!-- ============ CONTENT ============ -->
        <main id="mq-content" class="content">
            <div class="wrap">

                <div class="d-flex justify-content-end">@yield('filtr_text')</div>
                <div class="d-flex justify-content-center">@yield('filtr_link')</div>
                <div class="d-flex justify-content-center">@yield('filtr2_link')</div>

                <div class="col-sm-12 text-center" style="padding-top: 0;">
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

        <footer class="marquee-footer">
            <strong>VideoSite</strong> — &copy; {{ date('Y') }}
        </footer>

        <button onclick="topFunction()" id="myBtn" title="Do góry" aria-label="Przewiń do góry">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="19" x2="12" y2="5"/><path d="M5 12l7-7 7 7"/></svg>
        </button>

    </body>
</html>
