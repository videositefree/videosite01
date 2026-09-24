
@extends('layouts.app')


@section('title')VideoSite wyszukaj @endsection


@section('search')

<button class="btn btn-primary" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseSort multiCollapseDate multiCollapseTime multiCollapseRating multiCollapseEntities">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px; margin-right:6px;"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
    Filtry
</button>

<form action="{{ url('/search/method') }}" method="GET" style="display:flex; flex-wrap:wrap; gap: 24px; width:100%; margin-top: 14px;">
    <input type="hidden" name="search" value="{{$search}}">

    <div class="col-sm-3 col-lg-3">
        <div style="font-weight:600; margin-bottom:8px;">Sortuj wg <a type="button" data-toggle="collapse" data-target="#multiCollapseSort" aria-expanded="false" aria-controls="multiCollapseSort">(Powiązanie)</a></div>
        <div class="collapse multi-collapse" id="multiCollapseSort">
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="sort" value="relevance"> Powiązanie z tagami</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="sort" value="uploaddate"> Data przesłania</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="sort" value="rating"> Ocena</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="sort" value="length"> Długość</label>
        </div>
    </div>

    <div class="col-sm-3 col-lg-3">
        <div style="font-weight:600; margin-bottom:8px;">Data <a type="button" data-toggle="collapse" data-target="#multiCollapseDate" aria-expanded="false" aria-controls="multiCollapseDate">(Cały okres)</a></div>
        <div class="collapse multi-collapse" id="multiCollapseDate">
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="all"> Cały okres</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="today"> Ostatnie 3 dni</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="week"> W tym tygodniu</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="month"> W tym miesiącu</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="3month"> Ostatnie 3 miesiące</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="date" value="6month"> Ostatnie 6 miesięcy</label>
        </div>
    </div>

    <div class="col-sm-4 col-lg-4">
        <div style="font-weight:600; margin-bottom:8px;">Czas trwania <a type="button" data-toggle="collapse" data-target="#multiCollapseTime" aria-expanded="false" aria-controls="multiCollapseTime">(Wszystkie)</a></div>
        <div class="collapse multi-collapse" id="multiCollapseTime">
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="time" value="3-10min"> Krótkie filmiki (3–10 min)</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="time" value="10-20min"> Średnie filmy (10–20 min)</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="time" value="20-40min"> Długie filmy (20–40 min)</label>
            <label style="display:block; padding:6px 0;"><input type="radio" class="radio_serch" name="time" value="40min_more"> Pełnometrażowe (+40 min)</label>
        </div>
    </div>

    <div class="col-sm-3 col-lg-3">
        <div style="font-weight:600; margin-bottom:8px;">Ocena <a type="button" data-toggle="collapse" data-target="#multiCollapseRating" aria-expanded="false" aria-controls="multiCollapseRating">(Dowolna)</a></div>
        <div class="collapse multi-collapse" id="multiCollapseRating">
            <div style="display:flex; align-items:center; gap:10px; padding: 6px 0;">
                <label style="margin:0;">Od
                    <input type="number" name="rating_min" min="0" max="6" step="0.5" class="form-control"
                           style="width:80px; display:inline-block; margin-left:6px;"
                           value="{{ $ratingMin ?? old('rating_min') }}">
                </label>
                <label style="margin:0;">do
                    <input type="number" name="rating_max" min="0" max="6" step="0.5" class="form-control"
                           style="width:80px; display:inline-block; margin-left:6px;"
                           value="{{ $ratingMax ?? old('rating_max') }}">
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div style="font-weight:600; margin-bottom:8px;">Konkretne tagi / gwiazdy / wytwórnie <a type="button" data-toggle="collapse" data-target="#multiCollapseEntities" aria-expanded="false" aria-controls="multiCollapseEntities">(Dowolne)</a></div>
        <div class="collapse multi-collapse" id="multiCollapseEntities">
        <div style="display:flex; flex-wrap:wrap; gap:24px; padding-top:6px;">

            <div class="upload-subgroup" style="border:none; padding:0; margin:0; min-width:260px; flex:1;">
                <label class="upload-subgroup__label">Tagi</label>
                <div class="input-group">
                    <input type="text" class="form-control ui-autocomplete-input" id="filter_tag" placeholder="Dodaj tag" autocomplete="off">
                    <div class="input-group-append">
                        <div class="btn btn-success text-white" id="filter_addTag">Dodaj</div>
                    </div>
                </div>
                <ul class="upload-pill-list" id="filter_tagList">
                    @isset($selectedTags)
                        @foreach ($selectedTags as $t)
                            <li class="tags" data-pill-id="tags_{{ $t->id }}">
                                {{ $t->name }}
                                <button type="button" class="deleteTag btn-delete" data-remove="tags_{{ $t->id }}">X</button>
                            </li>
                            <input type="hidden" name="tags[]" data-pill-id="tags_{{ $t->id }}" value="{{ $t->id }}">
                        @endforeach
                    @endisset
                </ul>
            </div>

            <div class="upload-subgroup" style="border:none; padding:0; margin:0; min-width:260px; flex:1;">
                <label class="upload-subgroup__label">Gwiazdy</label>
                <div class="input-group">
                    <input type="text" class="form-control ui-autocomplete-input" id="filter_star" placeholder="Dodaj gwiazdę" autocomplete="off">
                    <div class="input-group-append">
                        <div class="btn btn-success text-white" id="filter_addStar">Dodaj</div>
                    </div>
                </div>
                <ul class="upload-pill-list" id="filter_starList">
                    @isset($selectedStars)
                        @foreach ($selectedStars as $s)
                            <li class="starr" data-pill-id="starr_{{ $s->id }}">
                                {{ $s->name }}
                                <button type="button" class="deleteTag btn-delete" data-remove="starr_{{ $s->id }}">X</button>
                            </li>
                            <input type="hidden" name="stars[]" data-pill-id="starr_{{ $s->id }}" value="{{ $s->id }}">
                        @endforeach
                    @endisset
                </ul>
            </div>

            <div class="upload-subgroup" style="border:none; padding:0; margin:0; min-width:260px; flex:1;">
                <label class="upload-subgroup__label">Wytwórnie</label>
                <div class="input-group">
                    <input type="text" class="form-control ui-autocomplete-input" id="filter_studio" placeholder="Dodaj wytwórnię" autocomplete="off">
                    <div class="input-group-append">
                        <div class="btn btn-success text-white" id="filter_addStudio">Dodaj</div>
                    </div>
                </div>
                <ul class="upload-pill-list" id="filter_studioList">
                    @isset($selectedStudios)
                        @foreach ($selectedStudios as $st)
                            <li class="studios" data-pill-id="studios_{{ $st->id }}">
                                {{ $st->name }}
                                <button type="button" class="deleteTag btn-delete" data-remove="studios_{{ $st->id }}">X</button>
                            </li>
                            <input type="hidden" name="studios[]" data-pill-id="studios_{{ $st->id }}" value="{{ $st->id }}">
                        @endforeach
                    @endisset
                </ul>
            </div>

        </div>
        </div>
    </div>

    <div class="col-sm-12" style="padding-top:6px;">
        <button class="btn btn-success" type="submit">Wyszukaj</button>
    </div>
