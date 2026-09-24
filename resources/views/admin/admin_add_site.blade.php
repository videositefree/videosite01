
@extends('layouts.admin')
@section('title')VideoSite Dodaj Stronę @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_sites') }}">Strony</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dodaj Stronę</li>
</ol>
@endsection

@section('content')

<div class="upload-page" style="max-width: 720px;">

    <h1 class="upload-page__title">Dodaj nową stronę</h1>

    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <form action="{{url('/save_site')}}" method="POST" enctype="multipart/form-data" id="add_films">
        @csrf

        <section class="upload-section">
            <h2><span class="upload-step">1</span> Dane</h2>

            <div class="form-group">
                <input type="text" class="form-control @error('site_name') form-error @enderror" name="site_name" placeholder="Nazwa">
                @error('site_name')
                    <div class="alert alert-danger valid_msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="url" class="form-control @error('site_link') form-error @enderror" name="site_link" placeholder="Link do strony" value="https://">
                <small class="form-text text-muted">Pamiętaj, aby link do strony zawierał https://… W innym wypadku nie będzie możliwości dodania linku!</small>
                @error('site_link')
                    <div class="alert alert-danger valid_msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <textarea class="form-control @error('site_description') form-error @enderror" name="site_description" placeholder="Opis"></textarea>
                @error('site_description')
                    <div class="alert alert-danger valid_msg">{{ $message }}</div>
                @enderror
            </div>

            <label>Ocena strony</label>
            <div class="upload-toggle-row" style="flex-wrap:wrap; gap:14px;">
                @for ($i = 1; $i <= 6; $i++)
                    <label style="margin:0;"><input type="radio" id="rating_{{ $i }}" name="rating" value="{{ $i }}"> {{ $i }}</label>
                @endfor
            </div>
            @error('rating')
                <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror
        </section>

        <section class="upload-section">
            <h2><span class="upload-step">2</span> Tagi</h2>

            <div class="upload-subgroup">
                <label class="upload-subgroup__label">Tagi stron</label>
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
                <label class="upload-subgroup__label">Tagi filmów</label>
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
        </section>

        <div class="upload-submit-row">
            <button class="btn btn-success btn-lg">Wyślij</button>
        </div>

    </form>

</div>

<script>
$(function() {
    $('#tag').autocomplete({
        source: "{{url('/gettag_sites')}}",
        minLength: 1,
        scroll:true,
        select: function(event, ui) { $('#tag').val(ui.item.value); }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
    };

    var id = 0;
    $("#addTag").click(function(){
        if($("#tag").val() ) {
            id++;
            var li = document.createElement("li");
            li.className = "tags";
            li.setAttribute("id", id);
            var i = document.createElement("INPUT");
            i.setAttribute("name","multiTag[]");
            i.setAttribute("type","hidden");
            i.setAttribute("id", id);
            var tag = document.getElementById('tag').value;
            li.innerHTML = " " + tag + '  <button class="deleteTag btn-delete" id="'+id+'">X</button>'
            i.setAttribute("value", tag);
            $("#tagList").append(li)
            $("#tagList").append(i)
            $('#tag').val('');
        }});

    $("#tagList").on('click', 'button.deleteTag', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove()
        $(":input[id='"+idDiv+"']").remove();
    });

    $("#tagList").on('click', 'button.deleteTagExsit', function() {
        var del_id = this.id;
        var toDel = del_id.replace('id_', '');
        $("#id_"+toDel).remove();
    });


    $('#tag_films').autocomplete({
        source: "{{url('/gettag')}}",
        minLength: 1,
        scroll:true,
        select: function(event, ui) { $('#tag_films').val(ui.item.value); }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
    };

    var idFilms = 0;
    $("#addTagFilms").click(function(){
        if($("#tag_films").val() ) {
            idFilms++;
            var li = document.createElement("li");
            li.className = "tags";
            li.setAttribute("id", 'films'+idFilms);
            var i = document.createElement("INPUT");
            i.setAttribute("name","multiTagFilms[]");
            i.setAttribute("type","hidden");
            i.setAttribute("id", 'films'+idFilms);
            var tag = document.getElementById('tag_films').value;
            li.innerHTML = " " + tag + '  <button class="deleteTag btn-delete" id="films'+idFilms+'">X</button>'
            i.setAttribute("value", tag);
            $("#tagListFilms").append(li)
            $("#tagListFilms").append(i)
            $('#tag_films').val('');
        }});

    $("#tagListFilms").on('click', 'button.deleteTag', function() {
        var idDiv = this.id;
        $("#"+idDiv).remove()
        $(":input[id='"+idDiv+"']").remove();
    });

    $("#tagListFilms").on('click', 'button.deleteTagExsit', function() {
        var del_id = this.id;
        var toDel = del_id.replace('id_', '');
        $("#id_"+toDel).remove();
    });
});
</script>

@endsection
