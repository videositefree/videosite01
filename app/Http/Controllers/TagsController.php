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
   
    
    public function categories()
    {
  
        $tags = DB::table('tags')
        ->orderBy('created_at', 'DESC')
        ->distinct()
        ->paginate(28);
   
        $count_tags = DB::table('tags')->count();

        $categories = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories'));
 
    }
    



    //==================================================================== SORT categories =========================================================== //
    public function categories_asc()
    {
  
        $tags = DB::table('tags')
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_tags = DB::table('tags')->count();

        $categories_asc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_asc'));
 
    }

    public function categories_name_asc()
    {
  
        $tags = DB::table('tags')
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(28);

        $count_tags = DB::table('tags')->count();

        $categories_name_asc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_name_asc'));
 
    }

    public function categories_name_desc()
    {
  
        $tags = DB::table('tags')
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(28);

        $count_tags = DB::table('tags')->count();

        $categories_name_desc = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_name_desc'));
 
    }

    public function categories_user_random()
    {
  
        $tags = DB::table('tags')
        ->inRandomOrder()
        ->distinct()
        ->paginate(28);

        $count_tags = DB::table('tags')->count();

        $categories_user_random_info = 1;

        return view('sites.categories', compact('tags', 'count_tags', 'categories_user_random_info'));
 
    }

    //==================================================================== END =========================================================== //


























    

    // ============================================================ Tags films use in stars ====================================================== //
    

    public function tags_stars_db_film(){

        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->count();

        $categories = 1;
        $stars_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories', 'stars_db_films'));
       
    }


    public function searchtag_tags_stars_db_film(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('stars_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('stars_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url_films = url('/select_categories_stars_db_films',$id);

            $count_films = DB::table('tags')
            ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
            ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
            ->orderBy('name', 'ASC')
            ->select('stars.*')
            ->where('tags.id', $id)
            ->where('stars_tags.tag_db', 1)
            ->distinct()
            ->count();

            echo '
            <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
            <div class="card" style="background-color: #F5F5F5; border: none !important;">
                <div class="wrapper">
                    <a href="'.$url_films.'">
                        <img class="card-img-top img-fluid" src="'.$thumbnail.'">
        
                    <div class="film_number">
                        <i class="fas fa-video ">&nbsp;&nbsp;'.$count_films.'</i>
                    </div>
                </div>
        
                <div class=" card-hover">
                    <a href="'.$url_films.'">
                        <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                            
                                '.$name.'
                            
                        </div>
                    </a>
                </div>
                
                </a>
            </div>
            </div>
    
            ';       

        }
            echo '<hr style="width: inherit;">';             
        }
        else
        {
            echo '
            <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
                <div class="alert alert-danger">
                    <ul>
                        Przepraszamy ale nie mamy tego czego szukasz :/
                    </ul>
                </div>
            </div>
            '; 
        }

        
    }




    //==================================================================== SORT Tags films use in stars BY  =========================================================== //

    public function tags_id_asc_db_films_stars_user(){
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->count();

        $categories_asc = 1;
        $stars_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_asc', 'stars_db_films'));
       
    }

    public function tags_name_asc_db_films_stars_user(){
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->count();

        $categories_name_asc = 1;
        $stars_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_name_asc', 'stars_db_films'));
       
    }

    public function tags_name_desc_db_films_stars_user(){

         $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->count();

        $categories_name_desc = 1;
        $stars_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_name_desc', 'stars_db_films'));
       
    }


    public function tags_random_db_films_stars_user(){

        $tags = DB::table('tags')
       ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
       ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
       ->select('tags.*')
       ->where('stars_tags.tag_db', 1)
       ->inRandomOrder()
       ->distinct('tags.id')
       ->paginate(27);

       $all_tags = DB::table('tags')
       ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
       ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
       ->select('tags.*')
       ->where('stars_tags.tag_db', 1)
       ->inRandomOrder()
       ->distinct('tags.id')
       ->paginate(27);

       $count_tags = DB::table('tags')
       ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
       ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
       ->select('tags.*')
       ->where('stars_tags.tag_db', 1)
       ->inRandomOrder()
       ->distinct('tags.id')
       ->count();

       $categories_db_films_random = 1;
       $stars_db_films = 1;
       
       return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_db_films_random', 'stars_db_films'));
      
   }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   































    // ============================================================ Tags films use in studios ====================================================== //





    public function tags_studios_db_film(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct('tags.id')
        ->count();

        $categories = 1;
        $studios_db_films = 1;
        

        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories', 'studios_db_films'));
       
    }


    public function searchtag_tags_studios_db_film(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('studios_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('studios_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags',$id);
        $url_delete = url('/delete_files_from_admin_search_tags',$id);
        $url_films = url('/select_categories_studios_db_films',$id);

        $count_films = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->orderBy('name', 'ASC')
        ->select('studios.*')
        ->where('tags.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->distinct()
        ->count();

        echo '
        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
            <div class="wrapper">
                <a href="'.$url_films.'">
                    <img class="card-img-top img-fluid" src="'.$thumbnail.'">
    
                <div class="film_number">
                    <i class="fas fa-video ">&nbsp;&nbsp;'.$count_films.'</i>
                </div>
            </div>
    
            <div class=" card-hover">
                <a href="'.$url_films.'">
                    <div class="card-body" style="border: 1px solid rgba(0, 0, 0, 0.125);">
                        
                            '.$name.'
                        
                    </div>
                </a>
            </div>
            
            </a>
        </div>
        </div>

        ';
                
        }
        echo '<hr style="width: inherit;">';
            
        }
        else
        {
            echo '
            <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
                <div class="alert alert-danger">
                    <ul>
                        Przepraszamy ale nie mamy tego czego szukasz :/
                    </ul>
                </div>
            </div>
            '; 
        }

        
    }

    

    //==================================================================== SORT Tags films use in studios BY  =========================================================== //

    public function tags_id_asc_db_films_studios_user(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct('tags.id')
        ->count();

        $categories_asc = 1;
        $studios_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags','categories_asc', 'studios_db_films'));
       
    }

    public function tags_name_asc_db_films_studios_user(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct('tags.id')
        ->count();

        $categories_name_asc = 1;
        $studios_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_name_asc', 'studios_db_films'));
       
    }

    public function tags_name_desc_db_films_studios_user(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->count();

        $categories_name_desc = 1;
        $studios_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'categories_name_desc', 'studios_db_films'));
       
    }


    public function tags_random_db_films_studios_user(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->inRandomOrder()
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->inRandomOrder()
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->inRandomOrder()
        ->orderBy('name', 'DESC')
        ->distinct('tags.id')
        ->count();

        $tags_random_db_films_studios_user_info = 1;
        $studios_db_films = 1;
        
        return view('sites.categories_db_films',compact('tags', 'count_tags', 'all_tags', 'tags_random_db_films_studios_user_info', 'studios_db_films'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   

























}
