
@extends('layouts.app')


@section('title')VideoSite @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

@if ($count_films > 0)

    @if (isset( $tags_another))
            @section('extra_content')
    @section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($admin_tags_star_data_asc))
                Data dodania - najnowsze
            @endif

            @if (isset($admin_tags_star_data_desc))
                Data dodania - najstarsze
            @endif

            @if (isset($admin_tags_star_new))
                Nazwa A - Z
            @endif

            @if (isset($admin_tags_star_old))
               Nazwa Z - A
            @endif 

            @if (isset($admin_tags_star_rating_asc))
               Ocena - rosnąco
            @endif 

            @if (isset($admin_tags_star_rating_desc))
                Ocena - malejąco
            @endif 

            @if (isset($admin_tags_star_random))
                LOSOWO
            @endif 
            
            

        

        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/select_categories_stars_date_asc', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/select_categories_stars_date_desc', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/select_categories_stars', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/select_categories_stars_desc', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_stars_rating_asc', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-up"></a>
              OCENY
              <a href="{{ url('/select_categories_stars_rating_desc', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_stars_random', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/select_categories_stars_random', $hidden_id_tags_stars) }}" class="filtr_films fas fa-arrow-down"></a></br>
        </div>
    </div>           
    
@endsection
            <div class="row" style="word-wrap: break-word;">
                <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                    <img src="{{URL::asset("$tags_another->thumbnail")}}" style="height: 270px; width: 230px;"></img></br>
                    <b>{{$tags_another->name}}</b></br>
                    @if (isset($site_url_stars))
                    Ilość Gwiazd: {{$count_films}}
                        @auth
                        <div class="t" style="font-size: 15px; padding-top: 5px;">
                            <a href="{{url('/edit_tags_stars', $tags_another->id)}}" class="btn btn-info">Edytuj</a>                        
                        </div>
                        @endauth
                    @endif

                </div>
            </div>
            @endsection

        @endif
  
        @if (isset( $tags_another_studios))
            @section('extra_content')
            @section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($admin_tags_studios_data_asc))
                Data dodania - najnowsze
            @endif

            @if (isset($admin_tags_studios_data_desc))
                Data dodania - najstarsze
            @endif

            @if (isset($admin_tags_studios_new))
                Nazwa A - Z
            @endif

            @if (isset($admin_tags_studios_old))
               Nazwa Z - A
            @endif 

            @if (isset($admin_tags_studios_rating_asc))
               Ocena - rosnąco
            @endif 

            @if (isset($admin_tags_studios_rating_desc))
                Ocena - malejąco
            @endif 

            @if (isset($admin_tags_studios_random))
                LOSOWO
            @endif 
            
            

        

        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/select_categories_studios_date_asc', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/select_categories_studios_date_desc', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/select_categories_studios', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/select_categories_studios_desc', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_studios_rating_asc', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-up"></a>
              OCENY
              <a href="{{ url('/select_categories_studios_rating_desc', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_studios_random', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/select_categories_studios_random', $hidden_id_tags_studios) }}" class="filtr_films fas fa-arrow-down"></a></br>
        </div>
    </div>           
    
@endsection
                <div class="row" style="word-wrap: break-word;">
                    <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                        <img src="{{URL::asset("$tags_another_studios->thumbnail")}}" style="height: 270px; width: 230px;"></img></br>
                        <b>{{$tags_another_studios->name}}</b></br>

                        @if (isset($site_url_studios))
                        Ilość Wytwórni: {{$count_films}}
                            @auth
                            <div class="t" style="font-size: 15px; padding-top: 5px;">
                                <a href="{{url('/edit_tags_studios', $tags_another_studios->id)}}" class="btn btn-info">Edytuj</a>                        
                            </div>
                            @endauth
                        @endif


                    </div>
                </div>
            @endsection

        @endif

        


        @if (isset( $tags_another_sites))
            @section('extra_content')
            @section('filtr_text') <div style="font-size: 20px; margin-right: 15px;"> Sortuj według:</div>
    <div class="dropdown">
        <button class="btn dropdown-toggle" style="color: white; background: #333;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
            @if (isset($admin_tags_sites_data_asc))
                Data dodania - najnowsze
            @endif

            @if (isset($admin_tags_sites_data_desc))
                Data dodania - najstarsze
            @endif

            @if (isset($admin_tags_sites_name_asc))
                Nazwa A - Z
            @endif

            @if (isset($admin_tags_sites_name_desc))
               Nazwa Z - A
            @endif 

            @if (isset($admin_tags_sites_rating_asc))
               Ocena - rosnąco
            @endif 

            @if (isset($admin_tags_sites_rating_desc))
                Ocena - malejąco
            @endif 

            @if (isset($admin_tags_sites_random))
                LOSOWO
            @endif 
            
            

        

        </button>

        <div class="dropdown-menu dropdown-filtr-menu" aria-labelledby="dropdownMenuButton">
              <a href="{{ url('/select_categories_sites', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-up"></a>
              DATY DODANIA
              <a href="{{ url('/select_categories_sites_date_asc', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-down"></a></br>
            
              <a href="{{ url('/select_categories_sites_name_asc', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-up"></a>
              NAZWY
              <a href="{{ url('/select_categories_sites_name_desc', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_sites_rating_asc', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-up"></a>
              OCENY
              <a href="{{ url('/select_categories_sites_rating_desc', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-down"></a></br>

              <a href="{{ url('/select_categories_sites_random', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-up"></a>
              LOSOWO
              <a href="{{ url('/select_categories_sites_random', $hidden_id_sites) }}" class="filtr_films fas fa-arrow-down"></a></br>
        </div>
    </div>           
    
