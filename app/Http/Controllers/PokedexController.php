<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PokedexController extends Controller
{
    public function index(Request $request, ?int $generation = null): View
    {
        $type = $request->query('type');
        $isLegendary = $request->boolean('is_legendary');
        $isMythical = $request->boolean('is_mythical');
        $evolutionStage = $request->query('evolution_stage');

        $pokemons = Pokemon::filter([
            'type' => $type,
            'generation' => $generation,
            'is_legendary' => $isLegendary,
            'is_mythical' => $isMythical,
            'evolution_stage' => $evolutionStage,
        ])->get();

        return view('pokedex.index', [
            'pokemons' => $pokemons,
            'allTypes' => Type::valid()->get(),
            'currentType' => $type,
            'currentGeneration' => $generation,
            'isLegendary' => $isLegendary,
            'isMythical' => $isMythical,
            'evolutionStage' => $evolutionStage,
        ]);
    }

    public function show(string $nom): View
    {
        $pokemon = Pokemon::where('nom', $nom)->first();

        abort_unless($pokemon, 404, "Ce Pokémon n'existe pas.");

        return view('pokedex.show', ['pokemon' => $pokemon]);
    }
}
