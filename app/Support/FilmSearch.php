<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Jedna wyszukiwarka dla całego serwisu (formularz w nagłówku i panel filtrów).
 *
 * Zasady:
 *  - zapytanie jest dzielone na słowa; "cudzysłów" = fraza, minus przed słowem = wyklucz,
 *  - KAŻDE słowo musi pasować gdzieś (tytuł, tag, gwiazda, wytwórnia, tagi gwiazd, tagi wytwórni),
 *    ale różne słowa mogą pasować w różnych miejscach ("anna basen" = gwiazda Anna + tag basen),
 *  - gdy nic nie pasuje do wszystkich słów, pokazujemy filmy pasujące do CZĘŚCI słów (tryb 'any'),
 *  - wszystko jest robione przez EXISTS (bez JOIN-ów), więc nie ma duplikatów filmów,
 *  - wielkość liter i polskie znaki nie mają znaczenia (kolacja utf8mb4_unicode_ci),
 *  - znaki % i _ wpisane przez użytkownika są traktowane jak zwykłe znaki.
 */
class FilmSearch
{
    const MAX_TERMS = 8;

    /** @return array [ ['text' => 'anna', 'exclude' => false], ... ] */
    public static function parseTerms($input)
    {
        $input = trim(preg_replace('/\s+/u', ' ', (string) $input));
        if ($input === '') {
            return [];
        }

        preg_match_all('/(-?)"([^"]+)"|(\S+)/u', $input, $m, PREG_SET_ORDER);

        $terms = [];
        foreach ($m as $match) {
            if (isset($match[3]) && $match[3] !== '') {
                $text = $match[3];
                $exclude = false;
                if (mb_strlen($text) > 1 && mb_substr($text, 0, 1) === '-') {
                    $exclude = true;
                    $text = mb_substr($text, 1);
                }
            } else {
                $text = $match[2];
                $exclude = $match[1] === '-';
            }

            $text = trim($text, " \t\n\r\0\x0B\"");
            if ($text === '') {
                continue;
            }

            $terms[] = ['text' => $text, 'exclude' => $exclude];
            if (count($terms) >= self::MAX_TERMS) {
                break;
            }
        }

        return $terms;
    }

    private static function like($text)
    {
        return '%' . addcslashes($text, '\\%_') . '%';
    }

    /** Warunek "to słowo pasuje do filmu" - [sql, bindings]. */
    private static function termCondition($text)
    {
        $like = self::like($text);

        $sql = "(films.name LIKE ?"
            . " OR EXISTS (SELECT 1 FROM films_tags ft JOIN tags t ON t.id = ft.tag_id WHERE ft.film_id = films.id AND t.name LIKE ?)"
            . " OR EXISTS (SELECT 1 FROM films_stars fs JOIN stars s ON s.id = fs.stars_id WHERE fs.film_id = films.id AND s.name LIKE ?)"
            . " OR EXISTS (SELECT 1 FROM films_studios fo JOIN studios so ON so.id = fo.studios_id WHERE fo.film_id = films.id AND so.name LIKE ?)"
            . " OR EXISTS (SELECT 1 FROM films_stars fs2 JOIN stars_tags sgt ON sgt.star_id = fs2.stars_id AND sgt.tag_db = 0 JOIN tags_stars ts ON ts.id = sgt.tag_id WHERE fs2.film_id = films.id AND ts.name LIKE ?)"
            . " OR EXISTS (SELECT 1 FROM films_studios fo2 JOIN studios_tags stt ON stt.studio_id = fo2.studios_id AND stt.tag_db = 0 JOIN tags_studios tst ON tst.id = stt.tag_id WHERE fo2.film_id = films.id AND tst.name LIKE ?)"
            . ")";

        return [$sql, array_fill(0, 6, $like)];
    }

