<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118130425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mouvement (id INT AUTO_INCREMENT NOT NULL, type_mouvement_id INT DEFAULT NULL, tiers_id INT DEFAULT NULL, numMouvement VARCHAR(20) NOT NULL, dateMouvement DATETIME DEFAULT NULL, INDEX IDX_5B51FC3E6B927827 (type_mouvement_id), INDEX IDX_5B51FC3E68B77723 (tiers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3E6B927827 FOREIGN KEY (type_mouvement_id) REFERENCES typeMouvement (id)');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3E68B77723 FOREIGN KEY (tiers_id) REFERENCES tiers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3E6B927827');
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3E68B77723');
        $this->addSql('DROP TABLE mouvement');
    }
}
