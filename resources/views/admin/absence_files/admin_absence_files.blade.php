
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')
<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center">
  <div class="col-sm-12 text-center" style="padding-bottom: 10px;">
    <ul>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_films')}}" style="margin-bottom: 20px; margin-right: 10px;"> FILMY </a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_short')}}" style="margin-bottom: 20px; margin-right: 10px;"> SHORT</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_thumbnail')}}" style="margin-bottom: 20px; margin-right: 10px;"> ZDJĘCIA </a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_tags')}}" style="margin-bottom: 20px; margin-right: 10px;"> TAGI FILMOWE</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_stars')}}" style="margin-bottom: 20px; margin-right: 10px;"> GWIAZDY</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_studios')}}" style="margin-bottom: 20px; margin-right: 10px;"> WYTWÓRNIE</a></br>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_tags_stars')}}" style="margin-bottom: 20px; margin-right: 10px;"> TAGI GWIAZD</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_tags_studios')}}" style="margin-bottom: 20px; margin-right: 10px;"> TAGI WYTWÓRNI</a>
      <a class="btn btn-outline-primary" href="{{url('/absence_files_tags_sites')}}" style="margin-bottom: 20px; margin-right: 10px;"> TAGI STRON</a></br>

      <h3>Pliki których nie ma w bazie danych jednocześnie są w folderach.</h3>

    </ul>
  </div>
  <h5>Folder: 
  @if(isset($check_db_films)) Filmy @endif
  @if(isset($check_db_short)) Short @endif
  @if(isset($check_db_thumbnail)) Thumbnail @endif
  @if(isset($check_db_tags)) Thumbnail/Tagi @endif
  @if(isset($check_db_tags_stars)) Thumbnail/Tags_Stars @endif
  @if(isset($check_db_tags_studios)) Thumbnail/Tags_Studios @endif
  @if(isset($check_db_tags_sites)) Thumbnail/Tags_Sites @endif
  @if(isset($check_db_stars)) Thumbnail/Stars @endif
  @if(isset($check_db_studios)) Thumbnail/Studios @endif

  <h5>


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

  <div class="container">
    <div class="text-center" style="color: red;">UWAGA USUWANIE PLIKÓW WYSTĘPUJE BEZ POTWIERDZENIA OZNACZA TO ŻE ZOSTANĄ USUNIĘTE PO KLIKNIĘCIU USUŃ !!!</div>
    <div class="text-center" style="color: red;">JEŚLI WYŚWIETLA SIĘ NAZWA FOLDERU TO OZNACZA ŻE NIE JEST WYKORZYSTYWANY PRZEZ PRZEZ STRONĘ DOTYCZY TYLKO FOLDERU "Folder: Filmy"</div>
    
    <div style="padding-top: 10px;"></div>
  </div>

  
  
</div>
<!-- ================================================================= FILMY ============================================================== -->
<?php $id = 0; ?>
@if(isset($check_db_films))
   
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
        @foreach($file_not_exist as $key => $file_not_exist)
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$file_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form>
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$file_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$file_not_exist}}" hidden/>
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
@endif

<!-- ================================================================= SHORT ============================================================== -->
@if(isset($check_db_short))
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
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$file_not_exist_short}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form>
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$file_not_exist_short}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$file_not_exist_short}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="0" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
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
@endif


  

<!-- ================================================================= Thumbnail ============================================================== -->

@if(isset($check_db_thumbnail))  
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
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$file_not_exist_thumbnail}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form>
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$file_not_exist_thumbnail}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$file_not_exist_thumbnail}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="0" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
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
@endif



<!-- ========================== THUMBNAIL/TAGS | THUMBNAIL/TAGS_STARS | THUMBNAIL/TAGS_STUDIOS | THUMBNAIL/TAGS_SITES  ========================== -->

@if(isset($check_db_tags) OR isset($check_db_tags_stars) OR isset($check_db_tags_studios) OR isset($check_db_tags_sites))  
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
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$tags_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form>
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$tags_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$tags_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="1" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
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
@endif



<!-- ================================================================= Thumbnail/STARS ============================================================== -->


@if(isset($check_db_stars))  
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
        @foreach($stars_not_exist as $key => $stars_not_exist)
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$stars_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form>
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$stars_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$stars_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="1" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
                </form>
                <div style="margin-left: 5px;">
                <form action="{{ url('/delete_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input name="delete_files_url" value="{{$stars_not_exist}}" hidden>
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
@endif



<!-- ================================================================= Thumbnail/STUDIOS ============================================================== -->


@if(isset($check_db_studios))  
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
        <?php $id++ ?>
          <tr>
            <td class="table_site">{{$studios_not_exist}}</a></td>
            <td class="table_site">
              <div style="display: inline-flex;">
                <form action="{{ url('/show_absence_files') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_url" name="show_files_url" value="{{$studios_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder" name="show_files_folder" value="{{$studios_not_exist}}" hidden/>
                  <input type="text" data-id="<?php echo $id; ?>" data-name="show_files_folder_number" name="show_files_folder_number" value="1" hidden/>
                  <button data-id="<?php echo $id; ?>" data-show-files-url="tutaj_url" data-show_files_folde class="btn btn-success">Pokaż</button>
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
@endif

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





<div style="padding-top: 10%;"></div>


@endsection

@section('pagi') @endsection