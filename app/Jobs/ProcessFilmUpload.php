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
        $warnings = [];

        try {
            // czas trwania filmu - bez tego na stronie filmu pokazuje się 00:00:01
            $this->saveDuration();

            $this->setStage('tags', 'Zapisywanie tagów, gwiazd i wytwórni...');
            $this->attachTagsByName($this->filmId, $this->tagNames);
            $this->attachStarsByName($this->filmId, $this->starNames, $this->inheritStarTags);
            $this->attachStudiosByName($this->filmId, $this->studioNames, $this->inheritStudioTags);
        } catch (\Throwable $e) {
            // bez tagów / czasu film nadal istnieje w bazie - nie przerywamy całego procesu
            Log::error('ProcessFilmUpload: błąd (tagi/czas) film ID '.$this->filmId.' - '.$e->getMessage());
            $warnings[] = 'Nie wszystkie dane zostały zapisane: '.$e->getMessage();
        }

        // zwiastun i miniatura są od siebie niezależne - błąd jednego nie blokuje drugiego,
        // a sam film (już zapisany na dysku i w bazie) zostaje w każdym przypadku
        $this->setStage('trailer', 'Tworzenie zwiastunu...');
        try {
            $this->createTrailer();
        } catch (\Throwable $e) {
            Log::error('ProcessFilmUpload: zwiastun film ID '.$this->filmId.' - '.$this->describeError($e));
            $warnings[] = 'Zwiastun: '.$e->getMessage().' (szczegóły w storage/logs/laravel.log)';
        }

        $this->setStage('thumbnail', 'Generowanie miniatury...');
        try {
            $this->createThumbnail();
        } catch (\Throwable $e) {
            Log::error('ProcessFilmUpload: miniatura film ID '.$this->filmId.' - '.$this->describeError($e));
            $warnings[] = 'Miniatura: '.$e->getMessage().' (szczegóły w storage/logs/laravel.log)';
        }

        $this->setStage('done', empty($warnings) ? 'Gotowe!' : implode("\n", $warnings));

        // automatyczna kopia bazy po dodaniu filmu (max 20, najstarsze są kasowane);
        // po 'done', żeby nie opóźniać komunikatu, a błąd kopii nie psuje dodanego filmu
        try {
            \App\Support\DatabaseBackup::create();
        } catch (\Throwable $e) {
            Log::error('ProcessFilmUpload: automatyczna kopia bazy nie powiodła się - '.$e->getMessage());
        }
    }

    // pełny komunikat z ffmpeg (stderr) - "Encoding failed" sam w sobie nic nie mówi
    private function describeError(\Throwable $e)
    {
        $msg = get_class($e).': '.$e->getMessage();
        $prev = $e->getPrevious();
        while ($prev) {
            $msg .= "\n  -> ".get_class($prev).': '.$prev->getMessage();
            $prev = $prev->getPrevious();
        }
        return $msg;
    }

    private function absoluteSource()
    {
        return storage_path('../../filmy/'.$this->storedPath);
    }

    private function getDuration()
    {
        $media = \FFMpeg::open($this->storedPath);
        return (int) floor($media->getDurationInSeconds());
    }

    private function saveDuration()
    {
        $duration = $this->getDuration();
        if ($duration > 0) {
            DB::table('films')->where('id', $this->filmId)->update(['duration' => $duration]);
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
        $duration = $this->getDuration();

        if ($duration <= 30) {
            throw new \Exception('Film jest zbyt krótki, aby przygotować zwiastun (min. ok. 40 sekund).');
        }

        $length = 15;
        $start = (float) $this->trailerStart;

        // zwiastun nie może wyjść poza koniec filmu
        if ($start < 0) {
            $start = 0;
        }
        if ($start + $length > $duration) {
            $start = max(0, $duration - $length);
        }

        // surowe php-ffmpeg nie używa dysków Laravela - potrzebna prawdziwa ścieżka,
        // niezależna od katalogu roboczego procesu (Apache vs. worker kolejki)
        $absoluteSourcePath = $this->absoluteSource();
        $absoluteTrailerPath = storage_path('../../filmy/short/'.$this->filmId.'.mp4');

        if (file_exists($absoluteTrailerPath)) {
            @unlink($absoluteTrailerPath);
        }

        $ffmpeg = \FFMpeg\FFMpeg::create([
            'timeout' => 3600,
            'ffmpeg.threads' => 4,
        ]);
        $video = $ffmpeg->open($absoluteSourcePath);
        $video->filters()->clip(
            \FFMpeg\Coordinate\TimeCode::fromSeconds($start),
            \FFMpeg\Coordinate\TimeCode::fromSeconds($length)
        );

        // Domyślny X264 z php-ffmpeg używa kodeka audio libfdk_aac, którego większość
        // buildów ffmpeg (np. Windows) w ogóle nie ma -> "Encoding failed".
        // Wbudowany kodek "aac" jest dostępny zawsze.
        $format = new \FFMpeg\Format\Video\X264('aac', 'libx264');
        $format->setAdditionalParameters(['-pix_fmt', 'yuv420p', '-movflags', '+faststart']);

        $video->save($format, $absoluteTrailerPath);
    }

    private function createThumbnail()
    {
        $duration = $this->getDuration();

        // klatka musi leżeć wewnątrz filmu
        $second = (float) $this->thumbnailSecond;
        if ($second < 0 || $second >= $duration) {
            $second = max(0, floor($duration / 2));
        }

        $thumbnailPath = storage_path('../../filmy/thumbnail/'.$this->filmId.'.png');

        if (file_exists($thumbnailPath)) {
            @unlink($thumbnailPath);
        }

        \FFMpeg::fromDisk('thumbnaill')
            ->open($this->storedPath)
            ->addFilter(function ($filters) {
                $filters->resize(new \FFMpeg\Coordinate\Dimension(1280, 720));
            })
            ->getFrameFromSeconds($second)
            ->export()
            ->toDisk('thumbnail')
            ->save($this->filmId.'.png');

        $image = Image::make($thumbnailPath);
        $image->resize(550, 350);
        $image->save($thumbnailPath);
    }
}
