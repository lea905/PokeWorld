<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    use HandlesImageUpload;

    public function index(): View
    {
        return view('admin.organisations.index', [
            'organisations' => Organisation::withCount('dresseurs')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.organisations.form', ['organisation' => new Organisation()]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new Organisation(), 'Organisation créée.');
    }

    public function edit(Organisation $organisation): View
    {
        return view('admin.organisations.form', ['organisation' => $organisation]);
    }

    public function update(Request $request, Organisation $organisation): RedirectResponse
    {
        return $this->save($request, $organisation, 'Organisation mise à jour.');
    }

    public function destroy(Organisation $organisation): RedirectResponse
    {
        $organisation->delete();

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation supprimée.');
    }

    private function save(Request $request, Organisation $organisation, string $message): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'but' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $organisation->fill(collect($data)->except('image')->all());
        $organisation->image = $this->handleImage($request, 'image', 'images', $organisation->image);
        $organisation->save();

        return redirect()->route('admin.organisations.index')->with('success', $message);
    }
}
