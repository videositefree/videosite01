
@extends('layouts.admin')

@section('title')VideoSite Zarządzaj Stronami @endsection


@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <h1 class="admin-page__title">Strony</h1>
    </div>

    @if (\Session::has('success'))
    <div class="alert alert-success"><ul>{!! \Session::get('success') !!}</ul></div>
    @endif
    @if (\Session::has('errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('errors') !!}</ul></div>
    @endif

    @if( $count_sites > 0 )

        <div class="admin-toolbar">
            <div class="admin-toolbar__search">
                <input type="text" name="search_tag" id="search_tag" class="form-control" placeholder="Szukaj…">
            </div>
            <div class="admin-toolbar__actions">
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete_all">Usuń wszystkie</a>
                <a href="{{url('/add_site')}}" class="btn btn-success">+ Dodaj nową stronę</a>
            </div>
        </div>

        <div class="entity-grid" id="result"></div>

        <script>
        $(document).ready(function(){
            function load_data(query)
            {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    url:"{{url('/searchsite_admin')}}",
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
                                    <a href="{{url('/admin_sites')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/site_id_asc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 25%">
                            <div class="th-sort">Nazwa
                                <span class="th-sort__arrows">
                                    <a href="{{url('/site_name_asc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/site_name_desc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 35%">
                            <div class="th-sort">Opis
                                <span class="th-sort__arrows">
                                    <a href="{{url('/site_description_asc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/site_description_desc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 12%">
                            <div class="th-sort">Ocena
                                <span class="th-sort__arrows">
                                    <a href="{{url('/site_rating_asc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 15l-6-6-6 6"/></svg></a>
                                    <a href="{{url('/site_rating_desc')}}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 9l6 6 6-6"/></svg></a>
                                </span>
                            </div>
                        </th>
                        <th scope="col" style="width: 20%">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sites as $key => $site)
                    <tr>
                        <th scope="row">{{$sites->firstItem() + $key}}</th>
                        <td class="table_site"><b><a href="{{ $site->link }}" target="_blank">{{ $site->name }}</a></b></td>
                        <td>{{$site->description}}</td>
                        <td>{{$site->rating}}</td>
                        <td>
                            <a href="{{url('/edit_site', $site->id)}}" class="btn btn-info">Edytuj</a>
                            <a class="btn btn-danger" href="{{url('/delete_files_from_admin_search_site', $site->id)}}">Usuń</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="admin-empty-state">
            <p>Brak stron do wyświetlenia.</p>
            <a href="{{url('/add_site')}}" class="btn btn-success">Dodaj nową stronę</a>
        </div>

    @endif

</div>


<div class="modal fade" id="delete_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-body" style="font-size:17px; text-align: center">
                Czy jesteś pewien że chcesz usunąć WSZYSTKIE strony?
                </br></br>
                Wszystkie informację o stronach zostaną usunięte z bazy danych.</br></br> <b>Pamiętaj że decyzji nie można cofnąć!</b>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{url('/delete_all_site')}}">Usuń</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagi') {{$sites->links()}} @endsection
