<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags;

use Image;


class TagsController extends Controller
{

    // =========================================================================================================
    // POMOCNICZE METODY QUERY — jak w FilmsController: usuwa duplikację
    // powtórzoną w każdej z metod sortujących poniżej.
    // =========================================================================================================

    private function tagsQuery()
    {
        return DB::table('tags')->distinct();
    }

    private function starTagsQuery()
    {
        return DB::table('tags')
            ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
            ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
            ->select('tags.*')
            ->where('stars_tags.tag_db', 1)
            ->distinct('tags.id');
    }

    private function studioTagsQuery()
    {
        return DB::table('tags')
            ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
            ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
            ->select('tags.*')
            ->where('studios_tags.tag_db', 1)
            ->distinct('tags.id');
    }

    // Karta encji w tym samym stylu co reszta serwisu (motyw "Marquee"),
    // zamiast starego, nieostylowanego markupu Bootstrapa wklejanego
    // bezpośrednio z kontrolera.
    private function renderEntityCard($url, $thumbnail, $count, $name)
    {
        return '
            <a href="'.$url.'" class="entity-card">
                <div class="entity-card__media">
                    <img src="'.$thumbnail.'" alt="'.htmlspecialchars($name).'" loading="lazy">
                    <div class="film_number"><i class="fas fa-video"></i>&nbsp;&nbsp;'.$count.'</div>
                </div>
                <div class="entity-card__body">'.htmlspecialchars($name).'</div>
            </a>
        ';
    }

    private function renderEmptyResult()
    {
        return '
            <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
                <div class="alert alert-danger">
                    <ul>
                        Przepraszamy ale nie mamy tego czego szukasz :/
                    </ul>
                </div>
            </div>
        ';
    }


    // =========================================================================================================
    // TAGI FILMÓW
    // =========================================================================================================

    public function categories()
    {
        $tags = $this->tagsQuery()->orderBy('created_at', 'DESC')->paginate(28);
        $count_tags = DB::table('tags')->count();
        $categories = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories'));
    }

    //==================================================================== SORT categories =========================================================== //

    public function categories_asc()
    {
        $tags = $this->tagsQuery()->orderBy('id', 'ASC')->paginate(28);
        $count_tags = DB::table('tags')->count();
        $categories_asc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_asc'));
    }

    public function categories_name_asc()
    {
        $tags = $this->tagsQuery()->orderBy('name', 'ASC')->paginate(28);
        $count_tags = DB::table('tags')->count();
        $categories_name_asc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_name_asc'));
    }

    public function categories_name_desc()
    {
        $tags = $this->tagsQuery()->orderBy('name', 'DESC')->paginate(28);
        $count_tags = DB::table('tags')->count();
        $categories_name_desc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_name_desc'));
    }

    public function categories_user_random()
    {
        $tags = $this->tagsQuery()->inRandomOrder()->paginate(28);
        $count_tags = DB::table('tags')->count();
        $categories_user_random_info = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_user_random_info'));
    }

    //==================================================================== END =========================================================== //


    // ============================================================ Tags films use in stars ====================================================== //

    public function tags_stars_db_film()
    {
        $tags = $this->starTagsQuery()->orderBy('id', 'DESC')->paginate(27);
        $count_tags = $this->starTagsQuery()->count();

        $categories = 1;
        $stars_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories', 'stars_db_films'));
    }


