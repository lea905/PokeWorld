<?php
namespace App\DataFixtures;

use App\Entity\Dresseur;
use App\Entity\Equipe;
use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EquipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Création d’une équipe simple pour Sacha
        $dresseur = $this->getReference('dresseur_sacha',Dresseur::class);
        $pokemon = $this->getReference('pokemon_1',Pokemon::class); // Bulbizarre

        $equipe = new Equipe();
        $equipe->setDresseur($dresseur);
        $equipe->setPokemon($pokemon);
        $equipe->setNiveau(15);

        $manager->persist($equipe);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DresseurFixtures::class,
            PokemonFixtures::class,
        ];
    }
}
