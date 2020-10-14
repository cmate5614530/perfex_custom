<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix().'requests.id',
    'company',
    'pickup',
    'dropoff',
    'pickup_dt', 
    'passengers',
    'name',
    'status',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'requests';
$join         = [ 'LEFT JOIN '.db_prefix().'clients ON '.db_prefix().'requests.client_id = '.db_prefix().'clients.userid' ,
                'LEFT JOIN '.db_prefix().'items_groups ON '.db_prefix().'requests.vehicle_type = '.db_prefix().'items_groups.id'];
$where = [];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['pickup','dropoff']);

$output  = $result['output'];
$rResult = $result['rResult'];

$CI          = & get_instance();
//acer_log('table_pur_request.php: rResult');
//acer_log($rResult);
foreach ($rResult as $aRow) {
    $row = [];
    $status = '';
    $offers_count = 0;
    for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i] == 'request_date')
        {
            $row[] = $_data;       
        } else if($aColumns[$i] == 'status')
        {
            $status = $aRow['status'];
            //get offers count
            $CI->db->select('*');
            $CI->db->where('request_id', $request_id);
            $offers_count = $CI->db->count_all_results(db_prefix().'offers');
            //update status
            if($status == 'active')
            {
                $CI->db->select('*');
                $CI->db->where('request_id', $request_id);
                $CI->db->where('status', 'accepted');
                $accepted_count = $CI->db->count_all_results(db_prefix().'offers');
                if($accepted_count > 0) $status = 'accepted';
            }

            if($status == 'active')
            {
                $row[] = "<span class='btn btn-primary' style='border-radius:15px' >"._l('Active')." </span>";  
            }
            if($status == 'accepted')
            {
                $row[] = "<span class='btn btn-success' style='border-radius:15px' > <i class='fa fa-check'> </i>"._l('Accepted'). 
                    " </span>";  
            }
        } else {
            $row[] = $_data;
        }
            
        /*
        if($aColumns[$i] == 'request_date'){
            $_data = _dt($aRow['request_date']);
        }elseif($aColumns[$i] == 'requester'){
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['requester']) . '">' . staff_profile_image($aRow['requester'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['requester']) . '">' . get_staff_full_name($aRow['requester']) . '</a>';
        }elseif($aColumns[$i] == 'department'){
            $_data = $aRow['name'];
        }elseif ($aColumns[$i] == 'status') {
            $_data = get_status_approve($aRow['status']);
        }elseif($aColumns[$i] == 'pur_rq_name'){
            $name = '<a href="' . admin_url('purchase/view_pur_request/' . $aRow['id'] ).'">'.$aRow['pur_rq_code'].' - ' . $aRow['pur_rq_name'] . '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('purchase/view_pur_request/' . $aRow['id'] ).'" >' . _l('view') . '</a>';

            if ( (has_permission('recruitment', '', 'edit') || is_admin()) &&  $aRow['status'] != 2) {
                $name .= ' | <a href="' . admin_url('purchase/pur_request/' . $aRow['id'] ).'" >' . _l('edit') . '</a>';
            }

            if (has_permission('recruitment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('purchase/delete_pur_request/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        }elseif($aColumns[$i] == 'id'){
            if($aRow['status'] == 2){
                $_data = '<div class="btn-group mright5" data-toggle="tooltip" title="'._l('request_quotation_tooltip').'">
                           <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><i class="fa fa-file-pdf-o"></i><span class="caret"></span></a>
                           <ul class="dropdown-menu dropdown-menu-right">
                              <li class="hidden-xs"><a href="'. admin_url('purchase/request_quotation_pdf/'.$aRow['id'].'?output_type=I').'">'. _l('view_pdf').'</a></li>
                              <li class="hidden-xs"><a href="'. admin_url('purchase/request_quotation_pdf/'.$aRow['id'].'?output_type=I').'" target="_blank">'. _l('view_pdf_in_new_window').'</a></li>
                              <li><a href="'.admin_url('purchase/request_quotation_pdf/'.$aRow['id']).'">'. _l('download').'</a></li>
                           </ul>
                           </div>';

                $_data .= '<a href="#" onclick="send_request_quotation('.$aRow['id'].'); return false;" class="btn btn-success" ><i class="fa fa-envelope" data-toggle="tooltip" title="'. _l('request_quotation') .'"></i></a>';

            }else{
                $_data = '';
            }
        }
        */

        
    }

    //9.28 AG: add 'View Offers' column
    $request_id  = $aRow[db_prefix().'requests.id'];
    $url = admin_url('purchase/offers/'.$request_id);
    $action  = "<a href='$url' class='btn btn-success' style='border-radius: 15px;'>".
            _l('View_Offers')."</a>";
    if($offers_count != 0) 
    { 
        $action = $action. "<span class='badge btn-danger'".
        "style='background-color: #fc2d42; color: white; margin-left: -15px; margin-top: -30px;'>".
        $offers_count."</span>";
    }
    $row[] = $action;
    //9.28 AG: end
    $output['aaData'][] = $row;

}
