<?php

namespace App\Http\Controllers;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /** GET /recherche?q=terme */
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $empty = collect();

        return view('search.index', [
            'query' => $query,
            'pokemons' => $query !== '' ? Pokemon::search($query)->get() : $empty,
            'dresseurs' => $query !== '' ? Dresseur::search($query)->get() : $empty,
            'arenes' => $query !== '' ? Arene::search($query)->get() : $empty,
        ]);
    }
}
