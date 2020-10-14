<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();

        if (!$CI->db->field_exists('vendor', db_prefix() .'expenses')) {
            $CI->db->query('ALTER TABLE `'.db_prefix() . 'expenses` 
          ADD COLUMN `vendor` INT(11) NULL;');            
        }
     }
}
