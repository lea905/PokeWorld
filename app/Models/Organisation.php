<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
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

    protected function slugSource(): string
    {
        return (string) $this->nom;
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
