{{-- Wspólne menu sortowania filmów - ten sam kod w każdym widoku z listą filmów.
     Opcje, etykiety i domyślne sortowanie pochodzą z App\Support\FilmSort (domyślne ustawia admin). --}}
@php
    $filmSortOptions = \App\Support\FilmSort::options(\App\Support\FilmSort::context());
    $filmSortCurrent = \App\Support\FilmSort::current(request());
@endphp
<div style="font-size: 20px; margin-right: 15px;">Sortuj według:</div>
<div class="dropdown">
    <button class="btn dropdown-toggle" type="button" id="filmSortButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ \App\Support\FilmSort::label($filmSortCurrent) }}
    </button>
    <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="filmSortButton">
        @foreach ($filmSortOptions as $sortKey => $sortOption)
            <a class="dropdown-item {{ $sortKey === $filmSortCurrent ? 'active' : '' }}"
               href="{{ \App\Support\FilmSort::url($sortKey) }}">{{ $sortOption['label'] }}</a>
        @endforeach
    </div>
</div>
