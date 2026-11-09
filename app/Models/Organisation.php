<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasSlug;

    protected $table = 'organisation';

    public $timestamps = false;

    protected $fillable = ['nom', 'but', 'image', 'slug'];

    /** @return HasMany<Dresseur, $this> */
    public function dresseurs(): HasMany
    {
        return $this->hasMany(Dresseur::class, 'organisation_id');
    }

    /** Recherche par nom ou par but de l'organisation. */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where(fn ($q) => $q->where('nom', 'like', "%{$term}%")->orWhere('but', 'like', "%{$term}%"))
            ->orderBy('nom');
    }

    protected function slugSource(): string
    {
        return (string) $this->nom;
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
