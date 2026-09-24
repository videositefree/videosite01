
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Gwiazdami @endsection


@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">Gwiazdy</h1>
        <div class="admin-quicklinks">
            <a href="{{url('/open_main_folder_stars')}}" class="admin-quicklink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Gwiazdy
            </a>
        </div>
    </div>

    @if (\Session::has('success'))
    <div class="alert alert-success"><ul>{!! \Session::get('success') !!}</ul></div>
    @endif
    @if (\Session::has('errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('errors') !!}</ul></div>
    @endif

    @if( $count_stars > 0 )

        <div class="admin-toolbar">
            <div class="admin-toolbar__search">
                <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj…">
            </div>
            <div class="admin-toolbar__actions">
                <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń wszystkie</button>
                <a href="{{url('/add_stars')}}" class="btn btn-success">+ Dodaj nową gwiazdę</a>
            </div>
        </div>

        <div class="entity-grid" id="result"></div>

        <script>
        $(document).ready(function(){
            function load_data(query)
            {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url:"{{url('/searchstar_admin')}}",
                    method:"post",
                    data:{query:query},
                    success:function(data){ $('#result').html(data); }
                });
            }
            $('#search_tag').keyup(function(){
                var search = $(this).val();
                if(search != '') { load_data(search); }
            });
        });
        </script>

        <div class="table-responsive-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 8%">
                            <div class="th-sort">#
                                <span class="th-sort__arrows">
                                    <a href="{{url('/admin_stars')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/stars_id_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 32%">
                            <div class="th-sort">Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{url('/stars_name_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/stars_name_desc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 15%">
                            <div class="th-sort">Płeć
                                <span class="th-sort__arrows">
                                    <a href="{{url('/stars_gender_male_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/stars_gender_female_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 12%">
                            <div class="th-sort">Ocena
                                <span class="th-sort__arrows">
                                    <a href="{{url('/stars_rating_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/stars_rating_desc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 10%">Zdjęcie</th>
                        <th scope="col" style="width: 23%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($stars as $key => $star)
                    <tr>
                        <td scope="row">{{$stars->firstItem() + $key}}</td>
                        <td class="table_site">
                            <a href="{{ url('/select_stars', $star->id) }}">
                                <b>{{$star->name}}</b>
                                <div class="film_number">
                                    <i class="fas fa-video"></i> <?php

                                        $count_films = DB::table('stars')
                                        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
                                        ->join('films', 'films.id', '=', 'films_stars.film_id')
                                        ->orderBy('name', 'ASC')
                                        ->select('films.*')
                                        ->where('stars.id', $star->id)
                                        ->where('activ', '=', '1')
                                        ->distinct()
                                        ->count();

                                    ?>&nbsp;&nbsp;{{$count_films}}
                                </div>
                            </a>
                        </td>
                        <td>
                            @if($star->sex == "male") Mężczyzna @endif
                            @if($star->sex == "female") Kobieta @endif
                        </td>
                        <td>{{"$star->rating"}}</td>
                        <td>
                            <img src="{{URL::asset("$star->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$star->name}}" style="max-width:70px; border-radius:6px;">
                        </td>
                        <td>
                            <a href="{{url('/edit_stars', $star->id)}}" class="btn btn-info">Edytuj</a>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_stars', $star->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak gwiazd do wyświetlenia.</p>
            <a href="{{url('/add_stars')}}" class="btn btn-success">Dodaj nową gwiazdę</a>
        </div>

    @endif

</div>


<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-body" style="font-size:17px; text-align: center">
                Czy jesteś pewien że chcesz usunąć WSZYSTKIE gwiazdy?
                </br></br>
                Kategorie, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{url('/delete_all_stars')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('pagi') {{$stars->links()}} @endsection
