<?php

namespace App\Http\Controllers;

use App\Support\FilmSort;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contexts = FilmSort::contexts();
        $globalOptions = FilmSort::labels();
        $mode = FilmSort::mode();
        $globalKey = FilmSort::globalKey();

        // dla każdej podstrony: lista opcji (wyszukiwarka ma dodatkowo "trafność") i aktualny wybór
        $perContext = [];
        foreach ($contexts as $ctx => $name) {
            $perContext[$ctx] = [
                'name' => $name,
                'options' => FilmSort::labels($ctx),
                'current' => FilmSort::contextKey($ctx),
            ];
        }

        return view('admin.admin_settings', compact('mode', 'globalKey', 'globalOptions', 'perContext'));
    }

    public function save(Request $request)
    {
        $mode = $request->input('film_sort_mode') === 'separate' ? 'separate' : 'all';
        $global = $request->input('default_film_sort');

        if (!FilmSort::isValid($global)) {
            return redirect()->back()->with('msg_errors', 'Wybrano niepoprawny sposób sortowania.');
        }

        FilmSort::saveSetting('film_sort_mode', $mode);
        FilmSort::saveSetting(FilmSort::SETTING_KEY, $global);

        // w trybie "jednakowo" pola podstron są wyłączone (przeglądarka ich nie wysyła),
        // więc nie ruszamy zapisanych wartości - wrócą po przełączeniu na "osobno"
        if ($mode === 'separate') {
            foreach (array_keys(FilmSort::contexts()) as $ctx) {
                $value = (string) $request->input('film_sort_' . $ctx, '');
                // pusta wartość = ta podstrona używa ustawienia ogólnego
                FilmSort::saveSetting('film_sort_' . $ctx, FilmSort::isValid($value, $ctx) ? $value : '');
            }
        }

        return redirect('/admin_settings')->with('msg_success', 'Ustawienia zostały zapisane.');
    }
}