    /** Punkty trafności jednego słowa - [sql, bindings]. Tytuł waży najwięcej, potem gwiazda, tag, wytwórnia. */
    private static function termScore($text)
    {
        $like = self::like($text);

        $sql = "(CASE WHEN films.name LIKE ? THEN 10 ELSE 0 END)"
            . " + (CASE WHEN EXISTS (SELECT 1 FROM films_stars fs JOIN stars s ON s.id = fs.stars_id WHERE fs.film_id = films.id AND s.name LIKE ?) THEN 8 ELSE 0 END)"
            . " + (CASE WHEN EXISTS (SELECT 1 FROM films_tags ft JOIN tags t ON t.id = ft.tag_id WHERE ft.film_id = films.id AND t.name LIKE ?) THEN 6 ELSE 0 END)"
            . " + (CASE WHEN EXISTS (SELECT 1 FROM films_studios fo JOIN studios so ON so.id = fo.studios_id WHERE fo.film_id = films.id AND so.name LIKE ?) THEN 6 ELSE 0 END)"
            . " + (CASE WHEN EXISTS (SELECT 1 FROM films_stars fs2 JOIN stars_tags sgt ON sgt.star_id = fs2.stars_id AND sgt.tag_db = 0 JOIN tags_stars ts ON ts.id = sgt.tag_id WHERE fs2.film_id = films.id AND ts.name LIKE ?) THEN 2 ELSE 0 END)"
            . " + (CASE WHEN EXISTS (SELECT 1 FROM films_studios fo2 JOIN studios_tags stt ON stt.studio_id = fo2.studios_id AND stt.tag_db = 0 JOIN tags_studios tst ON tst.id = stt.tag_id WHERE fo2.film_id = films.id AND tst.name LIKE ?) THEN 2 ELSE 0 END)";

        return [$sql, array_fill(0, 6, $like)];
    }

    private static function addTextConditions($query, array $terms, $mode)
    {
        $include = [];
        foreach ($terms as $t) {
            if ($t['exclude']) {
                list($sql, $bind) = self::termCondition($t['text']);
                $query->whereRaw('NOT ' . $sql, $bind);
            } else {
                $include[] = $t;
            }
        }

        if (count($include) === 0) {
            return;
        }

        if ($mode === 'any' && count($include) > 1) {
            $query->where(function ($q) use ($include) {
                foreach ($include as $t) {
                    list($sql, $bind) = self::termCondition($t['text']);
                    $q->orWhereRaw($sql, $bind);
                }
            });
        } else {
            foreach ($include as $t) {
                list($sql, $bind) = self::termCondition($t['text']);
                $query->whereRaw($sql, $bind);
            }
        }
    }

    private static function addRelevance($query, array $terms, $rawPhrase)
    {
        $parts = [];
        $bindings = [];

        $phrase = trim(implode(' ', array_map(function ($t) { return $t['text']; },
            array_filter($terms, function ($t) { return !$t['exclude']; }))));

        if ($phrase !== '') {
            // cała fraza w tytule: dokładnie / na początku / gdziekolwiek
            $parts[] = "(CASE WHEN films.name = ? THEN 60 ELSE 0 END)";
            $bindings[] = $phrase;
            $parts[] = "(CASE WHEN films.name LIKE ? THEN 25 ELSE 0 END)";
            $bindings[] = addcslashes($phrase, '\\%_') . '%';
            $parts[] = "(CASE WHEN films.name LIKE ? THEN 15 ELSE 0 END)";
            $bindings[] = self::like($phrase);
        }

        foreach ($terms as $t) {
            if ($t['exclude']) {
                continue;
            }
            list($sql, $bind) = self::termScore($t['text']);
            $parts[] = $sql;
            $bindings = array_merge($bindings, $bind);
        }

        if (empty($parts)) {
            return false;
        }

        if (empty($query->columns)) {
            $query->select('films.*');
        }
        $query->selectRaw('(' . implode(' + ', $parts) . ') as relevance', $bindings);
        return true;
    }

    private static function idList($value)
    {
        return array_values(array_unique(array_filter(array_map('intval', (array) $value))));
    }

