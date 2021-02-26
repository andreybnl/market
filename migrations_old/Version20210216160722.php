<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216160722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE market_product (id INT AUTO_INCREMENT NOT NULL, create_time DATETIME NOT NULL, edit_time DATETIME NOT NULL, sku VARCHAR(65) NOT NULL, range_identifier VARCHAR(255) NOT NULL, core_identifier VARCHAR(255) NOT NULL, _retail_hash VARCHAR(255) NOT NULL, _batch_hash_array VARCHAR(255) NOT NULL, _batch_hash VARCHAR(255) NOT NULL, _core_hash VARCHAR(255) NOT NULL, _product_range_hash VARCHAR(255) NOT NULL, _product_group_hash VARCHAR(255) NOT NULL, _product_core_hash VARCHAR(255) NOT NULL, name_search VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, btch_stock VARCHAR(255) NOT NULL, rtl_size_code VARCHAR(255) NOT NULL, batch_id_original INT NOT NULL, btch_stock_total VARCHAR(255) NOT NULL, btch_container_type VARCHAR(255) NOT NULL, btch_unit_weight VARCHAR(255) NOT NULL, btch_height_from VARCHAR(255) NOT NULL, btch_height_to VARCHAR(101) NOT NULL, btch_container_size VARCHAR(255) NOT NULL, btch_container_shape VARCHAR(255) NOT NULL, btch_container_contents VARCHAR(255) NOT NULL, btch_container_diameter VARCHAR(255) NOT NULL, chn_price_retail DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE crontask CHANGE cron_start cron_start DATETIME NOT NULL, CHANGE cron_end cron_end DATETIME NOT NULL, CHANGE cron_duration cron_duration VARCHAR(255) NOT NULL, CHANGE last_status last_status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE market_product');
        $this->addSql('ALTER TABLE crontask CHANGE cron_start cron_start DATETIME DEFAULT NULL, CHANGE cron_end cron_end DATETIME DEFAULT NULL, CHANGE cron_duration cron_duration VARCHAR(47) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_status last_status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
