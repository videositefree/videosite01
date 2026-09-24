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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $requiredDirectories = [
            '../../filmy/' => 'Niestety nie możemy automatycznie utworzyć folderu filmy
            Spróbuj dodać folder ręcznie następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/short' => 'Niestety nie możemy automatycznie utworzyć folderu short!
            Spróbuj dodać podfolder short ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/conversion' => 'Niestety nie możemy automatycznie utworzyć folderu conversion!
            Spróbuj dodać podfolder conversion ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/conversion/delete' => 'Niestety nie możemy automatycznie utworzyć folderu delete!
            Spróbuj dodać podfolder delete ręcznie do folderu filmy/conversion następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/cut' => 'Niestety nie możemy automatycznie utworzyć folderu cut!
            Spróbuj dodać podfolder cut ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/cut/delete' => 'Niestety nie możemy automatycznie utworzyć folderu delete!
            Spróbuj dodać podfolder delete ręcznie do folderu filmy/cut następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/thumbnail' => 'Niestety nie możemy automatycznie utworzyć folderu thumbnail!
            Spróbuj dodać podfolder thumbnail ręcznie do folderu filmy następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/thumbnail/stars' => 'Niestety nie możemy automatycznie utworzyć folderu stars!
            Spróbuj dodać podfolder stars ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/thumbnail/studios' => 'Niestety nie możemy automatycznie utworzyć folderu studios!
            Spróbuj dodać podfolder studios ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora',

            '../../filmy/thumbnail/tags' => 'Niestety nie możemy automatycznie utworzyć folderu tags!
            Spróbuj dodać podfolder tags ręcznie do folderu filmy/thumbnail następnie spróbuj ponownie przejść do panelu administratora',
        ];

        foreach ($requiredDirectories as $path => $errorMessage) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path)) {
                return response()->json(['errors' => ['file' => [$errorMessage]]], 422);
            }
        }

        $directory = "../../filmy/short";
        if (!file_exists($directory)) {
            return response()->json(['errors' => ['file' => ['Dodaj folder "short" do folderu "filmy" i spróbuj ponownie!']]], 422);
        }

        $type = $request->file('file')->extension();
        $name = $request->input('film_name');
        $multiTag = $request->input('multiTag');
        $katalog = $request->input('katalog');
        $time_sec = $request->input('time_sec');
        $time_sec_thumbnail = $request->input('time_sec_thumbnail');

        $checkbox_stars_tag = $request->input('extra_tag_stars');
        $checkbox_studios_tag = $request->input('extra_tag_studios');

        // to jest jedyna część, która faktycznie "przesyła" plik — w tym
        // momencie transfer z przeglądarki na serwer już się zakończył
        // (Laravel odebrał cały plik do katalogu tymczasowego razem z resztą
        // requestu), to tylko przenosi go z tymczasowej lokalizacji do
        // docelowego folderu na dysku, więc jest szybkie
        $ur = $request->file('file')->store($katalog);
        $url = "../../filmy/".$ur."";

        $bytes = $request->file('file')->getSize();

        if ($bytes >= 1073741824) {
            $size = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $size = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $size = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $size = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $size = $bytes . ' byte';
        } else {
            $size = '0 bytes';
        }

        $rating = empty($request->input('star')) ? "1" : $request->input('star');

        $films = new films;
        $films->name = $name;
        $films->url = $url;
        $films->short = "";
        $films->thumbnail = "";
        $films->type = $type;
        $films->size = $size;
        $films->rating = $rating;
        $films->duration = "1";
        $films->activ = "1";
        $films->no_films = "0";
        $films->no_thumbnail = "0";
        $films->no_short = "0";
        $films->save();

        $last_id = $films->id;

        $films = films::find($last_id);
        $films->short = '../../filmy/short/'.$last_id.'.mp4';
        $films->thumbnail = '../../filmy/thumbnail/'.$last_id.'.png';
        $films->save();

        // reszta (tagi/gwiazdy/wytwórnie, zwiastun, miniatura) dzieje się
        // teraz w tle — patrz app/Jobs/ProcessFilmUpload.php — żeby ten
        // request mógł się zakończyć od razu i przeglądarka mogła zacząć
        // odpytywać o realny postęp zamiast czekać w ciemno na cały proces
        $token = bin2hex(random_bytes(20));

        DB::table('upload_progress')->insert([
            'token' => $token,
            'film_id' => $last_id,
            'stage' => 'queued',
            'message' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \App\Jobs\ProcessFilmUpload::dispatch(
            $token,
            $last_id,
            $ur,
            $url,
            $time_sec,
            $time_sec_thumbnail,
            $name,
            $multiTag,
            $request->input('multiStar'),
            $request->input('multiStudios'),
            $checkbox_stars_tag,
            $checkbox_studios_tag
        );

        return response()->json([
            'token' => $token,
            'film_id' => $last_id,
        ]);
    }


    public function uploadStatus($token)
    {
        $progress = DB::table('upload_progress')->where('token', $token)->first();

        if (!$progress) {
            return response()->json(['stage' => 'unknown'], 404);
        }

        return response()->json([
            'stage' => $progress->stage,
            'message' => $progress->message,
            'film_id' => $progress->film_id,
        ]);
    }


    
}
