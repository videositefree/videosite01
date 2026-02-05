
@extends('layouts.error_db')


@section('title')VideoSite konfiguracja @endsection


@section('content')

<div class="col-sm-12 text-center" style="padding-top: 30px;">

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


<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-6">
      
          


            <div class="text-center" style="padding-top: 35%;">

                <ul> 
                    <h3>Upss... Widzę że brakuje nam kilku plików w bazie danych.</h3>

                1. Aby szybko temu zaradzić uzupełnimy bazę danych wymaganymi tabelami.</br>
                2. Nie musisz się niczym martwić!.</br>
                            
                </ul>

                <ul>
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Wykonaj migrację
                        </button>
                </ul>
            </div>
 




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content modtext" >
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Baza Danych</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="font-size: 16px; font-family: Nunito !important;">
       Kliknij dodaj i daj nam chwilkę...&nbsp;&nbsp; zostanieś przeniesiony do strony głównej.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
        <a href="{{ url('/migrations_db') }}" class="btn btn-success">Dodaj</a>
      </div>
    </div>
  </div>
</div>
                



        </div>
        
    </div>
</div>

@endsection
