<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226134938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur CHANGE ambition ambition LONGTEXT NOT NULL, CHANGE region region VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team RENAME INDEX idx_c4e0a61f87cb4a1f TO IDX_C4E0A61FA1A01CBE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon DROP FOREIGN KEY FK_5C722232296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon DROP FOREIGN KEY FK_5C7222322FE71C3E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon ADD CONSTRAINT FK_9DA5E1C4296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon ADD CONSTRAINT FK_9DA5E1C42FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id_pokemon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon RENAME INDEX idx_5c722232296cd8ae TO IDX_9DA5E1C4296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon RENAME INDEX idx_5c7222322fe71c3e TO IDX_9DA5E1C42FE71C3E
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE team RENAME INDEX idx_c4e0a61fa1a01cbe TO IDX_C4E0A61F87CB4A1F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon DROP FOREIGN KEY FK_9DA5E1C4296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon DROP FOREIGN KEY FK_9DA5E1C42FE71C3E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon ADD CONSTRAINT FK_5C722232296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon ADD CONSTRAINT FK_5C7222322FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id_pokemon) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon RENAME INDEX idx_9da5e1c4296cd8ae TO IDX_5C722232296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team_pokemon RENAME INDEX idx_9da5e1c42fe71c3e TO IDX_5C7222322FE71C3E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dresseur CHANGE region region VARCHAR(255) DEFAULT '' NOT NULL, CHANGE ambition ambition VARCHAR(255) NOT NULL
        SQL);
    }
}
