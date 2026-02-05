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

    public function stars()
    {
       
        $stars = DB::table('stars')
        ->orderBy('created_at', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_i = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_i'));

    }


    //==================================================================== SORT STARS =========================================================== //
    public function stars_asc()
    {
       
        $stars = DB::table('stars')
        ->orderBy('created_at', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_asc'));

    }

    public function stars_name_asc()
    {
       
        $stars = DB::table('stars')
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_name_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_name_asc'));

    }

    public function stars_gender_male_asc(){

        $stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'male')->paginate(28);
        $count_stars = DB::table('stars')->where('sex', '=', 'male')->count();
        $stars_gender_male = 1;
        return view('sites.stars',compact('stars', 'count_stars', 'stars_gender_male'));
    
    }

    public function stars_gender_female_desc(){

        $stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'female')->paginate(28);
        $count_stars = DB::table('stars')->where('sex', '=', 'female')->count();
        $stars_gender_female = 1;
        return view('sites.stars',compact('stars', 'count_stars', 'stars_gender_female'));
    
    }


    public function stars_name_desc()
    {
       
        $stars = DB::table('stars')
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_name_desc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_name_desc'));

    }

    public function stars_rating_asc()
    {
       
        $stars = DB::table('stars')
        ->orderBy('rating', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_rating_asc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_rating_asc'));

    }

    public function stars_rating_desc()
    {
       
        $stars = DB::table('stars')
        ->orderBy('rating', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_rating_desc = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_rating_desc'));

    }

    public function stars_random()
    {
       
        $stars = DB::table('stars')
        ->inRandomOrder()
        ->distinct()
        ->paginate(28);

        $count_stars = DB::table('stars')->count();

        $stars_random_info = 1;

        return view('sites.stars', compact('stars', 'count_stars', 'stars_random_info'));

    }

     //==================================================================== END ============================================================= //
    






}
