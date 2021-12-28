<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110083132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP INDEX UNIQ_B87555156128735E, ADD INDEX IDX_B87555156128735E (historique_id)');
        $this->addSql('ALTER TABLE evenement ADD historique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6128735E FOREIGN KEY (historique_id) REFERENCES historique (id)');
        $this->addSql('CREATE INDEX IDX_B26681E6128735E ON evenement (historique_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP INDEX IDX_B87555156128735E, ADD UNIQUE INDEX UNIQ_B87555156128735E (historique_id)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6128735E');
        $this->addSql('DROP INDEX IDX_B26681E6128735E ON evenement');
        $this->addSql('ALTER TABLE evenement DROP historique_id');
    }
}
