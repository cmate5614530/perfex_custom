<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    '1',
    'id',
    'default_category_enable',
    'vehicle_make',
    'vehicle_model',
    'group_id',
    'number_of_passengers',
    'number_of_suitcases',
    'extra_time_enable',
    'extra_time_min',
    'extra_time_max',
    'extra_time_step',
    'base_location',
    'distance_pl',
    'distance_dol',
    'service_pl',
    'service_dol',
    'driver_lang',
    'meet_included_enable',
    'vehicle_availability_enable',
    'wse',
    'ers',
    'cts',
    'areas',
    'bookings_interval',
    'price_type_variable',
    'net_prices',
    'bus_prices',
    'google_calendar_enable',
    'google_calendar_settings',
    'google_calendar_id',
    'pr_dates',
    'pr_dates_acp',
    'pr_hours',
    'pr_hours_acp',
    'pr_distance',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'items';



$where = [];


$join =[];


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'vehicle_make', 
    'vehicle_model' ,
    'base_location' ,  
    ]);


$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    //9.10 AG: added to filter the item rows by id
    if($user_type == 'supplier')
    {
        $id = $aRow['id'];
        $flag = false;
        for($i = 0; $i < count($items); $i++)
        {
            if($id == $items[$i]['items']){
                $flag = true;
               break;
            }
        }
        if($flag == false)
            continue;
    }
    
    //9.10 AG: end

     $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $flag = true;
        /*get commodity file*/
        if ($aColumns[$i] == 'id') {
            $arr_images = $this->ci->purchase_model->get_item_attachments($aRow['id']);
            if(count($arr_images) > 0){
                if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name'])){
                    $_data = '<img class="images_w_table" src="' . site_url('modules/purchase/uploads/item_img/' . $arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name']).'" alt="'.$arr_images[0]['file_name'] .'" >';
                }else{
                    $_data = '<img class="images_w_table" src="' . site_url('modules/warehouse/uploads/item_img/' . $arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name']).'" alt="'.$arr_images[0]['file_name'] .'" >';
                }

            }else{

                $_data = '<img class="images_w_table" src="' . site_url('modules/purchase/uploads/nul_image.jpg' ).'" alt="nul_image.jpg">';
            }
        }


        else if($aColumns[$i] == 'vehicle_make') {
             $code = '<a href="#" onclick="init_commodity_detail('.$aRow['id'].'); return false;">' . $aRow['vehicle_make'] . '</a>';
              $code .= '<div class="row-options">';

            $code .= '<a href="#" onclick="init_commodity_detail('.$aRow['id'].'); return false;">' . _l('view') . '</a>';
            //9.9 AG: removed permission checking
            //if (has_permission('purchase', '', 'edit') || is_admin()) {
            {
                $code .= ' | <a href="#" onclick="edit_commodity_item(this); return false;"  data-commodity_id="'.$aRow['id'].
                '" data-vehicle_make="'.$aRow['vehicle_make'].'" data-vehicle_model="'.$aRow['vehicle_model'].
                '" data-number_of_suitcases="'.$aRow['number_of_suitcases'].
                '" data-default_category_enable="'.$aRow['default_category_enable'].
                '" data-extra_time_min="'.$aRow['extra_time_min'].
                '" data-extra_time_max="'.$aRow['extra_time_max'].
                '" data-extra_time_step="'.$aRow['extra_time_step'].
                '" data-number_of_passengers="'.$aRow['number_of_passengers'].
                '" data-extra_time_enable="'.$aRow['extra_time_enable'].
                '" data-base_location="'.$aRow['base_location'].
                '" data-service_pl="'.$aRow['service_pl'].
                '" data-service_dol="'.$aRow['service_dol'].
                '" data-distance_pl="'.$aRow['distance_pl'].
                '" data-distance_dol="'.$aRow['distance_dol'].
                '" data-base_location="'.$aRow['base_location'].
                '" data-net_prices="'.$aRow['net_prices'].
                '" data-bus_prices="'.$aRow['bus_prices'].
                '" data-meet_included_enable="'.$aRow['meet_included_enable'].
                '" data-driver_lang="'.$aRow['driver_lang'].
                '" data-vehicle_availability_enable="'.$aRow['vehicle_availability_enable'].
                '" data-wse="'.$aRow['wse'].
                '" data-ers="'.$aRow['ers'].
                '" data-cts="'.$aRow['cts'].
                '" data-areas="'.$aRow['areas'].
                '" data-google_calendar_settings="'.$aRow['google_calendar_settings'].
                '" data-google_calendar_enable="'.$aRow['google_calendar_enable'].
                '" data-google_calendar_id="'.$aRow['google_calendar_id'].
                '" data-pr_dates="'.$aRow['pr_dates'].
                '" data-pr_dates_acp="'.$aRow['pr_dates_acp'].
                '" data-pr_hours="'.$aRow['pr_hours'].
                '" data-pr_hours_acp="'.$aRow['pr_hours_acp'].
                '" data-pr_distance="'.$aRow['pr_distance'].
                '" data-price_type_variable="'.$aRow['price_type_variable'].'" >' . _l('edit') . '</a>';
            }
            //9.9 AG: removed permission checking
            //if (has_permission('purchase', '', 'edit') || is_admin()) {
            {
                //9.10 Ag: added checking admin to determine url
                if(is_admin())
                    $code .= ' | <a href="' . admin_url('purchase/delete_commodity/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                else 
                    $code .= ' | <a href="' . site_url('purchase/vendors_portal/delete_commodity/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $code .= '</div>';
            $_data = $code;

        }elseif ($aColumns[$i] == 'vehicle_model' || $aColumns[$i] == 'number_of_passengers'||
                $aColumns[$i] == 'base_location') {
            $_data = $aRow[ $aColumns[$i] ];
        }elseif ($aColumns[$i] == 'extra_time_enable' || $aColumns[$i] == 'vehicle_availability_enable') {
            $_data = $aRow[ $aColumns[$i] ];
            if($_data == "true")
                $_data = "<span class='fa fa-check text-primary'> </span>";
            else $_data = "<span class='fa fa-close text-danger'> </span>";
        }elseif ($aColumns[$i] == 'price_type_variable') {
            $_data = $aRow['price_type_variable'];
            if($_data == "true")
                $_data = "Variable";
            else $_data = "Fixed";
        }elseif ($aColumns[$i] == 'group_id') {
            if($aRow['group_id'] != null){
                $_data = get_group_name_item($aRow['group_id']) != null ? get_group_name_item($aRow['group_id'])->name : '';
            }else{
                $_data = '';
            }
        }elseif($aColumns[$i] == '1'){
            $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        } else{
            $flag = false;
        }
        if($flag == true)
          $row[] = $_data;
        
    }
    $output['aaData'][] = $row;
}

