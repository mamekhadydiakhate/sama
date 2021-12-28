<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015100540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, user_id INT DEFAULT NULL, tranche_horaire_id INT DEFAULT NULL, historique_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_B87555152534008B (structure_id), INDEX IDX_B8755515A76ED395 (user_id), INDEX IDX_B875551569832F6F (tranche_horaire_id), UNIQUE INDEX UNIQ_B87555156128735E (historique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_pp (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', login_tentative INT DEFAULT NULL, login_tentative_at DATETIME DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E5F1FF7592FC23A8 (username_canonical), UNIQUE INDEX UNIQ_E5F1FF75A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_E5F1FF75C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autorite (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, id_personne VARCHAR(255) NOT NULL, INDEX IDX_BC599E16FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, evenement_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_67F068BCFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulte (id INT AUTO_INCREMENT NOT NULL, activite_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, cause VARCHAR(255) NOT NULL, INDEX IDX_AF6A33A09B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, historique_evenement_id INT DEFAULT NULL, periodicite_id INT DEFAULT NULL, user_id INT DEFAULT NULL, thematique VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, lieu VARCHAR(255) DEFAULT NULL, structure_org VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, structure_concernee VARCHAR(255) DEFAULT NULL, INDEX IDX_B26681E2534008B (structure_id), UNIQUE INDEX UNIQ_B26681EABD84570 (historique_evenement_id), INDEX IDX_B26681E2928752A (periodicite_id), INDEX IDX_B26681EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extraction (id INT AUTO_INCREMENT NOT NULL, type_periodicite_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, type_extraction VARCHAR(255) NOT NULL, INDEX IDX_3ADCB5D67FEB5CBE (type_periodicite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, addresse_ip VARCHAR(255) DEFAULT NULL, INDEX IDX_EDBFD5ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_evenement (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, id_utilisateur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_58552356FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interim (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', login_tentative INT DEFAULT NULL, login_tentative_at DATETIME DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, personne_remplacee VARCHAR(255) NOT NULL, id_utilisateur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_615F886992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_615F8869A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_615F8869C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periodicite (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_de_coordination (id INT AUTO_INCREMENT NOT NULL, activite_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, structure_impactee VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_9BFC84739B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, extraction_id INT DEFAULT NULL, type_structure_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_6F0137EAF992488A (extraction_id), UNIQUE INDEX UNIQ_6F0137EAA277BA8E (type_structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tranche_horaire (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, INDEX IDX_F5A404F9FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_periodicite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_service (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C9BCF5272534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_structure (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, workflow_id INT DEFAULT NULL, admin_pp_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', login_tentative INT DEFAULT NULL, login_tentative_at DATETIME DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B392FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1D1C63B3A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1D1C63B3C05FB297 (confirmation_token), INDEX IDX_1D1C63B3275ED078 (profil_id), INDEX IDX_1D1C63B32C7C2CBA (workflow_id), INDEX IDX_1D1C63B36DE55096 (admin_pp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workflow (id INT AUTO_INCREMENT NOT NULL, admin_pp_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_65C598166DE55096 (admin_pp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555152534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551569832F6F FOREIGN KEY (tranche_horaire_id) REFERENCES tranche_horaire (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555156128735E FOREIGN KEY (historique_id) REFERENCES historique (id)');
        $this->addSql('ALTER TABLE autorite ADD CONSTRAINT FK_BC599E16FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE difficulte ADD CONSTRAINT FK_AF6A33A09B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EABD84570 FOREIGN KEY (historique_evenement_id) REFERENCES historique_evenement (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E2928752A FOREIGN KEY (periodicite_id) REFERENCES periodicite (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE extraction ADD CONSTRAINT FK_3ADCB5D67FEB5CBE FOREIGN KEY (type_periodicite_id) REFERENCES type_periodicite (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE historique_evenement ADD CONSTRAINT FK_58552356FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE point_de_coordination ADD CONSTRAINT FK_9BFC84739B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAF992488A FOREIGN KEY (extraction_id) REFERENCES extraction (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA277BA8E FOREIGN KEY (type_structure_id) REFERENCES type_structure (id)');
        $this->addSql('ALTER TABLE tranche_horaire ADD CONSTRAINT FK_F5A404F9FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE type_service ADD CONSTRAINT FK_C9BCF5272534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B36DE55096 FOREIGN KEY (admin_pp_id) REFERENCES admin_pp (id)');
        $this->addSql('ALTER TABLE workflow ADD CONSTRAINT FK_65C598166DE55096 FOREIGN KEY (admin_pp_id) REFERENCES admin_pp (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE difficulte DROP FOREIGN KEY FK_AF6A33A09B0F88B1');
        $this->addSql('ALTER TABLE point_de_coordination DROP FOREIGN KEY FK_9BFC84739B0F88B1');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B36DE55096');
        $this->addSql('ALTER TABLE workflow DROP FOREIGN KEY FK_65C598166DE55096');
        $this->addSql('ALTER TABLE autorite DROP FOREIGN KEY FK_BC599E16FD02F13');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFD02F13');
        $this->addSql('ALTER TABLE historique_evenement DROP FOREIGN KEY FK_58552356FD02F13');
        $this->addSql('ALTER TABLE tranche_horaire DROP FOREIGN KEY FK_F5A404F9FD02F13');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAF992488A');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555156128735E');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EABD84570');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E2928752A');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3275ED078');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555152534008B');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E2534008B');
        $this->addSql('ALTER TABLE type_service DROP FOREIGN KEY FK_C9BCF5272534008B');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551569832F6F');
        $this->addSql('ALTER TABLE extraction DROP FOREIGN KEY FK_3ADCB5D67FEB5CBE');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAA277BA8E');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515A76ED395');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EA76ED395');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32C7C2CBA');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE admin_pp');
        $this->addSql('DROP TABLE autorite');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE difficulte');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE extraction');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE historique_evenement');
        $this->addSql('DROP TABLE interim');
        $this->addSql('DROP TABLE periodicite');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE point_de_coordination');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE tranche_horaire');
        $this->addSql('DROP TABLE type_periodicite');
        $this->addSql('DROP TABLE type_service');
        $this->addSql('DROP TABLE type_structure');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE workflow');
    }
}
