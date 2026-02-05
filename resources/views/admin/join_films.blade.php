
@extends('layouts.admin')

@section('title')VideoSite Konwersja Filmów @endsection


@section('content')

<script src="{{ asset('js/admin_films.js') }}" defer></script> <!-- all script for forms -->


<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Wytnij fragment filmu <h1>
<a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_join')}}" style="margin-bottom: 20px;"> SCALONE </a>

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
        <div class="row d-flex justify-content-center" style="padding-top: 15px; padding-bottom:15px;">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here-1">
        </video>

        </div>

        <div class="row d-flex justify-content-center" style="padding-top: 15px; padding-bottom:15px;">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here-2">
        </video>

        </div>

        <div class="row d-flex justify-content-center" style="padding-top: 15px; padding-bottom:15px;">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here-3">
        </video>

        </div>

        <div class="row d-flex justify-content-center" style="padding-top: 15px; padding-bottom:15px;">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here-4">
        </video>

        </div>

        <div class="row d-flex justify-content-center" style="padding-top: 15px; padding-bottom:15px;">

        <video class="col-lg-12" width="500"  poster="{{ asset('icon/app.blade/logo_video.png') }}" controls="">
            <source src="mov_bbb.mp4" id="video_here-5">
        </video>

        </div>

    </div>


    </div>


          <div class="col-sm-6">
      
        <form action="{{url('/join_save')}}" method="POST" enctype="multipart/form-data" id="conversion">

            @csrf <!-- {{ csrf_field() }} -->

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

            <div>Filmy będą łączone na końcu zaczynając od filmu nr 1 do 5</div></br>

            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film_1" name="file_1">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film_1" style="text-align: left">Wybierz Plik NR 1</label>
                    @error('file_1')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film_2" name="file_2">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film_2" style="text-align: left">Wybierz Plik NR 2</label>
                    @error('file_2')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film_3" name="file_3">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film_3" style="text-align: left">Wybierz Plik NR 3</label>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film_4" name="file_4">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film_4" style="text-align: left">Wybierz Plik NR 4</label>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="film_5" name="file_5">
                    <label class="custom-file-label @error('file') form-error @enderror" for="film_5" style="text-align: left">Wybierz Plik NR 5</label>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                </div>
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