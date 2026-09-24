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

    // =========================================================================================================
    // POMOCNICZE METODY
    // =========================================================================================================

    private function starsSorted($orderColumn = 'id', $direction = 'DESC', $sex = null)
    {
        $query = DB::table('stars')->orderBy($orderColumn, $direction);
        if ($sex !== null) {
            $query->where('sex', '=', $sex);
        }
        return $query->paginate(27);
    }

    private function attachStarTag($starId, $tagId, $tagDb)
    {
        $exists = DB::table('stars_tags')
            ->where('star_id', $starId)
            ->where('tag_id', $tagId)
            ->where('tag_db', $tagDb)
            ->exists();

        if (!$exists) {
            $pivot = new stars_tags;
            $pivot->star_id = $starId;
            $pivot->tag_id = $tagId;
            $pivot->tag_db = $tagDb;
            $pivot->save();
        }
    }

    // $tagDb=0 -> tabela tags_stars (własne tagi gwiazd), $tagDb=1 -> tabela tags (wspólna z filmami)
    private function attachStarTagsByName($starId, $names, $tagDb)
    {
        if (empty($names)) {
            return;
        }
        $table = $tagDb === 0 ? 'tags_stars' : 'tags';

        foreach ($names as $name) {
            $matches = DB::table($table)->where('name', '=', $name)->get();
            foreach ($matches as $match) {
                $this->attachStarTag($starId, $match->id, $tagDb);
            }
        }
    }

    private function openStaticFolder($path)
    {
        if (is_dir($path)) {
            shell_exec('start '.$path.'');
            return redirect()->back();
        }
        return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
    }

    private function openStarFolder($id, $folderDepth, $fileDepth = null)
    {
        $star = stars::find($id);
        $string = explode("/", $star->thumbnail);
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

    private function renderFilmSearchCard($url, $urlEdit, $urlDelete, $thumbnail, $count, $name)
    {
        return '
            <div class="entity-col">
                <a href="'.$url.'" class="entity-card">
                    <div class="entity-card__media">
                        <img src="'.$thumbnail.'" alt="'.htmlspecialchars($name).'" loading="lazy">
                        <div class="film_number_search"><i class="fas fa-video"></i>&nbsp;&nbsp;'.$count.'</div>
                    </div>
                    <div class="entity-card__body">'.htmlspecialchars($name).'</div>
                </a>
                <div class="jssearch" style="display:flex; gap:8px; margin-top:8px;">
                    <a href="'.$urlEdit.'" class="btn btn-info">Edytuj</a>
                    <a href="'.$urlDelete.'" class="btn btn-danger">Usuń</a>
                </div>
            </div>
        ';
    }

    private function renderEmptySearchResult()
    {
        return '
            <div class="col-sm-12 text-center" style="padding-top: 30px; padding-bottom: 30px">
                <div class="alert alert-danger">
                    <ul>
                        Przepraszamy ale nie mamy tego czego szukasz :/
                    </ul>
                </div>
            </div>
        ';
    }

   
    //============================================================== ADMIN TABLE STARS =========================================================== //
    public function stars(){

        $stars = $this->starsSorted('id', 'DESC');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }



    //==================================================================== SORT STARS BY  =========================================================== //

    public function stars_id_asc(){

        $stars = $this->starsSorted('id', 'ASC');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_name_asc(){

        $stars = $this->starsSorted('name', 'ASC');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_gender_male(){

        $stars = $this->starsSorted('sex', 'ASC', 'male');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->where('sex', '=', 'male')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_gender_female(){

        $stars = $this->starsSorted('sex', 'ASC', 'female');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->where('sex', '=', 'female')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_name_desc(){

        $stars = $this->starsSorted('name', 'DESC');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_rating_asc(){

        $stars = $this->starsSorted('rating', 'ASC');
        $all_stars = $stars;
        $count_stars = DB::table('stars')->count();
        return view('admin.admin_stars',compact('stars', 'count_stars', 'all_stars'));
    
    }

    public function stars_rating_desc(){

        $stars = $this->starsSorted('rating', 'DESC');
        $all_stars = $stars;
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

        $this->attachStarTagsByName($last_id, $request->input('multiTag'), 0);
        $this->attachStarTagsByName($last_id, $request->input('multiTagFilms'), 1);

        
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
        return $this->openStaticFolder("..\\..\\filmy\\thumbnail\\stars\\");
    }


    public function open_folder_stars($id) {
        return $this->openStarFolder($id, 4);
    }

    public function open_folder_stars_next($id) {
        return $this->openStarFolder($id, 5, 6);
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

            echo $this->renderFilmSearchCard($url_films, $url, $url_delete, $thumbnail, $count_films, $name);
        }

        }
        else
        {
            echo $this->renderEmptySearchResult();
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
