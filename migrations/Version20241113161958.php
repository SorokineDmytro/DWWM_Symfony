<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113161958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tiers DROP FOREIGN KEY FK_16473BA2A54850DF');
        $this->addSql('DROP INDEX IDX_16473BA2A54850DF ON tiers');
        $this->addSql('ALTER TABLE tiers CHANGE type_tiers_id typeTiers_id INT NOT NULL');
        $this->addSql('ALTER TABLE tiers ADD CONSTRAINT FK_16473BA2CDA89BFD FOREIGN KEY (typeTiers_id) REFERENCES typeTiers (id)');
        $this->addSql('CREATE INDEX IDX_16473BA2CDA89BFD ON tiers (typeTiers_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tiers DROP FOREIGN KEY FK_16473BA2CDA89BFD');
        $this->addSql('DROP INDEX IDX_16473BA2CDA89BFD ON tiers');
        $this->addSql('ALTER TABLE tiers CHANGE typeTiers_id type_tiers_id INT NOT NULL');
        $this->addSql('ALTER TABLE tiers ADD CONSTRAINT FK_16473BA2A54850DF FOREIGN KEY (type_tiers_id) REFERENCES typeTiers (id)');
        $this->addSql('CREATE INDEX IDX_16473BA2A54850DF ON tiers (type_tiers_id)');
    }
}
