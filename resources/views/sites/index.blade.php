
@extends('layouts.app')


@section('title')VideoSite @endsection


@section('content')

    @if( $count_films >0 )
        
        @if (isset($films_check))
            @section('filtr_text')
                @include('partials.film_sort')
                <div class="t" style="font-size: 15px; padding-left: 20px;">
                    <a href="{{ url('/index_random_film_watch') }}">
                        <button class="btn btn-success">
                            <span> Losowy Film</span>
                        </button>
                    </a>
                </div>
            @endsection
        
        @endif



      @if (isset( $tags))
        @section('extra_content')
        <div class="row" style="word-wrap: break-word;">
        @section('filtr_text') @include('partials.film_sort') @endsection
            <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                <img src="{{URL::asset("$tags->thumbnail")}}" style="height: 270px; width: 230px;" controls muted></img></br>
                <b>{{$tags->name}}</b></br>
                Ilość filmów: {{$count_films}}
                @guest
                @else
                <div class="t" style="font-size: 15px; padding-top: 5px;">
                    <a href="{{url('/edit_tags', $tags->id)}}" class="btn btn-info">Edytuj</a>                        
                </div>
                @endguest
            </div>
        </div>
        @endsection

        @section('extra_content2')
            <hr style="border: 1px solid black;"></hr>
        @endsection

      @endif







      @if (isset( $stars))
        @section('extra_content')
        @section('filtr_text') @include('partials.film_sort') @endsection

        <div class="row" style="word-wrap: break-word;">
            <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                <img src="{{URL::asset("$stars->thumbnail")}}" style="height: 270px; width: 230px;" controls muted ></img></br>
                <b>{{$stars->name}}</b></br>
                Ilość filmów: {{$count_films}}
                @if ( $tags_stars_count > 0)
                <div class="t" style="font-size: 15px; padding-top: 5px;">Tagi Gwiazd:
                    @foreach ($tags_stars as $tag)
                    <a href="{{url('/select_categories_stars_db_films', $tag->tag_id)}}"class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$tag->name}}</a>
                    @endforeach               
                </div>
                @endif

                @if ( $tags_stars_films_count > 0)
                <div class="t" style="font-size: 15px; padding-top: 10px;">Tagi Filmowe:
                    @foreach ($tags_stars_films as $tags)
                        <a href="{{url('/select_categories_stars_db_films', $tags->tag_id)}}"class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$tags->name}}</a>
                    @endforeach                       
                </div>
                @endif
                <div class="t" style="padding-top: 20px;"></div>
                @guest
                @else
                <div class="t" style="font-size: 15px; padding-top: 5px;">
                    <a href="{{url('/edit_stars', $stars->id)}}" class="btn btn-info">Edytuj</a>                        
                </div>
                @endguest
            </div>
        </div>
        @endsection

        @section('extra_content2')
            <hr style="border: 1px solid black;"></hr>
        @endsection

      @endif


      @if (isset( $studios))
        @section('extra_content')
        @section('filtr_text') @include('partials.film_sort') @endsection
        <div class="row" style="word-wrap: break-word;">
            <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                <img src="{{URL::asset("$studios->thumbnail")}}" style="height: 270px; width: 230px;" controls muted></img></br>
                <b style="font-size: 20px; padding-bottom:10px;">{{$studios->name}}</b></br>
                Ilość filmów: {{$count_films}}

                @if ( $tags_studios_count > 0)
                <div class="t" style="font-size: 15px; padding-top: 10px;">Tagi Wytwórni:
                    @foreach ($tags_studios as $tags)
                        <a href="{{url('/select_categories_studios', $tags->tag_id)}}"class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$tags->name}}</a>
                    @endforeach                       
                </div>
                @endif
                @if ( $tags_studios_films_count > 0)
                <div class="t" style="font-size: 15px; padding-top: 10px;">Tagi Filmowe:
                    @foreach ($tags_studios_films as $tags)
                        <a href="{{url('/select_categories_studios_db_films', $tags->tag_id)}}"class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$tags->name}}</a>
                    @endforeach                       
                </div>
                @endif
                <div class="t" style="padding-top: 20px;"></div>
                @guest
                @else
                <div class="t" style="font-size: 15px; padding-top: 5px;">
                    <a href="{{url('/edit_studios', $studios->id)}}" class="btn btn-info">Edytuj</a>                        
                </div>
                @endguest
            </div>
        </div>
        @endsection

        @section('extra_content2')
            <hr style="border: 1px solid black;"></hr>
        @endsection

    @endif

