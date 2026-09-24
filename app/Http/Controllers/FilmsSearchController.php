<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\films;

use App\tags;

use App\stars;

use App\studios;

class FilmsSearchController extends Controller
{
  
    
    public function add_films(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('authsites.add_films');
        }else{
          
            return view('authsites.add_films')->with('errorsMsg',"UWAGA!!! ");
           
        }

    }

    public function search(Request $request)
    {

        $search = $request -> input('search');

        // Poprzednio wyszukiwanie sprawdzało wyłącznie tytuł filmu (kolumna
        // "name"), więc szukanie po nazwisku aktora, tagu czy wytwórni nie
        // dawało żadnych wyników mimo że pasujący film istniał. Teraz szukamy
        // jednocześnie w tytule, tagach, gwiazdach i wytwórniach.
        $filmIds = DB::table('films')
            ->leftJoin('films_tags', 'films_tags.film_id', '=', 'films.id')
            ->leftJoin('tags', 'tags.id', '=', 'films_tags.tag_id')
            ->leftJoin('films_stars', 'films_stars.film_id', '=', 'films.id')
            ->leftJoin('stars', 'stars.id', '=', 'films_stars.stars_id')
            ->leftJoin('films_studios', 'films_studios.film_id', '=', 'films.id')
            ->leftJoin('studios', 'studios.id', '=', 'films_studios.studios_id')
            ->where('films.activ', '=', '1')
            ->where(function ($query) use ($search) {
                $query->where('films.name', 'like', '%'.$search.'%')
                    ->orWhere('tags.name', 'like', '%'.$search.'%')
                    ->orWhere('stars.name', 'like', '%'.$search.'%')
                    ->orWhere('studios.name', 'like', '%'.$search.'%');
            })
            ->select('films.id')
            ->distinct();

        $films = DB::table('films')
            ->whereIn('id', $filmIds)
            ->where('activ', '=', '1')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->paginate(27);

        return view('sites.search', compact('films', 'search'));

    }




}
