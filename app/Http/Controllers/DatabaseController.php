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

        $requiredDirectories = [
            '../../filmy/' => 'Niestety nie możemy utworzyć folderu filmy!',

            '../../filmy/short' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/short!</br>',

            '../../filmy/conversion' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion!</br>',

            '../../filmy/conversion/cut_delete' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion!</br>
            Niestety nie możemy utworzyć folderu filmy/conversion/cut_delete!</br>',

            '../../filmy/thumbnail' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>',

            '../../filmy/thumbnail/stars' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail/stars!</br>',

            '../../filmy/thumbnail/studios' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail</br>!
            Niestety nie możemy utworzyć folderu filmy/thumbnail/studios!</br>',

            '../../filmy/thumbnail/tags' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail!</br>
            Niestety nie możemy utworzyć folderu filmy/thumbnail/tags!</br>',

            '../../filmy/cut' => 'Niestety nie możemy utworzyć folderu filmy!</br>
            Niestety nie możemy utworzyć folderu filmy/cut!</br>',
        ];

        foreach ($requiredDirectories as $path => $errorMessage) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path)) {
                return redirect()->back()->with('msg_errors', $errorMessage);
            }
        }

        return redirect(url('/migrations_db'));
    
    }



}