<?php $heroFilm = ($films->currentPage() == 1 && $films->count() > 0) ? $films->getCollection()->random() : null; ?>

@if($heroFilm)
<section class="hero">
    <div class="hero__bg" style="background-image: url('{{URL::asset("$heroFilm->thumbnail")}}');"></div>
    <div class="hero__scrim"></div>
    <div class="hero__content">
        <span class="hero__eyebrow">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            Polecane
        </span>
        <h1 class="hero__title">{{ $heroFilm->name }}</h1>
        <div class="hero__meta">
            <span><i class="fa fa-clock-o"></i> @php echo gmdate("H:i:s", $heroFilm->duration); @endphp</span>
            <span><i class="fas fa-star"></i> {{ $heroFilm->rating }}</span>
        </div>
        <a href="{{ url('/watch', $heroFilm->id) }}" class="hero__cta">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            Oglądaj teraz
        </a>
    </div>
</section>
@endif

<div class="film-grid">
@foreach ($films as $film)

<a href="{{ url('/watch', $film->id) }}" class="film-card" data-preview="true" aria-label="{{ $film->name }}">

    <div class="video-wrapper">

        <div class="poster-still" style="background-image: url('{{URL::asset("$film->thumbnail")}}');"></div>

        <video
            muted
            loop
            playsinline
            preload="none"
            poster="{{URL::asset("$film->thumbnail")}}"
            tabindex="-1"
            aria-hidden="true"
        >
            <source src="{{URL::asset("$film->short")}}" type="video/mp4" />
        </video>

        <div class="play-hint" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="11" fill="rgba(0,0,0,0.45)" stroke="rgba(242,241,237,0.85)" stroke-width="1"/>
                <path d="M10 8.5L16 12L10 15.5V8.5Z" fill="#F2F1ED"/>
            </svg>
        </div>

        <div class="sweep"></div>

        <div class="film_rating">
            <i class="fa fa-star" aria-hidden="true"></i> {{$film->rating}}
        </div>

        <div class="film_duration">
            <i class="fa fa-clock-o"></i> &nbsp;@php echo gmdate("H:i:s", $film->duration); @endphp
        </div>

    </div>

    <div class="card-hover" style="text-align: left;">
        <div class="text_video_name">
            {{$film->name}}
        </div>
    </div>

</a>

@endforeach
</div>

<div class="col-sm-12">
<div style="margin-top: 80px; "></div>
</div>
@else


<div class="col-sm-12 text-center">
  
    
    <div class="vertical-center">
        <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
            
            <div class="alert alert-danger">

                        <div class="col-sm-12 text-center" style="padding-top: 30px;">

                        @if (\Session::has('msg_success'))
                        <div class="alert alert-success">
                            <ul>
                                {!! \Session::get('msg_success') !!}
                            </ul>
                        </div>
                        @endif

                        @if (\Session::has('msg_errors'))
                        <div class="alert alert-danger">
                            <ul>
                                {!! \Session::get('msg_errors') !!}
                            </ul>
                        </div>
                        @endif

                        </div>
                <ul>
                    Brak filmów do wyświetlenia.
                </ul>

                <ul>
                    <a href="{{ url('/add_films') }}"><button class="btn btn-success">Dodaj Nowy Film</button></a>
                </ul>

            </div>

        </div>
    </div>


</div>
@endif



@endsection
@section('pagi') {{ $films->links() }} @endsection

