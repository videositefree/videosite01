<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags_studios;

use Image;


class TagsStudiosController extends Controller
{

    private function tagsStudiosQuery()
    {
        return DB::table('tags_studios')->distinct('id');
    }

    public function categories_studios()
    {
        $tags = $this->tagsStudiosQuery()->orderBy('created_at', 'DESC')->paginate(28);
        $count_tags = DB::table('tags_studios')->count();
        $categories = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories'));
    }


    //==================================================================== SORT categories =========================================================== //

    public function categories_studios_asc()
    {
        $tags = $this->tagsStudiosQuery()->orderBy('id', 'ASC')->paginate(28);
        $count_tags = DB::table('tags_studios')->count();
        $categories_asc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_asc'));
    }

    public function categories_studios_name_asc()
    {
        $tags = $this->tagsStudiosQuery()->orderBy('name', 'ASC')->paginate(28);
        $count_tags = DB::table('tags_studios')->count();
        $categories_name_asc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_name_asc'));
    }

    public function categories_studios_name_desc()
    {
        $tags = $this->tagsStudiosQuery()->orderBy('name', 'DESC')->paginate(28);
        $count_tags = DB::table('tags_studios')->count();
        $categories_name_desc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_name_desc'));
    }

    public function categories_studios_random()
    {
        $tags = $this->tagsStudiosQuery()->inRandomOrder()->paginate(28);
        $count_tags = DB::table('tags_studios')->count();
        $categories_random_info = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_random_info'));
    }

    //==================================================================== END =========================================================== //

}
