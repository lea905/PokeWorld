<?php

namespace App\Enums;

/**
 * Jeux (et dessin animé) dans lesquels une équipe apparaît.
 * Les cas sont déclarés dans l'ordre de sortie : c'est l'ordre d'affichage des onglets.
 * La valeur est celle stockée en base (colonne team.jeux, liste JSON).
 */
enum Jeu: string
{
    case RougeBleu = 'rouge-bleu';
    case Jaune = 'jaune';
    case OrArgentCristal = 'or-argent-cristal';
    case Rubis = 'rubis';
    case Saphir = 'saphir';
    case RougeFeuVertFeuille = 'rouge-feu-vert-feuille';
    case Emeraude = 'emeraude';
    case DiamantPerle = 'diamant-perle';
    case Platine = 'platine';
    case HeartGoldSoulSilver = 'heartgold-soulsilver';
    case RubisOmega = 'rubis-omega';
    case SaphirAlpha = 'saphir-alpha';
    case UltraSoleil = 'ultra-soleil';
    case UltraLune = 'ultra-lune';
    case LetsGo = 'lets-go';
    case DiamantEtincelantPerleScintillante = 'diamant-etincelant-perle-scintillante';
    case DessinAnime = 'dessin-anime';

    /** Nom affiché, tel qu'écrit sur Poképédia. */
    public function label(): string
    {
        return match ($this) {
            self::RougeBleu => 'Rouge et Bleu',
            self::Jaune => 'Jaune',
            self::OrArgentCristal => 'Or, Argent et Cristal',
            self::Rubis => 'Rubis',
            self::Saphir => 'Saphir',
            self::RougeFeuVertFeuille => 'Rouge Feu et Vert Feuille',
            self::Emeraude => 'Émeraude',
            self::DiamantPerle => 'Diamant et Perle',
            self::Platine => 'Platine',
            self::HeartGoldSoulSilver => 'Or HeartGold et Argent SoulSilver',
            self::RubisOmega => 'Rubis Oméga',
            self::SaphirAlpha => 'Saphir Alpha',
            self::UltraSoleil => 'Ultra-Soleil',
            self::UltraLune => 'Ultra-Lune',
            self::LetsGo => "Let's Go, Pikachu et Let's Go, Évoli",
            self::DiamantEtincelantPerleScintillante => 'Diamant Étincelant et Perle Scintillante',
            self::DessinAnime => 'Dessin animé',
        };
    }

    /** Position dans l'ordre de sortie (0 = le plus ancien). */
    public function ordre(): int
    {
        return array_search($this, self::cases(), true);
    }
}
