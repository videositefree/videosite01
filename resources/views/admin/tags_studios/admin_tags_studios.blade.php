
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Tagami @endsection


@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">Tagi wytwórni</h1>
        <div class="admin-quicklinks">
            <a class="admin-quicklink" href="{{url('/open_main_folder_tags_studios')}}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Folder tagów
            </a>
        </div>
    </div>

    @if( $count_tags > 0 )

        @if (\Session::has('success'))
        <div class="alert alert-success"><ul>{!! \Session::get('success') !!}</ul></div>
        @endif
        @if (\Session::has('errors'))
        <div class="alert alert-danger"><ul>{!! \Session::get('errors') !!}</ul></div>
        @endif

        <div class="admin-toolbar">
            <div class="admin-toolbar__search">
                <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj…">
            </div>
            <div class="admin-toolbar__actions">
                <button class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń wszystkie</button>
                <a href="{{url('/add_tags_studios')}}" class="btn btn-success">+ Dodaj nowy tag</a>
            </div>
        </div>

        <div class="entity-grid" id="result"></div>

        <script>
        $(document).ready(function(){
            function load_data(query)
            {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url:"{{url('/searchtag_studios_admin')}}",
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
                                    <a href="{{url('/admin_tags_studios')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/tags_id_asc_admin_studios_studios')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 45%">
                            <div class="th-sort">Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{url('/tags_name_asc_admin_studios')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/tags_name_desc_admin_studios')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 20%">Zdjęcie</th>
                        <th scope="col" style="width: 27%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tags as $key => $tag)
                    <tr>
                        <th scope="row">{{$tags->firstItem() + $key}}</th>
                        <td class="table_site">
                            <a href="{{ url('/select_categories_studios', $tag->id) }}">
                                <b>{{$tag->name}}</b>
                                <div class="film_number">
                                    <i class="fas fa-tag"></i> <?php

                                    $count_films = DB::table('tags_studios')
                                    ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags_studios.id')
                                    ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
                                    ->orderBy('name', 'ASC')
                                    ->select('studios.*')
                                    ->where('tags_studios.id', $tag->id)
                                    ->where('studios_tags.tag_db', 0)
                                    ->distinct()
                                    ->count();

                                    ?>&nbsp;&nbsp;{{$count_films}}
                                </div>
                            </a>
                        </td>
                        <td>
                            <img src="{{URL::asset("$tag->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$tag->name}}" style="max-width:70px; border-radius:6px;">
                        </td>
                        <td>
                            <a href="{{url('/edit_tags_studios', $tag->id)}}" class="btn btn-info">Edytuj</a>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_tags_studios', $tag->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak tagów do wyświetlenia.</p>
            <a href="{{url('/add_tags_studios')}}" class="btn btn-success">Dodaj nowy tag</a>
        </div>

    @endif

</div>


<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-body" style="font-size:17px; text-align: center">
                Czy jesteś pewien że chcesz usunąć WSZYSTKIE tagi?
                </br></br>
                Kategorie, zdjęcia oraz wszystkie elementy z nimi powiązane zostaną permanentnie usunięte z dysku twardego oraz bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć</b>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{url('/delete_all_tags_studios')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagi') {{$tags->links()}} @endsection
