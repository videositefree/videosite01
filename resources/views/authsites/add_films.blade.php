
@extends('layouts.app')

@section('title')VideoSite Dodaj Film @endsection

@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 


<script src="{{ asset('js/add_films.js') }}" defer></script> <!-- all script for forms -->
<script src="{{ asset('js/starrr.js') }}" defer></script> <!-- stars system -->
<script src="{{ asset('js/jquery.form.js') }}" defer></script> <!-- upload bar  -->


<!-- Sticky video player -->
<script>
function del_stick() {
    document.getElementById("button_video_sticky").style.display='inline';
    document.getElementById("button_video_sticky_block").style.display='none';
    document.getElementById("sticky_video").classList.remove("sticky_video_edit"); 
}

function btn_stick() {
    document.getElementById("button_video_sticky").style.display='none';
    document.getElementById("button_video_sticky_block").style.display='inline';
    document.getElementById("sticky_video").classList.add("sticky_video_edit"); 
}

function del_stick_2() {
    document.getElementById("button_video_sticky_2").style.display='inline';
    document.getElementById("button_video_sticky_block_2").style.display='none';
    document.getElementById("sticky_video_2").classList.remove("sticky_video_edit"); 
}

function btn_stick_2() {
    document.getElementById("button_video_sticky_2").style.display='none';
    document.getElementById("button_video_sticky_block_2").style.display='inline';
    document.getElementById("sticky_video_2").classList.add("sticky_video_edit"); 
}



</script>


<!-- Mesage return when   --> 


<div class="col-sm-12 text-center">

  
    
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

<div class="col-sm-12 text-center" style="padding-top: 30px;">

