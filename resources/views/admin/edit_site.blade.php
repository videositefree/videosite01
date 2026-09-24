
@extends('layouts.admin')

@section('title')VideoSite Edytuj Stronę @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_sites') }}">Strony</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edytowanie Strony</li>
</ol>
@endsection

@section('content')

<div class="upload-page" style="max-width: 720px;">

    <h1 class="upload-page__title">Edytuj: {{$site->name}}</h1>

    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <section class="upload-section">
        <h2><span class="upload-step">1</span> Tagi</h2>

        <div class="upload-subgroup">
            <label class="upload-subgroup__label">Tagi dedykowane</label>
            @if(count($tags) > 0)
                <ul class="upload-pill-list" id="tagList_db">
                    @foreach ($tags as $tag)
                        <li class="tags" id="id_{{$tag->id}}">
                            <a href="{{url('/select_categories_sites', $tag->tag_id)}}" target="_blank">{{$tag->name}}</a>
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
            @if(count($tags_films) > 0)
                <ul class="upload-pill-list" id="tagList_db_films">
                    @foreach ($tags_films as $tag)
                        <li class="tags" id="filmid_{{$tag->id}}">
                            <a href="{{url('/select_categories_sites_db_films', $tag->tag_id)}}" target="_blank">{{$tag->name}}</a>
                            <button class="deleteTagExsit btn-delete" id="filmid_{{$tag->id}}">X</button>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="color:var(--muted);">Brak tagów do wyświetlenia.</p>
            @endif
        </div>

        <form action="{{url('/sites_tag_add_edit_site')}}" method="POST" enctype="multipart/form-data" id="add_tags_sites">
            @csrf
            <input value="{{$site->id}}" name="id" hidden>

            <div class="upload-subgroup">
                <label class="upload-subgroup__label">Dodaj nowe tagi</label>
                <div class="input-group">
                    <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj nowe tagi" autocomplete="off">
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
                <label class="upload-subgroup__label">Dodaj nowe tagi filmów</label>
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

    <section class="upload-section">
        <h2><span class="upload-step">2</span> Dane</h2>

        <form action="{{url('/edit_site_save')}}" method="POST" enctype="multipart/form-data" id="edit_site_main">
            @csrf

            <div class="form-group">
                <input type="text" class="form-control @error('site_name') form-error @enderror" id="site_name" value="{{$site->name}}" name="site_name" placeholder="Nazwa">
                @error('site_name')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <input type="text" class="form-control @error('site_link') form-error @enderror" value="{{$site->link}}" name="site_link" placeholder="Link do strony">
                @error('site_link')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <textarea class="form-control @error('site_description') form-error @enderror" name="site_description" placeholder="Opis">{{$site->description}}</textarea>
                @error('site_description')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
            </div>

            <input type="hidden" value="{{$site->id}}" name="sites_id">

            <div class="form-group">
                <label>Ocena strony: <b>{{$site->rating}}</b></label>
                <div class="upload-toggle-row" style="flex-wrap:wrap; gap:14px;">
                    @for ($i = 1; $i <= 6; $i++)
                        <label style="margin:0;">
                            <input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}" {{ $site->rating == $i ? 'checked' : '' }}> {{ $i }}
                        </label>
                    @endfor
                </div>
                @error('rating')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
            </div>
            <input type="hidden" value="{{$site->rating}}" name="hidden_rating">

            <div class="upload-submit-row">
                <button class="btn btn-success btn-lg">Wyślij</button>
            </div>
        </form>
    </section>

</div>

<script>
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

$(function() {

    $('#tag').autocomplete({
        source: "{{url('/gettag_sites')}}",
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
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_tag_sites')}}", data: 'delete_id='+toDel });
    });

    $("#tagList_db_films").on('click', 'button.deleteTagExsit', function() {
        var toDel = this.id.replace('filmid_', '');
        $("#filmid_"+toDel).remove();
        $.ajax({ type: 'POST', url: "{{url('/edit_films_ajax_delete_tag_sites')}}", data: 'delete_id='+toDel });
    });

});
</script>

@endsection
