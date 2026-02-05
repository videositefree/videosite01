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
   
    
    public function categories_studios()
    {
  
        $tags = DB::table('tags_studios')
        ->orderBy('created_at', 'DESC')
        ->distinct('id')
        ->paginate(28);
   
        $count_tags = DB::table('tags_studios')->count();

        $categories = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories'));
 
    }
    



    //==================================================================== SORT categories =========================================================== //
    public function categories_studios_asc()
    {
  
        $tags = DB::table('tags_studios')
        ->orderBy('id', 'ASC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_studios')->count();

        $categories_asc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_asc'));
 
    }

    public function categories_studios_name_asc()
    {
  
        $tags = DB::table('tags_studios')
        ->orderBy('name', 'ASC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_studios')->count();

        $categories_name_asc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_name_asc'));
 
    }

    public function categories_studios_name_desc()
    {
  
        $tags = DB::table('tags_studios')
        ->orderBy('name', 'DESC')
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_studios')->count();

        $categories_name_desc = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_name_desc'));
 
    }


    public function categories_studios_random()
    {
  
        $tags = DB::table('tags_studios')
        ->inRandomOrder()
        ->distinct('id')
        ->paginate(28);

        $count_tags = DB::table('tags_studios')->count();

        $categories_random_info = 1;

        return view('sites.categories_studios', compact('tags', 'count_tags', 'categories_random_info'));
 
    }

    //==================================================================== END =========================================================== //





}
