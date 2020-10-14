<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        
        if (row_purchase_options_exist('"pur_order_prefix"') == 0){
          $CI->db->query('INSERT INTO `tblpurchase_option` (`option_name`, `option_val`, `auto`) VALUES ("pur_order_prefix", "#PO", "1");
        ');
        }

        if (!$CI->db->field_exists('number', db_prefix() .'pur_orders')) {
            $CI->db->query('ALTER TABLE `'.db_prefix() . 'pur_orders` 
          ADD COLUMN `number` INT(11) NULL;');            
        }

        if (!$CI->db->field_exists('expense_convert', db_prefix() .'pur_orders')) {
            $CI->db->query('ALTER TABLE `'.db_prefix() . 'pur_orders` 
          ADD COLUMN `expense_convert` INT(11) NULL DEFAULT "0";');            
        }
     }
}
