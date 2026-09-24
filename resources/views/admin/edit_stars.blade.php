
@extends('layouts.admin')

@section('title')VideoSite Edytuj Gwiazdę @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_stars') }}">Gwiazdy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edytowanie Gwiazdy</li>
</ol>
@endsection

@section('content')

<div class="upload-page">

    <h1 class="upload-page__title">Edytuj: {{$stars->name}}</h1>

    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <div class="upload-layout">

        <!-- ============ LEWA STRONA: podgląd zdjęcia ============ -->
        <div class="upload-preview">
            <div class="upload-preview__card">
                <label class="upload-subgroup__label">Ustawione</label>
                <div class="resizable-video" style="aspect-ratio:1/1; margin-bottom:16px;">
                    <img src="{{URL::asset("$stars->thumbnail")}}" style="width:100%; height:100%; object-fit:contain;">
                </div>

                <label class="upload-subgroup__label">Podgląd nowego (po wybraniu pliku)</label>
                <div class="resizable-video" style="aspect-ratio:1/1;">
                    <img src="{{ asset('icon/app.blade/notfing_found.png') }}" id="video_here" style="width:100%; height:100%; object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="upload-form-wrap">

            <!-- ============ 1. TAGI ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">1</span> Tagi</h2>

                <div class="upload-subgroup">
                    <label class="upload-subgroup__label">Tagi dedykowane</label>
                    @if(count($tags) > 0)
                        <ul class="upload-pill-list" id="tagList_db">
                            @foreach ($tags as $tag)
                                <li class="tags" id="id_{{$tag->id}}">
                                    <a href="{{url('/select_categories_stars', $tag->tag_id)}}" target="_blank">{{$tag->name}}</a>
                                    <button class="deleteTagExsit btn-delete" id="id_{{$tag->id}}">X</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--muted);">Brak tagów do wyświetlenia.</p>
                    @endif
                </div>

                <div class="upload-subgroup">
                    <label class="upload-subgroup__label">Tagi filmowe</label>
                    <small class="form-text text-muted">Korzystając z tagów filmowych, dodasz je automatycznie do filmów, wystarczy przypisać gwiazdę do filmu.</small>
                    @if(count($tags_films) > 0)
                        <ul class="upload-pill-list" id="tagList_db_films" style="margin-top:10px;">
                            @foreach ($tags_films as $tag)
                                <li class="tags" id="filmid_{{$tag->id}}">
                                    <a href="{{url('/select_categories_stars_db_films', $tag->tag_id)}}" target="_blank">{{$tag->name}}</a>
                                    <button class="deleteTagExsit btn-delete" id="filmid_{{$tag->id}}">X</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--muted); margin-top:10px;">Brak tagów do wyświetlenia.</p>
                    @endif
                </div>

                <form action="{{url('/stars_tag_add_edit_site')}}" method="POST" enctype="multipart/form-data" id="add_tags_stars">
                    @csrf
                    <input value="{{$stars->id}}" name="id" hidden>

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Dodaj tagi gwiazd</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj nowe tagi gwiazd" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="tagList">
                            @if ($errors->any())
                            <li class="upload-old-value">
                                Ze względów bezpieczeństwa formularz został zresetowany.<br>
                                Zapisaliśmy ostatnie 40 tagów, które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 40; $i++){{ old("multiTag.$i") }}&nbsp;@endfor
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Dodaj tagi filmów</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="tag_films" placeholder="Dodaj nowe tagi filmów" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addTagFilms">Dodaj</div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="tagListFilms">
                            @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 40 tagów, które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 40; $i++){{ old("multiTagFilms.$i") }}&nbsp;@endfor
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="upload-submit-row">
                        <button class="btn btn-success">Wyślij</button>
                    </div>
                </form>
            </section>

            <!-- ============ 2. DANE GWIAZDY ============ -->
            <section class="upload-section">
                <h2><span class="upload-step">2</span> Dane</h2>

                <form action="{{url('/edit_stars_save')}}" method="POST" enctype="multipart/form-data" id="edit_stars_main">
                    @csrf

                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="thumbnail_tags" name="thumbnail_stars">
                        <label class="custom-file-label" for="thumbnail_stars">Wybierz zdjęcie</label>
                    </div>

                    <a class="admin-quicklink" href="{{url('/open_folder_stars', $stars->id)}}" style="margin-bottom: 16px; display:inline-flex;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        Główny folder z gwiazdami
                    </a>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control @error('stars_name') form-error @enderror" name="stars_name" placeholder="Imię i nazwisko gwiazdy" value="{{$stars->name}}">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_stars_next', $stars->id)}}" title="Otwórz folder"></a>
                            </div>
                        </div>
                        @error('stars_name')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Wybierz płeć</label>
                        <select class="form-control @error('chose_sex') form-error @enderror" name="chose_sex">
                            <option></option>
                            <option value="1" @if($stars->sex == "male") selected @endif>Mężczyzna</option>
                            <option value="2" @if($stars->sex == "female") selected @endif>Kobieta</option>
                        </select>
                        @error('chose_sex')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>

                    <input type="hidden" value="{{$stars->id}}" name="stars_id">

                    <div class="form-group">
                        <label>Ocena gwiazdy: <b>{{$stars->rating}}</b></label>
                        <div class="upload-toggle-row" style="flex-wrap:wrap; gap:14px;">
                            @for ($i = 1; $i <= 6; $i++)
                                <label style="margin:0;">
                                    <input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}" {{ $stars->rating == $i ? 'checked' : '' }}> {{ $i }}
                                </label>
                            @endfor
                        </div>
                        @error('rating')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>
                    <input type="hidden" value="{{$stars->rating}}" name="hidden_rating">

                    <div class="form-group">
                        <small class="form-text text-muted">Zdjęcia w dobrej jakości zajmują bardzo dużo miejsca na dysku twardym.</small>
                        <small class="form-text text-muted">Możemy to zmienić, zmniejszając zdjęcia do rozmiaru 350×350px — wystarczy zaznaczyć "Tak".</small>
                        <small class="form-text text-muted" style="display:block; margin: 8px 0 14px;">
                            Jeśli nie odpowiadają Ci powyższe wymiary, możesz to zmienić
                            <a type="button" data-toggle="collapse" data-target="#hiddendiv" aria-expanded="false" aria-controls="collapseExample" onclick="checked_radio()"><b>tutaj</b></a>
                        </small>

                        <label for="yes_no_radio">Czy chcesz zmniejszyć zdjęcia?</label>
                        <div class="upload-toggle-row" style="margin-top:8px;">
                            <label><input type="radio" name="resize_img" id="resize_img_1" value="1"> Tak</label>
                            <label style="margin-left:20px;"><input type="radio" name="resize_img" id="resize_img_2" value="2" checked> Nie</label>
                        </div>

                        <div class="collapse upload-collapse" id="hiddendiv">
                            <div style="display:flex; gap:16px;">
                                <input type="text" class="form-control" name="height_img" placeholder="Wysokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                                <input type="text" class="form-control" name="width_img" placeholder="Szerokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                            </div>
                        </div>
                    </div>

                    <div class="upload-submit-row">
                        <button class="btn btn-success btn-lg">Wyślij</button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</div>

