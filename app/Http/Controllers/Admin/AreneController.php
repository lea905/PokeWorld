<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AreneRequest;
use App\Models\Arene;
use App\Models\Dresseur;
use Illuminate\Http\RedirectResponse;
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
        return $this->form(new Arene);
    }

    public function store(AreneRequest $request): RedirectResponse
    {
        $this->save($request, new Arene);

        return redirect()->route('admin.arenes.index')->with('success', 'Arène créée.');
    }

    public function edit(Arene $arene): View
    {
        return $this->form($arene);
    }

    public function update(AreneRequest $request, Arene $arene): RedirectResponse
    {
        $this->save($request, $arene);

        return redirect()->route('admin.arenes.index')->with('success', 'Arène mise à jour.');
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

    private function save(AreneRequest $request, Arene $arene): void
    {
        $arene->fill($request->safe()->except('badge'));
        $arene->badge = $this->handleImage($request, 'badge', 'images/Badges', $arene->badge);
        $arene->save();
    }
}
