
@extends('layouts.admin')




@section('content')
@section('title')VideoSite Zarządzaj @endsection
<script src="{{ asset('js/cut_films.js') }}" defer></script>

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
        Strona testowana na chrome 91 oraz firefox 90. W związku z tym że pewne funkcję są dodawane i usuwane z przeglądarek,
                funkcją kluczową do tworzenia miniatur czy zwiastunów filmów oprócz (FFmpeg) jest konwertowanie czasu z (input type="time") na sekundy.</br>
            
            <div style="padding-top: 30px;;"><h3>Instrukcja użytkowania <a href="instrukcja.pdf" target="_blank">Klik</a></br></h3></div>
<div class="container" style="margin-top: 3%;">

    <div class="row">

        <div class="col-sm-12" >
                    <div class="col-sm-12 text-center" style="padding-bottom: 10px;"><h4> Informację Dodatkowe</h4> </div>
                       
                                           
                        

                Wpisz osobno godzinę, minutę, sekunde i sprawdź czy poprawnie jest konwertowany na sekundy.</br>
                <div class="form-group text-center" style="padding-top: 10px;">
                    <input type="time" name="start" id="start" step="1" value="00:00:00" style="border-radius: 25px; width:15%; text-align:center; margin-right: 20px;">
                    <input name="time_start" value="0" style="border-radius: 25px; width:15%; text-align:center;" disabled>
                    <small id="durationlHelp" class="form-text text-muted">W prawym oknie powinny pojawić się poniższe wartości</br></br>
                    1 h - 3600 sekund</br>
                    1 min - 60 sekund</br>
                    1 sek - 1 sekunda</br></br>
                    w przypadku ustawienia czasu --:--:-- lub ustawieniu jednej z trzech wartości powinno pojawić się 720 sekund </br></br>
                    Jeśli wartości nie są poprawne, tworzenie miniatur oraz filmów nie będzie możliwe</br>
                    </small>
                </div>
                

                <div>1. Wszystkie pliki dodawane do na stronę muszą być w formacie mp4 </div>
                <div>2. Filmy możesz skonwertować na mp4 <a href="{{url('/conversion')}}"> Klik </a> </div>
                <div>3. Miniatury czy zwiastun można dodać z dowolnego programu należy tylko nadać odpowiednie id filmu. 
                Jeśli włączymy konkretny film u góry na pasku adresu pojawi się taki link "http://localhost/videosite/public/watch/1". Wystarczy teraz
                do folderu thumbnail dodać plik o nazwie 1.png ponieważ na końcu linku znajduje się 1 analogicznie do zwiastunu należy tylko wybrać odpowiedni folder. ROZSZERZENIE PLIKU JEST W TYM WYPADKU BARDZO WAŻNE!!!! </div>
                <div>4. Najważniejsze informację znajdują się na podstronach prosimy o uważne analizowanie przypadków.  </div>
                <div>5. Większość pracy której miał na początku wykonywać użytkowanik została zaautomatyzowana.  </div>
                <div style="text-align:center">Miłej Zabawy!</div>

            </div>
        </div>

        

    </div>

    <div class="container" style="margin-top: 12%;"></div>

</div>






@endsection