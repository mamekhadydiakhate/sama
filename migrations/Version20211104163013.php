<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104163013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551569832F6F');
        $this->addSql('DROP INDEX IDX_B875551569832F6F ON activite');
        $this->addSql('ALTER TABLE activite DROP tranche_horaire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD tranche_horaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551569832F6F FOREIGN KEY (tranche_horaire_id) REFERENCES tranche_horaire (id)');
        $this->addSql('CREATE INDEX IDX_B875551569832F6F ON activite (tranche_horaire_id)');
    }
}
