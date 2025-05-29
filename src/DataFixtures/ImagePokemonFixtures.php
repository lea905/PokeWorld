<?php
namespace App\DataFixtures;

use App\Entity\ImagePokemon;
use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImagePokemonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // On ajoute juste une image à chaque pokémon déjà créé
        for ($i = 1; $i <= 10; $i++) {
            $pokemon = $this->getReference('pokemon_' . $i,Pokemon::class);

            $image = new ImagePokemon();
            $image->setPokemon($pokemon);
            $image->setIdPokemon($pokemon->getIdPokemon());
            $image->setTypeImage('front_default');
            $image->setUrlImage($pokemon->getImagePrincipale());

            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PokemonFixtures::class,
        ];
    }
}
