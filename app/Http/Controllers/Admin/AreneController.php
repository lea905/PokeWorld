<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Arene;
use App\Models\Dresseur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AreneController extends Controller
{
    use HandlesImageUpload;

    public function index(): View
    {
        return view('admin.arenes.index', [
            'arenes' => Arene::with('champion')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Arene());
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new Arene(), 'Arène créée.');
    }

    public function edit(Arene $arene): View
    {
        return $this->form($arene);
    }

    public function update(Request $request, Arene $arene): RedirectResponse
    {
        return $this->save($request, $arene, 'Arène mise à jour.');
    }

    public function destroy(Arene $arene): RedirectResponse
    {
        $arene->delete();

        return redirect()->route('admin.arenes.index')->with('success', 'Arène supprimée.');
    }

    private function form(Arene $arene): View
    {
        return view('admin.arenes.form', [
            'arene' => $arene,
            'dresseurs' => Dresseur::orderBy('prenom')->get(),
        ]);
    }

    private function save(Request $request, Arene $arene, string $message): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'lieu' => ['required', 'string', 'max:255'],
            // Un dresseur ne peut être champion que d'une seule arène
            'idChampion' => ['nullable', 'exists:dresseur,id', Rule::unique('arene', 'idChampion')->ignore($arene->id)],
            'badge' => ['nullable', 'image', 'max:4096'],
        ], [
            'idChampion.unique' => 'Ce dresseur est déjà champion d\'une autre arène.',
        ], [
            'idChampion' => 'champion',
            'region' => 'région',
        ]);

        $arene->fill(collect($data)->except('badge')->all());
        $arene->badge = $this->handleImage($request, 'badge', 'images/Badges', $arene->badge);
        $arene->save();

        return redirect()->route('admin.arenes.index')->with('success', $message);
    }
}
