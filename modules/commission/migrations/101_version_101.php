<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();          

        if (!$CI->db->field_exists('clients', db_prefix() . 'commission_policy')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . "commission_policy`
            ADD COLUMN `clients` TEXT NULL,
            ADD COLUMN `client_groups` TEXT NULL
          ;");
        }
     }
}
