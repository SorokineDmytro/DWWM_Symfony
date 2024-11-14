<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113131814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dwwm_produit ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dwwm_produit ADD CONSTRAINT FK_6EEFC49BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_6EEFC49BCF5E72D ON dwwm_produit (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dwwm_produit DROP FOREIGN KEY FK_6EEFC49BCF5E72D');
        $this->addSql('DROP INDEX IDX_6EEFC49BCF5E72D ON dwwm_produit');
        $this->addSql('ALTER TABLE dwwm_produit DROP categorie_id');
    }
}
