<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204133502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typeMouvement ADD type_tiers_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE typeMouvement ADD CONSTRAINT FK_3A9F1C01A54850DF FOREIGN KEY (type_tiers_id) REFERENCES typeTiers (id)');
        $this->addSql('CREATE INDEX IDX_3A9F1C01A54850DF ON typeMouvement (type_tiers_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typeMouvement DROP FOREIGN KEY FK_3A9F1C01A54850DF');
        $this->addSql('DROP INDEX IDX_3A9F1C01A54850DF ON typeMouvement');
        $this->addSql('ALTER TABLE typeMouvement DROP type_tiers_id');
    }
}
