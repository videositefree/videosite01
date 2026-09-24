<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags_studios;

use App\studios_tags;

use Image;


class AdminTagsStudiosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    // =========================================================================================================
    // POMOCNICZE METODY
    // =========================================================================================================

    private function tagsStudiosSorted($orderColumn = 'id', $direction = 'DESC')
    {
        return DB::table('tags_studios')->orderBy($orderColumn, $direction)->paginate(27);
    }

    private function attachTagToStudio($studioId, $tagId, $tagDb)
    {
        $exists = DB::table('studios_tags')
            ->where('studio_id', $studioId)
            ->where('tag_id', $tagId)
            ->where('tag_db', $tagDb)
            ->exists();

        if (!$exists) {
            $pivot = new studios_tags;
            $pivot->studio_id = $studioId;
            $pivot->tag_id = $tagId;
            $pivot->tag_db = $tagDb;
            $pivot->save();
            return $pivot->id;
        }
        return null;
    }

    // $tagDb=0 -> tabela tags_studios (własne tagi wytwórni), $tagDb=1 -> tabela tags (wspólna z filmami)
    private function attachTagsToStudioByName($studioId, $names, $tagDb)
    {
        $lastId = null;
        if (empty($names)) {
            return $lastId;
        }
        $table = $tagDb === 0 ? 'tags_studios' : 'tags';

        foreach ($names as $name) {
            $matches = DB::table($table)->where('name', '=', $name)->get();
            foreach ($matches as $match) {
                $id = $this->attachTagToStudio($studioId, $match->id, $tagDb);
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

    private function openTagsStudiosFolder($id, $folderDepth, $fileDepth = null)
    {
        $tags = tags_studios::find($id);
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

    // wspólne zapytanie dla całej rodziny select_categories_studios* — wytwórnie przypisane do tagu
    private function studiosForTagQuery($tagTable, $id, $tagDb, $orderColumn, $direction)
    {
        return DB::table($tagTable)
            ->join('studios_tags', 'studios_tags.tag_id', '=', $tagTable.'.id')
            ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
            ->orderBy($orderColumn, $direction)
            ->select('studios.*')
            ->where($tagTable.'.id', $id)
            ->where('studios_tags.tag_db', $tagDb)
            ->distinct();
    }

    private function countStudiosForTag($tagTable, $id, $tagDb)
    {
        return $this->studiosForTagQuery($tagTable, $id, $tagDb, 'name', 'ASC')->count();
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


    public function tags_studios(){

        $tags = $this->tagsStudiosSorted('id', 'DESC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_studios')->count();
        return view('admin.tags_studios.admin_tags_studios',compact('tags', 'count_tags', 'all_tags'));
       
    }



    //==================================================================== SORT TAGS BY  ========================================================== //

    public function tags_studios_id_asc(){

        $tags = $this->tagsStudiosSorted('id', 'ASC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_studios')->count();
        return view('admin.tags_studios.admin_tags_studios',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_studios_name_asc(){

        $tags = $this->tagsStudiosSorted('name', 'ASC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_studios')->count();
        return view('admin.tags_studios.admin_tags_studios',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_studios_name_desc(){

        $tags = $this->tagsStudiosSorted('name', 'DESC');
        $all_tags = $tags;
        $count_tags = DB::table('tags_studios')->count();
        return view('admin.tags_studios.admin_tags_studios',compact('tags', 'count_tags', 'all_tags'));
       
    }

    //==================================================================== END SORT TAGS BY  ====================================================== //
   





    //==================================================================== ADD TAGS =============================================================== //
    public function add_tags_studios(){

        return view('admin.tags_studios.admin_add_tags_studios');
    
    }
    //============================================================================ END ============================================================ //



    //==================================================================== SAVE ADD TAGS ========================================================== //
    public function save_tags_studios(Request $request){

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



        $films_tags_db = DB::table('tags_studios')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_tags_db->isEmpty()){
                $path = $request->file('thumbnail_tags')->store('thumbnail/tags_studios'); //save file from form

                $tags = new tags_studios;                            
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
            $save_in = '../../filmy/thumbnail/tags_studios/'.$last_id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_studios/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_studios/'.$last_id.'.png', 90, 'jpg');

            }

        }

            

        unlink("../../filmy/".$path.""); //delete upload file another name no id

            $tags = tags_studios::find($last_id);
            $tags->thumbnail = '../../filmy/thumbnail/tags_studios/'.$last_id.'.png';
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
    //============================================================================ END ============================================================ //



    //==================================================================== EDIT TAGS ============================================================== //
    public function edit_tags_studios($id){

        $tags = tags_studios::find($id);

        if($tags === null){
            return redirect('/admin_tags_studios')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        return view('admin.tags_studios.edit_tags_studios', compact('tags'));
    
    }
    //============================================================================ END ============================================================ //


    //================================================================== Open folders tags_studios ================================================== //
    public function open_main_folder_tags_studios() {
        return $this->openStaticFolder("..\\..\\filmy\\thumbnail\\tags_studios\\");
    }
    //============================================================================ END ============================================================ //



    //=========================================================== Open folders thumbnail in edit_tags blade ======================================= //
    public function open_folder_tags_studios($id) {
        return $this->openTagsStudiosFolder($id, 4);
    }
    //============================================================================ END ============================================================ //



    //========================================================= Open folders tags_studios and select id =========================================== //
    public function open_folder_tags_next_studios($id) {
        return $this->openTagsStudiosFolder($id, 5, 6);
    }
    //============================================================================ END ============================================================ //




    //==================================================================== SAVE EDIT TAGS =========================================================== //
    public function edit_tags_studios_save(Request $request){

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
        
        $path = $request->file('thumbnail_tags')->store('thumbnail/tags_studios'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/tags_studios/'.$id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_studios/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_studios/'.$id.'.png', 90, 'jpg');

            }

        }

      
        
        unlink("../../filmy/".$path.""); //delete copy of this file don`t remove this!!!!
        }

        $tags_chk = DB::table('tags_studios')
        ->select('name')
        ->where('id', $id)
        ->get();    
        foreach($tags_chk as $tags_chk){
            $tags_chk = $tags_chk->name;
        }

        if($tags_chk !== $name){

            $films_tags_db = DB::table('tags_studios')
            ->select('id')
            ->where('name', $name)
            ->get();

            if($films_tags_db->isEmpty()){
                $tags = tags_studios::find($id);
                $tags->name = $name;
                $tags->thumbnail = '../../filmy/thumbnail/tags_studios/'.$id.'.png';
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
    //============================================================================ END ============================================================ //






    //==================================================================== DELETE TAGS =========================================================== //

    public function delete_tags_studios($id){

            
        $tags = DB::table('tags_studios')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        $films_tags = DB::table('studios_tags') 
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
    //============================================================================ END ============================================================ //





    //==================================================================== DELETE ALL TAGS =========================================================== //
    public function delete_all_tags_studios(){

        
        $tags = DB::table('tags_studios')
        ->get();


        foreach($tags as $tags){
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }

        $films_tags = DB::table('studios_tags') 
        ->delete();

        $tags = DB::table('tags_studios') 
        ->delete();


            $max = DB::table('tags_studios')->max('id') + 1; 
            DB::statement("ALTER TABLE tags AUTO_INCREMENT =  $max");

        

        if($tags === 1) {
            return redirect()->back()->with('success', ' Wszystkie tagi zostały usunięte!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Tagi nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }
        
    }
    //============================================================================ END ============================================================ //










    //==================================================================== SEARCH TAG IN TABLE VIEW =========================================================== //
    public function searchtag_studios_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags_studios',$id);
        $url_delete = url('/delete_files_from_admin_search_tags_studios',$id);
        
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $url_films = url('/select_categories_studios',$id);

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

    public function delete_files_from_admin_search_tags_studios($id){

        $files_db = DB::table('tags_studios')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Tagi Wytwórni";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_tags_studios_save($id){
      
        $files_db = DB::table('tags_studios')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        $url = $files_db->thumbnail;
        }

        $tags = DB::table('tags_studios') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_tags = DB::table('studios_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 0)
        ->delete();

        if($tags === 1) {
            return redirect('admin_tags_studios')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_tags_studios')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //



    // ===================================================== DELETE NEW TAGS, studios, STUDIOS IN EDIT BLADE ====================================== //

    public function edit_films_ajax_delete_tag_studios(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_tags = studios_tags::find($delete_id);
            $films_tags->delete();
            
    }
    //============================================================================ END ============================================================ //
    

    // ====================================================== Edit ================================================================================ //
    public function studios_tag_add_edit_site(Request $request){

        $last_id = $request -> input('id');

        $result1 = $this->attachTagsToStudioByName($last_id, $request->input('multiTag'), 0);
        $result2 = $this->attachTagsToStudioByName($last_id, $request->input('multiTagFilms'), 1);
        $last_id_db = $result1 ?? $result2;

        if(isset($last_id_db)) {
        
            return redirect()->back()->with('msg_success', 'Nowy Tag został dodany poprawnie.');
        }
        else
        {
            return redirect()->back()->with('msg_errors', 'Możliwe że taki tag jest już przypisany do tego rekordu.</br> Jeśli błąd występuje skontaktuj się z administratorem.');
        }
    }
    //============================================================================ END ============================================================ //
    



    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME AND TAG_DB = 0! ============================ //
    public function select_categories_studios($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'name', 'ASC')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0; // show or hidde <a href> and how many films have the same tag
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_new = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db' , 'hidden_id_tags_studios', 'admin_tags_studios_new'));
    }

    // ======================================================================= FILTRS ====================================================== //


    public function select_categories_studios_desc($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'name', 'desc')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_old = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_old'));
    }

    public function select_categories_studios_date_asc($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'created_at', 'ASC')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_data_asc = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_data_asc'));
    }

    public function select_categories_studios_date_desc($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'created_at', 'desc')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_data_desc = 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_data_desc'));
    }

    public function select_categories_studios_rating_asc($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'studios.rating', 'ASC')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_rating_asc= 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_rating_asc'));
    }

    public function select_categories_studios_rating_desc($id)
    {
        $films = $this->studiosForTagQuery('tags_studios', $id, 0, 'studios.rating', 'desc')->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_rating_desc= 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_rating_desc'));
    }

    public function select_categories_studios_random($id)
    {
        $films = DB::table('tags_studios')
            ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags_studios.id')
            ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
            ->inRandomOrder()
            ->select('studios.*')
            ->where('tags_studios.id', $id)
            ->where('studios_tags.tag_db', 0)
            ->distinct()
            ->paginate(27);
        $count_films = $this->countStudiosForTag('tags_studios', $id, 0);

        $site_url_studios = '1';
        $info_db = 0;
        $tags_another_studios = DB::table('tags_studios')->where('id', $id)->first();
        $hidden_id_tags_studios = $id;
        $admin_tags_studios_random= 0;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios', 'info_db', 'hidden_id_tags_studios', 'admin_tags_studios_random'));
    }




    //============================================================================ END ============================================================ //



    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME AND TAG_DB = 1! ============================ //
    public function select_categories_studios_db_films($id)
    {
        $films = $this->studiosForTagQuery('tags', $id, 1, 'name', 'ASC')->paginate(27);
        $count_films = $this->countStudiosForTag('tags', $id, 1);

        $site_url_studios = '1';
        $tags_another_studios = DB::table('tags')->where('id', $id)->first();

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_studios', 'site_url_studios'));
    }
    //============================================================================ END ============================================================ //


}
