<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'contract_name',
    db_prefix().'pur_contracts.vendor',
    'pur_order',
    'contract_value',
    'start_date',
    'end_date', 
    'add_from',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'pur_contracts';
$join         = ['LEFT JOIN '.db_prefix().'pur_orders ON '.db_prefix().'pur_orders.id = '.db_prefix().'pur_contracts.pur_order'];
$where = [];

if(isset($vendor)){
    array_push($where, ' AND '.db_prefix().'pur_contracts.vendor = '.$vendor);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'pur_contracts.id as contract_id','contract_number','pur_order_number','pur_order_name']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i] == db_prefix().'pur_contracts.vendor'){
            $ven = get_vendor_company_name($aRow[db_prefix().'pur_contracts.vendor']);
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow[db_prefix().'pur_contracts.vendor']) . '" >' .  $ven . '</a>';
        }elseif($aColumns[$i] == 'contract_name'){
            $numberOutput = '';
            $numberOutput = '<a href="' . admin_url('purchase/contract/' . $aRow['contract_id']) . '">' . $aRow['contract_number'].' - '. $aRow['contract_name'] . '</a>';
    
            $numberOutput .= '<div class="row-options">';

            if (has_permission('purchase', '', 'view')) {
                $numberOutput .= ' <a href="' . admin_url('purchase/contract/' . $aRow['contract_id']) . '" >' . _l('view') . '</a>';
            }
            if (has_permission('purchase', '', 'edit')) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/contract/' . $aRow['contract_id']) . '">' . _l('edit') . '</a>';
            }
            if (has_permission('purchase', '', 'delete')) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/delete_contract/' . $aRow['contract_id']) . '" class="text-danger">' . _l('delete') . '</a>';
            }
            $numberOutput .= '</div>';

            $_data = $numberOutput;

        }elseif($aColumns[$i] == 'start_date'){
            $_data = _d($aRow['start_date']);
        }elseif($aColumns[$i] == 'end_date'){
            $_data = _d($aRow['end_date']);
        }elseif($aColumns[$i] == 'add_from'){
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['add_from']) . '">' . staff_profile_image($aRow['add_from'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['add_from']) . '">' . get_staff_full_name($aRow['add_from']) . '</a>';
        }elseif($aColumns[$i] == 'contract_value'){
            $_data = app_format_money($aRow['contract_value'],'');
        }elseif($aColumns[$i] == 'pur_order'){
            $_data = '<a href="' . admin_url('purchase/purchase_order/' . $aRow['pur_order']) . '">' . $aRow['pur_order_number'].' - '. $aRow['pur_order_name'] . '</a>';
        }
    
        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
