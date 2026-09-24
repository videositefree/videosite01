
@extends('layouts.app')


@section('title')VideoSite Tagi @endsection


@section('content')
<div class="col-sm-12">

@if( $count_tags >0 )

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
            @if (isset($categories_stars_random))
                LOSOWO
            @endif
            
        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/tags_stars') }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/tags_stars_asc') }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/tags_stars_name_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/tags_stars_name_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/tags_stars_random') }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/tags_stars_random') }}" class="filtr_films fas fa-arrow-down"></a></br>
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
			url:"{{url('/searchtag_stars')}}",
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



<div class="entity-grid">
  
@foreach ($tags as $tag)

<?php
    $count_films = DB::table('tags_stars')
    ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags_stars.id')
    ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
    ->orderBy('name', 'ASC')
    ->select('stars.*')
    ->where('tags_stars.id', $tag->id)
    ->where('stars_tags.tag_db', 0)
    ->distinct()
    ->count();
?>

    <a href="{{ url('/select_categories_stars', $tag->id) }}" class="entity-card">
            <div class="entity-card__media">
                <img src="{{URL::asset("$tag->thumbnail")}}" alt="{{ $tag->name }}" loading="lazy">
                <div class="film_number">
                    <i class="fas fa-tag"></i>&nbsp;&nbsp;{{$count_films}}
                </div>
            </div>
            <div class="entity-card__body">
                {{$tag->name}}
            </div>
        </a>

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