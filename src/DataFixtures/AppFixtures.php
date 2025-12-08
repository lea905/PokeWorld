<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Dresseur;
use App\Entity\Arene;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function load(ObjectManager $manager): void
    {
        $dresseursData = json_decode(file_get_contents($this->kernel->getProjectDir() . '/src/DataFixtures/data/Dresseurs.json'), true);
        $dresseurEntities = [];

        foreach ($dresseursData as $data) {
            $dresseur = new Dresseur();
            $dresseur->setNom($data['nom'] ?: 'Inconnu');
            if (empty($data['nom']) && !empty($data['prenom'])) {
                $dresseur->setNom($data['prenom']);
                $dresseur->setPrenom('');
            } else {
                $dresseur->setPrenom($data['prenom']);
            }

            if ($dresseur->getNom() === 'Inconnu' && !empty($data['prenom'])) {
                $dresseur->setNom($data['prenom']);
                $dresseur->setPrenom('');
            }

            if (!empty($data['nom']) && !empty($data['prenom'])) {
                $dresseur->setNom($data['nom']);
                $dresseur->setPrenom($data['prenom']);
            } elseif (empty($data['nom']) && !empty($data['prenom'])) {
                $dresseur->setNom($data['prenom']);
                $dresseur->setPrenom('');
            }

            $dresseur->setLieuOrigine($data['lieuOrigine']);
            $dresseur->setAmbition($data['ambition']);
            $dresseur->setDescription($data['description']);
            $dresseur->setImage('images/' . $data['image']);

            $manager->persist($dresseur);

            $key = $dresseur->getNom();
            $dresseurEntities[$key] = $dresseur;
        }

        $arenesData = json_decode(file_get_contents($this->kernel->getProjectDir() . '/src/DataFixtures/data/arenes.json'), true);

        foreach ($arenesData as $data) {
            $arene = new Arene();
            $arene->setNom($data['nom']);
            $arene->setRegion($data['region']);
            $arene->setLieu($data['lieu']);
            $arene->setBadge($data['badge']);
            $arene->setImageBadge('images/' . $data['imageBadge']);

            if (isset($data['champion'])) {
                $championName = $data['champion'];
                if (isset($dresseurEntities[$championName])) {
                    $arene->setChampion($dresseurEntities[$championName]);
                }
            }

            $manager->persist($arene);
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
