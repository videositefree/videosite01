
@extends('layouts.admin')
@section('title')VideoSite Dodaj Gwiazdę @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_stars') }}">Gwiazdy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dodaj Gwiazdę</li>
</ol>
@endsection

@section('content')

<div class="upload-page">

    <h1 class="upload-page__title">Dodaj nową gwiazdę</h1>

    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <div class="upload-layout">

        <div class="upload-preview">
            <div class="upload-preview__card">
                <div class="resizable-video" style="aspect-ratio:1/1;">
                    <img src="{{ asset('icon/app.blade/notfing_found.png') }}" id="video_here" style="width:100%; height:100%; object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="upload-form-wrap">
            <form action="{{url('/save_stars')}}" method="POST" enctype="multipart/form-data" id="add_films">
                @csrf

                <section class="upload-section">
                    <h2><span class="upload-step">1</span> Zdjęcie</h2>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="thumbnail_stars" name="thumbnail_stars">
                        <label class="custom-file-label @error('thumbnail_stars') form-error @enderror" for="thumbnail_stars">Wybierz zdjęcie</label>
                    </div>
                    @error('thumbnail_stars')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </section>

                <section class="upload-section">
                    <h2><span class="upload-step">2</span> Dane</h2>

                    <div class="input-group" style="margin-bottom: 14px;">
                        <input type="text" class="form-control @error('stars_name') form-error @enderror" id="stars_name" name="stars_name" placeholder="Imię i nazwisko gwiazdy">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('stars_name').value = ''">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('stars_name')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror

                    <label for="chose_sex">Wybierz płeć</label>
                    <select class="form-control @error('chose_sex') form-error @enderror" name="chose_sex" id="chose_sex" style="margin-bottom: 14px;">
                        <option></option>
                        <option value="1">Mężczyzna</option>
                        <option value="2">Kobieta</option>
                    </select>
                    @error('chose_sex')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror

                    <label>Ocena gwiazdy</label>
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
                    <h2><span class="upload-step">3</span> Tagi</h2>

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Tagi gwiazd</label>
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
                        <label class="upload-subgroup__label">Tagi filmów</label>
                        <div class="input-group">
                            <input type="text" class="form-control ui-autocomplete-input" id="tag_films" placeholder="Dodaj nowe tagi filmów" autocomplete="off">
                            <div class="input-group-append">
                                <div class="btn btn-success text-white" id="addTagFilms">Dodaj</div>
                            </div>
                        </div>
                        <small class="form-text text-muted">Korzystając z tagów filmowych, dodasz je automatycznie do filmów, wystarczy przypisać gwiazdę do filmu.</small>
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

                <section class="upload-section">
                    <h2><span class="upload-step">4</span> Rozmiar zdjęcia</h2>
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
                </section>

                <div class="upload-submit-row">
                    <button class="btn btn-success btn-lg">Wyślij</button>
                </div>

            </form>
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

$(function() {
    $('#tag').autocomplete({
        source: "{{url('/gettag_stars')}}",
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
