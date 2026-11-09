<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DresseurController extends Controller
{
    use HandlesImageUpload;

    public function index(): View
    {
        return view('admin.dresseurs.index', [
            'dresseurs' => Dresseur::with('organisation')->withCount('teams')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Dresseur(['est_mechant' => false]));
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new Dresseur(), 'Dresseur créé.');
    }

    public function edit(Dresseur $dresseur): View
    {
        return $this->form($dresseur->load('teams.pokemons'));
    }

    public function update(Request $request, Dresseur $dresseur): RedirectResponse
    {
        return $this->save($request, $dresseur, 'Dresseur mis à jour.');
    }

    public function destroy(Dresseur $dresseur): RedirectResponse
    {
        $dresseur->delete();

        return redirect()->route('admin.dresseurs.index')->with('success', 'Dresseur supprimé.');
    }

    private function form(Dresseur $dresseur): View
    {
        return view('admin.dresseurs.form', [
            'dresseur' => $dresseur,
            'organisations' => Organisation::orderBy('nom')->get(),
            'pokemons' => Pokemon::without(['typePrincipal', 'typeSecondaire'])
                ->orderBy('numero_pokedex')
                ->get(['id_pokemon', 'nom', 'numero_pokedex']),
        ]);
    }

    private function save(Request $request, Dresseur $dresseur, string $message): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['nullable', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'ville_natale' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'ambition' => ['required', 'string'],
            'description' => ['required', 'string'],
            'organisation_id' => ['nullable', 'exists:organisation,id'],
            'grade' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'teams' => ['array'],
            'teams.*.id' => ['nullable', 'integer'],
            'teams.*.nom' => ['required', 'string', 'max:255'],
            'teams.*.pokemons' => ['array'],
            'teams.*.pokemons.*' => ['integer', 'exists:pokemon,id_pokemon'],
        ], [], [
            'prenom' => 'prénom',
            'ville_natale' => 'ville natale',
            'region' => 'région',
            'teams.*.nom' => "nom de l'équipe",
        ]);

        DB::transaction(function () use ($request, $dresseur, $data) {
            $dresseur->fill(collect($data)->except(['image', 'teams'])->all());
            $dresseur->est_mechant = $request->boolean('est_mechant');
            $dresseur->image = $this->handleImage($request, 'image', 'images', $dresseur->image);
            $dresseur->save();

            $this->syncTeams($dresseur, $data['teams'] ?? []);
        });

        return redirect()->route('admin.dresseurs.index')->with('success', $message);
    }

    /** Crée / met à jour les équipes envoyées et supprime celles retirées du formulaire. */
    private function syncTeams(Dresseur $dresseur, array $teams): void
    {
        $keptIds = [];

        foreach ($teams as $teamData) {
            $team = isset($teamData['id'])
                ? $dresseur->teams()->findOrFail($teamData['id'])
                : $dresseur->teams()->make();

            $team->nom = $teamData['nom'];
            $team->save();
            $team->pokemons()->sync($teamData['pokemons'] ?? []);

            $keptIds[] = $team->id;
        }

        $dresseur->teams()->whereNotIn('id', $keptIds)->delete();
    }
}
