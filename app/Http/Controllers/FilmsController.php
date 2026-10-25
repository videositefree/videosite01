<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\films;

use App\stars_tags;

use App\Support\FilmSort;

class FilmsController extends Controller
{

    // =========================================================================================================
    // POMOCNICZE METODY QUERY — wcześniej każda z ~30 metod poniżej pisała ten
    // sam JOIN/WHERE od nowa (w sumie ponad 100 prawie identycznych bloków
    // zapytań w tym pliku). Wyciągnięte tutaj raz; każda publiczna metoda
    // dokłada tylko swój własny orderBy()/inRandomOrder(). Nazwy metod
    // publicznych, routing i nazwy zmiennych przekazywanych do widoków
    // zostały bez zmian — to nie jest przebudowa API, tylko usunięcie
    // duplikacji w środku.
    // =========================================================================================================

    private function activeFilmsQuery()
    {
        return DB::table('films')
            ->where('activ', '=', '1')
            ->distinct();
    }

    private function tagFilmsQuery($id)
    {
        return DB::table('tags')
            ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
            ->join('films', 'films.id', '=', 'films_tags.film_id')
            ->select('films.*')
            ->where('tags.id', $id)
            ->where('activ', '=', '1')
            ->distinct();
    }

    private function starFilmsQuery($id)
    {
        return DB::table('stars')
            ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
            ->join('films', 'films.id', '=', 'films_stars.film_id')
            ->select('films.*')
            ->where('stars.id', $id)
            ->where('activ', '=', '1')
            ->distinct();
    }

    private function studioFilmsQuery($id)
    {
        return DB::table('studios')
            ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
            ->join('films', 'films.id', '=', 'films_studios.film_id')
            ->select('films.*')
            ->where('studios.id', $id)
            ->where('activ', '=', '1')
            ->distinct();
    }

    // tagi przypisane do gwiazdy (własne + odziedziczone z bazy tagów filmowych)
    // + ich liczniki, wykorzystywane przez wszystkie warianty select_stars_*
    private function starExtras($id)
    {
        $tags_stars = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
            ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
            ->where('stars.id', $id)
            ->where('stars_tags.tag_db', 0)
            ->orderBy('stars.name', 'ASC')
            ->get();

        $tags_stars_films = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
            ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
            ->where('stars.id', $id)
            ->where('stars_tags.tag_db', 1)
            ->orderBy('stars.name', 'ASC')
            ->get();

        $tags_stars_count = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
            ->where('stars.id', $id)
            ->where('stars_tags.tag_db', 0)
            ->count();

        $tags_stars_films_count = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
            ->where('stars.id', $id)
            ->where('stars_tags.tag_db', 1)
            ->count();

        $stars = DB::table('stars')->where('id', $id)->first();

        return [
            'tags_stars' => $tags_stars,
            'tags_stars_films' => $tags_stars_films,
            'tags_stars_count' => $tags_stars_count,
            'tags_stars_films_count' => $tags_stars_films_count,
            'stars' => $stars,
        ];
    }

    // to samo co starExtras(), dla wytwórni
    private function studioExtras($id)
    {
        $tags_studios = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
            ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 0)
            ->orderBy('studios.name', 'ASC')
            ->get();

