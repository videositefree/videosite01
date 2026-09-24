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

    private function studiosQuery()
    {
        return DB::table('studios')->distinct();
    }

    public function studios()
    {
        $studios = $this->studiosQuery()->orderBy('created_at', 'DESC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_i = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_i'));
    }

    //==================================================================== SORT STUDIOS =========================================================== //

    public function studios_asc()
    {
        $studios = $this->studiosQuery()->orderBy('created_at', 'ASC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_asc'));
    }

    public function studios_name_asc()
    {
        $studios = $this->studiosQuery()->orderBy('name', 'ASC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_name_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_name_asc'));
    }

    public function studios_name_desc()
    {
        $studios = $this->studiosQuery()->orderBy('name', 'DESC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_name_desc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_name_desc'));
    }

    public function studios_rating_asc()
    {
        $studios = $this->studiosQuery()->orderBy('rating', 'ASC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_rating_asc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_rating_asc'));
    }

    public function studios_rating_desc()
    {
        $studios = $this->studiosQuery()->orderBy('rating', 'DESC')->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_rating_desc = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_rating_desc'));
    }

    public function studios_random()
    {
        $studios = $this->studiosQuery()->inRandomOrder()->paginate(28);
        $count_studios = DB::table('studios')->count();
        $studios_random_info = 1;

        return view('sites.studios', compact('studios', 'count_studios', 'studios_random_info'));
    }

    //==================================================================== END ============================================================== //

}
