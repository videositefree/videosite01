@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Bazą danych @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12">

<div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Zarządzaj Kopiami Bazy danych</h2>
      <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_db')}}" style="margin-bottom: 20px;"> Baza Danych </a>
    </ul>
</div>

@if( $count_copy > 0 )

    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 5px; padding-bottom: 5px">
    
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                {!! \Session::get('success') !!}
            </ul>
        </div>
        @endif

        @if (\Session::has('errors'))
        <div class="alert alert-danger">
            <ul>
                {!! \Session::get('errors') !!}
            </ul>
        </div>
        @endif

    </div>


<div style="float: right; float: right; padding-top: 18px; margin-bottom:10px;" >
  <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń Wszystkie Kopię Bazy Danych</button>
  <a href="{{url('/copy_db')}}" class="btn btn-success">Wykonaj Kopię Bazy Danych</a>

</div>



<div class="col-sm-12 row" id="result"></div>


<div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image col-sm-12 text-center">
      <thead class="thead-dark">
		    <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">
            Akcja
        </th>
		    </tr>
		  </thead>
		  <tbody>
            @foreach ($files as $key => $file)
              <tr>
                <th scope="row">{{$files->firstItem() + $key}}</th>
                
                <td>
                {{$file->name}}
                </td>
               <td>
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$file->id}}">Usuń</button>
                </td>
              </tr>
            @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>


@else
<div class="vertical-center">
  <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">

    <div class="alert alert-danger">
      <ul>
          Brak kopi bazy danych.
      </ul>

      <ul>
        <a href="{{url('/copy_db')}}" class="btn btn-success">Utwórz kopię bazy danych</a>
      </ul>
    </div>

  </div>
</div>


@endif


</div>






<!------------------------------------------------------------------Modal-------------------------------------------------------------------->

  @foreach ($files as $file)

  <div class="modal fade"  id="delete_{{$file->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Usuń Film nr: {{$file->id}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="font-size:17px;">
        Czy jeteś pewien że chcesz usunąć:
        </br></br><b>{{$file->name}}</b></br></br>
        Kopia zapasowa bazy danych zostanie usunięta. </b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
          <a class="btn btn-danger" href="{{ url('/delete_db_copy', $file->name) }}">Usuń</a>
        </div>
      </div>
    </div>
  </div>
@endforeach


<!------------------------------------------------------------------Modal-------------------------------------------------------------------->

<div class="modal fade"  id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Usuń Wszystkie kopie.</h5>
          <button type="button" class="close modtext" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="font-size:17px;">
        Czy jeteś pewien że chcesz usunąć wszystkie kopie bazy danych?
        </br></br>
        Wszystkie kopie zostaną pernamętnie usunięte z dysku twardego. </b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
          <a class="btn btn-danger" href="{{url('/delete_db_copy_all')}}">Usuń</a>
        </div>
      </div>
    </div>
  </div>



@endsection
@section('pagi') {{$files->links()}} @endsection