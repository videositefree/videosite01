
@extends('layouts.admin')

@section('title')VideoSite Edytuj Film @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_films') }}">Filmy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edytowanie Filmu</li>
</ol>
@endsection

@section('content')

<div class="upload-page">

    <div class="admin-page__header">
        <h1 class="upload-page__title">Edytowanie filmu #{{$films->id}}</h1>
        <div class="admin-quicklinks">
            <a class="admin-quicklink" href="{{url('/watch', $films->id)}}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 5v14l11-7z"/></svg>
                Zobacz na stronie
            </a>
            <a class="admin-quicklink" href="{{url('/open_folder_film', $films->id)}}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Folder z filmem
            </a>
        </div>
    </div>

    @if(!empty($errorsMsg))
    <div class="alert alert-danger">
        {{ $errorsMsg }}<br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania miniatury oraz zwiastunu filmu."></i>
    </div>
    @endif
    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <div class="upload-layout">

        <!-- ============ LEWA STRONA: podgląd (film / trailer / miniatura) ============ -->
        <div class="upload-preview">
            <div class="upload-preview__card">
                <label class="upload-subgroup__label">Film</label>
                <div class="resizable-video" style="margin-bottom:16px;">
                    <video src="{{URL::asset("$films->url")}}" controls></video>
                </div>

                <label class="upload-subgroup__label">Trailer</label>
                <div class="resizable-video" style="height:160px; margin-bottom:16px;">
                    <video src="{{URL::asset("$films->short")}}" controls></video>
                </div>

                <label class="upload-subgroup__label">Miniaturka</label>
                <div class="resizable-video" style="height:160px;">
                    <img src="{{URL::asset("$films->thumbnail")}}" style="width:100%; height:100%; object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="upload-form-wrap">

            <!-- ============ 1. GŁÓWNE DANE ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">1</span> Dane filmu</h2>

                <form action="{{url('/edit_films_save')}}" method="POST" enctype="multipart/form-data" id="edit_films_main">
                    @csrf

                    <div class="form-group">
                        <label for="film_name">Tytuł filmu</label>
                        <input type="text" class="form-control @error('film_name') form-error @enderror" name="film_name" value="{{$films->name}}" id="film_name">
                        @error('film_name')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="url">Pełna ścieżka dostępu do filmu</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('url') form-error @enderror" name="url" value="{{$films->url}}" id="url">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_next', $films->id)}}" title="Otwórz folder"></a>
                            </div>
                        </div>
                        @error('url')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="short">Pełna ścieżka dostępu do traileru</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('short') form-error @enderror" name="short" value="{{$films->short}}" id="short">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_short', $films->id)}}" title="Otwórz folder"></a>
                            </div>
                        </div>
                        <small class="form-text text-muted">Jeśli ścieżka dostępu jest pusta, popraw ją na "../../filmy/short/{{$films->id}}.mp4"</small>
                        @error('short')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Pełna ścieżka dostępu do zdjęcia</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('thumbnail') form-error @enderror" name="thumbnail" value="{{$films->thumbnail}}" id="thumbnail">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_thumbnail', $films->id)}}" title="Otwórz folder"></a>
                            </div>
                        </div>
                        @error('thumbnail')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="duration">Czas całego filmu (sekundy)</label>
                        <input type="number" class="form-control" name="duration" value="{{$films->duration}}" id="duration">
                        <small class="form-text text-muted">1. Jeśli czas wynosi 0 lub 1, wyślij formularz — czas powinien zostać policzony automatycznie.</small>
                        <small class="form-text text-muted">2. Jeśli nadal wynosi 0 lub 1, skorzystaj z kalkulatora poniżej, a następnie wpisz czas ręcznie.</small>

                        <div style="display:flex; align-items:center; gap:12px; margin-top:10px;">
                            <input type="time" name="start" id="start" step="1" value="00:00:00" class="form-control" style="max-width:160px;">
                            <span style="color:var(--muted);">→</span>
                            <input name="time_start" value="0" class="form-control" style="max-width:100px;" disabled>
                        </div>
                        <small class="form-text text-muted">Lewa kolumna: czas trwania filmu. Prawa: automatyczne przeliczenie na sekundy (1h = 3600s, 1min = 60s).</small>
                    </div>

                    <div class="form-group">
                        <label style="font-size:15px;">Ocena filmu: <b>{{$films->rating}}</b></label>
                        <div class="upload-toggle-row" style="flex-wrap:wrap; gap:14px;">
                            @for ($i = 1; $i <= 6; $i++)
                                <label style="margin:0;">
                                    <input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}" {{ $films->rating == $i ? 'checked' : '' }}> {{ $i }}
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="activ">Film aktywny</label><br>
                        <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" name="activ" id="activ" {{ $films->activ == 1 ? 'checked' : '' }}>
                    </div>

                    <input type="hidden" value="{{$films->id}}" name="films_id">

                    <div class="upload-submit-row">
                        <button class="btn btn-success btn-lg">Zapisz zmiany</button>
                    </div>
                </form>
            </section>

            <!-- ============ 2. TRAILER ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">2</span> Wygeneruj trailer</h2>
                <form action="{{url('/edit_films_trailer_save')}}" method="POST" enctype="multipart/form-data" id="edit_films_trailer">
                    @csrf
                    <div class="form-group">
                        <input type="time" name="short_time_video" id="short_time_video" step="1" value="00:00:00" max="05:00:00" class="form-control" style="max-width:200px;">
                        <input name="time_sec_video" type="hidden" value="720">
                        <small class="form-text text-muted">1. Sprawdź, czy pole "Czas całego filmu" jest uzupełnione — jeśli nie, zapisz najpierw sekcję 1.</small>
                        <small class="form-text text-muted">2. Trailer zawsze trwa 15 sekund.</small>
                        <small class="form-text text-muted">3. Jeśli ustawisz czas 30 min, a film trwa 20 min, trailer i tak powstanie z 12. minuty.</small>
                    </div>
                    <input type="hidden" value="{{$films->id}}" name="films_id">
                    <input type="hidden" value="{{$films->duration}}" name="duration">
                    <input type="hidden" value="{{$films->url}}" name="url">
                    <div class="upload-submit-row">
                        <button class="btn btn-success" type="submit" data-toggle="modal" data-target="#exampleModal">Wygeneruj trailer</button>
                    </div>
                </form>
            </section>

            <!-- ============ 3. MINIATURKA ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">3</span> Wygeneruj miniaturkę</h2>
                <form action="{{url('/edit_films_thumbnail_save')}}" method="POST" enctype="multipart/form-data" id="edit_films_thumb">
                    @csrf
                    <div class="form-group">
                        <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00" class="form-control" style="max-width:200px;">
                        <input name="time_sec_thumbnail" type="hidden" value="720">
                        <small class="form-text text-muted">Sprawdź, czy pole "Czas całego filmu" jest uzupełnione — jeśli nie, zapisz najpierw sekcję 1.</small>
                    </div>
                    <input type="hidden" value="{{$films->id}}" name="films_id">
                    <input type="hidden" value="{{$films->duration}}" name="duration">
                    <input type="hidden" value="{{$films->url}}" name="url">
                    <div class="upload-submit-row">
                        <button class="btn btn-success" type="submit">Wygeneruj miniaturkę</button>
                    </div>
                </form>
            </section>

            <!-- ============ 4. DODAJ TAGI / GWIAZDY / WYTWÓRNIE ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">4</span> Dodaj tagi, gwiazdy, wytwórnie</h2>

                <form action="{{url('/edit_films_add_tag')}}" method="POST" enctype="multipart/form-data" id="edit_films_add_tag">
                    @csrf

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Tagi</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj nowe tagi" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="tagList"></ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="extra_tag_stars" id="extra_tag_stars" value="0">
                            <label class="custom-control-label" for="extra_tag_stars">Dodaj tagi gwiazdy</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do gwiazdy (wykorzystywana baza tagów filmowych)."></i>
                        </div>
                        <label class="upload-subgroup__label" style="margin-top:14px;">Gwiazdy</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="star" placeholder="Dodaj nowe gwiazdy" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addStar">Dodaj</div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="starList"></ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="extra_tag_studios" name="extra_tag_studios" value="1">
                            <label class="custom-control-label" for="extra_tag_studios">Dodaj tagi wytwórni</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do wytwórni (wykorzystywana baza tagów filmowych)."></i>
                        </div>
                        <label class="upload-subgroup__label" style="margin-top:14px;">Wytwórnie</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="studios" placeholder="Dodaj nowe wytwórnie" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addStudios">Dodaj</div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="studiosList"></ul>
                    </div>

                    <div class="upload-submit-row">
                        <button class="btn btn-success btn-lg">Dodaj</button>
                    </div>
                </form>
            </section>

            <!-- ============ 5. ISTNIEJĄCE TAGI / GWIAZDY / WYTWÓRNIE ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">5</span> Przypisane obecnie</h2>
                <small class="form-text text-muted" style="margin-bottom:14px; display:block;">Kliknięcie X usuwa powiązanie od razu, bez dodatkowego potwierdzenia.</small>

                <div class="upload-subgroup">
                    <label class="upload-subgroup__label">Tagi</label>
                    @if(count($tags) > 0)
                        <ul class="upload-pill-list" id="tagList_db">
                            @foreach ($tags as $tag)
                                <li class="tags" id="id_{{$tag->id}}">
                                    <a href="{{url('/select_categories', $tag->tag_id)}}" class="tags_edit" target="_blank">{{$tag->name}}</a>
                                    <button class="deleteTagExsit btn-delete" id="id_{{$tag->id}}">X</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--muted);">Brak tagów do wyświetlenia.</p>
                    @endif
                </div>

                <div class="upload-subgroup">
                    <label class="upload-subgroup__label">Gwiazdy</label>
                    @if(count($stars) > 0)
                        <ul class="upload-pill-list" id="starList_db">
                            @foreach ($stars as $star)
                                <li class="star" id="star_id_{{$star->id}}">
                                    <a href="{{url('/select_stars', $star->stars_id)}}" class="star_edit" target="_blank">{{$star->name}}</a>
                                    <button class="deleteStarExsit btn-delete" id="star_id_{{$star->id}}">X</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--muted);">Brak gwiazd do wyświetlenia.</p>
                    @endif
                </div>

                <div class="upload-subgroup">
                    <label class="upload-subgroup__label">Wytwórnie</label>
                    @if(count($studios) > 0)
                        <ul class="upload-pill-list" id="studiosList_db">
                            @foreach ($studios as $studio)
                                <li class="studios" id="studio_id_{{$studio->id}}">
                                    <a href="{{url('/select_studios', $studio->studios_id)}}" class="studio_edit" target="_blank">{{$studio->name}}</a>
                                    <button class="deleteStudioExsit btn-delete" id="studio_id_{{$studio->id}}">X</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--muted);">Brak wytwórni do wyświetlenia.</p>
                    @endif
                </div>
            </section>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tworzenie zwiastunu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row justify-content-center text-center">
                <tw>Prosimy o chwilę cierpliwości.</br>
                    Strona zostanie automatycznie odświeżona po przygotowaniu zwiastunu filmowego.</br>
                    W zależności od posiadanego sprzętu może to zająć do 5 min.
                </tw><br>
                <img src="{{ asset('icon/app.blade/reload.gif') }}" style="width: 70px; height: 70px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>


