<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227104708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, but LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur ADD organisation_id INT DEFAULT NULL, ADD est_mechant TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur ADD CONSTRAINT FK_77EA2FC69E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_77EA2FC69E6B1585 ON dresseur (organisation_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur DROP FOREIGN KEY FK_77EA2FC69E6B1585
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE organisation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_77EA2FC69E6B1585 ON dresseur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur DROP organisation_id, DROP est_mechant
        SQL);
    }
}
