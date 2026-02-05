<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use App\films;

use App\tags;

use App\tags_studios;

use App\tags_stars;

use App\tags_sites;

use App\stras;

use App\studios;

use App\site;

use Image;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Exists;

class AdminController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth'); 
    }



    //==================================================================== END ============================================================== //
    



    public function admin(){

        
        $films = DB::table('films')
            ->count();

        $full_duration = 0;  
        $films_times = DB::table('films')
        ->get();

        foreach($films_times as $films_times)
        {
            $films_time = $films_times->duration;
            $full_duration += $films_time;
        }

        $init = $full_duration;
        $full_duration_day = floor($init / 86400);
        $full_duration_hours = floor(($init -($full_duration_day*86400)) / 3600);
        $full_duration_hours_only = floor(($init /3600));
        $full_duration_minutes = floor(($init / 60) % 60);
        $full_duration_minutes_only = floor(($init / 60) );
        $full_duration_seconds = $init % 60;



        $f_on = DB::table('films')
            ->where('activ', '=', '1')
            ->count();
                     
        $f_off = DB::table('films')
            ->where('activ', '=', '0')
            ->count();

        
        $tags = DB::table('tags')
            ->count();

        $tags_stars = DB::table('tags_stars')
            ->count();
        
        $tags_studios = DB::table('tags_studios')
            ->count();

        $tags_sites = DB::table('tags_sites')
            ->count();


        $stars = DB::table('stars')
            ->count();

        $studios = DB::table('studios')
            ->count();

        $site = DB::table('site')
            ->count();

            $bytes = 0;
            $path = realpath('../../filmy/');
            if($path!==false && $path!='' && file_exists($path)){
                foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object){
                    $bytes += $object->getSize();
                }
            }
            

            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }



            $directory = "../../filmy/";
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            if (file_exists($directory)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu filmy<br> 
                Spróbuj dodać folder ręcznie następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory1 = "../../filmy/short";
            if (!file_exists($directory1)) {
                mkdir($directory1, 0777, true);
            }if (file_exists($directory1)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu short!</br> 
                Spróbuj dodać podfolder short ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory2 = "../../filmy/conversion";
            if (!file_exists($directory2)) {
                mkdir($directory2, 0777, true);
            }if (file_exists($directory2)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu conversion!</br>
                Spróbuj dodać podfolder conversion ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory3 = "../../filmy/conversion/delete";
            if (!file_exists($directory3)) {
                mkdir($directory3, 0777, true);
            }if (file_exists($directory3)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu delete!</br>
                Spróbuj dodać podfolder delete ręcznie do folderu filmy/conversion następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory4 = "../../filmy/cut";
            if (!file_exists($directory4)) {
                mkdir($directory4, 0777, true);
            }if (file_exists($directory4)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu cut!</br>
                Spróbuj dodać podfolder cut ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory5 = "../../filmy/cut/delete";
            if (!file_exists($directory5)) {
                mkdir($directory5, 0777, true);
            }if (file_exists($directory5)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu delete!</br>
                Spróbuj dodać podfolder delete ręcznie do folderu filmy/cut następnie spróbuj ponownie przejść do panelu administratora');
            }
            

            $directory6 = "../../filmy/thumbnail";
            if (!file_exists($directory6)) {
                mkdir($directory6, 0777, true);
            }if (file_exists($directory6)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu thumbnail!</br>
                Spróbuj dodać podfolder thumbnail ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory7 = "../../filmy/thumbnail/stars";
            if (!file_exists($directory7)) {
                mkdir($directory7, 0777, true);
            }if (file_exists($directory7)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu stars!</br>
                Spróbuj dodać podfolder stars ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory8 = "../../filmy/thumbnail/studios";
            if (!file_exists($directory8)) {
                mkdir($directory8, 0777, true);
            }if (file_exists($directory8)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu studios!</br>
                Spróbuj dodać podfolder studios ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory9 = "../../filmy/thumbnail/tags";
            if (!file_exists($directory9)) {
                mkdir($directory9, 0777, true);
            }if (file_exists($directory9)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu tags!</br>
                Spróbuj dodać podfolder tags ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
               
            }

            $directory10 = "../../filmy/join";
            if (!file_exists($directory4)) {
                mkdir($directory4, 0777, true);
            }if (file_exists($directory4)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu join!</br>
                Spróbuj dodać podfolder join ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
            }

            $directory11 = "../../filmy/join/delete";
            if (!file_exists($directory5)) {
                mkdir($directory5, 0777, true);
            }if (file_exists($directory5)) {}
            else{
                return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu delete!</br>
                Spróbuj dodać podfolder delete ręcznie do folderu filmy/join następnie spróbuj ponownie przejść do panelu administratora');
            }

           





            // FILMS
            $films_stat = DB::table('films')
            ->get();

            if ($films_stat->isEmpty()) {
                $films_status = "0"; // if database is null
            }else{
                
        
                foreach($films_stat as $films_stat){
                    
                    $name = $films_stat->name;
                    $url = $films_stat->url;
            
                }
            
                // md5 name
                if (file_exists($films_stat->url)){
                        
                    $films_status = "1";
                
                }
                else
                {
                    // id name
                    $films_status = "2";
                }
                

        
            }

            if ($files = glob("../../filmy/" . "/*")) 
            {
                
            }
            else {
                $films_status = "3";
            }

            if(!isset($films_status)){
                $films_status = "4";
            }


        
            // TAGS
            $tags_stat = DB::table('tags')
            ->get();
            if ($tags_stat->isEmpty()) {
                $tags_status = "0";
            }else{
                

                foreach($tags_stat as $tags_stat){
                    
                    $id = $tags_stat->id;
                    $name = $tags_stat->name;
                    $url = $tags_stat->thumbnail;
            
                }
            
                // md5 name
                if (file_exists($tags_stat->thumbnail)){
                        
                    $tags_status = "1";
                
                }
                $path_thumbnail = "../../filmy/thumbnail/tags/".$id.".png";
                // md5 name
                if (!file_exists($path_thumbnail)){
                        
                    $tags_status = "2";
                 
                }  

 
            
            }

            if ($files = glob("../../filmy/thumbnail/tags/" . "/*")) 
            {
                
            }
            else {
                $tags_status = "3";
            }
            
            if(!isset($tags_status)){
                $tags_status = "4";
            }

            // STARS
            $stars_stat = DB::table('stars')
            ->get();

            if ($stars_stat->isEmpty()) {
                $stars_status = "0";
            }else{
                
                foreach($stars_stat as $stars_stat){
                    
                    $id = $stars_stat->id;
                    $name = $stars_stat->name;
                    $url = $stars_stat->thumbnail;
            
                }
            
                // md5 name
                if (file_exists($stars_stat->thumbnail)){
                        
                    $stars_status = "1";
                
                }
                $path_thumbnail = "../../filmy/thumbnail/stars/".$id.".png";
                // md5 name
                if (!file_exists($path_thumbnail)){
                        
                    $stars_status = "2";
                 
                }  
    

            
            }

            if ($files = glob("../../filmy/thumbnail/stars/" . "/*")) 
            {
                
            }
            else {
                $stars_status = "3";
            }

            if(!isset($stars_status)){
                $stars_status = "4";
            }


            // Studios
            $studios_stat = DB::table('studios')
            ->get();
            if ($studios_stat->isEmpty()) {
                $studios_status = "0";
            }else{
               

                foreach($studios_stat as $studios_stat){
                    
                    $id = $studios_stat->id;
                    $name = $studios_stat->name;
                    $url = $studios_stat->thumbnail;
            
                }
            
                // md5 name
                if (file_exists($studios_stat->thumbnail)){
                        
                    $studios_status = "1";
            
                }
                $path_thumbnail = "../../filmy/thumbnail/studios/".$id.".png";
                // md5 name
                if (!file_exists($path_thumbnail)){
                        
                    $studios_status = "2";
                 
                }                
           

           
            }

            if ($files = glob("../../filmy/thumbnail/studios/" . "/*")) 
            {
                
            }
            else {
                $studios_status = "3";
            }

            if(!isset($studios_status)){
                $studios_status = "4";
            }

            // THUMBNAIL
            $thumbnail_stat = DB::table('films')
            ->get();
            if ($thumbnail_stat->isEmpty()) {
                $thumbnail_status = "0";
            }else{
                

                foreach($thumbnail_stat as $thumbnail_stat){
                    
                    $id = $thumbnail_stat->id;
                    $name = $thumbnail_stat->name;
                    $url = $thumbnail_stat->thumbnail;
            
                }
            
                // md5 name
                if (file_exists($thumbnail_stat->thumbnail)){
                        
                    $thumbnail_status = "1";
                
                }

                $path_thumbnail = "../../filmy/thumbnail/".$name.".png";
                // id name
                if (file_exists($path_thumbnail)){
                        
                    $thumbnail_status = "2";
                 
                }
               
                
           
            }
                 
          
            if ($files = glob("../../filmy/thumbnail/" . "/*")) 
            {
                $thumbnail_status = "1";
            }
            else {
                $thumbnail_status = "3";
            }

            if(!isset($thumbnail_status)){
                $thumbnail_status = "4";
            }



 
            
        return view('admin.admin_index', compact('films', 'f_on', 'f_off', 'tags', 'tags_stars', 'tags_studios', 'tags_sites', 'stars', 'studios', 'bytes','films_status','tags_status', 'stars_status', 'studios_status', 'thumbnail_status', 'site', 
        'full_duration_day', 'full_duration_hours', 'full_duration_hours_only', 'full_duration_minutes', 'full_duration_minutes_only', 'full_duration_seconds', 'full_duration'));
        
       
        

    }



   //==================================================================== END ============================================================== //



    public function help(){
    
        return view('admin.admin_extra_info');
    
    }


}
