
@extends('layouts.app')


@section('title')VideoSite Tagi @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 
<div class="col-sm-12">

@if( $count_tags > 0 )

@section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($categories_asc))
                Data dodania - najstarsze
            @endif

            @if (isset($categories))
                Data dodania - najnowsze
            @endif

            @if (isset($categories_name_asc))
                Nazwa A - Z
            @endif

            @if (isset($categories_name_desc))
                Nazwa Z - A
            @endif

            @if (isset($categories_random_info))
                LOSOWO
            @endif
            
        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/tags_studios') }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/tags_studios_asc') }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/tags_studios_name_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/tags_studios_name_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/categories_studios_random') }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/categories_studios_random') }}" class="filtr_films fas fa-arrow-down"></a></br>
        </div>
    </div>        
        
    
    
@endsection


    <div class="" style="float:right;">             
        <div class="input-group" style="padding-top: 18px; margin-bottom:10px; width: 250px;">
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
			url:"{{url('/searchtag_studios')}}",
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



<div class="col-sm-12 row">
  
@foreach ($tags as $tag)

<div class="col-sm-3 col-m-2" style="padding-top: 20px;">
      <div class="card " style="background-color: #F5F5F5; border: none !important;">
        <div class="wrapper">
            <a href="{{ url('/select_categories_studios', $tag->id) }}">
                <img class="card-img-top img-fluid" src="{{URL::asset("$tag->thumbnail")}}">

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
        </div>

        <div class=" card-hover">
        <a href="{{ url('/select_categories_studios', $tag->id) }}">
            <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                
                    {{$tag->name}}
                
            </div>
            </a>
        </div>

        </a>
      </div>
    </div>



@endforeach


<div class="col-sm-12" style="padding-top: 5%;"></div>

@else
<div class="col-sm-12 text-center">
  
    
    <div class="vertical-center">
        <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
            
            <div class="alert alert-danger">
                <ul>
                    Brak tagów do wyświetlenia.
                </ul>

                <ul>
                    <a href="{{url('/add_tags')}}" class="btn btn-success">Dodaj Nowy Tag</a>
                </ul>

            </div>

        </div>
    </div>


</div>
@endif



</div>



@endsection
@section('pagi') {{$tags->links()}} @endsection