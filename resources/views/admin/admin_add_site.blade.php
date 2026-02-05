
@extends('layouts.admin')
@section('title')VideoSite Dodaj Stronę @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_sites') }}">Strony</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dodaj Stronę</li>
</ol>
@endsection

@section('content')

<div class="w-100 h-50 text-center" style="padding-bottom: 40px;"><h1> Dodaj Nową Stronę </h1></div>

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

<div class="col-sm-6 text-center offset-sm-3">


    <form action="{{url('/save_site')}}" method="POST" enctype="multipart/form-data" id="add_films">

    @csrf <!-- {{ csrf_field() }} -->

        <div class="form-group">
            <input type="text" class="form-control @error('site_name') form-error @enderror" name="site_name" placeholder="Nazwa">
            @error('site_name')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="url" class="form-control @error('site_link') form-error @enderror" name="site_link" placeholder="Link do strony" value="https://">
            <small id="durationlHelp" class="form-text text-muted">Pamiętaj aby link do strony zawierał https://... W innym wypadku nie będzie możliwości dodania linku!</small>
            @error('site_link')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror
        </div>
            
        <div class="form-group">
            <textarea class="form-control @error('site_description') form-error @enderror" name="site_description" placeholder="Opis"></textarea>
            @error('site_description')
            <div class="alert alert-danger valid_msg">{{ $message }}</div>
            @enderror
        </div>



        <div class="form-group">
            <label for="rating">Ocena Strony:</label><br>
                    <div style="display:inline-block;">
            <div style="display:inline-block;">
                <input type="radio" id="rating" name="rating" value="1" >
                    <label for="rating">1</label>
            </div>
            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="2" >
                    <label for="rating">2</label>
            </div>
            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="3" >
                    <label for="rating">3</label>
            </div>
            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="4" >
                    <label for="rating">4</label>
            </div>
            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="5" >
                    <label for="rating">5</label>
            </div>
            <div style="display:inline-block; margin-left: 10px;">
                <input type="radio" id="rating" name="rating" value="6" >
                    <label for="rating">6</label>
            </div>
        </div>
        @error('rating')
              <div class="alert alert-danger valid_msg">{{ $message }}</div>
        @enderror
        </div>


        <div class="form-group">
                <div class="input-group"> 
                <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj Nowe Tagi" autocomplete="off">
                    <div class="input-group-append">
                    <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                    </div>
                </div>
            </div>


          <div class="form-group">
              <div style="padding: 10px;" id="tagList">

              @if ($errors->any())
              Ze wzgledów bezpieczeństwa formularz został usunięty.</br>
              Zapisaliśmy ostanie 40 tagów które chciałeś dodać:</br></br>
              {{ old('multiTag.0') }}&nbsp
              {{ old('multiTag.1') }}&nbsp
              {{ old('multiTag.2') }}&nbsp
              {{ old('multiTag.3') }}&nbsp
              {{ old('multiTag.4') }}&nbsp
              {{ old('multiTag.5') }}&nbsp
              {{ old('multiTag.6') }}&nbsp
              {{ old('multiTag.7') }}&nbsp
              {{ old('multiTag.8') }}&nbsp
              {{ old('multiTag.9') }}&nbsp
              {{ old('multiTag.10') }}&nbsp
              {{ old('multiTag.11') }}&nbsp
              {{ old('multiTag.12') }}&nbsp
              {{ old('multiTag.13') }}&nbsp
              {{ old('multiTag.14') }}&nbsp
              {{ old('multiTag.15') }}&nbsp
              {{ old('multiTag.16') }}&nbsp
              {{ old('multiTag.17') }}&nbsp
              {{ old('multiTag.18') }}&nbsp
              {{ old('multiTag.19') }}&nbsp
              {{ old('multiTag.20') }}&nbsp
              {{ old('multiTag.21') }}&nbsp
              {{ old('multiTag.22') }}&nbsp
              {{ old('multiTag.23') }}&nbsp
              {{ old('multiTag.24') }}&nbsp
              {{ old('multiTag.25') }}&nbsp
              {{ old('multiTag.26') }}&nbsp
              {{ old('multiTag.27') }}&nbsp
              {{ old('multiTag.28') }}&nbsp
              {{ old('multiTag.29') }}&nbsp
              {{ old('multiTag.30') }}&nbsp
              {{ old('multiTag.31') }}&nbsp
              {{ old('multiTag.32') }}&nbsp
              {{ old('multiTag.33') }}&nbsp
              {{ old('multiTag.34') }}&nbsp
              {{ old('multiTag.35') }}&nbsp
              {{ old('multiTag.36') }}&nbsp
              {{ old('multiTag.37') }}&nbsp
              {{ old('multiTag.38') }}&nbsp
              {{ old('multiTag.39') }}&nbsp
              {{ old('multiTag.40') }}&nbsp</br></br>
              
            @endif

              </div>
          </div>





          <div class="form-group">
            <div class="input-group"> 
            <input type="text" class="form-control ui-autocomplete-input" id="tag_films" placeholder="Dodaj Nowe Tagi Filmów" autocomplete="off">
                <div class="input-group-append">
                <div class="btn btn-success text-white" id="addTagFilms">Dodaj</div>
                </div>
            </div>
        </div>


          <div class="form-group">
              <div style="padding: 10px;" id="tagListFilms">

              @if ($errors->any())
              Ze wzgledów bezpieczeństwa formularz został usunięty.</br>
              Zapisaliśmy ostanie 40 tagów które chciałeś dodać:</br></br>
              {{ old('multiTagFilms.0') }}&nbsp
              {{ old('multiTagFilms.1') }}&nbsp
              {{ old('multiTagFilms.2') }}&nbsp
              {{ old('multiTagFilms.3') }}&nbsp
              {{ old('multiTagFilms.4') }}&nbsp
              {{ old('multiTagFilms.5') }}&nbsp
              {{ old('multiTagFilms.6') }}&nbsp
              {{ old('multiTagFilms.7') }}&nbsp
              {{ old('multiTagFilms.8') }}&nbsp
              {{ old('multiTagFilms.9') }}&nbsp
              {{ old('multiTagFilms.10') }}&nbsp
              {{ old('multiTagFilms.11') }}&nbsp
              {{ old('multiTagFilms.12') }}&nbsp
              {{ old('multiTagFilms.13') }}&nbsp
              {{ old('multiTagFilms.14') }}&nbsp
              {{ old('multiTagFilms.15') }}&nbsp
              {{ old('multiTagFilms.16') }}&nbsp
              {{ old('multiTagFilms.17') }}&nbsp
              {{ old('multiTagFilms.18') }}&nbsp
              {{ old('multiTagFilms.19') }}&nbsp
              {{ old('multiTagFilms.20') }}&nbsp
              {{ old('multiTagFilms.21') }}&nbsp
              {{ old('multiTagFilms.22') }}&nbsp
              {{ old('multiTagFilms.23') }}&nbsp
              {{ old('multiTagFilms.24') }}&nbsp
              {{ old('multiTagFilms.25') }}&nbsp
              {{ old('multiTagFilms.26') }}&nbsp
              {{ old('multiTagFilms.27') }}&nbsp
              {{ old('multiTagFilms.28') }}&nbsp
              {{ old('multiTagFilms.29') }}&nbsp
              {{ old('multiTagFilms.30') }}&nbsp
              {{ old('multiTagFilms.31') }}&nbsp
              {{ old('multiTagFilms.32') }}&nbsp
              {{ old('multiTagFilms.33') }}&nbsp
              {{ old('multiTagFilms.34') }}&nbsp
              {{ old('multiTagFilms.35') }}&nbsp
              {{ old('multiTagFilms.36') }}&nbsp
              {{ old('multiTagFilms.37') }}&nbsp
              {{ old('multiTagFilms.38') }}&nbsp
              {{ old('multiTagFilms.39') }}&nbsp
              {{ old('multiTagFilms.40') }}&nbsp</br></br>
              
            @endif

              </div>
          </div>



                
        <!---- display tags autocomplete --->

        <script>
        $(function() {
        $('#tag').autocomplete({
            source: "{{url('/gettag_sites')}}",
            minLength: 1,
            scroll:true,
            select: function(event, ui)
            {
                $('#tag').val(ui.item.value);
                
            }
            }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
            };
        });
            
        var id = 0;
        $("#addTag").click(function(){
            if($("#tag").val() ) {
                
                    id++;
                var li = document.createElement("li");
                    li.className = "tags";
                    li.setAttribute("id", id);
                
                var i = document.createElement("INPUT"); 
                    i.setAttribute("name","multiTag[]");
                    i.setAttribute("type","hidden");
                    i.setAttribute("id", id);
                
                var tag = document.getElementById('tag').value;
                    
                li.innerHTML = " " + tag + '  <button class=\"deleteTag btn-delete\" id=\"'+id+'\">X</button>' 
                i.setAttribute("value", tag);    
                
                $("#tagList").append(li)
                $("#tagList").append(i)
                $('#tag').val('');  
            }});

        $("#tagList").on('click', 'button.deleteTag', function() {
        
            var idDiv = this.id;
            $("#"+idDiv).remove()
            $(":input[id='"+idDiv+"']").remove();

        });

        $("#tagList").on('click', 'button.deleteTagExsit', function() {
        
            var del_id = this.id;
            var toDel = del_id.replace('id_', '');
            $("#id_"+toDel).remove();

        });




        $(function() {
        $('#tag_films').autocomplete({
            source: "{{url('/gettag')}}",
            minLength: 1,
            scroll:true,
            select: function(event, ui)
            {
                $('#tag_films').val(ui.item.value);
                
            }
            }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li class='"+item.disabled+"'><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
            };
        });
            
        var id = 0;
        $("#addTagFilms").click(function(){
            if($("#tag_films").val() ) {
                
                    id++;
                var li = document.createElement("li");
                    li.className = "tags";
                    li.setAttribute("id", id);
                
                var i = document.createElement("INPUT"); 
                    i.setAttribute("name","multiTagFilms[]");
                    i.setAttribute("type","hidden");
                    i.setAttribute("id", id);
                
                var tag = document.getElementById('tag_films').value;
                    
                li.innerHTML = " " + tag + '  <button class=\"deleteTag btn-delete\" id=\"'+id+'\">X</button>' 
                i.setAttribute("value", tag);    
                
                $("#tagListFilms").append(li)
                $("#tagListFilms").append(i)
                $('#tag_films').val('');  
            }});

        $("#tagListFilms").on('click', 'button.deleteTag', function() {
        
            var idDiv = this.id;
            $("#"+idDiv).remove()
            $(":input[id='"+idDiv+"']").remove();

        });

        $("#tagListFilms").on('click', 'button.deleteTagExsit', function() {
        
            var del_id = this.id;
            var toDel = del_id.replace('id_', '');
            $("#id_"+toDel).remove();

        });

        </script>
                
       
    

                    <div class="form-group">
                      <button class="btn btn-success">Wyślij</button>
                    </div>

    </form>

<div style="padding-top: 80px;"></div>

</div>



@endsection