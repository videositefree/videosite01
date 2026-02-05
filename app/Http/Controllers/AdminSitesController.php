<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDO;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\site;

use App\stars_tags;

use App\sites_tags;
use Illuminate\Validation\Rules\Exists;
use Image;

class AdminSitesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }

    
   //============================================================== ADMIN TABLE SITE =========================================================== //
   public function site(){

    $sites = DB::table('site')->orderBy('id', 'DESC')->paginate(27);
    $all_sites = DB::table('site')->orderBy('id', 'DESC')->paginate(27);
    $count_sites = DB::table('site')->count();
    return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));

    
   
    }

    //==================================================================== SORT SITE BY  =========================================================== //

    public function site_id_asc(){

        $sites = DB::table('site')->orderBy('id', 'ASC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('id', 'ASC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_name_asc(){

        $sites = DB::table('site')->orderBy('name', 'ASC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('name', 'ASC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_name_desc(){

        $sites = DB::table('site')->orderBy('name', 'DESC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('name', 'DESC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_description_asc(){

        $sites = DB::table('site')->orderBy('description', 'ASC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('description', 'ASC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_description_desc(){

        $sites = DB::table('site')->orderBy('description', 'DESC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('description', 'DESC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_rating_asc(){

        $sites = DB::table('site')->orderBy('rating', 'ASC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('rating', 'ASC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }

    public function site_rating_desc(){

        $sites = DB::table('site')->orderBy('rating', 'DESC')->paginate(27);
        $all_sites = DB::table('site')->orderBy('rating', 'DESC')->paginate(27);
        $count_sites = DB::table('site')->count();
        return view('admin.admin_site',compact('sites', 'count_sites', 'all_sites'));
        
    
    }


    //==================================================================== END  =========================================================== //






    //==================================================================== ADD STARS =========================================================== //
    public function add_site(){

        return view('admin.admin_add_site');

    }
    //==================================================================== END  =========================================================== //




    //================================================================== SAVE ADD STARS =========================================================== //
    public function save_site(Request $request){

        $rules = [
            'site_name' => 'required',
            'site_link' => 'required',
            'site_description' => 'required',
            'rating' => 'required',
        ];

        $customMessages = [
            'site_name.required' => 'Wymagana nazwa strony!',
            'site_link.required' => 'Wymagany link do strony!',
            'site_description.required' => 'Wymagany opis strony!',
            'rating.required' => 'Wymagana ocena strony!'
        ];

        $this->validate($request, $rules, $customMessages);

        $site_name = $request -> input('site_name');
        $site_link = $request -> input('site_link');
        $site_description = $request -> input('site_description');
        $rating = $request -> input('rating');

        $films_site_db = DB::table('site')
        ->select('id')
        ->where('name', $site_name)
        ->where('link', $site_link)
        ->get();

        if($films_site_db->isEmpty()){
        
            $site = new site;
            $site->name = $site_name;
            $site->link = $site_link;
            $site->description = $site_description;
            $site->rating = $rating;
            $site->save();
            $id_db = $site->id;

        }else{
            foreach($films_site_db as $films_site_db)
            {
                $films_site_db = $films_site_db->id;
            }
            
            return redirect()->back()->with('msg_errors', 'Strona o nazwie "'.$site_name.'" i linku "'.$site_link.'" już istnieje. </br>
            Kliknij <a href="'.url('/admin_sites').'">TUTAJ</a> następnie użyj wyszukiwarki z lewej strony i wpisz "'.$site_name.'" lub "'.$films_site_db.'"
            ');
        }

       
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
                        ->where('site_id', $id_db)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 0)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new sites_tags;                            
                            $films_tags->site_id = $id_db;
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
                        ->where('site_id', $id_db)
                        ->where('tag_id', $tag_id)
                        ->where('tag_db', 1)
                        ->get();

                        if($films_tags_db->isEmpty()){

                            $films_tags = new sites_tags;                            
                            $films_tags->site_id = $id_db;
                            $films_tags->tag_id = $tag_id;
                            $films_tags->tag_db = 1;
                            $films_tags->save();
                            $last_id_db = $films_tags->id;
                        }
                        
                        
                    }

                    
                }
            }
        }

        
            
            if(isset($last_id_db) OR isset($id_db)) {
            
                return redirect()->back()->with('msg_success', 'Dodałeś Nową stronę!<br>');
            }
            else
            {
                return redirect()->back()->with('msg_errors', 'Błąd dodawania nowej strony. Prosimy o kontakt z administratorem.');
            }
        
        
    }
    //==================================================================== END  =========================================================== //





    //==================================================================== EDIT STARS =========================================================== //
    public function edit_site($id){

        $site = site::find($id);

        if($site === null){
            return redirect('/admin_sites')->with('errors', 'Brak rekordu w bazie danych.');        
        }

        $tags = DB::table('site')
        ->join('sites_tags', 'sites_tags.site_id', '=', 'site.id')
        ->join('tags_sites', 'sites_tags.tag_id', '=', 'tags_sites.id')
        ->select('tags_sites.name', 'sites_tags.tag_id', 'sites_tags.id')
        ->where('site.id', $id)
        ->where('sites_tags.tag_db', 0)
        ->orderBy('site.name', 'ASC')
        ->get();

        $tags_films = DB::table('site')
        ->join('sites_tags', 'sites_tags.site_id', '=', 'site.id')
        ->join('tags', 'sites_tags.tag_id', '=', 'tags.id')
        ->select('tags.name', 'sites_tags.tag_id', 'sites_tags.id')
        ->where('site.id', $id)
        ->where('sites_tags.tag_db', 1)
        ->orderBy('site.name', 'ASC')
        ->get();

      

        return view('admin.edit_site', compact('site', 'tags', 'tags_films'));

    }
    //==================================================================== END  =========================================================== //
    



    //==================================================================== EDIT STARS SAVE =========================================================== //
    public function edit_site_save(Request $request){

        $rules = [
            'site_link' => 'required',
            'site_description' => 'required',
            
        ];

        $customMessages = [
            'site_link.required' => 'Wymagany link do strony!',
            'site_description.required' => 'Wymagany opis strony!',
            

        ];


        $this->validate($request, $rules, $customMessages);

        
        $name = $request -> input('site_name');
        $link = $request -> input('site_link');
        $description = $request -> input('site_description');
        $id = $request -> input('sites_id');
        $rating = $request -> input('rating');
        $hidde_rating = $request -> input('hidden_rating');


        if(empty($rating)){
            $rating = $hidde_rating;
        }
        else
        {
            $rating = $request->input('rating');
        }



        $site = site::find($id);
        $site->name = $name;
        $site->link = $link;
        $site->description = $description;
        $site->rating = $rating;
        $site->save(); 
        $last_id_db = $site->id;

        if(isset($last_id_db)) {

            return redirect()->back()->with('msg_success', $name.' Został edytowany poprawnie!');
        }
        else
        {
                return redirect()->back()->with('msg_errors', 'Błąd edycji rekordu. Prosimy o kontakt z administratorem.');
        }
    }   

    //==================================================================== END  =========================================================== //






    //==================================================================== DELETE STARS =========================================================== //
    public function delete_site($id){

        $sites = DB::table('site')
        ->where('id', '=', $id)
        ->select('*')
        ->get();

        foreach($sites as $sites){
            $name = $sites->name;
        }

        

        $site = DB::table('site') 
        ->where('id', '=', $id)
        ->delete();

        $countfiles = DB::table('site')->count();

        if($countfiles == 0){
            $max = DB::table('site')->max('id') + 1; 
            DB::statement("ALTER TABLE site AUTO_INCREMENT =  $max");

        }

    

        if($site === 1) 
        {
            return redirect()->back()->with('success', $name.' została usunięta!');
        }
        else
        {
            return redirect()->back()->with('errors', 'rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }

    //==================================================================== END  =========================================================== //








    //==================================================================== DELETE ALL STARS =========================================================== //
    public function delete_all_site(){

        
        $site = DB::table('site')
        ->get();


        $site = DB::table('site') 
        ->delete();

        if(Schema::hasTable('tags_site')){
            $site = DB::table('tags_site') 
            ->delete();
        }

    
        $max = DB::table('site')->max('id') + 1; 
        DB::statement("ALTER TABLE site AUTO_INCREMENT =  $max");

        

        if($site === 1) {
            return redirect()->back()->with('success', 'Wszystkie strony zostały usunięty!');
        }
        else
        {
            return redirect()->back()->with('errors', 'Strony nie zostały usunięte!</br> Prosimy o kontakt z administratorem.');
        }

        
        
    }
    //==================================================================== END  =========================================================== //






    //============================================================= SEARCH STAR IN TABLE VIEW =========================================================== //
    public function searchsite_admin(Request $request)
    {

        $searchTerm = $request -> get('query');
        
        $site = DB::table('site')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(4)
        ->get();

        $count = DB::table('site')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->Orwhere('id', 'like', '%'.$searchTerm.'%')
        ->count();

        if($count>0){

        foreach($site as $site){
        $id = $site->id;
        $name = $site->name;
        $description = $site->description;
        $link = $site->link;

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

        $url = url('/edit_site',$id);
        $url_delete = url('/delete_files_from_admin_search_site',$id);
        echo '
        <div class="col-sm-3">
        <div class=" m-2">
            <div class="card video-wrapper" style="background-color: #666666;">

            <a href="'.$url_films.'">
            <div class="film_number_search">
                <i class="fas fa-tag">&nbsp;&nbsp;'.$count_films.'</i>
            </div>
            </a>
            
                
            <div class="card-body">
                <p class="card-text"><a style="color: #cffd00; text-decoration: none;" href="'.$link.'" target="_blank"><b>'.$name.'</b></a></p><br>
                '.$description.'
            </div>

            <a href="'.$url.'" class="btn btn-info" style="margin-bottom:5px;">Edytuj</a>
            <a href="'.$url_delete.'" class="btn btn-danger">Usuń</a>
            </div>
            <div style="margin-bottom: 30px";</div>
            
        </div></div>
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
    //==================================================================== END  =========================================================== //





    //============================================================== SEARCH IN ADMIN FILMS ========================================================================== //

    public function delete_files_from_admin_search_site($id){
 
        $files_db = DB::table('site')
        ->where('id', $id)
        ->select('*')
        ->get();

        $name_files = "Strona";

        return view('admin.admin_delete_search_files', compact('files_db', 'name_files', 'id'));
    } 
    
    // DELTE
    public function delete_files_from_admin_search_site_save($id){

        $files_db = DB::table('site')
        ->where('id', $id)
        ->select('*')
        ->get();

        foreach($files_db as $files_db){
        $name = $files_db->name;
        }

        $site = DB::table('site') 
        ->where('id', '=', $id)
        ->delete();

        if($site === 1) {
            return redirect('admin_sites')->with('success', $name.' został usunięty!');
        }
        else
        {
            return redirect('admin_sites')->with('errors', 'Rekord nie został usunięty!</br> Prosimy o kontakt z administratorem.');
        }
    } 

    //============================================================== END ========================================================================== //


    
    
        

    




}