<!-- ERROS MESAGE WHEN FFMPEG DON`T WORKING! -->
@if(!empty($errorsMsg))
  <div class="alert alert-success"> {{ $errorsMsg }} </br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
  
        <i class="fas fa-question-circle" style="color: black;" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
        pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania miniatury oraz zwiastunu filmu.
        ">        
        </i>

  </div>

@endif


</div>



<div class="col-lg-6 d-none d-lg-block" style="padding-top: 100px">

  <div class="conatainer" id="sticky_video">
    <div class="row d-flex justify-content-center">

      <video class="col-lg-12" width="500" height="350"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
        <source src="" id="video_here">
      </video>     
      <script>
        const heading = document.querySelector('video');
        heading.style.height = "100%";
     
      </script> 

      <div style="padding-top: 20px;">
          <button class="btn btn-danger" onclick="del_stick()" id="button_video_sticky_block" style="display: none;">Zablokuj Film</button>
          <button class="btn btn-success" onclick="btn_stick()" id="button_video_sticky">Odblokuj Film</button>
          
      </div>

    </div>
  </div>
</div>


<!-------------------------------------------------------------- RIGHT SIDE ----------------------------------------------------------------->


<div class="form-right col-lg-6">


  <h3>Dodaj nowy film:</h3>

  <div class="row d-flex justify-content-center d-none d-sm-block d-lg-none" id="sticky_video_2" style="padding-top: 20px;">

      <video class="col-lg-12" width="500" height="250"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
        <source src="" id="video_here_2">
      </video>

      <div style="padding-top: 10px;">
          <button class="btn btn-danger" onclick="del_stick_2()" id="button_video_sticky_block_2" style="display: none;">Zablokuj Film</button>
          <button class="btn btn-success" onclick="btn_stick_2()" id="button_video_sticky_2">Odblokuj Film</button>
          
      </div>

    </div>

  <div class="conatainer" style="margin-top: 60px;">
    <div class="row d-flex justify-content-center">
      <div class="col-sm-11">
        <form action="{{ url('/add_films_save') }}" method="POST" enctype="multipart/form-data" id="add_films">

          @csrf <!-- {{ csrf_field() }} -->

          <div class="form-group">
            <div class="custom-file mb-3">
              <input type="file" class="custom-file-input" id="film" name="file">
              <label class="custom-file-label" for="film" style="text-align: left" >Wybierz Plik</label>
              @error('file')
                <div class="alert alert-danger valid_msg">{{ $message }}</div>
              @enderror
              @if($errors->has('file'))
                  <div class="error">{{ $errors->first('file') }}</div>
              @endif
            </div>
          </div>


          <div class="form-group">

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck" data-toggle='collapse' data-target='#collapsediv1'>
              <label class="custom-control-label" for="customCheck">Trailer filmowy</label>
                <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Trailer tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>
            </div>
  
          </div>
              
          <div class="form-group">
            <div id='collapsediv1' class='collapse div1'>
              <div>
                  <div class="col-12">
                  <input type="time" name="short_time" id="short_time" step="1" value="00:00:00" max="05:00:00" style="border-radius: 25px; width:35%; text-align:center">
                  <input name="time_sec" type="hidden" value="720"/> <!-- get time in second -->  
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">

            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="thumbchk" data-toggle='collapse' data-target='#thumb'>
              <label class="custom-control-label" for="thumbchk">Miniaturka Filmu</label>
              <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Miniaturke tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>

            </div>
            
          </div>


          <div class="form-group">
            <div id='thumb' class='collapse div2'>
              <div>
                  <div class="col-12">
                    <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00" style="border-radius: 25px; width:35%; text-align:center">
                    <input name="time_sec_thumbnail" type="hidden" value="720"/> <!-- get time in second -->
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="film_name" name="film_name" placeholder="Tytuł Filmu" value="{{ old('film_name') }}">
            <span class="input-group-append">
              <button class="btn btn-outline-secondary border-left-0 border" type="button" onclick="document.getElementById('film_name').value = ''">
                <i class="fa fa-times"></i>
              </button>
            </span>
            </div>
            @error('film_name')
                <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror            
          </div>          
          <script type="text/javascript">
            $(document).ready(function() {
              $('#film').on('input', function() {
                var name = document.getElementById("film").value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, ""); 
                var change = $("input[id='film_name']");
                change.val(name);  

              });
            });
          </script>


            <div class="form-group">
                <div class="input-group"> 
                <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj Nowe Tagi" autocomplete="off">
                    <div class="input-group-append">
                    <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                    </div>
                </div>
            </div>

          <div class="form-group">
              <div style="padding: 10px;" id="tagList">

              @if ($errors->any())
              Ze wzgledów bezpieczeństwa formularz został usunięty.</br>
              Zapisaliśmy ostanie 40 tagów do filmu które chciałeś dodać:</br></br>
              {{ old('multiTag.0') }}&nbsp
              {{ old('multiTag.1') }}&nbsp
              {{ old('multiTag.2') }}&nbsp
              {{ old('multiTag.3') }}&nbsp
              {{ old('multiTag.4') }}&nbsp
              {{ old('multiTag.5') }}&nbsp
              {{ old('multiTag.6') }}&nbsp
              {{ old('multiTag.7') }}&nbsp
              {{ old('multiTag.8') }}&nbsp
              {{ old('multiTag.9') }}&nbsp
              {{ old('multiTag.10') }}&nbsp
              {{ old('multiTag.11') }}&nbsp
              {{ old('multiTag.12') }}&nbsp
              {{ old('multiTag.13') }}&nbsp
              {{ old('multiTag.14') }}&nbsp
              {{ old('multiTag.15') }}&nbsp
              {{ old('multiTag.16') }}&nbsp
              {{ old('multiTag.17') }}&nbsp
              {{ old('multiTag.18') }}&nbsp
              {{ old('multiTag.19') }}&nbsp
              {{ old('multiTag.20') }}&nbsp
              {{ old('multiTag.21') }}&nbsp
              {{ old('multiTag.22') }}&nbsp
              {{ old('multiTag.23') }}&nbsp
              {{ old('multiTag.24') }}&nbsp
              {{ old('multiTag.25') }}&nbsp
              {{ old('multiTag.26') }}&nbsp
              {{ old('multiTag.27') }}&nbsp
              {{ old('multiTag.28') }}&nbsp
              {{ old('multiTag.29') }}&nbsp
              {{ old('multiTag.30') }}&nbsp
              {{ old('multiTag.31') }}&nbsp
              {{ old('multiTag.32') }}&nbsp
              {{ old('multiTag.33') }}&nbsp
              {{ old('multiTag.34') }}&nbsp
              {{ old('multiTag.35') }}&nbsp
              {{ old('multiTag.36') }}&nbsp
              {{ old('multiTag.37') }}&nbsp
              {{ old('multiTag.38') }}&nbsp
              {{ old('multiTag.39') }}&nbsp
              {{ old('multiTag.40') }}&nbsp</br></br>
              
            @endif

              </div>
          </div>



            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="extra_tag_stars" id="extra_tag_stars" value="0">
              <label class="custom-control-label" for="extra_tag_stars">Dodaj tagi gwiazdy</label>
              <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do gwiazdy (wykorzystywana baza tagów filmowych)."></i>

            </div>
            <div style="padding-top: 10px;"></div>

          <div class="form-group">
            <div class="input-group"> 
                <input type="text" class="form-control ui-autocomplete-input" id="star" placeholder="Dodaj Nowe Gwiazdy" autocomplete="off">
                <div class="input-group-append">
                    <div class="btn btn-success text-white" id="addStar">Dodaj</div>
                </div>
            </div>
          </div>

          <div class="form-group">
            <div style="padding: 10px;" id="starList">

            @if ($errors->any())
              
               Zapisaliśmy ostanie 15 aktorów do filmu których chciałeś dodać:</br></br>
              {{ old('multiStar.0') }}&nbsp
              {{ old('multiStar.1') }}&nbsp
              {{ old('multiStar.2') }}&nbsp
              {{ old('multiStar.3') }}&nbsp
              {{ old('multiStar.4') }}&nbsp
              {{ old('multiStar.5') }}&nbsp
              {{ old('multiStar.6') }}&nbsp
              {{ old('multiStar.7') }}&nbsp
              {{ old('multiStar.8') }}&nbsp
              {{ old('multiStar.9') }}&nbsp
              {{ old('multiStar.10') }}&nbsp
              {{ old('multiStar.11') }}&nbsp
              {{ old('multiStar.12') }}&nbsp
              {{ old('multiStar.13') }}&nbsp
              {{ old('multiStar.14') }}&nbsp
              {{ old('multiStar.15') }}&nbsp</br></br>
             
            @endif

            </div>
          </div>



            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="extra_tag_studios" name="extra_tag_studios" value="testatestetsaet">
              <label class="custom-control-label" for="extra_tag_studios">Dodaj tagi wytwórni</label>
              <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do wytwórni (wykorzystywana baza tagów filmowych)."></i>

            </div>
            <div style="padding-top: 10px;"></div>

            <div class="form-group">
              <div class="input-group"> 
                  <input type="text" class="form-control ui-autocomplete-input" id="studios" placeholder="Dodaj Nowe Wytwórnie" autocomplete="off">
                  <div class="input-group-append">
                      <div class="btn btn-success text-white" id="addStudios">Dodaj</div>
                  </div>
              </div>
            </div>

          <div class="form-group">
              <div style="padding: 10px;" id="studiosList">

              @if ($errors->any())
                
               Zapisaliśmy ostanie 5 wytwórni które chciałeś dodać:</br></br>
              {{ old('multiStudios.0') }}&nbsp
              {{ old('multiStudios.1') }}&nbsp
              {{ old('multiStudios.2') }}&nbsp
              {{ old('multiStudios.3') }}&nbsp
              {{ old('multiStudios.4') }}&nbsp
              {{ old('multiStudios.5') }}&nbsp</br></br>
              
             
              @endif

              </div>
          </div>
          <div>
          @php
                $directory = "../../filmy/";
                if (!file_exists($directory)) {
                echo '
                  
                  <div class="form-group">
                    <input type="text" class="form-control" style="text-align: center; background-color: red;
                      color: white;" placeholder="BRAK FOLDERU DO ZAPISU!" disabled>
                  </div>
                  
                ';
                }else{

                  echo '
                  <div class="form-group">
                    <label for="exampleFormControlSelect">Wybierz nazwę folder do zapisu</label>
                    <select class="browser-default custom-select " id="exampleFormControlSelect1" name="katalog">     
                        <option></option>
                  ';
                
                 if ($handle = opendir('../../filmy/')) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && $file != "thumbnail" && $file != "short" && $file != "conversion" && $file != "cut" && $file != "join") {
                        echo "<option>".$file."</option>";
                    }
                }
                closedir($handle);}}@endphp
              
            </select>

            @if ($errors->any())
            </br></br>
            Wybrany katalog do zapisu filmu: &nbsp;&nbsp;{{ old('katalog') }}
            @endif
          </div>

          <div class="form-group">
            <input type="hidden" name="star" id="save_rating" value="">

            Ocena Filmu:
            <div class='ratings' style="padding-bottom:15px;"></div>

            @if ($errors->any())
            </br>
            Wybrana ocena filmu: &nbsp;{{ old('star') }}
            @endif
          </div>   

          <div id="div1" style="padding-bottom: 25px;">
            <button type="submit" name="submit" id="submit" class="btn btn-info"> Zapisz</button>
          </div>
          <div id="message"></div>
        </form>
      </div> 
    </div> 
  </div> 
</div>



<!---- NOT SHOW MODAL WHEN INPUT FILE IS NULL  --->
<script>
  $('#submit').click(function(){
    if($('#film_name').val() !== '' && $('#film').val() !== ''){
      $('#div2').modal();
      $('#div2').modal('toggle');
  $('#div2').modal('show');

    }
});

</script>

                
    <div class="modal fade" id="div2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false" >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content modtext" >
        <div class="modal-header" >
          <h5 class="modal-title text-center"><tw>Przesyłanie Pliku!</tw></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row justify-content-center text-center">

            </div>
          <tw>Prosimy o chwilę cierpliwości.</br>
              Strona zostanie automatycznie odświeżona po przesłaniu pliku i stworzeniu zwiastunu filmowego.</tw><br>
               
           <div class="form-group">

                  <img src="{{ asset('icon/app.blade/reload.gif') }}" style="width: 70px; height: 70px;"></img>
              
                
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


 
  


<!---- display tags autocomplete --->

<script>
$(function() {
  $('#tag').autocomplete({
      source: "{{url('/gettag')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#tag').val(ui.item.value);

        var preview = document.getElementById("tag"); //getElementById instead of querySelectorAll
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


//================================= display stars autocomplete =======================//
  
$(function() {
  $('#star').autocomplete({
      source: "{{url('/getstar')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#star').val(ui.item.value);
        var preview = document.getElementById("star"); //getElementById instead of querySelectorAll
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


//================================= display studios autocomplete =======================//
  
$(function() {
  $('#studios').autocomplete({
      source: "{{url('/getstudios')}}",
      minLength: 1,
      scroll:true,
      select: function(event, ui)
      {
        $('#studios').val(ui.item.value);
        var preview = document.getElementById("studios"); //getElementById instead of querySelectorAll
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