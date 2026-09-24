
@extends('layouts.admin')

@section('title')VideoSite Edytuj Tag @endsection

@section('direction')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/admin_tags') }}">Kategorie</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edytowanie Kategorii</li>
</ol>
@endsection

@section('content')

<div class="upload-page">

    <h1 class="upload-page__title">Edytuj: {{$tags->name}}</h1>

    @if (\Session::has('msg_success'))
    <div class="alert alert-success"><ul>{!! \Session::get('msg_success') !!}</ul></div>
    @endif
    @if (\Session::has('msg_errors'))
    <div class="alert alert-danger"><ul>{!! \Session::get('msg_errors') !!}</ul></div>
    @endif

    <div class="upload-layout">

        <div class="upload-preview">
            <div class="upload-preview__card">
                <label class="upload-subgroup__label">Ustawione</label>
                <div class="resizable-video" style="aspect-ratio:1/1; margin-bottom:16px;">
                    <img src="{{URL::asset("$tags->thumbnail")}}" style="width:100%; height:100%; object-fit:contain;">
                </div>

                <label class="upload-subgroup__label">Podgląd nowego (po wybraniu pliku)</label>
                <div class="resizable-video" style="aspect-ratio:1/1;">
                    <img src="{{ asset('icon/app.blade/notfing_found.png') }}" id="video_here" style="width:100%; height:100%; object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="upload-form-wrap">
            <form action="{{url('/edit_tags_save')}}" method="POST" enctype="multipart/form-data" id="edit_tags_main">
                @csrf

                <section class="upload-section">
                    <h2><span class="upload-step">1</span> Zdjęcie i nazwa</h2>

                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="thumbnail_tags" name="thumbnail_tags">
                        <label class="custom-file-label" for="thumbnail_tags">Wybierz zdjęcie</label>
                    </div>

                    <a class="admin-quicklink" href="{{url('/open_folder_tags', $tags->id)}}" style="margin-bottom: 16px; display:inline-flex;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        Główny folder z tagami
                    </a>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control @error('tags_name') form-error @enderror" name="tags_name" placeholder="Nazwa tagu" value="{{$tags->name}}">
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary fas fa-folder-open" href="{{url('/open_folder_tags_next', $tags->id)}}" title="Otwórz folder"></a>
                            </div>
                        </div>
                        @error('tags_name')<div class="alert alert-danger valid_msg">{{ $message }}</div>@enderror
                    </div>
                    <input type="hidden" value="{{$tags->id}}" name="tags_id">
                </section>

                <section class="upload-section">
                    <h2><span class="upload-step">2</span> Rozmiar zdjęcia</h2>
                    <small class="form-text text-muted">Zdjęcia w dobrej jakości zajmują bardzo dużo miejsca na dysku twardym.</small>
                    <small class="form-text text-muted">Możemy to zmienić, zmniejszając zdjęcia do rozmiaru 350×350px — wystarczy zaznaczyć "Tak".</small>
                    <small class="form-text text-muted" style="display:block; margin: 8px 0 14px;">
                        Jeśli nie odpowiadają Ci powyższe wymiary, możesz to zmienić
                        <a type="button" data-toggle="collapse" data-target="#hiddendiv" aria-expanded="false" aria-controls="collapseExample" onclick="checked_radio()"><b>tutaj</b></a>
                    </small>

                    <label for="yes_no_radio">Czy chcesz zmniejszyć zdjęcia?</label>
                    <div class="upload-toggle-row" style="margin-top:8px;">
                        <label><input type="radio" name="resize_img" id="resize_img_1" value="1"> Tak</label>
                        <label style="margin-left:20px;"><input type="radio" name="resize_img" id="resize_img_2" value="2" checked> Nie</label>
                    </div>

                    <div class="collapse upload-collapse" id="hiddendiv">
                        <div style="display:flex; gap:16px;">
                            <input type="text" class="form-control" name="height_img" placeholder="Wysokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                            <input type="text" class="form-control" name="width_img" placeholder="Szerokość" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                        </div>
                    </div>
                </section>

                <div class="upload-submit-row">
                    <button class="btn btn-success btn-lg">Wyślij</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function checked_radio() {
    if ($('#hiddendiv').is('.collapse')) {
        document.getElementById("resize_img_1").checked = false;
        document.getElementById("resize_img_2").checked = true;
    }
    if ($('#hiddendiv').is('.collapse:not(.show)')) {
        document.getElementById("resize_img_1").checked = true;
        document.getElementById("resize_img_2").checked = false;
    }
}
</script>

@endsection
