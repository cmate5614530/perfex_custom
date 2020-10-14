<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'commission_policy')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "commission_policy` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `from_date` DATE NOT NULL,
        `to_date` DATE NULL,
        `percent_enjoyed` VARCHAR(45) NULL,
        `product_setting` LONGTEXT NULL,
        `ladder_setting` LONGTEXT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('datecreated', db_prefix() . 'commission_policy')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
    ADD COLUMN `datecreated` DATETIME NOT NULL
  ;");
}



if (!$CI->db->table_exists(db_prefix() . 'commission')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "commission` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `staffid` INT(11) NOT NULL,
        `invoice_id` INT(11) NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL,
        `date` DATE NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'list_widget')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "list_widget` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `add_from` INT(11) NOT NULL,
        `rel_id` INT(11) NULL,
        `rel_type` VARCHAR(45) NULL,
        `layout` VARCHAR(45) NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('commission_policy_type', db_prefix() . 'commission_policy')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
    ADD COLUMN `commission_policy_type` VARCHAR(45) NULL
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'applicable_staff')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "applicable_staff` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `commission_policy` INT(11) NOT NULL,
        `applicable_staff` LONGTEXT NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('name', db_prefix() . 'applicable_staff')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . "applicable_staff`
    ADD COLUMN `name` VARCHAR(255) NOT NULL,
    ADD COLUMN `datecreated` DATETIME NOT NULL,
    ADD COLUMN `addedfrom` INT(11) NOT NULL
  ;");
}

if (!$CI->db->field_exists('addedfrom', db_prefix() . 'commission_policy')) {
	$CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
    ADD COLUMN `addedfrom` INT(11) NOT NULL
  ;");
}

// Version 1.0.1
if (!$CI->db->field_exists('clients', db_prefix() . 'commission_policy')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
    ADD COLUMN `clients` TEXT NULL,
    ADD COLUMN `client_groups` TEXT NULL
  ;");
}

// Version 1.0.2
if (!$CI->db->field_exists('commmission_first_invoices', db_prefix() . 'commission_policy')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
    ADD COLUMN `commmission_first_invoices` INT(11) NOT NULL DEFAULT 0,
    ADD COLUMN `number_first_invoices` INT(11) NOT NULL DEFAULT 0,
    ADD COLUMN `percent_first_invoices` VARCHAR(45) NOT NULL DEFAULT 0;");
}

if (!$CI->db->field_exists('is_client', db_prefix() . 'applicable_staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "applicable_staff`
    ADD COLUMN `is_client` INT(11) NOT NULL DEFAULT 0;");
}

if (!$CI->db->field_exists('is_client', db_prefix() . 'commission')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "commission`
    ADD COLUMN `is_client` INT(11) NOT NULL DEFAULT 0;");
}

//Version 1.0.3

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
