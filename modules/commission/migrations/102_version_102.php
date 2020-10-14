<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();          
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
     }
}
