<?php

namespace App\Http\Controllers;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('index', [
            // Un Pokémon et une Arène tirés au sort une fois par jour
            'pokemonDuJour' => $this->tirageDuJour(Pokemon::class),
            'areneDuJour' => $this->tirageDuJour(Arene::class),
            // Les 3 derniers ajouts
            'pokemons' => Pokemon::latest('id_pokemon')->take(3)->get(),
            'dresseurs' => Dresseur::latest('id')->take(3)->get(),
        ]);
    }

    /**
     * Sélectionne un enregistrement stable pour la journée en cours :
     * le décalage est dérivé de la date, il ne change donc qu'à minuit.
     *
     * @param  class-string<Model>  $modele
     */
    private function tirageDuJour(string $modele): ?Model
    {
        $total = $modele::count();

        if ($total === 0) {
            return null;
        }

        $decalage = crc32($modele.today()->toDateString()) % $total;

        return $modele::query()
            ->orderBy((new $modele)->getKeyName())
            ->skip($decalage)
            ->first();
    }
}
