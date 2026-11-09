<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PokedexController extends Controller
{
    /** Nombre de Pokémon affichés par page. */
    private const PER_PAGE = 48;

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
        ])->paginate(self::PER_PAGE)->withQueryString();

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

    /** Fiche d'un Pokémon, trouvée par son numéro de Pokédex (route model binding). */
    public function show(Pokemon $pokemon): View
    {
        return view('pokedex.show', ['pokemon' => $pokemon]);
    }

    /** Compatibilité avec les anciennes URL par nom. */
    public function redirectFromName(string $nom): RedirectResponse
    {
        $pokemon = Pokemon::where('nom', $nom)->firstOrFail(); // 404 si introuvable

        return redirect()->route('pokedex.show', $pokemon, 301);
    }
}
