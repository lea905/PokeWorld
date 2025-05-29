<?php
namespace App\DataFixtures;

use App\Entity\Map;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MapFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $map = new Map();
        $map->setNom('Kanto');
        $map->setJeu('Pokémon Rouge / Bleu');
        $map->setImage('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png'); // Exemple image
        $map->setDescription('La région de départ dans la première génération.');

        $manager->persist($map);
        $manager->flush();
    }
}
