<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PokemonRequest;
use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Les Pokémon sont importés depuis la PokéAPI : l'admin permet de les consulter et de les corriger.
 */
class PokemonController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('q'));

        return view('admin.pokemons.index', [
            'search' => $search,
            'pokemons' => Pokemon::when($search !== '', fn ($q) => $q->where('nom', 'like', "%{$search}%"))
                ->orderBy('numero_pokedex')
                ->paginate(30)
                ->withQueryString(),
        ]);
    }

    public function edit(Pokemon $pokemon): View
    {
        return view('admin.pokemons.form', [
            'pokemon' => $pokemon,
            'types' => Type::orderBy('nom')->get(),
        ]);
    }

    public function update(PokemonRequest $request, Pokemon $pokemon): RedirectResponse
    {
        $pokemon->update($request->validated());

        return redirect()->route('admin.pokemons.index')->with('success', 'Pokémon mis à jour.');
    }
}
