<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\tags;

use App\stars;

use App\studios;

use Illuminate\Support\Collection;

class AjaxTagController extends Controller
{
// ================================================================= ADD NEW FILMS! ====================================================== //
    // Search metod for add film blade
    public function gettag(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['tag_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }

    public function getstar(Request $request){

            $searchTerm = $request -> get('term');
            
            $stars = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->select('*')
            ->take(10)
            ->get();
    
            $total_row = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->count();
    
            
            if($total_row > 0)
            {
                foreach($stars as $row)
                {
                
                    $temp_array['value'] = $row->name;
                    $temp_array['label'] = $row->name;
                    $temp_array['star_id'] = $row->id;
                    $temp_array['img'] = '../'.$row->thumbnail;
                    $output[] = $temp_array;
                }
            }
            else
            {
           
                $temp_array['label'] = 'Brak wyników wyszukiwania.';
                $temp_array['img'] = 'icon/app.blade/notfing_found.png';
                $temp_array['disabled'] = 'ui-state-disabled';
                $output[] = $temp_array;
            }
    
            echo json_encode($output, true);
            
    }

    public function getstudios(Request $request){

        $searchTerm = $request -> get('term');
        
        $studios = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($studios as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['studios_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;      

        }

        echo json_encode($output, true);
        


        
    }


    public function gettag_stars(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }




    public function gettag_studios(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }



    public function gettag_sites(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }

// ================================================================= END! ====================================================== //




// ================================================================= EDIT FILMS! ====================================================== //
    // Search metod for edit film blade
    public function gettagg(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['tag_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = '../icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }

    public function getstarr(Request $request){

            $searchTerm = $request -> get('term');
            
            $stars = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->select('*')
            ->take(10)
            ->get();
    
            $total_row = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->count();
    
            
            if($total_row > 0)
            {
                foreach($stars as $row)
                {
                
                    $temp_array['value'] = $row->name;
                    $temp_array['label'] = $row->name;
                    $temp_array['star_id'] = $row->id;
                    $temp_array['img'] = '../'.$row->thumbnail;
                    $output[] = $temp_array;
                }
            }
            else
            {
           
                $temp_array['label'] = 'Brak wyników wyszukiwania.';
                $temp_array['img'] = '../icon/app.blade/notfing_found.png';
                $temp_array['disabled'] = 'ui-state-disabled';
                $output[] = $temp_array;
            }
    
            echo json_encode($output, true);
            
    }

    public function getstudioss(Request $request){

        $searchTerm = $request -> get('term');
        
        $studios = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($studios as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['studios_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = '../icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;      

        }

        echo json_encode($output, true);
        


        
    }

    // END



    // Search in user tag 
    public function searchtag(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/select_categories',$id);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();
        
        echo '

        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
              <a href="'.$url.'">
                  <img class="card-img-top img-fluid" src="'.$thumbnail.'">
  
              <div class="film_number">
                  <i class="fas fa-video ">&nbsp;&nbsp;'.$count_films.'</i>
              </div>
          </div>
  
            <div class=" card-hover">
                <a href="'.$url.'">
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





    // Search in user tag stars
    public function searchtag_stars(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/select_categories_stars',$id);

        $count_films = DB::table('tags_stars')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->orderBy('name', 'ASC')
        ->select('stars.*')
        ->where('tags_stars.id', $id)
        ->where('stars_tags.tag_db', 0)        
        ->distinct()
        ->count();
        
        $url_films = url('/select_categories_stars',$id);
        
        echo '

        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
              <a href="'.$url.'">
                  <img class="card-img-top img-fluid" src="'.$thumbnail.'">

                  
                  <a href="'.$url_films.'">
                  <div class="film_number">
                      <i class="fas fa-tag">&nbsp;&nbsp;'.$count_films.'</i>
                  </div>
              </a>

          </div>
  
            <div class=" card-hover">
                <a href="'.$url.'">
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


    public function searchstar(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $stars = DB::table('stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($stars as $stars){
        $id = $stars->id;
        $name = $stars->name;
        $thumbnail = $stars->thumbnail;
        $url = url('/select_stars',$id);

        $count_films = DB::table('stars')
        ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
        ->join('films', 'films.id', '=', 'films_stars.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('stars.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        echo '
        
        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
              <a href="'.$url.'">
                  <img class="card-img-top img-fluid" src="'.$thumbnail.'">
  
              <div class="film_number">
                  <i class="fas fa-video ">&nbsp;&nbsp;'.$count_films.'</i>
              </div>
          </div>
  
            <div class=" card-hover">
                <a href="'.$url.'">
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


    public function searchstudios(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $studios = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($studios as $studios){
        $id = $studios->id;
        $name = $studios->name;
        $thumbnail = $studios->thumbnail;
        $url = url('/select_studios',$id);

        $count_films = DB::table('studios')
        ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
        ->join('films', 'films.id', '=', 'films_studios.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('studios.id', $id)
        ->where('activ', '=', '1')
        ->distinct()
        ->count();

        echo '
        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
              <a href="'.$url.'">
                  <img class="card-img-top img-fluid" src="'.$thumbnail.'">
  
              <div class="film_number">
                  <i class="fas fa-video ">&nbsp;&nbsp;'.$count_films.'</i>
              </div>
          </div>
  
            <div class=" card-hover">
                <a href="'.$url.'">
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



    //==================================================================== SEARCH TAG IN TABLE VIEW =========================================================== //
    public function searchtag_studios(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();
        
        

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url_films = url('/select_categories_studios',$id);

        $count_films = DB::table('tags_studios')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->orderBy('name', 'ASC')
        ->select('studios.*')
        ->where('tags_studios.id', $id)
        ->where('studios_tags.tag_db', 0) 
        ->distinct()
        ->count();


        echo '

        <div class="col-sm-3 col-m-2" style="padding-top: 20px;">
        <div class="card" style="background-color: #F5F5F5; border: none !important;">
          <div class="wrapper">
              <a href="'.$url_films.'">
                  <img class="card-img-top img-fluid" src="'.$thumbnail.'">

                  
                  <a href="'.$url_films.'">
                  <div class="film_number">
                      <i class="fas fa-tag">&nbsp;&nbsp;'.$count_films.'</i>
                  </div>
              </a>

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

}