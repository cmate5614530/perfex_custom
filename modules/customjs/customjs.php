<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Perfex CRM Custom JS Module
Description: The easiest way to implement own javascript
Version: 1.0.0
Author: Aleksandar Stojanov
Author URI: https://aleksandarstojanov.com
Requires at least: 2.3.4
*/

define('CUSTOM_JS_MODULE', 'customjs');

$CI = &get_instance();

register_activation_hook(CUSTOM_JS_MODULE, 'customjs_activation_hook');

/**
 * The activation function
 */
function customjs_activation_hook()
{
    require(__DIR__ .'/init.php');
}
/**
 * Register helper init
 */
$CI->load->helper(CUSTOM_JS_MODULE.'/custom_js');
