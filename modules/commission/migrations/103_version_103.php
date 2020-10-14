<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();          
        if (!$CI->db->table_exists(db_prefix() . 'commission_hierarchy')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "commission_hierarchy` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `salesman` INT(11) NOT NULL,
                `coordinator` INT(11) NOT NULL,
                `percent` VARCHAR(45) NOT NULL,
                `addedfrom` INT(11) NULL,
                `datecreated` DATETIME NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'commission_salesadmin_group')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "commission_salesadmin_group` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `salesadmin` INT(11) NOT NULL,
                `customer_group` INT(11) NOT NULL,
                `addedfrom` INT(11) NULL,
                `datecreated` DATETIME NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->table_exists(db_prefix() . 'commission_receipt')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "commission_receipt` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `amount` DECIMAL(15,2) NOT NULL,
                `paymentmode` VARCHAR(40) NULL,
                `paymentmethod` VARCHAR(191) NULL,
                `date` DATE NOT NULL,
                `daterecorded` DATETIME NOT NULL,
                `note` TEXT NOT NULL,
                `transactionid` MEDIUMTEXT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->field_exists('addedfrom', db_prefix() . 'commission_receipt')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "commission_receipt`
            ADD COLUMN `addedfrom` INT(11) NULL;");
        }

        if (!$CI->db->field_exists('convert_expense', db_prefix() . 'commission_receipt')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "commission_receipt`
            ADD COLUMN `convert_expense` INT(11) NULL;");
        }

        if (!$CI->db->table_exists(db_prefix() . 'commission_receipt_detail')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . "commission_receipt_detail` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `receipt_id` INT(11) NOT NULL,
                `commission_id` INT(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
        }

        if (!$CI->db->field_exists('paid', db_prefix() . 'commission')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "commission`
            ADD COLUMN `paid` INT(11) NOT NULL DEFAULT '0';");
        }
    }
}
