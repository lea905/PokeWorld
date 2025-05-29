<?php

namespace App\DataFixtures;

use App\Entity\Arene;
use App\Entity\Dresseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AreneFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $champion = $this->getReference('dresseur_sacha',Dresseur::class);
        $arene = new Arene();
        $arene->setNom('Arène de Bourg Palette');
        $arene->setRegion('Kanto');
        $arene->setLieu('Bourg Palette');
        $arene->setBadge('Badge de Bourg Palette');
        $arene->setImageBadge('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/badge.png');
        $arene->setChampion($champion);

        $manager->persist($arene);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DresseurFixtures::class,
        ];
    }
}
