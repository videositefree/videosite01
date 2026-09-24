
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Wytwórniami @endsection


@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">Wytwórnie filmowe</h1>
        <div class="admin-quicklinks">
            <a href="{{url('/open_main_folder_studios')}}" class="admin-quicklink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Wytwórnie
            </a>
        </div>
    </div>

    @if (\Session::has('success'))
    <div class="alert alert-success"><ul>{!! \Session::get('success') !!}</ul></div>
    @endif
    @if (\Session::has('errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('errors') !!}</ul></div>
    @endif

    @if( $count_studios > 0 )

        <div class="admin-toolbar">
            <div class="admin-toolbar__search">
                <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj…">
            </div>
            <div class="admin-toolbar__actions">
                <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń wszystkie</button>
                <a href="{{url('/add_studios')}}" class="btn btn-success">+ Dodaj nową wytwórnię</a>
            </div>
        </div>

        <div class="entity-grid" id="result"></div>

        <script>
        $(document).ready(function(){
            function load_data(query)
            {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url:"{{url('/searchstudios_admin')}}",
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
                                    <a href="{{url('/admin_studios')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/studios_id_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 40%">
                            <div class="th-sort">Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{url('/studios_name_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/studios_name_desc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 15%">
                            <div class="th-sort">Ocena
                                <span class="th-sort__arrows">
                                    <a href="{{url('/studios_rating_asc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/studios_rating_desc_admin')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 12%">Zdjęcie</th>
                        <th scope="col" style="width: 25%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($studios as $key => $studio)
                    <tr>
                        <th scope="row">{{$studios->firstItem() + $key}}</th>
                        <td class="table_site">
                            <a href="{{ url('/select_studios', $studio->id) }}">
                                <b>{{$studio->name}}</b>
                                <div class="film_number">
                                    <i class="fas fa-video"></i> <?php

                                    $count_films = DB::table('studios')
                                    ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
                                    ->join('films', 'films.id', '=', 'films_studios.film_id')
                                    ->orderBy('name', 'ASC')
                                    ->select('films.*')
                                    ->where('studios.id', $studio->id)
                                    ->where('activ', '=', '1')
                                    ->distinct()
                                    ->count();

                                    ?>&nbsp;&nbsp;{{$count_films}}
                                </div>
                            </a>
                        </td>
                        <td>{{$studio->rating}}</td>
                        <td>
                            <img src="{{URL::asset("$studio->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$studio->name}}" style="max-width:70px; border-radius:6px;">
                        </td>
                        <td>
                            <a href="{{url('/edit_studios', $studio->id)}}" class="btn btn-info">Edytuj</a>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_studios', $studio->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak wytwórni do wyświetlenia.</p>
            <a href="{{url('/add_studios')}}" class="btn btn-success">Dodaj nową wytwórnię</a>
        </div>

    @endif

</div>


<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-body" style="font-size:17px; text-align: center">
                Czy jesteś pewien że chcesz usunąć WSZYSTKIE wytwórnie?
                </br></br>
                Wytwórnie, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br>
                <b>Pamiętaj że decyzji nie można cofnąć</b>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{url('/delete_all_studios')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('pagi') {{$studios->links()}} @endsection
