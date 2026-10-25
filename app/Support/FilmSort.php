<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * JEDNO miejsce, które definiuje sortowanie filmów w całym serwisie:
 * lista opcji, domyślne sortowanie (z bazy), zastosowanie sortowania do zapytania
 * oraz adresy linków. Używają go wszystkie kontrolery i wszystkie widoki
 * (partial partials/film_sort.blade.php), więc każda lista filmów działa tak samo.
 */
class FilmSort
{
    const SETTING_KEY = 'default_film_sort';
    const FALLBACK = 'newest';

    /** Podstrony, dla których można ustawić osobne sortowanie domyślne. */
    public static function contexts()
    {
        return [
            'home'   => 'Strona główna',
            'tag'    => 'Strona tagu',
            'star'   => 'Strona gwiazdy',
            'studio' => 'Strona wytwórni',
            'search' => 'Wyszukiwarka',
        ];
    }

    /** Na której podstronie jesteśmy (na podstawie adresu). */
    public static function context(Request $request = null)
    {
        $request = $request ?: request();
        $path = trim($request->path(), '/');

        if (strpos($path, 'select_categories') === 0) { return 'tag'; }
        if (strpos($path, 'select_stars') === 0)      { return 'star'; }
        if (strpos($path, 'select_studios') === 0)    { return 'studio'; }
        if (strpos($path, 'search') === 0)            { return 'search'; }
        return 'home';
    }

    /**
     * klucz => [etykieta]. Kolejność = kolejność w menu.
     * "relevance" (trafność) istnieje tylko w wyszukiwarce - poza nią nie ma czego dopasowywać.
     */
    public static function options($context = null)
    {
        $options = [
            'newest'        => ['label' => 'Data dodania – najnowsze'],
            'oldest'        => ['label' => 'Data dodania – najstarsze'],
            'name_asc'      => ['label' => 'Nazwa A – Z'],
            'name_desc'     => ['label' => 'Nazwa Z – A'],
            'rating_desc'   => ['label' => 'Ocena – od najwyższej'],
            'rating_asc'    => ['label' => 'Ocena – od najniższej'],
            'duration_desc' => ['label' => 'Długość – najdłuższe'],
            'duration_asc'  => ['label' => 'Długość – najkrótsze'],
            'score_desc'    => ['label' => 'Łapki – najlepszy wynik (w górę minus w dół)'],
            'likes_desc'    => ['label' => 'Łapki w górę – najwięcej'],
            'dislikes_desc' => ['label' => 'Łapki w dół – najwięcej'],
            'random'        => ['label' => 'Losowo'],
        ];

        if ($context === 'search') {
            $options = ['relevance' => ['label' => 'Trafność – najlepsze dopasowanie']] + $options;
        }

        return $options;
    }

    public static function labels($context = null)
    {
        $out = [];
        foreach (self::options($context) as $key => $opt) {
            $out[$key] = $opt['label'];
        }
        return $out;
    }

    public static function isValid($key, $context = null)
    {
        return is_string($key) && array_key_exists($key, self::options($context));
    }

    // ---------------------------------------------------------------- ustawienia w bazie (app_settings)

    private static $settingsCache = null;

    private static function settings()
    {
        if (self::$settingsCache === null) {
            self::$settingsCache = [];
            try {
                if (Schema::hasTable('app_settings')) {
                    self::$settingsCache = DB::table('app_settings')->pluck('value', 'key')->all();
                }
            } catch (\Throwable $e) {
                // brak bazy / tabeli - zostają wartości domyślne
            }
        }
        return self::$settingsCache;
    }

    public static function setting($key, $default = null)
    {
        $all = self::settings();
        return array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '' ? $all[$key] : $default;
    }

