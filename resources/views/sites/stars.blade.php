
@extends('layouts.app')


@section('title')VideoSite Gwiazdy @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 
<div class="col-sm-12">

@if( $count_stars >0 )

@section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($stars_asc))
                Data dodania - najstarsze
            @endif

            @if (isset($stars_i))
                Data dodania - najnowsze
            @endif

            @if (isset($stars_name_asc))
                Nazwa A - Z
            @endif

            @if (isset($stars_name_desc))
                Nazwa Z - A
            @endif

            @if (isset($stars_rating_asc))
                Ocena - rosnąco
            @endif

            @if (isset($stars_rating_desc))
                Ocena - malejąco
            @endif

            @if (isset($stars_gender_male))
                Płeć - mężczyzna
            @endif

            @if (isset($stars_gender_female))
                Płeć - kobieta
            @endif

            @if (isset($stars_random_info))
                LOSOWO
            @endif


        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/stars') }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/stars_asc') }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/stars_name_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/stars_name_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/stars_rating_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              OCENY
              <a href="{{ url('/stars_rating_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/stars_gender_male_asc') }}" class="filtr_films fas fa-arrow-up"></a>
              PŁEĆ
              <a href="{{ url('/stars_gender_female_desc') }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/stars_random') }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/stars_random') }}" class="filtr_films fas fa-arrow-down"></a></br>
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
			url:"{{url('searchstar')}}",
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
@foreach ($stars as $star)


    <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
      <div class="card " style="background-color: #F5F5F5; border: none !important;">
        <div class="wrapper">
            <a href="{{ url('/select_stars', $star->id) }}">
                <img class="card-img-top img-fluid" src="{{URL::asset("$star->thumbnail")}}">

            <div class="film_number">
                <i class="fas fa-video "></i> <?php 

                  $count_films = DB::table('stars')
                  ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
                  ->join('films', 'films.id', '=', 'films_stars.film_id')
                  ->orderBy('name', 'ASC')
                  ->select('films.*')
                  ->where('stars.id', $star->id)
                  ->where('activ', '=', '1')
                  ->distinct()
                  ->count();

                  ?>&nbsp;&nbsp;{{$count_films}}
            </div>

            <div class="film_number_star">
                <i class="fas fa-star "></i>
                &nbsp;&nbsp;{{$star->rating}}
            </div>

        </div>

        <div class=" card-hover">
        <a href="{{ url('/select_stars', $star->id) }}">
            <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                
                    {{$star->name}}
                
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
                    Brak gwiazd do wyświetlenia.
                </ul>

                <ul>
                    <a href="{{url('/add_stars')}}" class="btn btn-success">Dodaj Nową gwiazdę</a>
                </ul>

            </div>

        </div>
    </div>


</div>


@endif


</div>



@endsection
@section('pagi') {{$stars->links()}} @endsection