<script>
function checked_radio() {
    if ($('#hiddendiv').is('.collapse')) {
        document.getElementById("resize_img_1").checked = false;
        document.getElementById("resize_img_2").checked = true;
    }
    if ($('#hiddendiv').is('.collapse:not(.show)')) {
        document.getElementById("resize_img_1").checked = true;
        document.getElementById("resize_img_2").checked = false;
    }
}

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

$(function() {

    $('#tag').autocomplete({
        source: "{{url('/gettag_stars')}}",
        minLength: 1,
        scroll: true,
        select: function(event, ui) { $('#tag').val(ui.item.value); }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>").appendTo(ul);
    };

    var id = 0;
    $("#addTag").click(function(){
        if ($("#tag").val()) {
            id++;
            var li = document.createElement("li");
            li.className = "tags";
            li.setAttribute("id", 'newtag'+id);
            var i = document.createElement("INPUT");
            i.setAttribute("name", "multiTag[]");
            i.setAttribute("type", "hidden");
            i.setAttribute("id", 'newtag'+id);
            var tag = document.getElementById('tag').value;
            li.innerHTML = " " + tag + ' <button class="deleteTag btn-delete" id="newtag'+id+'">X</button>';
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

    $('#tag_films').autocomplete({
        source: "{{url('/gettag')}}",
        minLength: 1,
        scroll: true,
        select: function(event, ui) { $('#tag_films').val(ui.item.value); }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>").appendTo(ul);
    };

    var idFilms = 0;
    $("#addTagFilms").click(function(){
        if ($("#tag_films").val()) {
            idFilms++;
            var li = document.createElement("li");
            li.className = "tags";
            li.setAttribute("id", 'newtagfilm'+idFilms);
            var i = document.createElement("INPUT");
            i.setAttribute("name", "multiTagFilms[]");
            i.setAttribute("type", "hidden");
            i.setAttribute("id", 'newtagfilm'+idFilms);
            var tag = document.getElementById('tag_films').value;
            li.innerHTML = " " + tag + ' <button class="deleteTag btn-delete" id="newtagfilm'+idFilms+'">X</button>';
            i.setAttribute("value", tag);
            $("#tagListFilms").append(li).append(i);
            $('#tag_films').val('');
        }
    });
    $("#tagListFilms").on('click', 'button.deleteTag', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove();
        $(":input[id='"+idDiv+"']").remove();
    });

    // usuwanie już przypisanych — jeden handler zamiast dwóch identycznych
    $("#tagList_db").on('click', 'button.deleteTagExsit', function() {
        var toDel = this.id.replace('id_', '');
        $("#id_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_tag_stars')}}", data: 'delete_id='+toDel });
    });

    $("#tagList_db_films").on('click', 'button.deleteTagExsit', function() {
        var toDel = this.id.replace('filmid_', '');
        $("#filmid_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_tag_stars_films')}}", data: 'delete_id='+toDel });
    });

});
</script>

@endsection
