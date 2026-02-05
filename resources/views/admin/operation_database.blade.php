
@extends('layouts.admin')




@section('content')
@section('title')VideoSite Zarządzaj @endsection

<button onclick="topFunction()" id="myBtn">Top</button> 

    <!-- Mesage return when backup   --> 
    <div class="col-sm-12 text-center" style="padding-top: 15px;">
    
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

<div class="container" style="margin-top: 6%;">
    <div class="row">

        <div class="col-sm-5 offset-sm-1 site_info mr-1">

            <div class="col-sm-12 operation_link">

                <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIKI </a>

                    <div class="operation_text">
                        <div>   
                            filmy.txt - ID, Ocena, Nazwa, Tagi, Gwiazdy, Wytwórnie 
                        </div>
                        
                        <div style="padding-top: 5px;">   
                            tagi.txt - Nazwa Tagu.
                        </div> 
                        <div style="padding-top: 5px;">   
                            gwiazdy.txt - Imię i Nazwisko.
                        </div> 
                        <div style="padding-top: 5px;">   
                            wytwornie.txt - Nazwa Wytwórni. 
                        </div> 
                        <div style="padding-top: 5px;">   
                            strony.txt - Nazwa, Link, Opis.  
                        </div>  
                    

                    </div>
                    
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_special_txt')}}" class="btn btn-success "> Wykonaj </a>
                    </div>                
                </div>


            
                <div class="col-sm-12 operation_link">
                <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIK </a>

                    <div class="operation_text"> 
                        <div>   
                            filmy.txt - ID, Ocena, Nazwa, Tagi, Gwiazdy, Wytwórnie 
                        </div> 
                    </div>

                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_films_txt')}}" class="btn btn-success "> Wykonaj </a>
                    </div>
                </div>



                
                <div class="col-sm-12 operation_link">
                    <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIK </a>
                    <div class="operation_text">
                        <div style="padding-top: 5px;">   
                            tagi.txt - Nazwa Tagu.
                        </div> 
                    </div>
                        
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_tags_txt')}}" class="btn btn-success"> Wykonaj </a>
                    </div>
                </div>




                <div class="col-sm-12 operation_link">
                <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIK </a>
                    <div class="operation_text">
                        <div>   
                            gwiazdy.txt - Imię i Nazwisko.
                        </div>                 
                    </div>

                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_stars_txt')}}" class="btn btn-success"> Wykonaj </a>
                    </div>
                </div>


                <div class="col-sm-12 operation_link">
                    <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIK </a>
                    <div class="operation_text">
                        <div>   
                            wytwornie.txt - Nazwa Wytwórni. 
                        </div> 
                    </div>

                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_studios_txt')}}" class="btn btn-success"> Wykonaj </a>
                    </div>
                </div>


                <div class="col-sm-12 operation_link">
                    <a class="btn btn-outline-primary fas fa-folder-open fa-film" href="{{url('/open_main_folder_out_videosite')}}" style="margin-top: 25px;"> PLIK </a>
                    <div class="operation_text"> 
                        <div>   
                            strony.txt - Nazwa, Link, Opis.  
                        </div>
                    </div>

                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        <a href="{{url('/create_site_txt')}}" class="btn btn-success"> Wykonaj </a>
                    </div>
                </div>

            </div>

            

            <div class="col-sm-5 justify-content-center text-center site_info ml-1">


                <div class="col-sm-12 operation_link">
                    <div class="operation_text">
                        Zamień tytuły filmów w folderach </br></br>
                        <b> Status:</b>
                        @if( $films_status == 2 ) Brak filmów w bazie danych.@endif 
                        @if( $films_status == 0 ) Nazwa filmów z bazy danych. </br> (Brak możliwości wyświetlenia filmów na stronie)@endif 
                        @if( $films_status == 1 )  Nazwa filmów poprawna. </br> (Wyświetlanie filmów na stronie możliwe)@endif 
                        </br></br>
                    </div>

                    @if( $films_status == 0 OR $films_status == 1 )
                    Naciśnij Nazwa aby filmy w folderze zmieniły się na tytuły z bazy danych.</br></br>
                    <a href="{{url('/change_film_name')}}" class="btn btn-success "> Nazwa</a>
                    <a href="{{url('/change_film_name_md5')}}" class="btn btn-success "> MD5</a></br></br>
                    Naciśnij MD5 aby poprawnie wyświetlać filmy na stronie.
                    @endif
                </div>




                <div class="col-sm-12 operation_link">
                    <div class="operation_text">
                        Zamień tytuły tagów w folderach </br></br>
                        <b> Status:</b>
                        @if( $tags_status == 2 ) Brak tagów w bazie danych.@endif 
                        @if( $tags_status == 0 ) Nazwa tagów z bazy danych. </br> (Brak możliwości wyświetlenia tagów na stronie)@endif 
                        @if( $tags_status == 1 )  Nazwa tagów poprawna. </br> (Wyświetlanie tagów na stronie możliwe)@endif 
                        </br></br>
                    </div> 

                    @if( $tags_status == 0 OR $tags_status == 1 )
                    Naciśnij Nazwa aby tagi w folderze zmieniły się na tytuły z bazy danych.</br></br>
                    <a href="{{url('/change_tags_name')}}" class="btn btn-success "> Nazwa </a>
                    <a href="{{url('/change_tags_name_md5')}}" class="btn btn-success "> MD5</a></br></br>
                    Naciśnij MD5 aby poprawnie wyświetlać tagów na stronie.
                    @endif
                </div>    



                

                <div class="col-sm-12 operation_link">
                    <div class="operation_text">
                        Zamień tytuły gwiazd w folderach </br></br>
                        <b> Status:</b>
                        @if( $stars_status == 2 ) Brak gwiazd w bazie danych.@endif 
                        @if( $stars_status == 0 ) Nazwa gwiazd z bazy danych. </br> (Brak możliwości wyświetlenia gwiazd na stronie)@endif 
                        @if( $stars_status == 1 )  Nazwa gwiazd poprawna. </br> (Wyświetlanie gwiazd na stronie możliwe)@endif 
                        </br></br>
                    </div>
                        
                    @if( $stars_status == 0 OR $stars_status == 1 )
                    Naciśnij Nazwa aby gwiazdy w folderze zmieniły się na tytuły z bazy danych.</br></br>
                    <a href="{{url('/change_stars_name')}}" class="btn btn-success "> Nazwa </a>
                    <a href="{{url('/change_stars_name_md5')}}" class="btn btn-success "> MD5</a></br></br>
                    Naciśnij MD5 aby poprawnie wyświetlać gwiazdy na stronie.
                    @endif
                </div>




                <div class="col-sm-12 operation_link">
                    <div class="operation_text">
                        Zamień tytuły wytwórni w folderach </br></br>
                        <b> Status:</b>
                        @if( $studios_status == 2 ) Brak wytwórni w bazie danych @endif 
                        @if( $studios_status == 0 ) Nazwa wytwórni z bazy danych. </br> (Brak możliwości wyświetlenia wytwórni na stronie)@endif 
                        @if( $studios_status == 1 )  Nazwa wytwórni poprawna. </br> (Wyświetlanie wytwórni na stronie możliwe)@endif 
                        </br></br>
                    </div>

                    @if( $studios_status == 0 OR $studios_status == 1)
                    Naciśnij Nazwa aby wytwórnie w folderze zmieniły się na tytuły z bazy danych.</br></br>
                    <a href="{{url('/change_studios_name')}}" class="btn btn-success "> Nazwa </a>
                    <a href="{{url('/change_studios_name_md5')}}" class="btn btn-success "> MD5</a></br></br>
                    Naciśnij MD5 aby poprawnie wyświetlać wytwórni na stronie.
                    @endif 
                </div>



                
                <div class="col-sm-12 operation_link">
                    <div class="operation_text">
                        Zamień tytuły miniatur filmów w folderach </br></br>
                        <b> Status:</b>
                        @if( $thumbnail_status == 2 ) Brak miniatur filmów w folderze filmy/thumbnail.@endif 
                        @if( $thumbnail_status == 0 ) Nazwa miniatur z bazy danych. </br> (Brak możliwości wyświetlenia miniatur na stronie)@endif 
                        @if( $thumbnail_status == 1 )  Nazwa miniatur poprawna. </br> (Wyświetlanie miniatur na stronie możliwe)@endif 
                        </br></br>
                    </div>

                    @if( $thumbnail_status == 0 OR $thumbnail_status == 1 )
                    Naciśnij Nazwa aby miniatury w folderze zmieniły się na tytuły z bazy danych.</br></br>
                    <a href="{{url('/change_thumbnail_name')}}" class="btn btn-success "> Nazwa </a>
                    <a href="{{url('/change_thumbnail_name_md5')}}" class="btn btn-success "> MD5</a></br></br>
                    Naciśnij MD5 aby poprawnie wyświetlać miniatur na stronie.
                    @endif
                </div> 

    

            </div>


        </div>

        
     

    </div>

    <div style="padding-top: 100px;"></div>

</div>







  





@endsection