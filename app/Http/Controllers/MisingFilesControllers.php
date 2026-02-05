<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use App\films;

use App\tags;

use App\tags_stars;

use App\tags_studios;

use App\tags_sites;

use App\stars;

use App\studios;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use Illuminate\Validation\Rules\Exists;

use Illuminate\Support\Facades\File;
use PhpParser\Node\Expr\Print_;

class MisingFilesControllers extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }


    //==================================================================== absence_films =========================================================== //
    public function absence_films(){

        $films = DB::table('films')
        ->get();
 
        
        
        foreach($films as $films){
            $id = $films->id;
   

            if (!file_exists($films->url)){
                    
                $no_films = "0";
                
            }
            else
            {
                $no_films = "1";
            }

            if (!file_exists($films->thumbnail)){
                        
                $no_thumbnail = "0";
                
            }
            else
            {
                $no_thumbnail = "1";
            }

            if (!file_exists($films->short)){
                        
                $no_short = "0";
                
            
            }
            else
            {
                $no_short = "1";
            
            }


            $films = films::find($id);
            $films->no_films = $no_films;
            $films->no_thumbnail = $no_thumbnail;
            $films->no_short = $no_short;

            $films->save();
    
        }

        $films = DB::table('films')
        ->where('no_films', '=', 0)
        ->orWhere('no_thumbnail', '=', 0)
        ->orWhere('no_short', '=', 0 )
        ->paginate(27);


        $films_status = DB::table('films')
        ->where('no_films', '=', 0)
        ->orWhere('no_thumbnail', '=', 0)
        ->orWhere('no_short', '=', 0 )
        ->count();

        return view('admin.admin_absence_films', compact('films', 'films_status'));
        
    }






    //==================================================================== absence_tags =========================================================== //
    
    public function absence_tags(){

        $tags = DB::table('tags')
        ->get();
 
        
        
        foreach($tags as $tags){
            $id = $tags->id;

            if (!file_exists($tags->thumbnail)){
                        
                $no_tags = "0";
            
            }
            else
            {
                $no_tags = "1";
            }


        $tags = tags::find($id);
        $tags->no_tags = $no_tags;


        $tags->save();
    
        }

        $tags = DB::table('tags')
        ->where('no_tags', '=', 0)
        ->paginate(27);


        $tags_status = DB::table('tags')
        ->where('no_tags', '=', 0)
        ->count();

        $check_db_tags = 1;

        return view('admin.admin_absence_tags', compact('tags', 'tags_status', 'check_db_tags'));
        
    }


    //==================================================================== absence_tags_stars =========================================================== //
    
    public function absence_tags_stars(){

        $tags = DB::table('tags_stars')
        ->get();
 
        
        
        foreach($tags as $tags){
            $id = $tags->id;

            if (!file_exists($tags->thumbnail)){
                        
                $no_tags = "0";
            
            }
            else
            {
                $no_tags = "1";
            }


            $tags = tags_stars::find($id);
            $tags->no_tags = $no_tags;
            $tags->save();

        }

        $tags = DB::table('tags_stars')
        ->where('no_tags', '=', 0)
        ->paginate(27);


        $tags_status = DB::table('tags_stars')
        ->where('no_tags', '=', 0)
        ->count();

        $check_db_stars = 1;

        return view('admin.admin_absence_tags', compact('tags', 'tags_status', 'check_db_stars'));
        
    }



    //==================================================================== absence_tags_studios =========================================================== //
    
    public function absence_tags_studios(){

        $tags = DB::table('tags_studios')
        ->get();
 
        
        
        foreach($tags as $tags){
            $id = $tags->id;

            if (!file_exists($tags->thumbnail)){
                        
                $no_tags = "0";
            
            }
            else
            {
                $no_tags = "1";
            }


        $tags = tags_studios::find($id);
        $tags->no_tags = $no_tags;


        $tags->save();
    
        }

        $tags = DB::table('tags_studios')
        ->where('no_tags', '=', 0)
        ->paginate(27);


        $tags_status = DB::table('tags_studios')
        ->where('no_tags', '=', 0)
        ->count();

        $check_db_studios = 1;

        return view('admin.admin_absence_tags', compact('tags', 'tags_status','check_db_studios'));
        
    }


    //==================================================================== absence_tags_sites =========================================================== //
    
    public function absence_tags_sites(){

        $tags = DB::table('tags_sites')
        ->get();
 
        
        
        foreach($tags as $tags){
            $id = $tags->id;

            if (!file_exists($tags->thumbnail)){
                        
                $no_tags = "0";
            
            }
            else
            {
                $no_tags = "1";
            }


        $tags = tags_sites::find($id);
        $tags->no_tags = $no_tags;


        $tags->save();
    
        }

        $tags = DB::table('tags_sites')
        ->where('no_tags', '=', 0)
        ->paginate(27);


        $tags_status = DB::table('tags_sites')
        ->where('no_tags', '=', 0)
        ->count();

        $check_db_sites = 1;

        return view('admin.admin_absence_tags', compact('tags', 'tags_status', 'check_db_sites'));
        
    }






    //==================================================================== absence_stars =========================================================== //
    
    public function absence_stars(){

        $stars = DB::table('stars')
        ->get();
 
        
        
        foreach($stars as $stars){
            $id = $stars->id;

            if (!file_exists($stars->thumbnail)){
                        
                $no_stars = "0";
            
            }
            else
            {
                $no_stars = "1";
            }


        $stars = stars::find($id);
        $stars->no_stars = $no_stars;


        $stars->save();
    
        }

        $stars = DB::table('stars')
        ->where('no_stars', '=', 0)
        ->paginate(27);


        $stars_status = DB::table('stars')
        ->where('no_stars', '=', 0)
        ->count();

        return view('admin.admin_absence_stars', compact('stars', 'stars_status'));
        
    }




    //==================================================================== absence_studios =========================================================== //
    
    public function absence_studios(){

        $studios = DB::table('studios')
        ->get();
 
        
        
        foreach($studios as $studios){
            $id = $studios->id;

            if (!file_exists($studios->thumbnail)){
                        
                $no_studios = "0";
            
            }
            else
            {
                $no_studios = "1";
            }


        $studios = studios::find($id);
        $studios->no_studios = $no_studios;


        $studios->save();
    
        }

        $studios = DB::table('studios')
        ->where('no_studios', '=', 0)
        ->paginate(27);


        $studios_status = DB::table('studios')
        ->where('no_studios', '=', 0)
        ->count();

        return view('admin.admin_absence_studios', compact('studios', 'studios_status'));
        
    }
   



    //==================================================================== absence_files =========================================================== //
 
    public function absence_files_films()
    {
        $films = DB::table('films')->get();

        $db_filenames = []; // nazwy plików w bazie
        $folders_to_scan = []; // foldery do przeszukania

        // Zbierz nazwy plików z bazy
        foreach ($films as $film) {
            $url = str_replace('\\', '/', $film->url); // normalizuj slashe
            $basename = strtolower(basename($url));
            $db_filenames[$basename] = true;

            // folder, w którym film się znajduje
            $parts = explode('/', $url);
            $folders_to_scan[] = implode('/', array_slice($parts, 0, -1));
        }

        $folders_to_scan = array_unique($folders_to_scan);
        $missing_files = [];

        // Skanowanie folderów
        foreach ($folders_to_scan as $folder) {

            if (!is_dir($folder)) continue;

            $handle = opendir($folder);
            if (!$handle) continue;

            while (false !== ($entry = readdir($handle))) {

                if (in_array($entry, [".", "..", "conversion", "cut", "short", "thumbnail", "join"])) {
                    continue;
                }

                $entry_lc = strtolower($entry);

                // jeśli pliku nie ma w bazie, dodaj go do listy brakujących
                if (!isset($db_filenames[$entry_lc])) {
                    $missing_files[] = $folder . '/' . $entry;
                }
            }

            closedir($handle);
        }

        return view('admin.absence_files.admin_absence_files', [
            'folders_to_scan' => $folders_to_scan,
            'file_not_exist' => $missing_files,
            'check_db_films' => 1
        ]);
    }






    public function absence_files_short()
    {
        $films = DB::table('films')->get();

        $db_short_filenames = []; // nazwy short z bazy
        $folders_to_scan = [];    // foldery do przeszukania

        foreach ($films as $film) {
            $short = str_replace('\\', '/', $film->short); // normalizacja slasha
            $basename = strtolower(basename($short));
            $db_short_filenames[$basename] = true;

            // folder, w którym short się znajduje
            $parts = explode('/', $short);
            $folders_to_scan[] = implode('/', array_slice($parts, 0, -1));
        }

        $folders_to_scan = array_unique($folders_to_scan);
        $file_not_exist_short = [];

        // Skanowanie folderów
        foreach ($folders_to_scan as $folder_path) {

            if (!is_dir($folder_path)) continue;

            $handle = opendir($folder_path);
            if (!$handle) continue;

            while (false !== ($entry = readdir($handle))) {

                if (in_array($entry, [".", ".."])) continue;

                $entry_lc = strtolower($entry);

                // jeśli pliku short nie ma w bazie, dodaj do listy brakujących
                if (!isset($db_short_filenames[$entry_lc])) {
                    $file_not_exist_short[] = $folder_path . '/' . $entry;
                }
            }

            closedir($handle);
        }

        $check_db_short = 1;

        return view('admin.absence_files.admin_absence_files', [
            'folders_to_scan' => $folders_to_scan,
            'file_not_exist_short' => $file_not_exist_short,
            'check_db_short' => $check_db_short
        ]);
    }




    public function absence_files_thumbnail()
    {
        $films = DB::table('films')->get();

        $db_thumbnail_filenames = []; // nazwy thumbnail z bazy
        $folders_to_scan = [];        // foldery do przeszukania

        foreach ($films as $film) {
            $thumbnail = str_replace('\\', '/', $film->thumbnail); // normalizacja slasha
            $basename = strtolower(basename($thumbnail));
            $db_thumbnail_filenames[$basename] = true;

            // folder, w którym thumbnail się znajduje
            $parts = explode('/', $thumbnail);
            $folders_to_scan[] = implode('/', array_slice($parts, 0, -1));
        }

        $folders_to_scan = array_unique($folders_to_scan);
        $file_not_exist_thumbnail = [];

        // Skanowanie folderów
        foreach ($folders_to_scan as $folder_path) {

            if (!is_dir($folder_path)) continue;

            $handle = opendir($folder_path);
            if (!$handle) continue;

            while (false !== ($entry = readdir($handle))) {

                if (in_array($entry, [".", "..", "tags", "stars", "studios", "tags_sites", "tags_stars", "tags_studios"])) {
                    continue;
                }

                $entry_lc = strtolower($entry);

                // jeśli pliku thumbnail nie ma w bazie, dodaj do listy brakujących
                if (!isset($db_thumbnail_filenames[$entry_lc])) {
                    $file_not_exist_thumbnail[] = $folder_path . '/' . $entry;
                }
            }

            closedir($handle);
        }

        $check_db_thumbnail = 1;

        return view('admin.absence_files.admin_absence_files', [
            'folders_to_scan' => $folders_to_scan,
            'file_not_exist_thumbnail' => $file_not_exist_thumbnail,
            'check_db_thumbnail' => $check_db_thumbnail
        ]);
    }




    public function absence_files_tags(){

        $tags = DB::table('tags')        
        ->get();

        foreach ($tags as $tags){
            $tags_url = $tags->thumbnail;

            $inside_tags = explode("/", $tags_url);
            
            // films
            $inside_tags_url = implode('/', array_slice($inside_tags, 0, -1));            
            $inside_tags_url_array[] = $inside_tags_url;
        
        }
    
        $inside_tags_url = array_unique($inside_tags_url_array);
        foreach($inside_tags_url as $inside_tags_url) {
        
            if(file_exists($inside_tags_url)){
            
                if ($handle = opendir($inside_tags_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_tags_url."/".$folder_file;

                            $tags_status = DB::table('tags')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($tags_status > 0 ){                    
                            }
                            else
                            {                              
                                $tags_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }

        if(!isset($tags_not_exist)){
            $tags_not_exist = [];
        }

        $check_db_tags = 1;

        return view('admin.absence_files.admin_absence_files', compact('inside_tags_url','tags_not_exist', 'check_db_tags'));


    }



    public function absence_files_tags_stars(){

        $tags = DB::table('tags_stars')        
        ->get();

        $tags_count_stars = DB::table('tags_stars')        
        ->count();

        if ($tags_count_stars > 0 ){
        foreach ($tags as $tags){
            $tags_url = $tags->thumbnail;

            $inside_tags = explode("/", $tags_url);
            
            // films
            $inside_tags_url = implode('/', array_slice($inside_tags, 0, -1));            
            $inside_tags_url_array[] = $inside_tags_url;
        
        }
    
        $inside_tags_url = array_unique($inside_tags_url_array);
        foreach($inside_tags_url as $inside_tags_url) {
        
            if(file_exists($inside_tags_url)){
            
                if ($handle = opendir($inside_tags_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_tags_url."/".$folder_file;

                            $tags_status = DB::table('tags_stars')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($tags_status > 0 ){                    
                            }
                            else
                            {                              
                                $tags_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }
        }else{
            $inside_tags_url = "../../filmy/thumbnail/tags_stars";
            if ($handle = opendir($inside_tags_url)) {

                while (false !== ($folder_file = readdir($handle))) {
            
                    if ($folder_file != "." && $folder_file != "..") {
            
                        $chech_db = $inside_tags_url."/".$folder_file;

                        $tags_status = DB::table('tags_stars')
                        ->where('thumbnail', '=', $chech_db)
                        ->count();

                        if ($tags_status > 0 ){                    
                        }
                        else
                        {                              
                            $tags_not_exist[] = $chech_db;
                        }


                    }
                }
            
                closedir($handle);
            }
        }

        if(!isset($tags_not_exist)){
            $tags_not_exist = [];
        }

        $check_db_tags_stars = 1;

        return view('admin.absence_files.admin_absence_files', compact('tags_not_exist', 'check_db_tags_stars', 'tags_count_stars'));


    }



    public function absence_files_tags_studios(){

        $tags = DB::table('tags_studios')        
        ->get();

        $tags_count_studios = DB::table('tags_studios')        
        ->count();

        if ($tags_count_studios > 0 ){

        foreach ($tags as $tags){
            $tags_url = $tags->thumbnail;

            $inside_tags = explode("/", $tags_url);
            
            // films
            $inside_tags_url = implode('/', array_slice($inside_tags, 0, -1));            
            $inside_tags_url_array[] = $inside_tags_url;
        
        }
    
        $inside_tags_url = array_unique($inside_tags_url_array);
        foreach($inside_tags_url as $inside_tags_url) {
        
            if(file_exists($inside_tags_url)){
            
                if ($handle = opendir($inside_tags_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_tags_url."/".$folder_file;

                            $tags_status = DB::table('tags_studios')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($tags_status > 0 ){                    
                            }
                            else
                            {                              
                                $tags_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }
        }else{
            $inside_tags_url = "../../filmy/thumbnail/tags_studios";
            if ($handle = opendir($inside_tags_url)) {

                while (false !== ($folder_file = readdir($handle))) {
            
                    if ($folder_file != "." && $folder_file != "..") {
            
                        $chech_db = $inside_tags_url."/".$folder_file;

                        $tags_status = DB::table('tags_stars')
                        ->where('thumbnail', '=', $chech_db)
                        ->count();

                        if ($tags_status > 0 ){                    
                        }
                        else
                        {                              
                            $tags_not_exist[] = $chech_db;
                        }


                    }
                }
            
                closedir($handle);
            }
        }

        if(!isset($tags_not_exist)){
            $tags_not_exist = [];
        }

        $check_db_tags_studios = 1;

        return view('admin.absence_files.admin_absence_files', compact('tags_not_exist', 'check_db_tags_studios', 'tags_count_studios'));


    }


    public function absence_files_tags_sites(){

        $tags = DB::table('tags_sites')        
        ->get();

        $tags_count_sites = DB::table('tags_sites')        
        ->count();

        if ($tags_count_sites > 0 ){

        foreach ($tags as $tags){
            $tags_url = $tags->thumbnail;

            $inside_tags = explode("/", $tags_url);
            
            // films
            $inside_tags_url = implode('/', array_slice($inside_tags, 0, -1));            
            $inside_tags_url_array[] = $inside_tags_url;
        
        }
    
        $inside_tags_url = array_unique($inside_tags_url_array);
        foreach($inside_tags_url as $inside_tags_url) {
        
            if(file_exists($inside_tags_url)){
            
                if ($handle = opendir($inside_tags_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_tags_url."/".$folder_file;

                            $tags_status = DB::table('tags_sites')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($tags_status > 0 ){                    
                            }
                            else
                            {                              
                                $tags_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }
        }else{
            $inside_tags_url = "../../filmy/thumbnail/tags_sites";
            if ($handle = opendir($inside_tags_url)) {

                while (false !== ($folder_file = readdir($handle))) {
            
                    if ($folder_file != "." && $folder_file != "..") {
            
                        $chech_db = $inside_tags_url."/".$folder_file;

                        $tags_status = DB::table('tags_stars')
                        ->where('thumbnail', '=', $chech_db)
                        ->count();

                        if ($tags_status > 0 ){                    
                        }
                        else
                        {                              
                            $tags_not_exist[] = $chech_db;
                        }


                    }
                }
            
                closedir($handle);
            }
        }


        if(!isset($tags_not_exist)){
            $tags_not_exist = [];
        }

        $check_db_tags_sites = 1;

        return view('admin.absence_files.admin_absence_files', compact('tags_not_exist', 'check_db_tags_sites', 'tags_count_sites'));


    }


    public function absence_files_stars(){  
    $stars = DB::table('stars')        
        ->get();
        
        foreach ($stars as $stars){
            $stars_url = $stars->thumbnail;

            $inside_stars = explode("/", $stars_url);
            
            // films
            $inside_stars_url = implode('/', array_slice($inside_stars, 0, -1));            
            $inside_stars_url_array[] = $inside_stars_url;
        
        }


        $inside_stars_url = array_unique($inside_stars_url_array);
        foreach($inside_stars_url as $inside_stars_url) {
        
            if(file_exists($inside_stars_url)){
            
                if ($handle = opendir($inside_stars_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_stars_url."/".$folder_file;

                            $stars_status = DB::table('stars')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($stars_status > 0 ){                    
                            }
                            else
                            {                              
                                $stars_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }

        if(!isset($stars_not_exist)){
            $stars_not_exist = [];
        }

        $check_db_stars = 1;

        return view('admin.absence_files.admin_absence_files', compact('inside_stars_url','stars_not_exist', 'check_db_stars'));
    }




    public function absence_files_studios(){  
        $studios = DB::table('studios')        
        ->get();

        foreach ($studios as $studios){
            $studios_url = $studios->thumbnail;

            $inside_studios = explode("/", $studios_url);
            
            // films
            $inside_studios_url = implode('/', array_slice($inside_studios, 0, -1));            
            $inside_studios_url_array[] = $inside_studios_url;
        
        }
    

        $inside_studios_url = array_unique($inside_studios_url_array);
        foreach($inside_studios_url as $inside_studios_url) {

            if(file_exists($inside_studios_url)){
            
                if ($handle = opendir($inside_studios_url)) {

                    while (false !== ($folder_file = readdir($handle))) {
                
                        if ($folder_file != "." && $folder_file != "..") {
                
                            $chech_db = $inside_studios_url."/".$folder_file;

                            $studios_status = DB::table('studios')
                            ->where('thumbnail', '=', $chech_db)
                            ->count();

                            if ($studios_status > 0 ){                    
                            }
                            else
                            {                              
                                $studios_not_exist[] = $chech_db;
                            }


                        }
                    }
                
                    closedir($handle);
                }

            }
        }

        if(!isset($studios_not_exist)){
            $studios_not_exist = [];
        }

        $check_db_studios = 1;

        return view('admin.absence_files.admin_absence_files', compact('inside_studios_url','studios_not_exist', 'check_db_studios'));

    }






    // ===================================================================== Delete and Show =============================================================== //


    public function delete_absence_files(){
        $url = $_POST["delete_files_url"];

        
        if (is_dir($url)) {
            if (file_exists($url)){                
                $string = explode("/", $url);
                $url_film = implode('/', array_slice($string, -0));
                File::deleteDirectory($url_film);
                return redirect()->back()->with('success', $url.' został usunięty!');
            }
            else{
                return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy wykonać tej operacji.</br> Prosimy o kontakt z administratorem.');
            }    
        }
            
        
        

        if (!is_dir($url)) {
            if (file_exists($url)){
                unlink($url);
                return redirect()->back()->with('success', $url.' został usunięty!');
            }
            else{
                return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy wykonać tej operacji.</br> Prosimy o kontakt z administratorem.');
            }
        }

    }



    public function show_absence_files(){
        $url = $_POST["show_files_url"];
        $url_folder = $_POST["show_files_folder"];
        $show_files_folder_number = $_POST["show_files_folder_number"];

        if($show_files_folder_number > 0){
    
            echo $show_files_folder_number;
            $string = explode("/", $url);
            $url_film = implode('\\', array_slice($string, 0, 5));
            $url_filmm = implode('\\', array_slice($string, 0, 6));
        }
        else
        {
            $string = explode("/", $url);
            $url_film = implode('\\', array_slice($string, 0, 4));
            $url_filmm = implode('\\', array_slice($string, 0, 5));
        }

        if (file_exists($url)){
            if(file_exists($url_filmm)){
            shell_exec('explorer /select, '.$url_filmm.'');
            }
            else
            {
                shell_exec('start '.$url_film.'');  
            }
            return redirect()->back();
        }
        else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy wyświetlić folderu.</br> Prosimy o kontakt z administratorem.');
        }

    }








   


}
