
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Wytwórniami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12">

<div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Wytwórnie Filmowe</h2><a class="btn btn-outline-primary fas fa-folder-open" href="{{url('/open_main_folder_studios')}}" style="margin-bottom: 20px;"> WYTWÓRNIE </a>
    </ul>
  </div>

@if( $count_studios >0 )
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
  <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń Wszystkie Wytwórnie</button>
  <a href="{{url('/add_studios')}}" class="btn btn-success">Dodaj Nową Wytwórnię</a>
 
</div>

<div class="col-sm-12">

    <div class="" style="float:left;">             
        <div class="input-group" style="padding-top: 18px; margin-bottom:10px; width: 200px;">
            <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Wyszukaj...">
        </div>
    </div>          

</div>

<div class="col-sm-12 row" style="text-align: center;" id="result"></div>
    
    


<script>
$(document).ready(function(){
	
	function load_data(query)
	{
        // crfs 
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
		$.ajax({
			url:"{{url('/searchstudios_admin')}}",
			method:"post",
			data:{query:query},
			success:function(data)
			{
            
               $('#result').html(data);
			}
		});
	}
	
	$('#search_tag').keyup(function(){
		var search = $(this).val();
		if(search != '')
		{
			load_data(search);
		}

	});
});
</script>




<div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image"  style="text-align: center">
      <thead class="thead-dark">
		    <tr>
          <th scope="col">
          <a href="{{url('/admin_studios')}}" class="table_link fas fa-arrow-up"></a>
          #
          <a href="{{url('/studios_id_asc_admin')}}" class="table_link fas fa-arrow-down"></a>  </th>
          <th scope="col">
          <a href="{{url('/studios_name_asc_admin')}}" class="table_link fas fa-arrow-up"></a>
          Nazwa
          <a href="{{url('/studios_name_desc_admin')}}" class="table_link fas fa-arrow-down"></a></th>
          <th scope="col">
          <a href="{{url('/studios_rating_asc_admin')}}" class="table_link fas fa-arrow-up"></a>
          Ocena
          <a href="{{url('/studios_rating_desc_admin')}}" class="table_link fas fa-arrow-down"></a></th>
          
          <th scope="col">Zdjęcie</th>
          <th scope="col">
            Akcja
          </th>
		    </tr>
		  </thead>
		  <tbody>
        @foreach($studios as $key => $studio)
          <tr>
            <th scope="row">{{$studios->firstItem() + $key}}</th>
            <td class="table_site">
              <a href="{{ url('/select_studios', $studio->id) }}">
                <b>{{$studio->name}}</b>
                <div class="film_number">
                  <i class="fas fa-video "></i> <?php 

                  $count_films = DB::table('studios')
                  ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
                  ->join('films', 'films.id', '=', 'films_studios.film_id')
                  ->orderBy('name', 'ASC')
                  ->select('films.*')
                  ->where('studios.id', $studio->id)
                  ->where('activ', '=', '1')
                  ->distinct()
                  ->count();


                  ?>&nbsp;&nbsp;{{$count_films}}
                </div>
              </a>
            </td>
            <td>
              {{$studio->rating}}
            </td>
            <td class="w-25">
              <img src="{{URL::asset("$studio->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$studio->name}}">
            </td>
            
            <td>
              <a href="{{url('/edit_studios', $studio->id)}}" class="btn btn-info">Edytuj</a>
              <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$studio->id}}">Usuń</button> -->
              <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_studios', $studio->id)}}">Usuń</a>
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
          Brak wytwórni do wyświetlenia.
      </ul>

      <ul>
        <a href="{{url('/add_studios')}}" class="btn btn-success">Dodaj Nową Wytwórnie</a>
      </ul>
    </div>

  </div>
</div>


@endif


</div>





<!-- delete fast modal
@foreach($all_studios as $studio)
  <div class="modal fade"  id="delete_{{$studio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >
        <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jeteś pewien że chcesz usunąć:
        </br></br><b>{{$studio->name}}?</b></br></br>
        Gwiazda, zdjęcia oraz wszystkie elementy z nim powiązane zostaną permanentnie usunięte z bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_studios', $studio->id)}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
        </div>
      </div>
    </div>
  </div>
@endforeach  -->


<!------------------------------------------------------------------Modal-------------------------------------------------------------------->

<div class="modal fade"  id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >

        <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jeteś pewien że chcesz usunąć WSZYSTKIE wytwórnie?
        </br></br>
        Wytwórnie, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br>
        <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_all_studios')}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
        </div>
      </div>
    </div>
  </div>



@endsection
@section('pagi') {{$studios->links()}} @endsection