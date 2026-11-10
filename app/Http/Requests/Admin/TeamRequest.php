<?php

namespace App\Http\Requests\Admin;

use App\Enums\Jeu;
use Illuminate\Validation\Rule;

class TeamRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'jeux' => ['array'],
            'jeux.*' => ['distinct', Rule::enum(Jeu::class)],
            'dresseur_id' => ['nullable', 'exists:dresseur,id'],
            'pokemons' => ['array'],
            'pokemons.*' => ['integer', 'exists:pokemon,id_pokemon'],
        ];
    }

    public function attributes(): array
    {
        return ['pokemons.*' => 'Pokémon', 'jeux.*' => 'jeu'];
    }
}
