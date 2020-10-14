<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Perfex CRM Custom JS Module
Description: The easiest way to implement own javascript
Version: 1.0.0
Requires at least: 2.3.4
*/

/**
 * Hooks init
 */
hooks()->add_action('app_admin_footer', 'inject_custom_js');
hooks()->add_action('app_admin_head', 'customjs_before_admin_render');
hooks()->add_action('app_customers_head', 'customjs_before_customer_render');
if (is_admin()) {
    hooks()->add_action('admin_init', 'customjs_add_settings_tab');
}

/**
 * Add scripts to admin area before render
 */
function customjs_before_admin_render()
{
    $CI =& get_instance();
    $CI->db->where('name', 'custom_js_admin_scripts');
    $js_data = $CI->db->get(db_prefix().'options')->row('value');
    if ($js_data) {
        $encoded_js_data = json_encode($js_data, true);
        echo json_decode($encoded_js_data);
    }
}
/**
 * Add scripts to customer area before render
 */
function customjs_before_customer_render()
{
    $CI =& get_instance();
    $CI->db->where('name', 'custom_js_customer_scripts');
    $js_data = $CI->db->get(db_prefix().'options')->row('value');
    if ($js_data) {
        $encoded_js_data = json_encode($js_data, true);
        echo json_decode($encoded_js_data);
    }
}
/**
 * Get scripts to admin area before render
 */
function get_custom_js_admin_active_data()
{
    $CI =& get_instance();
    $CI->db->where('name', 'custom_js_admin_scripts');
    $result =  $CI->db->get(db_prefix().'options')->row('value');
    $result = trim($result);

    if ($result == '') {
        return '';
    }
    return $result;
}
/**
 * Get scripts to customer area before render
 */
function get_custom_js_customer_active_data()
{
    $CI =& get_instance();
    $CI->db->where('name', 'custom_js_customer_scripts');
    $result =  $CI->db->get(db_prefix().'options')->row('value');
    $result = trim($result);

    if ($result == '') {
        return '';
    }
    return $result;
}
/**
 * Add new CustomJS settings tab in settings area
 */
function customjs_add_settings_tab()
{
    $CI = & get_instance();
    $CI->app_tabs->add_settings_tab('customjs-settings', [
        'name'     => 'Custom JavaScript',
        'view'     => 'customjs/custom_js_view',
        'position' => 37,
    ]);
}
/**
 * Injects the module javascript
 */
function inject_custom_js()
{
    echo '<script src="'.module_dir_url(CUSTOM_JS_MODULE, 'assets/js/custom_javascript.js').'"></script>';
    //9.2 AG: select min js
    echo '<script src="'.base_url().'assets/plugins/select2/js/select2.min.js"></script>';
    
}
