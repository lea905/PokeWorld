<?php

namespace App\Http\Controllers;

use App\Models\Arene;
use Illuminate\View\View;

class AreneController extends Controller
{
    public function index(): View
    {
        return view('arene.index', [
            'arenes' => Arene::with('champion')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $arene = Arene::with('champion.teams.pokemons')->where('slug', $slug)->firstOrFail(); // 404 si introuvable

        return view('arene.show', ['arene' => $arene]);
    }
}
