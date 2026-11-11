<?php

namespace Database\Seeders;

use App\Enums\Grade;
use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use Illuminate\Database\Seeder;

/**
 * Crée les antagonistes manquants : arène de Jadielle et autres organisations avec leur boss.
 * Les textes et les équipes vérifiés sur Poképédia sont appliqués ensuite par PokepediaSeeder.
 *
 * Ne fait qu'ajouter ce qui manque : il peut être relancé sans risque sur une base
 * existante (php artisan db:seed --class=MechantsSeeder).
 */
class MechantsSeeder extends Seeder
{
    /** Organisations et prénom de leur boss (fiche complétée par PokepediaSeeder). */
    private const ORGANISATIONS = [
        'Team Aqua' => 'Arthur',
        'Team Magma' => 'Max',
        'Team Galaxie' => 'Hélio',
    ];

    public function run(): void
    {
        $donnees = require database_path('data/pokepedia.php');
        $organisations = require database_path('data/organisations.php');

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

        // Autres organisations et leur boss
        foreach (self::ORGANISATIONS as $nom => $prenomBoss) {
            $organisation = Organisation::firstOrCreate(['nom' => $nom], ['but' => $organisations[$nom]['but']]);

            Dresseur::firstOrCreate(['prenom' => $prenomBoss], $donnees[$prenomBoss]['infos'] + [
                'nom' => '',
                'est_mechant' => true,
                'grade' => Grade::Boss,
                'organisation_id' => $organisation->id,
            ]);
        }
    }
}
