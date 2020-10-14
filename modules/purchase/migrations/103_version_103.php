<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();

        if (!$CI->db->table_exists(db_prefix() . 'pur_vendor_items')) {
		    $CI->db->query('CREATE TABLE `' . db_prefix() . "pur_vendor_items` (
		      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		      `vendor` int(11) NOT NULL,
		      `group_items` int(11) NULL,
		      `items` int(11) NOT NULL,
		      `add_from` int(11) NULL,
		      `datecreate` DATE NULL,
		      PRIMARY KEY (`id`)
		    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
		}
     }
}
