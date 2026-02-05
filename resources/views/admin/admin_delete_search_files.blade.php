
@extends('layouts.admin')

@section('title')VideoSite Konwersja Filmów @endsection


@section('content')

<script src="{{ asset('js/admin_films.js') }}" defer></script> <!-- all script for forms -->


<button onclick="topFunction()" id="myBtn">Top</button> 
@foreach ($files_db as $files_db)
<div class="w-100 text-center" style="padding-bottom: 15px;"><h1>Usuń {{$files_db->name}}<h1>

</div>


    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
   
        @if (\Session::has('msg_success'))
        <div class="alert alert-success">
            <ul>
                {!! \Session::get('msg_success') !!}
            </ul>
        </div>
        @endif

        @if (\Session::has('msg_errors'))
        <div class="alert alert-danger">
            <ul>
                {!! \Session::get('msg_errors') !!}
            </ul>
        </div>
        @endif

    </div>
</div>


<div class="container">
    <div class="row justify-content-center text-center" style="text-decoration: none !important;">

    <div class="col align-self-center" style="text-decoration: none !important;">
    Czy jesteś pewien że chcesz usunąć <b>{{$files_db->name}}?</b> z zakładki {{$name_files}}?</br>
    {{$name_files}}, zdjęcia oraz wszystkie elementy z nim powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
    <div style="margin-top:2%"></div>

    @if($name_files == "Strona")
    <div class="text-center" style="padding-bottom:20px;">
    <h4><b>OPIS</b></h4>
        {{$files_db->description}}
    </div>
    @endif

    @if($name_files == "Tag" OR $name_files == "Gwiazda" OR $name_files == "Wytwórnia" OR $name_files == "Tagi Gwiazd" OR $name_files == "Tagi Gwiazd" OR $name_files == "Tagi Wytwórni" OR $name_files == "Tagi Stron")
    <div class="text-center" style="padding-bottom:10px;">
        <img src="{{URL::asset("$files_db->thumbnail")}}" class="img-fluid img-thumbnail" alt="Maid">
    </div>
    @endif
    @if($name_files == "Film")
  
    <div class="text-center" style="padding-bottom:10px;">
        <div class=""> Thumbnail</div>
        <img src="{{URL::asset("$files_db->thumbnail")}}" style="max-width: 50%; height: 80%;">
        
    </div>

    <div class="col-sm-8 offset-sm-2" style="text-decoration: none !important;">
        <!-- use this class to sticky player "sticky_video_edit" -->
        <div class="text-center " style="padding-bottom:20px;">
            <div class=""> Film</div>
            <video src="{{URL::asset("$files_db->url")}}" controls="" width="320" height="200"></video>

        </div>

        <div class="text-center" style="padding-bottom:20px;">
            <div class=""> Trailer</div>
            <video src="{{URL::asset("$files_db->short")}}" controls="" width="320" height="200"></video>
        </div>


    </div>

    
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_films_save', $id)}}"> Usuń </a>
    @endif
    @if($name_files == "Tag")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_tags_save', $id)}}">  Usuń </a>
    @endif
    @if($name_files == "Gwiazda")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_stars_save', $id)}}"> Usuń</a>
    @endif
    @if($name_files == "Wytwórnia")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_studios_save', $id)}}"> Usuń </a>
    @endif
    @if($name_files == "Strona")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_site_save', $id)}}"> Usuń </a>
    @endif

    @if($name_files == "Tagi Gwiazd")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_tags_stars_save', $id)}}"> Usuń </a>
    @endif
    @if($name_files == "Tagi Wytwórni")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_tags_studios_save', $id)}}"> Usuń </a>
    @endif
    @if($name_files == "Tagi Stron")
    <a class="btn btn-danger" style="margin-right: 15px;" href="{{url('/delete_files_from_admin_search_tags_sites_save', $id)}}"> Usuń </a>
    @endif
    <a class="btn btn-success" href="{{ url()->previous() }}" > Wróć </a>
    </div>

       
    </div>
</div>
<div style="padding-bottom: 50px;"></div>
@endforeach

@endsection
