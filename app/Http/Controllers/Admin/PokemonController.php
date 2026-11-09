<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $search = trim((string) $request->query('q', ''));

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

    public function update(Request $request, Pokemon $pokemon): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'numero_pokedex' => ['required', 'integer', 'min:1'],
            'type1' => ['required', 'exists:type,id'],
            'type2' => ['nullable', 'exists:type,id', 'different:type1'],
            'image_principale' => ['required', 'string', 'max:255'],
        ], [], [
            'numero_pokedex' => 'numéro de Pokédex',
            'type1' => 'type 1',
            'type2' => 'type 2',
            'image_principale' => 'image',
        ]);

        $pokemon->update($data);

        return redirect()->route('admin.pokemons.index')->with('success', 'Pokémon mis à jour.');
    }
}
