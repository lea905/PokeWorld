<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pokemon extends Model
{
    protected $table = 'pokemon';

    protected $primaryKey = 'id_pokemon';

    public $timestamps = false;

    protected $guarded = ['id_pokemon'];

    /** Les deux types sont toujours chargés (équivalent du fetch EAGER Doctrine). */
    protected $with = ['typePrincipal', 'typeSecondaire'];

    protected $casts = [
        'mega_evolution_possible' => 'boolean',
        'dynamax_possible' => 'boolean',
        'is_legendary' => 'boolean',
        'is_mythical' => 'boolean',
    ];

    /*
     * Les colonnes s'appellent "type1" et "type2" : les relations portent donc
     * un autre nom pour ne pas entrer en conflit avec les attributs.
     */
    /** @return BelongsTo<Type, $this> */
    public function typePrincipal(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type1');
    }

    /** @return BelongsTo<Type, $this> */
    public function typeSecondaire(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type2');
    }

    /** @return BelongsTo<Pokemon, $this> */
    public function evolutionPrecedente(): BelongsTo
    {
        return $this->belongsTo(self::class, 'idEvolutionPrecedente', 'id_pokemon');
    }

    /** @return HasMany<Pokemon, $this> */
    public function evolutionsSuivantes(): HasMany
    {
        return $this->hasMany(self::class, 'idEvolutionPrecedente', 'id_pokemon');
    }

    /** @return BelongsToMany<Team, $this> */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_pokemon', 'pokemon_id', 'team_id');
    }

    /** Premier Pokémon de la famille d'évolution. */
    public function evolutionRoot(): self
    {
        $root = $this;
        while ($root->evolutionPrecedente !== null) {
            $root = $root->evolutionPrecedente;
        }

        return $root;
    }

    /**
     * Filtres du Pokédex : name, generation, type, is_legendary, is_mythical, evolution_stage.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['name'] ?? null, fn ($q, $name) => $q->where('nom', 'like', "%{$name}%"))
            ->when($filters['generation'] ?? null, fn ($q, $gen) => $q->where('generation', $gen))
            ->when($filters['type'] ?? null, function ($q, $type) {
                $q->where(fn ($sub) => $sub
                    ->whereHas('typePrincipal', fn ($t) => $t->where('nom', 'like', "%{$type}%"))
                    ->orWhereHas('typeSecondaire', fn ($t) => $t->where('nom', 'like', "%{$type}%")));
            })
            ->when($filters['is_legendary'] ?? false, fn ($q) => $q->where('is_legendary', true))
            ->when($filters['is_mythical'] ?? false, fn ($q) => $q->where('is_mythical', true))
            ->when(($filters['evolution_stage'] ?? null) === 'base', fn ($q) => $q->whereNull('idEvolutionPrecedente'))
            ->when(($filters['evolution_stage'] ?? null) === 'evolution', fn ($q) => $q->whereNotNull('idEvolutionPrecedente'))
            ->orderBy('generation')
            ->orderBy('numero_pokedex');
    }

    /** Colonnes minimales pour les listes déroulantes de l'admin (sans charger les types). */
    public function scopeForSelect(Builder $query): Builder
    {
        return $query->without(['typePrincipal', 'typeSecondaire'])
            ->orderBy('numero_pokedex')
            ->select(['id_pokemon', 'nom', 'numero_pokedex']);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where(fn ($q) => $q->where('nom', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%"))
            ->orderBy('numero_pokedex');
    }

    public function __toString(): string
    {
        return $this->nom.' (#'.$this->numero_pokedex.')';
    }
}
