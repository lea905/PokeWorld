<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class AreneRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'lieu' => ['required', 'string', 'max:255'],
            // Un dresseur ne peut être champion que d'une seule arène
            'idChampion' => [
                'nullable',
                'exists:dresseur,id',
                Rule::unique('arene', 'idChampion')->ignore($this->route('arene')),
            ],
            'badge' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return ['idChampion.unique' => "Ce dresseur est déjà champion d'une autre arène."];
    }

    public function attributes(): array
    {
        return [
            'idChampion' => 'champion',
            'region' => 'région',
        ];
    }
}
