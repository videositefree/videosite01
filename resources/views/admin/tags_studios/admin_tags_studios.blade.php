
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Tagami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12">

<div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Tagi Wytwórni</h2><a class="btn btn-outline-primary fas fa-folder-open" href="{{url('/open_main_folder_tags_studios')}}" style="margin-bottom: 20px;"> TAGI </a>
    </ul>
  </div>

@if( $count_tags >0 )

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
  <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń Wszystkie Tagi</button>
  <a href="{{url('/add_tags_studios')}}" class="btn btn-success">Dodaj Nowy Tag</a>
  
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
			url:"{{url('/searchtag_studios_admin')}}",
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
          <a href="{{url('/admin_tags')}}" class="table_link fas fa-arrow-up"></a>
          #
          <a href="{{url('/tags_id_asc_admin')}}" class="table_link fas fa-arrow-down"></a>  
          </th>
          <th scope="col">
          <a href="{{url('/tags_name_asc_admin')}}" class="table_link fas fa-arrow-up"></a>
          Nazwa
          <a href="{{url('/tags_name_desc_admin')}}" class="table_link fas fa-arrow-down"></a>    
          </th>
          <th scope="col">Zdjęcie</th>
          <th scope="col">
            Akcja
          </th>
		    </tr>
		  </thead>
		  <tbody>
        @foreach($tags as $key => $tag)
          <tr>
            <th scope="row">{{$tags->firstItem() + $key}}</th>
            <td class="table_site">
              <a href="{{ url('/select_categories_studios', $tag->id) }}">
                <b>{{$tag->name}}</b>

                <div class="film_number">
                  <i class="fas fa-tag"></i> <?php 

                  $count_films = DB::table('tags_studios')
                  ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags_studios.id')
                  ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
                  ->orderBy('name', 'ASC')
                  ->select('studios.*')
                  ->where('tags_studios.id', $tag->id)
                  ->where('studios_tags.tag_db', 0)
                  ->distinct()
                  ->count();

                  ?>&nbsp;&nbsp;{{$count_films}}
                </div>
              </a>
            </td>
            <td class="w-25">
              <img src="{{URL::asset("$tag->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$tag->name}}">
            </td>
            
            <td>
              <a href="{{url('/edit_tags_studios', $tag->id)}}" class="btn btn-info">Edytuj</a>
              <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$tag->id}}">Usuń</button> -->
              <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_tags_studios', $tag->id)}}">Usuń</a>
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
          Brak tagów do wyświetlenia.
      </ul>

      <ul>
        <a href="{{url('/add_tags_studios')}}" class="btn btn-success">Dodaj Nowy Tag</a>
      </ul>
    </div>
    
  </div>
</div>



@endif


</div>


<!-- delete fast modal

@foreach($all_tags as $tag)
  <div class="modal fade"  id="delete_{{$tag->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modtext" >
       <div class="modal-body" style="font-size:17px; text-align: center">
        Czy jeteś pewien że chcesz usunąć:
        </br></br><b>{{$tag->name}}?</b></br></br>
        Kategoria, zdjęcia oraz wszystkie elementy z nim powiązane zostaną permanentnie usunięte z bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_tags', $tag->id)}}">Usuń</a>
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
        Czy jeteś pewien że chcesz usunąć WSZYSTKIE tagi?
        </br></br>
        Kategorie, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
        </div>
        <div class="modal-footer">
          <a class="btn btn-danger" href="{{url('/delete_all_tags_studios')}}">Usuń</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
          
        </div>
      </div>
    </div>
  </div>


@endsection

@section('pagi') {{$tags->links()}} @endsection