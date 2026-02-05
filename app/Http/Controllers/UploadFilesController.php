<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\films;

use App\films_tags;

use App\studios;

use App\films_stars;

use App\films_studios;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use ProtoneMedia\LaravelFFMpeg\FFMpeg\CopyVideoFormat;

use Image;


class UploadFilesController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('auth'); 
    }


    public function add_films(){

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
            return view('authsites.add_films');
        }else{
          
            return view('authsites.add_films')->with('errorsMsg',"UWAGA!!! "); // reszta wiadomości po wyświetleniu strony nie zmieniać!
           
        }

    }
    
    public function save(Request $request){

        $rules = [
            'file' => 'required|mimes:mp4', // Dodajemy więcej obsługiwanych formatów
            'film_name' => 'required',
        ];
    
        $customMessages = [
            'file.required' => 'Prosimy o dodanie filmu!',
            'file.mimes' => 'Plik musi być w formacie .mp4. Plik który został przesłany:  ' . $request->file('file')->getClientOriginalExtension() . '!',
            'film_name.required' => 'Wymagany tytuł filmu!'
        ];
    
        $validator = Validator::make($request->all(), $rules, $customMessages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        

        $directory = "../../filmy/";
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        if (file_exists($directory)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu filmy
            Spróbuj dodać folder ręcznie następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory1 = "../../filmy/short";
        if (!file_exists($directory1)) {
            mkdir($directory1, 0777, true);
        }if (file_exists($directory1)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu short!
            Spróbuj dodać podfolder short ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory2 = "../../filmy/conversion";
        if (!file_exists($directory2)) {
            mkdir($directory2, 0777, true);
        }if (file_exists($directory2)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu conversion!
            Spróbuj dodać podfolder conversion ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory3 = "../../filmy/conversion/delete";
        if (!file_exists($directory3)) {
            mkdir($directory3, 0777, true);
        }if (file_exists($directory3)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu delete!
            Spróbuj dodać podfolder delete ręcznie do folderu filmy/conversion następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory4 = "../../filmy/cut";
        if (!file_exists($directory4)) {
            mkdir($directory4, 0777, true);
        }if (file_exists($directory4)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu cut!
            Spróbuj dodać podfolder cut ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory5 = "../../filmy/cut/delete";
        if (!file_exists($directory5)) {
            mkdir($directory5, 0777, true);
        }if (file_exists($directory5)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu delete!
            Spróbuj dodać podfolder delete ręcznie do folderu filmy/cut następnie spróbuj ponownie przejść do panelu administratora');
        }
        

        $directory6 = "../../filmy/thumbnail";
        if (!file_exists($directory6)) {
            mkdir($directory6, 0777, true);
        }if (file_exists($directory6)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu thumbnail!
            Spróbuj dodać podfolder thumbnail ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory7 = "../../filmy/thumbnail/stars";
        if (!file_exists($directory7)) {
            mkdir($directory7, 0777, true);
        }if (file_exists($directory7)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu stars!
            Spróbuj dodać podfolder stars ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory8 = "../../filmy/thumbnail/studios";
        if (!file_exists($directory8)) {
            mkdir($directory8, 0777, true);
        }if (file_exists($directory8)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu studios!
            Spróbuj dodać podfolder studios ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
        }

        $directory9 = "../../filmy/thumbnail/tags";
        if (!file_exists($directory9)) {
            mkdir($directory9, 0777, true);
        }if (file_exists($directory9)) {}
        else{
            return redirect()->back()->with('errors', 'Niestety nie możemy automatycznie utworzyć folderu tags!
            Spróbuj dodać podfolder tags ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora');
           
        }

        $directory = "../../filmy/short";
        if (!file_exists($directory)) {
            return redirect()->back()->with('msg_errors', 'Dodaj folder "short" do folderu "filmy" i spróbuj ponownie!');
        }
        else
        {

        $file = $request -> input('file');
        $type = $request->file('file')->extension();
        
        $name = $request -> input('film_name');
        $multiTag = $request -> input('multiTag');
        $katalog = $request -> input('katalog'); // select katalog from film folder
        $time_sec = $request -> input('time_sec');
        
        $checkbox_stars_tag = $request -> input('extra_tag_stars');
        $checkbox_studios_tag = $request -> input('extra_tag_studios');
  

        $ur = $request->file('file')->store($katalog); // save file in katalog and get name, save katalog
        $url = "../../filmy/".$ur."";


        $short_katalog = Storage::disk('save_video')->url("/short/");
        $bytes = $request->file('file')->getSize();

        
        // size film to db 
        if ($bytes >= 1073741824)
        {
            $size = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $size = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $size = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $size = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $size = $bytes . ' byte';
        }
        else
        {
            $size = '0 bytes';
        }

            
        
        if(empty($request->input('star'))){
            $rating = "1";
        }
        else
        {
            $rating = $request->input('star');
        }
        
        $activ = "1";

        

            $films = new films;
                        
            $films->name = $name;
            $films->url = $url;
            $films->short = "";
            $films->thumbnail = "";
            $films->type = $type;
            $films->size = $size;
            $films->rating = $rating;
            $films->duration = "1";
            $films->activ = $activ;
            $films->no_films = "0";
            $films->no_thumbnail = "0";
            $films->no_short = "0";


            $films->save();
            $last_id = $films->id;

            $films = films::find($last_id);
            $films->short = '../../filmy/short/'.$last_id.'.mp4';
            $films->thumbnail = '../../filmy/thumbnail/'.$last_id.'.png';
            $films->save();



        // add new tags for films 
        if(!empty($multiTag))
        {
            foreach ($multiTag as $key=>$tag) 
            {

                $query = DB::table('tags')
                ->where('name', '=', $tag)
                ->get();
                
                $tagscount = $query->count();

                if($tagscount > 0) 
                {
                    foreach ($query as $tags) {
                        $tag_id = $tags->id;

                        $films_tags_db = DB::table('films_tags')
                        ->select('tag_id')
                        ->where('film_id', $last_id)
                        ->where('tag_id', $tag_id)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new films_tags;                            
                            $films_tags->film_id = $last_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->save();

                        }
                        
                        
                    }

                    
                }
            }
        }



        // add new stars for films 
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
                        ->where('film_id', $last_id)
                        ->where('stars_id', $star_id)
                        ->get();

                        if($films_stars_db->isEmpty()){

                            $films_stars = new films_stars;
                            $films_stars->film_id = $last_id;
                            $films_stars->stars_id = $star_id;

                            $films_stars->save();
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
                                ->where('film_id', $last_id)
                                ->where('tag_id', $star_id)
                                ->get();

                                if($films_tags_db->isEmpty()){

                                    $films_tags = new films_tags;                            
                                    $films_tags->film_id = $last_id;
                                    $films_tags->tag_id = $star_id;
                                    $films_tags->save();

                                }


                            }
                        
                        }
                        

                    }
                }
            }
        }


        // add new studios for films 
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
                        ->where('film_id', $last_id)
                        ->where('studios_id', $studios_id)
                        ->get();

                        if($films_studios_db->isEmpty()){

                            $films_studios = new films_studios;
                            $films_studios->film_id = $last_id;
                            $films_studios->studios_id = $studios_id;
                            $films_studios->save();
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
                                ->where('film_id', $last_id)
                                ->where('tag_id', $studios_id)
                                ->get();

                                if($films_tags_db->isEmpty()){

                                    $films_tags = new films_tags;                            
                                    $films_tags->film_id = $last_id;
                                    $films_tags->tag_id = $studios_id;
                                    $films_tags->save();

                                }


                            }
                        }
                    }
                        
                }
            }
        }

       

        $check = shell_exec('ffmpeg -h');

        if (!empty($check)){
        
            // get duration films and select time for thumbnail
            $media = \FFMpeg::open($ur);
            $duration = $media->getDurationInSeconds();
            $time_sec_thumb = $request -> input('time_sec_thumbnail');
            
            $duration_thumbnail = "720";

            if(isset($time_sec_thumb)){
                $duration_thumbnail = $time_sec_thumb;
            }

            if($time_sec_thumb > $duration){
                $duration_thumbnail = "720";
            }
  

            // set unlimited time symfony for create trailer from video
            set_time_limit(0);

            // ------------------------------------------- CREATE SHORT VIDEO ----------------------------------------------------------- //

            if ($duration <= 30){
                $time_error ="Przepraszamy niestety film jest zbyt krótki aby przygotować zwiastun. Minimalna długość filmu to 40 sekund
                W przypadku gdy mimo to nie możesz utworzyć zwiastunu prosimy o kontakt z Administratorem.";
            }

            if ($duration > 30){

                if ($time_sec > $duration){

                    return redirect(url('/add_films'))->with('msg_errors', 'Film został dodany do bazy danych i zapisany na dysku!</br>
                    Niestety film jest zbyt krótki aby utworzyć zwiazstun oraz miniaturkę filmu.</br>
                    Jeśli chcesz dodać zwiastun filmu Przejdź do zakładki zarządzaj->filmy następnie wybierz film dodaj zwiastun oraz miniaturkę');
                }
                    

                // copy format video, this moment first 5 second have same sound no video....
                // // create trailer from video
                // $ffmpeg = \FFMpeg\FFMpeg::create();
                // $video = $ffmpeg->open($url);
                // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
                // $video->save(new CopyVideoFormat, '../../filmy/short/'.$last_id.'.mp4');


                // create trailer from video
                $ffmpeg = \FFMpeg\FFMpeg::create();
                $video = $ffmpeg->open($url);
                $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
                $video->save(new \FFMpeg\Format\Video\X264, '../../filmy/short/'.$last_id.'.mp4');




            

                // create thumbnail for video
                \FFMpeg::fromDisk('local')
                ->open($ur)
                ->addFilter(function ($filters) {
                    $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
                })
                ->getFrameFromSeconds($duration_thumbnail)
                ->export()
                    ->toDisk('thumbnail')
                ->save(''.$last_id.'.png');

                // RESIZE IMAGE!!!!!
                $open_image = "../../filmy/thumbnail/".$last_id.".png";
                $save_in = "../../filmy/thumbnail/".$last_id.".png";
                $image_resize = Image::make($open_image);              
                $image_resize->resize(550, 350);
                $image_resize->save($save_in, 90, 'jpg');
                
            }

            if ($time_sec > $duration){
                $time_sec = "720";

                if ($duration > $time_sec){
                    // copy format video, this moment first 5 second have same sound no video....
                    // create trailer from video
                    // $ffmpeg = \FFMpeg\FFMpeg::create();
                    // $video = $ffmpeg->open($url);
                    // $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
                    // $video->save(new CopyVideoFormat, '../../filmy/short/'.$last_id.'.mp4');


                    // create trailer from video
                    $ffmpeg = \FFMpeg\FFMpeg::create();
                    $video = $ffmpeg->open($url);
                    $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($time_sec), \FFMpeg\Coordinate\TimeCode::fromSeconds(15));
                    $video->save(new \FFMpeg\Format\Video\X264, '../../filmy/short/'.$last_id.'.mp4');

                    // RESIZE IMAGE!!!!!
                    $open_image = "../../filmy/thumbnail/".$last_id.".png";
                    $save_in = "../../filmy/thumbnail/".$last_id.".png";
                    $image_resize = Image::make($open_image);              
                    $image_resize->resize(550, 350);
                    $image_resize->save($save_in);


                }
            
            
            }

            

            
            
        
        }
        else
        {
            if (!empty($check)){
                $media = \FFMpeg::open($ur);
                $duration = $media->getDurationInSeconds();
            }
            else
            {
                $duration = 1;
            }

            // update db 
            $films = films::find($last_id);
            $films->short = '../../filmy/short/'.$last_id.'.mp4';
            $films->thumbnail = '../../filmy/thumbnail/'.$last_id.'.png';
            $films->duration = $duration;
            $films->save();

            return redirect(url('/add_films'))->with('msg_errors', 'Przykro nam niestety nie możemy przygotować zwiastunu oraz miniaturki filmu.
            Prosimy o kontakt z Administratorem.');

       

        }


        // update db 
        $films = films::find($last_id);
        $films->short = '../../filmy/short/'.$last_id.'.mp4';
        $films->thumbnail = '../../filmy/thumbnail/'.$last_id.'.png';
        $films->duration = $duration;
        $films->save();   
        
        if (isset($time_error)){
        // update db 
        $films = films::find($last_id);
        $films->short = '../../filmy/short/'.$last_id.'.mp4';
        $films->thumbnail = '../../filmy/thumbnail/'.$last_id.'.png';
        $films->duration = $duration;
        $films->save();   


        return redirect(url('/add_films'))->with('msg_errors', 'Przykro nam niestety nie możemy przygotować zwiastunu oraz miniaturki filmu.
            Prosimy o kontakt z Administratorem.')
            ->with('msg_errors', $time_error)
            ->with('msg_success', 'Dodałeś nowy film!
            '.$name.'
            PAMIĘTAJ ABY ZROBIĆ KOPIE ZAPASOWĄ BAZY DANYCH!!!');



        }
        
            $directory_thumbnail = "../../filmy/thumbnail/".$last_id.".png";
            if (!file_exists($directory1)) {

                $directory_short = "../../filmy/short/".$last_id.".mp4";
                if (!file_exists($directory1)) {
                
                    return redirect(url('/add_films'))->with('msg_errors', 'Niestety nie możemy utworzyć zwiastunu oraz miniatury filmu!</br>
                    Film został poprawnie dodany do bazy danych. Zwiastun filmu oraz miniaturę możesz dodać później');

                }
            
                return redirect(url('/add_films'))->with('msg_errors', 'Niestety nie możemy utworzyć miniatury filmu!</br>
                Film został poprawnie dodany do bazy danych. Miniaturę możesz dodać później');

            }

            $directory_short = "../../filmy/short/".$last_id.".mp4";
            if (!file_exists($directory1)) {
               
                return redirect(url('/add_films'))->with('msg_errors', 'msg_errors', 'Niestety nie możemy utworzyć zwiastunu filmu!</br>
                Film został poprawnie dodany do bazy danych. Zwiastun filmu możesz dodać później');

            }
            
            return redirect(url('/add_films'))->with('success', 'Dodałeś nowy film! '.$name.' PAMIĘTAJ ABY ZROBIĆ KOPIE ZAPASOWĄ BAZY DANYCH!!!');
        
        }
    }



    
}
