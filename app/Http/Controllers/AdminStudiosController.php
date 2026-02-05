<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\studios;

use App\studios_tags;

use Image;


class AdminStudiosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }

    
   //==================================================================== ADMIN TABLE STUDIOS =========================================================== //
   public function studios(){

    $studios = DB::table('studios')->orderBy('id', 'DESC')->paginate(27);
    $all_studios = DB::table('studios')->orderBy('id', 'DESC')->paginate(27);
    $count_studios = DB::table('studios')->count();
    return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }


    //==================================================================== SORT STUDIOS BY  =========================================================== //


    public function studios_id_asc(){

        $studios = DB::table('studios')->orderBy('id', 'ASC')->paginate(27);
        $all_studios = DB::table('studios')->orderBy('id', 'ASC')->paginate(27);
        $count_studios = DB::table('studios')->count();
        return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }

    public function studios_name_asc(){

        $studios = DB::table('studios')->orderBy('name', 'ASC')->paginate(27);
        $all_studios = DB::table('studios')->orderBy('name', 'ASC')->paginate(27);
        $count_studios = DB::table('studios')->count();
        return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }

    public function studios_name_desc(){

        $studios = DB::table('studios')->orderBy('name', 'DESC')->paginate(27);
        $all_studios = DB::table('studios')->orderBy('name', 'DESC')->paginate(27);
        $count_studios = DB::table('studios')->count();
        return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }

    public function studios_rating_asc(){

        $studios = DB::table('studios')->orderBy('rating', 'ASC')->paginate(27);
        $all_studios = DB::table('studios')->orderBy('rating', 'ASC')->paginate(27);
        $count_studios = DB::table('studios')->count();
        return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }

    public function studios_rating_desc(){

        $studios = DB::table('studios')->orderBy('rating', 'DESC')->paginate(27);
        $all_studios = DB::table('studios')->orderBy('rating', 'DESC')->paginate(27);
        $count_studios = DB::table('studios')->count();
        return view('admin.admin_studios',compact('studios', 'count_studios', 'all_studios'));
    
    }


    //==================================================================== END ====================================================================== //





    //==================================================================== ADD STUDIOS =========================================================== //
    public function add_studios(){

        return view('admin.admin_add_studios');
    
    }
    //==================================================================== END ====================================================================== //




    //==================================================================== ADD SAVE STUDIOS =========================================================== //
    public function save_studios(Request $request){

        $rules = [
            'thumbnail_studios' => 'required|mimes:jpg,jpeg,png,bmp,tiff',
            'studios_name' => 'required',
            'rating' => 'required',

        ];

        $customMessages = [
            'thumbnail_studios.required' => 'Prosimy o dodanie zdjęcia w formacie jpg, jpeg, png, bmp, tiff!',
            'studios_name.required' => 'Wymagane nazwa wytwórni!',
            'rating.required' => 'Wymagana ocena wytwórni!'
        ];

        $this->validate($request, $rules, $customMessages);

        $thumbnail = $request -> input('thumbnail_studios');
        $name = $request -> input('studios_name');
        $rating = $request -> input('rating');
        $resize_img = $request -> input('resize_img');
        $height_img = $request -> input('height_img');
        $width_img = $request -> input('width_img');

        $films_studios_db = DB::table('studios')
        ->select('id')
        ->where('name', $name)
        ->get();

        if($films_studios_db->isEmpty()){
            $path = $request->file('thumbnail_studios')->store('thumbnail/studios'); //save file from form
        
            $studios = new studios;                            
            $studios->name = $name;
            $studios->rating = $rating;
            $studios->thumbnail = "../../filmy/".$path."";
            $studios->no_studios = "0";
            $studios->save();
            $last_id = $studios->id;

        }else{
            foreach($films_studios_db as $films_studios_db)
            {
                $films_studios_db = $films_studios_db->id;
            }
            
            return redirect()->back()->with('msg_errors', 'Wytwórnia o nazwie "'.$name.'" już istnieje. </br>
            Kliknij <a href="'.url('/admin_studios').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$name.'" lub "'.$films_studios_db.'"
            ');
        }


            if(!is_null($height_img) && !is_null($width_img)){
                
                if($resize_img === "1"){
                    // RESIZE IMAGE!!!!!
                $open_image = "../../filmy/".$path;
                $save_in = '../../filmy/thumbnail/studios/'.$last_id.'.png';
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
                $save_in = '../../filmy/thumbnail/studios/'.$last_id.'.png';
                $image_resize = Image::make($open_image);              
                $image_resize->resize(350, 350);
                $image_resize->save($save_in, 90, 'jpg');
                }
                else
                {

                    $image_url = "../../filmy/".$path;
                    $img = Image::make($image_url);
                    $img->save('../../filmy/thumbnail/studios/'.$last_id.'.png', 90, 'jpg');

                }

            }   

                    

            unlink("../../filmy/".$path.""); //delete file

            $studios = studios::find($last_id);
            $studios->thumbnail = '../../filmy/thumbnail/studios/'.$last_id.'.png';
            $studios->rating = $rating;
            $studios->save();
            $last_id_db = $studios->id;


            $multiTag = $request -> input('multiTag');
            // add new tags for films 
            if(!empty($multiTag))
            {
                foreach ($multiTag as $key=>$tag) 
                {
    
                    $query = DB::table('tags_studios')
                    ->where('name', '=', $tag)
                    ->get();
                    
                    $tagscount = $query->count();
    
                    if($tagscount > 0) 
                    {
                        foreach ($query as $tags) {
                            $tag_id = $tags->id;
    
                            $films_tags_db = DB::table('studios_tags')
                            ->select('tag_id')
                            ->where('studio_id', $last_id)
                            ->where('tag_id', $tag_id)
                            ->where('tag_db', 0)
                            ->get();
    
                            if($films_tags_db->isEmpty()){
    
                                $films_tags = new studios_tags;                            
                                $films_tags->studio_id = $last_id;
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
    
                            $films_tags_db = DB::table('studios_tags')
                            ->select('tag_id')
                            ->where('studio_id', $last_id)
                            ->where('tag_id', $tag_id)
                            ->where('tag_db', 1)
                            ->get();
    
                            if($films_tags_db->isEmpty()){
    
                                $films_tags = new studios_tags;                            
                                $films_tags->studio_id = $last_id;
                                $films_tags->tag_id = $tag_id;
                                $films_tags->tag_db = 1;
                                $films_tags->save();
    
                            }
                            
                            
                        }
    
                        
                    }
                }
            }



            
            
            if(isset($last_id_db)) {
            
                return redirect()->back()->with('msg_success', 'Dodałeś Nowe studio!<br>'.$name.'');
            }
            else
            {
                return redirect()->back()->with('msg_errors', 'Błąd dodawania nowego studia. Prosimy o kontakt z administratorem.');
            }
            
            
    }
    //==================================================================== END ====================================================================== //





    //==================================================================== EDIT STUDIOS =========================================================== //
    public function edit_studios($id){

        $studios = studios::find($id);

        if($studios === null){
            return redirect('/admin_studios')->with('errors', 'Brak rekordu w bazie danych.');        
        }

         $tags = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags_studios', 'studios_tags.tag_id', '=', 'tags_studios.id')
        ->select('tags_studios.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 0)
        ->orderBy('studios.name', 'ASC')
        ->get();

        $tags_films = DB::table('studios')
        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
        ->where('studios.id', $id)
        ->where('studios_tags.tag_db', 1)
        ->orderBy('studios.name', 'ASC')
        ->get();

        return view('admin.edit_studios', compact('studios', 'tags', 'tags_films'));
    
    }


    public function open_main_folder_studios() {

 
        $url_film = "..\\..\\filmy\\thumbnail\\studios\\";

        if (is_dir($url_film)){
        shell_exec('start '.$url_film.'');
        return redirect()->back();
        }
        else{
            return redirect()->back()->with('msg_errors', 'Błąd wyświetlania folderu. Prosimy o kontakt z administratorem.');
        }
        

    }


    public function open_folder_studios($id) {

        $tags = studios::find($id);

  
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

    public function open_folder_studios_next($id) {

        $tags = studios::find($id);

  
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
    //==================================================================== END ====================================================================== //





    //==================================================================== EDIT SAVE STUDIOS =========================================================== //
    public function edit_studios_save(Request $request){

        $rules = [
            'studios_name' => 'required',
            

        ];

        $customMessages = [
            'studios_name.required' => 'Wymagana nazwa studia!',
            

        ];



        $this->validate($request, $rules, $customMessages);

        
        $thumbnail = $request -> input('thumbnail_studios');
        $name = $request -> input('studios_name');
        $id = $request -> input('studios_id');
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




        if ($_FILES['thumbnail_studios']['size'] > 0 )
        {
        
        $path = $request->file('thumbnail_studios')->store('thumbnail/studios'); //save file from form

        if (file_exists("../../filmy/".$id.".png")){
        unlink("../../filmy/".$id.".png"); //delete file
        }

        if(!is_null($height_img) && !is_null($width_img)){
                        
            if($resize_img === "1"){
               // RESIZE IMAGE!!!!!
            $open_image = "../../filmy/".$path;
            $save_in = '../../filmy/thumbnail/studios/'.$id.'.png';
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
            $save_in = '../../filmy/thumbnail/studios/'.$id.'.png';
            $image_resize = Image::make($open_image);              
            $image_resize->resize(350, 350);
            $image_resize->save($save_in, 90, 'jpg');
            }
            else
            {

                $image_url = "../../filmy/".$path;
                $img = Image::make($image_url);
                $img->save('../../filmy/thumbnail/studios/'.$id.'.png', 90, 'jpg');

            }

        }
        
        unlink("../../filmy/".$path.""); //delete file
        }

        $studios_chk = DB::table('studios')
        ->select('name')
        ->where('id', $id)
        ->get();    
        foreach($studios_chk as $studios_chk){
            $studios_chk = $studios_chk->name;
        }

        if($studios_chk !== $name){

            $films_studios_db = DB::table('studios')
            ->select('id')
            ->where('name', $name)
            ->get();

            if($films_studios_db->isEmpty()){

                $studios = studios::find($id);
                $studios->name = $name;
                $studios->thumbnail = '../../filmy/thumbnail/studios/'.$id.'.png';
                $studios->rating = $rating;
                $studios->save(); 
                $last_id_db = $studios->id;
            }else{
                foreach($films_studios_db as $films_studios_db)
                {
                    $films_studios_db = $films_studios_db->id;
                }
                
                return redirect()->back()->with('msg_errors', 'Próbujesz zmienić nazwę tagu z "'.$studios_chk.'" na "'.$name.'" który już istnieje w bazie danych. </br>
                Kliknij <a href="'.url('/admin_studios').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$name.'" lub "'.$films_studios_db.'"
                ');
            }

        }
    

        return redirect()->back()->with('msg_success', $name.' Został edytowany poprawnie!');
        
       
    }
    //==================================================================== END ====================================================================== //






    //==================================================================== DELETE STUDIOS =========================================================== //
    public function delete_studios($id){

            
        $studios = DB::table('studios')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        $films_studios = DB::table('films_studios') 
        ->where('studios_id', '=', $id)
        ->delete();
        
        foreach($studios as $studios){
            $name = $studios->name;
            $thumbnail = $studios->thumbnail;
        }

        if (file_exists($thumbnail)) {
            unlink($thumbnail);  
        }  

        $studios = DB::table('studios') 
        ->where('id', '=', $id)
        ->delete();

        $countfiles = DB::table('studios')->count();

        if($countfiles == 0){
            $max = DB::table('studios')->max('id') + 1; 
            DB::statement("ALTER TABLE studios AUTO_INCREMENT =  $max");

        }

        if($studios === 1) {
            return redirect()->back()->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }
    //==================================================================== END ====================================================================== //





    //==================================================================== DELETE ALL STUDIOS =========================================================== //
    public function delete_all_studios(){

        
        $studios = DB::table('studios')
        ->get();

        foreach($studios as $studios){
            $name = $studios->name;
            $thumbnail = $studios->thumbnail;
        

            if (file_exists($thumbnail)) {
                unlink($thumbnail);  
            }

        }
        


        $studios = DB::table('studios') 
        ->delete();

        if(Schema::hasTable('films_studios')){
            $films_studios = DB::table('films_studios') 
            ->delete();
        }

        if(Schema::hasTable('tags_studios')){
            $site = DB::table('tags_studios') 
            ->delete();
        }

            $max = DB::table('studios')->max('id') + 1; 
            DB::statement("ALTER TABLE studios AUTO_INCREMENT =  $max");


        if($studios === 1) {
            return redirect()->back()->with('success', 'Wszystkie wytwórnie zostały usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Wytwórnie nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }
    //==================================================================== END ====================================================================== //





    //==================================================================== SEARCH STUDIOS IN TABLE VIEW =========================================================== //
    public function searchstudios_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $studios = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($studios as $studios){
            $id = $studios->id;
            $name = $studios->name;
            $thumbnail = $studios->thumbnail;
            $url = url('/edit_studios',$id);
            $url_delete = url('/delete_files_from_admin_search_studios',$id);
            $url_films = url('/select_studios',$id);

            $count_films = DB::table('studios')
            ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
            ->join('films', 'films.id', '=', 'films_studios.film_id')
            ->orderBy('name', 'ASC')
            ->select('films.*')
            ->where('studios.id', $id)
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
    //==================================================================== END ====================================================================== //

    
    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_studios($id){
 
        $files_db = DB::table('studios')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Wytwórnia";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_studios_save($id){

        $files_db = DB::table('studios')
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

        $studios = DB::table('studios') 
        ->where('id', '=', $id)
        ->delete();
  
        $films_studios = DB::table('films_studios') 
        ->where('studios_id', '=', $id)
        ->delete();

        if($studios === 1) {
            return redirect('admin_studios')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_studios')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //

    

    




}
