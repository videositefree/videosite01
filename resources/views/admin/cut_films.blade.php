
@extends('layouts.admin')

@section('title')VideoSite Konwersja Filmów @endsection


@section('content')

<script src="{{ asset('js/admin_films.js') }}" defer></script> <!-- all script for forms -->


<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Wytnij fragment filmu <h1>
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
      
        <form action="{{url('/cut_save')}}" method="POST" enctype="multipart/form-data" id="conversion">

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
                    <input type="input" class="form-control  @error('file') form-error @enderror" id="film_name" name="film_name" id="film_name" placeholder="Tytuł filmu">
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
                <label for="film_name">Początek:</label></br>
                <input type="time" name="start" id="start" step="1" value="00:00:00" style="border-radius: 25px; width:35%; text-align:center">
                <input name="time_start" type="hidden" value="0">
            </div>
            
            <div class="form-group text-center">
                <label for="film_name">Koniec:</label></br>
                <input type="time" name="end" id="end" step="1" value="00:00:00" style="border-radius: 25px; width:35%; text-align:center">
                <input name="time_end" type="hidden" value="15">
            </div> 

        
            <div class="form-group text-center" style="padding-top: 20px;">
                <button class="btn btn-success" id="submit" type="submit">Wyślij</button>
            </div>
                        

            </form>



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
                    <h5 class="modal-title" id="exampleModalLabel">Wycinanie Filmu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body row justify-content-center text-center">

                        <tw>Prosimy o cierpliwość.</br>
                            Strona zostanie automatycznie odświeżona po wycięciu filmu</br>
                            w zależności od posiadanego sprzętu oraz długości filmu może to zająć do 20 min. </br></br>
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
    <div style="padding-bottom: 50px;">
    </div>
</div>


@endsection