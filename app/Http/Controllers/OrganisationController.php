<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\View\View;

class OrganisationController extends Controller
{
    public function index(): View
    {
        return view('organisation.index', [
            'organisations' => Organisation::withCount('dresseurs')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $organisation = Organisation::with('dresseurs')->where('slug', $slug)->firstOrFail(); // 404 si introuvable

        return view('organisation.show', ['organisation' => $organisation]);
    }
}
