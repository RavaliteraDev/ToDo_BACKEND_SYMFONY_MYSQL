<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021193458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD user_name LONGTEXT NOT NULL, DROP first_name, CHANGE last_name full_name LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE to_do ADD comments LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD last_name LONGTEXT NOT NULL, ADD first_name LONGTEXT DEFAULT NULL, DROP full_name, DROP user_name');
        $this->addSql('ALTER TABLE to_do DROP comments');
    }
}
