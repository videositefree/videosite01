
@extends('layouts.app')

@section('title')VideoSite Dodaj Film @endsection

@section('content')

<script src="{{ asset('js/add_films.js') }}" defer></script>
<script src="{{ asset('js/starrr.js') }}" defer></script>
<script src="{{ asset('js/jquery.form.js') }}" defer></script>

<script>
function del_stick() {
    document.getElementById("button_video_sticky").style.display='inline';
    document.getElementById("button_video_sticky_block").style.display='none';
    var el = document.getElementById("sticky_video");
    el.classList.remove("is-floating");
    el.style.left = ''; el.style.top = ''; el.style.width = ''; el.style.height = '';
}
function btn_stick() {
    document.getElementById("button_video_sticky").style.display='none';
    document.getElementById("button_video_sticky_block").style.display='inline';
    document.getElementById("sticky_video").classList.add("is-floating");
}
function del_stick_2() {
    document.getElementById("button_video_sticky_2").style.display='inline';
    document.getElementById("button_video_sticky_block_2").style.display='none';
    var el = document.getElementById("sticky_video_2");
    el.classList.remove("is-floating");
    el.style.left = ''; el.style.top = ''; el.style.width = ''; el.style.height = '';
}
function btn_stick_2() {
    document.getElementById("button_video_sticky_2").style.display='none';
    document.getElementById("button_video_sticky_block_2").style.display='inline';
    document.getElementById("sticky_video_2").classList.add("is-floating");
}
</script>

