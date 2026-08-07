<?php

namespace App\DataFixtures;

use App\Entity\Arene;
use App\Entity\Dresseur;
use App\Entity\Map;
use App\Entity\Team;
use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class WorldFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['world'];
    }

    public function load(ObjectManager $manager): void
    {
        // --- MAPS ---
        $mapsData = [
            ['nom' => 'Kanto', 'jeu' => 'Rouge/Bleu/Jaune', 'description' => 'La région d\'origine, où l\'aventure a commencé.', 'image' => 'kanto.png'],
            ['nom' => 'Johto', 'jeu' => 'Or/Argent/Cristal', 'description' => 'Une région riche en mythes et traditions.', 'image' => 'johto.png'],
            ['nom' => 'Hoenn', 'jeu' => 'Rubis/Saphir/Émeraude', 'description' => 'Une région tropicale avec de vastes océans.', 'image' => 'hoenn.png'],
            ['nom' => 'Sinnoh', 'jeu' => 'Diamant/Perle/Platine', 'description' => 'Une région montagneuse abritant le Mont Couronné.', 'image' => 'sinnoh.png'],
        ];

        foreach ($mapsData as $data) {
            $map = $manager->getRepository(Map::class)->findOneBy(['nom' => $data['nom']]);
            if (!$map) {
                $map = new Map();
                $map->setNom($data['nom']);
                $map->setJeu($data['jeu']);
                $map->setDescription($data['description']);
                $map->setImage($data['image']);
                $manager->persist($map);
            }
        }

        // --- DRESSEURS (Héros et Champions) ---
        $sacha = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Sacha']);
        if (!$sacha) {
            $sacha = new Dresseur();
            $sacha->setNom('Ketchum');
            $sacha->setPrenom('Sacha');
            $sacha->setVilleNatale('Bourg Palette');
            $sacha->setRegion('Kanto');
            $sacha->setAmbition('Devenir un Maître Pokémon.');
            $sacha->setDescription('Un jeune dresseur plein d\'énergie, toujours accompagné de son Pikachu.');
            $sacha->setEstMechant(false);
            $sacha->setImage('sacha.png');
            $manager->persist($sacha);
        }

        $pierre = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Pierre']);
        if (!$pierre) {
            $pierre = new Dresseur();
            $pierre->setNom('');
            $pierre->setPrenom('Pierre');
            $pierre->setVilleNatale('Argenta');
            $pierre->setRegion('Kanto');
            $pierre->setAmbition('Devenir le meilleur Éleveur Pokémon.');
            $pierre->setDescription('Un dresseur expert en Pokémon Roche et excellent cuisinier.');
            $pierre->setEstMechant(false);
            $pierre->setImage('pierre.png');
            $manager->persist($pierre);
        }

        $ondine = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Ondine']);
        if (!$ondine) {
            $ondine = new Dresseur();
            $ondine->setNom('');
            $ondine->setPrenom('Ondine');
            $ondine->setVilleNatale('Azuria');
            $ondine->setRegion('Kanto');
            $ondine->setAmbition('Devenir la meilleure dresseuse de Pokémon Eau.');
            $ondine->setDescription('Spécialiste des Pokémon Eau, avec un fort caractère.');
            $ondine->setEstMechant(false);
            $ondine->setImage('ondine.png');
            $manager->persist($ondine);
        }

        // --- ARÈNES ---
        $areneArgenta = $manager->getRepository(Arene::class)->findOneBy(['nom' => 'Arène d\'Argenta']);
        if (!$areneArgenta) {
            $areneArgenta = new Arene();
            $areneArgenta->setNom('Arène d\'Argenta');
            $areneArgenta->setRegion('Kanto');
            $areneArgenta->setLieu('Argenta');
            $areneArgenta->setBadge('Badge Roche');
            $areneArgenta->setImageBadge('badge-roche.png');
            $areneArgenta->setChampion($pierre);
            $manager->persist($areneArgenta);
        }

        $areneAzuria = $manager->getRepository(Arene::class)->findOneBy(['nom' => 'Arène d\'Azuria']);
        if (!$areneAzuria) {
            $areneAzuria = new Arene();
            $areneAzuria->setNom('Arène d\'Azuria');
            $areneAzuria->setRegion('Kanto');
            $areneAzuria->setLieu('Azuria');
            $areneAzuria->setBadge('Badge Cascade');
            $areneAzuria->setImageBadge('badge-cascade.png');
            $areneAzuria->setChampion($ondine);
            $manager->persist($areneAzuria);
        }

        // --- TEAMS (Equipes de Pokémon pour les dresseurs) ---
        // On récupère quelques Pokémon si possible (dépend de PokemonFixtures)
        $pikachu = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Pikachu']);
        $dracaufeu = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Dracaufeu']);
        $racaillou = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Racaillou']);
        $onix = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Onix']);
        $stari = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Stari']);
        $staross = $manager->getRepository(Pokemon::class)->findOneBy(['nom' => 'Staross']);

        if ($pikachu && $dracaufeu) {
            $teamSacha = $manager->getRepository(Team::class)->findOneBy(['nom' => 'Équipe de Sacha', 'dresseur' => $sacha]);
            if (!$teamSacha) {
                $teamSacha = new Team();
                $teamSacha->setNom('Équipe de Sacha');
                $teamSacha->setDresseur($sacha);
                $teamSacha->addPokemon($pikachu);
                $teamSacha->addPokemon($dracaufeu);
                $manager->persist($teamSacha);
            }
        }

        if ($racaillou && $onix) {
            $teamPierre = $manager->getRepository(Team::class)->findOneBy(['nom' => 'Équipe de Pierre', 'dresseur' => $pierre]);
            if (!$teamPierre) {
                $teamPierre = new Team();
                $teamPierre->setNom('Équipe de Pierre');
                $teamPierre->setDresseur($pierre);
                $teamPierre->addPokemon($racaillou);
                $teamPierre->addPokemon($onix);
                $manager->persist($teamPierre);
            }
        }

        if ($stari && $staross) {
            $teamOndine = $manager->getRepository(Team::class)->findOneBy(['nom' => 'Équipe d\'Ondine', 'dresseur' => $ondine]);
            if (!$teamOndine) {
                $teamOndine = new Team();
                $teamOndine->setNom('Équipe d\'Ondine');
                $teamOndine->setDresseur($ondine);
                $teamOndine->addPokemon($stari);
                $teamOndine->addPokemon($staross);
                $manager->persist($teamOndine);
            }
        }

        $manager->flush();
    }

}
