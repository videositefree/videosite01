<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\films;

use App\tags;

use App\stars;

use App\studios;

class FilmsSearchController extends Controller
{
  
    
    public function add_films(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('authsites.add_films');
        }else{
          
            return view('authsites.add_films')->with('errorsMsg',"UWAGA!!! ");
           
        }

    }
    public function search(Request $request)
    {
   
        $search = $request -> input('search');

        $films = DB::table('films')
        ->where('name', 'like', '%'.$search.'%')
        ->where('activ', '=', '1')
        ->distinct()
        ->paginate(27); 
    
        
        return view('sites.search', compact('films', 'search'));

    }




}
