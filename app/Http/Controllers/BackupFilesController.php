<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\db_copy_files;

use ZipArchive;

use App\folder_copy_files;

use File;


class BackupFilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }
   
    //==================================================================== blade ========================================================= //
    public function db_copy_blade(){

        $files = DB::table('db_copy_files')
        ->orderBy('name', 'DESC')
        ->paginate(27);
        $count_copy = DB::table('db_copy_files')->count();        

  
        return view('admin.admin_db', compact('count_copy', 'files'));
    }
    //==================================================================== end ========================================================= //
    


    public function open_main_folder_db() {

 
        $url_film = "..\\storage\\app\\db-backup\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }



    //=========================================================== create copy database .zip ================================================ //
    
    public function copy_db(){

        // save folder is in backup.php file. If you want change modify line 11 the name folder and 104 filesystems disk 
        // Directory
        $directory = "../storage/app/db-backup/";
        // Returns array of files
        $files = scandir($directory);
        // Count number of files and store them to variable..
        $first = count($files)-2;

        \Artisan::call('backup:run',['--only-db'=>true]);

        $l = 0;
        $r = '';
        foreach( new \DirectoryIterator('../storage/app/db-backup/') as $file )
        {
            $ctime = $file->getCTime();    // Time file was created
            $fname = $file->getFileName(); // File name
            if( $ctime > $l )
            {
                $l = $ctime;
                $name = $fname;
            }
        }

        $db_copy_files = new db_copy_files;
                  
        $db_copy_files->name = $name;
        $db_copy_files->url = "../storage/app/db-backup/".$name."";
        $db_copy_files->save();
      

        $files = scandir($directory);
        // Count number of files and store them to variable..
        $two = count($files)-2;

        
        if($first < $two) {
            
            return redirect(url('/admin_database_copy'))->with('success', 'Kopia zapasowa bazy danych zrobiona!<br> znajdziesz ją w "videosite\storage\app\db-backup"');
        }
        else
        {
            return redirect(url('/admin_database_copy'))->with('errors', 'Błąd tworzenia kopi bazy danych prosimy o kontakt z administratorem.');
        }
    }

    //==================================================================== END =========================================================== //


    //==================================================================== DELETE CHOSE ID =========================================================== //

    public function delete_db_copy($file){

        if (file_exists('../storage/app/db-backup/'.$file)) {
            unlink('../storage/app/db-backup/'.$file);

            $files = DB::table('db_copy_files')
            ->where('name', '=', $file)
            ->delete();

            $countfiles = DB::table('db_copy_files')->count();

            if($countfiles == 0){
                $max = DB::table('db_copy_files')->max('id') + 1; 
                DB::statement("ALTER TABLE db_copy_files AUTO_INCREMENT =  $max");

            }
                    
            return redirect(url('/admin_database_copy'))->with('success', 'Kopia została usunięta');
        }
        else
        {
            return redirect(url('/admin_database_copy'))->with('errors', 'Przepraszamy nie możemy usunąć tej kopi. Prosimy o kontakt z Administratorem');
        }
    }




    //==================================================================== delete all copy database .zip ========================================================= //

    public function delete_db_copy_all(){
   
        if (file_exists('../storage/app/db-backup/')) {
            

            $dir = '../storage/app/db-backup/';
            foreach(glob($dir.'*.*') as $v){
                unlink($v);
            }

            $files = DB::table('db_copy_files')
            ->delete();

            $max = DB::table('db_copy_files')->max('id') + 1; 
            DB::statement("ALTER TABLE db_copy_files AUTO_INCREMENT =  $max");

                    
            return redirect(url('/admin_database_copy'))->with('success', 'Wszystkie pliki zostały usunięte');
        }
        else
        {
            return redirect(url('/admin_database_copy'))->with('error', 'Przepraszamy nie możemy usunąć kopi. Prosimy o kontakt z Administratorem');
        }
    }

    //======================================================================= END ================================================================== //


















    //================================================================== THUMBNAIL BACKUP ==================================================================//




















    //============================================================ BLADE FOLDER COPY ===================================================== //

    public function copy_folder_blade(){
        $files = DB::table('folder_copy_files')
        ->orderBy('name', 'DESC')
        ->paginate(27);
        $count_copy = DB::table('folder_copy_files')->count();        


        
        return view('admin.admin_folders', compact('count_copy', 'files'));
    }


    public function open_main_folder_thumbnail_copy() {

 
        $url_film = "..\\storage\\app\\backup_films\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }



    //========================================================= create folder thumbnail .zip ===================================================== //
    public function copy_folder(){
        
        // set unlimited time symfony for create trailer from video
        set_time_limit(0);

        $date = date('Y-m-d_h-i-s');        
   
        // ====== ZIP FOLDER ======//
        // Get real path for our folder
        $rootPath = realpath('../../filmy/thumbnail/');
        $destination = '../storage/app/backup_films/'.$date.'.zip';

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = 'thumbnail/'.substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        
       
    

        if(!File::exists($destination)) {
            return redirect(url('/admin_folder_copy'))->with('errors', 'Błąd tworzenia kopii sprawdź czy folder
            nie jest pusty jeśli w środku znajdują się pliki prosimy o kontakt z administratorem.');
        }
        else
        {


            $folder_copy_files = new folder_copy_files;
                  
            $folder_copy_files->name = $date.".zip";
            $folder_copy_files->url = "../storage/app/backup_films/".$date.".zip";
            $folder_copy_files->save();


            return redirect(url('/admin_folder_copy'))->with('success', 'Kopia zapasowa folderu została zrobiona.<br> znajdziesz ją w "videosite\storage\app\backup_films"');
        }

    }

    //==================================================================== END ========================================================= //
   







    //==================================================================== DELETE CHOSE FOLDER COPY ========================================================= //
    public function delete_folder_copy_thumbnail($file){

        if (file_exists('../storage/app/backup_films/'.$file)) {

            unlink('../storage/app/backup_films/'.$file);

            $files = DB::table('folder_copy_files')
            ->where('name', '=', $file)
            ->delete();

            
            $countfiles = DB::table('folder_copy_files')->count();

            if($countfiles == 0){
                $max = DB::table('folder_copy_files')->max('id') + 1; 
                DB::statement("ALTER TABLE folder_copy_files AUTO_INCREMENT =  $max");

            }
                    
            return redirect(url('/admin_folder_copy'))->with('success', 'Kopia została usunięta');
        }
        else
        {
            return redirect(url('/admin_folder_copy'))->with('errors', 'Przepraszamy nie możemy usunąć tej kopi. Prosimy o kontakt z Administratorem');
        }
    }
    //==================================================================== END ========================================================= //








    //====================================================== create copy database .zip ====================================================== //
    public function delete_folder_thumbnail_all(){
   
        if (file_exists('../storage/app/backup_films/')) {
            
   
            $dir = '../storage/app/backup_films/';
            foreach(glob($dir.'*.*') as $v){
                unlink($v);
            }

            $files = DB::table('folder_copy_files')
            ->delete();

    
                $max = DB::table('folder_copy_files')->max('id') + 1; 
                DB::statement("ALTER TABLE folder_copy_files AUTO_INCREMENT =  $max");

            

                    
                return redirect(url('/admin_folder_copy'))->with('success', 'Wszystkie pliki zostały usunięte');
        }
        else
        {
                return redirect(url('/admin_folder_copy'))->with('errors', 'Przepraszamy nie możemy usunąć kopi. Prosimy o kontakt z Administratorem');
        }
    }


    //==================================================================== END ========================================================= //

}
