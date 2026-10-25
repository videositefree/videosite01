<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Podpowiedzi filmów obok odtwarzacza. Mieszanka:
 *  - "similar": wspólne gwiazdy / wytwórnie / tagi z oglądanym filmem
 *               (gwiazda waży najwięcej), z premią za łapki w górę i świeżość,
 *  - "top":     najlepiej oceniane łapkami,
 *  - "new":     najnowsze,
 *  - "random":  losowe (żeby coś nowego zawsze się trafiło).
 * Kolejność jest przeplatana, a losowość stabilna w obrębie jednej sesji i filmu
 * (odświeżenie strony nie tasuje wszystkiego od nowa).
 */
class RelatedFilms
{
    const TOTAL = 30; // 6 w panelu bocznym + 3 strony po 8 pod odtwarzaczem

    private static function ids($table, $col, $filmId)
    {
        return DB::table($table)->where('film_id', $filmId)->pluck($col)->map(function ($v) { return (int) $v; })->all();
    }

    private static function base($filmId, array $exclude = [])
    {
        $q = DB::table('films')
            ->where('films.activ', '=', '1')
            ->where('films.id', '!=', $filmId);

        if (count($exclude)) {
            $q->whereNotIn('films.id', $exclude);
        }
        return $q;
    }

    private static function netVotes($filmIds)
    {
        if (count($filmIds) === 0) {
            return [];
        }
        return DB::table('film_votes')
            ->whereIn('film_id', $filmIds)
            ->groupBy('film_id')
            ->selectRaw('film_id, COALESCE(SUM(vote), 0) as net')
            ->pluck('net', 'film_id')
            ->map(function ($v) { return (int) $v; })
            ->all();
    }

    private static function votesSelect($query)
    {
        $sub = DB::table('film_votes')->whereColumn('film_votes.film_id', 'films.id')
            ->selectRaw('COALESCE(SUM(film_votes.vote), 0)');
        if (empty($query->columns)) {
            $query->select('films.*');
        }
        return $query->selectSub($sub, 'net_votes');
    }

