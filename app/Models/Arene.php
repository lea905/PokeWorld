<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Arene extends Model
{
    use HasSlug;

    protected $table = 'arene';

    public $timestamps = false;

    protected $fillable = ['slug', 'nom', 'region', 'lieu', 'badge', 'idChampion'];

    /** @return BelongsTo<Dresseur, $this> */
    public function champion(): BelongsTo
    {
        return $this->belongsTo(Dresseur::class, 'idChampion');
    }

    /** Vrai si le champ "badge" contient un nom de fichier image (public/images/Badges). */
    /** @return Attribute<bool, never> */
    protected function badgeEstImage(): Attribute
    {
        return Attribute::get(fn () => $this->badge && Str::contains($this->badge, ['.webp', '.png', '.jpg']));
    }

    /** "roche.webp" => "Roche". */
    /** @return Attribute<string|null, never> */
    protected function badgeLibelle(): Attribute
    {
        return Attribute::get(fn () => $this->badge ? Str::ucfirst(Str::lower(Str::before($this->badge, '.'))) : null);
    }

    protected function slugSource(): string
    {
        return (string) $this->nom;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where(fn ($q) => $q->where('nom', 'like', "%{$term}%")
                ->orWhere('region', 'like', "%{$term}%")
                ->orWhere('lieu', 'like', "%{$term}%"))
            ->orderBy('nom');
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
