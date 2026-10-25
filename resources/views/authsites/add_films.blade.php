
@extends('layouts.app')

@section('title')VideoSite Dodaj Film @endsection

@section('content')

<script src="{{ asset('js/add_films.js') }}" defer></script>
<script src="{{ asset('js/starrr.js') }}" defer></script>
<script src="{{ asset('js/jquery.form.js') }}" defer></script>

<script>
// suffix: '' = podgląd desktop, '_2' = podgląd mobile
function previewEls(suffix) {
    var card = document.getElementById('sticky_video' + suffix);
    return { card: card, box: card.querySelector('.resizable-video') };
}
function setStickyButtons(suffix, floating) {
    document.getElementById('button_video_sticky' + suffix).style.display = floating ? 'none' : 'inline';
    document.getElementById('button_video_sticky_block' + suffix).style.display = floating ? 'inline' : 'none';
    document.getElementById('button_video_reset' + suffix).style.display = floating ? 'inline' : 'none';
}
function clearPreviewInline(el) {
    el.box.style.width = '';
    el.box.style.height = '';
    ['left', 'top', 'right', 'bottom', 'width', 'height'].forEach(function (p) { el.card.style[p] = ''; });
}
function btn_stick_any(suffix) {
    var el = previewEls(suffix);
    // zapamiętaj dokładne miejsce i rozmiar, żeby po odpięciu nic nie "skakało"
    var cardRect = el.card.getBoundingClientRect();
    var boxRect = el.box.getBoundingClientRect();
    el.box.style.width = boxRect.width + 'px';
    el.box.style.height = boxRect.height + 'px';
    el.card.style.left = cardRect.left + 'px';
    el.card.style.top = cardRect.top + 'px';
    el.card.classList.add('is-floating');
    setStickyButtons(suffix, true);
}
function del_stick_any(suffix) {
    // przypięcie z powrotem na miejscu = pełny reset rozmiaru i pozycji
    var el = previewEls(suffix);
    el.card.classList.remove('is-floating');
    clearPreviewInline(el);
    setStickyButtons(suffix, false);
}
function resetPreviewSize(suffix) {
    // "Resetuj widok": wraca do domyślnego rozmiaru i położenia, ale nadal jest odpięty
    del_stick_any(suffix);
    btn_stick_any(suffix);
}
function del_stick() { del_stick_any(''); }
function btn_stick() { btn_stick_any(''); }
function del_stick_2() { del_stick_any('_2'); }
function btn_stick_2() { btn_stick_any('_2'); }
</script>

<style>
/* --- okno przesyłania i przetwarzania --- */
#div2 .modal-dialog { max-width: 480px; width: calc(100% - 24px); margin: 0 auto; }
#div2 .modal-content { width: 100%; }
#div2 .modal-body { display: block; padding: 22px 24px; text-align: center; }
#div2 .modal-title { width: 100%; text-align: center; margin: 0; }
#div2 .modal-footer { justify-content: center; gap: 8px; }

