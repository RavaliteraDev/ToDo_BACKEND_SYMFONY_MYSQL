<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018073341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE to_do (uuid VARCHAR(36) NOT NULL, person_uuid VARCHAR(36) NOT NULL, title LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, due_date DATETIME DEFAULT NULL, is_completed TINYINT(1) NOT NULL, INDEX IDX_1249EDA05B99D48D (person_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE to_do ADD CONSTRAINT FK_1249EDA05B99D48D FOREIGN KEY (person_uuid) REFERENCES person (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE to_do DROP FOREIGN KEY FK_1249EDA05B99D48D');
        $this->addSql('DROP TABLE to_do');
    }
}
