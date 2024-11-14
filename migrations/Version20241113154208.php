<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113154208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tiers (id INT AUTO_INCREMENT NOT NULL, num_tiers VARCHAR(20) NOT NULL, nom_tiers VARCHAR(255) NOT NULL, adresse_tiers VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_6eefc49bcf5e72d TO IDX_29A5EC27BCF5E72D');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tiers');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_29a5ec27bcf5e72d TO IDX_6EEFC49BCF5E72D');
    }
}