@endsection
                <div class="row" style="word-wrap: break-word;">
                    <div style="padding-top: 2px; padding-bottom: 2px; margin-left: 7%">
                        <img src="{{URL::asset("$tags_another_sites->thumbnail")}}" style="height: 270px; width: 230px;"></img></br>
                        <b>{{$tags_another_sites->name}}</b></br>

                        @if (isset($site_url_sites))
                        Ilość Stron: {{$count_films}}
                            @auth
                            <div class="t" style="font-size: 15px; padding-top: 5px;">
                                <a href="{{url('/edit_tags_sites', $tags_another_sites->id)}}" class="btn btn-info">Edytuj</a>                        
                            </div>
                            @endauth
                        @endif


                    </div>
                </div>
            @endsection
       

        @endif

        @section('extra_content2')
                <hr style="border: 1px solid black;"></hr>
        @endsection
      


      <div class="col-sm-12 row">
  
  @foreach ($films as $tag)
    @if (isset($site_url_stars))
      <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card " style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
          @if (!isset($info_db))
              <a href="{{ url('/select_stars', $tag->id) }}">
                  <img class="card-img-top img-fluid" src="{{URL::asset("$tag->thumbnail")}}">
            
              <div class="film_number">
                  <i class="fas fa-video "></i> <?php 
                
               
                  $count_films = DB::table('stars')
                  ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
                  ->join('films', 'films.id', '=', 'films_stars.film_id')
                  ->orderBy('name', 'ASC')
                  ->select('films.*')
                  ->where('stars.id', $tag->id)
                  ->where('activ', '=', '1')
                  ->distinct()
                  ->count();


                  ?>&nbsp;&nbsp;{{$count_films}}
              </div>
          </div>
    
            <div class=" card-hover">
                <a href="{{ url('/select_stars', $tag->id) }}">
              <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                  
                {{$tag->name}}
                  
              </div>
              </a>
            </div>
            </a>
            @else
                <img class="card-img-top img-fluid" src="{{URL::asset("$tag->thumbnail")}}">
            </div>
    
            <div class=" card-hover">
            <a href="{{ url('/select_stars', $tag->id) }}">
              <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125); color: black;">
                  
                {{$tag->name}}
                  
              </div>
              </a>
            </div>

            @endif
  
         
        </div>
      </div>

    @endif




    @if (isset($site_url_studios))
      <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card " style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
          @if (!isset($info_db))
              <a href="{{ url('/select_studios', $tag->id) }}">
                  <img class="card-img-top img-fluid" src="{{URL::asset("$tag->thumbnail")}}">
  
              <div class="film_number">
                  <i class="fas fa-video "></i> <?php 
                
               
                    $count_films = DB::table('studios')
                    ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
                    ->join('films', 'films.id', '=', 'films_studios.film_id')
                    ->orderBy('name', 'ASC')
                    ->select('films.*')
                    ->where('studios.id', $tag->id)
                    ->where('activ', '=', '1')
                    ->distinct()
                    ->count();


                  ?>&nbsp;&nbsp;{{$count_films}}
              </div>
          </div>
    
            <div class=" card-hover">
                <a href="{{ url('/select_studios', $tag->id) }}">

            <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                  
                {{$tag->name}}
                  
              </div>
              </a>
          </div>
  
          </a>
          @else
                <img class="card-img-top img-fluid" src="{{URL::asset("$tag->thumbnail")}}">
            </div>
    
            <div class=" card-hover">
            <a href="{{ url('/select_studios', $tag->id) }}">
              <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125); color: black;">
                  
                {{$tag->name}}
                  
              </div>
            </a>
            </div>

            @endif
        </div>
      </div>

    @endif









    @if (isset($site_url_sites))
        <div class="col-sm-3">
        <div class=" m-2">
            <div class="card video-wrapper" style="background-color: #666666;">

            <div class="card-header text-center">
                <p class="card-text"><a style="color: #cffd00; text-decoration: none;" href="{{ $tag->link }}" target="_blank"><b>{{ $tag->name }}</b></a></p>
            </div>
                
            <div class="card-body">
                {{$tag->description}}
                <div style="padding-top: 80px;"></div>
            </div>

 
            </div>
        </div>
        </div>
    @endif
  
  
  
  @endforeach
  
@else

<div class="col-sm-12 text-center">
  
    
    <div class="vertical-center">
        <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
            
            <div class="alert alert-danger">
                <ul>
                    Brak zawartości do wyświetlenia.</br> Dodaj nową treść i spróbuj ponownie.
                </ul>

                <ul>
                    <a href="{{url('/admin_index')}}" class="btn btn-success">Panel Administracyjny</a>
                </ul>

            </div>

        </div>
    </div>


</div>

@endif 
<div class="col-sm-12">
<div style="margin-top: 80px; "></div>
</div>




@endsection
@section('pagi') {{ $films->onEachSide(0)->links() }} @endsection

