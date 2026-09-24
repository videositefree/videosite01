<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\tags;

use App\stars;

use App\studios;

use Illuminate\Support\Collection;

class AjaxTagController extends Controller
{

    // =========================================================================================================
    // POMOCNICZE METODY — gettag()/getstar()/getstudios() były trzema
    // kopiami tej samej logiki (różnią się tylko nazwą tabeli i nazwą
    // klucza id w odpowiedzi JSON). searchtag()/searchtag_stars()/
    // searchstar()/searchstudios()/searchtag_studios() budowały ten sam
    // fragment HTML pięć razy z osobna — w dwóch wariantach nawet z
    // BŁĘDNIE zagnieżdżonymi tagami <a> jeden w drugim (nieprawidłowy
    // HTML: <a><a></a></a>), bo do tej samej karty doklejano drugi link
    // do dokładnie tego samego adresu. Zamienione na jeden, poprawny
    // komponent karty.
    // =========================================================================================================

    private function autocompletePayload($table, $searchTerm, $idKey = null)
    {
        $rows = DB::table($table)
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->select('*')
            ->take(10)
            ->get();

        if ($rows->count() === 0) {
            return [[
                'label' => 'Brak wyników wyszukiwania.',
                'img' => 'icon/app.blade/notfing_found.png',
                'disabled' => 'ui-state-disabled',
            ]];
        }

        $output = [];
        foreach ($rows as $row) {
            $item = [
                'value' => $row->name,
                'label' => $row->name,
                'img' => '../'.$row->thumbnail,
            ];
            if ($idKey) {
                $item[$idKey] = $row->id;
            }
            $output[] = $item;
        }
        return $output;
    }

    private function renderEntityCard($url, $thumbnail, $count, $name, $icon = 'fa-video')
    {
        return '
            <a href="'.$url.'" class="entity-card">
                <div class="entity-card__media">
                    <img src="'.$thumbnail.'" alt="'.htmlspecialchars($name).'" loading="lazy">
                    <div class="film_number"><i class="fas '.$icon.'"></i>&nbsp;&nbsp;'.$count.'</div>
                </div>
                <div class="entity-card__body">'.htmlspecialchars($name).'</div>
            </a>
        ';
    }

    private function renderEmptyResult()
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


    // ================================================================= ADD NEW FILMS! ====================================================== //
    // Search metod for add film blade

    public function gettag(Request $request)
    {
        echo json_encode($this->autocompletePayload('tags', $request->get('term'), 'tag_id'), true);
    }

    public function getstar(Request $request)
    {
        echo json_encode($this->autocompletePayload('stars', $request->get('term'), 'star_id'), true);
    }

    public function getstudios(Request $request)
    {
        echo json_encode($this->autocompletePayload('studios', $request->get('term'), 'studios_id'), true);
    }


