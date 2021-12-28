<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021091010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_service ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_service ADD CONSTRAINT FK_C9BCF527A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C9BCF527A76ED395 ON type_service (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_service DROP FOREIGN KEY FK_C9BCF527A76ED395');
        $this->addSql('DROP INDEX UNIQ_C9BCF527A76ED395 ON type_service');
        $this->addSql('ALTER TABLE type_service DROP user_id');
    }
}
