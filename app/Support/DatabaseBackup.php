<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

/**
 * Kopia zapasowa bazy danych: jeden plik .zip, a w nim jeden plik .sql w katalogu głównym archiwum
 * (bez zagnieżdżonych folderów). Zapisuje się w storage/app/db-backup.
 *
 * Limit: maksymalnie MAX_BACKUPS kopii - po utworzeniu nowej najstarsze są usuwane.
 * Używane zarówno przez przycisk w panelu admina, jak i automatycznie po dodaniu filmu.
 */
class DatabaseBackup
{
    const MAX_BACKUPS = 20;

    public static function directory()
    {
        return storage_path('app' . DIRECTORY_SEPARATOR . 'db-backup');
    }

    /**
     * @return array ['name' => 'db_backup_....zip', 'path' => '/pełna/ścieżka']
     * @throws \Exception
     */
    public static function create()
    {
        if (!class_exists(\ZipArchive::class)) {
            throw new \Exception('Brak rozszerzenia PHP "zip" (włącz extension=zip w php.ini).');
        }

        $dir = self::directory();
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new \Exception('Nie można utworzyć katalogu kopii: ' . $dir);
        }

        // jedna kopia naraz (np. dwa filmy kończące się w tej samej chwili)
        $lock = fopen($dir . DIRECTORY_SEPARATOR . '.lock', 'c');
        if ($lock) {
            flock($lock, LOCK_EX);
        }

        try {
            $base = 'db_backup_' . date('Y-m-d_H-i-s');
            $i = 1;
            $unique = $base;
            while (file_exists($dir . DIRECTORY_SEPARATOR . $unique . '.zip')) {
                $unique = $base . '_' . $i++;
            }

            $sqlPath = $dir . DIRECTORY_SEPARATOR . $unique . '.sql';
            $zipPath = $dir . DIRECTORY_SEPARATOR . $unique . '.zip';

            try {
                if (!self::dumpWithMysqldump($sqlPath)) {
                    self::dumpWithPhp($sqlPath); // zapasowo, gdy nie ma mysqldump
                }

                $zip = new \ZipArchive();
                if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                    throw new \Exception('Nie można utworzyć pliku zip: ' . $zipPath);
                }
                // drugi argument = nazwa w archiwum: sam plik, bez żadnych folderów
                $zip->addFile($sqlPath, $unique . '.sql');
                $zip->close();
            } finally {
                if (file_exists($sqlPath)) {
                    @unlink($sqlPath);
                }
            }

            if (!file_exists($zipPath) || filesize($zipPath) === 0) {
                throw new \Exception('Plik kopii nie został utworzony.');
            }

            $name = $unique . '.zip';
            DB::table('db_copy_files')->insert([
                'name' => $name,
                'url' => '../storage/app/db-backup/' . $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            self::prune();

            return ['name' => $name, 'path' => $zipPath];
        } finally {
            if ($lock) {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    /** Zostawia MAX_BACKUPS najnowszych kopii, starsze kasuje (plik + wpis w bazie). */
    public static function prune($max = self::MAX_BACKUPS)
    {
        $files = glob(self::directory() . DIRECTORY_SEPARATOR . '*.zip') ?: [];

        usort($files, function ($a, $b) {
            $d = filemtime($a) <=> filemtime($b);
            return $d !== 0 ? $d : strcmp($a, $b);
        });

        $excess = count($files) - $max;
        for ($i = 0; $i < $excess; $i++) {
            $name = basename($files[$i]);
            @unlink($files[$i]);
            DB::table('db_copy_files')->where('name', $name)->delete();
        }
    }

    // ------------------------------------------------------------------ zrzut przez mysqldump

    private static function dumpWithMysqldump($sqlPath)
    {
        $cfg = config('database.connections.' . config('database.default'));
        if (!is_array($cfg) || ($cfg['driver'] ?? '') !== 'mysql') {
            return false;
        }

        $binDir = $cfg['dump']['dump_binary_path'] ?? '';
        $exe = 'mysqldump' . (DIRECTORY_SEPARATOR === '\\' ? '.exe' : '');
        $binary = $binDir !== '' && file_exists(rtrim($binDir, '\\/') . DIRECTORY_SEPARATOR . $exe)
            ? rtrim($binDir, '\\/') . DIRECTORY_SEPARATOR . $exe
            : 'mysqldump'; // z PATH

        $cmd = [
            $binary,
            '--host=' . $cfg['host'],
            '--port=' . $cfg['port'],
            '--user=' . $cfg['username'],
            '--single-transaction',
            '--routines',
            '--default-character-set=utf8mb4',
            '--result-file=' . $sqlPath,
            $cfg['database'],
        ];

        $env = [];
        if (!empty($cfg['password'])) {
            $env['MYSQL_PWD'] = $cfg['password']; // nie podajemy hasła w linii poleceń
        }

        try {
            $process = new Process($cmd, null, $env ?: null);
            $process->setTimeout((int) ($cfg['dump']['timeout'] ?? 300));
            $process->run();

            return $process->isSuccessful() && file_exists($sqlPath) && filesize($sqlPath) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    // ------------------------------------------------------------------ zrzut w czystym PHP (zapasowy)

    private static function dumpWithPhp($sqlPath)
    {
        $pdo = DB::connection()->getPdo();
        $out = fopen($sqlPath, 'w');
        if (!$out) {
            throw new \Exception('Nie można zapisać pliku: ' . $sqlPath);
        }

        fwrite($out, "-- Kopia bazy " . date('Y-m-d H:i:s') . "\nSET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")->fetchAll(\PDO::FETCH_NUM);

        foreach ($tables as $row) {
            $table = $row[0];
            $q = '`' . str_replace('`', '``', $table) . '`';

            $create = $pdo->query('SHOW CREATE TABLE ' . $q)->fetch(\PDO::FETCH_NUM);
            fwrite($out, "DROP TABLE IF EXISTS $q;\n" . $create[1] . ";\n\n");

            $stmt = $pdo->query('SELECT * FROM ' . $q);
            $batch = [];
            while ($r = $stmt->fetch(\PDO::FETCH_NUM)) {
                $vals = array_map(function ($v) use ($pdo) {
                    return $v === null ? 'NULL' : $pdo->quote($v);
                }, $r);
                $batch[] = '(' . implode(',', $vals) . ')';

                if (count($batch) >= 100) {
                    fwrite($out, "INSERT INTO $q VALUES " . implode(",\n", $batch) . ";\n");
                    $batch = [];
                }
            }
            if ($batch) {
                fwrite($out, "INSERT INTO $q VALUES " . implode(",\n", $batch) . ";\n");
            }
            fwrite($out, "\n");
        }

        fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($out);
    }
}
