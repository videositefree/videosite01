<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Support\FilmSearch;
use App\Support\FilmSort;

class Filtrscontroller extends Controller
{
    // Jedna wyszukiwarka obsługuje i formularz z nagłówka (/search?search=...),
    // i panel filtrów (/search/method). Wcześniej były to dwie różne,
    // niespójne implementacje (jedna szukała tylko po tytule + tagach, druga
    // po tytule + tagach + gwiazdach + wytwórniach, każda inaczej).
    // Cała logika jest w App\Support\FilmSearch.

    public function relevance(Request $request)
    {
        $built = FilmSearch::build($request);
        $meta = $built['meta'];

        $films = FilmSort::paginate($built['query']);

        $search = $meta['search'];
        $sort = $meta['sort'];
        $date = $meta['date'];
        $time = $meta['time'];
        $ratingMin = $meta['ratingMin'];
        $ratingMax = $meta['ratingMax'];
        $match = $meta['match'];
        $searchMode = $meta['mode'];

        $selectedTags = count($meta['tagIds']) ? DB::table('tags')->whereIn('id', $meta['tagIds'])->orderBy('name')->get() : collect();
        $selectedStars = count($meta['starIds']) ? DB::table('stars')->whereIn('id', $meta['starIds'])->orderBy('name')->get() : collect();
        $selectedStudios = count($meta['studioIds']) ? DB::table('studios')->whereIn('id', $meta['studioIds'])->orderBy('name')->get() : collect();

        return view('sites.search', compact(
            'films', 'search', 'sort', 'date', 'time', 'ratingMin', 'ratingMax', 'match', 'searchMode',
            'selectedTags', 'selectedStars', 'selectedStudios'
        ));
    }
}
