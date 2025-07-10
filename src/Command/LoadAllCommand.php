<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\DBAL\Connection;

#[AsCommand(
    name: 'app:load:all',
    description: 'Charge toutes les fixtures + les imports personnalisés',
)]
class LoadAllCommand extends Command
{
    private Application $application;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false); // Important pour éviter l'arrêt du processus
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // 0. Réinitialisation de la table pokemon
        $io->section('Réinitialisation de la table pokemon');
        /** @var Connection $conn */
        $conn = $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getConnection();
        $conn->executeStatement("SET FOREIGN_KEY_CHECKS = 0");
        $conn->executeStatement("UPDATE pokemon SET idEvolutionPrecedente = NULL");
        $conn->executeStatement("TRUNCATE TABLE pokemon");
        $conn->executeStatement("SET FOREIGN_KEY_CHECKS = 1");

        // 1. Charger les fixtures
        $io->title('Chargement des fixtures Doctrine');
        $this->application->run(new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true
        ]), $output);

        // 2. Import des dresseurs
        $io->section('Import des dresseurs');
        $this->application->run(new ArrayInput([
            'command' => 'app:import:dresseurs',
        ]), $output);

        // 3. Import des arènes
        $io->section('Import des arènes');
        $this->application->run(new ArrayInput([
            'command' => 'app:import:arenes',
        ]), $output);

        $io->success('Tous les imports ont été exécutés avec succès.');

        return Command::SUCCESS;
    }
}
