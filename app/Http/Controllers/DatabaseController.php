<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;


class DatabaseController extends Controller
{

     //============================================ INDEX CHECK DATABASE TABLE IF ERROR TRY ADD ONE MORE TIME ===================================== //
    public function migrations_db(){

        try {
            $db_host = env('DB_HOST');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');

            $db = new PDO("mysql:host=$db_host", $db_username, $db_password);
        } catch (\Exception $e) {
           
            return redirect(url('/connection_db'))->with('errors', 'Błąd połączenia z bazą danych.');
        }

        //migrations db -> manually_db blade
        Artisan::call('migrate:fresh');
        
        return redirect(url('/'));

    } 
   

    //==================================================================== END ============================================================= //





    //============================================ INDEX CHECK DATABASE CONNECTION ===================================== //
     public function connection_db(){


      return view('install.manually');
      
        
    }
    
    //==================================================================== END ============================================================= //


    //============================================ CONFIG INSTALL.MANUALLY ===================================== //

    public function config_db(Request $request){

                
        $host = $request -> input('host');
        $port = $request -> input('port'); 
        $database = $request -> input('database'); 
        $username = $request -> input('username'); 
        $password = $request -> input('password'); 
        
        $src_file = "../.env";
        
        $file = file($src_file) or exit("Nie można zapisać zmian w pliku .env spróbuj ręcznie!");;
        $lines = array_map(function ($value) { return rtrim($value, PHP_EOL); }, $file);
        $lines[9] = 'DB_HOST='.$host;
        $lines[10] = 'DB_PORT='.$port;
        $lines[11] = 'DB_DATABASE='.$database;
        $lines[12] = 'DB_USERNAME='.$username;
        $lines[13] = 'DB_PASSWORD='.$password;
        $lines = array_values($lines);
        $content = implode(PHP_EOL, $lines);
        file_put_contents($src_file, $content);

        try {
            if(env('DB_DATABASE')) {
                $db_host = env('DB_HOST');
                $db_username = env('DB_USERNAME');
                $db_password = env('DB_PASSWORD');
                
                try{
                $db = new PDO("mysql:host=$db_host", $db_username, $db_password);
            
                } catch (\Exception $e) {
              
                return redirect(url('/connection_db'))->with('errors', 'Błąd połączenia z bazą danych.</br> Sprawdź czy baza danych o nazwie <b>'.$database.'</b> istnieje <br>
                Sprawdź czy użytkownik o nazwie <b>'.$username.'</b> istnieje <br>
                Sprawdź czy hasło <b>'.$password.'</b> jest poprawne <br>
                ');
            }
                return redirect(url('/'));
            }
            else
            {

            $db = new PDO("mysql:host=$host", $username, $password);

            }
        } catch (\Exception $e) {
            
            return redirect(url('/connection_db'))->with('errors', 'Błąd połączenia z bazą danych.</br> Sprawdź czy baza danych o nazwie <b>'.$database.'</b> istnieje <br>
            Sprawdź czy użytkownik o nazwie <b>'.$username.'</b> istnieje <br>
            Sprawdź czy hasło <b>'.$password.'</b> jest poprawne <br>
            ');
        }
        

       
        return redirect(url('/'));
        
    }



    public function install_db(){

        // create db and migrations -> manually_create_db blade
        Artisan::call('db:create');
        

        $directory = "../../filmy/";
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        if (file_exists($directory)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!');
        }

        $directory1 = "../../filmy/short";
        if (!file_exists($directory1)) {
            mkdir($directory1, 0777, true);
        }if (file_exists($directory1)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/short!</br>');
        }

        $directory2 = "../../filmy/conversion";
        if (!file_exists($directory2)) {
            mkdir($directory2, 0777, true);
        }if (file_exists($directory2)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion!</br>');
        }

        $directory3 = "../../filmy/conversion/cut_delete";
        if (!file_exists($directory3)) {
            mkdir($directory3, 0777, true);
        }if (file_exists($directory3)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion/cut_delete!</br>');
        }
        

        $directory4 = "../../filmy/thumbnail";
        if (!file_exists($directory4)) {
            mkdir($directory4, 0777, true);
        }if (file_exists($directory4)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>');
        }

        $directory5 = "../../filmy/thumbnail/stars";
        if (!file_exists($directory5)) {
            mkdir($directory5, 0777, true);
        }if (file_exists($directory5)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail/stars!</br>');
        }

        $directory6 = "../../filmy/thumbnail/studios";
        if (!file_exists($directory6)) {
            mkdir($directory6, 0777, true);
        }if (file_exists($directory6)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail</br>!
            Niestety nie możemy utworzyć folderu filmy/thumbnail/studios!</br>');
        }

        $directory7 = "../../filmy/thumbnail/tags";
        if (!file_exists($directory7)) {
            mkdir($directory7, 0777, true);
        }if (file_exists($directory)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail/tags!</br>');
           
        }

        $directory8 = "../../filmy/cut";
        if (!file_exists($directory8)) {
            mkdir($directory8, 0777, true);
        }if (file_exists($directory8)) {}
        else{
            return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/cut!</br>');
        }
        

        return redirect(url('/migrations_db'));
    
    }



}
