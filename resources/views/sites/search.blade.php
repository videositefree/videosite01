
@extends('layouts.app')


@section('title')VideoSite wyszukaj @endsection


@section('search')
<button onclick="topFunction()" id="myBtn">Top</button> 
<style>
.search{
  background-color: #333;
}
</style>

<button class="btn btn-primary col-sm-2 " type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Filtry</button>


                
              <div class="col-sm-3 col-lg-3">
                
              <form action="{{ url('/search/method') }}" method="GET">
                <input type="hidden" name="search" value="{{$search}}"><br>
                Sortuj wg: <a class="" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="color: red;">Powiązanie</a><br><br>
                  <div class="collapse multi-collapse " id="multiCollapseExample1">
                  <input type="radio" class="radio_serch" name="sort" value="relevance">  Powiązanie z tagami<br>
                  <input type="radio" class="radio_serch" name="sort" value="uploaddate">  Data przesłania<br>
                  <input type="radio" class="radio_serch" name="sort" value="rating">  Ocena<br>
                  <input type="radio" class="radio_serch" name="sort" value="length">  Długość<br>
                
                </div>
              </div>
          
          
              <div class="col-sm-3 col-lg-3">
              <br>
                Data: <a class="" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="color: red;">Cały okres</a><br><br>
                  <div class="collapse multi-collapse " id="multiCollapseExample1">
                  <input type="radio" class="radio_serch" name="date" value="all"> Cały okres<br>
                  <input type="radio" class="radio_serch" name="date" value="today"> Ostatnie 3 dni<br>
                  <input type="radio" class="radio_serch" name="date" value="week"> W tym tygodniu<br>
                  <input type="radio" class="radio_serch" name="date" value="month"> W tym miesiącu<br>
                  <input type="radio" class="radio_serch" name="date" value="3month"> Ostanie 3 miesiące<br>
                  <input type="radio" class="radio_serch" name="date" value="6month"> Ostanie 6 miesięcy<br><br>

                  </div>
              </div>
          
              <div class="col-sm-4 col-lg-4">
              <br>
                Czas trwania: <a class="" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" style="color: red;">Wszystkie</a><br><br>
                  <div class="collapse multi-collapse " id="multiCollapseExample1">
                  <input type="radio" class="radio_serch" name="time" value="3-10min"> krótkie filmiki (3-10 min)<br>
                  <input type="radio" class="radio_serch" name="time" value="10-20min"> średnie filmy (10-20min)<br>
                  <input type="radio" class="radio_serch" name="time" value="20-40min"> Długie filmy (20-40min)<br>
                  <input type="radio" class="radio_serch" name="time" value="40min_more"> Pełnometrażowe filmy (+40min)<br><br>
                    
                  <button class="btn btn-success" type="submit" style="margin-top: 5px;">Wyszukaj
                  </div>
                  </form>
              </div>
            
@endsection



@section('content')

@foreach ($films as $film)



<div class="col-sm-4">
    <div class=" m-2">
        <div class="card video-wrapper" style="background-color: #F5F5F5;">

                <video className="card-img-top bg-light mb-3 img-responsive video-responsive" src="{{URL::asset("$film->short")}}" poster="{{URL::asset("$film->thumbnail")}}" loop  preload="metadata" controls muted></video>
                
                
                <div class="film_duration">
                    <i class="fa fa-clock-o"></i> &nbsp;@php echo gmdate("H:i:s", $film->duration); @endphp
                </div>

                <div class="film_rating">
                    <i class="far fa-star"></i> &nbsp;{{$film->rating}} 
                </div>

                <div class="card-hover" style="text-align: center; " >
                        
                            <a href="{{ url('/watch', $film->id) }}">
                        <div style=" max-height: 100px; min-height: 80px; padding-top: 30px; font-size: 13px;" class="text_video_name">
                        {{$film->name}}  
                        </div> 
                        </a> 
                </div> 
        </div>
    </div>
</div>


@endforeach








@endsection
@section('pagi') {{ $films->appends(Request::all())->links() }} @endsection



