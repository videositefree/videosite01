<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags_stars;

use Image;


class TagsStarsController extends Controller
{

    private function tagsStarsQuery()
    {
        return DB::table('tags_stars')->distinct('id');
    }

    public function categories_stars()
    {
        $tags = $this->tagsStarsQuery()->orderBy('created_at', 'DESC')->paginate(28);
        $count_tags = DB::table('tags_stars')->count();
        $categories = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories'));
    }


    //==================================================================== SORT categories =========================================================== //

    public function categories_stars_asc()
    {
        $tags = $this->tagsStarsQuery()->orderBy('id', 'ASC')->paginate(28);
        $count_tags = DB::table('tags_stars')->count();
        $categories_asc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_asc'));
    }

    public function categories_stars_name_asc()
    {
        $tags = $this->tagsStarsQuery()->orderBy('name', 'ASC')->paginate(28);
        $count_tags = DB::table('tags_stars')->count();
        $categories_name_asc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_name_asc'));
    }

    public function categories_stars_name_desc()
    {
        $tags = $this->tagsStarsQuery()->orderBy('name', 'DESC')->paginate(28);
        $count_tags = DB::table('tags_stars')->count();
        $categories_name_desc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_name_desc'));
    }

    public function categories_stars_random()
    {
        $tags = $this->tagsStarsQuery()->inRandomOrder()->paginate(28);
        $count_tags = DB::table('tags_stars')->count();
        $categories_stars_random = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_stars_random'));
    }

    //==================================================================== END =========================================================== //

}
