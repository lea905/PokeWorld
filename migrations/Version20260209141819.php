<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209141819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, dresseur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F87CB4A1F (dresseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_pokemon (team_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_5C722232296CD8AE (team_id), INDEX IDX_5C7222322FE71C3E (pokemon_id), PRIMARY KEY(team_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F87CB4A1F FOREIGN KEY (dresseur_id) REFERENCES dresseur (id)');
        $this->addSql('ALTER TABLE team_pokemon ADD CONSTRAINT FK_5C722232296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_pokemon ADD CONSTRAINT FK_5C7222322FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id_pokemon) ON DELETE CASCADE');
        $this->addSql('DROP TABLE equipe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, idDresseur INT NOT NULL, idPokemon INT NOT NULL, niveau INT DEFAULT NULL, INDEX IDX_2449BA1573B9CC3 (idDresseur), INDEX IDX_2449BA153F32F279 (idPokemon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1573B9CC3 FOREIGN KEY (idDresseur) REFERENCES dresseur (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA153F32F279 FOREIGN KEY (idPokemon) REFERENCES pokemon (id_pokemon)');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F87CB4A1F');
        $this->addSql('ALTER TABLE team_pokemon DROP FOREIGN KEY FK_5C722232296CD8AE');
        $this->addSql('ALTER TABLE team_pokemon DROP FOREIGN KEY FK_5C7222322FE71C3E');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_pokemon');
    }
}
