<?php

namespace App\Http\Requests\Admin;

class DresseurRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['nullable', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'ville_natale' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'ambition' => ['required', 'string'],
            'description' => ['required', 'string'],
            'est_mechant' => ['boolean'],
            'organisation_id' => ['nullable', 'exists:organisation,id'],
            'grade' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'teams' => ['array'],
            'teams.*.id' => ['nullable', 'integer'],
            'teams.*.nom' => ['required', 'string', 'max:255'],
            'teams.*.pokemons' => ['array'],
            'teams.*.pokemons.*' => ['integer', 'exists:pokemon,id_pokemon'],
        ];
    }

    public function attributes(): array
    {
        return [
            'prenom' => 'prénom',
            'ville_natale' => 'ville natale',
            'region' => 'région',
            'est_mechant' => 'antagoniste',
            'teams.*.nom' => "nom de l'équipe",
        ];
    }

    /** Champs du dresseur lui-même (sans l'image ni les équipes, traitées à part). */
    public function dresseurData(): array
    {
        return [
            ...collect($this->validated())->except(['image', 'teams'])->all(),
            'est_mechant' => $this->boolean('est_mechant'),
        ];
    }

    /** @return array<int, array{id?: int, nom: string, pokemons?: array<int>}> */
    public function teams(): array
    {
        return $this->validated('teams', []);
    }
}
