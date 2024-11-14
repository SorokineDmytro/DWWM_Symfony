<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112111443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit CHANGE num_produit numProduit VARCHAR(20) NOT NULL, CHANGE prix_unitaire prixUnitaire NUMERIC(10, 2) NOT NULL, CHANGE prix_revient prixRevient NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit CHANGE numProduit num_produit VARCHAR(20) NOT NULL, CHANGE prixUnitaire prix_unitaire NUMERIC(10, 2) NOT NULL, CHANGE prixRevient prix_revient NUMERIC(10, 2) DEFAULT NULL');
    }
}
