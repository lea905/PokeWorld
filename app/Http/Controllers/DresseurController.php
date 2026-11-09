<?php

namespace App\Http\Controllers;

use App\Models\Dresseur;
use Illuminate\View\View;

class DresseurController extends Controller
{
    public function index(): View
    {
        return view('dresseur.index', [
            'dresseurs' => Dresseur::with('arene')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $dresseur = Dresseur::with(['teams.pokemons', 'arene', 'organisation'])
            ->where('slug', $slug)
            ->first();

        abort_unless($dresseur, 404, 'Dresseur non trouvé');

        return view('dresseur.show', ['dresseur' => $dresseur]);
    }
}
