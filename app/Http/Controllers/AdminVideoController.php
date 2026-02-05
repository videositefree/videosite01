<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Exists;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\CopyVideoFormat;

use Image;

class AdminVideoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }
 
    //==================================================================== CUT =========================================================== //

    public function cut(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('admin.cut_films');
        }else{

            return view('admin.cut_films')->with('errorsMsg',"UWAGA!!! ");

        }
    
    }

    public function join(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('admin.join_films');
        }else{

            return view('admin.join_films')->with('errorsMsg',"UWAGA!!! ");

        }
    
    }

    public function open_main_folder_cut() {

 
        $url_film = "..\\..\\filmy\\cut\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }


    public function open_main_folder_join() {

 
        $url_film = "..\\..\\filmy\\join\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }

    public function open_main_folder_conversion() {

 
        $url_film = "..\\..\\filmy\\conversion\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }


    //==================================================================== JOIN VIDEO =========================================================== //
    
    public function join_save(Request $request){
        $rules = [
            'file_1' => 'required|mimes:mp4,avi,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
            'file_2' => 'required|mimes:mp4,avi,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
            'film_name' => 'required',
        ];

        $customMessages = [
            'file_1.required' => 'Prosimy o dodanie filmu w formacie mp4, avi, x-flv, x-mpegURL, MP2T, 3gpp, quicktime, x-msvideo, x-ms-wmv!',
            'file_2.required' => 'Prosimy o dodanie filmu w formacie mp4, avi, x-flv, x-mpegURL, MP2T, 3gpp, quicktime, x-msvideo, x-ms-wmv!',
            'film_name.required' => 'Wymagany tytuł filmu!'
        ];

        $this->validate($request, $rules, $customMessages);


        $file_1 = $request -> input('file_1');
        $file_2 = $request -> input('file_2');
        $file_3 = $request -> input('file_3');
        $file_4 = $request -> input('file_4');
        $file_5 = $request -> input('file_5');
        $file_name = $request -> input('name');    
        
        $name = $request -> input('film_name');


        //delete all specials characters 
        $string = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $clear_string_name =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
        

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
        
            if(file_exists('../../filmy/join/'.$clear_string_name.'.mp4')){

                return redirect()->back()->with('msg_errors', 'Film o takiej nazwie już istnieje!</br>');
            }
            else
            {

                $path_1 = $request->file('file_1')->store('join/delete');
                $path_1 = substr($path_1, 5);
                $path_2 = $request->file('file_2')->store('join/delete');
                $path_2 = substr($path_2, 5);

                $type_1 = $request->file('file_1')->extension();
                $type_2 = $request->file('file_2')->extension();         

                $url_1 = "../../filmy/".$path_1."";
                $url_2 = "../../filmy/".$path_2."";

                if($request->has('file_3')){
                    $path_3 = $request->file('file_3')->store('join/delete');
                    $path_3 = substr($path_3, 5);
                    $type_3 = $request->file('file_3')->extension();
                    $url_3 = "../../filmy/".$path_3."";
                }

                if($request->has('file_4')){
                    $path_4 = $request->file('file_4')->store('join/delete');
                    $path_4 = substr($path_4, 5);
                    $type_4 = $request->file('file_4')->extension();
                    $url_4 = "../../filmy/".$path_4."";

                }

                if($request->has('file_5')){
                    $path_5 = $request->file('file_5')->store('join/delete');
                    $path_5 = substr($path_5, 5);
                    $type_5 = $request->file('file_5')->extension();
                    $url_5 = "../../filmy/".$path_5."";


                }


                
                
                

                $directory8 = "../../filmy/join";
                if (!file_exists($directory8)) {
                    mkdir($directory8, 0777, true);
                }if (file_exists($directory8)) {}
                else{
                    return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
                    Niestety nie możemy utworzyć folderu filmy/cut!</br>');
                }

                if(!empty($path_1 AND $path_2)){
                    $myfile = fopen("../../filmy/join/list.txt", "w") or die("Unable to open file!");
                    $txt = "file '".$path_1."'\n";
                    fwrite($myfile, $txt);
                    $txt = "file '".$path_2."'\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                }

                if(!empty($path_3)){
                    if(!empty($path_1 AND $path_2 AND $path_3)){
                        $myfile = fopen("../../filmy/join/list.txt", "w") or die("Unable to open file!");
                        $txt = "file '".$path_1."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_2."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_3."'\n";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                    }
                }

                if(!empty($path_4)){
                    if(!empty($path_1 AND $path_2 AND $path_3 AND $path_4)){
                        $myfile = fopen("../../filmy/join/list.txt", "w") or die("Unable to open file!");
                        $txt = "file '".$path_1."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_2."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_3."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_4."'\n";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                    }
                }

                if(!empty($path_5)){
                    if(!empty($path_1 AND $path_2 AND $path_3 AND $path_4 AND $path_5)){
                        $myfile = fopen("../../filmy/join/list.txt", "w") or die("Unable to open file!");
                        $txt = "file '".$path_1."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_2."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_3."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_4."'\n";
                        fwrite($myfile, $txt);
                        $txt = "file '".$path_5."'\n";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                    }
                }
            
                $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
                exec("ffmpeg -f concat -i ../../filmy/join/list.txt -c copy ../../filmy/join/".$name.".mp4");
    

                if (file_exists('../../filmy/join/'.$path_1)) {
                    unlink('../../filmy/join/'.$path_1);
                }

                if (file_exists('../../filmy/join/'.$path_2)) {
                    unlink('../../filmy/join/'.$path_2);
                }

                if(!empty($path_3)){
                    if (file_exists('../../filmy/join/'.$path_3)) {
                        unlink('../../filmy/join/'.$path_3);
                    }
                }
                if(!empty($path_4)){
                    if (file_exists('../../filmy/join/'.$path_4)) {
                        unlink('../../filmy/join/'.$path_4);
                    }
                }
                if(!empty($path_5)){
                    if (file_exists('../../filmy/join/'.$path_5)) {
                        unlink('../../filmy/join/'.$path_5);
                    }
                }
                if (file_exists('../../filmy/join/list.txt')) {
                    unlink('../../filmy/join/list.txt');
                }


            }
        
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy połączyć filmu.<br>
            Prosimy o kontakt z Administratorem.');
            

        }

        
        return redirect()->back()->with('msg_success', 'Twój film '.$name.' jest już gotowy<br>
        Znajdziesz go w filmy/join/'.$name.'.mp4</br>
        ');
    
    }

    //==================================================================== END =========================================================== //



    public function cut_save(Request $request){
        $rules = [
            'file' => 'required|mimes:mp4,avi,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
            'film_name' => 'required',
        ];

        $customMessages = [
            'file.required' => 'Prosimy o dodanie filmu w formacie mp4, avi, x-flv, x-mpegURL, MP2T, 3gpp, quicktime, x-msvideo, x-ms-wmv!',
            'film_name.required' => 'Wymagany tytuł filmu!'
        ];

        $this->validate($request, $rules, $customMessages);


        $file = $request -> input('file');
        $file_name = $request -> input('name');
        $type = $request->file('file')->extension();
        
        $name = $request -> input('film_name');
        $start = $request -> input('time_start');
        $end = $request -> input('time_end');

        //delete all specials characters 
        $string = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $clear_string_name =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
        

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
        
            if(file_exists('../../filmy/cut/'.$clear_string_name.'.mp4')){

                return redirect()->back()->with('msg_errors', 'Film o takiej nazwie już istnieje!</br>');
            }else{

                $path = $request->file('file')->store('cut/delete');
                $url = "../../filmy/".$path."";

                $directory8 = "../../filmy/cut";
                if (!file_exists($directory8)) {
                    mkdir($directory8, 0777, true);
                }if (file_exists($directory8)) {}
                else{
                    return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
                    Niestety nie możemy utworzyć folderu filmy/cut!</br>');
                }
            
                
            // copy format video, this moment first 5 second have same sound no video....
            // create trailer from video
            // $ffmpeg = \FFMpeg\FFMpeg::create();
            // $video = $ffmpeg->open($url);
            // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds("$start"), \FFMpeg\Coordinate\TimeCode::fromSeconds($end));
            // $video->save(new CopyVideoFormat, '../../filmy/cut/'.$name.'.mp4');


            // if cut long video display errors... 

            // create trailer from video
            // $ffmpeg = \FFMpeg\FFMpeg::create();
            // $video = $ffmpeg->open($url);
            // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($start), \FFMpeg\Coordinate\TimeCode::fromSeconds($end));
            // $video->save(new \FFMpeg\Format\Video\X264, '../../filmy/cut/'.$name.'.mp4');


            

            $special_end = $end - $start;
            // exec("ffmpeg -ss ".$start." -i ".$url." -to ".$special_end." -c copy ../../filmy/cut/".$clear_string_name.".mp4");
            exec("ffmpeg -ss ".$start." -i ".$url." -to ".$special_end." -map 0 -c:v libx264 -c:a copy ../../filmy/cut/".$clear_string_name.".mp4");
            //slower version 

            if (file_exists('../../filmy/'.$path)) {
                unlink('../../filmy/'.$path);
            }
        }
        
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy skrócić filmu.<br>
            Prosimy o kontakt z Administratorem.');
            

        }

        $path = "../../filmy/cut/".$clear_string_name.".mp4";

        $start = gmdate("H:i:s", $start);
        $end = gmdate("H:i:s", $end);
        
        if(file_exists($path)){
            return redirect()->back()->with('msg_success', 'Twój film '.$name.' jest już gotowy<br>
            Znajdziesz go w filmy/cut/'.$name.'.mp4</br></br>

            Ostatnio zapisany film </br>
            Start: '.$start.'</br>
            Koniec: '.$end.'
            ');
        }else{
            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy skrócić filmu.<br>
            Prosimy o kontakt z Administratorem.');
        }
    
    }

    //==================================================================== END =========================================================== //





    

    //==================================================================== CUT IMAGE =========================================================== //
    public function cut_image(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('admin.cut_image');
        }else{

            return view('admin.cut_image')->with('errorsMsg',"UWAGA!!! ");

        }
    
    }
    //==================================================================== END =========================================================== //



    

    public function cut_image_save(Request $request){
        $rules = [
            'file' => 'required|mimes:mp4,avi,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
            'film_name' => 'required',
        ];

        $customMessages = [
            'file.required' => 'Prosimy o dodanie filmu w formacie mp4, avi, x-flv, x-mpegURL, MP2T, 3gpp, quicktime, x-msvideo, x-ms-wmv!',
            'film_name.required' => 'Wymagany tytuł miniatury filmu!'
        ];

        $this->validate($request, $rules, $customMessages);


        $file = $request -> input('file');
        $file_name = $request -> input('name');
        $type = $request->file('file')->extension();
        
        $name = $request -> input('film_name');
        $time_sec = $request -> input('time_sec_thumbnail');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');

        //delete all specials characters 
        $string = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $clear_string_name =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        
        

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
        
            if(file_exists('../../filmy/cut/'.$clear_string_name.'.mp4')){

                return redirect()->back()->with('msg_errors', 'miniatura o takiej nazwie już istnieje!</br>');
            }else{

                $path = $request->file('file')->store('cut/delete');
                $url = "".$path."";

                $directory8 = "../../filmy/cut";
                if (!file_exists($directory8)) {
                    mkdir($directory8, 0777, true);
                }if (file_exists($directory8)) {}
                else{
                    return redirect()->back()->with('msg_errors', 'Niestety nie możemy utworzyć folderu filmy!</br>
                    Niestety nie możemy utworzyć folderu filmy/cut!</br>');
                }
           
            // create thumbnail for video
            \FFMpeg::fromDisk('local')
            ->open($url)
            ->addFilter(function ($filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
            })
            ->getFrameFromSeconds("$time_sec")
            ->export()
                ->toDisk('local')
            ->save('cut/'.$clear_string_name.'.png');

            if(!is_null($height_img) && !is_null($width_img)){
                        
                if($resize_img === "1"){
                // RESIZE IMAGE!!!!!
                $open_image = '../../filmy/cut/'.$clear_string_name.'.png';
                $save_in = '../../filmy/cut/'.$clear_string_name.'.png';
                $image_resize = Image::make($open_image);              
                $image_resize->resize($width_img, $height_img);
                $image_resize->save($save_in, 90, 'jpg');
                }
    
            }
            else
            {
                        
                if($resize_img === "1"){
                // RESIZE IMAGE!!!!!
                $open_image = '../../filmy/cut/'.$clear_string_name.'.png';
                $save_in = '../../filmy/cut/'.$clear_string_name.'.png';
                $image_resize = Image::make($open_image);              
                $image_resize->resize(350, 350);
                $image_resize->save($save_in, 90, 'jpg');
                }
                else
                {
    
                    $image_url = '../../filmy/cut/'.$clear_string_name.'.png';
                    $img = Image::make($image_url);
                    $img->save('../../filmy/cut/'.$clear_string_name.'.png');
    
                }
    
            }
           
    
            if (file_exists('../../filmy/'.$path)) {
                unlink('../../filmy/'.$path);
            }
        }
        
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy wykonać miniatury z filmu.<br>
            Prosimy o kontakt z Administratorem.');
            

        }

        $time_sec = gmdate("H:i:s", $time_sec);
        return redirect()->back()->with('msg_success', 'Twója miniatura '.$name.' jest już gotowa<br>
        Znajdziesz go w filmy/cut/'.$name.'.mp4</br>
        
        Miniatura filmowa  </br>
            Start: '.$time_sec.'</br>
           
        ');
    }











    


    //==================================================================== SIMPLY CONVERSION =========================================================== //

    public function simply_conversion(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('admin.simply_conversion_films');
        }else{

            return view('admin.simply_conversion_films')->with('errorsMsg',"UWAGA!!! ");
       
        }
    }


    
    public function simply_convert_save(Request $request){
        $rules = [
          
            'film_name' => 'required',
        ];
    
        $customMessages = [
            
            'film_name.required' => 'Wymagany tytuł filmu!'
        ];
    
        $this->validate($request, $rules, $customMessages);


        $file = $request -> input('file');
        $type = $request->file('file')->extension();
                
        $name = $request -> input('film_name'); 
        $format = $request -> input('format');

        //delete all specials characters 
        $string = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $clear_string_name =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if(file_exists('../../filmy/conversion/'.$name.'.mp4')){

            return redirect()->back()
        ->with('msg_errors', 'Plik o takiej nazwie już istnieje!</br> Prosimy o zmianę nazwy lub przenieść film w inne miejsce.');
        }

        $path = $request->file('file')->store('conversion/delete/');
 
        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){        
          
            if(file_exists('../../filmy/conversion/'.$clear_string_name.'.mp4')){
                return redirect()->back()
            ->with('msg_errors', 'Plik o takiej nazwie już istnieje!</br> Prosimy o zmianę nazwy lub przenieść film w inne miejsce.');
            }

            // zapisujemy czas początkowy
            $start = microtime();

            $url = "../../filmy/".$path;
            
            shell_exec("ffmpeg -i $url -vcodec copy -acodec copy ../../filmy/conversion/$clear_string_name.$format");

            if (file_exists($url)) {
                unlink($url);
            }

            // count time convert films
            $end = microtime();
            $start = explode(' ', $start);
            $end = explode(' ', $end);
            $difference = ($end[0]+$end[1])-($start[0]+$start[1]);
            $time = gmdate("H:i:s", $difference);

           
        
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy konwertować filmu.<br>
            Prosimy o kontakt z Administratorem.');
            

        }

        
        
        return redirect()->back()->with('msg_success', 'Twój film '.$name.' jest już gotowy w formacie '.$format.'<br>
        Znajdziesz go w filmy/conversion/'.$name.'.'.$format.'</br>
        Czas przetważania filmu:  '.$time.'
        
        ');
        

    }











    //==================================================================== CONVERSION =========================================================== //

    public function conversion(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('admin.conversion_films');
        }else{

            return view('admin.conversion_films')->with('errorsMsg',"UWAGA!!! ");
       
        }
    }

    
    public function convert_save(Request $request){
        $rules = [
          
            'film_name' => 'required',
        ];
    
        $customMessages = [
            
            'film_name.required' => 'Wymagany tytuł filmu!'
        ];
    
        $this->validate($request, $rules, $customMessages);


        $file = $request -> input('file');
        $type = $request->file('file')->extension();        
        $name = $request -> input('film_name');

        //delete all specials characters 
        $string = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $clear_string_name =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if(file_exists('../../filmy/conversion/'.$name.'.mp4')){

            return redirect()->back()
        ->with('msg_errors', 'Plik o takiej nazwie już istnieje!</br> Prosimy o zmianę nazwy lub przenieść film w inne miejsce.');
        }
 
        $path = $request->file('file')->store('conversion/delete/');
      

   
        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
        
            


            // zapisujemy czas początkowy
            $start = microtime();

            \FFMpeg::fromDisk('local')
            ->open($path)
            ->export()
            ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
            ->toDisk('convert_video')            
            ->save(''.$clear_string_name.'.mp4');

                
            if (file_exists('../../filmy/'.$path)) {
                unlink('../../filmy/'.$path);
            }

            // count time convert films
            $end = microtime();
            $start = explode(' ', $start);
            $end = explode(' ', $end);
            $difference = ($end[0]+$end[1])-($start[0]+$start[1]);
            $time = gmdate("H:i:s", $difference);
        
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy konwertować filmu.<br>
            Prosimy o kontakt z Administratorem.');
            

        }

        
        return redirect()->back()->with('msg_success', 'Twój film '.$name.' jest już gotowy w formacie mp4!<br>
        Znajdziesz go w filmy/conversion/'.$name.'.mp4</br>
        Czas przetważania filmu:  '.$time.'
        
        ');
        
    }












}
