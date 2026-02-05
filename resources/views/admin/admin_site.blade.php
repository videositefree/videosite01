
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Strony</h2>
    </ul>
  </div>
    
  @if( $count_sites >0 )
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
    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń Wszystkie Strony</a>
    <a href="{{url('/add_site')}}" ><button class="btn btn-success">Dodaj Nową Stronę</button></a>
  </div>

<div class="col-sm-12">

    <div class="" style="float:left;">             
        <div class="input-group" style="padding-top: 18px; margin-bottom:10px; width: 200px;">
            <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Wyszukaj...">
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
			url:"{{url('/searchsite_admin')}}",
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
          <th scope="col">
          <a href="{{url('/admin_sites')}}" class="table_link fas fa-arrow-up"></a>
          #
          <a href="{{url('/site_id_asc')}}" class="table_link fas fa-arrow-down"></a>
          </th></th>

          <th scope="col"><a href="{{url('/site_name_asc')}}" class="table_link fas fa-arrow-up"></a>
            Nazwa
            <a href="{{url('/site_name_desc')}}" class="table_link fas fa-arrow-down"></a>
          </th>

          <th scope="col"><a href="{{url('/site_description_asc')}}" class="table_link fas fa-arrow-up"></a>
            Opis
            <a href="{{url('/site_description_desc')}}" class="table_link fas fa-arrow-down"></a>
          </th>

          <th scope="col"><a href="{{url('/site_rating_asc')}}" class="table_link fas fa-arrow-up"></a>
            Ocena
            <a href="{{url('/site_rating_desc')}}" class="table_link fas fa-arrow-down"></a>
        </th>
          <th scope="col">Akcja</th>
		    </tr>
		  </thead>
		  <tbody>
        @foreach($sites as $key => $site)
          <tr>
            <th scope="row">{{$sites->firstItem() + $key}}</th>
            <td class="table_site"><b><a href="{{ $site->link }}" target="_blank">{{ $site->name }}</a></b>
            </td>
            <td>{{$site->description}}</td>
            <td>{{$site->rating}}</td>
 
            <td>
              <a href="{{url('/edit_site', $site->id)}}" class="btn btn-info">Edytuj</a>
              <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$site->id}}">Usuń</button> -->
              <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_site', $site->id)}}">Usuń</a>
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
              Brak stron do wyświetlenia.
          </ul>

          <ul>
            <a href="{{url('/add_site')}}" ><button class="btn btn-success">Dodaj Nową stronę</button></a>
            
          </ul>

      </div>

  </div>
</div>
@endif


</div>



<!-- delete, fast modal
@foreach($all_sites as $site)
  <div class="modal fade"  id="delete_{{$site->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext" >
        <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jesteś pewien że chcesz usunąć:
        </br></br><b>{{$site->name}}</br>
        {{$site->link}}?</br></br>
        </b>
        Wszystkie informację o stronię zostaną usunięte z bazy danych</br></br><b>Pamiętaj że decyzji nie można cofnąć!</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_site', $site->id)}}">Usuń</a>
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
        Czy jeteś pewien że chcesz usunąć WSZYSTKIE strony?
        </br></br>
        Wszystkie informację o stronach zostaną usunięte z bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć!</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_all_site')}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
        </div>
      </div>
    </div>
  </div>




@endsection

@section('pagi') {{$sites->links()}} @endsection