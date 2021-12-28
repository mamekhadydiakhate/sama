<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111142837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515DB12F0DA');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515E6357589');
        $this->addSql('DROP INDEX IDX_B8755515E6357589 ON activite');
        $this->addSql('DROP INDEX IDX_B8755515DB12F0DA ON activite');
        $this->addSql('ALTER TABLE activite DROP point_de_coordination_id, DROP difficulte_id');
        $this->addSql('ALTER TABLE difficulte ADD activite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE difficulte ADD CONSTRAINT FK_AF6A33A09B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('CREATE INDEX IDX_AF6A33A09B0F88B1 ON difficulte (activite_id)');
        $this->addSql('ALTER TABLE point_de_coordination ADD activite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point_de_coordination ADD CONSTRAINT FK_9BFC84739B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('CREATE INDEX IDX_9BFC84739B0F88B1 ON point_de_coordination (activite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD point_de_coordination_id INT DEFAULT NULL, ADD difficulte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515DB12F0DA FOREIGN KEY (point_de_coordination_id) REFERENCES point_de_coordination (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515E6357589 FOREIGN KEY (difficulte_id) REFERENCES difficulte (id)');
        $this->addSql('CREATE INDEX IDX_B8755515E6357589 ON activite (difficulte_id)');
        $this->addSql('CREATE INDEX IDX_B8755515DB12F0DA ON activite (point_de_coordination_id)');
        $this->addSql('ALTER TABLE difficulte DROP FOREIGN KEY FK_AF6A33A09B0F88B1');
        $this->addSql('DROP INDEX IDX_AF6A33A09B0F88B1 ON difficulte');
        $this->addSql('ALTER TABLE difficulte DROP activite_id');
        $this->addSql('ALTER TABLE point_de_coordination DROP FOREIGN KEY FK_9BFC84739B0F88B1');
        $this->addSql('DROP INDEX IDX_9BFC84739B0F88B1 ON point_de_coordination');
        $this->addSql('ALTER TABLE point_de_coordination DROP activite_id');
    }
}
