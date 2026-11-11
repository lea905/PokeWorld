<?php

namespace App\Enums;

/**
 * Grade d'un membre dans une organisation, du plus haut au plus bas.
 * La valeur est celle stockée en base (colonne dresseur.grade).
 */
enum Grade: string
{
    case Boss = 'Boss';
    case Commandant = 'Commandant';
    case Scientifique = 'Scientifique';
    case Sbire = 'Sbire';

    /** Rang hiérarchique : 1 = le plus élevé. */
    public function rang(): int
    {
        return match ($this) {
            self::Boss => 1,
            self::Commandant => 2,
            self::Scientifique => 3,
            self::Sbire => 4,
        };
    }

    /** Valeurs triées par rang, pour les listes déroulantes. */
    public static function ordered(): array
    {
        $cases = self::cases();
        usort($cases, fn (self $a, self $b) => $a->rang() <=> $b->rang());

        return $cases;
    }
}
