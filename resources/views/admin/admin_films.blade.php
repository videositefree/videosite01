
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Filmami @endsection


@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">Filmy</h1>
        <div class="admin-quicklinks">
            <a href="{{url('/open_main_folder_film')}}" class="admin-quicklink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Filmy
            </a>
            <a href="{{url('/open_main_folder_thumbnail')}}" class="admin-quicklink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Zdjęcia
            </a>
            <a href="{{url('/open_main_folder_short')}}" class="admin-quicklink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Short
            </a>
        </div>
    </div>

    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>{!! \Session::get('success') !!}</ul>
    </div>
    @endif

    @if (\Session::has('errors'))
    <div class="alert alert-danger">
        <ul>{!! \Session::get('errors') !!}</ul>
    </div>
    @endif

    @if( $count_films > 0 )

        <div class="admin-toolbar">
            <div class="admin-toolbar__search">
                <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj po id, tytule, nazwie pliku…">
            </div>
            <div class="admin-toolbar__actions">
                <a class="btn btn-danger" href="{{url('/admin_films_off')}}">Wyłącz wszystkie</a>
                <a class="btn btn-success" href="{{url('/admin_films_on')}}">Włącz wszystkie</a>
                <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń wszystkie</button>
                <a href="{{url('/add_films')}}" class="btn btn-success">+ Dodaj nowy film</a>
            </div>
        </div>

        <div class="admin-search-grid" id="result"></div>

        <script>
        $(document).ready(function(){

            function load_data(query)
            {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    url:"{{url('/searchfilms_admin')}}",
                    method:"post",
                    data:{query:query},
                    success:function(data)
                    {
                        $('#result').html(data);
                    }
                });
            }

            $('#search_tag').keyup(function(){
                var search = $(this).val();
                if(search != '')
                {
                    load_data(search);
                }
            });
        });
        </script>

        <div class="table-responsive-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 10%">
                            <div class="th-sort">
                                #
                                <span class="th-sort__arrows">
                                    <a href="{{url('/admin_films')}}" title="Rosnąco"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('films_id_asc')}}" title="Malejąco"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>

                        <th scope="col" style="width: 40%">
                            <div class="th-sort">
                                Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{url('/films_name_asc')}}" title="A–Z"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/films_name_desc')}}" title="Z–A"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>

                        <th scope="col" style="width: 15%">
                            <div class="th-sort">
                                Status
                                <span class="th-sort__arrows">
                                    <a href="{{url('/films_on_desc')}}" title="Aktywne"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/films_off_desc')}}" title="Wyłączone"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>

                        <th scope="col" style="width: 15%">
                            <div class="th-sort">
                                Ocena
                                <span class="th-sort__arrows">
                                    <a href="{{url('/films_rating_asc')}}" title="Rosnąco"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/films_rating_desc')}}" title="Malejąco"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>

                        <th scope="col" style="width: 20%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($films as $key => $film)
                    <tr>
                        <th scope="row">{{$films->firstItem() + $key}}</th>
                        <td class="table_site"><b><a href="{{url('/edit_films', $film->id)}}">{{$film->name}}</a></b></td>
                        <td>
                            @if($film->activ == '0')
                                <span class="status-pill status-pill--off">Wyłączony</span>
                            @else
                                <span class="status-pill status-pill--on">Aktywny</span>
                            @endif
                        </td>
                        <td>{{$film->rating}}</td>
                        <td>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_films', $film->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak filmów do wyświetlenia.</p>
            <a href="{{url('/add_films')}}" class="btn btn-success">Dodaj nowy film</a>
        </div>

    @endif

</div>


<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-body" style="font-size:17px; text-align: center">
                Czy jesteś pewien że chcesz usunąć WSZYSTKIE filmy?
                </br></br>
                Film, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{url('/delete_all_films')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagi') {{$films->links()}} @endsection
