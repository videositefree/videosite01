
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Tagami @endsection


@section('content')

@php
    if (isset($stars_db_films)) {
        $context_title = 'Tagi filmów wykorzystywane przez gwiazdy';
        $ajax_url = url('/searchtag_admin_tags_stars_db_film');
        $sort_base = url('/admin_tags_stars_db_film');
        $sort_id_asc = url('/tags_id_asc_admin_db_films_stars');
        $sort_name_asc = url('/tags_name_asc_admin_db_films_stars');
        $sort_name_desc = url('/tags_name_desc_admin_db_films_stars');
    } elseif (isset($studios_db_films)) {
        $context_title = 'Tagi filmów wykorzystywane przez wytwórnie';
        $ajax_url = url('/searchtag_admin_tags_studios_db_film');
        $sort_base = url('/admin_tags_studios_db_film');
        $sort_id_asc = url('/tags_id_asc_admin_db_films_studios');
        $sort_name_asc = url('/tags_name_asc_admin_db_films_studios');
        $sort_name_desc = url('/tags_name_desc_admin_db_films_studios');
    } elseif (isset($sites_db_films)) {
        $context_title = 'Tagi filmów wykorzystywane przez strony';
        $ajax_url = url('/searchtag_admin_tags_sites_db_film');
        $sort_base = url('/admin_tags_sites_db_film');
        $sort_id_asc = url('/tags_id_asc_admin_db_films_sites');
        $sort_name_asc = url('/tags_name_asc_admin_db_films_sites');
        $sort_name_desc = url('/tags_name_desc_admin_db_films_sites');
    }
@endphp

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">{{ $context_title ?? 'Tagi filmów' }}</h1>
        <div class="admin-quicklinks">
            <a class="admin-quicklink" href="{{url('/open_main_folder_tags')}}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Folder tagów
            </a>
            <a class="admin-quicklink" href="{{url('/admin_tags')}}">Tagi filmowe</a>
            <a class="admin-quicklink" href="{{url('/admin_tags_stars_db_film')}}">Tagi gwiazd</a>
            <a class="admin-quicklink" href="{{url('/admin_tags_studios_db_film')}}">Tagi wytwórni</a>
            <a class="admin-quicklink" href="{{url('/admin_tags_sites_db_film')}}">Tagi stron</a>
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
                <a href="{{url('/add_tags')}}" class="btn btn-success">+ Dodaj nowy tag</a>
            </div>
        </div>

        <div class="entity-grid" id="result"></div>

        <script>
        $(document).ready(function(){
            function load_data(query)
            {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url:"{{ $ajax_url }}",
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
                                    <a href="{{ $sort_base }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{ $sort_id_asc }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 45%">
                            <div class="th-sort">Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{ $sort_name_asc }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{ $sort_name_desc }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
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
                            @if(isset($stars_db_films))
                                <a href="{{ url('/select_categories_stars_db_films', $tag->id) }}">
                            @elseif(isset($studios_db_films))
                                <a href="{{ url('/select_categories_studios_db_films', $tag->id) }}">
                            @elseif(isset($sites_db_films))
                                <a href="{{ url('/select_categories_sites_db_films', $tag->id) }}">
                            @endif
                                <b>{{$tag->name}}</b>
                                <div class="film_number">
                                    <i class="fas fa-video"></i>
                                    <?php
                                        if (isset($stars_db_films)) {
                                            $count_films = DB::table('tags')
                                                ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
                                                ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
                                                ->select('stars.*')
                                                ->where('tags.id', $tag->id)
                                                ->where('stars_tags.tag_db', 1)
                                                ->distinct()
                                                ->count();
                                        } elseif (isset($studios_db_films)) {
                                            $count_films = DB::table('tags')
                                                ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
                                                ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
                                                ->select('studios.*')
                                                ->where('tags.id', $tag->id)
                                                ->where('studios_tags.tag_db', 1)
                                                ->distinct()
                                                ->count();
                                        } elseif (isset($sites_db_films)) {
                                            $count_films = DB::table('tags')
                                                ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
                                                ->join('site', 'site.id', '=', 'sites_tags.site_id')
                                                ->select('site.*')
                                                ->where('tags.id', $tag->id)
                                                ->where('sites_tags.tag_db', 1)
                                                ->distinct()
                                                ->count();
                                        }
                                    ?>&nbsp;&nbsp;{{$count_films}}
                                </div>
                            </a>
                        </td>
                        <td>
                            <img src="{{URL::asset("$tag->thumbnail")}}" class="img-fluid img-thumbnail" alt="{{$tag->name}}" style="max-width:70px; border-radius:6px;">
                        </td>
                        <td>
                            <a href="{{url('/edit_tags', $tag->id)}}" class="btn btn-info">Edytuj</a>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_tags', $tag->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak tagów do wyświetlenia.</p>
            <a href="{{url('/add_tags')}}" class="btn btn-success">Dodaj nowy tag</a>
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
                <a class="btn btn-danger" href="{{url('/delete_all_tags')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagi') {{$tags->links()}} @endsection
