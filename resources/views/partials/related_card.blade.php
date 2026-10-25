{{-- Karta podpowiedzi filmu. $rel = film, $tile = true -> kafelek (pod odtwarzaczem), false -> wiersz (panel boczny) --}}
@php
    $reasonLabels = ['similar' => 'Podobne', 'top' => 'Najlepiej oceniane', 'new' => 'Nowe', 'random' => 'Losowe'];
@endphp
<a href="{{ url('/watch', $rel->id) }}" class="related-card {{ !empty($tile) ? 'related-card--tile' : '' }} {{ !empty($side) ? 'is-side' : '' }}" title="{{ $rel->name }}">
    <div class="related-card__thumb">
        <img src="{{ URL::asset($rel->thumbnail) }}" alt="{{ $rel->name }}" loading="lazy">
        <span class="related-card__duration">{{ gmdate($rel->duration >= 3600 ? 'H:i:s' : 'i:s', (int) $rel->duration) }}</span>
    </div>
    <div class="related-card__body">
        <div class="related-card__name">{{ $rel->name }}</div>
        <div class="related-card__meta">
            <span class="related-badge related-badge--{{ $rel->reason }}">{{ $reasonLabels[$rel->reason] ?? '' }}</span>
            <span><i class="fa fa-star" aria-hidden="true"></i> {{ $rel->rating }}</span>
            @if (($rel->net_votes ?? 0) != 0)
                <span><i class="fa fa-thumbs-{{ $rel->net_votes > 0 ? 'up' : 'down' }}" aria-hidden="true"></i> {{ abs($rel->net_votes) }}</span>
            @endif
        </div>
    </div>
</a>
