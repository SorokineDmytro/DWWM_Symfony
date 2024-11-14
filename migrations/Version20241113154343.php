<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113154343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tiers ADD nomTiers VARCHAR(255) NOT NULL, ADD adresseTiers VARCHAR(255) NOT NULL, DROP nom_tiers, DROP adresse_tiers, CHANGE num_tiers numTiers VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tiers ADD nom_tiers VARCHAR(255) NOT NULL, ADD adresse_tiers VARCHAR(255) NOT NULL, DROP nomTiers, DROP adresseTiers, CHANGE numTiers num_tiers VARCHAR(20) NOT NULL');
    }
}
