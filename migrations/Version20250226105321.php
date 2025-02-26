<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226105321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit (id INT AUTO_INCREMENT NOT NULL, auditeur_id INT DEFAULT NULL, site_id INT DEFAULT NULL, date_heure_audit DATETIME NOT NULL, score_conformite DOUBLE PRECISION NOT NULL, zone VARCHAR(255) NOT NULL, INDEX IDX_9218FF79D8CACAB (auditeur_id), INDEX IDX_9218FF79F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auditeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, nom VARCHAR(255) NOT NULL, support VARCHAR(255) NOT NULL, est_actif TINYINT(1) NOT NULL, critere LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, nom_site VARCHAR(255) NOT NULL, mail_contact VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verification (id INT AUTO_INCREMENT NOT NULL, operation_id INT DEFAULT NULL, audit_id INT DEFAULT NULL, est_conforme TINYINT(1) NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_5AF1C50B44AC3583 (operation_id), INDEX IDX_5AF1C50BBD29F359 (audit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF79D8CACAB FOREIGN KEY (auditeur_id) REFERENCES auditeur (id)');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF79F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE verification ADD CONSTRAINT FK_5AF1C50B44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id)');
        $this->addSql('ALTER TABLE verification ADD CONSTRAINT FK_5AF1C50BBD29F359 FOREIGN KEY (audit_id) REFERENCES audit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79D8CACAB');
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79F6BD1646');
        $this->addSql('ALTER TABLE verification DROP FOREIGN KEY FK_5AF1C50B44AC3583');
        $this->addSql('ALTER TABLE verification DROP FOREIGN KEY FK_5AF1C50BBD29F359');
        $this->addSql('DROP TABLE audit');
        $this->addSql('DROP TABLE auditeur');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE verification');
    }
}
