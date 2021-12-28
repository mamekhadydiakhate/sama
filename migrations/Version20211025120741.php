<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025120741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EABD84570 FOREIGN KEY (historique_evenement_id) REFERENCES evenement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B26681EABD84570 ON evenement (historique_evenement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EABD84570');
        $this->addSql('DROP INDEX UNIQ_B26681EABD84570 ON evenement');
    }
}
