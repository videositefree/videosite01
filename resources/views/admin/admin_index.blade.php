
@extends('layouts.admin')


@section('title')VideoSite Zarządzaj @endsection


@section('content')

<div class="admin-page">

    @if( $films_status != 0 or $tags_status != 0 or $stars_status != 0 or $studios_status != 0 or $thumbnail_status != 0 )
    <div class="status-alert-list">

        @if( $films_status == 0 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status filmów:</b> brak filmów w bazie danych.</span></div>
        @endif
        @if( $films_status == 2 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status filmów:</b> nazwa filmów z bazy danych nie zgadza się z plikami (brak możliwości wyświetlenia filmów na stronie). <a href="{{url('/change_film_name_md5')}}">Popraw</a></span></div>
        @endif
        @if( $films_status == 3 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status filmów:</b> folder z filmami jest pusty.</span></div>
        @endif
        @if( $films_status == 4 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status filmów:</b> problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</span></div>
        @endif

        @if( $tags_status == 0 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status tagów:</b> brak tagów w bazie danych.</span></div>
        @endif
        @if( $tags_status == 2 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status tagów:</b> nazwa tagów z bazy danych nie zgadza się z plikami (brak możliwości wyświetlenia tagów na stronie). <a href="{{url('/change_tags_name_md5')}}">Popraw</a></span></div>
        @endif
        @if( $tags_status == 3 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status tagów:</b> folder z tagami jest pusty.</span></div>
        @endif
        @if( $tags_status == 4 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status tagów:</b> problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</span></div>
        @endif

        @if( $stars_status == 0 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status gwiazd:</b> brak gwiazd w bazie danych.</span></div>
        @endif
        @if( $stars_status == 2 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status gwiazd:</b> nazwa gwiazd z bazy danych nie zgadza się z plikami (brak możliwości wyświetlenia gwiazd na stronie). <a href="{{url('/change_stars_name_md5')}}">Popraw</a></span></div>
        @endif
        @if( $stars_status == 3 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status gwiazd:</b> folder z gwiazdami jest pusty.</span></div>
        @endif
        @if( $stars_status == 4 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status gwiazd:</b> problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</span></div>
        @endif

        @if( $studios_status == 0 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status wytwórni:</b> brak wytwórni w bazie danych.</span></div>
        @endif
        @if( $studios_status == 2 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status wytwórni:</b> nazwa wytwórni z bazy danych nie zgadza się z plikami (brak możliwości wyświetlenia wytwórni na stronie). <a href="{{url('/change_studios_name_md5')}}">Popraw</a></span></div>
        @endif
        @if( $studios_status == 3 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status wytwórni:</b> folder z wytwórniami jest pusty.</span></div>
        @endif
        @if( $studios_status == 4 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status wytwórni:</b> problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</span></div>
        @endif

        @if( $thumbnail_status == 0 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status miniatur:</b> brak miniatur w bazie danych.</span></div>
        @endif
        @if( $thumbnail_status == 2 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status miniatur:</b> nazwa miniatur z bazy danych nie zgadza się z plikami (brak możliwości wyświetlenia miniatur na stronie). <a href="{{url('/Change_thumbnail_name_md5')}}">Popraw</a></span></div>
        @endif
        @if( $thumbnail_status == 3 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status miniatur:</b> folder z miniaturami jest pusty.</span></div>
        @endif
        @if( $thumbnail_status == 4 )
        <div class="status-alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><b>Status miniatur:</b> problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</span></div>
        @endif

    </div>
    @endif

    <div class="links-inline">
        <a href="{{url('/admin_help')}}">📖 Zapoznaj się z instrukcją obsługi strony</a>
        <a href="{{url('/operation_database')}}">✏️ Chcesz aby filmy miały oryginalny tytuł w folderze zamiast zakodowanej nazwy? Kliknij tutaj</a>
    </div>

    <div class="dash-grid">

        <div class="dash-card">
            <h2>Statystyki</h2>

            <div class="dash-headline">
                <b>Rozmiar folderu filmy:</b> {{$bytes}}<br>
                <b>Całkowity czas:</b>
                {{$full_duration_day}} dni {{$full_duration_hours}} godzin {{$full_duration_minutes}} minut {{$full_duration_seconds}} sekund
            </div>

            <div class="stat-grid">
                <a href="{{url('/admin_films')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$films}}</span>
                    <span class="stat-tile__label">Wszystkie filmy</span>
                </a>
                <a href="{{url('/films_on_desc')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$f_on}}</span>
                    <span class="stat-tile__label">Włączone</span>
                </a>
                <a href="{{url('/films_off_desc')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$f_off}}</span>
                    <span class="stat-tile__label">Wyłączone</span>
                </a>

                <a href="{{url('/admin_stars')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$stars}}</span>
                    <span class="stat-tile__label">Gwiazdy</span>
                </a>
                <a href="{{url('/admin_studios')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$studios}}</span>
                    <span class="stat-tile__label">Wytwórnie</span>
                </a>
                <a href="{{url('/admin_sites')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$site}}</span>
                    <span class="stat-tile__label">Strony</span>
                </a>

                <a href="{{url('/admin_tags')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$tags}}</span>
                    <span class="stat-tile__label">Tagi filmów</span>
                </a>
                <a href="{{url('/admin_tags_stars')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$tags_stars}}</span>
                    <span class="stat-tile__label">Tagi gwiazd</span>
                </a>
                <a href="{{url('/admin_tags_studios')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$tags_studios}}</span>
                    <span class="stat-tile__label">Tagi wytwórni</span>
                </a>
                <a href="{{url('/admin_tags_sites')}}" class="stat-tile">
                    <span class="stat-tile__value">{{$tags_sites}}</span>
                    <span class="stat-tile__label">Tagi stron</span>
                </a>
            </div>
        </div>

        <div class="dash-card">
            <h2>Zarządzaj</h2>
            <div class="dash-actions">
                <div class="dash-action">
                    <span>Wykonaj kopię zapasową bazy danych</span>
                    <a href="{{url('/copy_db')}}" class="btn btn-success">Backup Database</a>
                </div>
                <div class="dash-action">
                    <span>Wykonaj kopię zapasową folderu thumbnail</span>
                    <a href="{{url('/copy_folder')}}" class="btn btn-success">Backup Folder</a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
