
@extends('layouts.admin')

@section('title')VideoSite Konwersja Filmów @endsection


@section('content')


<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Konwertuj Film na .MP4 </h1>
<a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_conversion')}}" style="margin-bottom: 20px;"> KONWERSJA </a>
<div class="center"> Konwersja filmu oznacza utratę jakości nagrania!</div>
</div>


    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">

        @if(!empty($errorsMsg))
            <div class="alert alert-danger"> {{ $errorsMsg }} </br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
            
                    <i class="fas fa-question-circle" style="color: black;" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
                    pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania konwersji filmu na .mp4.
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

          
          <div class="row justify-content-center"> Wystarczy zmiana rozszerzenia &NonBreakingSpace; <a href="{{url('/simply_conversion')}}">  Tutaj </a>&NonBreakingSpace;
          Pamietaj że konwersja powoduje utratę jakości.</div></br>
          
        <form action="{{url('/convert_save')}}" method="POST" enctype="multipart/form-data" id="conversion">

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
                    <input type="input" class="form-control  @error('file') form-error @enderror" name="film_name" id="film_name" placeholder="Tytuł filmu" >
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

            <div class="form-group">
                <label for="format">Format</label>
                <input type="input" class="form-control" name="format" id="format" value=".mp4" disabled>
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
                    <h5 class="modal-title" id="exampleModalLabel">Konwersja Filmu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body row justify-content-center text-center">

                        <tw>Prosimy o cierpliwość.</br>
                            Strona zostanie automatycznie odświeżona po przetworzeniu filmu do formatu mp4.</br>
                            w zależności od posiadanego sprzętu oraz długości filmu może to zająć do 2h. </br></br>
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