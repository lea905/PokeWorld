<?php

namespace App\Http\Controllers;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Pokemon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('index', [
            // Un Pokémon et une Arène au hasard
            'pokemonDuJour' => Pokemon::inRandomOrder()->first(),
            'areneDuJour' => Arene::inRandomOrder()->first(),
            // Les 3 derniers ajouts
            'pokemons' => Pokemon::latest('id_pokemon')->take(3)->get(),
            'dresseurs' => Dresseur::latest('id')->take(3)->get(),
        ]);
    }
}
