<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Commission
Description: Set up a commission program so staffs can earn money promoting products.
Version: 1.0.4
Requires at least: 2.3.*
Author: GreenTech_Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
 */

define('COMMISSION_MODULE_NAME', 'commission');
define('COMMISSION_REVISION', 104);
hooks()->add_action('admin_init', 'commission_module_init_menu_items');
hooks()->add_action('admin_init', 'commission_permissions');
hooks()->add_action('app_admin_head', 'commission_add_head_components');
hooks()->add_action('app_admin_footer', 'commission_add_footer_components');
hooks()->add_filter('get_dashboard_widgets', 'commission_add_dashboard_widget');
hooks()->add_filter('after_payment_added', 'add_commission');
hooks()->add_filter('before_invoice_deleted', 'delete_commission');
hooks()->add_action('customers_navigation_end', 'commission_module_init_client_menu_items');
hooks()->add_action('app_customers_footer', 'commission_client_add_footer_components');
hooks()->add_action('after_customer_admins_tab', 'add_tab_commission_in_client');
hooks()->add_action('after_custom_profile_tab_content', 'add_content_commission_in_client');

/**
 * Register activation module hook
 */
register_activation_hook(COMMISSION_MODULE_NAME, 'commission_module_activation_hook');

$CI = &get_instance();
$CI->load->helper(COMMISSION_MODULE_NAME . '/commission');

/**
 * commission add head components
 * @return
 */
function commission_add_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/commission/new_commission_policy') === false)) {
		echo '<link href="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/update_commission_policy') === false)) {
		echo '<link href="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/commission_policy') === false)) {
		echo '<link href="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/css/commission_policy.css') . '?v=' . COMMISSION_REVISION.'"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * commission add footer components
 * @return
 */
function commission_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, '/admin/commission/new_commission_policy') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';

	}

	if (!(strpos($viewuri, '/admin/commission/update_commission_policy') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/manage_commission') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/applicable_staff') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/manage_applicable_staff.js') . '?v=' . COMMISSION_REVISION.'"></script>';
	}
	if (!(strpos($viewuri, '/admin/commission/applicable_client') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/manage_applicable_staff.js') . '?v=' . COMMISSION_REVISION.'"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/commission_policy') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/manage_commission_policy.js') . '?v=' . COMMISSION_REVISION.'"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/setting') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/manage_setting.js') . '?v=' . COMMISSION_REVISION.'"></script>';
	}

	if (!(strpos($viewuri, '/admin/commission/manage_commission_receipt') === false)) {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/manage_receipt.js') . '?v=' . COMMISSION_REVISION.'"></script>';
	}

	if ($viewuri == '/admin' || $viewuri == '/admin/') {
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/js/commission_dashboard.js') . '?v=' . COMMISSION_REVISION.'"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
}

/**
 * commission module activation_hook
 * @return
 */
function commission_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(COMMISSION_MODULE_NAME, [COMMISSION_MODULE_NAME]);

/**
 * Init commission module menu items in setup in admin_init hook
 * @return null
 */
