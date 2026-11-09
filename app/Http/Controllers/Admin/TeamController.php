<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamRequest;
use App\Models\Dresseur;
use App\Models\Pokemon;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
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
        return $this->form(new Team);
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        $this->save($request, new Team);

        return redirect()->route('admin.teams.index')->with('success', 'Équipe créée.');
    }

    public function edit(Team $team): View
    {
        return $this->form($team->load('pokemons'));
    }

    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $this->save($request, $team);

        return redirect()->route('admin.teams.index')->with('success', 'Équipe mise à jour.');
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
            'pokemons' => Pokemon::forSelect()->get(),
        ]);
    }

    private function save(TeamRequest $request, Team $team): void
    {
        $team->fill($request->safe()->except('pokemons'))->save();
        $team->pokemons()->sync($request->validated('pokemons', []));
    }
}
