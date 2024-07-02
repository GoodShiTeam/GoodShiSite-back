<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240701151931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at column with default value CURRENT_TIMESTAMP to contact table';
    }

    public function up(Schema $schema): void
    {
        // Ajout de la colonne created_at avec la valeur par défaut CURRENT_TIMESTAMP
        $this->addSql('ALTER TABLE contact ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Suppression de la colonne datetime
        $this->addSql('ALTER TABLE contact DROP datetime');
    }
}
