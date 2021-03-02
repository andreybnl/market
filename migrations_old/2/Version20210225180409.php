<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225180409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
       // $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT DEFAULT NULL');
       // $this->addSql('ALTER TABLE market_product CHANGE edit_time edit_time VARCHAR(255) NOT NULL, CHANGE range_identifier range_identifier VARCHAR(255) NOT NULL, CHANGE core_identifier core_identifier VARCHAR(255) NOT NULL, CHANGE _retail_hash _retail_hash VARCHAR(255) NOT NULL, CHANGE _batch_hash_array _batch_hash_array VARCHAR(255) NOT NULL, CHANGE _batch_hash _batch_hash VARCHAR(255) NOT NULL, CHANGE _core_hash _core_hash VARCHAR(255) NOT NULL, CHANGE _product_range_hash _product_range_hash VARCHAR(255) NOT NULL, CHANGE _product_group_hash _product_group_hash VARCHAR(255) NOT NULL, CHANGE _product_core_hash _product_core_hash VARCHAR(255) NOT NULL, CHANGE name_search name_search VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE btch_stock btch_stock VARCHAR(255) NOT NULL, CHANGE rtl_size_code rtl_size_code VARCHAR(255) NOT NULL, CHANGE batch_id_original batch_id_original INT NOT NULL, CHANGE btch_stock_total btch_stock_total VARCHAR(255) NOT NULL, CHANGE btch_container_type btch_container_type VARCHAR(255) NOT NULL, CHANGE btch_unit_weight btch_unit_weight VARCHAR(255) NOT NULL, CHANGE btch_height_from btch_height_from VARCHAR(255) NOT NULL, CHANGE btch_height_to btch_height_to VARCHAR(101) NOT NULL, CHANGE btch_container_size btch_container_size VARCHAR(255) NOT NULL, CHANGE btch_container_shape btch_container_shape VARCHAR(255) NOT NULL, CHANGE btch_container_contents btch_container_contents VARCHAR(255) NOT NULL, CHANGE btch_container_diameter btch_container_diameter VARCHAR(255) NOT NULL, CHANGE chn_price_retail chn_price_retail DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE market_query ADD request_type VARCHAR(40) DEFAULT NULL, ADD supplier VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
       // $this->addSql('ALTER TABLE manual_log CHANGE answer answer MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
       // $this->addSql('ALTER TABLE market_product CHANGE edit_time edit_time VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE range_identifier range_identifier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE core_identifier core_identifier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _retail_hash _retail_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _batch_hash_array _batch_hash_array VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _batch_hash _batch_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _core_hash _core_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _product_range_hash _product_range_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _product_group_hash _product_group_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE _product_core_hash _product_core_hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_search name_search VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_stock btch_stock VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rtl_size_code rtl_size_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE batch_id_original batch_id_original INT DEFAULT NULL, CHANGE btch_stock_total btch_stock_total VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_container_type btch_container_type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_unit_weight btch_unit_weight VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_height_from btch_height_from VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_height_to btch_height_to VARCHAR(101) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_container_size btch_container_size VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_container_shape btch_container_shape VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_container_contents btch_container_contents VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE btch_container_diameter btch_container_diameter VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE chn_price_retail chn_price_retail DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE market_query DROP request_type, DROP supplier');
    }
}
