
@extends('layouts.app')


@section('title')VideoSite Wytwórnie @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 
<div class="col-sm-12">

@if( $count_studios >0 )
@section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($studios_asc))
                Data dodania - najstarsze
            @endif

            @if (isset($studios_i))
                Data dodania - najnowsze
            @endif

            @if (isset($studios_name_asc))
                Nazwa A - Z
            @endif

            @if (isset($studios_name_desc))
                Nazwa Z - A
            @endif

            @if (isset($studios_rating_asc))
                Ocena - rosnąco
            @endif

            @if (isset($studios_rating_desc))
                Ocena - malejąco
            @endif

            @if (isset($studios_random_info))
                LOSOWO
            @endif


        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/studios') }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/studios_asc') }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/studios_name_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/studios_name_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/studios_rating_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              OCENY
              <a href="{{ url('/studios_rating_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/studios_random') }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/studios_random') }}" class="filtr_films fas fa-arrow-down"></a></br>
        </div>
    </div>           
    
@endsection

@section('filtr_text') <div style="font-size: 18px;"> Sortuj według:</div> @endsection
      @section('filtr_link')
      
      


         
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
			url:"{{url('/searchstudios')}}",
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
@foreach ($studios as $studio)


    <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
      <div class="card " style="background-color: #F5F5F5; border: none !important;">
        <div class="wrapper">
            <a href="{{ url('/select_studios', $studio->id) }}">
                <img class="card-img-top img-fluid" src="{{URL::asset("$studio->thumbnail")}}">

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

            <div class="film_number_star">
                <i class="fas fa-star "></i>
                &nbsp;&nbsp;{{$studio->rating}}
            </div>

        </div>

        <div class=" card-hover">
        <a href="{{ url('/select_studios', $studio->id) }}">
            <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                
                    {{$studio->name}}
                
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
                    Brak wytwórni do wyświetlenia.
                </ul>

                <ul>
                    <a href="{{url('/add_studios')}}" class="btn btn-success">Dodaj Nową wytwórnie</a>
                </ul>

            </div>

        </div>
    </div>


</div>


@endif


</div>



@endsection
@section('pagi') {{$studios->links()}} @endsection