    public function searchtag_tags_stars_db_film(Request $request)
    {
        $searchTerm = $request->get('query');

        $tags = $this->starTagsQuery()
            ->where('tags.name', 'like', '%'.$searchTerm.'%')
            ->take(4)
            ->get();

        if ($tags->count() > 0) {

            foreach ($tags as $tag) {
                $count_films = DB::table('tags')
                    ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
                    ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
                    ->select('stars.*')
                    ->where('tags.id', $tag->id)
                    ->where('stars_tags.tag_db', 1)
                    ->distinct()
                    ->count();

                $url_films = url('/select_categories_stars_db_films', $tag->id);

                echo $this->renderEntityCard($url_films, $tag->thumbnail, $count_films, $tag->name);
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    //==================================================================== SORT Tags films use in stars BY  =========================================================== //

    public function tags_id_asc_db_films_stars_user()
    {
        $tags = $this->starTagsQuery()->orderBy('id', 'ASC')->paginate(27);
        $count_tags = $this->starTagsQuery()->count();

        $categories_asc = 1;
        $stars_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_asc', 'stars_db_films'));
    }

    public function tags_name_asc_db_films_stars_user()
    {
        $tags = $this->starTagsQuery()->orderBy('name', 'ASC')->paginate(27);
        $count_tags = $this->starTagsQuery()->count();

        $categories_name_asc = 1;
        $stars_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_name_asc', 'stars_db_films'));
    }

    public function tags_name_desc_db_films_stars_user()
    {
        $tags = $this->starTagsQuery()->orderBy('name', 'DESC')->paginate(27);
        $count_tags = $this->starTagsQuery()->count();

        $categories_name_desc = 1;
        $stars_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_name_desc', 'stars_db_films'));
    }

    public function tags_random_db_films_stars_user()
    {
        $tags = $this->starTagsQuery()->inRandomOrder()->paginate(27);
        $count_tags = $this->starTagsQuery()->count();

        $categories_db_films_random = 1;
        $stars_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_db_films_random', 'stars_db_films'));
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //


    // ============================================================ Tags films use in studios ====================================================== //

    public function tags_studios_db_film()
    {
        $tags = $this->studioTagsQuery()->orderBy('id', 'DESC')->paginate(27);
        $count_tags = $this->studioTagsQuery()->count();

        $categories = 1;
        $studios_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories', 'studios_db_films'));
    }


    public function searchtag_tags_studios_db_film(Request $request)
    {
        $searchTerm = $request->get('query');

        $tags = $this->studioTagsQuery()
            ->where('tags.name', 'like', '%'.$searchTerm.'%')
            ->take(4)
            ->get();

        if ($tags->count() > 0) {

            foreach ($tags as $tag) {
                $count_films = DB::table('tags')
                    ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
                    ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
                    ->select('studios.*')
                    ->where('tags.id', $tag->id)
                    ->where('studios_tags.tag_db', 1)
                    ->distinct()
                    ->count();

                $url_films = url('/select_categories_studios_db_films', $tag->id);

                echo $this->renderEntityCard($url_films, $tag->thumbnail, $count_films, $tag->name);
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    //==================================================================== SORT Tags films use in studios BY  =========================================================== //

    public function tags_id_asc_db_films_studios_user()
    {
        $tags = $this->studioTagsQuery()->orderBy('id', 'ASC')->paginate(27);
        $count_tags = $this->studioTagsQuery()->count();

        $categories_asc = 1;
        $studios_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_asc', 'studios_db_films'));
    }

    public function tags_name_asc_db_films_studios_user()
    {
        $tags = $this->studioTagsQuery()->orderBy('name', 'ASC')->paginate(27);
        $count_tags = $this->studioTagsQuery()->count();

        $categories_name_asc = 1;
        $studios_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_name_asc', 'studios_db_films'));
    }

    public function tags_name_desc_db_films_studios_user()
    {
        $tags = $this->studioTagsQuery()->orderBy('name', 'DESC')->paginate(27);
        $count_tags = $this->studioTagsQuery()->count();

        $categories_name_desc = 1;
        $studios_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'categories_name_desc', 'studios_db_films'));
    }

    public function tags_random_db_films_studios_user()
    {
        $tags = $this->studioTagsQuery()->inRandomOrder()->paginate(27);
        $count_tags = $this->studioTagsQuery()->count();

        $tags_random_db_films_studios_user_info = 1;
        $studios_db_films = 1;

        return view('sites.categories_db_films', compact('tags', 'count_tags', 'tags_random_db_films_studios_user_info', 'studios_db_films'));
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //

}
