<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Calcule automatiquement le slug à l'enregistrement s'il est vide
 * (équivalent des callbacks PrePersist/PreUpdate de Doctrine).
 */
trait HasSlug
{
    abstract protected function slugSource(): string;

    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::makeSlug($model->slugSource());
            }
        });
    }

    public static function makeSlug(string $value): string
    {
        // Les apostrophes deviennent des séparateurs : "Arène d'Argenta" => "arene-d-argenta"
        return Str::slug(str_replace(["'", "\u{2019}"], ' ', trim($value)), '-', 'fr');
    }
}
