<?php

namespace Database\Seeders;

use App\Enums\Grade;
use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use Illuminate\Database\Seeder;

/**
 * Complète l'univers des antagonistes : équipes de la Team Rocket, arène de Jadielle
 * et autres organisations.
 *
 * Ne fait qu'ajouter ce qui manque : il peut être relancé sans risque sur une base
 * existante (php artisan db:seed --class=MechantsSeeder).
 */
class MechantsSeeder extends Seeder
{
    /** Équipes des membres de la Team Rocket : prénoms possibles, nom de l'équipe, Pokémon. */
    private const EQUIPES_ROCKET = [
        [['Giovanni'], 'Équipe de Giovanni', ['Persian', 'Nidoking', 'Rhinoféros']],
        [['Amos'], 'Équipe d\'Amos', ['Malosse', 'Smogogo', 'Démolosse']],
        [['Sbire H', 'Sbire'], 'Équipe du Sbire', ['Rattata', 'Abo', 'Smogo']],
    ];

    /** Autres organisations, avec leur boss et son équipe. */
    private const ORGANISATIONS = [
        [
            'nom' => 'Team Aqua',
            'but' => 'Étendre les océans pour créer un monde idéal pour les Pokémon aquatiques.',
            'boss' => [
                'prenom' => 'Arthur',
                'region' => 'Hoenn',
                'ville_natale' => 'Inconnue',
                'description' => 'Chef charismatique de la Team Aqua, prêt à réveiller Kyogre pour accomplir son rêve.',
                'ambition' => 'Faire monter les eaux et rendre la mer à tous les Pokémon.',
            ],
            'equipe' => ['Grahyèna', 'Nostenfer', 'Sharpedo'],
        ],
        [
            'nom' => 'Team Magma',
            'but' => 'Étendre les terres émergées pour offrir plus d\'espace à l\'humanité.',
            'boss' => [
                'prenom' => 'Max',
                'region' => 'Hoenn',
                'ville_natale' => 'Inconnue',
                'description' => 'Chef méthodique de la Team Magma, prêt à réveiller Groudon pour assécher les mers.',
                'ambition' => 'Agrandir les continents pour le bien des humains.',
            ],
            'equipe' => ['Grahyèna', 'Nostenfer', 'Camérupt'],
        ],
        [
            'nom' => 'Team Galaxie',
            'but' => 'Détruire le monde actuel pour en recréer un nouveau, sans esprit ni émotions.',
            'boss' => [
                'prenom' => 'Hélio',
                'region' => 'Sinnoh',
                'ville_natale' => 'Voilaroc',
                'description' => 'Chef froid et calculateur de la Team Galaxie, obsédé par Dialga et Palkia.',
                'ambition' => 'Créer un univers parfait, débarrassé de toute émotion.',
            ],
            'equipe' => ['Corboss', 'Dimoret', 'Nostenfer'],
        ],
    ];

    public function run(): void
    {
        // Équipes des membres de la Team Rocket
        foreach (self::EQUIPES_ROCKET as [$prenoms, $nomEquipe, $pokemons]) {
            if ($dresseur = Dresseur::whereIn('prenom', $prenoms)->first()) {
                $this->creerEquipe($dresseur, $nomEquipe, $pokemons);
            }
        }

        // Arène de Jadielle : Giovanni en est le champion
        $giovanni = Dresseur::where('prenom', 'Giovanni')->first();

        if ($giovanni && ! $giovanni->arene && ! Arene::where('nom', 'Arène de Jadielle')->exists()) {
            Arene::create([
                'nom' => 'Arène de Jadielle',
                'region' => 'Kanto',
                'lieu' => 'Jadielle',
                'badge' => 'Terre',
                'idChampion' => $giovanni->id,
            ]);
        }

        // Autres organisations, leur boss et son équipe
        foreach (self::ORGANISATIONS as $donnees) {
            $organisation = Organisation::firstOrCreate(['nom' => $donnees['nom']], ['but' => $donnees['but']]);

            $boss = Dresseur::firstOrCreate(['prenom' => $donnees['boss']['prenom']], $donnees['boss'] + [
                'nom' => '',
                'est_mechant' => true,
                'grade' => Grade::Boss,
                'organisation_id' => $organisation->id,
            ]);

            $this->creerEquipe($boss, 'Équipe de '.$boss->prenom, $donnees['equipe']);
        }
    }

    /** Crée l'équipe si elle n'existe pas encore et que tous ses Pokémon sont en base. */
    private function creerEquipe(Dresseur $dresseur, string $nom, array $nomsPokemon): void
    {
        if ($dresseur->teams()->exists()) {
            return; // le dresseur a déjà une équipe : on n'y touche pas
        }

        $pokemons = Pokemon::whereIn('nom', $nomsPokemon)->pluck('id_pokemon');

        if ($pokemons->count() !== count($nomsPokemon)) {
            return; // Pokémon absents (import partiel de la PokéAPI)
        }

        Team::create(['nom' => $nom, 'dresseur_id' => $dresseur->id])
            ->pokemons()
            ->attach($pokemons);
    }
}
