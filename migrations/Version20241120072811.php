<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120072811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligneMouvement (id INT AUTO_INCREMENT NOT NULL, mouvement_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, quantite NUMERIC(10, 2) NOT NULL, prixUnitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_A8FA2ACBECD1C222 (mouvement_id), INDEX IDX_A8FA2ACBF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ligneMouvement ADD CONSTRAINT FK_A8FA2ACBECD1C222 FOREIGN KEY (mouvement_id) REFERENCES mouvement (id)');
        $this->addSql('ALTER TABLE ligneMouvement ADD CONSTRAINT FK_A8FA2ACBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligneMouvement DROP FOREIGN KEY FK_A8FA2ACBECD1C222');
        $this->addSql('ALTER TABLE ligneMouvement DROP FOREIGN KEY FK_A8FA2ACBF347EFB');
        $this->addSql('DROP TABLE ligneMouvement');
    }
}
