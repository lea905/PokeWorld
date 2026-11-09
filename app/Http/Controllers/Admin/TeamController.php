<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dresseur;
use App\Models\Pokemon;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('admin.teams.index', [
            'teams' => Team::with(['dresseur', 'pokemons'])->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Team());
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new Team(), 'Équipe créée.');
    }

    public function edit(Team $team): View
    {
        return $this->form($team->load('pokemons'));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        return $this->save($request, $team, 'Équipe mise à jour.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Équipe supprimée.');
    }

    private function form(Team $team): View
    {
        return view('admin.teams.form', [
            'team' => $team,
            'dresseurs' => Dresseur::orderBy('prenom')->get(),
            'pokemons' => Pokemon::without(['typePrincipal', 'typeSecondaire'])
                ->orderBy('numero_pokedex')
                ->get(['id_pokemon', 'nom', 'numero_pokedex']),
        ]);
    }

    private function save(Request $request, Team $team, string $message): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'dresseur_id' => ['nullable', 'exists:dresseur,id'],
            'pokemons' => ['array'],
            'pokemons.*' => ['integer', 'exists:pokemon,id_pokemon'],
        ]);

        $team->fill($data)->save();
        $team->pokemons()->sync($data['pokemons'] ?? []);

        return redirect()->route('admin.teams.index')->with('success', $message);
    }
}
