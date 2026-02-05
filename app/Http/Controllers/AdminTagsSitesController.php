<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags_sites;

use App\sites_tags;

use Image;


class AdminTagssitesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }


    public function tags_sites(){

        $tags = DB::table('tags_sites')->orderBy('id', 'DESC')->paginate(27);
        $all_tags = DB::table('tags_sites')->orderBy('id', 'DESC')->paginate(27);
        $count_tags = DB::table('tags_sites')->count();
        return view('admin.tags_sites.admin_tags_sites',compact('tags', 'count_tags', 'all_tags'));
       
    }



    //==================================================================== SORT TAGS BY  =========================================================== //

    public function tags_sites_id_asc(){

        $tags = DB::table('tags_sites')->orderBy('id', 'ASC')->paginate(27);
        $all_tags = DB::table('tags_sites')->orderBy('id', 'ASC')->paginate(27);
        $count_tags = DB::table('tags_sites')->count();
        return view('admin.tags_sites.admin_tags_sites',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_sites_name_asc(){

        $tags = DB::table('tags_sites')->orderBy('name', 'ASC')->paginate(27);
        $all_tags = DB::table('tags_sites')->orderBy('name', 'ASC')->paginate(27);
        $count_tags = DB::table('tags_sites')->count();
        return view('admin.tags_sites.admin_tags_sites',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_sites_name_desc(){

        $tags = DB::table('tags_sites')->orderBy('name', 'DESC')->paginate(27);
        $all_tags = DB::table('tags_sites')->orderBy('name', 'DESC')->paginate(27);
        $count_tags = DB::table('tags_sites')->count();
        return view('admin.tags_sites.admin_tags_sites',compact('tags', 'count_tags', 'all_tags'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   





    //==================================================================== ADD TAGS =========================================================== //
    public function add_tags_sites(){

        return view('admin.tags_sites.admin_add_tags_sites');
    
    }
    //============================================================================ END ============================================================ //


    //==================================================================== SAVE ADD TAGS =========================================================== //
    public function save_tags_sites(Request $request){

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

        if(!is_null($height_img) && !is_null($width_img)){
            echo "podana wysokość to".$height_img;
        }



        $films_tags_db = DB::table('tags_sites')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_tags_db->isEmpty()){
                $path = $request->file('thumbnail_tags')->store('thumbnail/tags_sites'); //save file from form

                $tags = new tags_sites;                            
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
            $save_in = '../../filmy/thumbnail/tags_sites/'.$last_id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_sites/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_sites/'.$last_id.'.png', 90, 'jpg');

            }

        }

            

        unlink("../../filmy/".$path.""); //delete upload file another name no id

            $tags = tags_sites::find($last_id);
            $tags->thumbnail = '../../filmy/thumbnail/tags_sites/'.$last_id.'.png';
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


    //==================================================================== EDIT TAGS =========================================================== //
    public function edit_tags_sites($id){

        $tags = tags_sites::find($id);

        if($tags === null){
            return redirect('/admin_tags_sites')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        return view('admin.tags_sites.edit_tags_sites', compact('tags'));
    
    }
    //============================================================================ END ============================================================ //


    //================================================================== Open folders tags_sites ================================================== //
    public function open_main_folder_tags_sites() {

 
        $url_film = "..\\..\\filmy\\thumbnail\\tags_sites\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }
    //============================================================================ END ============================================================ //


    //=========================================================== Open folders thumbnail in edit_tags blade ======================================= //
    public function open_folder_tags_sites($id) {

        $tags = tags_sites::find($id);

  
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
    //============================================================================ END ============================================================ //

    
    
    //==================================================================== SAVE EDIT TAGS =========================================================== //
    public function open_folder_tags_next_sites($id) {

        $tags = tags_sites::find($id);

  
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
    //============================================================================ END ============================================================ //



    //==================================================================== SAVE EDIT TAGS =========================================================== //
    public function edit_tags_sites_save(Request $request){

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
        
        $path = $request->file('thumbnail_tags')->store('thumbnail/tags_sites'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/tags_sites/'.$id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags_sites/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags_sites/'.$id.'.png', 90, 'jpg');

            }

        }

      
        
        unlink("../../filmy/".$path.""); //delete copy of this file don`t remove this!!!!
        }

        $tags_chk = DB::table('tags_sites')
        ->select('name')
        ->where('id', $id)
        ->get();    
        foreach($tags_chk as $tags_chk){
            $tags_chk = $tags_chk->name;
        }

        if($tags_chk !== $name){

            $films_tags_db = DB::table('tags_sites')
            ->select('id')
            ->where('name', $name)
            ->get();

            if($films_tags_db->isEmpty()){
                $tags = tags_sites::find($id);
                $tags->name = $name;
                $tags->thumbnail = '../../filmy/thumbnail/tags_sites/'.$id.'.png';
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

    public function delete_tags_sites($id){

            
        $tags = DB::table('tags_sites')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        $films_tags = DB::table('sites_tags') 
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
    public function delete_all_tags_sites(){

        
        $tags = DB::table('tags_sites')
        ->get();


        foreach($tags as $tags){
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }

        $films_tags = DB::table('sites_tags') 
        ->delete();

        $tags = DB::table('tags_sites') 
        ->delete();


            $max = DB::table('tags_sites')->max('id') + 1; 
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
    public function searchtag_sites_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags_sites',$id);
        $url_delete = url('/delete_files_from_admin_search_tags_sites',$id);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();
        
        $url_films = url('/select_categories_sites',$id);

        echo '
        <div class="col-sm-3 ">
                <div class=" m-2 ">
                    <div class="card video-wrapper" style="background-color: #F5F5F5;">

                    <img src="'.$thumbnail.'" height="270" ></img>

                    <a href="'.$url_films.'">
                        <div class="film_number_search">
                            <i class="fas fa-tag">&nbsp;&nbsp;'.$count_films.'</i>
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
    //==================================================================== END =========================================================== //

    
    
    
    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_tags_sites($id){

        $files_db = DB::table('tags_sites')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Tagi Stron";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_tags_sites_save($id){
      
        $files_db = DB::table('tags_sites')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        $url = $files_db->thumbnail;
        }

        $tags = DB::table('tags_sites') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_tags = DB::table('sites_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 0)
        ->delete();

        if($tags === 1) {
            return redirect('admin_tags_sites')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_tags_sites')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //



    // ====================================================== DELETE NEW TAGS, sites, sites IN EDIT BLADE ========================================== //

    public function edit_films_ajax_delete_tag_sites(Request $request){

        $delete_id = $request -> input('delete_id');
        
        $films_tags = sites_tags::find($delete_id);
            $films_tags->delete();
            
    }
    //============================================================================ END ============================================================ //
    
    
    
    
    
    public function sites_tag_add_edit_site(Request $request){

        $last_id = $request -> input('id');
        $multiTag = $request -> input('multiTag');
        // add new tags for films 
        if(!empty($multiTag))
        {
            foreach ($multiTag as $key=>$tag) 
            {

                $query = DB::table('tags_sites')
                ->where('name', '=', $tag)
                ->get();
                
                $tagscount = $query->count();

                if($tagscount > 0) 
                {
                    foreach ($query as $tags) {
                        $tag_id = $tags->id;

                        $films_tags_db = DB::table('sites_tags')
                        ->select('tag_id')
                        ->where('site_id', $last_id)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 0)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new sites_tags;                            
                            $films_tags->site_id = $last_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->tag_db = 0;
                            $films_tags->save();
                            $last_id_db = $films_tags->id;
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

                        $films_tags_db = DB::table('sites_tags')
                        ->select('tag_id')
                        ->where('site_id', $last_id)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 1)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new sites_tags;                            
                            $films_tags->site_id = $last_id;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->tag_db = 1;
                            $films_tags->save();
                            $last_id_db = $films_tags->id;
                        }
                        
                        
                    }

                    
                }
            }
        }

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

    public function select_categories_sites($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.created_at', 'desc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_data_asc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_data_asc'));

 
    }
    //============================================================================ FILTRS ============================================================ //


    public function select_categories_sites_date_asc($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.created_at', 'asc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_data_desc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_data_desc'));

 
    }


    public function select_categories_sites_name_asc($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.name', 'asc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_name_asc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_name_asc'));

 
    }


    public function select_categories_sites_name_desc($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.name', 'desc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_name_desc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_name_desc'));

 
    }

    public function select_categories_sites_rating_asc($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.rating', 'asc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_rating_asc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_rating_asc'));

 
    }

    public function select_categories_sites_rating_desc($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('site.rating', 'desc')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_rating_desc = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_rating_desc'));

 
    }

    public function select_categories_sites_random($id)
    {


        $films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->inRandomOrder()
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags_sites')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags_sites.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags_sites')->where('id', $id)->first();

        $hidden_id_sites = $id;
        $admin_tags_sites_random = 1;

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites', 'hidden_id_sites', 'admin_tags_sites_random'));

 
    }
    //============================================================================ END ============================================================ //











    // ============================ DISPLAY ALL FILMS WHERE TAGS, STARS, PRODUCTION HAVE THE SAME NAME AND TAG_DB = 1! ============================ //
    public function select_categories_sites_db_films($id)
    {


        $films = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags.id', $id)
        ->where('sites_tags.tag_db', 1)
        ->distinct()
        ->paginate(27);

        $count_films = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags.id', $id)
        ->where('sites_tags.tag_db', 1)
        ->distinct()
        ->count();

        $site_url_sites = '1';

        $tags_another_sites = DB::table('tags')->where('id', $id)->first();

        return view('admin.tags.admin_select_categories', compact('films', 'count_films', 'tags_another_sites', 'site_url_sites'));

 
    }
    //============================================================================ END ============================================================ //








}
