<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\films;

use App\tags;

use App\films_tags;

use App\stars;

use App\films_stars;

use App\studios;

use App\films_studios;
use Illuminate\Validation\Rules\Exists;
use Image;

use ProtoneMedia\LaravelFFMpeg\FFMpeg\CopyVideoFormat;

use function PHPUnit\Framework\fileExists;

class AdminFilmsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }
   
    //==================================================================== BLADE ========================================================= //
    public function films(){

        
        $films = DB::table('films')->orderBy('id', 'DESC')->paginate(27);
        $all_films = DB::table('films')->orderBy('id', 'DESC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }
    //==================================================================== END ========================================================= //
    






    //==================================================================== TURN ON FILMS ========================================================= //
    public function films_on(){
        
        try {
            $films = DB::table('films')
        ->update(['activ' => "1"]);

        $films= 1;

        } catch (\Illuminate\Database\QueryException $e) {
            $films= 0;
        }

        if($films === 1) {
            return redirect()->back()->with('success', 'Wszystkie filmy zostały włączone!');
        }
        else
        {
            return redirect()->back()->with('errors', 'filmy nie zostały włączone!</br> Prosimy o kontakt z administratorem.');
        }
    }
    //==================================================================== END ========================================================= //









    //==================================================================== TURN OFF FILMS ========================================================= //
    public function films_off(){
        
        try {
            $films = DB::table('films')
        ->update(['activ' => "0"]);

        $films= 1;

        } catch (\Illuminate\Database\QueryException $e) {
            $films= 0;
        }

        if($films === 1) {
            return redirect()->back()->with('success', 'Wszystkie filmy zostały wyłączone!');
        }
        else
        {
            return redirect()->back()->with('errors', 'filmy nie zostały wyłączone!</br> Prosimy o kontakt z administratorem.');
        }
    }
    //==================================================================== END ========================================================= //










    //==================================================================== DELETE CHOSE FILMS ========================================================= //
    public function delete_films($id){
        
        $films = DB::table('films')
        ->where('id', '=', $id)
        ->select('*')
        ->get();
        
        foreach($films as $films){
            
           $name = $films->name;
           $url = $films->url;
           $short = $films->short;
           $thumbnail = $films->thumbnail;
        }

        $films_tags = DB::table('films_tags') 
        ->where('film_id', '=', $id)
        ->delete();

        $films_stars = DB::table('films_stars') 
        ->where('film_id', '=', $id)
        ->delete();

        $films_studios = DB::table('films_studios') 
        ->where('film_id', '=', $id)
        ->delete();

        if (file_exists($url)) {
            unlink($url);  
        }
        
        if (file_exists($short)) {
            unlink($short);  
        }
        
        if (file_exists($thumbnail)) {
            unlink($thumbnail);  
        }  

        $films = DB::table('films') 
        ->where('id', '=', $id)
        ->delete();

        $countfiles = DB::table('films')->count();

        if($countfiles == 0){
            $max = DB::table('films')->max('id') + 1; 
            DB::statement("ALTER TABLE films AUTO_INCREMENT =  $max");

        }

        if($films === 1) {
            return redirect()->back()->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
       
    }
    //==================================================================== END ========================================================= //












    //==================================================================== DELETE ALL FILMS ========================================================= //
    public function delete_all_films(){
        
        $films = DB::table('films')
        ->get();
       
        foreach($films as $films){
            
           $name = $films->name;
           $url = $films->url;
           $short = $films->short;
           $thumbnail = $films->thumbnail;
        

            if (file_exists($url)) {
                unlink($url);  
            }
            
            if (file_exists($short)) {
                unlink($short);  
            }
            
            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }  
        }

        $films_tags = DB::table('films_tags') 
        ->delete();

        $films_stars = DB::table('films_stars') 
        ->delete();

        $films = DB::table('films') 
        ->delete();

      
            $max = DB::table('films')->max('id') + 1; 
            DB::statement("ALTER TABLE films AUTO_INCREMENT =  $max");

      

        if($films === 1) {
            return redirect()->back()->with('success', 'Wszystkie filmy zostały usunięte!');
        }
        else
        {
            return redirect()->back()->with('errors', 'filmy nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }
       
    }
    //==================================================================== END ========================================================= //






























    //==================================================================== EDIT FILMS ========================================================= //
    public function edit_films($id){

        $films = films::find($id);

        if($films === null){
            return redirect('/admin_films')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        $tags = DB::table('films')
            ->join('films_tags', 'films_tags.film_id', '=', 'films.id')
            ->join('tags', 'films_tags.tag_id', '=', 'tags.id')
            ->select('tags.name', 'films_tags.tag_id', 'films_tags.id')
            ->where('films.id', $id)
            ->orderBy('tags.name', 'ASC')
            ->get();
        

        $stars = DB::table('films')
            ->join('films_stars', 'films_stars.film_id', '=', 'films.id')
            ->join('stars', 'films_stars.stars_id', '=', 'stars.id')
            ->select('stars.name', 'films_stars.stars_id', 'films_stars.id')
            ->where('films.id', $id)
            ->orderBy('stars.name', 'ASC')
            ->get();

        $studios = DB::table('films')
            ->join('films_studios', 'films_studios.film_id', '=', 'films.id')
            ->join('studios', 'films_studios.studios_id', '=', 'studios.id')
            ->select('studios.name', 'films_studios.studios_id', 'films_studios.id')
            ->where('films.id', $id)
            ->orderBy('studios.name', 'ASC')
            ->get();       
        
            $check = shell_exec('ffmpeg -h');

            if (!empty($check)){
                return view('admin.edit_films', compact('films', 'tags', 'stars', 'studios'));
            }else{
    
                return view('admin.edit_films', compact('films', 'tags', 'stars', 'studios'))->with('errorsMsg',"UWAGA!!! ");
           
            }

        
       
    }
    //==================================================================== END ========================================================= //

    public function open_main_folder_film() {

 
        $url_film = "..\\..\\filmy\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }

    public function open_main_folder_thumbnail() {

 
        $url_film = "..\\..\\filmy\\thumbnail\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }

    public function open_main_folder_short() {

 
        $url_film = "..\\..\\filmy\\short\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }



    public function open_folder_film($id) {

        $films = films::find($id);

        $url_film = $films->url;

        $string = explode("/", $url_film);
        $url_film = implode('\\', array_slice($string, 0, 3));

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }

    public function open_folder_film_next($id) {

        $films = films::find($id);

        $url_film = $films->url;


        $string = explode("/", $url_film);
        $url_film = implode('\\', array_slice($string, 0, 4));
        $url_filmm = implode('\\', array_slice($string, 0, 5));


        if (is_dir($url_film)){
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
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

    }

    public function open_folder_film_short($id) {

        $films = films::find($id);

        $url_sort = $films->short;


        $string = explode("/", $url_sort);
        $url_film = implode('\\', array_slice($string, 0, 4));
        $url_filmm = implode('\\', array_slice($string, 0, 5));


        if (is_dir($url_film)){
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
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

    }

    public function open_folder_film_thumbnail($id) {

        $films = films::find($id);

  
        $url_thumbnail = $films->thumbnail;

        $string = explode("/", $url_thumbnail);
        $url_film = implode('\\', array_slice($string, 0, 4));
        $url_filmm = implode('\\', array_slice($string, 0, 5));


        if (is_dir($url_film)){

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
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

    }








    // ========================== EDIT ONLY DATABASE INFORMATION - FILM NAME,  PATH FOR FILES, TIME,  RATING, ACTIV FILMS  ===================//
    public function edit_films_save(Request $request){

        $rules = [
            'film_name' => 'required',
            'url' => 'required',
            'short' => 'required',
            'thumbnail' => 'required',
        ];
    
        $customMessages = [
            'film_name.required' => 'Wymagana nazwa Filmu!',
            'url.required' => 'Wymagana ścieżka dostępu do Filmu!',
            'short.required' => 'Wymagana ścieżka dostępu do Trailera!',
            'thumbnail.required' => 'Wymagana ścieżka dostępu do obrazka!'
        ];
    
        $this->validate($request, $rules, $customMessages);

        $name = $request -> input('film_name');
        $url = $request -> input('url');
        $short = $request -> input('short');
        $thumbnail = $request -> input('thumbnail');
        $rating = $request -> input('rating');
        $activ = $request -> input('activ');
        $id = $request -> input('films_id');

        $ur = substr($url, 12);


        // get duration films 
        if (file_exists($url)){
        $media = \FFMpeg::open($ur);
        $duration = $media->getDurationInSeconds();
        }
        else
        {
            $duration = "1";
        }

        if($duration <= 0 ){
            $duration = $request -> input('duration');
        }

        if(isset($activ)){

            if(strpos($activ, 'on') !==false){
                $activ = 1;
            }
        }
        if ($activ == null){
            $activ = 0;
        }

        if(isset($rating)){

            $films = films::find($id);
            $films->rating = $rating;
                $films->save();
        }

        $films = films::find($id);
        $films->name = $name;
        $films->url = $url;
        $films->short = $short;
        $films->thumbnail = $thumbnail;
        $films->activ = $activ;
        $films->duration = $duration;
        $films->save();
        $last_id_db = $films->id;
        
        
        if(isset($last_id_db)) {

            return redirect()->back()->with('msg_success', 'Zmiany zostały zapisane!');
        }
        else
        {
            return redirect()->back()->with('msg_errors', 'Błąd edytowania filmu. Prosimy o kontakt z administratorem.');
        }

    }

    //==================================================================== END ========================================================= //












    //============================================================== CREATE NEW TRAILER FILMS ==================================================== //
    public function edit_films_trailer_save(Request $request){

        $time_sec = $request -> input('time_sec_video');
        $id = $request -> input('films_id');
        $duration1 = $request -> input('duration');
        $url = $request -> input('url');

        $ur = substr($url,12);

        $media = \FFMpeg::open($ur);
        $duration = $media->getDurationInSeconds();

        // ------------------------------------------- CREATE SHORT VIDEO ----------------------------------------------------------- //

        $check = shell_exec('ffmpeg -h'); // check ffmpeg to install
        if (!empty($check)){

            if ($duration <= 30){
                return redirect()->back()
                ->with('msg_errors', 'Przepraszamy niestety film jest zbyt krótki aby przygotować zwiastun. Minimalna długość filmu to 40 sekund<br>
                W przypadku gdy mimo to nie możesz utworzyć zwiastunu prosimy o kontakt z Administratorem.');
            }

            if ($duration > 30){

                if ($time_sec > $duration){
                    return redirect()->back()
                    ->with('msg_errors', 'Niestety zwiastun który próbujesz wykonać jest dłuższy niż cały film.</br>
                    Sprawdź ile trwa film i spróbuj ponownie.');
                }
            
            // copy format video, this moment first 5 second have same sound no video....
            // create trailer from video
            // $ffmpeg = \FFMpeg\FFMpeg::create();
            // $video = $ffmpeg->open($url);
            // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
            // $video->save(new CopyVideoFormat, '../../filmy/short/'.$id.'.mp4');

            // create trailer from video
            $ffmpeg = \FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open($url);
            $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
            $video->save(new \FFMpeg\Format\Video\X264, '../../filmy/short/'.$id.'.mp4');

                
            }

            if ($time_sec > $duration){
                $time_sec = "720";

               

            // copy format video, this moment first 5 second have same sound no video....
            // create trailer from video
            // $ffmpeg = \FFMpeg\FFMpeg::create();
            // $video = $ffmpeg->open($url);
            // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
            // $video->save(new CopyVideoFormat, '../../filmy/short/'.$id.'.mp4');

            // create trailer from video
            $ffmpeg = \FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open($url);
            $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
            $video->save(new \FFMpeg\Format\Video\X264, '../../filmy/short/'.$id.'.mp4');

            if ($time_sec > $duration){
                return redirect()->back()
                ->with('msg_errors', 'Niestety zwiastun który próbujesz wykonać jest dłuższy niż cały film.</br>
                Sprawdź ile trwa film i spróbuj ponownie.');
            }
            }

         

            return redirect()->back()->with('msg_success', 'Zwiastun został wykonany poprawnie.<br>');

           
        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy przygotować zwiastunu filmu.<br>
            Prosimy o kontakt z Administratorem.');

        }
                


    }

    //======================================================================== END ================================================================ //












    //============================================================== CREATE NEW THUMBNAIL ==================================================== //
    public function edit_films_thumbnail_save(Request $request){

        $time_sec = $request -> input('time_sec_thumbnail');
        $id = $request -> input('films_id');
        $duration = $request -> input('duration');
        $url = $request -> input('url');
        $str = substr($url, 11);

     

        $check = shell_exec('ffmpeg -h'); // check ffmpeg to install
        if (!empty($check)){

            if (file_exists("../../filmy/thumbnail/".$id.".png")){
                unlink("../../filmy/thumbnail/".$id.".png"); //delete file
            }
            // create thumbnail for video
            \FFMpeg::fromDisk('thumbnaill')
            ->open($str)
            ->addFilter(function ($filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
            })
            ->getFrameFromSeconds($time_sec)
            ->export()
                ->toDisk('thumbnail')
            ->save(''.$id.'.png');

           
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/thumbnail/".$id.".png";
            $save_in = "../../filmy/thumbnail/".$id.".png";
            $image_resize = Image::make($open_image);              
            $image_resize->resize(550, 350);
            $image_resize->save($save_in);
           
            

        }
        else
        {

            return redirect()->back()
            ->with('msg_errors', 'Przykro nam niestety nie możemy przygotować miniaturki z filmu.<br>
            Prosimy o kontakt z Administratorem.');

        }
      
 
        $films = films::find($id);
        $films->thumbnail = "../../filmy/thumbnail/$id.png";
        $films->save();
 
    
        return redirect()->back()->with('msg_success', 'Nowa miniaturka został wykonany poprawnie!');
 
       

    }

    //============================================================== END ========================================================================== //













    //====================================================== ADD NEW TAGS, STRAS, STUDIOS ======================================================= //
    public function edit_films_add_tag(Request $request){

        $films_id = $request -> input('films_id');
        $checkbox_stars_tag = $request -> input('extra_tag_stars');
        $checkbox_studios_tag = $request -> input('extra_tag_studios');

        $multiTag = $request -> input('multiTag');

        if(!empty($multiTag)){
                
            foreach ($multiTag as $key=>$tag){

                $query = DB::table('tags')
                ->where('name', '=', $tag)
                ->get();                    
                $tagscount = $query->count();
                
                if($tagscount > 0) {
                    foreach ($query as $tags) {
                        $tag_id = $tags->id;
                        
                        $films_tags_db = DB::table('films_tags')
                        ->select('tag_id')
                        ->where('film_id', $films_id)
                        ->where('tag_id', $tag_id)
                        ->get();

                        if($films_tags_db->isEmpty()){
                            $films_tags = new films_tags;
                            $films_tags->film_id = $films_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->save();
                            $last_id_tag = $films_tags->id;
                        }


                     
                    }
                }
            }
        }
    
            
     
        $multiStar = $request -> input('multiStar');

        if(!empty($multiStar)){
                
            foreach ($multiStar as $key=>$stars){

                $query = DB::table('stars')
                ->where('name', '=', $stars)
                ->get();

                $starcount = $query->count();

                if($starcount > 0) {
                    foreach ($query as $star) {
                        $star_id = $star->id;

                        $films_stars_db = DB::table('films_stars')
                        ->select('stars_id')
                        ->where('film_id', $films_id)
                        ->where('stars_id', $star_id)
                        ->get();

                        if($films_stars_db->isEmpty()){
                            $films_stars = new films_stars;
                            $films_stars->film_id = $films_id;
                            $films_stars->stars_id = $star_id;

                            $films_stars->save();
                            $last_id_star = $films_stars->id;
                        }


                        if(!is_null($checkbox_stars_tag)){
   
                            //add tag stars from database tag films 
                            $tags_films = DB::table('stars')
                            ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
                            ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
                            ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
                            ->where('stars.id', $star_id)
                            ->where('stars_tags.tag_db', 1)
                            ->orderBy('stars.name', 'ASC')
                            ->get();

                            foreach ($tags_films as $star) {
                                $star_id = $star -> tag_id;

                                $films_tags_db = DB::table('films_tags')
                                ->select('tag_id')
                                ->where('film_id', $films_id)
                                ->where('tag_id', $star_id)
                                ->get();

                                if($films_tags_db->isEmpty()){

                                    $films_tags = new films_tags;                            
                                    $films_tags->film_id = $films_id;
                                    $films_tags->tag_id = $star_id;
                                    $films_tags->save();

                                }


                            }
                        
                        }

  
                    }
                }
            }
        }


        
        $multiStudios = $request -> input('multiStudios');

        if(!empty($multiStudios)){
                
            foreach ($multiStudios as $key=>$studios){

                $query = DB::table('studios')
                ->where('name', '=', $studios)
                ->get();

                $studioscount = $query->count();

                if($studioscount > 0) {
                    foreach ($query as $studios) {
                        $studios_id = $studios->id;

                        $films_studios_db = DB::table('films_studios')
                        ->select('studios_id')
                        ->where('film_id', $films_id)
                        ->where('studios_id', $studios_id)
                        ->get();

                        if($films_studios_db->isEmpty()){
                            $films_studios = new films_studios;
                            $films_studios->film_id = $films_id;
                            $films_studios->studios_id = $studios_id;

                            $films_studios->save();
                            $last_id_studios = $films_studios->id;

                        }


                        if(!is_null($checkbox_studios_tag)){
                            //add tag stars from database tag films 
                            $tags_films = DB::table('studios')
                            ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
                            ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
                            ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
                            ->where('studios.id', $studios_id)
                            ->where('studios_tags.tag_db', 1)
                            ->orderBy('studios.name', 'ASC')
                            ->get();

                            foreach ($tags_films as $studios) {
                                $studios_id = $studios -> tag_id;

                                $films_tags_db = DB::table('films_tags')
                                ->select('tag_id')
                                ->where('film_id', $films_id)
                                ->where('tag_id', $studios_id)
                                ->get();

                                if($films_tags_db->isEmpty()){

                                    $films_tags = new films_tags;                            
                                    $films_tags->film_id = $films_id;
                                    $films_tags->tag_id = $studios_id;
                                    $films_tags->save();

                                }


                            }
                        }

  
                    }
                }
            }
        }


        if(isset($last_id_tag) && isset($last_id_star) && isset($last_id_studios)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nowy tag, gwiazdę i studio.');
        }
        elseif(isset($last_id_tag) && isset($last_id_star)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nowy tag i gwiazdę.');
        }
        elseif(isset($last_id_star) && isset($last_id_studios)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nową gwiazdę i studio.');
        }
        elseif(isset($last_id_tag) && isset($last_id_studios)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nowy tag i studio.');
        }
        elseif(isset($last_id_tag)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nowy tag.');
        }
        elseif(isset($last_id_star)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nową gwiazdę.');
        }
        elseif(isset($last_id_studios)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś nowe studio.');
        }
   
        else{
        
            return redirect()->back()->with('msg_errors', 'Prawdopodobnie próbujesz dodać tagi, gwiazdy lub wytwórnie które są już przypisane do tego filmu.</br>
            Jeśli dodajesz nowe i problem nadal występuje skontaktuj się z administratorem.');

        }
        



    }


    //============================================================== END ========================================================================== //

















    //==================================================================== SORT FILMS BY  =========================================================== //


    public function films_id_asc(){

        
        $films = DB::table('films')->orderBy('id', 'ASC')->paginate(27);
        $all_films = DB::table('films')->orderBy('id', 'ASC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_name_asc(){

        
        $films = DB::table('films')->orderBy('name', 'ASC')->paginate(27);
        $all_films = DB::table('films')->orderBy('name', 'ASC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_name_desc(){

        
        $films = DB::table('films')->orderBy('name', 'DESC')->paginate(27);
        $all_films = DB::table('films')->orderBy('name', 'DESC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_rating_asc(){

        
        $films = DB::table('films')->orderBy('rating', 'ASC')->paginate(27);
        $all_films = DB::table('films')->orderBy('rating', 'ASC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_rating_desc(){

        
        $films = DB::table('films')->orderBy('rating', 'DESC')->paginate(27);
        $all_films = DB::table('films')->orderBy('rating', 'DESC')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_on_desc(){

        
        $films = DB::table('films')->orderBy('id', 'DESC')->where('activ', '=', '1')->paginate(27);
        $all_films = DB::table('films')->orderBy('id', 'DESC')->where('activ', '=', '1')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_off_desc(){

        
        $films = DB::table('films')->orderBy('id', 'DESC')->where('activ', '=', '0')->paginate(27);
        $all_films = DB::table('films')->orderBy('id', 'DESC')->where('activ', '=', '0')->paginate(27);
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }


    public function unique_tags(){
        
        //Check duplicate tags, stars, studios in films

        $check_count_films = DB::table('films')
        ->select('films.*')
        ->get();
        $films_tags = array();
        $i = 0;
        foreach($check_count_films as $check_count_films){

            $id = $check_count_films->id;
            $name = $check_count_films->name;
        

            $all_films = DB::table('films_tags')
            ->select('tag_id')
            ->where('film_id','=',$id)
            ->orderBy('films_tags.tag_id','desc')
            ->count();
            
            $unique_films = DB::table('films_tags')
            ->select('tag_id')
            ->where('film_id','=',$id)
            ->orderBy('films_tags.tag_id','desc')
            ->distinct()
            ->count('tag_id');
            

            
            if ($all_films != $unique_films){
     
                
 
                $films_tags[$i]['id'] = $id;
                $films_tags[$i]['name'] = $name;
                $i++;
   
                
               
              

            }


        }
    

        if(!empty($films_tags)){
            $count_films = 1;
        }
        else
        {
            $count_films = 0; 
        }

        $tags_name = 1;
        return view('admin.unique_db.admin_unique_tags', compact('films_tags', 'count_films', 'tags_name'));
    }

    //==================================================================== END ======================================================================= //


    public function unique_stars(){
        
        //Check duplicate tags, stars, studios in films

        $check_count_films = DB::table('films')
        ->select('films.*')
        ->get();
        $films_stars = array();
        $i = 0;
        foreach($check_count_films as $check_count_films){

            $id = $check_count_films->id;
            $name = $check_count_films->name;
        

            $all_films = DB::table('films_stars')
            ->select('stars_id')
            ->where('film_id','=',$id)
            ->orderBy('films_stars.stars_id','desc')
            ->count();
            
            $unique_films = DB::table('films_stars')
            ->select('stars_id')
            ->where('film_id','=',$id)
            ->orderBy('films_stars.stars_id','desc')
            ->distinct()
            ->count('stars_id');
            

            
            if ($all_films != $unique_films){
     
                
 
                $films_stars[$i]['id'] = $id;
                $films_stars[$i]['name'] = $name;
                $i++;
   
                
               
              

            }


        }
    

        if(!empty($films_stars)){
            $count_films = 1;
        }
        else
        {
            $count_films = 0; 
        }

        $stars_name = 1;
        return view('admin.unique_db.admin_unique_tags', compact('films_stars', 'count_films', 'stars_name'));
    }

    //==================================================================== END ======================================================================= //


    public function unique_studios(){
        
        //Check duplicate tags, stars, studios in films

        $check_count_films = DB::table('films')
        ->select('films.*')
        ->get();
        $films_studios = array();
        $i = 0;
        foreach($check_count_films as $check_count_films){

            $id = $check_count_films->id;
            $name = $check_count_films->name;
        

            $all_films = DB::table('films_studios')
            ->select('studios_id')
            ->where('film_id','=',$id)
            ->orderBy('films_studios.studios_id','desc')
            ->count();
            
            $unique_films = DB::table('films_studios')
            ->select('studios_id')
            ->where('film_id','=',$id)
            ->orderBy('films_studios.studios_id','desc')
            ->distinct()
            ->count('studios_id');
            

            
            if ($all_films != $unique_films){
     
                
 
                $films_studios[$i]['id'] = $id;
                $films_studios[$i]['name'] = $name;
                $i++;
   
                
               
              

            }


        }
    

        if(!empty($films_studios)){
            $count_films = 1;
        }
        else
        {
            $count_films = 0; 
        }

        $studios_name = 1;
        return view('admin.unique_db.admin_unique_tags', compact('films_studios', 'count_films', 'studios_name'));
    }

    //==================================================================== END ======================================================================= //








    //============================================================== AJAX SEARCH  ========================================================================== //

    public function searchfilms_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $films = DB::table('films')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->Orwhere('url', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('films')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->Orwhere('url', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($films as $films){
            $id = $films->id;
            $name = $films->name;
            $thumbnail = $films->thumbnail;
            $url = url('/edit_films',$id);
            $url_delete = url('/delete_files_from_admin_search_films',$id);
            echo '
            <div class="col-sm-3 ">
                <div class=" m-2 ">
                    <div class="card video-wrapper" style="background-color: #F5F5F5;">

                    <img src="'.$thumbnail.'" height="270" ></img>
                        
                    <div class="card-body jssearch">
                        <p class="card-text">'.$name.'</p>
                    </div>

                    <div class="jssearch">
                    <a href="'.$url.'" class="btn btn-info">Edytuj</a>

                    <a href="'.$url_delete.'" class="btn btn-danger">Usuń</a>
                    
                    </div>

                    </div>
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
    //============================================================== END ========================================================================== //

    
    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_films($id){
 
        $files_db = DB::table('films')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Film";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_films_save($id){

        $files_db = DB::table('films')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        $url = $files_db->url;
        $short = $files_db->short;
        $thumbnail = $files_db->thumbnail;
        }

        if(file_exists($url)){
           unlink($url);
        }

        if(file_exists($short)){
            unlink($short);
        }
        
        if(file_exists($thumbnail)){
            unlink($thumbnail);
        } 

        $films = DB::table('films') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_tags = DB::table('films_tags') 
        ->where('film_id', '=', $id)
        ->delete();

        $films_stars = DB::table('films_stars') 
        ->where('film_id', '=', $id)
        ->delete();

        $films_studios = DB::table('films_studios') 
        ->where('film_id', '=', $id)
        ->delete();

        if($films === 1) {
            return redirect('/admin_films')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('/admin_films')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //





    //================================================= AJAX DELETE TAG, STARS, STUDIOS IN EDIT FILMS  ========================================== //

    public function edit_films_ajax_delete_tag(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_tags = films_tags::find($delete_id);
            $films_tags->delete();
            
    }


    public function edit_films_ajax_delete_star(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_stars = films_stars::find($delete_id);
            $films_stars->delete();

    }

    public function edit_films_ajax_delete_studio(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_studios = films_studios::find($delete_id);
            $films_studios->delete();

    }

    //============================================================== END ========================================================================== //


}
