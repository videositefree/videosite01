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
   
    
    public function categories_stars()
    {
  
        $tags = DB::table('tags_stars')
        ->orderBy('created_at', 'DESC')
        ->distinct('id')
        ->paginate(28);
   
        $count_tags = DB::table('tags_stars')->count();

        $categories = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories'));
 
    }
    



    //==================================================================== SORT categories =========================================================== //
    public function categories_stars_asc()
    {
  
        $tags = DB::table('tags_stars')
        ->orderBy('id', 'ASC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_stars')->count();

        $categories_asc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_asc'));
 
    }

    public function categories_stars_name_asc()
    {
  
        $tags = DB::table('tags_stars')
        ->orderBy('name', 'ASC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_stars')->count();

        $categories_name_asc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_name_asc'));
 
    }

    public function categories_stars_name_desc()
    {
  
        $tags = DB::table('tags_stars')
        ->orderBy('name', 'DESC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_stars')->count();

        $categories_name_desc = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_name_desc'));
 
    }

    public function categories_stars_random()
    {
  
        $tags = DB::table('tags_stars')
        ->inRandomOrder()
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_stars')->count();

        $categories_stars_random = 1;

        return view('sites.categories_stars', compact('tags', 'count_tags', 'categories_stars_random'));
 
    }

    //==================================================================== END =========================================================== //





}
