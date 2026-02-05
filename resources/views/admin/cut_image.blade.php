
@extends('layouts.admin')

@section('title')VideoSite Konwersja Filmów @endsection


@section('content')

<script src="{{ asset('js/admin_films.js') }}" defer></script> <!-- all script for forms -->


<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Wytnij miniaturę z filmu <h1>
<a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_cut')}}" style="margin-bottom: 20px;"> WYCIĘTE </a>
</div>


    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">

        @if(!empty($errorsMsg))
                <div class="alert alert-danger"> {{ $errorsMsg }} </br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
                
                        <i class="fas fa-question-circle" style="color: black;" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
                        pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości skracania filmów.
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
</div>


<div class="container">
    <div class="row justify-content-center">

    <div class="col-sm-6">

        <div class="conatainer">
        <div class="row d-flex justify-content-center">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here">
        </video>

        </div>

    </div>


    </div>


          <div class="col-sm-6">
      
        <form action="{{url('/cut_image_save')}}" method="POST" enctype="multipart/form-data" id="conversion">

            @csrf <!-- {{ csrf_field() }} -->


            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film" name="file">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film" style="text-align: left">Wybierz Plik</label>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group ">            
                <div class="input-group ">
                    <input type="input" class="form-control  @error('file') form-error @enderror" name="film_name" id="film_name"  placeholder="Nazwa miniatury">
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
                var change = $("input[name='film_name']");
                change.val(name);  

                });
            });
            </script>


            <div class="form-group text-center">
                <label for="film_name">Czas z którego ma być zrobiona miniatura:</label></br>
                <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00" style="border-radius: 25px; width:35%; text-align:center">
                <input name="time_sec_thumbnail" type="hidden" value="720"/> <!-- get time in second -->
              
            </div>
            
            
            <script>
        function checked_radio() {

          if($('#hiddendiv').is('.collapse')) {
              document.getElementById("resize_img_1").checked = false;
              document.getElementById("resize_img_2").checked = true;     
          }
            
          if($('#hiddendiv').is('.collapse:not(.show)')) {
            document.getElementById("resize_img_1").checked = true;
            document.getElementById("resize_img_2").checked = false;     
          }


        }
      </script>

      <div class="form-group text-center">           
        <small id="resizelHelp" class="form-text text-muted" style="padding-top: 10px;">Zdjęcia w dobrej jakości zajmują bardzo dużo miejsca na dysku twardym.</small>
        <small id="resizelHelp" class="form-text text-muted">Możemy to zmienić zmniejszając zdjęcia do rozmiarów 350px X 350px wystarzy zaznaczyć "Tak". </small> 
        <small id="resizelHelp" class="form-text text-muted" style="margin-top: 10px; margin-bottom: 30px;">Jeśli nie odpowiadają ci powyższe wymiary możesz zmienić to 
          <a type="button" data-toggle="collapse" data-target="#hiddendiv" aria-expanded="false" aria-controls="collapseExample" style="color:red" onclick="checked_radio()">
            <b>Tutaj</b>
          </a>
        
        </small>  
        <label for="yes_no_radio">Czy chcesz zmiejszyć zdjęcia?</label>

        <div style="padding-bottom: 5px;"></div>

        <input class="form-group" type="radio" name="resize_img" id="resize_img_1" value="1">
          <label for="resize_img_1">Tak</label>
        <input style="margin-left: 20px;" class="form-group" type="radio" name="resize_img" id="resize_img_2" value="2" checked>
          <label for="resize_img_2">Nie</label>

      </div>                    
    
      <div class="collapse container" id="hiddendiv">
        <div class="row justify-content-center">

        <div class="form-group col-sm-3">
          <input type="text" class="form-control" name="height_img" placeholder="Wysokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" >
        </div>

        <div class="form-group col-sm-3">
          <input type="text" class="form-control" name="width_img" placeholder="Szerokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" >
        </div>
        
        </div>
      </div>

      <div style="padding-top: 30px;">


        
            <div class="form-group text-center" style="padding-top: 20px;">
                <button class="btn btn-success" id="submit" type="submit">Wyślij</button>
            </div>
                        

            </form>
            <div style="padding-top: 30px;">


                <!---- NOT SHOW MODAL WHEN INPUT FILE IS NULL  --->
                <script>
                $('#submit').click(function(){
                    if($('#film_name').val() !== '' && $('#file').val() !== ''){
                    $('#exampleModal').modal();
                    $('#exampleModal').modal('toggle');
                $('#exampleModal').modal('show');

                    }
                });

                </script>

            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content modtext" >
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Wycinanie miniatury z Filmu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body row justify-content-center text-center">

                        <tw>Prosimy o cierpliwość.</br>
                            Strona zostanie automatycznie odświeżona po wykonaniu miniatury z filmu</br>
                            w zależności od posiadanego sprzętu oraz długości filmu może to zająć do 2 minut. </br></br>
                            PROSIMY NIE ZAMYKAĆ TEJ STRONY W CELU WYKONANIA KONWERSJI.
                        </tw></br></br>
                            <div class="col-sm-12">
                                <img src="{{ asset('icon/app.blade/reload.gif') }}" style="width: 70px; height: 70px;"></img>
                            </div>          
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                </div>
                </div>
            </div>
            </div>




        </div>

 
        
    </div>
</div>


@endsection