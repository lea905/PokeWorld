<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dresseur extends Model
{
    use HasSlug;

    protected $table = 'dresseur';

    public $timestamps = false;

    protected $fillable = [
        'slug', 'nom', 'prenom', 'ville_natale', 'region', 'ambition',
        'description', 'image', 'est_mechant', 'organisation_id', 'grade',
    ];

    protected $casts = ['est_mechant' => 'boolean'];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'dresseur_id');
    }

    public function arene(): HasOne
    {
        return $this->hasOne(Arene::class, 'idChampion');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    /**
     * Crée / met à jour les équipes données et supprime celles qui n'y figurent plus
     * (équivalent de l'orphanRemoval Doctrine).
     *
     * @param  array<int, array{id?: int|null, nom: string, pokemons?: array<int>}>  $teams
     */
    public function syncTeams(array $teams): void
    {
        $keptIds = [];

        foreach ($teams as $teamData) {
            $team = ! empty($teamData['id'])
                ? $this->teams()->findOrFail($teamData['id'])
                : $this->teams()->make();

            $team->nom = $teamData['nom'];
            $team->save();
            $team->pokemons()->sync($teamData['pokemons'] ?? []);

            $keptIds[] = $team->id;
        }

        $this->teams()->whereNotIn('id', $keptIds)->delete();
    }

    /** "Prénom Nom" (équivalent du __toString de l'entité). */
    protected function nomComplet(): Attribute
    {
        return Attribute::get(fn () => trim(($this->prenom ?? '').' '.($this->nom ?? '')));
    }

    protected function slugSource(): string
    {
        return $this->prenom.' '.$this->nom;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where(fn ($q) => $q->where('nom', 'like', "%{$term}%")
                ->orWhere('prenom', 'like', "%{$term}%")
                ->orWhere('ville_natale', 'like', "%{$term}%")
                ->orWhere('region', 'like', "%{$term}%"))
            ->orderBy('nom');
    }

    public function __toString(): string
    {
        return $this->nom_complet;
    }
}
