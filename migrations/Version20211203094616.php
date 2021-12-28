<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211203094616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pp DROP lundi');
        $this->addSql('ALTER TABLE interim DROP lundi');
        $this->addSql('ALTER TABLE utilisateur DROP lundi');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_pp ADD lundi DATE NOT NULL');
        $this->addSql('ALTER TABLE interim ADD lundi DATE NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD lundi DATE NOT NULL');
    }
}