    /**
     * Buduje zapytanie wyszukiwarki.
     * @return array ['query' => Builder, 'meta' => [...dane dla widoku]]
     */
    public static function build(Request $request)
    {
        $rawSearch = trim((string) $request->input('search', ''));
        $terms = self::parseTerms($rawSearch);

        $date = $request->input('date');
        $time = $request->input('time');
        $match = $request->input('match') === 'all' ? 'all' : 'any';
        $ratingMin = $request->input('rating_min');
        $ratingMax = $request->input('rating_max');
        $tagIds = self::idList($request->input('tags'));
        $starIds = self::idList($request->input('stars'));
        $studioIds = self::idList($request->input('studios'));

        $context = 'search';
        $sort = $request->query('sort', $request->input('sort'));
        $sort = FilmSort::isValid($sort, $context) ? $sort : FilmSort::defaultKey($context);

        $make = function ($mode) use ($terms, $date, $time, $match, $ratingMin, $ratingMax, $tagIds, $starIds, $studioIds) {
            $query = DB::table('films')->where('films.activ', '=', '1');

            self::addTextConditions($query, $terms, $mode);

            // --- data dodania
            $days = ['today' => 3, 'week' => 7, 'month' => 30, 'year' => 365];
            if (isset($days[$date])) {
                $query->whereRaw('films.created_at >= NOW() - INTERVAL ' . (int) $days[$date] . ' DAY');
            }

            // --- długość (sekundy), zgodnie z opisami w formularzu
            $ranges = [
                '3-10min'    => [180, 600],
                '10-20min'   => [600, 1200],
                '20-40min'   => [1200, 2400],
                '40min_more' => [2400, null],
            ];
            if (isset($ranges[$time])) {
                list($min, $max) = $ranges[$time];
                if ($min !== null) { $query->where('films.duration', '>', $min); }
                if ($max !== null) { $query->where('films.duration', '<=', $max); }
            }

            // --- ocena
            if (is_numeric($ratingMin)) { $query->where('films.rating', '>=', (float) $ratingMin); }
            if (is_numeric($ratingMax)) { $query->where('films.rating', '<=', (float) $ratingMax); }

            // --- konkretne tagi / gwiazdy / wytwórnie: "dowolny" albo "wszystkie"
            $entity = function ($ids, $pivot, $col) use ($query, $match) {
                if (count($ids) === 0) { return; }

                $sub = DB::table($pivot)->select('film_id')->whereIn($col, $ids);
                if ($match === 'all') {
                    $sub->groupBy('film_id')->havingRaw('COUNT(DISTINCT ' . $col . ') = ?', [count($ids)]);
                }
                $query->whereIn('films.id', $sub);
            };
            $entity($tagIds, 'films_tags', 'tag_id');
            $entity($starIds, 'films_stars', 'stars_id');
            $entity($studioIds, 'films_studios', 'studios_id');

            return $query;
        };

        $mode = 'all';
        $query = $make('all');

        // brak wyników dla wszystkich słów naraz -> spróbuj z częścią słów
        $includeCount = count(array_filter($terms, function ($t) { return !$t['exclude']; }));
        if ($includeCount > 1 && (clone $query)->count() === 0) {
            $anyQuery = $make('any');
            if ((clone $anyQuery)->count() > 0) {
                $query = $anyQuery;
                $mode = 'any';
            }
        }

        // --- sortowanie
        $hasRelevance = false;
        if ($sort === 'relevance') {
            $hasRelevance = self::addRelevance($query, $terms, $rawSearch);
            if ($hasRelevance) {
                $query->orderBy('relevance', 'desc')
                      ->orderBy('films.created_at', 'desc')
                      ->orderBy('films.id', 'desc');
            } else {
                // trafność bez wpisanego tekstu nie ma sensu - najnowsze
                FilmSort::apply($query, 'newest', $request);
            }
        } else {
            FilmSort::apply($query, $sort, $request);
        }

        return [
            'query' => $query,
            'meta' => [
                'search' => $rawSearch,
                'terms' => $terms,
                'mode' => $mode,       // 'all' = wszystkie słowa, 'any' = część słów (zapasowy tryb)
                'match' => $match,
                'sort' => $sort,
                'date' => $date,
                'time' => $time,
                'ratingMin' => $ratingMin,
                'ratingMax' => $ratingMax,
                'tagIds' => $tagIds,
                'starIds' => $starIds,
                'studioIds' => $studioIds,
            ],
        ];
    }
}
