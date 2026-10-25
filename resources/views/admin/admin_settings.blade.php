@extends('layouts.admin')

@section('title')VideoSite Ustawienia @endsection

@section('content')

<div class="container" style="margin-top: 3%;">
    <div class="col-sm-12 text-center">
        @if (\Session::has('msg_success'))
            <div class="alert alert-success">{!! \Session::get('msg_success') !!}</div>
        @endif
        @if (\Session::has('msg_errors'))
            <div class="alert alert-danger">{!! \Session::get('msg_errors') !!}</div>
        @endif
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-9 col-lg-7">
            <h3 class="text-center" style="margin-bottom: 24px;">Ustawienia strony</h3>

            <form action="{{ url('/admin_settings_save') }}" method="POST" id="settingsForm">
                @csrf

                <section class="upload-section">
                    <h2>Domyślne sortowanie filmów</h2>
                    <p style="color: var(--muted);">
                        To sortowanie obowiązuje, dopóki użytkownik sam nie wybierze innego z menu „Sortuj według”.
                    </p>

                    <div class="form-group">
                        <label style="display:block; padding:6px 0;">
                            <input type="radio" name="film_sort_mode" value="all" {{ $mode === 'all' ? 'checked' : '' }}>
                            Jednakowo dla wszystkich podstron
                        </label>
                        <label style="display:block; padding:6px 0;">
                            <input type="radio" name="film_sort_mode" value="separate" {{ $mode === 'separate' ? 'checked' : '' }}>
                            Osobno dla każdej podstrony
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="upload-subgroup__label" for="default_film_sort">Ustawienie ogólne (dla wszystkich podstron)</label>
                        <select name="default_film_sort" id="default_film_sort" class="form-control">
                            @foreach ($globalOptions as $key => $label)
                                <option value="{{ $key }}" {{ $key === $globalKey ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="perContextBox" style="margin-top: 22px;">
                        <label class="upload-subgroup__label">Osobne ustawienia podstron</label>
                        <p style="color: var(--muted); font-size: 14px;">
                            Podstrona z wyborem „jak ustawienie ogólne” używa ustawienia z listy powyżej.
                        </p>
                        @foreach ($perContext as $ctx => $info)
                            <div class="form-group">
                                <label for="film_sort_{{ $ctx }}" style="font-weight:600;">{{ $info['name'] }}</label>
                                <select name="film_sort_{{ $ctx }}" id="film_sort_{{ $ctx }}" class="form-control per-context-select">
                                    <option value="" {{ $info['current'] === '' ? 'selected' : '' }}>— jak ustawienie ogólne —</option>
                                    @foreach ($info['options'] as $key => $label)
                                        <option value="{{ $key }}" {{ $key === $info['current'] ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-success" style="margin-top: 10px;">Zapisz ustawienia</button>
                </section>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    var radios = document.querySelectorAll('input[name="film_sort_mode"]');
    var box = document.getElementById('perContextBox');
    var selects = box.querySelectorAll('select');

    function sync() {
        var separate = document.querySelector('input[name="film_sort_mode"]:checked').value === 'separate';
        box.style.opacity = separate ? '1' : '.45';
        selects.forEach(function (s) { s.disabled = !separate; });
    }
    radios.forEach(function (r) { r.addEventListener('change', sync); });
    sync();
})();
</script>

@endsection