    public function gettag_stars(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_stars')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }




    public function gettag_studios(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }



    public function gettag_sites(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags_sites')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = 'icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }

// ================================================================= END! ====================================================== //




// ================================================================= EDIT FILMS! ====================================================== //
    // Search metod for edit film blade
    public function gettagg(Request $request){

        $searchTerm = $request -> get('term');
        
        $tags = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('tags')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($tags as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['tag_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;
            }
        }
        else
        {
       
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = '../icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;
        }

        echo json_encode($output, true);
        
    }

    public function getstarr(Request $request){

            $searchTerm = $request -> get('term');
            
            $stars = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->select('*')
            ->take(10)
            ->get();
    
            $total_row = DB::table('stars')
            ->where('name', 'like', '%'.$searchTerm.'%')
            ->count();
    
            
            if($total_row > 0)
            {
                foreach($stars as $row)
                {
                
                    $temp_array['value'] = $row->name;
                    $temp_array['label'] = $row->name;
                    $temp_array['star_id'] = $row->id;
                    $temp_array['img'] = '../'.$row->thumbnail;
                    $output[] = $temp_array;
                }
            }
            else
            {
           
                $temp_array['label'] = 'Brak wyników wyszukiwania.';
                $temp_array['img'] = '../icon/app.blade/notfing_found.png';
                $temp_array['disabled'] = 'ui-state-disabled';
                $output[] = $temp_array;
            }
    
            echo json_encode($output, true);
            
    }

    public function getstudioss(Request $request){

        $searchTerm = $request -> get('term');
        
        $studios = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->select('*')
        ->take(10)
        ->get();

        $total_row = DB::table('studios')
        ->where('name', 'like', '%'.$searchTerm.'%')
        ->count();

        
        if($total_row > 0)
        {
            foreach($studios as $row)
            {
            
                $temp_array['value'] = $row->name;
                $temp_array['label'] = $row->name;
                $temp_array['studios_id'] = $row->id;
                $temp_array['img'] = '../'.$row->thumbnail;
                $output[] = $temp_array;      

            }
        }
        else
        {
            $temp_array['label'] = 'Brak wyników wyszukiwania.';
            $temp_array['img'] = '../icon/app.blade/notfing_found.png';
            $temp_array['disabled'] = 'ui-state-disabled';
            $output[] = $temp_array;      

        }

        echo json_encode($output, true);
        
    }

    // END


    // =========================================================================================================
    // ŻYWE WYSZUKIWANIE (podpowiedzi wpisywane na bieżąco na stronach
    // tagi/gwiazdy/wytwórnie) — poprzednio 5 osobnych metod po ~85 linii
    // każda, w tym dwie z nieprawidłowo zagnieżdżonymi <a> w <a>.
    // =========================================================================================================

    public function searchtag(Request $request)
    {
        $searchTerm = $request->get('query');

        $tags = DB::table('tags')->where('name', 'like', '%'.$searchTerm.'%')->take(4)->get();

        if ($tags->count() > 0) {
            foreach ($tags as $tag) {
                $count_films = DB::table('tags')
                    ->join('films_tags', 'films_tags.tag_id', '=', 'tags.id')
                    ->join('films', 'films.id', '=', 'films_tags.film_id')
                    ->where('tags.id', $tag->id)
                    ->where('activ', '=', '1')
                    ->distinct()
                    ->count();

                echo $this->renderEntityCard(url('/select_categories', $tag->id), $tag->thumbnail, $count_films, $tag->name);
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    // Search in user tag stars
    public function searchtag_stars(Request $request)
    {
        $searchTerm = $request->get('query');

        $tags = DB::table('tags_stars')->where('name', 'like', '%'.$searchTerm.'%')->take(4)->get();

        if ($tags->count() > 0) {
            foreach ($tags as $tag) {
                $count_films = DB::table('tags_stars')
                    ->join('stars_tags', 'stars_tags.tag_id', '=', 'tags_stars.id')
                    ->join('stars', 'stars.id', '=', 'stars_tags.star_id')
                    ->where('tags_stars.id', $tag->id)
                    ->where('stars_tags.tag_db', 0)
                    ->distinct()
                    ->count();

                echo $this->renderEntityCard(url('/select_categories_stars', $tag->id), $tag->thumbnail, $count_films, $tag->name, 'fa-tag');
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    public function searchstar(Request $request)
    {
        $searchTerm = $request->get('query');

        $stars = DB::table('stars')->where('name', 'like', '%'.$searchTerm.'%')->take(4)->get();

        if ($stars->count() > 0) {
            foreach ($stars as $star) {
                $count_films = DB::table('stars')
                    ->join('films_stars', 'films_stars.stars_id', '=', 'stars.id')
                    ->join('films', 'films.id', '=', 'films_stars.film_id')
                    ->where('stars.id', $star->id)
                    ->where('activ', '=', '1')
                    ->distinct()
                    ->count();

                echo $this->renderEntityCard(url('/select_stars', $star->id), $star->thumbnail, $count_films, $star->name);
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    public function searchstudios(Request $request)
    {
        $searchTerm = $request->get('query');

        $studios = DB::table('studios')->where('name', 'like', '%'.$searchTerm.'%')->take(4)->get();

        if ($studios->count() > 0) {
            foreach ($studios as $studio) {
                $count_films = DB::table('studios')
                    ->join('films_studios', 'films_studios.studios_id', '=', 'studios.id')
                    ->join('films', 'films.id', '=', 'films_studios.film_id')
                    ->where('studios.id', $studio->id)
                    ->where('activ', '=', '1')
                    ->distinct()
                    ->count();

                echo $this->renderEntityCard(url('/select_studios', $studio->id), $studio->thumbnail, $count_films, $studio->name);
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }


    //==================================================================== SEARCH TAG IN TABLE VIEW =========================================================== //

    public function searchtag_studios(Request $request)
    {
        $searchTerm = $request->get('query');

        $tags = DB::table('tags_studios')->where('name', 'like', '%'.$searchTerm.'%')->take(4)->get();

        if ($tags->count() > 0) {
            foreach ($tags as $tag) {
                $count_films = DB::table('tags_studios')
                    ->join('studios_tags', 'studios_tags.tag_id', '=', 'tags_studios.id')
                    ->join('studios', 'studios.id', '=', 'studios_tags.studio_id')
                    ->where('tags_studios.id', $tag->id)
                    ->where('studios_tags.tag_db', 0)
                    ->distinct()
                    ->count();

                echo $this->renderEntityCard(url('/select_categories_studios', $tag->id), $tag->thumbnail, $count_films, $tag->name, 'fa-tag');
            }
        } else {
            echo $this->renderEmptyResult();
        }
    }

}
