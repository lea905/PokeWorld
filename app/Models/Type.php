<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Type extends Model
{
    protected $table = 'type';

    public $timestamps = false;

    protected $fillable = ['nom'];

    /** @return HasMany<TypeRelation, $this> */
    public function relationsEnAttaque(): HasMany
    {
        return $this->hasMany(TypeRelation::class, 'idTypeAttaquant');
    }

    /** @return HasMany<TypeRelation, $this> */
    public function relationsEnDefense(): HasMany
    {
        return $this->hasMany(TypeRelation::class, 'idTypeDefenseur');
    }

    /** Types valides (sans le type "???"), triés par nom. */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('nom', '!=', '???')->orderBy('nom');
    }

    /** Suffixe CSS des classes text-bg-* / bg-type-* ("Ténèbres" => "tenebres"). */
    /** @return Attribute<lowercase-string, never> */
    protected function css(): Attribute
    {
        return Attribute::get(fn () => Str::lower(Str::ascii((string) $this->nom)));
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