    public static function for($film, $total = self::TOTAL)
    {
        $filmId = (int) $film->id;

        mt_srand(crc32(session()->getId() . '|' . $filmId));

        $tagIds = self::ids('films_tags', 'tag_id', $filmId);
        $starIds = self::ids('films_stars', 'stars_id', $filmId);
        $studioIds = self::ids('films_studios', 'studios_id', $filmId);

        $picked = [];      // id => true
        $lists = ['similar' => [], 'top' => [], 'new' => [], 'random' => []];

        // ---- 1. podobne: wspólne gwiazdy / wytwórnie / tagi
        if (count($tagIds) || count($starIds) || count($studioIds)) {
            $q = self::base($filmId);

            $q->select('films.*');
            foreach ([['films_tags', 'tag_id', $tagIds, 'shared_tags'],
                      ['films_stars', 'stars_id', $starIds, 'shared_stars'],
                      ['films_studios', 'studios_id', $studioIds, 'shared_studios']] as $def) {
                list($pivot, $col, $ids, $alias) = $def;
                if (count($ids)) {
                    $q->selectSub(DB::table($pivot)->whereColumn($pivot . '.film_id', 'films.id')
                        ->whereIn($pivot . '.' . $col, $ids)->selectRaw('COUNT(*)'), $alias);
                } else {
                    $q->selectRaw('0 as ' . $alias);
                }
            }

            $q->where(function ($w) use ($tagIds, $starIds, $studioIds) {
                if (count($tagIds))    { $w->orWhereIn('films.id', DB::table('films_tags')->select('film_id')->whereIn('tag_id', $tagIds)); }
                if (count($starIds))   { $w->orWhereIn('films.id', DB::table('films_stars')->select('film_id')->whereIn('stars_id', $starIds)); }
                if (count($studioIds)) { $w->orWhereIn('films.id', DB::table('films_studios')->select('film_id')->whereIn('studios_id', $studioIds)); }
            });

            // pula kandydatów: najwięcej wspólnych cech (gwiazda 8, wytwórnia 5, tag 3)
            $q->orderByRaw('(shared_stars * 8 + shared_studios * 5 + shared_tags * 3) DESC')
              ->orderBy('films.id', 'desc')
              ->limit(100);

            $candidates = $q->get();
            $votes = self::netVotes($candidates->pluck('id')->all());
            $now = time();

            $scored = [];
            foreach ($candidates as $c) {
                $base = $c->shared_stars * 8 + $c->shared_studios * 5 + $c->shared_tags * 3;
                $net = $votes[$c->id] ?? 0;
                $likeBonus = max(-5, min(10, $net)) * 1.5;                      // łapki: od -7.5 do +15
                $days = max(0, ($now - strtotime($c->created_at)) / 86400);
                $freshBonus = 4 * exp(-$days / 60);                              // świeżość: do +4
                $jitter = mt_rand(0, 200) / 100;                                 // lekkie tasowanie: 0-2

                $c->reason = 'similar';
                $c->net_votes = $net;
                $scored[] = ['score' => $base + $likeBonus + $freshBonus + $jitter, 'film' => $c];
            }

            usort($scored, function ($a, $b) { return $b['score'] <=> $a['score']; });

            foreach (array_slice($scored, 0, 12) as $row) {
                $lists['similar'][] = $row['film'];
                $picked[$row['film']->id] = true;
            }
        }

        $take = function ($query, $limit, $reason) use (&$picked, $filmId) {
            $rows = self::votesSelect($query)->limit($limit)->get();
            $out = [];
            foreach ($rows as $r) {
                if (isset($picked[$r->id])) { continue; }
                $r->reason = $reason;
                $picked[$r->id] = true;
                $out[] = $r;
            }
            return $out;
        };

        // ---- 2. najlepiej oceniane łapkami (losowe 2 z 10 najlepszych - nie zawsze te same)
        $topPool = self::votesSelect(self::base($filmId, array_keys($picked)))
            ->havingRaw('net_votes > 0')
            ->orderBy('net_votes', 'desc')->orderBy('films.id', 'desc')
            ->limit(20)->get()->all();
        shuffle($topPool);
        foreach (array_slice($topPool, 0, 5) as $r) {
            $r->reason = 'top';
            $picked[$r->id] = true;
            $lists['top'][] = $r;
        }

        // ---- 3. najnowsze
        $lists['new'] = $take(
            self::base($filmId, array_keys($picked))->orderBy('films.created_at', 'desc')->orderBy('films.id', 'desc'),
            5, 'new'
        );

        // ---- 4. losowe
        $lists['random'] = $take(
            self::base($filmId, array_keys($picked))->inRandomOrder(),
            5, 'random'
        );

        // ---- przeplatanie (najlepsze dopasowania na początku, między nimi top / nowe / losowe)
        $pattern = ['similar', 'similar', 'top', 'similar', 'new', 'similar', 'random'];
        $out = [];
        $guard = 0;
        while ($guard++ < 200 && array_sum(array_map('count', $lists)) > 0) {
            foreach ($pattern as $kind) {
                if (!empty($lists[$kind])) {
                    $out[] = array_shift($lists[$kind]);
                }
            }
        }

        // ---- uzupełnij losowymi do pełnej listy (np. mało tagów / nowa baza)
        if (count($out) < $total) {
            $exclude = array_merge([$filmId], array_map(function ($r) { return $r->id; }, $out));
            $more = self::votesSelect(self::base($filmId, $exclude))->inRandomOrder()->limit($total - count($out))->get();
            foreach ($more as $r) {
                $r->reason = 'random';
                $out[] = $r;
            }
        }

        mt_srand(); // przywróć losowe ziarno

        return collect(array_slice($out, 0, $total));
    }
}
