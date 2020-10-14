<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        if (!$CI->db->field_exists('description' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    ADD COLUMN `description` TEXT NULL AFTER `item_code`
		  ;");
		}
    }
}
