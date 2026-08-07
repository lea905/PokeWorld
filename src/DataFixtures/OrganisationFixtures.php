<?php

namespace App\DataFixtures;

use App\Entity\Dresseur;
use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de la Team Rocket (ou récupération si elle existe)
        $teamRocket = $manager->getRepository(Organisation::class)->findOneBy(['nom' => 'Team Rocket']);
        if (!$teamRocket) {
            $teamRocket = new Organisation();
            $teamRocket->setNom('Team Rocket');
            $teamRocket->setBut('Voler les Pokémon rares et puissants pour dominer le monde.');
            $teamRocket->setImage('team-rocket.png');
            $manager->persist($teamRocket);
        }

        // Création du Boss Giovanni
        $giovanni = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Giovanni']);
        if (!$giovanni) {
            $giovanni = new Dresseur();
            $giovanni->setPrenom('Giovanni');
            $giovanni->setNom(''); // Giovanni n'a qu'un prénom connu
            $giovanni->setVilleNatale('Jadielle');
            $giovanni->setRegion('Kanto');
            $giovanni->setAmbition('Créer le Pokémon le plus puissant (Mewtwo) et diriger le monde.');
            $giovanni->setDescription('Le Boss redoutable de la Team Rocket et Champion d\'arène.');
            $giovanni->setEstMechant(true);
            $giovanni->setGrade('Boss');
            $giovanni->setOrganisation($teamRocket);
            $manager->persist($giovanni);
        } else {
            $giovanni->setGrade('Boss');
        }

        // Création d'un Admin (Amos)
        $amos = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Amos']);
        if (!$amos) {
            $amos = new Dresseur();
            $amos->setPrenom('Amos');
            $amos->setNom('');
            $amos->setVilleNatale('Inconnue');
            $amos->setRegion('Johto');
            $amos->setAmbition('Faire revenir Giovanni et restaurer la gloire de la Team Rocket.');
            $amos->setDescription('L\'un des Commandants les plus fidèles de la Team Rocket.');
            $amos->setEstMechant(true);
            $amos->setGrade('Admin');
            $amos->setOrganisation($teamRocket);
            $manager->persist($amos);
        } else {
            $amos->setGrade('Admin');
        }

        // Création d'un Sbire
        $sbire1 = $manager->getRepository(Dresseur::class)->findOneBy(['prenom' => 'Sbire H']);
        if (!$sbire1) {
            $sbire1 = new Dresseur();
            $sbire1->setPrenom('Sbire H');
            $sbire1->setNom('Rocket');
            $sbire1->setVilleNatale('Inconnue');
            $sbire1->setRegion('Kanto');
            $sbire1->setAmbition('Servir Giovanni et voler des Pokémon.');
            $sbire1->setDescription('Un sbire classique de la Team Rocket, prêt à tout.');
            $sbire1->setEstMechant(true);
            $sbire1->setGrade('Sbire');
            $sbire1->setOrganisation($teamRocket);
            $manager->persist($sbire1);
        } else {
            $sbire1->setGrade('Sbire');
        }

        $manager->flush();
    }
}