<div class="upload-page">

    <div class="col-sm-12 text-center">
        @if (\Session::has('msg_success'))
          <div class="alert alert-success">
              <ul>{!! \Session::get('msg_success') !!}</ul>
          </div>
        @endif
        @if (\Session::has('msg_errors'))
          <div class="alert alert-danger">
              <ul>{!! \Session::get('msg_errors') !!}</ul>
          </div>
        @endif
    </div>

    @if(!empty($errorsMsg))
    <div class="alert alert-success upload-ffmpeg-alert">
        {{ $errorsMsg }}<br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
        pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania miniatury oraz zwiastunu filmu."></i>
    </div>
    @endif

    <h1 class="upload-page__title">Dodaj nowy film</h1>

    <div class="upload-layout">

        <!-- ============ LEWA STRONA: podgląd wideo (desktop) ============ -->
        <div class="upload-preview d-none d-lg-block">
            <div class="upload-preview__card" id="sticky_video">
                <div class="upload-preview__draghandle" data-drag-handle title="Przeciągnij, aby przenieść podgląd">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="6" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="18" r="1"/></svg>
                    Przeciągnij, aby przenieść
                </div>
                <div class="resizable-video">
                    <video poster="{{ asset('icon/app.blade/logo_video.png') }}" controls>
                        <source src="" id="video_here">
                    </video>
                </div>
                <p class="upload-preview__hint">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21l6-6m0 0l6 6M9 15V4m6 0l6 6m0 0l-6 6m6-6H10"/></svg>
                    Przeciągnij za prawy dolny róg, aby zmienić rozmiar podglądu.
                </p>
                <div class="upload-preview__actions">
                    <button class="btn btn-delete" onclick="del_stick()" id="button_video_sticky_block" style="display: none;">Przypnij na miejscu</button>
                    <button class="btn btn-success" onclick="btn_stick()" id="button_video_sticky">Odblokuj i przesuń</button>
                </div>
            </div>
        </div>

        <!-- ============ PRAWA STRONA: formularz ============ -->
        <div class="upload-form-wrap">

            <!-- Podgląd wideo (mobile / tablet) -->
            <div class="upload-preview d-block d-lg-none">
                <div class="upload-preview__card" id="sticky_video_2">
                    <div class="upload-preview__draghandle" data-drag-handle title="Przeciągnij, aby przenieść podgląd">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="6" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="18" r="1"/></svg>
                        Przeciągnij, aby przenieść
                    </div>
                    <div class="resizable-video resizable-video--sm">
                        <video poster="{{ asset('icon/app.blade/logo_video.png') }}" controls>
                            <source src="" id="video_here_2">
                        </video>
                    </div>
                    <div class="upload-preview__actions">
                        <button class="btn btn-delete" onclick="del_stick_2()" id="button_video_sticky_block_2" style="display: none;">Przypnij na miejscu</button>
                        <button class="btn btn-success" onclick="btn_stick_2()" id="button_video_sticky_2">Odblokuj i przesuń</button>
                    </div>
                </div>
            </div>

            <form action="{{ url('/add_films_save') }}" method="POST" enctype="multipart/form-data" id="add_films">
                @csrf

                <!-- 1. PLIK -->
                <section class="upload-section">
                    <h2><span class="upload-step">1</span> Plik wideo</h2>

                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="film" name="file">
                        <label class="custom-file-label" for="film">Wybierz Plik</label>
                    </div>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                    @if($errors->has('file'))
                        <div class="error">{{ $errors->first('file') }}</div>
                    @endif
                </section>

                <!-- 2. ZWIASTUN I MINIATURKA -->
                <section class="upload-section">
                    <h2><span class="upload-step">2</span> Zwiastun i miniaturka</h2>

                    <div class="upload-toggle-row">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck" data-toggle='collapse' data-target='#collapsediv1'>
                            <label class="custom-control-label" for="customCheck">Trailer filmowy</label>
                        </div>
                        <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Trailer tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>
                    </div>
                    <div id='collapsediv1' class='collapse upload-collapse'>
                        <input type="time" name="short_time" id="short_time" step="1" value="00:00:00" max="05:00:00">
                        <input name="time_sec" type="hidden" value="720"/>
                    </div>

                    <div class="upload-toggle-row" style="margin-top: 14px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="thumbchk" data-toggle='collapse' data-target='#thumb'>
                            <label class="custom-control-label" for="thumbchk">Miniaturka Filmu</label>
                        </div>
                        <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Miniaturke tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>
                    </div>
                    <div id='thumb' class='collapse upload-collapse'>
                        <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00">
                        <input name="time_sec_thumbnail" type="hidden" value="720"/>
                    </div>
                </section>

                <!-- 3. SZCZEGÓŁY -->
                <section class="upload-section">
                    <h2><span class="upload-step">3</span> Szczegóły filmu</h2>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="film_name" name="film_name" placeholder="Tytuł Filmu" value="{{ old('film_name') }}">
                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('film_name').value = ''">
                                    <i class="fa fa-times"></i>
                                </button>
                            </span>
                        </div>
                        @error('film_name')
                            <div class="alert alert-danger valid_msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#film').on('input', function() {
                                var name = document.getElementById("film").value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, "");
                                $("input[id='film_name']").val(name);
                            });
                        });
                    </script>

                    <div>
                    @php
                        $directory = "../../filmy/";
                        if (!file_exists($directory)) {
                            echo '<div class="form-group"><input type="text" class="form-control upload-missing-dir" placeholder="BRAK FOLDERU DO ZAPISU!" disabled></div>';
                        } else {
                            echo '<div class="form-group"><label for="exampleFormControlSelect">Wybierz nazwę folder do zapisu</label>
                            <select class="browser-default custom-select" id="exampleFormControlSelect1" name="katalog"><option></option>';
                            if ($handle = opendir('../../filmy/')) {
                                while (false !== ($file = readdir($handle))) {
                                    if ($file != "." && $file != ".." && $file != "thumbnail" && $file != "short" && $file != "conversion" && $file != "cut" && $file != "join") {
                                        echo "<option>".$file."</option>";
                                    }
                                }
                                closedir($handle);
                            }
                            echo '</select></div>';
                        }
                    @endphp
                        @if ($errors->any())
                        <div class="upload-old-value">Wybrany katalog do zapisu filmu: <strong>{{ old('katalog') }}</strong></div>
                        @endif
                    </div>
                </section>

                <!-- 4. TAGI / GWIAZDY / WYTWÓRNIE -->
                <section class="upload-section">
                    <h2><span class="upload-step">4</span> Tagi, gwiazdy, wytwórnie</h2>

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Tagi filmu</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj Nowe Tagi" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="tagList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 40 tagów do filmu które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 40; $i++){{ old("multiTag.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="extra_tag_stars" id="extra_tag_stars" value="0">
                            <label class="custom-control-label" for="extra_tag_stars">Dodaj tagi gwiazdy</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do gwiazdy (wykorzystywana baza tagów filmowych)."></i>
                        </div>

                        <label class="upload-subgroup__label" style="margin-top:14px;">Gwiazdy</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="star" placeholder="Dodaj Nowe Gwiazdy" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addStar">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="starList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 15 aktorów do filmu których chciałeś dodać:<br>
                                @for ($i = 0; $i <= 15; $i++){{ old("multiStar.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="extra_tag_studios" name="extra_tag_studios" value="testatestetsaet">
                            <label class="custom-control-label" for="extra_tag_studios">Dodaj tagi wytwórni</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do wytwórni (wykorzystywana baza tagów filmowych)."></i>
                        </div>

                        <label class="upload-subgroup__label" style="margin-top:14px;">Wytwórnie</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="studios" placeholder="Dodaj Nowe Wytwórnie" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addStudios">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="studiosList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 5 wytwórni które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 5; $i++){{ old("multiStudios.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>
                </section>

                <!-- 5. OCENA -->
                <section class="upload-section">
                    <h2><span class="upload-step">5</span> Ocena filmu</h2>
                    <input type="hidden" name="star" id="save_rating" value="">
                    <div class='ratings'></div>
                    @if ($errors->any())
                        <div class="upload-old-value">Wybrana ocena filmu: {{ old('star') }}</div>
                    @endif
                </section>

                <div class="upload-submit-row" id="div1">
                    <button type="submit" name="submit" id="submit" class="btn btn-success btn-lg">Zapisz film</button>
                </div>
                <div id="message"></div>

            </form>
        </div>
    </div>
</div>

<script>
  $('#submit').click(function(){
    if($('#film_name').val() !== '' && $('#film').val() !== ''){
      $('#div2').modal();
      $('#div2').modal('toggle');
      $('#div2').modal('show');
    }
  });
</script>

<div class="modal fade" id="div2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-header">
                <h5 class="modal-title text-center"><tw>Przesyłanie Pliku!</tw></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row justify-content-center text-center"></div>
            <tw>Prosimy o chwilę cierpliwości.<br>
                Strona zostanie automatycznie odświeżona po przesłaniu pliku i stworzeniu zwiastunu filmowego.</tw><br>
            <div class="form-group">
                <img src="{{ asset('icon/app.blade/reload.gif') }}" style="width: 70px; height: 70px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- autocomplete: tagi -->
<script>
$(function() {
  $('#tag').autocomplete({
      source: "{{url('/gettag')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#tag').val(ui.item.value);
        var preview = document.getElementById("tag");
        preview.setAttribute("tag_id", ui.item.tag_id);
      }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
       };
});

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
          var tag_id = $('#tag').attr("tag_id")
          url= '{{ url("/select_categories", "tag_id_url") }}';
          url = url.replace('tag_id_url', tag_id);

          li.innerHTML =  '<a href="'+url+'" target="_blank"> '  + tag +' </a> <button class=\"deleteTag btn-delete\" id=\"'+id+'\">X</button>'
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


// autocomplete: gwiazdy
$(function() {
  $('#star').autocomplete({
      source: "{{url('/getstar')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#star').val(ui.item.value);
        var preview = document.getElementById("star");
        preview.setAttribute("star_id", ui.item.star_id);
      }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
    };
});

var id = 0;
$("#addStar").click(function(){
  if($("#star").val() ) {
          id++;
      var li = document.createElement("li");
          li.className = "starr";
          li.setAttribute("id", id);
      var i = document.createElement("INPUT");
          i.setAttribute("name","multiStar[]");
          i.setAttribute("type","hidden");
          i.setAttribute("id", id);
      var star = document.getElementById('star').value;
      var star_id = $('#star').attr("star_id")
      url= '{{ url("/select_stars", "star_id_url") }}';
      url = url.replace('star_id_url', star_id);

      li.innerHTML =  '<a href="'+url+'" target="_blank"> ' + star + '  <button class=\"deleteStar btn-delete\" id=\"'+id+'\">X</button>'
      i.setAttribute("value", star);

      $("#starList").append(li)
      $("#starList").append(i)
      $('#star').val('');
  }});

$("#starList").on('click', 'button.deleteStar', function() {
  var idDiv = this.id;
  $("#"+idDiv).remove()
  $(":input[id='"+idDiv+"']").remove();
});

$("#starList").on('click', 'button.deleteStarExsit', function() {
  var del_id = this.id;
  var toDel = del_id.replace('id_', '');
  $("#id_"+toDel).remove();
});


// autocomplete: wytwórnie
$(function() {
  $('#studios').autocomplete({
      source: "{{url('/getstudios')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#studios').val(ui.item.value);
        var preview = document.getElementById("studios");
        preview.setAttribute("studios_id", ui.item.studios_id);
      }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
    };
});

var id = 0;
$("#addStudios").click(function(){
  if($("#studios").val() ) {
          id++;
      var li = document.createElement("li");
          li.className = "studios";
          li.setAttribute("id", id);
      var i = document.createElement("INPUT");
          i.setAttribute("name","multiStudios[]");
          i.setAttribute("type","hidden");
          i.setAttribute("id", id);
      var studios = document.getElementById('studios').value;
      var studios_id = $('#studios').attr("studios_id")
      url= '{{ url("/select_studios", "studios_id_url") }}';
      url = url.replace('studios_id_url', studios_id);

      li.innerHTML =  '<a href="'+url+'" target="_blank"> ' + studios + '  <button class=\"deleteStar btn-delete\" id=\"'+id+'\">X</button>'
      i.setAttribute("value", studios);

      $("#studiosList").append(li)
      $("#studiosList").append(i)
      $('#studios').val('');
  }});

$("#studiosList").on('click', 'button.deleteStar', function() {
  var idDiv = this.id;
  $("#"+idDiv).remove()
  $(":input[id='"+idDiv+"']").remove();
});

$("#studiosList").on('click', 'button.deleteStarExsit', function() {
  var del_id = this.id;
  var toDel = del_id.replace('id_', '');
  $("#id_"+toDel).remove();
});
</script>

@endsection