</form>

<script>
$(function() {
    function wireEntityPicker(inputId, addBtnId, listId, autocompleteUrl, fieldName, pillClass, idKey) {
        $('#' + inputId).autocomplete({
            source: "{{ url('') }}/" + autocompleteUrl,
            minLength: 1,
            select: function (event, ui) {
                $('#' + inputId).val(ui.item.value);
                $('#' + inputId).attr('data-picked-id', ui.item[idKey]);
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li class='" + item.disabled + "'><div><img src='" + item.img + "'><span>" + item.value + "</span></div></li>").appendTo(ul);
        };

        $('#' + addBtnId).click(function () {
            var name = $('#' + inputId).val();
            var id = $('#' + inputId).attr('data-picked-id');
            if (!name || !id) return;

            var pillKey = pillClass + '_' + id;
            if ($('[data-pill-id="' + pillKey + '"]').length) return; // już dodane

            var li = $('<li class="' + pillClass + '" data-pill-id="' + pillKey + '">' + name +
                ' <button type="button" class="deleteTag btn-delete" data-remove="' + pillKey + '">X</button></li>');
            var hidden = $('<input type="hidden" name="' + fieldName + '[]" data-pill-id="' + pillKey + '" value="' + id + '">');

            $('#' + listId).append(li).append(hidden);
            $('#' + inputId).val('').removeAttr('data-picked-id');
        });
    }

    wireEntityPicker('filter_tag', 'filter_addTag', 'filter_tagList', 'gettag', 'tags', 'tags', 'tag_id');
    wireEntityPicker('filter_star', 'filter_addStar', 'filter_starList', 'getstar', 'stars', 'starr', 'star_id');
    wireEntityPicker('filter_studio', 'filter_addStudio', 'filter_studioList', 'getstudios', 'studios', 'studios', 'studios_id');

    // jeden spójny klucz (data-pill-id) usuwa naraz i "pigułkę", i jej hidden input —
    // niezależnie od tego, czy pigułka była wyrenderowana przez serwer, czy dodana w JS
    $(document).on('click', '.deleteTag[data-remove]', function () {
        var key = $(this).data('remove');
        $('[data-pill-id="' + key + '"]').remove();
    });
});
</script>

@endsection



@section('content')

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








@endsection
@section('pagi') {{ $films->appends(Request::all())->links() }} @endsection