.upload-stage-list { list-style:none; padding:0; margin:0 auto 14px; display:inline-flex; flex-direction:column; gap:8px; text-align:left; }
.upload-stage-list li { display:flex; align-items:center; gap:10px; font-size:15px; opacity:.5; }
.upload-stage-list li .upload-stage-check { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; border:2px solid currentColor; font-size:12px; flex-shrink:0; }
.upload-stage-list li.is-active { opacity:1; font-weight:600; }
.upload-stage-list li.is-active .upload-stage-check { border-top-color:transparent; animation:upload-spin .8s linear infinite; }
.upload-stage-list li.is-done { opacity:1; color:#28a745; }
.upload-stage-list li.is-done .upload-stage-check { background:#28a745; border-color:#28a745; }
.upload-stage-list li.is-done .upload-stage-check::after { content:"\2713"; color:#fff; }
@keyframes upload-spin { to { transform:rotate(360deg); } }

.upload-progressbar-wrap { margin: 0 0 6px; }
.upload-progressbar-track { background:#e9ecef; border-radius:999px; height:12px; overflow:hidden; }
.upload-progressbar-fill { background:#28a745; height:100%; width:0%; transition:width .25s ease; }
.upload-progressbar-label { text-align:right; font-size:13px; color:#6c757d; margin-top:6px; }
.upload-status-text { margin-top:10px; font-size:14px; }
.upload-status-text.is-success { color:#28a745; font-weight:600; }
.upload-error-box {
  margin-top:14px; padding:10px 14px; border-radius:6px;
  background:#f8d7da; color:#842029; font-size:14px; text-align:left; white-space:pre-line;
}
.upload-warn-box {
  margin-top:14px; padding:10px 14px; border-radius:6px;
  background:#fff3cd; color:#664d03; font-size:14px; text-align:left; white-space:pre-line;
}
</style>

<div class="upload-page">

    <div class="col-sm-12 text-center">
        @if (\Session::has('msg_success'))
          <div class="alert alert-success">
              <ul>{!! \Session::get('msg_success') !!}</ul>
          </div>
        @endif
        @if (\Session::has('msg_errors'))
          <div class="alert alert-danger">
              <ul>{!! \Session::get('msg_errors') !!}</ul>
          </div>
        @endif
    </div>

    @if(!empty($errorsMsg))
    <div class="alert alert-success upload-ffmpeg-alert">
        {{ $errorsMsg }}<br>BRAK ZAINSTALOWANEGO LUB SKONFIGUROWANEGO PAKIETU FFMPEG NA URZĄDZENIU
        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Spróbuj ponownie zainstalować
        pakiet ffmpeg na pc. W innym wypadku nie będzie możliwości wykonania miniatury oraz zwiastunu filmu."></i>
    </div>
    @endif

    <h1 class="upload-page__title">Dodaj nowy film</h1>

    <div class="upload-layout">

        <!-- ============ LEWA STRONA: podgląd wideo (desktop) ============ -->
        <div class="upload-preview d-none d-lg-block">
            <div class="upload-preview__card" id="sticky_video">
                <div class="upload-preview__draghandle" data-drag-handle title="Przeciągnij, aby przenieść podgląd">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="6" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="18" r="1"/></svg>
                    Przeciągnij, aby przenieść
                </div>
                <div class="resizable-video">
                    <video poster="{{ asset('icon/app.blade/logo_video.png') }}" controls>
                        <source src="" id="video_here">
                    </video>
                </div>
                <p class="upload-preview__hint">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21l6-6m0 0l6 6M9 15V4m6 0l6 6m0 0l-6 6m6-6H10"/></svg>
                    Przeciągnij za prawy dolny róg, aby zmienić rozmiar podglądu (po odblokowaniu możesz go powiększyć do woli).
                </p>
                <div class="upload-preview__actions">
                    <button class="btn btn-delete" onclick="del_stick()" id="button_video_sticky_block" style="display: none;">Przypnij na miejscu</button>
                    <button class="btn btn-success" onclick="btn_stick()" id="button_video_sticky">Odblokuj i przesuń</button>
                    <button class="btn btn-secondary" onclick="resetPreviewSize('')" id="button_video_reset" style="display: none;" title="Przywróć domyślny rozmiar i położenie">Resetuj widok</button>
                </div>
            </div>
        </div>

        <!-- ============ PRAWA STRONA: formularz ============ -->
        <div class="upload-form-wrap">

            <!-- Podgląd wideo (mobile / tablet) -->
            <div class="upload-preview d-block d-lg-none">
                <div class="upload-preview__card" id="sticky_video_2">
                    <div class="upload-preview__draghandle" data-drag-handle title="Przeciągnij, aby przenieść podgląd">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="6" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="18" r="1"/></svg>
                        Przeciągnij, aby przenieść
                    </div>
                    <div class="resizable-video resizable-video--sm">
                        <video poster="{{ asset('icon/app.blade/logo_video.png') }}" controls>
                            <source src="" id="video_here_2">
                        </video>
                    </div>
                    <div class="upload-preview__actions">
                        <button class="btn btn-delete" onclick="del_stick_2()" id="button_video_sticky_block_2" style="display: none;">Przypnij na miejscu</button>
                        <button class="btn btn-success" onclick="btn_stick_2()" id="button_video_sticky_2">Odblokuj i przesuń</button>
                        <button class="btn btn-secondary" onclick="resetPreviewSize('_2')" id="button_video_reset_2" style="display: none;" title="Przywróć domyślny rozmiar i położenie">Resetuj widok</button>
                    </div>
                </div>
            </div>

            <form action="{{ url('/add_films_save') }}" method="POST" enctype="multipart/form-data" id="add_films">
                @csrf

                <!-- 1. PLIK -->
                <section class="upload-section">
                    <h2><span class="upload-step">1</span> Plik wideo</h2>

                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="film" name="file">
                        <label class="custom-file-label" for="film">Wybierz Plik</label>
                    </div>
                    @error('file')
                        <div class="alert alert-danger valid_msg">{{ $message }}</div>
                    @enderror
                    @if($errors->has('file'))
                        <div class="error">{{ $errors->first('file') }}</div>
                    @endif
                </section>

                <!-- 2. ZWIASTUN I MINIATURKA -->
                <section class="upload-section">
                    <h2><span class="upload-step">2</span> Zwiastun i miniaturka</h2>

                    <div class="upload-toggle-row">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label" for="customCheck">Trailer filmowy</label>
                        </div>
                        <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Trailer tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>
                    </div>
                    <div id='collapsediv1' class='upload-collapse'>
                        <input type="time" name="short_time" id="short_time" step="1" value="00:00:00" max="05:00:00">
                        <input name="time_sec" type="hidden" value="720"/>
                    </div>

                    <div class="upload-toggle-row" style="margin-top: 14px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="thumbchk">
                            <label class="custom-control-label" for="thumbchk">Miniaturka Filmu</label>
                        </div>
                        <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Miniaturke tworzymy z 12 min filmu jeśli masz ochotę to zmienić możesz zrobić to zanzaczając checkbox"></i>
                    </div>
                    <div id='thumb' class='upload-collapse'>
                        <input type="time" name="short_time_thumbnail" id="short_time_thumbnail" step="1" value="00:00:00" max="05:00:00">
                        <input name="time_sec_thumbnail" type="hidden" value="720"/>
                    </div>
                </section>

                <!-- 3. SZCZEGÓŁY -->
                <section class="upload-section">
                    <h2><span class="upload-step">3</span> Szczegóły filmu</h2>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="film_name" name="film_name" placeholder="Tytuł Filmu" value="{{ old('film_name') }}">
                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('film_name').value = ''">
                                    <i class="fa fa-times"></i>
                                </button>
                            </span>
                        </div>
                        @error('film_name')
                            <div class="alert alert-danger valid_msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#film').on('input', function() {
                                var name = document.getElementById("film").value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, "");
                                $("input[id='film_name']").val(name);
                            });
                        });
                    </script>

                    <div>
                    @php
                        $directory = "../../filmy/";
                        if (!file_exists($directory)) {
                            echo '<div class="form-group"><input type="text" class="form-control upload-missing-dir" placeholder="BRAK FOLDERU DO ZAPISU!" disabled></div>';
                        } else {
                            echo '<div class="form-group"><label for="exampleFormControlSelect">Wybierz nazwę folder do zapisu</label>
                            <select class="browser-default custom-select" id="exampleFormControlSelect1" name="katalog"><option></option>';
                            if ($handle = opendir('../../filmy/')) {
                                while (false !== ($file = readdir($handle))) {
                                    if ($file != "." && $file != ".." && $file != "thumbnail" && $file != "short" && $file != "conversion" && $file != "cut" && $file != "join") {
                                        echo "<option>".$file."</option>";
                                    }
                                }
                                closedir($handle);
                            }
                            echo '</select></div>';
                        }
                    @endphp
                        @if ($errors->any())
                        <div class="upload-old-value">Wybrany katalog do zapisu filmu: <strong>{{ old('katalog') }}</strong></div>
                        @endif
                    </div>
                </section>

                <!-- 4. TAGI / GWIAZDY / WYTWÓRNIE -->
                <section class="upload-section">
                    <h2><span class="upload-step">4</span> Tagi, gwiazdy, wytwórnie</h2>

                    <div class="upload-subgroup">
                        <label class="upload-subgroup__label">Tagi filmu</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="tag" placeholder="Dodaj Nowe Tagi" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addTag">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="tagList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 40 tagów do filmu które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 40; $i++){{ old("multiTag.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="extra_tag_stars" id="extra_tag_stars" value="0">
                            <label class="custom-control-label" for="extra_tag_stars">Dodaj tagi gwiazdy</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do gwiazdy (wykorzystywana baza tagów filmowych)."></i>
                        </div>

                        <label class="upload-subgroup__label" style="margin-top:14px;">Gwiazdy</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="star" placeholder="Dodaj Nowe Gwiazdy" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addStar">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="starList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 15 aktorów do filmu których chciałeś dodać:<br>
                                @for ($i = 0; $i <= 15; $i++){{ old("multiStar.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>

                    <div class="upload-subgroup">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="extra_tag_studios" name="extra_tag_studios" value="testatestetsaet">
                            <label class="custom-control-label" for="extra_tag_studios">Dodaj tagi wytwórni</label>
                            <i class="fas fa-question" data-toggle="tooltip" data-placement="bottom" title="Dodasz automatycznie przypisane tagi filmowe do wytwórni (wykorzystywana baza tagów filmowych)."></i>
                        </div>

                        <label class="upload-subgroup__label" style="margin-top:14px;">Wytwórnie</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control ui-autocomplete-input" id="studios" placeholder="Dodaj Nowe Wytwórnie" autocomplete="off">
                                <div class="input-group-append">
                                    <div class="btn btn-success text-white" id="addStudios">Dodaj</div>
                                </div>
                            </div>
                        </div>
                        <ul class="upload-pill-list" id="studiosList">
                        @if ($errors->any())
                            <li class="upload-old-value">
                                Zapisaliśmy ostatnie 5 wytwórni które chciałeś dodać:<br>
                                @for ($i = 0; $i <= 5; $i++){{ old("multiStudios.$i") }}&nbsp;@endfor
                            </li>
                        @endif
                        </ul>
                    </div>
                </section>

                <!-- 5. OCENA -->
                <section class="upload-section">
                    <h2><span class="upload-step">5</span> Ocena filmu</h2>
                    <input type="hidden" name="star" id="save_rating" value="">
                    <div class='ratings'></div>
                    @if ($errors->any())
                        <div class="upload-old-value">Wybrana ocena filmu: {{ old('star') }}</div>
                    @endif
                </section>

                <div class="upload-submit-row" id="div1">
                    <button type="submit" name="submit" id="submit" class="btn btn-success btn-lg">Zapisz film</button>
                </div>
                <div id="message"></div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="div2" tabindex="-1" role="dialog" aria-labelledby="uploadModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modtext">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalTitle">Przesyłanie pliku</h5>
            </div>
            <div class="modal-body">
                <ul class="upload-stage-list" id="uploadStageList">
                    <li data-stage="uploading"><span class="upload-stage-check"></span> Przesyłanie pliku</li>
                    <li data-stage="tags"><span class="upload-stage-check"></span> Zapisywanie tagów, gwiazd i wytwórni</li>
                    <li data-stage="trailer"><span class="upload-stage-check"></span> Tworzenie zwiastunu</li>
                    <li data-stage="thumbnail"><span class="upload-stage-check"></span> Generowanie miniatury</li>
                    <li data-stage="done"><span class="upload-stage-check"></span> Gotowe!</li>
                </ul>

                <div class="upload-progressbar-wrap" id="uploadProgressWrap">
                    <div class="upload-progressbar-track">
                        <div class="upload-progressbar-fill" id="uploadProgressFill"></div>
                    </div>
                    <div class="upload-progressbar-label" id="uploadProgressLabel">0%</div>
                </div>

                <div class="upload-status-text" id="uploadStatusText">Prosimy o chwilę cierpliwości...</div>
                <div class="upload-warn-box" id="uploadWarnBox" style="display:none;"></div>
                <div class="upload-error-box" id="uploadErrorBox" style="display:none;"></div>
            </div>
            <div class="modal-footer" id="uploadModalFooter" style="display:none;">
                <a href="#" class="btn btn-success" id="uploadEditLink" style="display:none;">Edytuj film</a>
                <button type="button" class="btn btn-secondary" id="uploadCloseBtn">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var form = document.getElementById('add_films');
    var modalEl = $('#div2');
    var titleEl = document.getElementById('uploadModalTitle');
    var stageList = document.getElementById('uploadStageList');
    var progressWrap = document.getElementById('uploadProgressWrap');
    var progressFill = document.getElementById('uploadProgressFill');
    var progressLabel = document.getElementById('uploadProgressLabel');
    var statusText = document.getElementById('uploadStatusText');
    var warnBox = document.getElementById('uploadWarnBox');
    var errorBox = document.getElementById('uploadErrorBox');
    var footer = document.getElementById('uploadModalFooter');
    var editLink = document.getElementById('uploadEditLink');
    var closeBtn = document.getElementById('uploadCloseBtn');
    var submitBtn = document.getElementById('submit');

    var STORE_KEY = 'videosite_upload_job';
    var STATUS_URL = '{{ url('/add_films_status') }}/';
    var EDIT_URL = '{{ url('/edit_films') }}/';
    var ORDER = ['uploading', 'tags', 'trailer', 'thumbnail', 'done'];

    var pollTimer = null;
    var job = null; // { token, filmId, name, hidden }

    // ---------- trwałość: po odświeżeniu strony wznawiamy podgląd ----------
    function saveJob() {
        try {
            if (job) { localStorage.setItem(STORE_KEY, JSON.stringify(job)); }
            else { localStorage.removeItem(STORE_KEY); }
        } catch (e) {}
    }
    function loadJob() {
        try { return JSON.parse(localStorage.getItem(STORE_KEY)); } catch (e) { return null; }
    }

    // ---------- UI ----------
    function setStage(stageKey, allDone) {
        var reached = ORDER.indexOf(stageKey);
        stageList.querySelectorAll('li').forEach(function (li) {
            var i = ORDER.indexOf(li.getAttribute('data-stage'));
            li.classList.remove('is-active', 'is-done');
            if (allDone || i < reached) { li.classList.add('is-done'); }
            else if (i === reached) { li.classList.add('is-active'); }
        });
    }

    function resetModal() {
        titleEl.textContent = 'Przesyłanie pliku';
        progressWrap.style.display = '';
        progressFill.style.width = '0%';
        progressLabel.textContent = '0%';
        statusText.style.display = '';
        statusText.className = 'upload-status-text';
        statusText.textContent = 'Prosimy o chwilę cierpliwości...';
        warnBox.style.display = 'none';
        errorBox.style.display = 'none';
        errorBox.textContent = '';
        footer.style.display = 'none';
        editLink.style.display = 'none';
        setStage('uploading');
    }

    function showProcessing() {
        // plik jest już na serwerze - pasek wysyłania znika, zostaje lista etapów
        titleEl.textContent = 'Przetwarzanie filmu';
        progressWrap.style.display = 'none';
        statusText.style.display = '';
        statusText.className = 'upload-status-text';
        statusText.textContent = 'To może potrwać kilka minut. Możesz zamknąć to okno lub odświeżyć stronę — ' +
            'przetwarzanie trwa w tle, a po jego zakończeniu to okno pojawi się ponownie.';
        footer.style.display = '';
        closeBtn.textContent = 'Zamknij';
        editLink.style.display = 'none';
    }

    function showResult(data) {
        var ok = data.stage === 'done' && (!data.message || data.message === 'Gotowe!');
        progressWrap.style.display = 'none';
        footer.style.display = '';
        closeBtn.textContent = 'Dodaj kolejny film';
        editLink.href = EDIT_URL + job.filmId;
        editLink.style.display = '';

        if (ok) {
            setStage('done', true);
            titleEl.textContent = 'Gotowe!';
            statusText.className = 'upload-status-text is-success';
            statusText.textContent = 'Wszystko się udało! Film „' + job.name + '” został przesłany i przetworzony.';
        } else {
            setStage('done', data.stage === 'done');
            titleEl.textContent = 'Film przesłany';
            statusText.className = 'upload-status-text';
            statusText.textContent = 'Film „' + job.name + '” został zapisany, ale nie wszystko udało się przetworzyć:';
            warnBox.style.display = 'block';
            warnBox.textContent = data.message || 'Nieznany błąd.';
        }
        modalEl.modal('show');

        // wynik został pokazany - po odświeżeniu nie wracamy już do tego filmu
        job = null;
        saveJob();
    }

    function showUploadError(message) {
        statusText.style.display = 'none';
        errorBox.style.display = 'block';
        errorBox.textContent = message || 'Wystąpił nieoczekiwany błąd podczas przesyłania filmu.';
        footer.style.display = '';
        closeBtn.textContent = 'Zamknij';
        editLink.style.display = 'none';
        submitBtn.disabled = false;
    }

    closeBtn.addEventListener('click', function () {
        if (job) { job.hidden = true; saveJob(); } // zamknięte w trakcie przetwarzania
        modalEl.modal('hide');
    });

    // ---------- odpytywanie o postęp ----------
    function poll() {
        if (!job) { return; }
        fetch(STATUS_URL + job.token, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(function (res) {
            if (res.status === 404) { stopPolling(); job = null; saveJob(); modalEl.modal('hide'); return null; }
            if (!res.ok) { throw new Error('status_' + res.status); }
            return res.json();
        })
        .then(function (data) {
            if (!data || !job) { return; }
            if (data.stage === 'done' || data.stage === 'error') {
                stopPolling();
                showResult(data);
            } else if (ORDER.indexOf(data.stage) > 0) {
                setStage(data.stage);
            } else {
                setStage('tags'); // 'queued' - czeka na worker; pierwszy etap pokazujemy jako bieżący
            }
        })
        .catch(function () { /* pojedynczy błąd odczytu - spróbujemy ponownie */ });
    }
    function startPolling() {
        stopPolling();
        poll();
        pollTimer = setInterval(poll, 2000);
    }
    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    // ---------- wznowienie po odświeżeniu strony ----------
    var saved = loadJob();
    if (saved && saved.token) {
        job = saved;
        resetModal();
        showProcessing();
        setStage('tags');
        if (!job.hidden) { modalEl.modal('show'); }
        startPolling();
    }

    function resetForm() {
        form.reset();
        $('#tagList, #starList, #studiosList').find('li, input').remove();
        $('#video_here, #video_here_2').attr('src', '');
        $('.custom-file-label').removeClass('selected').text('');
        submitBtn.disabled = false;
    }

    // ---------- wysyłanie formularza ----------
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var filmName = document.getElementById('film_name').value;
        var fileInput = document.getElementById('film');

        if (filmName === '' || fileInput.value === '') {
            return;
        }

        stopPolling();
        resetModal();
        submitBtn.disabled = true;
        modalEl.modal('show');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.getAttribute('action'), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', function (evt) {
            if (evt.lengthComputable) {
                var pct = Math.round((evt.loaded / evt.total) * 100);
                progressFill.style.width = pct + '%';
                progressLabel.textContent = pct + '%';
                if (pct >= 100) { statusText.textContent = 'Zapisywanie filmu na serwerze...'; }
            }
        });

        xhr.onload = function () {
            var response = null;
            try { response = JSON.parse(xhr.responseText); } catch (err) { response = null; }

            if (xhr.status === 200 && response && response.token) {
                job = { token: response.token, filmId: response.film_id, name: filmName, hidden: false };
                saveJob();
                resetForm();
                showProcessing();
                setStage('tags');
                startPolling();
            } else if (xhr.status === 422 && response && response.errors) {
                var keys = Object.keys(response.errors);
                var firstError = keys.length ? response.errors[keys[0]] : null;
                showUploadError(Array.isArray(firstError) ? firstError[0] : firstError);
            } else {
                showUploadError('Nie udało się wysłać pliku na serwer (kod ' + xhr.status + ').');
            }
        };

        xhr.onerror = function () {
            showUploadError('Błąd połączenia podczas wysyłania pliku.');
        };

        xhr.send(new FormData(form));
    });
})();
</script>

<script>
$(function () {

    // ---------- Zwiastun / miniaturka: pola pojawiają się natychmiast i zawsze zgodnie z checkboxem ----------
    function bindToggle(checkId, boxId, timeId, hiddenName) {
        var chk = document.getElementById(checkId);
        var box = document.getElementById(boxId);
        if (!chk || !box) { return; }

        function sync() {
            box.classList.toggle('is-open', chk.checked);
            if (!chk.checked) {
                // wyłączone = wracamy do wartości domyślnych, żeby ukryte pole nie wysyłało starego czasu
                document.getElementById(timeId).value = '00:00:00';
                var hidden = box.querySelector('input[name="' + hiddenName + '"]');
                if (hidden) { hidden.value = '720'; }
            }
        }
        chk.addEventListener('change', sync);
        // przeglądarka potrafi odtworzyć zaznaczony checkbox po odświeżeniu - ustawiamy widok zgodnie ze stanem
        box.classList.toggle('is-open', chk.checked);
    }
    bindToggle('customCheck', 'collapsediv1', 'short_time', 'time_sec');
    bindToggle('thumbchk', 'thumb', 'short_time_thumbnail', 'time_sec_thumbnail');

    // ---------- Tagi / gwiazdy / wytwórnie ----------
    function addPill(cfg) {
        var $input = $(cfg.input);
        var name = $.trim($input.val());
        if (!name) { return; }

        var $list = $(cfg.list);

        // ta sama pozycja nie może być dodana dwa razy
        var duplicate = false;
        $list.children('li[data-name]').each(function () {
            if ($(this).attr('data-name').toLowerCase() === name.toLowerCase()) { duplicate = true; }
        });
        if (duplicate) { $input.val('').removeAttr(cfg.idAttr); return; }

        var id = $input.attr(cfg.idAttr);
        var $li = $('<li>').addClass(cfg.cls).attr('data-name', name);

        if (id) {
            $('<a>').attr({ href: cfg.urlBase + '/' + id, target: '_blank' }).text(name).appendTo($li);
        } else {
            $('<span>').text(name).appendTo($li);
        }
        $('<button>').attr({ type: 'button', 'aria-label': 'Usuń: ' + name, title: 'Usuń' })
            .addClass('pill-remove').html('&times;').appendTo($li);
        $('<input>').attr({ type: 'hidden', name: cfg.fieldName }).val(name).appendTo($li);

        // wstawiamy alfabetycznie (po nazwie), a nie na końcu
        var placed = false;
        $list.children('li[data-name]').each(function () {
            if (name.localeCompare($(this).attr('data-name'), 'pl', { sensitivity: 'base' }) < 0) {
                $li.insertBefore(this);
                placed = true;
                return false;
            }
        });
        if (!placed) { $list.append($li); }

        $input.val('').removeAttr(cfg.idAttr);
    }

    function initPicker(cfg) {
        $(cfg.input).autocomplete({
            source: cfg.source,
            minLength: 1,
            scroll: true,
            select: function (event, ui) {
                $(cfg.input).val(ui.item.value);
                $(cfg.input).attr(cfg.idAttr, ui.item[cfg.itemId]);
            }
        }).autocomplete('instance')._renderItem = function (ul, item) {
            return $('<li>').addClass(item.disabled)
                .append($('<div>')
                    .append($('<img>').attr('src', item.img))
                    .append($('<span>').text(item.value)))
                .appendTo(ul);
        };

        $(cfg.addBtn).on('click', function () { addPill(cfg); });
        $(cfg.list).on('click', 'button.pill-remove', function () {
            $(this).closest('li').remove(); // razem z ukrytym polem formularza
        });
    }

    initPicker({
        input: '#tag', addBtn: '#addTag', list: '#tagList', cls: 'tags',
        fieldName: 'multiTag[]', idAttr: 'tag_id', itemId: 'tag_id',
        source: "{{ url('/gettag') }}", urlBase: "{{ url('/select_categories') }}"
    });
    initPicker({
        input: '#star', addBtn: '#addStar', list: '#starList', cls: 'starr',
        fieldName: 'multiStar[]', idAttr: 'star_id', itemId: 'star_id',
        source: "{{ url('/getstar') }}", urlBase: "{{ url('/select_stars') }}"
    });
    initPicker({
        input: '#studios', addBtn: '#addStudios', list: '#studiosList', cls: 'studios',
        fieldName: 'multiStudios[]', idAttr: 'studios_id', itemId: 'studios_id',
        source: "{{ url('/getstudios') }}", urlBase: "{{ url('/select_studios') }}"
    });
});
</script>

@endsection
