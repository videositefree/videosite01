
@extends('layouts.error_db')


@section('title')VideoSite konfiguracja @endsection


@section('content')

<div class="col-sm-12 text-center">
     
    
    @if (\Session::has('success'))
      <div class="alert alert-success text-center">
          <ul>
              {!! \Session::get('success') !!}
          </ul>
      </div>
    @endif

    @if (\Session::has('errors'))
      <div class="alert alert-danger text-center">
          <ul>
              {!! \Session::get('errors') !!}
          </ul>
      </div>
    @endif
   
  </div>

<div class="container">
    <div class="row justify-content-center">

        <div class="col-sm-6" style="padding-top: 50px; padding-bottom: 100px;">
               
            <h3 class="text-center" style="padding-bottom: 13px;"> Skonfiguruj połączenie z bazą danych.</h3>

            <div class="text-center" style="color: red;"> <a class="btn btn-primary" href="{{url('/')}}"> STRONA GŁÓWNA </a>

            <div style="padding-bottom: 2%; padding-top: 1%"></div>
            </div>
            <form class="text-center" action="{{ url('/config_db') }}" method="POST">
            @csrf
                <div class="form-group ">
                    <label for="film_name">Nazwa Hosta:</label>
                    <input type="input" class="form-control" name="host" id="host" value="localhost">
                </div>

                <div class="form-group ">
                    <label for="film_name">Port:</label>
                    <input type="input" class="form-control" name="port" id="port" value="3306">
                </div>

                <div class="form-group ">
                    <label for="film_name">Nazwa Użytkownika:</label>
                    <input type="input" class="form-control" name="username" id="username" >
                </div>

                <div class="form-group ">
                    <label for="film_name">Hasło:</label>
                    <input type="input" class="form-control" name="password" id="password" >
                </div>

                <div class="form-group ">
                    <label for="film_name">Nazwa bazy danych:</label>
                    <input type="input" class="form-control" name="database" id="database" >
                </div>

                
                <input class="btn btn-success" type="submit" value="Zapisz"></br>
            </form>    


        </div>
        
    </div>
</div>

@endsection
