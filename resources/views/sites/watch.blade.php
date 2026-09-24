
@extends('layouts.app')


@section('title')VideoSite - {{ $films->name }} @endsection


@section('content')

<div class="watch-page">

    @if(!empty($successMsg))
    <div id="successMessage" class="alert alert-success">{{ $successMsg }}</div>
    <script>window.setTimeout(function(){ var m = document.getElementById('successMessage'); if(m) m.style.display='none'; }, 2000);</script>
    @endif

    <div class="watch-player">
        <video class="watch-player__video" src="{{URL::asset("$films->url")}}" controls></video>
    </div>

    <div class="watch-meta">
        <h1 class="watch-title">{{ $films->name }}</h1>

        <div class="watch-rating" aria-label="Ocena: {{ $films->rating }} na 6">
            @for ($i = 1; $i <= 6; $i++)
                <i class="star fa fa-star fa-2x {{ $i <= $films->rating ? 'is-filled' : '' }}"></i>
            @endfor
        </div>

        <div class="watch-actions">

            <a href="{{ url('/index_random_film_watch') }}" class="watch-action-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                Losowy film
            </a>

            <a href="{{URL::asset("$films->url")}}" download="{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz film
            </a>

            <a href="{{URL::asset("$films->short")}}" download="Short_{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz zajawkę
            </a>

            <a href="{{URL::asset("$films->thumbnail")}}" download="Thumbnail_{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz miniaturę
            </a>

            @guest
            @else
            <a href="{{url('edit_films', $films->id)}}" class="watch-action-btn watch-action-btn--accent">
                <i class="fa fa-pen" aria-hidden="true"></i>
                Edytuj
            </a>
            @endguest

        </div>
    </div>

    <div class="watch-tagblocks">

        @if(count($tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi</h2>
                <div>
                    @foreach ($tags as $tag)
                        <a href="{{ url('/select_categories', $tag->id) }}" class="tags">{{$tag->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(count($stars) > 0)
            <section class="watch-tagblock">
                <h2>Gwiazdy</h2>
                <div>
                    @foreach ($stars as $star)
                        <a href="{{ url('/select_stars', $star->id) }}" class="starr">{{$star->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($stars_tags === 0)
        @elseif(count($stars_tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi Gwiazd</h2>
                <div>
                    @foreach ($stars_tags as $stars_tags)
                        <a href="{{ url('/select_categories_stars', $stars_tags->tag_id) }}" class="tags">{{$stars_tags->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(count($studios) > 0)
            <section class="watch-tagblock">
                <h2>Wytwórnie</h2>
                <div>
                    @foreach ($studios as $studios)
                        <a href="{{ url('/select_studios', $studios->id) }}" class="studios">{{$studios->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($studios_tags === 0)
        @elseif(count($studios_tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi Wytwórni</h2>
                <div>
                    @foreach ($studios_tags as $studios_tags)
                        <a href="{{ url('/select_categories_studios', $studios_tags->tag_id) }}" class="tags">{{$studios_tags->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>

</div>

@endsection
