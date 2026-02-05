
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Filmy</h2>
      <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_film')}}" style="margin-bottom: 20px;"> FILMY </a>
      <a class="btn btn-outline-primary fas fa-folder-open" href="{{url('/open_main_folder_thumbnail')}}" style="margin-bottom: 20px;"> ZDJĘCIA </a>
      <a class="btn btn-outline-primary fas fa-folder-open" href="{{url('/open_main_folder_short')}}" style="margin-bottom: 20px;"> SHORT </a>
    </ul>
    
  </div>
    
  @if( $count_films >0 )
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

   

    <a class="btn btn-danger" href="{{url('/admin_films_off')}}" >Wyłącz Wszystkie Filmy</a>
    <a class="btn btn-success" href="{{url('/admin_films_on')}}" >Włącz Wszystkie Filmy</a>
    
    <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń Wszystkie Filmy</button>
    <a href="{{url('/add_films')}}" ><button class="btn btn-success">Dodaj Nowy Film</button></a>
    

    
  </div>

<div class="col-sm-12">

    <div class="" style="float:left;">             
        <div class="input-group" style="padding-top: 18px; margin-bottom:10px; width: 300px;">
            <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj po id, tytule, nazwie pliku.">
        </div>
    </div>          

</div>

<div class="col-sm-12 row" id="result"></div>
    
    


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
			url:"{{url('/searchfilms_admin')}}",
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
		<table class="table table-image">
      <thead class="thead-dark">
		    <tr>
          <th scope="col" style="width: 15%">
          <a href="{{url('/admin_films')}}" class="table_link fas fa-arrow-up"></a>
          #
          <a href="{{url('films_id_asc')}}" class="table_link fas fa-arrow-down"></a>
          </th></th>

            <th scope="col" style="width: 40%"><a href="{{url('/films_name_asc')}}" class="table_link fas fa-arrow-up"></a>
            Nazwa
            <a href="{{url('/films_name_desc')}}" class="table_link fas fa-arrow-down"></a>
            </th>

            <th scope="col" style="width: 15%"><a href="{{url('/films_on_desc')}}" class="table_link fas fa-arrow-up"></a>
            Status
            <a href="{{url('/films_off_desc')}}" class="table_link fas fa-arrow-down"></a>

            <th scope="col" style="width: 15%"><a href="{{url('/films_rating_asc')}}" class="table_link fas fa-arrow-up"></a>
            Ocena
            <a href="{{url('/films_rating_desc')}}" class="table_link fas fa-arrow-down"></a>
            </th>


          <th scope="col" style="width: 20%">
          Akcja       
          </th>

		    </tr>
		  </thead>
		  <tbody>
        @foreach($films as $key => $film)
          <tr>
            <th scope="row">{{$films->firstItem() + $key}}</th>
            <td class="table_site"><b><a href="{{url('/edit_films', $film->id)}}" >{{$film->name}}</a></b></td>
            <td style="width: 3% !important;">
            
          
            @if($film->activ =='0')         
            <i class="far fa-times-circle"></i>
            @else
            <i class="far fa-check-circle"></i>
            @endif
            </td>
            
            <td>{{$film->rating}}</td>
 
            <td>
              
              <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$film->id}}">Usuń</button> -->
              <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_films', $film->id)}}">Usuń</a>
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
              Brak filmów do wyświetlenia.
          </ul>

          <ul>
            <a href="{{url('/add_films')}}" ><button class="btn btn-success">Dodaj Nowy Film</button></a>
            
          </ul>

      </div>

  </div>
</div>
@endif


</div>


<!-- display fast delete modal

@foreach($all_films as $film)
  <div class="modal fade"  id="delete_{{$film->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext" >
        <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jesteś pewien że chcesz usunąć:
        </br></br><b>{{$film->name}}?</b></br></br>
        Film, zdjęcia oraz wszystkie elementy z nim powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_films', $film->id)}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
        </div>
      </div>
    </div>
  </div>
@endforeach
 -->


<!------------------------------------------------------------------Modal-------------------------------------------------------------------->
  <div class="modal fade"  id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >
        <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jeteś pewien że chcesz usunąć WSZYSTKIE filmy?
        </br></br>
        Film, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_all_films')}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>          
        </div>
      </div>
    </div>
  </div>




@endsection

@section('pagi') {{$films->links()}} @endsection