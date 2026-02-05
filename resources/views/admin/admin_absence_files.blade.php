
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <h3>Tutaj sprawdzisz czy w folderze filmy znajdują się pliki których nie ma zapisanych w bazie danych.</h3>
    </ul>
  </div>
</div>

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

  <div style="float: right; float: right; padding-top: 18px; margin-bottom:10px;" ></div>



</div>



<div class="accordion content_colapse" id="accordionExample">
  <div class="card">
    <div class="card-header content_colapse" id="headingOne">
      <h2 class="mb-0 content_colapse">
        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Filmy
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show content_colapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
      
    
<div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image" style="text-align: center;">
      <thead class="thead-dark">
       <tr>
		    <tr>
          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Akcja
          </th>


		    </tr>
		  </thead>
		  <tbody>
        <?php $id = 0; ?>
        @foreach($file_not_exist as $key => $file_not_exist)
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$file_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form name="input">
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$file_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$inside_films_url}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="0" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
                  </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$file_not_exist}}" hidden>
                  <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
              </div>
            </td> 
          </tr>
        @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>
    

<script>

  $("button[data-id]").click(function(e) {
  e.preventDefault();
  let id = $(this).attr('data-id');
  let show_files_url = $('input[data-id="' + id + '"][data-name="show_files_url"]').val();
  let show_files_folder = $('input[data-id="' + id + '"][data-name="show_files_folder"]').val();
  let show_files_folder_number = $('input[data-id="' + id + '"][data-name="show_files_folder_number"]').val();

  //alert('ID: ' + id + ' | show_files_url: ' + show_files_url + ' | show_files_folder: ' + show_files_folder + ' | show_files_folder_number: ' + show_files_folder_number);

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
        type: 'POST',
        url: "{{url('/show_absence_files')}}",
        data: {show_files_url: show_files_url, show_files_folder: show_files_folder, show_files_folder_number: show_files_folder_number },
        success: function(data)
        {

        }
    });


  });

</script>




      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header content_colapse" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Short
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse content_colapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
       
      <div class="">
  <div class="row">
    <div class="col-12">
		<table class="table table-image" style="text-align: center;">
      <thead class="thead-dark">
      	<tr>
          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Akcja
          </th>

		    </tr>
		  </thead>
		  <tbody>
        @foreach($file_not_exist_short as $key => $file_not_exist_short)
          <tr>
            <td class="table_site">{{$file_not_exist_short}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form action="{{ url('/show_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="show_files_url" value="{{$file_not_exist_short}}" hidden>
                  <input name="show_files_folder" value="{{$inside_short_url}}" hidden>
                  <input name="show_files_folder_number" value="0" hidden>
                  <button type="submit" class="btn btn-success">Pokaż</button>
                </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$file_not_exist_short}}" hidden>
                  <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
              </div>
            </td>        
          </tr>
        @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>
  </div>
  </div>
</div>
  
  <div class="card">
    <div class="card-header content_colapse" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Thumbnail
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse content_colapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
    
<div class="">
  <div class="row">
    <div class="col-12">
      
		<table class="table table-image" style="text-align: center;">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Akcja
          </th>

		    </tr>
		  </thead>
		  <tbody>
        @foreach($file_not_exist_thumbnail as $key => $file_not_exist_thumbnail)
          <tr>
            <td class="table_site">{{$file_not_exist_thumbnail}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form action="{{ url('/show_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="show_files_url" value="{{$file_not_exist_thumbnail}}" hidden>
                  <input name="show_files_folder" value="{{$inside_thumbnail_url}}" hidden>
                  <input name="show_files_folder_number" value="0" hidden>
                  <button type="submit" class="btn btn-success">Pokaż</button>
                </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$file_not_exist_thumbnail}}" hidden>
                  <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
              </div>

            </td>
         
         
          </tr>
        @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>
  </div>
  </div>
</div>


  <div class="card">
    <div class="card-header content_colapse" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
        Tagi
        </button>
      </h2>
    </div>
    <div id="collapseFour" class="collapse content_colapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
    
<div class="">
  <div class="row">
    <div class="col-12">
      
		<table class="table table-image" style="text-align: center;">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Akcja
          </th>

		    </tr>
		  </thead>
		  <tbody>
        @foreach($tags_not_exist as $key => $tags_not_exist)
          <tr>
            <td class="table_site">{{$tags_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form action="{{ url('/show_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="show_files_url" value="{{$tags_not_exist}}" hidden>
                  <input name="show_files_folder" value="{{$tags_not_exist}}" hidden>
                  <input name="show_files_folder_number" value="1" hidden>
                  <button type="submit" class="btn btn-success">Pokaż</button>
                </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$tags_not_exist}}" hidden>
                  <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
              </div>

            </td>
         
         
          </tr>
        @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>   
  </div>
  </div>
</div>








  <div class="card">
    <div class="card-header content_colapse" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-info collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
        Wytwórnie
        </button>
      </h2>
    </div>
    <div id="collapseSix" class="collapse content_colapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
    
<div class="">
  <div class="row">
    <div class="col-12">
      
		<table class="table table-image" style="text-align: center;">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 35%">
            Nazwa
          </th>

          <th scope="col" style="width: 15%">
            Akcja
          </th>

		    </tr>
		  </thead>
		  <tbody>
        @foreach($studios_not_exist as $key => $studios_not_exist)
          <tr>
            <td class="table_site">{{$studios_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form action="{{ url('/show_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="show_files_url" value="{{$studios_not_exist}}" hidden>
                  <input name="show_files_folder" value="{{$studios_not_exist}}" hidden>
                  <input name="show_files_folder_number" value="1" hidden>
                  <button type="submit" class="btn btn-success">Pokaż</button>
                </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$studios_not_exist}}" hidden>
                  <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
              </div>

            </td>
         
         
          </tr>
        @endforeach
		  </tbody>
		</table>   
    </div>
  </div>
</div>
    
    
    
    
    </div>
    </div>
  </div>

</div>

<div style="padding-top: 10%;"></div>


@endsection

@section('pagi') @endsection