<?php

namespace App\Jobs;

use App\films_tags;
use App\films_stars;
use App\films_studios;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Image;

class ProcessFilmUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // kodowanie zwiastunu może trwać długo dla dużych plików
    public $timeout = 3600;

    protected $token;
    protected $filmId;
    protected $storedPath;   // $ur z kontrolera - ścieżka względna wewnątrz "filmy/"
    protected $url;          // "../../filmy/".$ur (na razie nieużywane bezpośrednio, ale zostawione dla zgodności z sygnaturą dispatch())
    protected $trailerStart;
    protected $thumbnailSecond;
    protected $filmName;
    protected $tagNames;
    protected $starNames;
    protected $studioNames;
    protected $inheritStarTags;
    protected $inheritStudioTags;

    public function __construct(
        $token,
        $filmId,
        $storedPath,
        $url,
        $trailerStart,
        $thumbnailSecond,
        $filmName,
        $tagNames,
        $starNames,
        $studioNames,
        $inheritStarTags,
        $inheritStudioTags
    ) {
        $this->token = $token;
        $this->filmId = $filmId;
        $this->storedPath = $storedPath;
        $this->url = $url;
        $this->trailerStart = $trailerStart;
        $this->thumbnailSecond = $thumbnailSecond;
        $this->filmName = $filmName;
        $this->tagNames = $tagNames;
        $this->starNames = $starNames;
        $this->studioNames = $studioNames;
        $this->inheritStarTags = $inheritStarTags;
        $this->inheritStudioTags = $inheritStudioTags;
    }

    public function handle()
    {
        try {
            $this->setStage('tags', 'Zapisywanie tagów, gwiazd i wytwórni...');
            $this->attachTagsByName($this->filmId, $this->tagNames);
            $this->attachStarsByName($this->filmId, $this->starNames, $this->inheritStarTags);
            $this->attachStudiosByName($this->filmId, $this->studioNames, $this->inheritStudioTags);

            $this->setStage('trailer', 'Tworzenie zwiastunu...');
            $this->createTrailer();

            $this->setStage('thumbnail', 'Generowanie miniatury...');
            $this->createThumbnail();

            $this->setStage('done', 'Gotowe!');
        } catch (\Throwable $e) {
            Log::error('ProcessFilmUpload: błąd przy przetwarzaniu filmu ID '.$this->filmId.' - '.$e->getMessage());

            $this->setStage('error', 'Wystąpił błąd podczas przetwarzania filmu: '.$e->getMessage());
        }
    }

    private function setStage($stage, $message = null)
    {
        DB::table('upload_progress')
            ->where('token', $this->token)
            ->update([
                'stage' => $stage,
                'message' => $message,
                'updated_at' => now(),
            ]);
    }

    // ---------------------------------------------------------------------
    // Tagi / gwiazdy / wytwórnie
    // Logika przeniesiona 1:1 z App\Http\Controllers\AdminFilmsController
    // (attachTagToFilm / attachTagsByName / attachStarsByName / attachStudiosByName),
    // żeby zachowanie było identyczne z resztą panelu.
    // ---------------------------------------------------------------------

    private function attachTagToFilm($filmId, $tagId)
    {
        $exists = DB::table('films_tags')
            ->where('film_id', $filmId)
            ->where('tag_id', $tagId)
            ->exists();

        if (!$exists) {
            $pivot = new films_tags;
            $pivot->film_id = $filmId;
            $pivot->tag_id = $tagId;
            $pivot->save();
            return $pivot->id;
        }
        return null;
    }

    private function attachTagsByName($filmId, $names)
    {
        if (empty($names)) {
            return;
        }

        foreach ($names as $name) {
            $matches = DB::table('tags')->where('name', '=', $name)->get();
            foreach ($matches as $match) {
                $this->attachTagToFilm($filmId, $match->id);
            }
        }
    }

    private function attachStarsByName($filmId, $names, $inheritTags)
    {
        if (empty($names)) {
            return;
        }

        foreach ($names as $name) {
            $matches = DB::table('stars')->where('name', '=', $name)->get();

            foreach ($matches as $star) {
                $exists = DB::table('films_stars')
                    ->where('film_id', $filmId)
                    ->where('stars_id', $star->id)
                    ->exists();

                if (!$exists) {
                    $pivot = new films_stars;
                    $pivot->film_id = $filmId;
                    $pivot->stars_id = $star->id;
                    $pivot->save();
                }

                if (!is_null($inheritTags)) {
                    $inheritedTags = DB::table('stars')
                        ->join('stars_tags', 'stars_tags.star_id', '=', 'stars.id')
                        ->join('tags', 'stars_tags.tag_id', '=', 'tags.id')
                        ->select('tags.name', 'stars_tags.tag_id', 'stars_tags.id')
                        ->where('stars.id', $star->id)
                        ->where('stars_tags.tag_db', 1)
                        ->orderBy('stars.name', 'ASC')
                        ->get();

                    foreach ($inheritedTags as $row) {
                        $this->attachTagToFilm($filmId, $row->tag_id);
                    }
                }
            }
        }
    }

    private function attachStudiosByName($filmId, $names, $inheritTags)
    {
        if (empty($names)) {
            return;
        }

        foreach ($names as $name) {
            $matches = DB::table('studios')->where('name', '=', $name)->get();

            foreach ($matches as $studio) {
                $exists = DB::table('films_studios')
                    ->where('film_id', $filmId)
                    ->where('studios_id', $studio->id)
                    ->exists();

                if (!$exists) {
                    $pivot = new films_studios;
                    $pivot->film_id = $filmId;
                    $pivot->studios_id = $studio->id;
                    $pivot->save();
                }

                if (!is_null($inheritTags)) {
                    $inheritedTags = DB::table('studios')
                        ->join('studios_tags', 'studios_tags.studio_id', '=', 'studios.id')
                        ->join('tags', 'studios_tags.tag_id', '=', 'tags.id')
                        ->select('tags.name', 'studios_tags.tag_id', 'studios_tags.id')
                        ->where('studios.id', $studio->id)
                        ->where('studios_tags.tag_db', 1)
                        ->orderBy('studios.name', 'ASC')
                        ->get();

                    foreach ($inheritedTags as $row) {
                        $this->attachTagToFilm($filmId, $row->tag_id);
                    }
                }
            }
        }
    }

    // ---------------------------------------------------------------------
    // Zwiastun / miniatura
    // Logika kodowania przeniesiona 1:1 z AdminFilmsController::edit_films_trailer_save()
    // i edit_films_thumbnail_save() - te same komendy FFmpeg, ten sam sposób
    // zapisu na dysk "short" / "thumbnail".
    // ---------------------------------------------------------------------

    private function createTrailer()
    {
        $check = shell_exec('ffmpeg -h');
        if (empty($check)) {
            throw new \Exception('ffmpeg nie jest zainstalowane / niedostępne w PATH serwera.');
        }

        $media = \FFMpeg::open($this->storedPath);
        $duration = $media->getDurationInSeconds();

        if ($duration <= 30) {
            throw new \Exception('Film jest zbyt krótki, aby przygotować zwiastun (min. ok. 40 sekund).');
        }

        $start = $this->trailerStart;

        if ($start > $duration) {
            $start = 720;
        }

        if ($start > $duration) {
            throw new \Exception('Zwiastun, który próbujesz wykonać, jest dłuższy niż cały film.');
        }

        // UWAGA: surowa biblioteka php-ffmpeg (\FFMpeg\FFMpeg::create()) - w odróżnieniu
        // od fasady \FFMpeg::open() powyżej - NIE korzysta z dysków Laravela, tylko
        // oczekuje prawdziwej ścieżki w systemie plików. Budujemy ją przez storage_path(),
        // bo to jedyny sposób żeby zadziałało niezależnie od "working directory" procesu -
        // a to jest różne przy żądaniu HTTP (Apache, CWD = public/) i przy workerze
        // kolejki uruchomionym jako usługa NSSM (CWD = katalog startowy usługi).
        // Gołe '../../filmy/...' (tak jak w oryginalnym kodzie kontrolera admina)
        // zakłada CWD = public/ i dlatego w Jobie zawodziło.
        $absoluteSourcePath = storage_path('../../filmy/'.$this->storedPath);
        $absoluteTrailerPath = storage_path('../../filmy/short/'.$this->filmId.'.mp4');

        $ffmpeg = \FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($absoluteSourcePath);
        $video->filters()->clip(
            \FFMpeg\Coordinate\TimeCode::fromSeconds($start),
            \FFMpeg\Coordinate\TimeCode::fromSeconds(15)
        );
        $video->save(new \FFMpeg\Format\Video\X264, $absoluteTrailerPath);
    }

    private function createThumbnail()
    {
        $check = shell_exec('ffmpeg -h');
        if (empty($check)) {
            throw new \Exception('ffmpeg nie jest zainstalowane / niedostępne w PATH serwera.');
        }

        // z tego samego powodu co w createTrailer() - ścieżka niezależna od CWD procesu
        $thumbnailPath = storage_path('../../filmy/thumbnail/'.$this->filmId.'.png');

        if (file_exists($thumbnailPath)) {
            unlink($thumbnailPath);
        }

        \FFMpeg::fromDisk('thumbnaill')
            ->open($this->storedPath)
            ->addFilter(function ($filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
            })
            ->getFrameFromSeconds($this->thumbnailSecond)
            ->export()
            ->toDisk('thumbnail')
            ->save($this->filmId.'.png');

        $image = Image::make($thumbnailPath);
        $image->resize(550, 350);
        $image->save($thumbnailPath);
    }
}
