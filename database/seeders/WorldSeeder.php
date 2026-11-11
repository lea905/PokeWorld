<?php

namespace Database\Seeders;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Map;
use Illuminate\Database\Seeder;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        // --- MAPS ---
        $maps = [
            ['nom' => 'Kanto', 'jeu' => 'Rouge/Bleu/Jaune', 'description' => "La région d'origine, où l'aventure a commencé.", 'image' => 'kanto.png'],
            ['nom' => 'Johto', 'jeu' => 'Or/Argent/Cristal', 'description' => 'Une région riche en mythes et traditions.', 'image' => 'johto.png'],
            ['nom' => 'Hoenn', 'jeu' => 'Rubis/Saphir/Émeraude', 'description' => 'Une région tropicale avec de vastes océans.', 'image' => 'hoenn.png'],
            ['nom' => 'Sinnoh', 'jeu' => 'Diamant/Perle/Platine', 'description' => 'Une région montagneuse abritant le Mont Couronné.', 'image' => 'sinnoh.png'],
        ];

        foreach ($maps as $map) {
            Map::firstOrCreate(['nom' => $map['nom']], $map);
        }

        // --- DRESSEURS (Héros et Champions) ---
        $sacha = Dresseur::firstOrCreate(['prenom' => 'Sacha'], [
            'nom' => 'Ketchum',
            'ville_natale' => 'Bourg Palette',
            'region' => 'Kanto',
            'ambition' => 'Devenir un Maître Pokémon.',
            'description' => "Un jeune dresseur plein d'énergie, toujours accompagné de son Pikachu.",
            'est_mechant' => false,
            'image' => 'sacha.png',
        ]);

        $pierre = Dresseur::firstOrCreate(['prenom' => 'Pierre'], [
            'nom' => '',
            'ville_natale' => 'Argenta',
            'region' => 'Kanto',
            'ambition' => 'Devenir le meilleur Éleveur Pokémon.',
            'description' => 'Un dresseur expert en Pokémon Roche et excellent cuisinier.',
            'est_mechant' => false,
            'image' => 'pierre.png',
        ]);

        $ondine = Dresseur::firstOrCreate(['prenom' => 'Ondine'], [
            'nom' => '',
            'ville_natale' => 'Azuria',
            'region' => 'Kanto',
            'ambition' => 'Devenir la meilleure dresseuse de Pokémon Eau.',
            'description' => 'Spécialiste des Pokémon Eau, avec un fort caractère.',
            'est_mechant' => false,
            'image' => 'ondine.png',
        ]);

        // --- ARÈNES ---
        Arene::firstOrCreate(['nom' => "Arène d'Argenta"], [
            'region' => 'Kanto',
            'lieu' => 'Argenta',
            'badge' => 'roche.webp',
            'idChampion' => $pierre->id,
        ]);

        Arene::firstOrCreate(['nom' => "Arène d'Azuria"], [
            'region' => 'Kanto',
            'lieu' => 'Azuria',
            'badge' => 'cascade.webp',
            'idChampion' => $ondine->id,
        ]);

        // Les équipes (vérifiées sur Poképédia) sont créées par PokepediaSeeder.
    }
}
