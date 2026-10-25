
@extends('layouts.app')


@section('title')VideoSite - {{ $films->name }} @endsection


@section('content')

<div class="watch-page">

    <div class="watch-layout">
    <div class="watch-main">

    @if(!empty($successMsg))
    <div id="successMessage" class="alert alert-success">{{ $successMsg }}</div>
    <script>window.setTimeout(function(){ var m = document.getElementById('successMessage'); if(m) m.style.display='none'; }, 2000);</script>
    @endif

    <div class="watch-player">
        <video class="watch-player__video" src="{{URL::asset("$films->url")}}" controls></video>
    </div>

    <div class="watch-meta">
        <h1 class="watch-title">{{ $films->name }}</h1>

        <div class="watch-rating" aria-label="Ocena: {{ $films->rating }} na 6">
            @for ($i = 1; $i <= 6; $i++)
                <i class="star fa fa-star fa-2x {{ $i <= $films->rating ? 'is-filled' : '' }}"></i>
            @endfor
        </div>

        <div class="watch-votes" id="watchVotes" data-url="{{ route('film_vote', $films->id) }}">
            <button type="button" class="watch-vote-btn {{ $my_vote === 1 ? 'is-active' : '' }}" data-vote="up" aria-label="Podoba mi się">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="watch-vote-count" id="voteUp">{{ $votes['up'] }}</span>
            </button>
            <button type="button" class="watch-vote-btn watch-vote-btn--down {{ $my_vote === -1 ? 'is-active' : '' }}" data-vote="down" aria-label="Nie podoba mi się">
                <i class="fa fa-thumbs-down" aria-hidden="true"></i> <span class="watch-vote-count" id="voteDown">{{ $votes['down'] }}</span>
            </button>
        </div>

        <div class="watch-actions">

            <a href="{{ url('/index_random_film_watch') }}?from={{ $films->id }}" class="watch-action-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                Losowy film
            </a>

            <a href="{{URL::asset("$films->url")}}" download="{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz film
            </a>

            <a href="{{URL::asset("$films->short")}}" download="Short_{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz zajawkę
            </a>

            <a href="{{URL::asset("$films->thumbnail")}}" download="Thumbnail_{{"$films->name"}}" class="watch-action-btn">
                <i class="fa fa-download" aria-hidden="true"></i>
                Pobierz miniaturę
            </a>

            @guest
            @else
            <a href="{{url('edit_films', $films->id)}}" class="watch-action-btn watch-action-btn--accent">
                <i class="fa fa-pen" aria-hidden="true"></i>
                Edytuj
            </a>
            @endguest

        </div>
    </div>

    <div class="watch-tagblocks">

        @if(count($tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi</h2>
                <div>
                    @foreach ($tags as $tag)
                        <a href="{{ url('/select_categories', $tag->id) }}" class="tags">{{$tag->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(count($stars) > 0)
            <section class="watch-tagblock">
                <h2>Gwiazdy</h2>
                <div>
                    @foreach ($stars as $star)
                        <a href="{{ url('/select_stars', $star->id) }}" class="starr">{{$star->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($stars_tags === 0)
        @elseif(count($stars_tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi Gwiazd</h2>
                <div>
                    @foreach ($stars_tags as $stars_tags)
                        <a href="{{ url('/select_categories_stars', $stars_tags->tag_id) }}" class="tags">{{$stars_tags->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(count($studios) > 0)
            <section class="watch-tagblock">
                <h2>Wytwórnie</h2>
                <div>
                    @foreach ($studios as $studios)
                        <a href="{{ url('/select_studios', $studios->id) }}" class="studios">{{$studios->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

        @if($studios_tags === 0)
        @elseif(count($studios_tags) > 0)
            <section class="watch-tagblock">
                <h2>Tagi Wytwórni</h2>
                <div>
                    @foreach ($studios_tags as $studios_tags)
                        <a href="{{ url('/select_categories_studios', $studios_tags->tag_id) }}" class="tags">{{$studios_tags->name}}</a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>{{-- /watch-tagblocks --}}

    </div>{{-- /watch-main --}}

    {{-- related-layout v6 --}}
    {{-- panel boczny: 5 filmów w pionie obok odtwarzacza (bez paginacji), tylko na szerokim ekranie --}}
    <aside class="watch-related" aria-label="Podpowiedzi filmów">
        <h2 class="watch-related__title">Polecane</h2>
        <div class="watch-related__list">
            @foreach ($related->take(5) as $rel)
                @include('partials.related_card', ['rel' => $rel, 'tile' => false])
            @endforeach
        </div>
    </aside>
    </div>{{-- /watch-layout --}}

    {{-- pod wytwórnią: 2 poziome rzędy na całą szerokość + paginacja --}}
    <section class="watch-related-bottom" id="relatedBottom" aria-label="Więcej filmów">
        <h2 class="watch-related__title">Może Cię zainteresować</h2>
        <div class="related-grid" id="relatedGrid" style="display:grid;">
            @foreach ($related as $i => $rel)
                @include('partials.related_card', ['rel' => $rel, 'tile' => true, 'side' => $i < 5])
            @endforeach
        </div>
        <div class="related-pager" id="relatedPager" role="navigation" aria-label="Strony podpowiedzi" style="display:flex; flex-direction:row; flex-wrap:wrap; justify-content:center; gap:8px;"></div>
    </section>

</div>

<script>
(function () {
    var box = document.getElementById('watchVotes');
    if (!box) return;
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var busy = false;

    box.addEventListener('click', function (e) {
        var btn = e.target.closest('.watch-vote-btn');
        if (!btn || busy) return;
        busy = true;

        fetch(box.getAttribute('data-url'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ vote: btn.getAttribute('data-vote') })
        })
        .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(function (d) {
            document.getElementById('voteUp').textContent = d.up;
            document.getElementById('voteDown').textContent = d.down;
            box.querySelector('[data-vote="up"]').classList.toggle('is-active', d.mine === 1);
            box.querySelector('[data-vote="down"]').classList.toggle('is-active', d.mine === -1);
        })
        .catch(function () { alert('Nie udało się zapisać głosu. Spróbuj ponownie.'); })
        .then(function () { busy = false; });
    });
})();
</script>


<script>
(function () {
    var grid = document.getElementById('relatedGrid');
    var pager = document.getElementById('relatedPager');
    if (!grid || !pager) { return; }

    var cards = Array.prototype.slice.call(grid.children);
    var wide = window.matchMedia('(min-width: 861px)'); // na szerokim ekranie pierwsze 5 jest w panelu bocznym
    var page = 0;

    function columns() {
        var cols = getComputedStyle(grid).gridTemplateColumns.split(' ').length;
        return Math.max(1, cols);
    }

    function render() {
        var items = cards.filter(function (c) { return !(wide.matches && c.classList.contains('is-side')); });
        var perPage = columns() * 2; // zawsze 2 poziome rzędy
        var pages = Math.max(1, Math.ceil(items.length / perPage));
        if (page > pages - 1) { page = pages - 1; }

        cards.forEach(function (c) { c.style.display = 'none'; });
        items.slice(page * perPage, (page + 1) * perPage).forEach(function (c) { c.style.display = ''; });

        pager.innerHTML = '';
        if (pages <= 1) { return; }

        function btn(label, target, opts) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'related-pager__btn' + (opts && opts.active ? ' is-active' : '');
            b.textContent = label;
            if (opts && opts.disabled) { b.disabled = true; }
            b.addEventListener('click', function () { page = target; render(); });
            pager.appendChild(b);
        }

        btn('‹', page - 1, { disabled: page === 0 });
        for (var i = 0; i < pages; i++) { btn(String(i + 1), i, { active: i === page }); }
        btn('›', page + 1, { disabled: page === pages - 1 });
    }

    var timer;
    window.addEventListener('resize', function () { clearTimeout(timer); timer = setTimeout(render, 120); });
    render();
})();
</script>

@endsection
