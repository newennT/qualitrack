<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324125245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79D8CACAB');
        $this->addSql('ALTER TABLE audit CHANGE auditeur_id auditeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF79D8CACAB FOREIGN KEY (auditeur_id) REFERENCES auditeur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79D8CACAB');
        $this->addSql('ALTER TABLE audit CHANGE auditeur_id auditeur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE audit ADD CONSTRAINT FK_9218FF79D8CACAB FOREIGN KEY (auditeur_id) REFERENCES auditeur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
