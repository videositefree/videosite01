
@extends('layouts.admin')

@section('title')VideoSite Edytuj Film @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_films') }}">Filmy</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edytowanie Filmu</li>
</ol>
@endsection

@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 
<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Edytowanie Filmu Nr: {{$films->id}} <h1>
<a class="btn btn-outline-secondary fas fa-tv" href="{{url('/watch', $films->id)}}" > Film - Strona Główna</a>
</div>


    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">

    <!-- ERROS MESAGE WHEN FFMPEG DON`T WORKING! -->
        @if(!empty($errorsMsg))
        <div class="alert alert-danger"> {{ $errorsMsg }} </br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
        
                <i class="fas fa-question-circle" style="color: black;" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
                pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania miniatury oraz zwiastunu filmu.
                ">        
                </i>

        </div>

        @endif
    
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


<div class="col-sm-6">
    <form action="{{url('/edit_films_save')}}" method="POST" enctype="multipart/form-data" id="add_films">

    @csrf <!-- {{ csrf_field() }} -->


    <div class="form-group">
        <label for="film_name">Tytuł filmu:</label>
        <input type="input" class="form-control @error('film_name') form-error @enderror" name="film_name" value="{{$films->name}}" id="film_name" >
        @error('film_name')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
        @enderror
    </div>

    <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film', $films->id)}}" style="margin-bottom: 20px;"> Główny folder z filmami</a>
    <div class="form-group">
        <label for="url">Pełna ścieżka dostępu do filmu:</label><br>
        
        <div class="input-group">            

            <input type="input" class="form-control @error('url') form-error @enderror" name="url" value="{{$films->url}}" id="url" > 
            <div class="input-group-append">
                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_next', $films->id)}}" ></a>   
            </div>

            @error('url')
                <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror
        </div>               
      
    </div>

    <div class="form-group">
        <label for="short">Pełna ścieżka dostępu do traileru filmowego:</label>
        <div class="input-group">     

            <input type="input" class="form-control @error('short') form-error @enderror" name="short" value="{{$films->short}}" id="short" >
            <div class="input-group-append">
                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_short', $films->id)}}" ></a>  
            </div>

        </div>
        
        <small id="shortlHelp" class="form-text text-muted">Jeśli ścieżka dostępu jest pusta popraw ją na "../../filmy/short/{{$films->id}}.mp4"</small>
            
        @error('short')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
        @enderror
        
    </div>

    <div class="form-group">
        <label for="thumbnail">Pełna ścieżka dostępu do zdjęcia:</label>
        <div class="input-group">

            <input type="input" class="form-control @error('thumbnail') form-error @enderror" name="thumbnail" value="{{$films->thumbnail}}" id="thumbnail">
            <div class="input-group-append">
                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_film_thumbnail', $films->id)}}" ></a>  
            </div>

        </div>
        @error('thumbnail')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="duration">Czas całego filmu:</label>
        <input type="number" class="form-control" name="duration" value="{{$films->duration}}" id="duration">
        <small id="durationlHelp" class="form-text text-muted">1. Jeśli czas wynosi 0 lub 1 prosimy wysłać formularz, czas filmu powinien zostać policzony automatycznie</small>
        <small id="durationlHelp" class="form-text text-muted">2. Jeśli czas nadal wynosi 0 lub 1 skorzystaj z poniższego kalukulatora następnie wpisz czas ręcznie.</small>

        <div class="form-group text-center" style="padding-top: 10px;">
            <input type="time" name="start" id="start" step="1" value="00:00:00" style="border-radius: 25px; width:20%; text-align:center; margin-right: 20px;">
            <input name="time_start" value="0" style="border-radius: 25px; width:20%; text-align:center;" disabled="">
            <small id="durationlHelp" class="form-text text-muted">W lewej kolumnie wpisz czas trwania filmu po prawej czas zostanie przeliczony na sekundy<br><br>
            1 h - 3600 sekund<br>
            1 min - 60 sekund<br>
            1 sek - 1 sekunda<br><br>
            w przypadku ustawienia czasu --:--:-- pojawi się 720 sekund w celu poprawnego działania wpisz 00 <br>

            </small>
        </div>

        <script>
        // get time and change to second times for video
        $(document).ready(function() {
        $('#start').on('input', function() {

            var hours, minutes, seconds;
            var czas = document.getElementById("start").value; 
            [hours, minutes, seconds] = czas.split(':');
            console.log (hours, minutes, seconds);

            if(seconds == null || seconds == '' || seconds == undefined) {
                seconds = 00;

            }
            
            var time_sec;
            time_sec = hours * (60 * 60) + minutes * 60 + seconds * 1;
            console.log (time_sec);

            if(isNaN(time_sec)){
                time_sec = "720"
            }

            var change = $("input[name='time_start']");
            change.val(time_sec);


        });

        });
        </script>
    </div>

    <div class="form-group">
        <label for="rating" style="font-size: 20px;">Ocena filmu: <b>{{$films->rating}}</b></label><br>

        <div style="display:inline-block;">
            <div style="display:inline-block;">
                <input type="radio" id="rating" name="rating" value="1" 
                
            <?php                         
                if($films->rating == 1){
                    echo "checked";
                }
            ?>
                
                >
                    <label for="rating">1</label>
            </div>



            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="2"
                
                <?php                         
                    if($films->rating == 2){
                        echo "checked";
                    }
                ?>
                
                >
                    <label for="rating">2</label>
            </div>



            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="3" 
                
                <?php                         
                    if($films->rating == 3){
                        echo "checked";
                    }
                ?>
                
                >
                    <label for="rating">3</label>
            </div>


            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="4" 
                
                <?php                         
                    if($films->rating == 4){
                        echo "checked";
                    }
                ?>
                
                >
                    <label for="rating">4</label>
            </div>


            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="5" 
                
                <?php                         
                    if($films->rating == 5){
                        echo "checked";
                    }
                ?>
                
                >
                    <label for="rating">5</label>
            </div>


            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="6" 
                
                <?php                         
                    if($films->rating == 6){
                        echo "checked";
                    }
                ?>
                
                >
                    <label for="rating">6</label>
            </div>


        </div>

    </div>

    <div class="form-group">
        <label for="activ">Film aktywny:</label><br>
        
        <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-offstyle="danger" name="activ" id="activ"
        
        <?php                         
            if($films->activ == 1){
                echo "checked";
            }
        ?>

        >

    </div>

    <div class="form-group text-center" style="padding-top: 20px;">
        <button class="btn btn-success" type="submit">Wyślij</button>
    </div>

    <input type="hidden" value="{{$films->id}}" name="films_id">
                    
    </form>
    
   
  
    <hr style="width:100%; background-color: black;"></hr>
    <div class="col-sm-12 " style="margin-left: 5%; z-index: 999" id="sticky_video">
        <div style="width: 90%; display: none;" id="display_video">
            
            <video id="films_stop" src="{{URL::asset("$films->url")}}"  controls></video>

        </div>
        <style>
            #films_stop{
                height: 30vh !important;
                width: 45vh !important;
            }
        </style>
        <script>


        function show_stick() {
            document.getElementById("button_video_stic").style.display='none';
            document.getElementById("button_video_sticky_blo").style.display='inline';           
            document.getElementById("display_video").style.display='block';
        }

        function hidde_stick() {
            document.getElementById("button_video_stic").style.display='inline';
            document.getElementById("button_video_sticky_blo").style.display='none';
            document.getElementById("display_video").style.display='none';

            const video = document.getElementById("films_stop");
            video.pause();
            
            
        }


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



        </script>

        <div>
            <button class="btn btn-danger btn-sm" onclick="hidde_stick()" id="button_video_sticky_blo" style="display: none;">Ukryj Film</button>
            <button class="btn btn-success btn-sm" onclick="show_stick()" id="button_video_stic" >Pokaż Film</button>

            <button class="btn btn-danger btn-sm" onclick="del_stick()" id="button_video_sticky_block" style="display: none;">Zablokuj Film</button>
            <button class="btn btn-success btn-sm" onclick="btn_stick()" id="button_video_sticky" >Odblokuj Film</button>
            
        </div>
        
        <div class="text-center" style="margin-bottom:20px;"></div>
    </div> 



    <div class="" style="padding-bottom:20px;">
        <div class="text-center"  style="padding-bottom: 25px;"> Edycja traileru</div>
        <form action="{{url('/edit_films_trailer_save')}}" method="POST" enctype="multipart/form-data" id="add_films">

            @csrf <!-- {{ csrf_field() }} -->

            <div class="form-group text-center">
                <input type="time" name="short_time_video" id="short_time_video" step="1" value="00:00:00" max="05:00:00" style="border-radius: 25px; width:35%; text-align:center">
                <input name="time_sec_video" type="hidden" value="720"/> <!-- get time in second -->
                <small id="durationlHelp" class="form-text text-muted">1. Sprawdź czy pole "Czas całego filmu:" wynosi 0 jeśli tak wpisz czas trwania całego filmu w sekundach.
                     Następnie zapisz formularz i dopiero stwórz zwiastun.</small>
                <small id="durationlHelp" class="form-text text-muted">2. Zwiastun filmu zawsze trwa 15 sekund.</small>
                <small id="durationlHelp" class="form-text text-muted">3. Jeśli ustawisz czas zwiastunu z 30 min a film trwa 20 to zwiastun zostanie stworzony z 12 min mimo wszystko.</small>
            </div>          

            <div class="form-group text-center" style="padding-top: 20px;">
                        <button class="btn btn-success" type="submit" data-toggle="modal" data-target="#exampleModal">Wyślij</button>
            </div>
            <input type="hidden" value="{{$films->id}}" name="films_id">
            <input type="hidden" value="{{$films->duration}}" name="duration">
            <input type="hidden" value="{{$films->url}}" name="url">

        </form>
    </div>



    <hr style="width:100%; background-color: black;"></hr><br> 
    <div class="" style="padding-bottom:20px;">
        <div class="text-center"  style="padding-bottom: 25px;"> Edycja Thumbnail</div>
        <form action="{{url('/edit_films_thumbnail_save')}}" method="POST" enctype="multipart/form-data" id="add_films">

            @csrf <!-- {{ csrf_field() }} -->

            <div class="form-group text-center">
                <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00" style="border-radius: 25px; width:35%; text-align:center">
                <input name="time_sec_thumbnail" type="hidden" value="720"/> <!-- get time in second -->
                <small id="durationlHelp" class="form-text text-muted">1. Sprawdź czy pole "Czas całego filmu:" wynosi 0 jeśli tak wpisz czas trwania całego filmu w sekundach.
                     Następnie zapisz formularz i dopiero stwórz miniaturkę...</small>
            </div>          

            <div class="form-group text-center" style="padding-top: 20px;">
                <button class="btn btn-success" type="submit">Wyślij</button>
            </div>

            <input type="hidden" value="{{$films->id}}" name="films_id">
            <input type="hidden" value="{{$films->duration}}" name="duration">
            <input type="hidden" value="{{$films->url}}" name="url">

        </form>
    </div>



    <hr style="width:100%; background-color: black;"></hr><br> 
    <div class="text-center" style="padding-bottom:20px;">
       
        <form action="{{url('/edit_films_add_tag')}}" method="POST" enctype="multipart/form-data" id="add_films">

            @csrf <!-- {{ csrf_field() }} -->

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

                    </div>
                </div>


                <script>
                    $(function() {
                        $('#tag').autocomplete({
                        source: "{{url('/gettagg')}}",
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

                            li.innerHTML =  '<a href="'+url+'" target="_blank"> ' + tag + '  <button class=\"deleteTag btn-delete\" id=\"'+id+'\">X</button>' 
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

                </script>

                

            

    
        <div class="text-center" style="padding-bottom:20px;">               

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

            </div>
        </div>


                <script>
                $(function() {
                $('#star').autocomplete({
                    source: "{{url('/getstarr')}}",
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
                                    li.className = "star";
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

                        

                </script>




            <div class="text-center" style="padding-bottom:20px;">
                
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

                        </div>
                    </div>


                    <script>
                    $(function() {
                    $('#studios').autocomplete({
                        source: "{{url('/getstudioss')}}",
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

                                    li.innerHTML =  '<a href="'+url+'" target="_blank"> ' + studios + '  <button class=\"deleteStudios btn-delete\" id=\"'+id+'\">X</button>' 
                                    i.setAttribute("value", studios);    
                                    
                                    $("#studiosList").append(li)
                                    $("#studiosList").append(i)
                                    $('#studios').val('');  
                                }});
                        
                            $("#studiosList").on('click', 'button.deleteStudios', function() {
                            
                                var idDiv = this.id;
                                $("#"+idDiv).remove()
                                $(":input[id='"+idDiv+"']").remove();

                            });

                            $("#studiosList").on('click', 'button.deleteStudiosExsit', function() {
                            
                                var del_id = this.id;
                                var toDel = del_id.replace('id_', '');
                                $("#id_"+toDel).remove();

                            });

                            

                    </script>

                </div>


                <input type="hidden" value="{{$films->id}}" id="films_id" name="films_id">
                
            
                <div class="form-group text-center" style="padding-top: 20px;">
                            <button class="btn btn-success" type="submit">Wyślij</button>
                </div>
                            

            </form>
        </div>
        

    </div>

   
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content modtext" >
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tworzenie zwiastunu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body row justify-content-center text-center">

            <tw>Prosimy o chwilę cierpliwości.</br>
                Strona zostanie automatycznie odświeżona po przygotowaniu zwiastunu filmowego.</br>
                w zależności od posiadanego sprzętu może to zająć do 5 min
            </tw><br>
                
                    <img src="{{ asset('icon/app.blade/reload.gif') }}" style="width: 70px; height: 70px;"></img>
                            
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
      </div>
    </div>
  </div>
</div>



</div>



<!------------------------------------------------------------------------ RIGHT SIDE -------------------------------------------------------->





<div class="col-sm-6">
 <!-- use this class to sticky player "sticky_video_edit" -->
    <div class="text-center " style="padding-bottom:20px;">
        <div class=""> Film</div>
        <video src="{{URL::asset("$films->url")}}" height="200" width="320" controls></video>

    </div>

    <div class="text-center" style="padding-bottom:20px;">
        <div class=""> Trailer</div>
    <video src="{{URL::asset("$films->short")}}" height="200" width="320" controls></video>

    </div>

    <div class="text-center" style="padding-bottom:10px;">
        <div class=""> Thumbnail</div>
        <img src="{{URL::asset("$films->thumbnail")}}" style="max-width: 50%; height: 80%;"></img>
        
    </div>

    <div class="text-center" style="padding-bottom: 50px;"></div>


    <div class="col-sm-12 text-center">
    <small id="durationlHelp" style="padding-top: 1%; padding-bottom: 3%;" class="form-text text-muted">Klikając X na przycisku automatycznie zostanie on usunięty z bazy danych bez 
        dodatkowego potwierdzenia.

    </small>

    <div class="text-center" style="padding-bottom: 20px;">
        <div class="" style="padding-bottom: 1%; padding-left: 2%"> Tagi</div>
        @if(count($tags) > 0)
        


        <div class="tagForm">
            <ul id="tagList_db">
                @foreach ($tags as $tag)
                    <li class="tags" id="id_{{$tag->id}}"> <a href="{{url('/select_categories', $tag->tag_id)}}" class="tags_edit"> {{$tag->name}}</a>  @csrf <button class="deleteTagExsit btn-delete" id="id_{{$tag->id}}">X</button></li>
                @endforeach
            </ul>
        </div>


        <script> 
      $("#tagList_db").on('click', 'button.deleteTagExsit', function() {
    
        var idDiv = this.id;
        $("#"+idDiv).remove()
        $(":input[id='"+idDiv+"']").remove();

    });

    $("#tagList_db").on('click', 'button.deleteTagExsit', function() {
        // crfs 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var del_id = this.id;
        var toDel = del_id.replace('id_', '');
        var token = document.getElementsByName('_token').value
        $("#id_"+toDel).remove();
        $.ajax({
            type:'POST',
            url:"{{url('/edit_films_ajax_delete_tag')}}",
            data:'delete_id='+toDel,
        });
    
    });



    </script>
        

        @else
        Brak tagów do wyświetlenia
        @endif

    </div>



    <div class="text-center" style="padding-bottom: 20px;">
        <div class="" style="padding-bottom: 1%; padding-left: 2%"> Gwiazdy</div>

        @if(count($stars) > 0)

        

        <div class="tagForm">
            <ul id="starList_db">
                @foreach ($stars as $star)
                    <li class="star" id="star_id_{{$star->id}}"> <a href="{{url('/select_stars', $star->stars_id)}}" class="star_edit"> {{$star->name}} </a> @csrf <button class="deleteStarExsit btn-delete" id="star_id_{{$star->id}}">X</button></li>
                @endforeach
            </ul>
        </div>

        <script> 
      $("#starList_db").on('click', 'button.deleteStarExsit', function() {
    
        var idDiv = this.id;
        $("#"+idDiv).remove()
        $(":input[id='"+idDiv+"']").remove();

    });

    $("#starList_db").on('click', 'button.deleteStarExsit', function() {
        // crfs 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var del_id = this.id;
        var toDel = del_id.replace('star_id_', '');
        var token = document.getElementsByName('_token').value
        $("#id_"+toDel).remove();
        $.ajax({
            type:'POST',
            url:"{{url('/edit_films_ajax_delete_star')}}",
            data:'delete_id='+toDel,
        });
    
    });



    </script>
        
    </div>

    @else
        Brak gwiazd do wyświetlenia
    @endif

   

    <div class="text-center" style="padding-bottom: 20px;">
        <div class="" style="padding-top:5%; padding-bottom: 1%; padding-left: 2%"> Wytwórnie</div>

        @if(count($studios) > 0)

        

        <div class="tagForm">
            <ul id="studiosList_db">
                @foreach ($studios as $studio)
                    <li class="studios" id="studio_id_{{$studio->id}}"> <a href="{{url('/select_studios', $studio->studios_id)}}" class="studio_edit" > {{$studio->name}} </a> @csrf <button class="deleteStudioExsit btn-delete" id="studio_id_{{$studio->id}}">X</button></li>
                @endforeach
            </ul>
        </div>

        <script> 
      $("#studiosList_db").on('click', 'button.deleteStudioExsit', function() {
    
        var idDiv = this.id;
        $("#"+idDiv).remove()
        $(":input[id='"+idDiv+"']").remove();

    });

    $("#studiosList_db").on('click', 'button.deleteStudioExsit', function() {
        // crfs 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var del_id = this.id;
        var toDel = del_id.replace('studio_id_', '');
        var token = document.getElementsByName('_token').value
        $("#id_"+toDel).remove();
        $.ajax({
            type:'POST',
            url:"{{url('/edit_films_ajax_delete_studio')}}",
            data:'delete_id='+toDel,
        });
    
    });



    </script>
        </div>
    </div>

    @else
        Brak wytwórni do wyświetlenia
        @endif

    </div>



    </div>



</div>



@endsection