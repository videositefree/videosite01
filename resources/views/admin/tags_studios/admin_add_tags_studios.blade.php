
@extends('layouts.admin')

@section('title')VideoSite Dodaj Tag @endsection


@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_tags_studios') }}">Kategorie</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dodawanie Kategorii</li>
</ol>
@endsection

@section('content')

<div class="w-100 h-50 text-center" ><h1> Dodaj Nowy Tag dla Wytwórni</h1></div>

    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 15px; padding-bottom: 15px">
    
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

<div class="col-sm-6 text-center offset-sm-3">




<div class="conatainer" style="padding-top: 10px; padding-bottom: 20px;">
    <div class="row d-flex justify-content-center">
      <img src="../public/icon/app.blade/notfing_found.png" id="video_here" style="height: 350px; width:250px;"> </img>
    </div>
</div>




  <form action="{{url('/save_tags_studios')}}" method="POST" enctype="multipart/form-data" id="add_films">

    @csrf <!-- {{ csrf_field() }} -->

    <div class="form-group">
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="thumbnail_tags" name="thumbnail_tags">
          <label class="custom-file-label @error('thumbnail_tags') form-error @enderror" for="thumbnail_tags" style="text-align: left">Wybierz Zdjęcie</label>
          @error('thumbnail_tags')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
          @enderror
        </div>
      </div>
        
      <div class="form-group">
        <div class="input-group">
          <input type="text" class="form-control @error('tags_name') form-error @enderror" id="tags_name" name="tags_name" placeholder="Nazwa Tagu">
            <span class="input-group-append">
                <button class="btn btn-outline-secondary border-left-0 border" type="button" onclick="document.getElementById('tags_name').value = ''">
                    <i class="fa fa-times"></i>
                </button>
            </span>
          </div>
          @error('tags_name')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
          @enderror        
      </div>
      <!-- <script type="text/javascript">
        $(document).ready(function() {
            $('#thumbnail_tags').on('input', function() {
            var name = document.getElementById("thumbnail_tags").value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, "");
            var change = $("input[name='tags_name']");
            change.val(name);  

            });
        });
        </script> -->

      <script>
        function checked_radio() {

          if($('#hiddendiv').is('.collapse')) {
              document.getElementById("resize_img_1").checked = false;
              document.getElementById("resize_img_2").checked = true;     
          }
            
          if($('#hiddendiv').is('.collapse:not(.show)')) {
            document.getElementById("resize_img_1").checked = true;
            document.getElementById("resize_img_2").checked = false;     
          }


        }
      </script>

      <div class="form-group">           
        <small id="resizelHelp" class="form-text text-muted" style="padding-top: 10px;">Zdjęcia w dobrej jakości zajmują bardzo dużo miejsca na dysku twardym.</small>
        <small id="resizelHelp" class="form-text text-muted">Możemy to zmienić zmniejszając zdjęcia do rozmiarów 350px X 350px wystarzy zaznaczyć "Tak". </small> 
        <small id="resizelHelp" class="form-text text-muted" style="margin-top: 10px; margin-bottom: 30px;">Jeśli nie odpowiadają ci powyższe wymiary możesz zmienić to 
          <a type="button" data-toggle="collapse" data-target="#hiddendiv" aria-expanded="false" aria-controls="collapseExample" style="color:red" onclick="checked_radio()">
            <b>Tutaj</b>
          </a>
        
        </small>  
        <label for="yes_no_radio">Czy chcesz zmiejszyć zdjęcia?</label>

        <div style="padding-bottom: 5px;"></div>

        <input class="form-group" type="radio" name="resize_img" id="resize_img_1" value="1">
          <label for="resize_img_1">Tak</label>
        <input style="margin-left: 20px;" class="form-group" type="radio" name="resize_img" id="resize_img_2" value="2" checked>
          <label for="resize_img_2">Nie</label>

      </div>                    
    
      <div class="collapse container" id="hiddendiv">
        <div class="row justify-content-center">

        <div class="form-group col-sm-3">
          <input type="text" class="form-control" name="height_img" placeholder="Wysokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" >
        </div>

        <div class="form-group col-sm-3">
          <input type="text" class="form-control" name="width_img" placeholder="Szerokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" >
        </div>
        
        </div>
      </div>

      <div style="padding-top: 30px;">

      <div class="form-group">
        <button class="btn btn-success">Wyślij</button>
      </div>

    </form>


    <div style="padding-bottom: 80px; "></div>
</div>



@endsection