<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\studios;

use Image;

class ProducersController extends Controller
{
 
    public function studios()
    {

        $studios = DB::table('studios')
        ->orderBy('created_at', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_i = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_i'));

    }

    //==================================================================== SORT STUDIOS =========================================================== //

    public function studios_asc()
    {

        $studios = DB::table('studios')
        ->orderBy('created_at', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_asc'));

    }

    public function studios_name_asc()
    {

        $studios = DB::table('studios')
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_name_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_name_asc'));

    }

    public function studios_name_desc()
    {

        $studios = DB::table('studios')
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_name_desc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_name_desc'));

    }

    public function studios_rating_asc()
    {

        $studios = DB::table('studios')
        ->orderBy('rating', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_rating_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_rating_asc'));

    }

    public function studios_rating_desc()
    {

        $studios = DB::table('studios')
        ->orderBy('rating', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_rating_desc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_rating_desc'));

    }


    public function studios_random()
    {

        $studios = DB::table('studios')
        ->inRandomOrder()
        ->distinct()
        ->paginate(28);

        $count_studios = DB::table('studios')->count();

        $studios_random_info = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_random_info'));

    }

    //==================================================================== END ============================================================== //



    


}
