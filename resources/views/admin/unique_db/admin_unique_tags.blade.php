
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
        @if(isset($tags_name))
          <h2>Duplikaty Tagów</h2>
        @endif
        @if(isset($stars_name))
          <h2>Duplikaty Gwiazd</h2>
        @endif
        @if(isset($studios_name))
          <h2>Duplikaty Wytwórni</h2>
        @endif

      
      <a href="{{url('/admin_films')}}" ><button class="btn btn-success">Strona domowa filmów</button></a></br></br>

        <a href="{{url('/unique_tags')}}" ><button class="btn btn-success">Duplikaty Tagów</button></a>
        <a href="{{url('/unique_stars')}}" ><button class="btn btn-success">Duplikaty Gwiazd</button></a>
        <a href="{{url('/unique_studios')}}" ><button class="btn btn-success">Duplikaty Wytwórni</button></a>
    </ul>
    
  </div>
    
  @if( $count_films >0 )
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

   

    <a href="{{url('/add_films')}}" ><button class="btn btn-success">Dodaj Nowy Film</button></a>
    

    
  </div>
  </div>



<div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image">
      <thead class="thead-dark" style=" text-align: center;">
		    <tr>
          <th scope="col"><a href="{{url('/films_name_asc')}}" class="table_link fas fa-arrow-up"></a>
            Nazwa
            <a href="{{url('/films_name_desc')}}" class="table_link fas fa-arrow-down"></a>
          </th>
		    </tr>
		  </thead>
		  <tbody>
        @if(isset($tags_name))
        @foreach($films_tags as $row)
          <tr>
            <td class="table_site" style=" text-align: center;">
              <b>
                <a href="{{url('/edit_films', $row['id'])}}" >
                {{ $row['name'] }}
               
                </a>
              </b>
            </td>
            </td>
          </tr>
        @endforeach
        @endif

        @if(isset($stars_name))
        @foreach($films_stars as $row)
          <tr>
            <td class="table_site" style=" text-align: center;">
              <b>
                <a href="{{url('/edit_films', $row['id'])}}" >
                {{ $row['name'] }}
               
                </a>
              </b>
            </td>
            </td>
          </tr>
        @endforeach
        @endif

        @if(isset($studios_name))
        @foreach($films_studios as $row)
          <tr>
            <td class="table_site" style=" text-align: center;">
              <b>
                <a href="{{url('/edit_films', $row['id'])}}" >
                {{ $row['name'] }}
               
                </a>
              </b>
            </td>
            </td>
          </tr>
        @endforeach
        @endif
		  </tbody>
		</table>   
    </div>
  </div>
</div>


@else
<div class="vertical-center">
  <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
      
      <div class="alert alert-danger">
        @if(isset($tags_name))
          <ul>
              Brak duplikatów tagów w filmach.
          </ul>
        @endif

        @if(isset($stars_name))
          <ul>
              Brak duplikatów gwiazd w filmach.
          </ul>
        @endif

        @if(isset($studios_name))
          <ul>
              Brak duplikatów wytwórni w filmach.
          </ul>
        @endif
          <ul>
            <a href="{{url('/admin_films')}}" ><button class="btn btn-success">Strona domowa filmów</button></a>
            
          </ul>

      </div>

  </div>
</div>
@endif


</div>






@endsection

@section('pagi')@endsection