<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\stars;

use App\stars_tags;

use Image;

class AdminStarsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }
   
    //============================================================== ADMIN TABLE STARS =========================================================== //
    public function stars(){

        $stars = DB::table('stars')->orderBy('id', 'DESC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('id', 'DESC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }



    //==================================================================== SORT STARS BY  =========================================================== //

    public function stars_id_asc(){

        $stars = DB::table('stars')->orderBy('id', 'ASC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('id', 'ASC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_name_asc(){

        $stars = DB::table('stars')->orderBy('name', 'ASC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('name', 'ASC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_gender_male(){

        $stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'male')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'male')->paginate(27);
        $count_stars = DB::table('stars')->where('sex', '=', 'male')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_gender_female(){

        $stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'female')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('sex', 'ASC')->where('sex', '=', 'female')->paginate(27);
        $count_stars = DB::table('stars')->where('sex', '=', 'female')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_name_desc(){

        $stars = DB::table('stars')->orderBy('name', 'DESC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('name', 'DESC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_rating_asc(){

        $stars = DB::table('stars')->orderBy('rating', 'ASC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('rating', 'ASC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_rating_desc(){

        $stars = DB::table('stars')->orderBy('rating', 'DESC')->paginate(27);
        $all_stars = DB::table('stars')->orderBy('rating', 'DESC')->paginate(27);
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }


    //==================================================================== END  =========================================================== //





    //==================================================================== ADD STARS =========================================================== //
    public function add_stars(){

        return view('admin.admin_add_stars');

    }

    //==================================================================== END  =========================================================== //




    //================================================================== SAVE ADD STARS =========================================================== //
    public function save_stars(Request $request){

        $rules = [
            'thumbnail_stars' => 'required|mimes:jpg,jpeg,png,bmp,tiff',
            'stars_name' => 'required',
            'chose_sex' => 'required',
            'rating' => 'required',
        ];

        $customMessages = [
            'thumbnail_stars.required' => 'Prosimy o dodanie zdjęcia w formacie jpg, jpeg, png, bmp, tiff!',
            'stars_name.required' => 'Wymagane imię gwiazdy!',
            'chose_sex_message.required' => 'Wymagane płeć gwiazdy!',
            'rating.required' => 'Wymagana ocena gwiazdy!'
        ];

        $this->validate($request, $rules, $customMessages);

        $thumbnail = $request -> input('thumbnail_stars');
        $name = $request -> input('stars_name');
        $rating = $request -> input('rating');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');
        $chose_sex = $request -> input('chose_sex');
        
        $films_stars_db = DB::table('stars')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_stars_db->isEmpty()){
            $path = $request->file('thumbnail_stars')->store('thumbnail/stars'); //save file from form

            if($chose_sex == 1){
                $chose_sex = "male";
            }

            if($chose_sex == 2){
                $chose_sex = "female";
            }
            
            $stars = new stars;                            
            $stars->name = $name;
            $stars->sex = $chose_sex;
            $stars->rating = $rating;
            $stars->thumbnail = "../../filmy/".$path."";
            $stars->no_stars = '0';
            $stars->save();
            $last_id = $stars->id;
        }else{
            foreach($films_stars_db as $films_stars_db)
            {
                $films_stars_db = $films_stars_db->id;
            }
            
            return redirect()->back()->with('msg_errors', 'Gwiazda o Imieniu i Nazwisku "'.$name.'" już istnieje. </br>
            Kliknij <a href="'.url('/admin_stars').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$name.'" lub "'.$films_stars_db.'"
            ');
        }

            
        if(!is_null($height_img) && !is_null($width_img)){
            
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/stars/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize($width_img, $height_img);
            $image_resize->save($save_in, 90, 'jpg');
            }

        }
        else
        {
                    
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/stars/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/stars/'.$last_id.'.png', 90, 'jpg');

            }

        }

            


        unlink("../../filmy/".$path.""); //delete file

        $stars = stars::find($last_id);
        $stars->thumbnail = '../../filmy/thumbnail/stars/'.$last_id.'.png';
        $stars->rating = $rating;
        $stars->save();
        $last_id_db = $stars->id;

        $multiTag = $request -> input('multiTag');
        // add new tags for films 
        if(!empty($multiTag))
        {
            foreach ($multiTag as $key=>$tag) 
            {

                $query = DB::table('tags_stars')
                ->where('name', '=', $tag)
                ->get();
                
                $tagscount = $query->count();

                if($tagscount > 0) 
                {
                    foreach ($query as $tags) {
                        $tag_id = $tags->id;

                        $films_tags_db = DB::table('stars_tags')
                        ->select('tag_id')
                        ->where('star_id', $last_id)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 0)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new stars_tags;                            
                            $films_tags->star_id = $last_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->tag_db = 0;
                            $films_tags->save();

                        }
                        
                        
                    }

                    
                }
            }
        }



        $multiTagFilms = $request -> input('multiTagFilms');
        // add new tags for films 
        if(!empty($multiTagFilms))
        {
            foreach ($multiTagFilms as $key=>$tag) 
            {

                $query = DB::table('tags')
                ->where('name', '=', $tag)
                ->get();
                
                $tagscount = $query->count();

                if($tagscount > 0) 
                {
                    foreach ($query as $tags) {
                        $tag_id = $tags->id;

                        $films_tags_db = DB::table('stars_tags')
                        ->select('tag_id')
                        ->where('star_id', $last_id)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 1)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new stars_tags;                            
                            $films_tags->star_id = $last_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->tag_db = 1;
                            $films_tags->save();

                        }
                        
                        
                    }

                    
                }
            }
        }

        
        if(isset($last_id_db)) {
        
            return redirect()->back()->with('msg_success', 'Dodałeś Nową gwiązdę!<br>'.$name.'');
        }
        else
        {
            return redirect()->back()->with('msg_errors', 'Błąd dodawania nowej gwiazdy. Prosimy o kontakt z administratorem.');
        }
        
            
    }

    //==================================================================== END  =========================================================== //





    //==================================================================== EDIT STARS =========================================================== //
    public function edit_stars($id){

        $stars = stars::find($id);

        if($stars === null){
            return redirect('/admin_stars')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        $tags = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags_stars', 'stars_tags.tag_id', '=', 'tags_stars.id')
        ->select('tags_stars.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 0)
        ->orderBy('stars.name', 'ASC')
        ->get();
        
        $tags_films = DB::table('stars')
        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
        ->where('stars.id', $id)
        ->where('stars_tags.tag_db', 1)
        ->orderBy('stars.name', 'ASC')
        ->get();

        return view('admin.edit_stars', compact('stars', 'tags', 'tags_films'));
        

    }


    public function open_main_folder_stars() {

 
        $url_film = "..\\..\\filmy\\thumbnail\\stars\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }


    public function open_folder_stars($id) {

        $tags = stars::find($id);

  
        $url_thumbnail = $tags->thumbnail;

        $string = explode("/", $url_thumbnail);
        $url_film = implode('\\', array_slice($string, 0, 4));


        if (is_dir($url_film)){
            shell_exec('start '.$url_film.'');
            return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

    }

    public function open_folder_stars_next($id) {

        $tags = stars::find($id);

  
        $url_thumbnail = $tags->thumbnail;

        $string = explode("/", $url_thumbnail);
        $url_film = implode('\\', array_slice($string, 0, 5));
        $url_filmm = implode('\\', array_slice($string, 0, 6));


        if (is_dir($url_film)){
            if(file_exists($url_filmm)){
            shell_exec('explorer /select, '.$url_filmm.'');
            }
            else
            {
            shell_exec('start'.$url_film.'');
            }
            return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }

    }

    //==================================================================== END  =========================================================== //


    //==================================================================== EDIT STARS SAVE =========================================================== //
    public function edit_stars_save(Request $request){

        $rules = [
            'stars_name' => 'required',
            'chose_sex' => 'required',
        ];

        $customMessages = [
            'stars_name.required' => 'Wymagane imię i nazwisko Gwiazdy!',
            'chose_sex_message.required' => 'Wymagana płeć gwiazdy!',

        ];
        
        $this->validate($request, $rules, $customMessages);

        $chose_sex = $request -> input('chose_sex');
        if($chose_sex == 1){
            $chose_sex = "male";
        }

        if($chose_sex == 2){
            $chose_sex = "female";
        }
        echo $chose_sex;
        
        $thumbnail = $request -> input('thumbnail_stars');
        $name = $request -> input('stars_name');
        $id = $request -> input('stars_id');
        $rating = $request -> input('rating');
        $hidde_rating = $request -> input('hidden_rating');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');


        if(empty($rating)){
            $rating = $hidde_rating;
        }
        else
        {
            $rating = $request->input('rating');
        }



        if ($_FILES['thumbnail_stars']['size'] > 0 )
        {
        
        $path = $request->file('thumbnail_stars')->store('thumbnail/stars'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/stars/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize($width_img, $height_img);
            $image_resize->save($save_in, 90, 'jpg');
            }

        }
        else
        {
                    
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/stars/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/stars/'.$id.'.png', 90, 'jpg');

            }

        }
        
        
        unlink("../../filmy/".$path.""); //delete file
        }


        $stars = stars::find($id);
        $stars->name = $name;
        $stars->sex = $chose_sex;
        $stars->thumbnail = '../../filmy/thumbnail/stars/'.$id.'.png';
        $stars->rating = $rating;
        $stars->save(); 
        $last_id_db = $stars->id;

        
        return redirect()->back()->with('msg_success', $name.' Został edytowany poprawnie!');
    
    }
    //==================================================================== END  =========================================================== //






    //==================================================================== DELETE STARS =========================================================== //
    public function delete_stars($id){

        
        $stars = DB::table('stars')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        $films_stars = DB::table('films_stars') 
        ->where('stars_id', '=', $id)
        ->delete();
        
        foreach($stars as $stars){
            $name = $stars->name;
            $thumbnail = $stars->thumbnail;
        }

        if (file_exists($thumbnail)) {
            unlink($thumbnail);  
        }  

        $stars = DB::table('stars') 
        ->where('id', '=', $id)
        ->delete();

        $countfiles = DB::table('stars')->count();

        if($countfiles == 0){
            $max = DB::table('stars')->max('id') + 1; 
            DB::statement("ALTER TABLE stars AUTO_INCREMENT =  $max");

        }

        if($stars === 1) {
            return redirect()->back()->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }
    //==================================================================== END  =========================================================== //



    //==================================================================== DELETE ALL STARS =========================================================== //
    public function delete_all_stars(){

        
        $stars = DB::table('stars')
        ->get();

        foreach($stars as $stars){
            $name = $stars->name;
            $thumbnail = $stars->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }
        


        $stars = DB::table('stars') 
        ->delete();

        if(Schema::hasTable('films_stars')){
            $films_stars = DB::table('films_stars') 
            ->delete();
        }
        
        if(Schema::hasTable('tags_stars')){
            $site = DB::table('tags_stars') 
            ->delete();
        }

    
            $max = DB::table('stars')->max('id') + 1; 
            DB::statement("ALTER TABLE stars AUTO_INCREMENT =  $max");

        

        if($stars === 1) {
            return redirect()->back()->with('success', 'Wszystkie gwiazdy zostały usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Gwiazdy nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }
    //==================================================================== END  =========================================================== //






    //============================================================= SEARCH STAR IN TABLE VIEW =========================================================== //
    public function searchstar_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $stars = DB::table('stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($stars as $stars){
            $id = $stars->id;
            $name = $stars->name;
            $thumbnail = $stars->thumbnail;
            $url = url('/edit_stars',$id);
            $url_delete = url('/delete_files_from_admin_search_stars',$id);
            $url_films = url('/select_stars',$id);

            $count_films = DB::table('stars')
            ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
            ->join('films', 'films.id', '=', 'films_stars.film_id')
            ->orderBy('name', 'ASC')
            ->select('films.*')
            ->where('stars.id', $id)
            ->where('activ', '=', '1')
            ->distinct()
            ->count();


            echo '
            <div class="col-sm-3 ">
                <div class=" m-2 ">
                    <div class="card video-wrapper" style="background-color: #F5F5F5;">

                    <img src="'.$thumbnail.'" height="270" ></img>

                    <a href="'.$url_films.'">
                    <div class="film_number_search">
                        <i class="fas fa-video">&nbsp;&nbsp;'.$count_films.'</i>
                    </div>
                    </a>
                        
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

    
//============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_stars($id){
 
        $files_db = DB::table('stars')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Gwiazda";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_stars_save($id){

        $files_db = DB::table('stars')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        $url = $files_db->thumbnail;
        }

        if(file_exists($url)){
           unlink($url);
        }   

        $stars = DB::table('stars') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_stars = DB::table('films_stars') 
        ->where('stars_id', '=', $id)
        ->delete();

        if($stars === 1) {
            return redirect('/admin_stars')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('/admin_stars')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //

    

    


        








}
