<?php

namespace App\Http\Controllers;

use App\films;
use Illuminate\Http\Request;

use PDO;



use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;


class OperationDatabaseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }


    //==================================================================== Blade =========================================================== //
    public function operation_database(){

        // FILMS
        $films = DB::table('films')
        ->get();

        if ($films->isEmpty()) {
            $films_status = "2"; // if database is null
        }else{
            
            foreach($films as $films){
                
                $name = $films->name;
                $url = $films->url;
            
            }
            
            // md5 name
            if (file_exists($films->url)){
                    
                $films_status = "1";
                
            }
            else
            {
                // id name
                $films_status = "0";
            }

       
        }


        // TAGS
        $tags = DB::table('tags')
        ->get();
        if ($tags->isEmpty()) {
            $tags_status = "2"; // if database is null
        }else{
            
            foreach($tags as $tags){
                
                $name = $tags->name;
                $url = $tags->thumbnail;
            
            }
            
            // md5 name
            if (file_exists($tags->thumbnail)){
                    
                $tags_status = "1";
                
            }
            else
            {
                // id name
                $tags_status = "0";
            }

       
        }
        

        // STARS
        $stars = DB::table('stars')
        ->get();
        if ($stars->isEmpty()) {
            $stars_status = "2"; // if database is null
        }else{
            
            foreach($stars as $stars){
                
                $name = $stars->name;
                $url = $stars->thumbnail;
        
            }
        
            // md5 name
            if (file_exists($stars->thumbnail)){
                    
                $stars_status = "1";
            
            }
            else
            {
                // id name
                $stars_status = "0";
            }
      
        }



        // Studios
        $studios = DB::table('studios')
        ->get();
        if ($studios->isEmpty()) {
            $studios_status = "2"; // if database is null
        }else{
            
            
            foreach($studios as $studios){
                
                $name = $studios->name;
                $url = $studios->thumbnail;
        
            }
        
            // md5 name
            if (file_exists($studios->thumbnail)){
                    
                $studios_status = "1";
        
            }
            else
            {
                // id name
                $studios_status = "0";
            }
        
        }


        // THUMBNAIL
        $thumbnail = DB::table('films')
        ->get();
        if ($thumbnail->isEmpty()) {
            $thumbnail_status = "2"; // if database is null
        }else{
            

            foreach($thumbnail as $thumbnail){
                
                $name = $thumbnail->name;
                $url = $thumbnail->thumbnail;
        
            }
        
            // md5 name
            if (file_exists($thumbnail->thumbnail)){
                    
                $thumbnail_status = "1";
            
            }
            else
            {
                // id name
                $thumbnail_status = "0";
            }

        
        }



      return view('admin.operation_database', compact('films_status', 'tags_status', 'stars_status', 'studios_status', 'thumbnail_status'));
       
    }














    //==================================================================== create txt site =========================================================== //
    public function create_site_txt(){

        
        $site = DB::table('site')
        ->get();
        
        $url = "../../strony.txt";
        if (file_exists($url)){
            return redirect()->back()->with('errors', 'Plik o nazwie strony.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń plik lub przenieś go w inne miejsce i spróbuj ponownie');
        }

        if (!file_exists($url)){
                
                $myfile = fopen($url, "w") or die("Unable to open file!");
                $txt = "";
                foreach($site as $site){
                
                    $name = $site->name;
                    $link = $site->link;
                    $description = $site->description;
                    
                    $txt .= "Nazwa: ".$name."\n".
                            "Link: ".$link."\n".
                            "Opis: ".$description."\n"."\n"."\n"."";
                   
                }
                
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Plik ze stronami został poprawnie utworzony znajdziesz go w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć pliku strony.txt które masz zapisane w bazie danych. Prosimy o kontakt z administratorem.');
        }


       
    }

    public function open_main_folder_out_videosite(){
        
        $url_film = "..\\..\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }



    //==================================================================== create txt tags =========================================================== //
    public function create_tags_txt(){

        
        $tags = DB::table('tags')
        ->get();
        
        $url = "../../tagi.txt";
        if (file_exists($url)){
            return redirect()->back()->with('errors', 'Plik o nazwie tagi.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń plik lub przenieś go w inne miejsce i spróbuj ponownie');
        }

        if (!file_exists($url)){
                
                $myfile = fopen($url, "w") or die("Unable to open file!");
                $txt = "";
                foreach($tags as $tags){
                
                    $name = $tags->name;
                    
                    $txt .= "Nazwa Tagu: ".$name."\n"."\n";
                   
                }

                $tagss = DB::table('tags')
                ->get();

                $txt .= "\n"."\n";
                $txt .=  "Tagi: ";
                foreach($tagss as $tags){
                    $name = $tags->name;                        
                    
                    $txt .=  " ".$name.",";
                }
                $txt .= "\n";
                
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Plik z tagami został poprawnie utworzony znajdziesz go w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć pliku tagi.txt z bazie danych. Prosimy o kontakt z administratorem.');
        }


       
    }



    //==================================================================== create txt stars =========================================================== //
    public function create_stars_txt(){

        
        $stars = DB::table('stars')
        ->get();
        
        $url = "../../gwiazdy.txt";
        if (file_exists($url)){
            return redirect()->back()->with('errors', 'Plik o nazwie gwiazdy.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń plik lub przenieś go w inne miejsce i spróbuj ponownie');
        }

        if (!file_exists($url)){
                
                $myfile = fopen($url, "w") or die("Unable to open file!");
                $txt = "";
                foreach($stars as $stars){
                
                    $name = $stars->name;
                    
                    $txt .= "Imie i Nazwisko: ".$name."\n"."\n";
                   
                }

                $stars = DB::table('stars')
                ->get();

                $txt .= "\n"."\n";
                $txt .=  "Imie i nazwisko Gwiazd: ";
                foreach($stars as $stars){
                    $name = $stars->name;                        
                    
                    $txt .=  " ".$name.",";
                }
                $txt .= "\n";
                
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Plik z Gwiazdami został poprawnie utworzony znajdziesz go w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć pliku gwiazdy.txt które masz zapisane w bazie danych. Prosimy o kontakt z administratorem.');
        }


       
    }






    //==================================================================== create txt studios =========================================================== //
    public function create_studios_txt(){

        
        $studios = DB::table('studios')
        ->get();
        
        $url = "../../wytwornie.txt";
        if (file_exists($url)){
            return redirect()->back()->with('errors', 'Plik o nazwie wytwornie.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń plik lub przenieś go w inne miejsce i spróbuj ponownie');
        }

        if (!file_exists($url)){
                
                $myfile = fopen($url, "w") or die("Unable to open file!");
                $txt = "";
                foreach($studios as $studios){
                
                    $name = $studios->name;
                    
                    $txt .= "Nazwa Wytwórni: ".$name."\n"."\n";
                   
                }

                $studios = DB::table('studios')
                ->get();

                $txt .= "\n"."\n";
                $txt .=  "Nazwa Wytwórni: ";
                foreach($studios as $studios){
                    $name = $studios->name;                        
                    
                    $txt .=  " ".$name.",";
                }
                $txt .= "\n";
                
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Plik z Wytwórniami został poprawnie utworzony znajdziesz go w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć pliku wytwornie.txt które masz zapisane w bazie danych. Prosimy o kontakt z administratorem.');
        }


       
    }

    public function create_special_txt(){
        $url = "../../filmy.txt";
        $url1 = "../../tagi.txt";
        $url2 = "../../gwiazdy.txt";
        $url3 = "../../wytwornie.txt";
        $url4 = "../../strony.txt";

        if (file_exists($url) OR file_exists($url1) OR file_exists($url2) OR file_exists($url3) OR file_exists($url4)){
            return redirect()->back()->with('errors', 'Plik o nazwie filmy.txt, tagi.txt, gwiazdy.txt, wytwornie.txt lub strony.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń pliki lub przenieś je w inne miejsce i spróbuj ponownie');
        }


        

        $this->create_films_txt();
        $this->create_tags_txt();
        $this->create_stars_txt();
        $this->create_studios_txt();
        $this->create_site_txt();


        


        if (file_exists($url)){
        return redirect()->back()->with('success', 'Pliki zostały utworzone poprawne znajdziesz je w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć plików. Prosimy o kontakt z administratorem.');
        }
    }



    //==================================================================== Change_film_name =========================================================== //

    public function change_film_name(){

        
        $films = DB::table('films')
        ->get();

        foreach($films as $films){
            
            $name = $films->name;
            $url = $films->url;
            $short = $films->short;
            $thumbnail = $films->thumbnail;
            
        
            $exp_url = explode(" ", $url);
            $folder = explode("/", $exp_url[0]);
            $folder_name = $folder[3];

         

            $folder_namee = substr($url, 6);
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.
            
  
            $new_name = "../../filmy/".$folder_name."/".$del_special_char_name.".mp4";

            if (strpos($folder_name,'.mp4') !== false || strpos($folder_name,'.avi') !== false || strpos($folder_name,'.mp3') !== false || strpos($folder_name,'.mkv') !== false || strpos($folder_name,'.cam') !== false){
                $folder_name = $folder[2];
                $new_name = "../../".$folder_name."/".$del_special_char_name.".mp4";
            }

            if (file_exists($url)){
                
                rename($url, $new_name);
            
            }
        }

        if (file_exists($new_name)){
        return redirect()->back()->with('success', 'Nazwy filmów zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }


    //==================================================================== Change_film_name_md5 =========================================================== //

    public function change_film_name_md5(){

        
        $films = DB::table('films')
        ->get();

        foreach($films as $films){
            
            $name = $films->name;
            $url = $films->url;
            $short = $films->short;
            $thumbnail = $films->thumbnail;
        

            $exp_url = explode(" ", $url);
            $folder = explode("/", $exp_url[0]);
            $folder_name = $folder[3];
            

            $folder_namee = substr($url, 6);
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.
            
            
            $new_name = "../../filmy/".$folder_name."/".$del_special_char_name.".mp4";

          
            if (strpos($folder_name,'.mp4') !== false || strpos($folder_name,'.avi') !== false || strpos($folder_name,'.mp3') !== false || strpos($folder_name,'.mkv') !== false || strpos($folder_name,'.cam') !== false){
                $folder_name = $folder[2];
                $new_name = "../../".$folder_name."/".$del_special_char_name.".mp4";
            }

            if (file_exists($new_name)){
                
                rename($new_name, $url);

            }
        }

        if (file_exists($url)){
            return redirect()->back()->with('success', 'Nazwy filmów zostały zmienione na szyfrowane.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }



    



    //==================================================================== change_tags_name =========================================================== //
    
    public function change_tags_name(){

        
        $tags = DB::table('tags')
        ->get();

        foreach($tags as $tags){

            $id = $tags->id;
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
            
            $url = "../../filmy/thumbnail/tags/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/tags/".$del_special_char_name.".png";

            if (file_exists($url)){
                
                rename($url, $new_name);
            
            }
        }

        if (file_exists($new_name)){
        return redirect()->back()->with('success', 'Nazwy tagów zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }




    //==================================================================== change_tags_name_md5 =========================================================== //
    
    public function change_tags_name_md5(){

        
        $tags = DB::table('tags')
        ->get();

        foreach($tags as $tags){

            $id = $tags->id;
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
            
            $url = "../../filmy/thumbnail/tags/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/tags/".$del_special_char_name.".png";

            if (file_exists($new_name)){
                
                rename($new_name, $url);
            
            }
        }

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Nazwy tagów zostały zmienione na szyfrowane.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }





    //==================================================================== change_stars_name =========================================================== //
    
    public function change_stars_name(){

        
        $stars = DB::table('stars')
        ->get();

        foreach($stars as $stars){

            $id = $stars->id;
            $name = $stars->name;
            $thumbnail = $stars->thumbnail;
            
            $url = "../../filmy/thumbnail/stars/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/stars/".$del_special_char_name.".png";

            if (file_exists($url)){
                
                rename($url, $new_name);
            
            }
        }

        if (file_exists($new_name)){
        return redirect()->back()->with('success', 'Nazwy gwiazd zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }



    //==================================================================== change_stars_name_md5 =========================================================== //
    
    public function change_stars_name_md5(){

        
        $stars = DB::table('stars')
        ->get();

        foreach($stars as $stars){

            $id = $stars->id;
            $name = $stars->name;
            $thumbnail = $stars->thumbnail;
            
            $url = "../../filmy/thumbnail/stars/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/stars/".$del_special_char_name.".png";

            if (file_exists($new_name)){
                
                rename($new_name, $url);
            
            }
        }

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Nazwy gwiazd zostały zmienione na szyfrowane.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }




    //==================================================================== create films site =========================================================== //
    public function create_films_txt(){

        
        $films = DB::table('films')
        ->get();

            
        $url = "../../filmy.txt";
        if (file_exists($url)){
            return redirect()->back()->with('errors', 'Plik o nazwie filmy.txt istnieje już w folderze głównym ze względów bezpieczeństwa nie nadpisujemy plików.</br>
            Usuń plik lub przenieś go w inne miejsce i spróbuj ponownie');
        }

        if (!file_exists($url)){
                
                $myfile = fopen($url, "w") or die("Unable to open file!");
                $txt = "";
                foreach($films as $films){
                
                    $id = $films->id;
                    $name = $films->name;
                    $rating = $films->rating;
                    
                      
                   
                }

                $i = 0;
                while ($i <= $id) {
                    $new_id = $i++; 
                
                    if (films::where('id', '=', $new_id)->exists()) {
                        $films = DB::table('films')
                        ->where('id', $new_id)
                        ->get();
                        foreach($films as $films){
                            $films_id = $films->id;
                            $films_name = $films->name;
                            $films_rating = $films->rating;
                          
                            $txt .= "ID: ".$films_id."\n".
                            "Ocena: ".$films_rating."\n".
                            "Nazwa: ".$films_name."\n";
                            
                            
                            
                        }
                        

                        $tags = DB::table('films')
                        ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
                        ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
                        ->select('tags.name', 'films_tags.id', 'tags.id')
                        ->where('films.id', $new_id)
                        ->distinct()
                        ->get();

                        $txt .=  "Tagi: ";
                        foreach($tags as $tags){
                            $tags_name = $tags->name;                            
                            
                            $txt .=  " ".$tags_name.",";
                        }
                        $txt .= "\n";
    
                        $stars = DB::table('films')
                        ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
                        ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
                        ->select('stars.name', 'films_stars.id', 'stars.id')
                        ->where('films.id', $new_id)
                        ->distinct()
                        ->get();
    
                        $txt .=  "Gwiazdy: ";
                        foreach($stars as $stars){
                            $stars_name = $stars->name;                            
                          
                            $txt .= " ".$stars_name.",";
                        }
                    
                        $txt .= "\n";
                        $studios = DB::table('films')
                        ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
                        ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
                        ->select('studios.name', 'films_studios.id', 'studios.id')
                        ->where('films.id', $new_id)
                        ->distinct()
                        ->get();
    
                        $txt .= "Wytwórnie: ";
                        foreach($studios as $studios){
                            $studios_name = $studios->name;
                            
                            $txt .= " ".$studios_name.",";
                        
    
                        }
                        $txt .= "\n";
                        $txt .= "\n";
                        $txt .= "\n";
                       

                    }

                }
                
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Plik z filmami został poprawnie utworzony znajdziesz go w głównym folderze.');
        }else{
            return redirect()->back()->with('errors', 'Przepraszamy niestety nie możemy utworzyć pliku filmy.txt które masz zapisane w bazie danych. Prosimy o kontakt z administratorem.');
        }


       
    }




    //==================================================================== CHANGE NAME STUDIOS =========================================================== //

    public function change_studios_name(){

        
        $studios = DB::table('studios')
        ->get();

        foreach($studios as $studios){

            $id = $studios->id;
            $name = $studios->name;
            $thumbnail = $studios->thumbnail;
            
            $url = "../../filmy/thumbnail/studios/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/studios/".$del_special_char_name.".png";

            if (file_exists($url)){
                
                rename($url, $new_name);
            
            }
        }

        if (file_exists($new_name)){
        return redirect()->back()->with('success', 'Nazwy wytwórni zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }

 
    //==================================================================== Change_studios_name_md5 =========================================================== //

    public function change_studios_name_md5(){

        
        $studios = DB::table('studios')
        ->get();

        foreach($studios as $studios){

            $id = $studios->id;
            $name = $studios->name;
            $thumbnail = $studios->thumbnail;
            
            $url = "../../filmy/thumbnail/studios/".$id.".png";
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/studios/".$del_special_char_name.".png";

            if (file_exists($new_name)){
                
                rename($new_name, $url);
            
            }
        }

        if (file_exists($url)){
        return redirect()->back()->with('success', 'Nazwy wytwórni zostały zmienione na szyfrowane.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }





    

    //==================================================================== CHANGE NAME STUDIOS =========================================================== //

    public function change_thumbnail_name(){

        
        $films = DB::table('films')
        ->get();

        foreach($films as $films){

            $id = $films->id;
            $name = $films->name;
            $thumbnail = $films->thumbnail;
            
           
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/".$del_special_char_name.".png";

            if (file_exists($thumbnail)){
                
                rename($thumbnail, $new_name);
            
            }
        }

        if (file_exists($new_name)){
        return redirect()->back()->with('success', 'Nazwy zdjęć filmów zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }


    public function change_thumbnail_name_md5(){

        
        $films = DB::table('films')
        ->get();

        foreach($films as $films){

            $id = $films->id;
            $name = $films->name;
            $thumbnail = $films->thumbnail;
            
           
     
            $del_special_char_name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name); // Removes special chars.

            
            $new_name = "../../filmy/thumbnail/".$del_special_char_name.".png";

            if (file_exists($new_name)){
                
                rename($new_name, $thumbnail);
            
            }
        }

        if (file_exists($thumbnail)){
        return redirect()->back()->with('success', 'Nazwy zdjęć filmów zostały zmienione na tytuły z bazy danych.');
        }else{
            return redirect()->back()->with('errors', 'Niestety nie możemy zmienić nazwy plików prosimy o kontakt z administratorem. Sprawdź czy tytuł nie zawiera znaków specialnych takich jak "! : / \ " itp które mogą powodować taki błąd.');
        }


       
    }
   


}
