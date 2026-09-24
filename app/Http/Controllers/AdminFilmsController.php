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

    // =========================================================================================================
    // POMOCNICZE METODY QUERY — usuwają duplikację powtórzoną w wielu
    // miejscach tego pliku (rodzina sortowania "films_*", dodawanie tagów/
    // gwiazd/wytwórni w edit_films_add_tag, szukanie duplikatów w
    // unique_tags/unique_stars/unique_studios, otwieranie folderów).
    // =========================================================================================================

    private function filmsSorted($orderColumn = 'id', $direction = 'DESC', $activOnly = null)
    {
        $query = DB::table('films')->orderBy($orderColumn, $direction);
        if ($activOnly !== null) {
            $query->where('activ', '=', $activOnly);
        }
        return $query->paginate(27);
    }

    private function attachTagToFilm($filmId, $tagId)
    {
        $exists = DB::table('films_tags')
            ->where('film_id', $filmId)
            ->where('tag_id', $tagId)
            ->exists();

        if (!$exists) {
            $pivot = new films_tags;
            $pivot->film_id = $filmId;
            $pivot->tag_id = $tagId;
            $pivot->save();
            return $pivot->id;
        }
        return null;
    }

    private function attachTagsByName($filmId, $names)
    {
        $lastId = null;
        if (empty($names)) {
            return $lastId;
        }
        foreach ($names as $name) {
            $matches = DB::table('tags')->where('name', '=', $name)->get();
            foreach ($matches as $match) {
                $id = $this->attachTagToFilm($filmId, $match->id);
                if ($id !== null) {
                    $lastId = $id;
                }
            }
        }
        return $lastId;
    }

    private function attachStarsByName($filmId, $names, $inheritTags)
    {
        $lastId = null;
        if (empty($names)) {
            return $lastId;
        }

        foreach ($names as $name) {
            $matches = DB::table('stars')->where('name', '=', $name)->get();

            foreach ($matches as $star) {
                $exists = DB::table('films_stars')
                    ->where('film_id', $filmId)
                    ->where('stars_id', $star->id)
                    ->exists();

                if (!$exists) {
                    $pivot = new films_stars;
                    $pivot->film_id = $filmId;
                    $pivot->stars_id = $star->id;
                    $pivot->save();
                    $lastId = $pivot->id;
                }

                if (!is_null($inheritTags)) {
                    $inheritedTags = DB::table('stars')
                        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
                        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
                        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
                        ->where('stars.id', $star->id)
                        ->where('stars_tags.tag_db', 1)
                        ->orderBy('stars.name', 'ASC')
                        ->get();

                    foreach ($inheritedTags as $row) {
                        $this->attachTagToFilm($filmId, $row->tag_id);
                    }
                }
            }
        }
        return $lastId;
    }

    private function attachStudiosByName($filmId, $names, $inheritTags)
    {
        $lastId = null;
        if (empty($names)) {
            return $lastId;
        }

        foreach ($names as $name) {
            $matches = DB::table('studios')->where('name', '=', $name)->get();

            foreach ($matches as $studio) {
                $exists = DB::table('films_studios')
                    ->where('film_id', $filmId)
                    ->where('studios_id', $studio->id)
                    ->exists();

                if (!$exists) {
                    $pivot = new films_studios;
                    $pivot->film_id = $filmId;
                    $pivot->studios_id = $studio->id;
                    $pivot->save();
                    $lastId = $pivot->id;
                }

                if (!is_null($inheritTags)) {
                    $inheritedTags = DB::table('studios')
                        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
                        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
                        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
                        ->where('studios.id', $studio->id)
                        ->where('studios_tags.tag_db', 1)
                        ->orderBy('studios.name', 'ASC')
                        ->get();

                    foreach ($inheritedTags as $row) {
                        $this->attachTagToFilm($filmId, $row->tag_id);
                    }
                }
            }
        }
        return $lastId;
    }

    // ile razy dany film ma przypisany ten sam wpis dwukrotnie (duplikat)
    private function findFilmsWithDuplicateEntity($pivotTable, $pivotIdColumn)
    {
        $result = [];
        $films = DB::table('films')->select('films.*')->get();

        foreach ($films as $film) {
            $all = DB::table($pivotTable)->select($pivotIdColumn)->where('film_id', $film->id)->count();
            $unique = DB::table($pivotTable)->select($pivotIdColumn)->where('film_id', $film->id)->distinct()->count($pivotIdColumn);

            if ($all != $unique) {
                $result[] = ['id' => $film->id, 'name' => $film->name];
            }
        }

        return $result;
    }

    private function openStaticFolder($path)
    {
        if (is_dir($path)) {
            shell_exec('start '.$path.'');
            return redirect()->back();
        }
        return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
    }

    // otwiera folder wyliczony ze ścieżki filmu (url/short/thumbnail); jeśli
    // podano $fileDepth, próbuje dodatkowo zaznaczyć konkretny plik w Eksploratorze
    private function openFilmFolder($id, $property, $folderDepth, $fileDepth = null)
    {
        $films = films::find($id);
        $sourceUrl = $films->{$property};

        $string = explode("/", $sourceUrl);
        $urlFolder = implode('\\', array_slice($string, 0, $folderDepth));

        if (!is_dir($urlFolder)) {
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

        if ($fileDepth !== null) {
            $urlFile = implode('\\', array_slice($string, 0, $fileDepth));
            if (file_exists($urlFile)) {
                shell_exec('explorer /select, '.$urlFile.'');
            } else {
                shell_exec('start '.$urlFolder.'');
            }
        } else {
            shell_exec('start '.$urlFolder.'');
        }

        return redirect()->back();
    }

    public function __construct()
    {
        $this->middleware('auth'); 
    }
   
    //==================================================================== BLADE ========================================================= //
    public function films(){

        $films = $this->filmsSorted('id', 'DESC');
        $all_films = $films;
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
        return $this->openStaticFolder("..\\..\\filmy\\");
    }

    public function open_main_folder_thumbnail() {
        return $this->openStaticFolder("..\\..\\filmy\\thumbnail\\");
    }

    public function open_main_folder_short() {
        return $this->openStaticFolder("..\\..\\filmy\\short\\");
    }



    public function open_folder_film($id) {
        return $this->openFilmFolder($id, 'url', 3);
    }

    public function open_folder_film_next($id) {
        return $this->openFilmFolder($id, 'url', 4, 5);
    }

    public function open_folder_film_short($id) {
        return $this->openFilmFolder($id, 'short', 4, 5);
    }

    public function open_folder_film_thumbnail($id) {
        return $this->openFilmFolder($id, 'thumbnail', 4, 5);
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

        $last_id_tag = $this->attachTagsByName($films_id, $request->input('multiTag'));
        $last_id_star = $this->attachStarsByName($films_id, $request->input('multiStar'), $checkbox_stars_tag);
        $last_id_studios = $this->attachStudiosByName($films_id, $request->input('multiStudios'), $checkbox_studios_tag);

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

        $films = $this->filmsSorted('id', 'ASC');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_name_asc(){

        $films = $this->filmsSorted('name', 'ASC');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_name_desc(){

        $films = $this->filmsSorted('name', 'DESC');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_rating_asc(){

        $films = $this->filmsSorted('rating', 'ASC');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_rating_desc(){

        $films = $this->filmsSorted('rating', 'DESC');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_on_desc(){

        $films = $this->filmsSorted('id', 'DESC', '1');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }

    public function films_off_desc(){

        $films = $this->filmsSorted('id', 'DESC', '0');
        $all_films = $films;
        $count_films = DB::table('films')->count();
        return view('admin.admin_films', compact('films', 'count_films', 'all_films'));
       
    }


    public function unique_tags(){

        $films_tags = $this->findFilmsWithDuplicateEntity('films_tags', 'tag_id');
        $count_films = !empty($films_tags) ? 1 : 0;
        $tags_name = 1;
        return view('admin.unique_db.admin_unique_tags', compact('films_tags', 'count_films', 'tags_name'));
    }

    //==================================================================== END ======================================================================= //


    public function unique_stars(){

        $films_stars = $this->findFilmsWithDuplicateEntity('films_stars', 'stars_id');
        $count_films = !empty($films_stars) ? 1 : 0;
        $stars_name = 1;
        return view('admin.unique_db.admin_unique_tags', compact('films_stars', 'count_films', 'stars_name'));
    }

    //==================================================================== END ======================================================================= //


    public function unique_studios(){

        $films_studios = $this->findFilmsWithDuplicateEntity('films_studios', 'studios_id');
        $count_films = !empty($films_studios) ? 1 : 0;
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
            <div class="admin-search-card">
                <img src="'.$thumbnail.'" alt="'.htmlspecialchars($name).'" loading="lazy">
                <div class="admin-search-card__body">
                    <div class="admin-search-card__name">'.htmlspecialchars($name).'</div>
                    <div class="admin-search-card__actions">
                        <a href="'.$url.'" class="btn btn-info">Edytuj</a>
                        <a href="'.$url_delete.'" class="btn btn-danger">Usuń</a>
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
