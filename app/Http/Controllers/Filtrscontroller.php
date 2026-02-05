<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;

use App\films;

use App\tags;

use App\stars;

use App\studios;

class Filtrscontroller extends Controller
{
    
    public function relevance(Request $request)
    {
    $tags = DB::table('tags') ->get();
    $stars = DB::table('stars') ->get();
    $studios = DB::table('studios') ->get();

    $search = $request -> input('search');
    
    $sort = $request -> input('sort');
    $date = $request -> input('date');
    $time = $request -> input('time');

    if($date === 'today'){
    $date_var = "NOW() - INTERVAL 3 DAY";
    }
    
    if($date === 'week'){
        $date_var = "NOW() - INTERVAL 7 DAY";
    }

    if($date === 'month'){
        $date_var = "NOW() - INTERVAL 30 DAY";
    }

    if($date === '3month'){
        $date_var = "NOW() - INTERVAL 3 MONTH";
    }

    if($date === '6month'){
        $date_var = "NOW() - INTERVAL 6 MONTH";
    }

    if($time === '3-10min'){
    $operator = "<=";
    $time_var = "600";
    }

    if($time === '10-20min'){

    $operator = ">";
    $time_var = "600";
    $operator_two = "<=";
    $time_var_two = "1200";
    }

    if($time === '20-40min'){

    $operator = ">";
    $time_var = "1200";
    $operator_two = "<=";
    $time_var_two = "2400";
    }

    if($time === '40min_more'){
        $operator = ">";
        $time_var = "2400";
        $operator_two = "<=";
        $time_var_two = "240000";
    }


    if($sort === 'relevance'){
    $films = DB::table('films')
    ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
    ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
    ->select('films.*')
    ->where('tags.name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->distinct()
    ->paginate(27);
    }

    if($sort === 'uploaddate'){

    $films = DB::table('films')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 
    }

    if($sort === 'rating'){

    $films = DB::table('films')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('rating', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($sort === 'length'){

    $films = DB::table('films')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('duration', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($date === 'all'){

    $films = DB::table('films')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($date === 'today'){

    $films = DB::table('films')
    ->where('created_at', '>', DB::raw('NOW() - INTERVAL 3 DAY'))
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }
    
    if($date === 'week'){

    $films = DB::table('films')
    ->where('created_at', '>', DB::raw('NOW() - INTERVAL 7 DAY'))
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($date === 'month'){

    $films = DB::table('films')
    ->where('created_at', '>', DB::raw('NOW() - INTERVAL 30 DAY'))
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($date === '3month'){

    $films = DB::table('films')
    ->where('created_at', '>', DB::raw('NOW() - INTERVAL 3 MONTH'))
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($date === '6month'){

    $films = DB::table('films')
    ->where('created_at', '>', DB::raw('NOW() - INTERVAL 6 MONTH'))
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->orderBy('created_at', 'desc')
    ->distinct()
    ->paginate(27); 

    }

    if($time === '3-10min'){

    $films = DB::table('films')
    ->where('duration', '<=', '600')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->distinct()
    ->paginate(27); 
    }

    if($time === '10-20min'){

    $films = DB::table('films')
    ->where('duration', '>', '600')
    ->where('duration', '<=', '1200')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->distinct()
    ->paginate(27); 
    }

    if($time === '20-40min'){

    $films = DB::table('films')
    ->where('duration', '>', '1200')
    ->where('duration', '<=', '2400')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->distinct()
    ->paginate(27); 
    }

    if($time === '40min_more'){

    $films = DB::table('films')
    ->where('duration', '>', '2400')
    ->where('name', 'like', '%'.$search.'%')
    ->where('activ', '=', '1')
    ->distinct()
    ->paginate(27); 
    }





        //------------------------------------------------------- ADVENCED METHOD! -----------------------------------------------------//





    if(!empty($sort) && !empty($date))
    {
        // for all date
        if($sort === 'relevance' && $date === 'all'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        elseif($sort === 'relevance'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        // for all date
        if($sort === 'uploaddate' && $date === 'all'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }

        elseif($sort === 'uploaddate'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->where('created_at', '>', DB::raw(''.$date_var.''))
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }


        // for all date
        if($sort === 'rating' && $date === 'all'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'rating'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->where('created_at', '>', DB::raw(''.$date_var.''))
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }


        // for all date
        if($sort === 'length' && $date === 'all'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'length'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->where('created_at', '>', DB::raw(''.$date_var.''))
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }
    

            

    }

        
    if(!empty($sort) && !empty($time))
    {

        // for all time
        if($sort === 'relevance' && $time === '3-10min'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('films.duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        elseif($sort === 'relevance'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('films.duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        // for all time
        if($sort === 'uploaddate' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }

        elseif($sort === 'uploaddate'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }


        // for all time
        if($sort === 'rating' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'rating'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }


        // for all time
        if($sort === 'length' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'length'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }
            
            
    }

    if(!empty($date) && !empty($time))
    {

        if($date === 'all' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === 'all'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        if($date === 'today' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === 'today'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }
        
        if($date === 'week' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === 'week'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        if($date === 'month' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === 'month'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        if($date === '3month' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === '3month'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        if($date === '6month' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($date === '6month'){

        $films = DB::table('films')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 

        }




            
    }
        
  

    if(!empty($sort) && !empty($date) && !empty($time))
    {


        // for all time
        if($sort === 'relevance' && $time === '3-10min' && $date === 'all'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('films.duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        elseif($sort === 'relevance'){
        $films = DB::table('films')
        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
        ->select('films.*')
        ->where('tags.name', 'like', '%'.$search.'%')
        ->where('films.duration', ''.$operator.'', ''.$time_var.'')
        ->where('films.duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27);
        }

        // for all time
        if($sort === 'uploaddate' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }

        elseif($sort === 'uploaddate'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('activ', '=', '1')
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->paginate(27); 
        }


        // for all time
        if($sort === 'rating' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'rating'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('activ', '=', '1')
        ->orderBy('rating', 'desc')
        ->distinct()
        ->paginate(27); 

        }


        // for all time
        if($sort === 'length' && $time === '3-10min'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        elseif($sort === 'length'){

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('duration', ''.$operator.'', ''.$time_var.'')
        ->where('duration', ''.$operator_two.'', ''.$time_var_two.'')
        ->where('films.created_at', '>', DB::raw(''.$date_var.''))
        ->where('activ', '=', '1')
        ->orderBy('duration', 'desc')
        ->distinct()
        ->paginate(27); 

        }

        
    }

    

    

    
    return view('sites.search', compact('films', 'sort', 'date', 'time', 'search'));

}





}
