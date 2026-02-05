<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\films;

use App\stars_tags;

class FilmsController extends Controller
{
    
    
     
    public function index()
    {

        //====================================================== check exist database and connection ==============================================//
    

        try {
            $db_host = env('DB_HOST');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $db_database = env('DB_DATABASE');

            if(env('DB_DATABASE')) {
                $db_host = env('DB_HOST');
                $db_username = env('DB_USERNAME');
                $db_password = env('DB_PASSWORD');
    
                $db = new PDO("mysql:host=$db_host", $db_username, $db_password);
            
            }
            else
            {
            $db = new PDO("mysql:host=$db_host", $db_username, $db_password);
            }

        } catch (\Exception $e) {
            return redirect(url('/connection_db'))->with('errors', 'Błąd połączenia z bazą danych.');
        }

        if(env('DB_DATABASE')) {


            try {
                $db_name = env('DB_DATABASE');
                $pdo = DB::connection()->getPdo();
                $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
                $result = $stmt->fetch();
                $count = $result[0];
                
            }
            catch (\Exception $e) {
                return view('install.manually_create_db');
            }


            if(isset($count)){
                if($count === 1){
    
                    if (!Schema::hasTable('films', 'tags', 'stars', 'studios', 'films_tags', 'films_stars ', 'films_studios', 'users')){
                        return view('install.manually_db');
                    }    
                }
            }else{
                return redirect(url('/connection_db'))
                ->with('errors', "Brak bazy danych o nazwie $db_database");
            }
      
            
        }
        else
        {
            return view('install.manually'); 
        }
    
        
        
    

        // ========================================================== END ===================================================================== //
            

        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'DESC')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index = 1;            

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index'));
    
    }






    //==================================================================== SORT FILMS BY =========================================================== //
    public function index_asc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'asc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_asc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_asc'));
 
    }

    public function index_name_asc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('name', 'asc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_name_asc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_name_asc'));
 
    }

    public function index_name_desc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('name', 'desc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_name_desc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_name_desc'));
 
    }

    public function index_rating_asc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'asc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort
        
        $index_rating_asc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_rating_asc'));
 
    }

    public function index_rating_desc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_rating_desc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_rating_desc'));
 
    }

    public function index_duration_asc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'ASC')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_duration_asc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_duration_asc'));
 
    }

    public function index_duration_desc()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_duration_desc = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_duration_desc'));
 
    }

    public function index_random()
    {
  
        $films = DB::table('films')
        ->where('activ', '=', '1')
        ->inRandomOrder()
        ->distinct()
        ->paginate(27);
        
        $count_films = DB::table('films')->count();
        $films_check = 1; // display filtr sort 

        $index_random = 1;

        return view('sites.index', compact('films', 'count_films', 'films_check', 'index_random'));
 
    }


    public function index_random_film_watch()
    {
  
        $films_watch = DB::table('films')
        ->where('activ', '=', '1')
        ->inRandomOrder()
        ->distinct()
        ->select('id')
        ->limit(1) // here is yours limit
        ->get();
        
        foreach($films_watch as $films_watch){
            $id = $films_watch->id;
        }      

        return redirect()->route('watch', ($id));

 
    }

    // ==================================================================== END =========================================================== //



    // ==================================================================== WATCH FILMS =========================================================== //
    public function watch($id)
    {


        $films = films::find($id);

        if($films === null){
            return redirect('/')->with('error', 'Brak rekordu w bazie danych.');        
        }
        
        $tags = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'films_tags.id', 'tags.id')
        ->where('films.id', $id)
        ->distinct()
        ->orderBy('tags.name', 'ASC')
        ->get();
    

        $stars = DB::table('films')
        ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
        ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
        ->select('stars.name', 'films_stars.id', 'stars.id')
        ->where('films.id', $id)
        ->orderBy('stars.name', 'ASC')
        ->distinct()
        ->get();

        $stars_id = DB::table('films')
        ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
        ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
        ->select('stars.name', 'films_stars.id', 'stars.id')
        ->where('films.id', $id)
        ->orderBy('stars.name', 'ASC')
        ->distinct()
        ->get();

        $stars_id_count = DB::table('films')
        ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
        ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
        ->select('stars.name', 'films_stars.id', 'stars.id')
        ->where('films.id', $id)
        ->orderBy('stars.name', 'ASC')
        ->distinct()
        ->count();
        
        if ($stars_id_count > 0){
            foreach($stars_id as $stars_id){
                $stars_id = $stars_id->id;
            }      

            $stars_tags = DB::table('stars')
            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
            ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
            ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
            ->where('stars.id', $stars_id)
            ->where('stars_tags.tag_db', 0)
            ->orderBy('stars.name', 'ASC')
            ->get();
        }
        else
        {
            $stars_tags = 0;
        }




        $studios = DB::table('films')
        ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
        ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
        ->select('studios.name', 'films_studios.id', 'studios.id')
        ->where('films.id', $id)
        ->orderBy('studios.name', 'ASC')
        ->distinct()
        ->get();

        $studios_id = DB::table('films')
        ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
        ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
        ->select('studios.name', 'films_studios.id', 'studios.id')
        ->where('films.id', $id)
        ->orderBy('studios.name', 'ASC')
        ->distinct()
        ->get();

        $studios_id_count = DB::table('films')
        ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
        ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
        ->select('studios.name', 'films_studios.id', 'studios.id')
        ->where('films.id', $id)
        ->orderBy('studios.name', 'ASC')
        ->distinct()
        ->count();

        if($studios_id_count > 0){
            foreach($studios_id as $studios_id){
                $studios_id = $studios_id->id;
            }

            $studios_tags = DB::table('studios')
            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
            ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
            ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
            ->where('studios.id', $id)
            ->where('studios_tags.tag_db', 0)
            ->orderBy('studios.name', 'ASC')
            ->get();
        }
        else
        {
            $studios_tags = 0;
        }


        return view('sites.watch', compact('films', 'tags', 'stars', 'stars_tags', 'studios', 'studios_tags'));

    }
    // ==================================================================== END =========================================================== //

  
    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME! ==================================== //

    public function select_categories($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('created_at', 'DESC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_new = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_new'));

 
    }

    // =============================================================== FILTRS ===================================================================

    public function select_categories_asc($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('created_at', 'asc')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_old = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_old'));

 
    }


    public function select_categories_films_asc($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('films.name', 'asc')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_name_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_name_asc'));

 
    }


    public function select_categories_films_desc($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('films.name', 'desc')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_name_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_name_desc'));

 
    }


    public function select_categories_films_stars_asc($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('films.rating', 'asc')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_stars_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_stars_asc'));

 
    }


    public function select_categories_films_stars_desc($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('films.rating', 'desc')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_stars_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_stars_desc'));

 
    }

    public function select_categories_random($id)
    {

        $hidden_id_tags = $id;

        $films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->inRandomOrder()
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags = DB::table('tags')->where('id', $id)->first();

        $select_cat_random = 0;

        return view('sites.index', compact('films', 'count_films', 'tags', 'hidden_id_tags', 'select_cat_random'));

 
    }


    // ============================================================= END =====================================================================







    


    public function select_stars($id)
    {
       
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('created_at', 'DESC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();


        $select_star_new = 0;
        $hidden_id_stars = $id;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_new'));

    }



    // =============================================================== FILTRS ===================================================================



    public function select_stars_asc($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('created_at', 'asc')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_old = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_old'));

    }




    public function select_stars_films_asc($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('films.name', 'asc')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_name_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_name_asc'));

    }

    public function select_stars_films_desc($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('films.name', 'DESC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_name_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_name_desc'));

    }

    public function select_stars_films_stars_asc($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('films.rating', 'asc')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_stars_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_stars_asc'));

    }

    public function select_stars_films_stars_desc($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('films.rating', 'DESC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_stars_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_stars_desc'));

    }

    public function select_stars_random($id)
    {
   
        $films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->inRandomOrder()
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        $tags_stars = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_stars_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        $tags_stars_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $tags_stars_films_count = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->count();
        
        $stars = DB::table('stars')->where('id', $id)->first();

        $hidden_id_stars = $id;
        $select_star_random = 0;

        return view('sites.index', compact('films', 'count_films', 'stars', 'tags_stars', 'tags_stars_count', 'tags_stars_films', 'tags_stars_films_count', 'hidden_id_stars', 'select_star_random'));

    }


    // ================================================================== END ===================================================================


















    public function select_studios($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('created_at', 'DESC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_new = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_new'));

 
    }

    // =============================================================== FILTRS ===================================================================


    public function select_studios_asc($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('created_at', 'asc')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_old = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_old'));

 
    }


    public function select_studios_films_asc($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('films.name', 'asc')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_name_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_name_asc'));

 
    }

    public function select_studios_films_desc($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('films.name', 'DESC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_name_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_name_desc'));

 
    }


    public function select_studios_films_stars_asc($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('films.rating', 'asc')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_stars_asc = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_stars_asc'));

 
    }


    public function select_studios_films_stars_desc($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('films.name', 'DESC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_stars_desc = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_stars_desc'));

 
    }


    public function select_studios_random($id)
    {

        $films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->inRandomOrder()
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();


        $tags_studios_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->count();

        $tags_studios_films_count = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->count();


        $tags_studios = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_studios_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $studios = DB::table('studios')->where('id', $id)->first();

        $hidden_id_studios = $id;
        $select_studio_random = 0;

        return view('sites.index', compact('films', 'count_films', 'studios', 'tags_studios_count', 'tags_studios_films_count', 'tags_studios', 'tags_studios_films', 'hidden_id_studios', 'select_studio_random'));


    }



    // ==================================================================== END =========================================================== //





}
