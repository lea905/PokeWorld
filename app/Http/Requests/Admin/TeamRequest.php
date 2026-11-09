<?php

namespace App\Http\Requests\Admin;

class TeamRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'dresseur_id' => ['nullable', 'exists:dresseur,id'],
            'pokemons' => ['array'],
            'pokemons.*' => ['integer', 'exists:pokemon,id_pokemon'],
        ];
    }

    public function attributes(): array
    {
        return ['pokemons.*' => 'Pokémon'];
    }
}
