<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrganisationRequest;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
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

    public function store(OrganisationRequest $request): RedirectResponse
    {
        $this->save($request, new Organisation());

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation créée.');
    }

    public function edit(Organisation $organisation): View
    {
        return view('admin.organisations.form', ['organisation' => $organisation]);
    }

    public function update(OrganisationRequest $request, Organisation $organisation): RedirectResponse
    {
        $this->save($request, $organisation);

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation mise à jour.');
    }

    public function destroy(Organisation $organisation): RedirectResponse
    {
        $organisation->delete();

        return redirect()->route('admin.organisations.index')->with('success', 'Organisation supprimée.');
    }

    private function save(OrganisationRequest $request, Organisation $organisation): void
    {
        $organisation->fill($request->safe()->except('image'));
        $organisation->image = $this->handleImage($request, 'image', 'images', $organisation->image);
        $organisation->save();
    }
}
