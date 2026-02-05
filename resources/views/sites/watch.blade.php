
@extends('layouts.app')


@section('title')VideoSite @endsection


@section('content')

<div class="col-sm-12 text-center" style="padding-top: 5px; padding-bottom: 5px">
    @if(!empty($successMsg))
    <div id="successMessage" class="alert alert-success"> {{ $successMsg }}</div>
    @endif
</div>
<script type="text/javascript">window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000); </script>


<div class="w-100 text-center" style="padding-bottom: 5px;"></div>

<div class="col-sm-12">

<div class="col-12 col-sm-12 col-md-8">
    <video className="card-img-top bg-light mb-3 img-responsive video-responsive w-100" src="{{URL::asset("$films->url")}}" style="height: 60%;" controls></video>
    <div class="t" style="font-size: 20px; padding-top: 20px;"><b>{{$films->name}}</b></br></br></div>
</div>


  

</div>

<div class="col-sm-6">

    <div >
        
<div>
<div>
<div class="ratings">
@if($films->rating == 1)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
@endif
@if($films->rating == 2)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
@endif
@if($films->rating == 3)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
@endif
@if($films->rating == 4)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x"></i>
<i class="star fa fa-star fa-2x"></i>
@endif
@if($films->rating == 5)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x"></i>
@endif
@if($films->rating == 6)
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
<i class="star fa fa-star fa-2x" style="color: gold;"></i>
@endif
</div>
</div></br>
        @guest
        @else            
            
            <a href="{{url('edit_films', $films->id)}}" class="btn btn-info">Edytuj</a>

        @endguest

            <div class="t" style="font-size: 15px; padding-top: 20px;">

            <a href="{{ url('/index_random_film_watch') }}">
                <button class="btn btn-success">
                    <i class="" aria-hidden="true" style="color: white;"></i>
                    <span> Losowy Film</span>
                </button>
            </a>  
            </br></br>

            <a href="{{URL::asset("$films->url")}}" download="{{"$films->name"}}"
                <button class="btn btn-success">
                    <i class="fa fa-download" aria-hidden="true" style="color: white;"></i>
                    <span> Download Film</span>
                </button>
            </a>
            <a href="{{URL::asset("$films->short")}}" download="Short_{{"$films->name"}}"
                <button class="btn btn-success">
                    <i class="fa fa-download" aria-hidden="true" style="color: white;"></i>
                    <span> Download Short</span>
                </button>
            </a>
            <a href="{{URL::asset("$films->thumbnail")}}" download="Thumbnail_{{"$films->name"}}"
                <button class="btn btn-success">
                    <i class="fa fa-download" aria-hidden="true" style="color: white;"></i>
                    <span> Download Img</span>
                </button>
            </a>
            
        </div>
</div> 
  
    @if(count($tags) > 0)
        <div class="" style="padding-bottom:20px; margin-top: 10px; font-size: 20px;">
                    
            <b> Tagi</b></br>
            
            @foreach ($tags as $tag)
                <a href="{{ url('/select_categories', $tag->id) }}"class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$tag->name}}</a>
            @endforeach
            
        </div>
    @endif

    @if(count($stars) > 0)
        <div class="" style="padding-bottom:20px; margin-top: 10px; font-size: 20px;">

            <b>Gwiazdy</b>

            <div class="tagForm">
                
                @foreach ($stars as $star)
                    <a href="{{ url('/select_stars', $star->id) }}" class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$star->name}}</a>
                @endforeach
            
            </div>

        </div>
    @endif

    @if($stars_tags === 0)
    @elseif(count($stars_tags) > 0)
        <div class="" style="padding-bottom:20px; margin-top: 10px; font-size: 20px;">

            <b>Tagi Gwiazd</b>

            <div class="studioForm">
            
                @foreach ($stars_tags as $stars_tags)
                    <a href="{{ url('/select_categories_stars', $stars_tags->tag_id) }}" class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$stars_tags->name}}</a>
                @endforeach
            
            </div>

        </div>
    @endif

    @if(count($studios) > 0)
        <div class="" style="padding-bottom:20px; margin-top: 10px; font-size: 20px;">

            <b>Wytwórnie</b>

            <div class="studioForm">
            
                @foreach ($studios as $studios)
                    <a href="{{ url('/select_studios', $studios->id) }}" class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$studios->name}}</a>
                @endforeach
            
            </div>

        </div>
    @endif

    @if($studios_tags === 0)
    @elseif(count($studios_tags) > 0)
        <div class="" style="padding-bottom:20px; margin-top: 10px; font-size: 20px;">

            <b>Tagi Wytwórni</b>

            <div class="studioForm">
            
                @foreach ($studios_tags as $studios_tags)
                    <a href="{{ url('/select_categories_studios', $studios_tags->tag_id) }}" class="badge badge-secondary" style="font-size: 1.1em; margin-right: 7px; margin-top: 7px; background-color: #343434;">{{$studios_tags->name}}</a>
                @endforeach
            
            </div> 

        </div>
    @endif


    </div>

    <div class="text-center" style="margin-bottom:80px;"></div>
</div>


<div class="col-sm-6">

    <div class="text-center" style="padding-bottom:20px;">
        <div class=""> </div>
        

    </div>

    <div class="text-center" style="padding-bottom:20px;">
        <div class=""></div>
    

    </div>

    

</div>


@endsection