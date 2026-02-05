
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button>

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <a class="btn btn-outline-primary" href="{{url('/absence_films')}}" style="margin-bottom: 20px; margin-right: 10px;"> BRAKI W FILMACH</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_tags')}}" style="margin-bottom: 20px; margin-right: 10px;"> BRAKI W TAGACH</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_stars')}}" style="margin-bottom: 20px; margin-right: 10px;"> BRAKI W GWIAZDACH</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_studios')}}" style="margin-bottom: 20px; margin-right: 10px;"> BRAKI W WYTWÓRNIACH</a></br>
    </ul>
  </div>

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h2>Spis plików których brakuje w folderach</h2>
    </ul>
  </div>
    
  @if( $stars_status >0 )
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

    
  </div>

<div class="col-sm-12">

    <div class="" style="float:left;">             
        <div class="input-group" style="padding-top: 18px; margin-bottom:10px; width: 200px;">
    </div>
</div>          

</div>

<div class="col-sm-12 row" id="result"></div>
    
<div class="container">
    <div class="text-center" style="color: red;"><h4>Gwiazdy</h4></div>
</div> 



<div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image">
      <thead class="thead-dark">
		    <tr>
          <th scope="col" style="width: 15%">
            #
          </th></th>

          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Miniatura
          </th>
        


		    </tr>
		  </thead>
		  <tbody>
        @foreach($stars as $key => $star)
          <tr>
            <th scope="row">{{$stars->firstItem() + $key}}</th>
            <td class="table_site"><a href="{{url('/edit_stars', $star->id)}}" >{{$star->name}}</a></td>
            <td style="width: 3% !important;">
     

            @if($star->no_stars =='0')         
            <i class="far fa-times-circle"></i>
            @else
            <i class="far fa-check-circle"></i>
            @endif
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
            Nie brakuje żadnego pliku. 
          </ul>


      </div>

  </div>
</div>
@endif


</div>








@endsection

@section('pagi') {{$stars->links()}} @endsection