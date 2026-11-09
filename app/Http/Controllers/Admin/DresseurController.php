<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DresseurRequest;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use Illuminate\Http\RedirectResponse;
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

    public function store(DresseurRequest $request): RedirectResponse
    {
        $this->save($request, new Dresseur);

        return redirect()->route('admin.dresseurs.index')->with('success', 'Dresseur créé.');
    }

    public function edit(Dresseur $dresseur): View
    {
        return $this->form($dresseur->load('teams.pokemons'));
    }

    public function update(DresseurRequest $request, Dresseur $dresseur): RedirectResponse
    {
        $this->save($request, $dresseur);

        return redirect()->route('admin.dresseurs.index')->with('success', 'Dresseur mis à jour.');
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
            'pokemons' => Pokemon::forSelect()->get(),
        ]);
    }

    private function save(DresseurRequest $request, Dresseur $dresseur): void
    {
        DB::transaction(function () use ($request, $dresseur) {
            $dresseur->fill($request->dresseurData());
            $dresseur->image = $this->handleImage($request, 'image', 'images', $dresseur->image);
            $dresseur->save();

            $dresseur->syncTeams($request->teams());
        });
    }
}