function commission_module_init_menu_items() {
	$CI = &get_instance();
	if (has_permission('commission', '', 'view') || has_permission('commission', '', 'view_own') || has_permission('commission_applicable_staff', '', 'view') || has_permission('commission_policy', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('commission', [
			'name' => _l('commission'),
			'icon' => 'fa fa-money',
			'position' => 30,
		]);
		if (has_permission('commission', '', 'view') || has_permission('commission', '', 'view_own')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'manage-commission',
				'name' => _l('statistics'),
				'icon' => 'fa fa-bar-chart',
				'href' => admin_url('commission/manage_commission'),
				'position' => 1,
			]);
		}
		
		if (has_permission('commission_receipt', '', 'view') || has_permission('commission', '', 'view_receipt')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'commission-receipt',
				'name' => _l('commission_receipt'),
				'icon' => 'fa fa-money',
				'href' => admin_url('commission/manage_commission_receipt'),
				'position' => 2,
			]);
		}

		if (has_permission('commission_applicable_staff', '', 'view') || has_permission('commission', '', 'view_applicable_staff')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'commission-applicable-staff',
				'name' => _l('applicable_staff'),
				'icon' => 'fa fa-users',
				'href' => admin_url('commission/applicable_staff'),
				'position' => 3,
			]);
		}

		if (has_permission('commission_applicable_staff', '', 'view') || has_permission('commission', '', 'view_applicable_client')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'commission-applicable-client',
				'name' => _l('applicable_client'),
				'icon' => 'fa fa-users',
				'href' => admin_url('commission/applicable_client'),
				'position' => 4,
			]);
		}

		if (has_permission('commission_policy', '', 'view') || has_permission('commission', '', 'view_program')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'commission-policy',
				'name' => _l('commission_policy'),
				'icon' => 'fa fa-clipboard',
				'href' => admin_url('commission/commission_policy'),
				'position' => 5,
			]);
		}

		if (has_permission('commission_setting', '', 'view') || has_permission('commission', '', 'view_setting')) {
			$CI->app_menu->add_sidebar_children_item('commission', [
				'slug' => 'commission-setting',
				'name' => _l('settings'),
				'icon' => 'fa fa-cog',
				'href' => admin_url('commission/setting'),
				'position' => 6,
			]);
		}
	}
}

/**
 * Init commission module permissions in setup in admin_init hook
 */
function commission_permissions() {

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view_own'   => _l('permission_view'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
	];
	register_staff_capabilities('commission', $capabilities, _l('commission'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view'   => _l('permission_view'),
		'create' => _l('permission_create'),
        'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('commission_receipt', $capabilities, _l('commission_receipt'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view'   => _l('permission_view'),
		'create' => _l('permission_create'),
        'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('commission_applicable_staff', $capabilities, _l('applicable_staff'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view'   => _l('permission_view'),
		'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('commission_policy', $capabilities, _l('commission_policy'));
}

/**
 * commission add dashboard widget
 * @param  object $widgets
 * @return object
 */
function commission_add_dashboard_widget($widgets) {
	$widgets[] = [
		'path' => 'commission/dashboard/commission',
		'container' => 'left-8',
	];
	return $widgets;
}

/**
 * add commission
 * @param integer $payment_id
 */
function add_commission($payment_id) {
	add_commission_staff($payment_id);
	return $payment_id;
}

/**
 * add commission
 * @param integer $payment_id
 */
function delete_commission($invoice_id) {
	delete_commission_staff($invoice_id);
	return $invoice_id;
}

/**
 * Init commission module menu items in setup in customers_navigation_end hook
 */
function commission_module_init_client_menu_items()
{
    if(check_applicable_client(get_client_user_id())){
	    $menu = '';
	    if (is_client_logged_in()) {
	        $menu .= '<li class="customers-nav-item-commission">
	                  <a href="'.site_url('commission/commission_client').'">
	                    <i class=""></i> '
	                    . _l('commission').'
	                  </a>
	               </li>';
	    }
    	echo html_entity_decode($menu);
    }
}


function commission_client_add_footer_components(){
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if(!(strpos($viewuri, '/commission/commission_client') === false)){
        echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(COMMISSION_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';  
    }
}

function add_tab_commission_in_client($client){
    $CI = &get_instance();
    if(check_applicable_client($client->userid)){
	    $menu = '';
        $menu .= '<li role="presentation">
                  <a href="#customer_commission" aria-controls="customer_commission" role="tab" data-toggle="tab">
                    <i class=""></i> '
                    . _l('commission').'
                  </a>
               </li>';
    	echo html_entity_decode($menu);
    }
}

function add_content_commission_in_client($client){
    $CI = &get_instance();
	if(check_applicable_client($client->userid)){
		require 'modules/commission/views/client/commission_tab.php';
    }
}