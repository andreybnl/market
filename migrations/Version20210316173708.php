<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316173708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE market_product ADD order_minimum INT DEFAULT NULL, ADD qty_increments INT DEFAULT NULL, ADD root VARCHAR(255) DEFAULT NULL, ADD root_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE market_product DROP order_minimum, DROP qty_increments, DROP root, DROP root_type');
    }
}
