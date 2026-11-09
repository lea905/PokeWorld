<?php

namespace App\Http\Controllers;

use App\Models\Dresseur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DresseurController extends Controller
{
    /** Camps proposés en filtre sur la liste des dresseurs. */
    private const CAMPS = ['heros', 'antagonistes'];

    public function index(Request $request): View
    {
        $camp = in_array($request->query('camp'), self::CAMPS, true) ? $request->query('camp') : null;

        return view('dresseur.index', [
            'dresseurs' => Dresseur::with('arene')->camp($camp)->orderBy('prenom')->get(),
            'camp' => $camp,
        ]);
    }

    public function show(string $slug): View
    {
        $dresseur = Dresseur::with(['teams.pokemons', 'arene', 'organisation'])
            ->where('slug', $slug)
            ->firstOrFail(); // 404 si introuvable

        return view('dresseur.show', ['dresseur' => $dresseur]);
    }
}
