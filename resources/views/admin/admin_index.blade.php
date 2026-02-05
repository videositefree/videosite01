
@extends('layouts.admin')




@section('content')
@section('title')VideoSite Zarządzaj @endsection

<button onclick="topFunction()" id="myBtn">Top</button> 

<div class="col-sm-12 text-center" style="padding-top: 15px;">
        @if( $films_status != 0 or $tags_status != 0 or $stars_status != 0 or $studios_status != 0 or $thumbnail_status != 0 )
        <div>
                <p style="color: red;">
                    
                    @if( $films_status == 0 )<b> Status Filmów:</b> <b>Brak filmów w bazie danych.</b></br>
                    <!-- else <b> Status Filmów:</b> Nazwa filmów poprawna. (Wyświetlanie filmów na stronie możliwe)</br> -->
                    @endif 
                    @if( $films_status == 2 )<b> Status Filmów:</b> Nazwa filmów z bazy danych. (Brak możliwości wyświetlenia filmów na stronie) <a href="{{url('/change_film_name_md5')}}" > Popraw</a></br>
                    @endif
                    @if( $films_status == 3 )<b> Status Tagów: Folder z filami jest pusty.</br>@endif
                    @if( $films_status == 4 )<b> Status Tagów: Problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</br>@endif



                    @if( $tags_status == 0 )<b> Status Tagów:</b> Brak tagów w bazie danych.</br>
                    <!-- else <b> Status Tagów:</b> Nazwa tagów poprawna. (Wyświetlanie tagów na stronie możliwe)</br> -->
                    @endif 
                    @if( $tags_status == 2 )<b> Status Tagów:</b> Nazwa tagów z bazy danych. (Brak możliwości wyświetlenia tagów na stronie)<a href="{{url('/change_tags_name_md5')}}" > Popraw</a></br>
                    @endif 
                    @if( $tags_status == 3 )<b> Status Tagów: Folder z tagami jest pusty.</br>@endif
                    @if( $tags_status == 4 )<b> Status Tagów: Problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</br>@endif
                    


                    @if( $stars_status == 0 )</b> Brak gwiazd w bazie danych.</br>
                    <!-- else <b> Status Gwiazd:</b> Nazwa gwiazd poprawna. (Wyświetlanie gwiazd na stronie możliwe)</br> -->
                    @endif 
                    @if( $stars_status == 2 )<b> Status Gwiazd:<b> Nazwa gwiazd z bazy danych. (Brak możliwości wyświetlenia gwiazd na stronie)<a href="{{url('/change_stars_name_md5')}}" > Popraw</a></br>
                    @endif
                    @if( $stars_status == 3 )<b> Status Gwiazd: Folder z gwiazdami jest pusty.</br>@endif
                    @if( $stars_status == 4 )<b> Status Tagów: Problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</br>@endif
                    


                    @if( $studios_status == 0 )<b> Status Wytwórni:</b> Brak wytwórni w bazie danych.</br>
                    <!-- else <b> Status Wytwórni:</b> Nazwa wytwórni poprawna. (Wyświetlanie wytwórni na stronie możliwe)</br> -->
                    @endif
                    @if( $studios_status == 2 )<b> Status Wytwórni:</b> Nazwa wytwórni z bazy danych. (Brak możliwości wyświetlenia wytwórni na stronie)<a href="{{url('/change_studios_name_md5')}}" > Popraw</a></br>
                    @endif
                    @if( $studios_status == 3 )<b> Status Wytwórni: Folder z wytwórniami jest pusty.</br>@endif
                    @if( $studios_status == 4 )<b> Status Tagów: Problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</br>@endif
                    

                    
                    @if( $thumbnail_status == 0 )<b> Status Miniatur:</b> Brak miniatur w bazie danych.</br></b>
                    <!-- else <b> Status:</b> Nazwa miniatur poprawna. (Wyświetlanie miniatur na stronie możliwe)</br> -->
                    @endif 
                    @if( $thumbnail_status == 2 )<b> Status Miniatur: Nazwa miniatur z bazy danych. (Brak możliwości wyświetlenia miniatur na stronie)<a href="{{url('/Change_thumbnail_name_md5')}}" > Popraw</a></br>
                    @endif
                    @if( $thumbnail_status == 3 )<b> Status Miniatur: Folder z miniaturami jest pusty.</br>@endif
                    @if( $thumbnail_status == 4 )<b> Status Tagów: Problem z identyfikacją błędu. Proszę skontaktować się z administratorem.</br>@endif
                

                </p>
            </div>
            @endif
            <div style="line-height: 3;">
            Zapoznaj się z instrukcją obsługi strony <a href="{{url('/admin_help')}}"> Klik</a></br>
            Chcesz aby filmy miały orginalny tytuł w folderze filmy zamiast zakodowanej nazwy? <a href="{{url('/operation_database')}}">Kliknij Tutaj</a></br></br>
            
            </div>
       

        <div class="container" style="margin-top: 1%;">

    <div class="row">

        <div class="col-sm-5 offset-sm-1 mr-1 text-left">
            <div style="margin-top: 40px;"></div>
            <div class="col-sm-12 text-center"><h2> Statystyki</h2> </div>
            <div class="col-sm-12 text-center"><b>Rozmiar folderu filmy: {{$bytes}}</br></br> </b>
            <b>Całkowity Czas</b></br>
            {{$full_duration}} Sekund <br><br>
            {{$full_duration_minutes_only}} Minut {{$full_duration_seconds}} Sekund <br><br>
            {{$full_duration_hours_only}} Godzin {{$full_duration_minutes}} Minut {{$full_duration_seconds}} Sekund <br><br>
            {{$full_duration_day}} Dni {{$full_duration_hours}} Godzin {{$full_duration_minutes}} Minut {{$full_duration_seconds}} Sekund 


        </div><br>
            
            <div class=" col-sm-12 text-center" style="line-height: 1.8;">
                <div class="row">
                    
                    <div class="col-sm-4 ">
                        <b>Wszystkie filmy:</b>&nbsp; <a href="{{url('/admin_films') }}" >{{$films}}</a> <br><br>
                        <b>włączone:</b> &nbsp;<a href="{{url('/films_on_desc') }}" >{{$f_on}}</a><br><br>
                        <b>wyłączone:</b> &nbsp;<a href="{{url('/films_off_desc') }}" >{{$f_off}}</a><br><br>
                    </div>

                    <div class="col-sm-4 ">
                        <b>Gwiazdy:</b> &nbsp;<a href="{{url('/admin_stars') }}" >{{$stars}}</a><br><br>
                        <b>Wytwórnie:</b> &nbsp;<a href="{{url('/admin_studios') }}" >{{$studios}}</a><br><br>
                        <b>Strony Internetowe:</b> &nbsp;<a href="{{url('/admin_sites') }}" >{{$site}}</a><br><br>


                        <div style="margin-top: 20px;"></div>
                    </div>

                    <div class="col-sm-4 ">
                        <b>Tagi Filmów:</b> &nbsp;<a href="{{url('/admin_tags') }}" >{{$tags}}</a><br><br>
                        <b>Tagi Gwiazd:</b> &nbsp;<a href="{{url('/admin_tags_stars') }}" >{{$tags_stars}}</a><br><br>
                        <b>Tagi Wytwórnii:</b> &nbsp;<a href="{{url('/admin_tags_studios') }}" >{{$tags_studios}}</a><br><br>
                        <b>Tagi Stron:</b> &nbsp;<a href="{{url('/admin_tags_sites') }}" >{{$tags_sites}}</a><br><br>


                        <div style="margin-top: 20px;"></div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-sm-5 justify-content-center text-center site_info ml-1">
            <div style="margin-top: 40px;"></div>
        <div class="col-sm-12 text-center"><h2> Zarządzaj </h2> </div>
            <div style="margin-top: 20px;"></div>
           
            Wykonaj Kopie zapasową bazy danych.</br>           <div style="padding-bottom: 5px;"></div>      
                <a href="{{url('/copy_db')}}" class="btn btn-success fas fa-database"> Backup Database</a></br></br> 
            
            Wykonaj Kopie zapasową folderu thumbnail.</br>   <div style="padding-bottom: 5px;"></div>          
                <a href="{{url('/copy_folder')}}" class="btn btn-success fas fa-database"> Backup Folder</a></br> 
             
            <div style="margin-top: 20px;"></div>

       

     
            

        </div>

    </div>

    <div class="container" style="margin-top: 12%;"></div>

</div>







  





@endsection