<?php
namespace App\DataFixtures;

use App\Entity\Dresseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DresseurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dresseur = new Dresseur();
        $dresseur->setNom('Ketchum');
        $dresseur->setPrenom('Sacha');
        $dresseur->setLieuOrigine('Bourg Palette');
        $dresseur->setAmbition('Devenir Maître Pokémon');
        $dresseur->setDescription('Dresseur passionné et courageux.');

        $manager->persist($dresseur);
        $this->setReference('dresseur_sacha', $dresseur);

        $manager->flush();
    }
}
