<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\tags;

use App\stars_tags;

use App\studios_tags;

use App\sites_tags;

use Image;


class AdminTagsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }


    public function tags(){

        $tags = DB::table('tags')->orderBy('id', 'DESC')->paginate(27);
        $all_tags = DB::table('tags')->orderBy('id', 'DESC')->paginate(27);
        $count_tags = DB::table('tags')->count();
        return view('admin.admin_tags',compact('tags', 'count_tags', 'all_tags'));
       
    }

    //==================================================================== SORT TAGS BY  =========================================================== //

    public function tags_id_asc(){

        $tags = DB::table('tags')->orderBy('id', 'ASC')->paginate(27);
        $all_tags = DB::table('tags')->orderBy('id', 'ASC')->paginate(27);
        $count_tags = DB::table('tags')->count();
        return view('admin.admin_tags',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_name_asc(){

        $tags = DB::table('tags')->orderBy('name', 'ASC')->paginate(27);
        $all_tags = DB::table('tags')->orderBy('name', 'ASC')->paginate(27);
        $count_tags = DB::table('tags')->count();
        return view('admin.admin_tags',compact('tags', 'count_tags', 'all_tags'));
       
    }

    public function tags_name_desc(){

        $tags = DB::table('tags')->orderBy('name', 'DESC')->paginate(27);
        $all_tags = DB::table('tags')->orderBy('name', 'DESC')->paginate(27);
        $count_tags = DB::table('tags')->count();
        return view('admin.admin_tags',compact('tags', 'count_tags', 'all_tags'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   





    //==================================================================== ADD TAGS =========================================================== //
    public function add_tags(){

        return view('admin.admin_add_tags');
    
    }



    //==================================================================== SAVE ADD TAGS =========================================================== //
    public function save_tags(Request $request){

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



        $films_tags_db = DB::table('tags')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_tags_db->isEmpty()){
                $path = $request->file('thumbnail_tags')->store('thumbnail/tags'); //save file from form

                $tags = new tags;                            
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
            $save_in = '../../filmy/thumbnail/tags/'.$last_id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags/'.$last_id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags/'.$last_id.'.png', 90, 'jpg');

            }

        }

            

        unlink("../../filmy/".$path.""); //delete upload file another name no id

            $tags = tags::find($last_id);
            $tags->thumbnail = '../../filmy/thumbnail/tags/'.$last_id.'.png';
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



    //==================================================================== EDIT TAGS =========================================================== //
    public function edit_tags($id){

        $tags = tags::find($id);

        if($tags === null){
            return redirect('/admin_tags')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        return view('admin.edit_tags', compact('tags'));
    
    }



    public function open_main_folder_tags() {

 
        $url_film = "..\\..\\filmy\\thumbnail\\tags\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }

    public function open_folder_tags($id) {

        $tags = tags::find($id);

  
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

    public function open_folder_tags_next($id) {

        $tags = tags::find($id);

  
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



    //==================================================================== SAVE EDIT TAGS =========================================================== //
    public function edit_tags_save(Request $request){

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
        
        $path = $request->file('thumbnail_tags')->store('thumbnail/tags'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
            // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/tags/'.$id.'.png';
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
            $save_in = '../../filmy/thumbnail/tags/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/tags/'.$id.'.png', 90, 'jpg');

            }

        }

      
        
        unlink("../../filmy/".$path.""); //delete copy of this file don`t remove this!!!!
        }

        $tags_chk = DB::table('tags')
        ->select('name')
        ->where('id', $id)
        ->get();    
        foreach($tags_chk as $tags_chk){
            $tags_chk = $tags_chk->name;
        }

        if($tags_chk !== $name){

            $films_tags_db = DB::table('tags')
            ->select('id')
            ->where('name', $name)
            ->get();

            if($films_tags_db->isEmpty()){
                $tags = tags::find($id);
                $tags->name = $name;
                $tags->thumbnail = '../../filmy/thumbnail/tags/'.$id.'.png';
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





    //==================================================================== DELETE TAGS =========================================================== //

    public function delete_tags($id){

            
        $tags = DB::table('tags')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

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

        $films_tags = DB::table('films_tags') 
        ->where('tag_id', '=', $id)
        ->delete();

        $stars_tags = DB::table('stars_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
        ->delete();
        

        $studios_tags = DB::table('studios_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
        ->delete();
        

        $sites_tags = DB::table('sites_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
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




    //==================================================================== DELETE ALL TAGS =========================================================== //
    public function delete_all_tags(){

        
        $tags = DB::table('tags')
        ->get();


        foreach($tags as $tags){
            $name = $tags->name;
            $thumbnail = $tags->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }



        $tags = DB::table('tags') 
        ->delete();

        if(Schema::hasTable('films_tags')){
            $films_tags = DB::table('films_tags') 
            ->delete();
        }
        
        if(Schema::hasTable('stars_tags')){
            $films_stars = DB::table('stars_tags')
            ->where('tag_db', '=', 1)
            ->delete();
        }

        if(Schema::hasTable('studios_tags')){
            $films_stars = DB::table('studios_tags')
            ->where('tag_db', '=', 1)
            ->delete();
        }

        if(Schema::hasTable('sites_tags')){
            $films_stars = DB::table('sites_tags')
            ->where('tag_db', '=', 1)
            ->delete();
        }


        $max = DB::table('tags')->max('id') + 1; 
        DB::statement("ALTER TABLE tags AUTO_INCREMENT =  $max");

        

        if($tags === 1) {
            return redirect()->back()->with('success', ' Wszystkie tagi zostały usunięte!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Tagi nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }
        
    }









    //==================================================================== SEARCH TAG IN Admin Tags =========================================================== //
    public function searchtag_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags',$id);
        $url_delete = url('/delete_files_from_admin_search_tags',$id);
        $url_films = url('/select_categories',$id);

        $count_films = DB::table('tags')
        ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
        ->join('films', 'films.id', '=', 'films_tags.film_id')
        ->orderBy('name', 'ASC')
        ->select('films.*')
        ->where('tags.id', $id)
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
    //==================================================================== END =========================================================== //

    
    
    
    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_tags($id){
 
        $files_db = DB::table('tags')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Tag";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_tags_save($id){

        $files_db = DB::table('tags')
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

        $tags = DB::table('tags') 
        ->where('id', '=', $id)
        ->delete();

        $stars_tags = DB::table('stars_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
        ->delete();
        

        $studios_tags = DB::table('studios_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
        ->delete();
        

        $sites_tags = DB::table('sites_tags') 
        ->where('tag_id', '=', $id)
        ->where('tag_db', '=', 1)
        ->delete();
  
        $films_tags = DB::table('films_tags') 
        ->where('tag_id', '=', $id)
        ->delete();

        if($tags === 1) {
            return redirect('admin_tags')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_tags')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //




























    






    // ============================================================ Tags films use in stars ====================================================== //
    

    public function admin_tags_stars_db_film(){

        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->count();

        $stars_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'stars_db_films'));
       
    }


    public function searchtag_admin_tags_stars_db_film(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('stars_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('stars_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags',$id);
        $url_delete = url('/delete_files_from_admin_search_tags',$id);
        $url_films = url('/select_categories_stars_db_films',$id);

        $count_films = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->orderBy('name', 'ASC')
        ->select('stars.*')
        ->where('tags.id', $id)
        ->where('stars_tags.tag_db', 1)
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



    //==================================================================== SORT Tags films use in stars BY  =========================================================== //

    public function tags_id_asc_db_films_stars(){
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->count();

        $stars_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'stars_db_films'));
       
    }

    public function tags_name_asc_db_films_stars(){
        
        $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->count();

        $stars_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'stars_db_films'));
       
    }

    public function tags_name_desc_db_films_stars(){

         $tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags.id')
        ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
        ->select('tags.*')
        ->where('stars_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->count();

        $stars_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'stars_db_films'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   































    // ============================================================ Tags films use in studios ====================================================== //





    public function admin_tags_studios_db_film(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->count();

        $studios_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'studios_db_films'));
       
    }


    public function searchtag_admin_tags_studios_db_film(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('studios_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('studios_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags',$id);
        $url_delete = url('/delete_files_from_admin_search_tags',$id);
        $url_films = url('/select_categories_studios_db_films',$id);

        $count_films = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->orderBy('name', 'ASC')
        ->select('studios.*')
        ->where('tags.id', $id)
        ->where('studios_tags.tag_db', 1)
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

    

    //==================================================================== SORT Tags films use in studios BY  =========================================================== //

    public function tags_id_asc_db_films_studios(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->count();

        $studios_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'studios_db_films'));
       
    }

    public function tags_name_asc_db_films_studios(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->count();

        $studios_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'studios_db_films'));
       
    }

    public function tags_name_desc_db_films_studios(){

        $tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
        ->select('tags.*')
        ->where('studios_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->count();

        $studios_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'studios_db_films'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   




























    






    // ============================================================ Tags films use in sites ====================================================== //



    public function admin_tags_sites_db_film(){

        $tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'DESC')
        ->distinct()
        ->count();

        $sites_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'sites_db_films'));
       
    }


    public function searchtag_admin_tags_sites_db_film(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('sites_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->take(4)
        ->get();

        $count = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->where('tags.name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('tags.id', 'like', '%'.$searchTerm.'%')
        ->where('sites_tags.tag_db', 1)
        ->select('tags.*')
        ->distinct()
        ->count();

        if($count>0){

        foreach($tags as $tags){
        $id = $tags->id;
        $name = $tags->name;
        $thumbnail = $tags->thumbnail;
        $url = url('/edit_tags',$id);
        $url_delete = url('/delete_files_from_admin_search_tags',$id);
        $url_films = url('/select_categories_sites_db_films',$id);

        $count_films = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->orderBy('name', 'ASC')
        ->select('site.*')
        ->where('tags.id', $id)
        ->where('sites_tags.tag_db', 1)
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



    //==================================================================== SORT Tags films use in sites BY  =========================================================== //

    public function tags_id_asc_db_films_sites(){


        $tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('id', 'ASC')
        ->distinct()
        ->count();

        $sites_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'sites_db_films'));
       
    }

    public function tags_name_asc_db_films_sites(){

        $tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'ASC')
        ->distinct()
        ->count();

        $sites_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'sites_db_films'));
       
    }

    public function tags_name_desc_db_films_sites(){

        $tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $all_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->paginate(27);

        $count_tags = DB::table('tags')
        ->join('sites_tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->join('site', 'site.id', '=', 'sites_tags.site_id')
        ->select('tags.*')
        ->where('sites_tags.tag_db', 1)
        ->orderBy('name', 'DESC')
        ->distinct()
        ->count();

        $sites_db_films = 1;
        
        return view('admin.tags_films_filtr.admin_tags_filtr',compact('tags', 'count_tags', 'all_tags', 'sites_db_films'));
       
    }

    //==================================================================== END SORT TAGS BY  =========================================================== //
   







}
