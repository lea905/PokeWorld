<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'dresseurs' => Dresseur::count(),
                'teams' => Team::count(),
                'pokemons' => Pokemon::count(),
                'arenes' => Arene::count(),
                'organisations' => Organisation::count(),
            ],
        ]);
    }
}
