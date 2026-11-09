<?php

namespace App\Http\Requests\Admin;

class PokemonRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'numero_pokedex' => ['required', 'integer', 'min:1'],
            'type1' => ['required', 'exists:type,id'],
            'type2' => ['nullable', 'exists:type,id', 'different:type1'],
            'image_principale' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'numero_pokedex' => 'numéro de Pokédex',
            'type1' => 'type 1',
            'type2' => 'type 2',
            'image_principale' => 'image',
        ];
    }
}
