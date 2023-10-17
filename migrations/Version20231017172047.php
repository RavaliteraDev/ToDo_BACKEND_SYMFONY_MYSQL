<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017172047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD last_name LONGTEXT NOT NULL, ADD first_name LONGTEXT DEFAULT NULL, ADD email_address LONGTEXT NOT NULL, ADD phone_number LONGTEXT DEFAULT NULL, ADD password LONGTEXT NOT NULL, ADD is_activated TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP last_name, DROP first_name, DROP email_address, DROP phone_number, DROP password, DROP is_activated');
    }
}
