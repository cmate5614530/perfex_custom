<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        //purchase request detail
        if ($CI->db->field_exists('unit_price' ,db_prefix() . 'pur_request_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_request_detail`
		    CHANGE COLUMN `unit_price` `unit_price` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('into_money' ,db_prefix() . 'pur_request_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_request_detail`
		    CHANGE COLUMN `into_money` `into_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		//purchase order detail
		if ($CI->db->field_exists('unit_price' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `unit_price` `unit_price` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('into_money' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `into_money` `into_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('total' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `total` `total` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('discount_%' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `discount_%` `discount_%` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('discount_money' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `discount_money` `discount_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('total_money' ,db_prefix() . 'pur_order_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_order_detail`
		    CHANGE COLUMN `total_money` `total_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		//pur estimate detail
		if ($CI->db->field_exists('unit_price' ,db_prefix() . 'pur_contracts')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `unit_price` `unit_price` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('into_money' ,db_prefix() . 'pur_estimate_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `into_money` `into_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('total' ,db_prefix() . 'pur_estimate_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `total` `total` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('total_money' ,db_prefix() . 'pur_estimate_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `total_money` `total_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('discount_money' ,db_prefix() . 'pur_estimate_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `discount_money` `discount_money` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		if ($CI->db->field_exists('discount_%' ,db_prefix() . 'pur_estimate_detail')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_estimate_detail`
		    CHANGE COLUMN `discount_%` `discount_%` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}

		//pur contract
		if ($CI->db->field_exists('contract_value' ,db_prefix() . 'pur_contracts')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_contracts`
		    CHANGE COLUMN `contract_value` `contract_value` DECIMAL(15,2) NULL DEFAULT NULL
		  ;");
		}
		
		// purchase request hash
		if (!$CI->db->field_exists('hash' ,db_prefix() . 'pur_request')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_request`
		    ADD COLUMN `hash` VARCHAR(32) NULL
		  ;");
		}

		// purchase order hash
		if (!$CI->db->field_exists('hash' ,db_prefix() . 'pur_orders')) { 
		  $CI->db->query('ALTER TABLE `' . db_prefix() . "pur_orders`
		    ADD COLUMN `hash` VARCHAR(32) NULL
		  ;");
		}
    }
}