    public static function saveSetting($key, $value)
    {
        $exists = DB::table('app_settings')->where('key', $key)->exists();
        if ($exists) {
            DB::table('app_settings')->where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
        } else {
            DB::table('app_settings')->insert([
                'key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        self::$settingsCache = null;
    }

    /** 'all' = to samo sortowanie wszędzie, 'separate' = osobne ustawienie dla każdej podstrony. */
    public static function mode()
    {
        return self::setting('film_sort_mode', 'all') === 'separate' ? 'separate' : 'all';
    }

    /** Ustawienie ogólne (dla wszystkich podstron). */
    public static function globalKey()
    {
        $value = self::setting(self::SETTING_KEY);
        return self::isValid($value) ? $value : self::FALLBACK;
    }

    /** Ustawienie jednej podstrony (pusty string = brak, czyli obowiązuje ogólne). */
    public static function contextKey($context)
    {
        $value = self::setting('film_sort_' . $context);
        return self::isValid($value, $context) ? $value : '';
    }

    /** Domyślne sortowanie dla danej podstrony: osobne (jeśli tryb "osobno") albo ogólne. */
    public static function defaultKey($context = null)
    {
        $context = $context ?: self::context();

        if (self::mode() === 'separate') {
            $own = self::contextKey($context);
            if ($own !== '') {
                return $own;
            }
        }

        return self::globalKey();
    }

    /** Aktualnie wybrane sortowanie: ?sort=... albo domyślne dla tej podstrony. */
    public static function current(Request $request = null)
    {
        $request = $request ?: request();
        $context = self::context($request);
        $key = $request->query('sort');
        return self::isValid($key, $context) ? $key : self::defaultKey($context);
    }

    public static function label($key)
    {
        $options = self::options('search'); // najszersza lista (z "trafnością")
        return isset($options[$key]) ? $options[$key]['label'] : $options[self::FALLBACK]['label'];
    }

    /** Link do tej samej strony z innym sortowaniem (reszta parametrów zostaje, numer strony wraca do 1). */
    public static function url($key, Request $request = null)
    {
        $request = $request ?: request();
        $params = ['sort' => $key, 'page' => null, 'reseed' => null];
        if ($key === 'random') {
            $params['reseed'] = 1; // wybranie "Losowo" = nowa kolejność
        }
        return $request->fullUrlWithQuery($params);
    }

    /** Kolejność losowa musi być stała podczas przechodzenia między stronami - stąd ziarno w sesji. */
    private static function randomSeed(Request $request)
    {
        $seed = session('film_random_seed');
        if (!$seed || $request->query('reseed')) {
            $seed = mt_rand(1, 2147483646);
            session(['film_random_seed' => $seed]);
        }
        return (int) $seed;
    }

    private static function votesSub($kind)
    {
        $sub = DB::table('film_votes')->whereColumn('film_votes.film_id', 'films.id');

        if ($kind === 'likes') {
            return $sub->where('film_votes.vote', 1)->selectRaw('COUNT(*)');
        }
        if ($kind === 'dislikes') {
            return $sub->where('film_votes.vote', -1)->selectRaw('COUNT(*)');
        }
        return $sub->selectRaw('COALESCE(SUM(film_votes.vote), 0)');
    }

    /**
     * Dokłada ORDER BY do zapytania na tabeli films (z dowolnymi joinami).
     * Zawsze dodaje films.id jako ostatnie kryterium, żeby kolejność między stronami była stabilna.
     */
    public static function apply($query, $key = null, Request $request = null)
    {
        $request = $request ?: request();
        $key = self::isValid($key, self::context($request)) ? $key : self::current($request);
        if ($key === 'relevance') {
            $key = 'newest'; // trafność liczy wyszukiwarka (App\Support\FilmSearch), tu jej nie ma
        }

        // nie mieszamy z kolejnością ustawioną wcześniej w zapytaniu
        $query->reorder();

        switch ($key) {
            case 'oldest':
                $query->orderBy('films.created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('films.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('films.name', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('films.rating', 'desc');
                break;
            case 'rating_asc':
                $query->orderBy('films.rating', 'asc');
                break;
            case 'duration_desc':
                $query->orderBy('films.duration', 'desc');
                break;
            case 'duration_asc':
                $query->orderBy('films.duration', 'asc');
                break;
            case 'score_desc':
            case 'likes_desc':
            case 'dislikes_desc':
                $kind = $key === 'likes_desc' ? 'likes' : ($key === 'dislikes_desc' ? 'dislikes' : 'score');
                $alias = $kind . '_count';

                // kolumna użyta w ORDER BY musi być na liście SELECT (wymóg DISTINCT w MySQL)
                if (empty($query->columns)) {
                    $query->select('films.*');
                }
                $query->selectSub(self::votesSub($kind), $alias);
                $query->orderBy($alias, 'desc');
                break;
            case 'random':
                $query->distinct(false); // DISTINCT + ORDER BY RAND() = błąd w trybie ONLY_FULL_GROUP_BY
                $query->orderByRaw('RAND(' . self::randomSeed($request) . ')');
                break;
            case 'newest':
            default:
                $query->orderBy('films.created_at', 'desc');
                break;
        }

        $query->orderBy('films.id', 'desc');

        return $query;
    }

    /** Paginacja zachowująca ?sort=... w linkach do kolejnych stron. */
    public static function paginate($query, $perPage = 27, Request $request = null)
    {
        $request = $request ?: request();
        return $query->paginate($perPage)->appends($request->except(['page', 'reseed']));
    }
}
