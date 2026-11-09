<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $table = 'team';

    public $timestamps = false;

    protected $fillable = ['nom', 'dresseur_id'];

    /** @return BelongsTo<Dresseur, $this> */
    public function dresseur(): BelongsTo
    {
        return $this->belongsTo(Dresseur::class, 'dresseur_id');
    }

    /** @return BelongsToMany<Pokemon, $this> */
    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class, 'team_pokemon', 'team_id', 'pokemon_id');
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Team';
    }
}