        $tags_studios_films = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
            ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 1)
            ->orderBy('studios.name', 'ASC')
            ->get();

        $tags_studios_count = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 0)
            ->count();

        $tags_studios_films_count = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 1)
            ->count();

        $studios = DB::table('studios')->where('id', $id)->first();

        return [
            'tags_studios' => $tags_studios,
            'tags_studios_films' => $tags_studios_films,
            'tags_studios_count' => $tags_studios_count,
            'tags_studios_films_count' => $tags_studios_films_count,
            'studios' => $studios,
        ];
    }


    // =========================================================================================================
    // STRONA GŁÓWNA
    // =========================================================================================================

    public function index()
    {

        //====================================================== check exist database and connection ==============================================//


        try {
            $db_host = env('DB_HOST');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $db_database = env('DB_DATABASE');

            if(env('DB_DATABASE')) {
                $db_host = env('DB_HOST');
                $db_username = env('DB_USERNAME');
                $db_password = env('DB_PASSWORD');

                $db = new PDO("mysql:host=$db_host", $db_username, $db_password);

            }
            else
            {
            $db = new PDO("mysql:host=$db_host", $db_username, $db_password);
            }

        } catch (\Exception $e) {
            return redirect(url('/connection_db'))->with('errors', 'Błąd połączenia z bazą danych.');
        }

        if(env('DB_DATABASE')) {


            try {
                $db_name = env('DB_DATABASE');
                $pdo = DB::connection()->getPdo();
                $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
                $result = $stmt->fetch();
                $count = $result[0];

            }
            catch (\Exception $e) {
                return view('install.manually_create_db');
            }


            if(isset($count)){
                if($count === 1){

                    if (!Schema::hasTable('films', 'tags', 'stars', 'studios', 'films_tags', 'films_stars ', 'films_studios', 'users')){
                        return view('install.manually_db');
                    }
                }
            }else{
                return redirect(url('/connection_db'))
                ->with('errors', "Brak bazy danych o nazwie $db_database");
            }


        }
        else
        {
            return view('install.manually');
        }

        // ========================================================== END ===================================================================== //

        $films = FilmSort::paginate(FilmSort::apply($this->activeFilmsQuery()));

        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort

        return view('sites.index', compact('films', 'count_films', 'films_check'));

    }


    //==================================================================== SORT FILMS BY =========================================================== //

    public function index_asc()
    {
        $films = $this->activeFilmsQuery()->orderBy('created_at', 'asc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_asc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_asc'));
    }

    public function index_name_asc()
    {
        $films = $this->activeFilmsQuery()->orderBy('name', 'asc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_name_asc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_name_asc'));
    }

    public function index_name_desc()
    {
        $films = $this->activeFilmsQuery()->orderBy('name', 'desc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_name_desc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_name_desc'));
    }

    public function index_rating_asc()
    {
        $films = $this->activeFilmsQuery()->orderBy('rating', 'asc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_rating_asc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_rating_asc'));
    }

    public function index_rating_desc()
    {
        $films = $this->activeFilmsQuery()->orderBy('rating', 'desc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_rating_desc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_rating_desc'));
    }

    public function index_duration_asc()
    {
        $films = $this->activeFilmsQuery()->orderBy('duration', 'ASC')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_duration_asc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_duration_asc'));
    }

    public function index_duration_desc()
    {
        $films = $this->activeFilmsQuery()->orderBy('duration', 'desc')->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_duration_desc = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_duration_desc'));
    }

    public function index_random()
    {
        $films = $this->activeFilmsQuery()->inRandomOrder()->paginate(27);
        $count_films = DB::table('films')->count();
        $films_check = 1;
        $index_random = 1;
        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_random'));
    }


    public function index_random_film_watch(Request $request)
    {
        // Wcześniej było tu ->limit(1)->first() bez żadnego sortowania, więc
        // zawsze wracał ten sam (pierwszy) film. Teraz prawdziwe losowanie.
        // Bez distinct() - w trybie ONLY_FULL_GROUP_BY MySQL nie pozwala łączyć
        // DISTINCT z ORDER BY RAND().
        $base = DB::table('films')->where('activ', '=', '1');

        if ((clone $base)->count() === 0) {
            return redirect('/')->with('error', 'Brak dostępnych filmów.');
        }

        // nie losujemy ponownie filmu, który właśnie oglądasz / który wylosowaliśmy ostatnio
        $exclude = array_filter([
            (int) $request->query('from'),
            (int) session('last_random_film'),
        ]);

        $query = clone $base;
        if (count($exclude) > 0 && (clone $base)->whereNotIn('id', $exclude)->exists()) {
            $query->whereNotIn('id', $exclude);
        }

        $film = $query->inRandomOrder()->select('id')->first();

        session(['last_random_film' => $film->id]);

        return redirect()->route('watch', $film->id)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    // ==================================================================== VOTES (kciuk w górę / w dół) ================================ //
    // Bez logowania. Głos trzymamy w bazie (film_votes) i w sesji. Nowa przeglądarka
    // = nowa sesja = można zagłosować ponownie. Ponowne kliknięcie tego samego
    // przycisku cofa głos, kliknięcie drugiego zmienia głos.

    private function voteCounts($filmId)
    {
        $row = DB::table('film_votes')
            ->where('film_id', $filmId)
            ->selectRaw('SUM(vote = 1) as up, SUM(vote = -1) as down')
            ->first();

        return [
            'up' => (int) ($row->up ?? 0),
            'down' => (int) ($row->down ?? 0),
        ];
    }

    public function vote(Request $request, $id)
    {
        if (!DB::table('films')->where('id', $id)->exists()) {
            return response()->json(['error' => 'Brak filmu.'], 404);
        }

        $direction = $request->input('vote');
        if (!in_array($direction, ['up', 'down'], true)) {
            return response()->json(['error' => 'Niepoprawny głos.'], 422);
        }
        $value = $direction === 'up' ? 1 : -1;

        $sessionId = session()->getId();
        $votes = session('film_votes', []);
        $current = $votes[$id] ?? 0;

        if ($current === $value) {
            // ten sam przycisk drugi raz = cofnięcie głosu
            DB::table('film_votes')->where('film_id', $id)->where('session_id', $sessionId)->delete();
            unset($votes[$id]);
            $mine = 0;
        } else {
            $exists = DB::table('film_votes')->where('film_id', $id)->where('session_id', $sessionId)->exists();
            if ($exists) {
                DB::table('film_votes')->where('film_id', $id)->where('session_id', $sessionId)
                    ->update(['vote' => $value, 'updated_at' => now()]);
            } else {
                DB::table('film_votes')->insert([
                    'film_id' => $id,
                    'session_id' => $sessionId,
                    'vote' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $votes[$id] = $value;
            $mine = $value;
        }

        session(['film_votes' => $votes]);

        return response()->json($this->voteCounts($id) + ['mine' => $mine]);
    }

    // ==================================================================== END =========================================================== //



    // ==================================================================== WATCH FILMS =========================================================== //
    public function watch($id)
    {

        $films = films::find($id);

        if($films === null){
            return redirect('/')->with('error', 'Brak rekordu w bazie danych.');
        }

        $tags = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'films_tags.id', 'tags.id')
        ->where('films.id', $id)
        ->distinct()
        ->orderBy('tags.name', 'ASC')
        ->get();


        $stars = DB::table('films')
        ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
        ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
        ->select('stars.name', 'films_stars.id', 'stars.id')
        ->where('films.id', $id)
        ->orderBy('stars.name', 'ASC')
        ->distinct()
        ->get();

        $stars_id_count = $stars->count();

        if ($stars_id_count > 0){
            $first_star_id = $stars->last()->id;

            $stars_tags = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
            ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
            ->where('stars.id', $first_star_id)
            ->where('stars_tags.tag_db', 0)
            ->orderBy('stars.name', 'ASC')
            ->get();
        }
        else
        {
            $stars_tags = 0;
        }


        $studios = DB::table('films')
        ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
        ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
        ->select('studios.name', 'films_studios.id', 'studios.id')
        ->where('films.id', $id)
        ->orderBy('studios.name', 'ASC')
        ->distinct()
        ->get();

        $studios_id_count = $studios->count();

        if($studios_id_count > 0){

            $studios_tags = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
            ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 0)
            ->orderBy('studios.name', 'ASC')
            ->get();
        }
        else
        {
            $studios_tags = 0;
        }


        $votes = $this->voteCounts($id);
        $my_vote = (int) (session('film_votes', [])[$id] ?? 0);

        $related = \App\Support\RelatedFilms::for($films);

        return view('sites.watch', compact('films', 'tags', 'stars', 'stars_tags', 'studios', 'studios_tags', 'votes', 'my_vote', 'related'));

    }
    // ==================================================================== END =========================================================== //


    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME! ==================================== //

    public function select_categories($id)
    {
        $hidden_id_tags = $id;

        $films = FilmSort::paginate(FilmSort::apply($this->tagFilmsQuery($id)));
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags'));
    }

    // =============================================================== FILTRS ===================================================================

    public function select_categories_asc($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->orderBy('created_at', 'asc')->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_old = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_old'));
    }

    public function select_categories_films_asc($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->orderBy('films.name', 'asc')->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_name_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_name_asc'));
    }

    public function select_categories_films_desc($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->orderBy('films.name', 'desc')->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_name_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_name_desc'));
    }

    public function select_categories_films_stars_asc($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->orderBy('films.rating', 'asc')->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_stars_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_stars_asc'));
    }

    public function select_categories_films_stars_desc($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->orderBy('films.rating', 'desc')->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_stars_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_stars_desc'));
    }

    public function select_categories_random($id)
    {
        $hidden_id_tags = $id;

        $films = $this->tagFilmsQuery($id)->inRandomOrder()->paginate(27);
        $count_films = $this->tagFilmsQuery($id)->count();
        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_random = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_random'));
    }


    // ============================================================= END =====================================================================


    public function select_stars($id)
    {
        $films = FilmSort::paginate(FilmSort::apply($this->starFilmsQuery($id)));
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars'
        ) + $extra);
    }


    // =============================================================== FILTRS ===================================================================


    public function select_stars_asc($id)
    {
        $films = $this->starFilmsQuery($id)->orderBy('created_at', 'asc')->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_old = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_old'
        ) + $extra);
    }

    public function select_stars_films_asc($id)
    {
        $films = $this->starFilmsQuery($id)->orderBy('films.name', 'asc')->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_name_asc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_name_asc'
        ) + $extra);
    }

    public function select_stars_films_desc($id)
    {
        $films = $this->starFilmsQuery($id)->orderBy('films.name', 'desc')->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_name_desc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_name_desc'
        ) + $extra);
    }

    public function select_stars_films_stars_asc($id)
    {
        $films = $this->starFilmsQuery($id)->orderBy('films.rating', 'asc')->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_stars_asc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_stars_asc'
        ) + $extra);
    }

    public function select_stars_films_stars_desc($id)
    {
        $films = $this->starFilmsQuery($id)->orderBy('films.rating', 'desc')->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_stars_desc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_stars_desc'
        ) + $extra);
    }

    public function select_stars_random($id)
    {
        $films = $this->starFilmsQuery($id)->inRandomOrder()->paginate(27);
        $count_films = $this->starFilmsQuery($id)->count();
        $extra = $this->starExtras($id);

        $hidden_id_stars = $id;
        $select_star_random = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_stars', 'select_star_random'
        ) + $extra);
    }


    // ================================================================== END ===================================================================


    public function select_studios($id)
    {
        $films = FilmSort::paginate(FilmSort::apply($this->studioFilmsQuery($id)));
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios'
        ) + $extra);
    }

    // =============================================================== FILTRS ===================================================================

    public function select_studios_asc($id)
    {
        $films = $this->studioFilmsQuery($id)->orderBy('created_at', 'asc')->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_old = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_old'
        ) + $extra);
    }

    public function select_studios_films_asc($id)
    {
        $films = $this->studioFilmsQuery($id)->orderBy('films.name', 'asc')->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_name_asc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_name_asc'
        ) + $extra);
    }

    public function select_studios_films_desc($id)
    {
        $films = $this->studioFilmsQuery($id)->orderBy('films.name', 'desc')->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_name_desc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_name_desc'
        ) + $extra);
    }

    public function select_studios_films_stars_asc($id)
    {
        $films = $this->studioFilmsQuery($id)->orderBy('films.rating', 'asc')->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_stars_asc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_stars_asc'
        ) + $extra);
    }

    public function select_studios_films_stars_desc($id)
    {
        $films = $this->studioFilmsQuery($id)->orderBy('films.rating', 'desc')->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_stars_desc = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_stars_desc'
        ) + $extra);
    }

    public function select_studios_random($id)
    {
        $films = $this->studioFilmsQuery($id)->inRandomOrder()->paginate(27);
        $count_films = $this->studioFilmsQuery($id)->count();
        $extra = $this->studioExtras($id);

        $hidden_id_studios = $id;
        $select_studio_random = 0;

        return view('sites.index', compact(
            'films', 'count_films', 'hidden_id_studios', 'select_studio_random'
        ) + $extra);
    }

    // ==================================================================== END =========================================================== //

}
