<?php

namespace Database\Seeders;

use App\Enums\Grade;
use App\Models\Dresseur;
use App\Models\Organisation;
use Illuminate\Database\Seeder;

class OrganisationSeeder extends Seeder
{
    public function run(): void
    {
        $teamRocket = Organisation::firstOrCreate(['nom' => 'Team Rocket'], [
            'but' => 'Voler les Pokémon rares et puissants pour dominer le monde.',
            'image' => 'team-rocket.png',
        ]);

        $membres = [
            [
                'prenom' => 'Giovanni',
                'nom' => '', // Giovanni n'a qu'un prénom connu
                'ville_natale' => 'Jadielle',
                'region' => 'Kanto',
                'ambition' => 'Créer le Pokémon le plus puissant (Mewtwo) et diriger le monde.',
                'description' => "Le Boss redoutable de la Team Rocket et Champion d'arène.",
                'grade' => Grade::Boss,
            ],
            [
                'prenom' => 'Amos',
                'nom' => '',
                'ville_natale' => 'Inconnue',
                'region' => 'Johto',
                'ambition' => 'Faire revenir Giovanni et restaurer la gloire de la Team Rocket.',
                'description' => "L'un des Commandants les plus fidèles de la Team Rocket.",
                'grade' => Grade::Commandant,
            ],
            [
                'prenom' => 'Sbire H',
                'nom' => 'Rocket',
                'ville_natale' => 'Inconnue',
                'region' => 'Kanto',
                'ambition' => 'Servir Giovanni et voler des Pokémon.',
                'description' => 'Un sbire classique de la Team Rocket, prêt à tout.',
                'grade' => Grade::Sbire,
            ],
        ];

        foreach ($membres as $membre) {
            $dresseur = Dresseur::firstOrNew(['prenom' => $membre['prenom']]);

            // Un membre déjà présent (éventuellement modifié dans l'admin) n'est pas écrasé
            if (! $dresseur->exists) {
                $dresseur->fill($membre + ['est_mechant' => true, 'organisation_id' => $teamRocket->id]);
                $dresseur->save();
            }
        }
    }
}
