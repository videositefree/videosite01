<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;

use App\films;

use App\tags;

use App\stars;

use App\studios;

class Filtrscontroller extends Controller
{

    // Wcześniej ta metoda budowała osobny, ręcznie skopiowany blok zapytania
    // dla KAŻDEJ kombinacji sort/date/time (~50 prawie identycznych bloków).
    // Efekt: część kombinacji (np. sort+time bez daty) w ogóle nie była
    // obsłużona, a gdy formularz trafiał tu bez zaznaczonego żadnego radio
    // buttona, zmienna $films nigdy nie była ustawiona i strona się wywalała.
    // Teraz zapytanie budowane jest przyrostowo — każdy filtr dokłada własny
    // warunek niezależnie od pozostałych, więc każda kombinacja (i brak
    // kombinacji) działa tak samo poprawnie.
    public function relevance(Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort');
        $date   = $request->input('date');
        $time   = $request->input('time');

        // nowe filtry: zakres oceny + konkretne tagi/gwiazdy/wytwórnie
        $ratingMin = $request->input('rating_min');
        $ratingMax = $request->input('rating_max');
        $tagIds    = array_filter((array) $request->input('tags', []));
        $starIds   = array_filter((array) $request->input('stars', []));
        $studioIds = array_filter((array) $request->input('studios', []));

        $dateIntervals = [
            'today'  => '3 DAY',
            'week'   => '7 DAY',
            'month'  => '30 DAY',
            '3month' => '3 MONTH',
            '6month' => '6 MONTH',
        ];

        // [dolna_granica_wylaczna, gorna_granica_wlaczna] w sekundach
        $durationRanges = [
            '3-10min'    => [null, 600],
            '10-20min'   => [600, 1200],
            '20-40min'   => [1200, 2400],
            '40min_more' => [2400, null],
        ];

        $usesTagJoin = ($sort === 'relevance');

        $query = DB::table('films')->where('films.activ', '=', '1');

        if ($usesTagJoin) {
            $query->join('films_tags', 'films_tags.film_id', '=', 'films.id')
                  ->join('tags', 'tags.id', '=', 'films_tags.tag_id')
                  ->select('films.*')
                  ->where(function ($q) use ($search) {
                      $q->where('films.name', 'like', '%'.$search.'%')
                        ->orWhere('tags.name', 'like', '%'.$search.'%');
                  });
        } else {
            $query->where('films.name', 'like', '%'.$search.'%');
        }

        if (!empty($date) && $date !== 'all' && isset($dateIntervals[$date])) {
            $query->where('films.created_at', '>', DB::raw('NOW() - INTERVAL '.$dateIntervals[$date]));
        }

        if (!empty($time) && isset($durationRanges[$time])) {
            [$min, $max] = $durationRanges[$time];
            if ($min !== null) {
                $query->where('films.duration', '>', $min);
            }
            if ($max !== null) {
                $query->where('films.duration', '<=', $max);
            }
        }

        if ($ratingMin !== null && $ratingMin !== '') {
            $query->where('films.rating', '>=', $ratingMin);
        }
        if ($ratingMax !== null && $ratingMax !== '') {
            $query->where('films.rating', '<=', $ratingMax);
        }

        // film musi mieć PRZYNAJMNIEJ JEDEN z zaznaczonych tagów (i analogicznie
        // dla gwiazd/wytwórni) — kategorie łączone są ze sobą przez AND, więc
        // wybranie tagu X i gwiazdy Y zwróci filmy pasujące do obu naraz
        if (!empty($tagIds)) {
            $query->whereIn('films.id', function ($sub) use ($tagIds) {
                $sub->select('film_id')->from('films_tags')->whereIn('tag_id', $tagIds);
            });
        }
        if (!empty($starIds)) {
            $query->whereIn('films.id', function ($sub) use ($starIds) {
                $sub->select('film_id')->from('films_stars')->whereIn('stars_id', $starIds);
            });
        }
        if (!empty($studioIds)) {
            $query->whereIn('films.id', function ($sub) use ($studioIds) {
                $sub->select('film_id')->from('films_studios')->whereIn('studios_id', $studioIds);
            });
        }

        switch ($sort) {
            case 'rating':
                $query->orderBy('films.rating', 'desc');
                break;
            case 'length':
                $query->orderBy('films.duration', 'desc');
                break;
            case 'uploaddate':
            default:
                $query->orderBy('films.created_at', 'desc');
                break;
        }

        $films = $query->distinct()->paginate(27);

        // wybrane tagi/gwiazdy/wytwórnie z nazwami — żeby formularz mógł
        // pokazać je z powrotem jako "pigułki" po odświeżeniu strony
        $selectedTags = !empty($tagIds) ? DB::table('tags')->whereIn('id', $tagIds)->get() : collect();
        $selectedStars = !empty($starIds) ? DB::table('stars')->whereIn('id', $starIds)->get() : collect();
        $selectedStudios = !empty($studioIds) ? DB::table('studios')->whereIn('id', $studioIds)->get() : collect();

        return view('sites.search', compact(
            'films', 'sort', 'date', 'time', 'search',
            'ratingMin', 'ratingMax', 'selectedTags', 'selectedStars', 'selectedStudios'
        ));
    }

}
