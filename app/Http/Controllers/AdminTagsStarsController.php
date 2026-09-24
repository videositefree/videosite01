<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags_stars;

use App\stars_tags;

use Image;


class AdminTagsStarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    // =========================================================================================================
    // POMOCNICZE METODY
    // =========================================================================================================

    private function tagsStarsSorted($orderColumn = 'id', $direction = 'DESC')
    {
        return DB::table('tags_stars')->orderBy($orderColumn, $direction)->paginate(27);
    }

    private function attachTagToStar($starId, $tagId, $tagDb)
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
            return $pivot->id;
        }
        return null;
    }

    // $tagDb=0 -> tabela tags_stars (własne tagi gwiazd), $tagDb=1 -> tabela tags (wspólna z filmami)
    private function attachTagsToStarByName($starId, $names, $tagDb)
    {
        $lastId = null;
        if (empty($names)) {
            return $lastId;
        }
        $table = $tagDb === 0 ? 'tags_stars' : 'tags';

        foreach ($names as $name) {
            $matches = DB::table($table)->where('name', '=', $name)->get();
            foreach ($matches as $match) {
                $id = $this->attachTagToStar($starId, $match->id, $tagDb);
                if ($id !== null) {
                    $lastId = $id;
                }
            }
        }
        return $lastId;
    }

    private function openStaticFolder($path)
    {
        if (is_dir($path)) {
            shell_exec('start '.$path.'');
            return redirect()->back();
        }
        return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
    }

    private function openTagsStarsFolder($id, $folderDepth, $fileDepth = null)
    {
        $tags = tags_stars::find($id);
        $string = explode("/", $tags->thumbnail);
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

    // wspólne zapytanie dla całej rodziny select_categories_stars* — gwiazdy przypisane do tagu
    private function starsForTagQuery($tagTable, $id, $tagDb, $orderColumn, $direction)
    {
        return DB::table($tagTable)
            ->join('stars_tags', 'stars_tags.tag_id', '=', $tagTable.'.id')
            ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
            ->orderBy($orderColumn, $direction)
            ->select('stars.*')
            ->where($tagTable.'.id', $id)
            ->where('stars_tags.tag_db', $tagDb)
            ->distinct();
    }

    private function countStarsForTag($tagTable, $id, $tagDb)
    {
        return $this->starsForTagQuery($tagTable, $id, $tagDb, 'name', 'ASC')->count();
    }

    private function renderEntityCard($url, $urlEdit, $urlDelete, $thumbnail, $count, $name)
    {
        return '
            <div class="entity-col">
                <a href="'.$url.'" class="entity-card">
                    <div class="entity-card__media">
                        <img src="'.$thumbnail.'" alt="'.htmlspecialchars($name).'" loading="lazy">
                        <div class="film_number_search"><i class="fas fa-tag"></i>&nbsp;&nbsp;'.$count.'</div>
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


    public function tags_stars(){

        $tags = $this->tagsStarsSorted('id', 'DESC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_stars')->count();
        return view('admin.tags.admin_tags_stars',compact('tags', 'count_tags', 'all_tags'));
       
    }



    //==================================================================== SORT TAGS BY  ========================================================== //

    public function tags_stars_id_asc(){

        $tags = $this->tagsStarsSorted('id', 'ASC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_stars')->count();
        return view('admin.tags.admin_tags_stars',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_stars_name_asc(){

        $tags = $this->tagsStarsSorted('name', 'ASC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_stars')->count();
        return view('admin.tags.admin_tags_stars',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_stars_name_desc(){

        $tags = $this->tagsStarsSorted('name', 'DESC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_stars')->count();
        return view('admin.tags.admin_tags_stars',compact('tags', 'count_tags', 'all_tags'));
       
    }

    //=================================================================== END SORT TAGS BY  ======================================================= //
   





    //==================================================================== ADD TAGS =============================================================== //
    public function add_tags_stars(){

        return view('admin.tags.admin_add_tags_stars');
    
    }



    //==================================================================== SAVE ADD TAGS ========================================================== //
    public function save_tags_stars(Request $request){

        $rules = [
            'thumbnail_tags' => 'required|mimes:jpg,jpeg,png,bmp,tiff',
            'tags_name' => 'required',
        ];

        $customMessages = [
            'thumbnail_tags.required' => 'Prosimy o dodanie zdjęcia w formacie jpg, jpeg, png, bmp, tiff!',
            'tags_name.required' => 'Wymagana nazwa tagu!'
        ];

        $this->validate($request, $rules, $customMessages);

        $thumbnail = $request -> input('thumbnail_tags');
        $name = $request -> input('tags_name');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');



        $films_tags_db = DB::table('tags_stars')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_tags_db->isEmpty()){
            $path = $request->file('thumbnail_tags')->store('thumbnail/tags_stars'); //save file from form

            $tags = new tags_stars;                            
            $tags->name = $name;
            $tags->thumbnail = "../../filmy/".$path."";
            $tags->no_tags = '0';
            $tags->save();
            $last_id = $tags->id;
        }else{
            foreach($films_tags_db as $films_tags_db)
            {
                $films_tags_db = $films_tags_db->id;
            }
            
            return redirect()->back()->with('msg_errors', 'Tag o nazwie "'.$name.'" już istnieje. </br>
            Kliknij <a href="'.url('/admin_tags').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$name.'" lub "'.$films_tags_db.'"
            ');
        }


        


        if(!is_null($height_img) && !is_null($width_img)){
            
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/tags_stars/'.$last_id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_stars/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_stars/'.$last_id.'.png', 90, 'jpg');

            }

        }

            

        unlink("../../filmy/".$path.""); //delete upload file another name no id

            $tags = tags_stars::find($last_id);
            $tags->thumbnail = '../../filmy/thumbnail/tags_stars/'.$last_id.'.png';
            $tags->save();   
            $last_id_db = $tags->id;
            
            if(isset($last_id_db)) {
            
                return redirect()->back()->with('msg_success', 'Dodałeś Nową kategorię!<br>'.$name.'');
            }
            else
            {
                return redirect()->back()->with('msg_errors', 'Błąd dodawania nowej kategorii. Prosimy o kontakt z administratorem.');
            }

            
    }



    //======================================================================= EDIT TAGS =========================================================== //
    public function edit_tags_stars($id){

        $tags = tags_stars::find($id);

        if($tags === null){
            return redirect('/admin_tags_stars')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        return view('admin.tags.edit_tags_stars', compact('tags'));
    
    }
    //========================================================================= END =============================================================== //

    //================================================================ Open folders tags_stars ==================================================== //
    public function open_main_folder_tags_stars() {
        return $this->openStaticFolder("..\\..\\filmy\\thumbnail\\tags_stars\\");
    }


    public function open_folder_tags_stars($id) {
        return $this->openTagsStarsFolder($id, 4);
    }


    public function open_folder_tags_next_stars($id) {
        return $this->openTagsStarsFolder($id, 5, 6);
    }

    //==================================================================== END ==================================================================== //
    
    
    
    //==================================================================== SAVE EDIT TAGS ========================================================= //
    public function edit_tags_stars_save(Request $request){

        $rules = [
            'tags_name' => 'required',
        ];

        $customMessages = [
            'tags_name.required' => 'Wymagana nazwa tagu!'
        ];

        $this->validate($request, $rules, $customMessages);
        
        $thumbnail = $request -> input('thumbnail_tags');
        $name = $request -> input('tags_name');
        $id = $request -> input('tags_id');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');

        if ($_FILES['thumbnail_tags']['size'] > 0 )
        {
        
        $path = $request->file('thumbnail_tags')->store('thumbnail/tags_stars'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/tags_stars/'.$id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_stars/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_stars/'.$id.'.png', 90, 'jpg');

            }

        }

      
        
        unlink("../../filmy/".$path.""); //delete copy of this file don`t remove this!!!!
        }

        $tags_chk = DB::table('tags_stars')
        ->select('name')
        ->where('id', $id)
        ->get();    
        foreach($tags_chk as $tags_chk){
            $tags_chk = $tags_chk->name;
        }

        if($tags_chk !== $name){

            $films_tags_db = DB::table('tags_stars')
            ->select('id')
            ->where('name', $name)
            ->get();

            if($films_tags_db->isEmpty()){
                $tags = tags_stars::find($id);
                $tags->name = $name;
                $tags->thumbnail = '../../filmy/thumbnail/tags_stars/'.$id.'.png';
                $tags->save(); 
                $last_id_db = $tags->id;
            }else{
                foreach($films_tags_db as $films_tags_db)
                {
                    $films_tags_db = $films_tags_db->id;
                }
                
                return redirect()->back()->with('msg_errors', 'Próbujesz zmienić nazwę tagu z "'.$tags_chk.'" na "'.$name.'" który już istnieje w bazie danych. </br>
                Kliknij <a href="'.url('/admin_tags').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$name.'" lub "'.$films_tags_db.'"
                ');
            }

        }



        return redirect()->back()->with('msg_success', $name.' Został edytowany poprawnie!');
 
    }

    //==================================================================== END ==================================================================== //




    //==================================================================== DELETE TAGS =========================================================== //

    public function delete_tags_stars($id){

            
        $tags = DB::table('tags_stars')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        $films_tags = DB::table('stars_tags') 
        ->where('tag_id', '=', $id)
        ->delete();
        
        foreach($tags as $tags){
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
        }

        if (file_exists($thumbnail)) {
            unlink($thumbnail);  
        }  

        $tags = DB::table('tags') 
        ->where('id', '=', $id)
        ->delete();

        $countfiles = DB::table('tags')->count();

        if($countfiles == 0){
            $max = DB::table('tags')->max('id') + 1; 
            DB::statement("ALTER TABLE tags AUTO_INCREMENT =  $max");

        }

        if($tags === 1) {
            return redirect()->back()->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
        
    }




    //==================================================================== DELETE ALL TAGS ======================================================= //
    public function delete_all_tags_stars(){

        
        $tags = DB::table('tags_stars')
        ->get();


        foreach($tags as $tags){
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }

        $films_tags = DB::table('stars_tags') 
        ->delete();

        $tags = DB::table('tags_stars') 
        ->delete();


            $max = DB::table('tags_stars')->max('id') + 1; 
            DB::statement("ALTER TABLE tags AUTO_INCREMENT =  $max");

        

        if($tags === 1) {
            return redirect()->back()->with('success', ' Wszystkie tagi zostały usunięte!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Tagi nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }
        
    }









    //================================================================ SEARCH TAG IN TABLE VIEW =================================================== //
    public function searchtag_stars_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags_stars',$id);
        $url_delete = url('/delete_files_from_admin_search_tags_stars',$id);
        
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $url_films = url('/select_categories_stars',$id);

        echo $this->renderEntityCard($url_films, $url, $url_delete, $thumbnail, $count_films, $name);
        
        }

        }
        else
        {
            echo $this->renderEmptySearchResult();
        }

        
    }
    //==================================================================== END =========================================================== //

    
    
    
    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_tags_stars($id){

        $files_db = DB::table('tags_stars')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Tagi Gwiazd";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_tags_stars_save($id){
      
        $files_db = DB::table('tags_stars')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        $url = $files_db->thumbnail;
        }

        $tags = DB::table('tags_stars') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_tags = DB::table('stars_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 0)
        ->delete();

        if($tags === 1) {
            return redirect('admin_tags_stars')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_tags_stars')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //



    // ====================================================== DELETE NEW TAGS, STARS, STUDIOS IN EDIT BLADE ========================================== //

    public function edit_films_ajax_delete_tag_stars(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_tags = stars_tags::find($delete_id);
        $films_tags->delete();
            
    }
    //============================================================================ END ============================================================ //


    public function edit_films_ajax_delete_tag_stars_films(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_tags = stars_tags::find($delete_id);
        $films_tags->delete();
            
    }
    //============================================================================ END ============================================================ //

    
    public function stars_tag_add_edit_site(Request $request){

        $last_id = $request -> input('id');

        $result1 = $this->attachTagsToStarByName($last_id, $request->input('multiTag'), 0);
        $result2 = $this->attachTagsToStarByName($last_id, $request->input('multiTagFilms'), 1);
        $last_id_db = $result1 ?? $result2;

        if(isset($last_id_db)) {
        
            return redirect()->back()->with('msg_success', 'Nowy Tag został dodany poprawnie.');
        }
        else
        {
            return redirect()->back()->with('msg_errors', 'Możliwe że taki tag jest już przypisany do tego rekordu.</br> Jeśli błąd występuje skontaktuj się z administratorem.');
        }
        
    }
    



    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME AND TAG_DB = 0! ============================ //

    public function select_categories_stars($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'name', 'ASC')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_new = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_new'));
    }


    // ======================================================================= FILTRS ======================================================


    public function select_categories_stars_desc($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'name', 'desc')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_old = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_old'));
    }


    public function select_categories_stars_date_asc($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'created_at', 'asc')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_data_asc = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_data_asc'));
    }


    public function select_categories_stars_date_desc($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'created_at', 'desc')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_data_desc = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_data_desc'));
    }


    public function select_categories_stars_rating_asc($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'stars.rating', 'asc')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_rating_asc= 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_rating_asc'));
    }

    public function select_categories_stars_rating_desc($id)
    {
        $films = $this->starsForTagQuery('tags_stars', $id, 0, 'stars.rating', 'desc')->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_rating_desc = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_rating_desc'));
    }


    public function select_categories_stars_random($id)
    {
        $films = DB::table('tags_stars')
            ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags_stars.id')
            ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
            ->inRandomOrder()
            ->select('stars.*')
            ->where('tags_stars.id', $id)
            ->where('stars_tags.tag_db', 0)
            ->distinct()
            ->paginate(27);
        $count_films = $this->countStarsForTag('tags_stars', $id, 0);

        $site_url_stars = '1';
        $info_db = 0;
        $tags_another = DB::table('tags_stars')->where('id', $id)->first();
        $hidden_id_tags_stars = $id;
        $admin_tags_star_random = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars', 'info_db', 'hidden_id_tags_stars', 'admin_tags_star_random'));
    }


    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME AND TAG_DB = 1! ============================ //

    public function select_categories_stars_db_films($id)
    {
        $films = $this->starsForTagQuery('tags', $id, 1, 'name', 'ASC')->paginate(27);
        $count_films = $this->countStarsForTag('tags', $id, 1);

        $site_url_stars = '1';
        $tags_another = DB::table('tags')->where('id', $id)->first();

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another', 'site_url_stars'));
    }

    //==================================================================== END ==================================================================== //


    //==================================================================== END ==================================================================== //





}
