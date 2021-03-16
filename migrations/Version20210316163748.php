<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316163748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE market_product ADD shape_diameter VARCHAR(255) NOT NULL, ADD plantenbak_vorm VARCHAR(255) DEFAULT NULL, ADD plantenbak_diameter VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE market_product DROP shape_diameter, DROP plantenbak_vorm, DROP plantenbak_diameter');
    }
}
