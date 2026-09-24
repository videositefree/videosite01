<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\stras;

use Image;

class StarsController extends Controller
{

    private function starsQuery()
    {
        return DB::table('stars')->distinct();
    }

    public function stars()
    {
        $stars = $this->starsQuery()->orderBy('created_at', 'DESC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_i = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_i'));
    }


    //==================================================================== SORT STARS =========================================================== //

    public function stars_asc()
    {
        $stars = $this->starsQuery()->orderBy('created_at', 'ASC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_asc'));
    }

    public function stars_name_asc()
    {
        $stars = $this->starsQuery()->orderBy('name', 'ASC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_name_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_name_asc'));
    }

    public function stars_gender_male_asc()
    {
        $stars = $this->starsQuery()->where('sex', '=', 'male')->paginate(28);
        $count_stars = DB::table('stars')->where('sex', '=', 'male')->count();
        $stars_gender_male = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_gender_male'));
    }

    public function stars_gender_female_desc()
    {
        $stars = $this->starsQuery()->where('sex', '=', 'female')->paginate(28);
        $count_stars = DB::table('stars')->where('sex', '=', 'female')->count();
        $stars_gender_female = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_gender_female'));
    }

    public function stars_name_desc()
    {
        $stars = $this->starsQuery()->orderBy('name', 'DESC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_name_desc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_name_desc'));
    }

    public function stars_rating_asc()
    {
        $stars = $this->starsQuery()->orderBy('rating', 'ASC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_rating_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_rating_asc'));
    }

    public function stars_rating_desc()
    {
        $stars = $this->starsQuery()->orderBy('rating', 'DESC')->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_rating_desc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_rating_desc'));
    }

    public function stars_random()
    {
        $stars = $this->starsQuery()->inRandomOrder()->paginate(28);
        $count_stars = DB::table('stars')->count();
        $stars_random_info = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_random_info'));
    }

     //==================================================================== END ============================================================= //

}