<script>
// przelicznik czasu HH:MM:SS -> sekundy (sekcja 1)
$(document).ready(function() {
    $('#start').on('input', function() {
        var hours, minutes, seconds;
        var czas = document.getElementById("start").value;
        [hours, minutes, seconds] = czas.split(':');
        if (seconds == null || seconds == '' || seconds == undefined) { seconds = 0; }
        var time_sec = hours * 3600 + minutes * 60 + seconds * 1;
        if (isNaN(time_sec)) { time_sec = "720"; }
        $("input[name='time_start']").val(time_sec);
    });
});

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

$(function() {

    // ---------- Dodawanie nowych tagów ----------
    $('#tag').autocomplete({
        source: "{{url('/gettagg')}}",
        minLength: 1,
        scroll: true,
        select: function(event, ui) {
            $('#tag').val(ui.item.value);
            $('#tag').attr("tag_id", ui.item.tag_id);
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>").appendTo(ul);
    };

    var idTag = 0;
    $("#addTag").click(function(){
        if ($("#tag").val()) {
            idTag++;
            var li = document.createElement("li");
            li.className = "tags";
            li.setAttribute("id", 'newtag'+idTag);
            var i = document.createElement("INPUT");
            i.setAttribute("name", "multiTag[]");
            i.setAttribute("type", "hidden");
            i.setAttribute("id", 'newtag'+idTag);
            var tag = document.getElementById('tag').value;
            var tag_id = $('#tag').attr("tag_id");
            var url = '{{ url("/select_categories", "tag_id_url") }}'.replace('tag_id_url', tag_id);
            li.innerHTML = '<a href="'+url+'" target="_blank">'+tag+'</a> <button class="deleteTag btn-delete" id="newtag'+idTag+'">X</button>';
            i.setAttribute("value", tag);
            $("#tagList").append(li).append(i);
            $('#tag').val('');
        }
    });
    $("#tagList").on('click', 'button.deleteTag', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove();
        $(":input[id='"+idDiv+"']").remove();
    });

    // ---------- Dodawanie nowych gwiazd ----------
    $('#star').autocomplete({
        source: "{{url('/getstarr')}}",
        minLength: 1,
        scroll: true,
        select: function(event, ui) {
            $('#star').val(ui.item.value);
            $('#star').attr("star_id", ui.item.star_id);
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>").appendTo(ul);
    };

    var idStar = 0;
    $("#addStar").click(function(){
        if ($("#star").val()) {
            idStar++;
            var li = document.createElement("li");
            li.className = "star";
            li.setAttribute("id", 'newstar'+idStar);
            var i = document.createElement("INPUT");
            i.setAttribute("name", "multiStar[]");
            i.setAttribute("type", "hidden");
            i.setAttribute("id", 'newstar'+idStar);
            var star = document.getElementById('star').value;
            var star_id = $('#star').attr("star_id");
            var url = '{{ url("/select_stars", "star_id_url") }}'.replace('star_id_url', star_id);
            li.innerHTML = '<a href="'+url+'" target="_blank">'+star+'</a> <button class="deleteStar btn-delete" id="newstar'+idStar+'">X</button>';
            i.setAttribute("value", star);
            $("#starList").append(li).append(i);
            $('#star').val('');
        }
    });
    $("#starList").on('click', 'button.deleteStar', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove();
        $(":input[id='"+idDiv+"']").remove();
    });

    // ---------- Dodawanie nowych wytwórni ----------
    $('#studios').autocomplete({
        source: "{{url('/getstudioss')}}",
        minLength: 1,
        scroll: true,
        select: function(event, ui) {
            $('#studios').val(ui.item.value);
            $('#studios').attr("studios_id", ui.item.studios_id);
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>").appendTo(ul);
    };

    var idStudio = 0;
    $("#addStudios").click(function(){
        if ($("#studios").val()) {
            idStudio++;
            var li = document.createElement("li");
            li.className = "studios";
            li.setAttribute("id", 'newstudio'+idStudio);
            var i = document.createElement("INPUT");
            i.setAttribute("name", "multiStudios[]");
            i.setAttribute("type", "hidden");
            i.setAttribute("id", 'newstudio'+idStudio);
            var studios = document.getElementById('studios').value;
            var studios_id = $('#studios').attr("studios_id");
            var url = '{{ url("/select_studios", "studios_id_url") }}'.replace('studios_id_url', studios_id);
            li.innerHTML = '<a href="'+url+'" target="_blank">'+studios+'</a> <button class="deleteStudio btn-delete" id="newstudio'+idStudio+'">X</button>';
            i.setAttribute("value", studios);
            $("#studiosList").append(li).append(i);
            $('#studios').val('');
        }
    });
    $("#studiosList").on('click', 'button.deleteStudio', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove();
        $(":input[id='"+idDiv+"']").remove();
    });

    // ---------- Usuwanie już przypisanych (sekcja 5) — jeden handler zamiast dwóch identycznych ----------
    $("#tagList_db").on('click', 'button.deleteTagExsit', function() {
        var toDel = this.id.replace('id_', '');
        $("#id_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_tag')}}", data: 'delete_id='+toDel });
    });

    $("#starList_db").on('click', 'button.deleteStarExsit', function() {
        var toDel = this.id.replace('star_id_', '');
        $("#star_id_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_star')}}", data: 'delete_id='+toDel });
    });

    $("#studiosList_db").on('click', 'button.deleteStudioExsit', function() {
        var toDel = this.id.replace('studio_id_', '');
        $("#studio_id_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_studio')}}", data: 'delete_id='+toDel });
    });

});
</script>

@endsection
