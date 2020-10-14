<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Commission Controller
 */
class Commission extends AdminController
{
	/**
	 * manage commission
	 */
	public function manage_commission(){
		if (!has_permission('commission', '', 'view') && !has_permission('commission', '', 'view_own') && !is_admin() ) {
            access_denied('commission');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');
        $this->load->model('currencies_model');

		$data['title'] = _l('commission');
        $data['currency'] = $this->currencies_model->get_base_currency();
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['clients'] = $this->clients_model->get();
        $data['products'] = $this->commission_model->get_product_select();

        $this->load->view('manage_commission', $data);
	}

	/**
	 * commission policy
	 */
	public function commission_policy(){
		if (!has_permission('commission_policy', '', 'view') && !is_admin() ) {
            access_denied('commission_policy');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');
        $this->load->model('clients_model');
            
		$data['title'] = _l('commission_policy');
        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);

        $data['invoices'] = $this->commission_model->get_invoice_without_commission();
        $data['list_commission_policy'] = $this->commission_model->load_commission_policy();
        $this->load->view('manage_commission_policy', $data);
	}

    /**
     * manage applicable staff
     */
    public function applicable_staff(){
        if (!has_permission('commission_applicable_staff', '', 'view') && !is_admin() ) {
            access_denied('applicable_staff');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');

        $data['is_client'] = 0;
        $data['title'] = _l('applicable_staff');
        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['list_applicable_staff'] = $this->commission_model->load_applicable_staff();
        $this->load->view('manage_applicable_staff', $data);
    }

	/**
	 * new commission policy
	 */
	public function new_commission_policy(){
		if (!has_permission('commission_policy', '', 'create') && !is_admin() ) {
            access_denied('commission_policy');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $data                = $this->input->post();
            if (!has_permission('commission_policy', '', 'create')) {
                access_denied('commission_policy');
            }
            $success = $this->commission_model->add_commission_policy($data);
            if ($success) {
                set_alert('success', _l('added_successfully', _l('commission_policy')));
            }
            redirect(admin_url('commission/commission_policy'));
        }
		$data['title'] = _l('commission_policy');
        $data['clients'] = $this->commission_model->get_customer();
        $data['client_groups'] = $this->clients_model->get_groups();
        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['products'] = $this->commission_model->get_product_select();
        $data['product_groups'] = $this->commission_model->get_product_group_select();

        $this->load->view('commission_policy', $data);
	}

	/**
	 * update commission policy
	 */
	public function update_commission_policy($id){
		if (!has_permission('commission_policy', '', 'edit') && !is_admin() ) {
            access_denied('commission_policy');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $data                = $this->input->post();
            if (!has_permission('commission_policy', '', 'edit')) {
                access_denied('commission_policy');
            }
            $success = $this->commission_model->update_commission_policy($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('commission_policy')));
            }
            redirect(admin_url('commission/commission_policy'));
        }
		$data['title'] = _l('commission_policy');

		$data['commission_policy'] = $this->commission_model->load_commission_policy($id);
        $data['clients'] = $this->commission_model->get_customer();
        $data['client_groups'] = $this->clients_model->get_groups();
        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['products'] = $this->commission_model->get_product_select();
        $data['product_groups'] = $this->commission_model->get_product_group_select();

        $this->load->view('commission_policy', $data);
	}

    /**
     * delete commission policy
     *
     * @param      <type>  $id     The identifier
     */
    public function delete_commission_policy($id){

        if (!has_permission('commission_policy', '', 'delete')) {
            access_denied('commission_policy');
        }
        if (!$id) {
            redirect(admin_url('commission/commission_policy'));
        }
        $this->load->model('commission_model');
        $success = $this->commission_model->delete_commission_policy($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('commission_policy')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('commission_policy')));
        }
        redirect(admin_url('commission/commission_policy'));
    }

    /**
     * new applicable staff
     */
    public function new_applicable_staff(){
        if (!has_permission('commission_applicable_staff', '', 'create') && !is_admin() ) {
            access_denied('applicable_staff');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $data                = $this->input->post();
            if (!has_permission('commission_applicable_staff', '', 'create')) {
                access_denied('applicable_staff');
            }
            $success = $this->commission_model->add_applicable_staff($data);
            if ($success) {
                set_alert('success', _l('added_successfully', _l('applicable_staff')));
            }
            redirect(admin_url('commission/applicable_staff'));
        }
        $data['title'] = _l('applicable_staff');
        $data['commission_policy'] = $this->commission_model->load_commission_policy();

        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['is_client'] = 0;
        $list_staff = [];
        foreach ($data['staffs'] as $key => $value) {
            $list_staff[$value['staffid']] = trim($value['firstname'] .' '.$value['lastname']);
        }

        $data['list_staff_json'] = json_encode($list_staff);
        $this->load->view('applicable_staff', $data);
    }

    /**
     * delete applicable staff
     *
     * @param      <type>  $id     The identifier
     */
    public function delete_applicable_staff($id){

        if (!has_permission('commission_applicable_staff', '', 'delete')) {
            access_denied('applicable_staff');
        }
        if (!$id) {
            redirect(admin_url('commission/applicable_staff'));
        }
        $this->load->model('commission_model');
        $success = $this->commission_model->delete_applicable_staff($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('applicable_staff')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('applicable_staff')));
        }
        redirect(admin_url('commission/applicable_staff'));
    }

    /**
     *  commission table
     *  
     *  @return json
     */
    public function commission_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('commission_model');

            $select = [
                'invoice_id',
                db_prefix() . 'commission.date as commission_date',
                get_sql_select_client_company(),
                'staffid',
                'total',
                'amount',
                'paid',
            ];
            $where = [];
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'commission.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('is_client')) {
                $is_client  = $this->input->post('is_client');
                if ($this->input->post('client_filter')) {
                    $client_filter  = $this->input->post('client_filter');
                    array_push($where, 'AND staffid IN (' . implode(', ', $client_filter) . ')');
                }
            }else{
                if ($this->input->post('staff_filter')) {
                    $staff_filter  = $this->input->post('staff_filter');
                    array_push($where, 'AND staffid IN (' . implode(', ', $staff_filter) . ')');
                }
                $is_client = 0;
            }
            array_push($where, 'AND is_client = '.$is_client);

            if ($this->input->post('products_services')) {
                $products_services  = $this->input->post('products_services');
                $where_item = '';
                if ($products_services != '') {
                    foreach ($products_services as $key => $value) {
                        $item_name = $this->commission_model->get_item_name($value);
                        if ($where_item == '') {
                            $where_item .= '(select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
                        } else {
                            $where_item .= ' or (select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
                        }
                    }
                }

                if ($where_item != '') {
                    array_push($where, 'AND ' .$where_item);
                }
            }

            if ($this->input->post('status')) {
                $statuss  = $this->input->post('status');
                $where_status = '';
                if ($statuss != '') {
                    foreach ($statuss as $key => $value) {
                        if($value == 2){
                            $value = 0;
                        }
                        if ($where_status == '') {
                            $where_status .= 'paid = '. $value;
                        } else {
                            $where_status .= ' or paid = '. $value;
                        }
                    }
                }

                if ($where_status != '') {
                    array_push($where, 'AND (' .$where_status.')');
                }
            }
            

            if (!has_permission('commission','','view')) {
                array_push($where, 'AND staffid = '. get_staff_user_id());
            }

            array_push($where, 'AND staffid != 0');

            $currency = $this->currencies_model->get_base_currency();
            $aColumns     = $select;
            $sIndexColumn = 'invoice_id';
            $sTable       = db_prefix() . 'commission';
            $join         = ['LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'commission.invoice_id',
        'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[db_prefix() . 'invoices.clientid', 'is_client']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'           => 0,
                'total_commission'           => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];

                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice_id']) . '" target="_blank">' . format_invoice_number($aRow['invoice_id']) . '</a>';

                $row[] = $_data;

                $row[] = _d($aRow['commission_date']);
                $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . $aRow['company'] . '</a>';

                if($aRow['is_client'] == 1){
                    $link = admin_url('clients/client/'.$aRow['staffid']);
                    $_data = ' <a href="' . $link . '" target="_blank">' . get_company_name($aRow['staffid'])  . '</a>';
                }else{
                    $_data = '<a href="' . admin_url('staff/profile/' . $aRow['staffid']) . '" target="_blank">' . staff_profile_image($aRow['staffid'], [
                    'staff-profile-image-small',
                    ]) . '</a>';
                    $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['staffid']) . '" target="_blank">' . get_staff_full_name($aRow['staffid'])  . '</a>';
                }

                $row[] = $_data;

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['amount'], $currency->name);
                $footer_data['total_commission'] += $aRow['amount'];
               
                if($aRow['paid'] == 1){
                    $status_name = _l('invoice_status_paid');
                    $label_class = 'success';
                }else{
                    $status_name = _l('invoice_status_unpaid');
                    $label_class = 'danger';
                }
                $row[]  = '<span class="label label-' . $label_class . ' s-status commission-status-' . $aRow['paid'] . '">' . $status_name . '</span>';

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    /**
     * Gets the where report period.
     *
     * @param      string  $field  The field
     *
     * @return     string  The where report period.
     */
    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
        }

        return $custom_date_select;
    }

    /**
     * get data commission chart
     * 
     * @return     json
     */
    public function commission_chart(){
        $this->load->model('currencies_model');
        $this->load->model('commission_model');
        $staff_filter = [];
        if ($this->input->post('staff_filter')) {
            $staff_filter  = $this->input->post('staff_filter');
        }

        if(!has_permission('commission', '', 'view')){
            $staff_filter  = [get_staff_user_id()];
        }

        $products_services  = [];
        if ($this->input->post('products_services')) {
            $products_services  = $this->input->post('products_services');
        }
        $year_report      = $this->input->post('year');
        $currency = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if($currency){
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }

        $is_client  = 0;
        if ($this->input->post('is_client')) {
            $is_client  = $this->input->post('is_client');
        }
        $data = $this->commission_model->commission_chart($year_report, $staff_filter, $products_services, $is_client);
        echo json_encode([
            'data_total' => $data['amount'],
            'data_paid' => $data['amount_paid'],
            'month' => $data['month'],
            'unit' => $currency_unit,
            'name' => $currency_name,
        ]);
        die();
    }

    /**
     * get data dashboard commission chart
     * 
     * @return     json
     */
    public function dashboard_commission_chart(){
        $this->load->model('currencies_model');
        $this->load->model('commission_model');
        $staff_filter = [];
        if ($this->input->post('staff_filter')) {
            $staff_filter  = $this->input->post('staff_filter');
        }

        $products_services  = [];
        if ($this->input->post('products_services')) {
            $products_services  = $this->input->post('products_services');
        }
        $year_report      = $this->input->post('year');
        $currency = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if($currency){
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }
        $data = $this->commission_model->dashboard_commission_chart($year_report, $staff_filter, $products_services);
        echo json_encode([
            'data' => $data['amount'],
            'month' => $data['month'],
            'unit' => $currency_unit,
            'name' => $currency_name,
        ]);
        die();
    }

    /**
     *  applicable staff table
     *  
     *  @return json
     */
    public function applicable_staff_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('commission_model');

            $select = [
                db_prefix() . 'applicable_staff.id as applicable_staff_id',
                db_prefix() . 'applicable_staff.applicable_staff as applicable_staff',
                db_prefix() . 'commission_policy.name as commission_policy_name', 
                db_prefix() . 'applicable_staff.addedfrom as applicable_staff_addedfrom', 
                db_prefix() . 'applicable_staff.datecreated as applicable_staff_datecreated', 
            ];
            $where = [];
            
            if ($this->input->post('is_client')) {
                $is_client  = $this->input->post('is_client');
            }else{
                $is_client = 0;
            }

            array_push($where, 'AND is_client = '.$is_client);
            if ($this->input->post('commission_policy_type')) {
                $commission_policy_type  = $this->input->post('commission_policy_type');
                array_push($where, 'AND commission_policy_type IN ('. implode(',', $commission_policy_type).')');
            }

            if ($this->input->post('staff_filter')) {
                $staff_filter  = $this->input->post('staff_filter');
                $staff_where = '';
                foreach ($staff_filter as $key => $value) {
                    if($staff_where != ''){
                        $staff_where .= ' or find_in_set('.$value.', applicable_staff)';
                    }else{
                        $staff_where .= 'find_in_set('.$value.', applicable_staff)';
                    }
                }

                if($staff_where != ''){
                    array_push($where, 'AND ('.$staff_where.')');
                }
            }
            $from_date = '';
            $to_date = '';
            if ($this->input->post('from_date')) {
                $from_date  = $this->input->post('from_date');
                if(!$this->commission_model->check_format_date($from_date)){
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date  = $this->input->post('to_date');
                if(!$this->commission_model->check_format_date($to_date)){
                    $to_date = to_sql_date($to_date);
                }
            }

            if($from_date != '' && $to_date != ''){
                array_push($where, 'AND ((from_date <= "'.$from_date.'" and to_date >= "'.$from_date.'") or (from_date <= "'.$to_date.'" and to_date >= "'.$to_date.'") or (from_date > "'.$from_date.'" and to_date < "'.$to_date.'"))');
            }elseif($from_date != ''){
                array_push($where, 'AND (from_date >= "'.$from_date.'" or to_date >= "'.$from_date.'")');
            }elseif($to_date != ''){
                array_push($where, 'AND (from_date <= "'.$to_date.'" or to_date <= "'.$to_date.'")');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'applicable_staff';
            $join         = ['LEFT JOIN ' . db_prefix() . 'commission_policy ON ' . db_prefix() . 'commission_policy.id = ' . db_prefix() . 'applicable_staff.commission_policy',];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[db_prefix() . 'commission_policy.id as commission_policy_id',
                'from_date',
                'to_date',
                'is_client'
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];
                if($aRow['is_client'] == 1){
                    $link = admin_url('clients/client/'.$aRow['applicable_staff']);
                    $_data = ' <a href="' . $link . '" target="_blank">' . get_company_name($aRow['applicable_staff'])  . '</a>';
                }else{
                    $_data = '<a href="' . admin_url('staff/profile/' . $aRow['applicable_staff']) . '" target="_blank">' . staff_profile_image($aRow['applicable_staff'], [
                    'staff-profile-image-small',
                    ]) . '</a>';
                    $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['applicable_staff']) . '" target="_blank">' . get_staff_full_name($aRow['applicable_staff'])  . '</a>';
                }
                $row[] = $_data;

                $row[] = '<a href="' . admin_url('commission/update_commission_policy/' . $aRow['commission_policy_id']) . '" target="_blank">' . $aRow['commission_policy_name'] . '</a>';

                $row[] = _d($aRow['from_date']);
                $row[] = _d($aRow['to_date']);

                $options = icon_btn('commission/delete_applicable_staff/' . $aRow['applicable_staff_id'], 'remove', 'btn-danger', ['title' => _l('delete')]);

                $row[] =  $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     *  commission policy table
     *  
     *  @return json
     */
    public function commission_policy_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('commission_model');

            $select = [
                'id',
                'name',
                'commission_policy_type',
                'from_date', 
                'to_date', 
            ];
            $where = [];
            

            if ($this->input->post('commission_policy_type')) {
                $commission_policy_type  = $this->input->post('commission_policy_type');
                array_push($where, 'AND commission_policy_type IN ('. implode(',', $commission_policy_type).')');
            }

            $from_date = '';
            $to_date = '';
            if ($this->input->post('from_date')) {
                $from_date  = $this->input->post('from_date');
                if(!$this->commission_model->check_format_date($from_date)){
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date  = $this->input->post('to_date');
                if(!$this->commission_model->check_format_date($to_date)){
                    $to_date = to_sql_date($to_date);
                }
            }

            if($from_date != '' && $to_date != ''){
                array_push($where, 'AND ((from_date <= "'.$from_date.'" and to_date >= "'.$from_date.'") or (from_date <= "'.$to_date.'" and to_date >= "'.$to_date.'") or (from_date > "'.$from_date.'" and to_date < "'.$to_date.'"))');
            }elseif($from_date != ''){
                array_push($where, 'AND (from_date >= "'.$from_date.'" or to_date >= "'.$from_date.'")');
            }elseif($to_date != ''){
                array_push($where, 'AND (from_date <= "'.$to_date.'" or to_date <= "'.$to_date.'")');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'commission_policy';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[]);

            $output  = $result['output'];
            $rResult = $result['rResult'];


            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['name'];
                $commission_policy_type = '';
                if($aRow['commission_policy_type'] == '1'){
                   $commission_policy_type = _l('calculated_as_ladder');
                }elseif($aRow['commission_policy_type'] == '2'){
                   $commission_policy_type = _l('calculated_as_percentage');
                }elseif($aRow['commission_policy_type'] == '3'){
                   $commission_policy_type = _l('calculated_by_the_product');
                }
                $row[] = $commission_policy_type;

                $row[] = _d($aRow['from_date']);
                $row[] = _d($aRow['to_date']);

                $options = icon_btn('commission/update_commission_policy/' . $aRow['id'], 'edit', 'btn-default', ['title' => _l('edit')]);
                $options .= icon_btn('commission/delete_commission_policy/' . $aRow['id'], 'remove', 'btn-danger', ['title' => _l('delete')]);

                $row[] =  $options;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * Gets the data detail commission.
     * 
     * @return json
     */
    public function get_data_detail_commission_table($staffid){
        $this->load->model('currencies_model');
        $this->load->model('commission_model');
        $staff_filter = [];
        if ($this->input->post('staff_filter')) {
            $staff_filter  = $this->input->post('staff_filter');
        }

        $products_services  = [];
        if ($this->input->post('products_services')) {
            $products_services  = $this->input->post('products_services');
        }
        $year_report      = $this->input->post('year');
        $currency = $this->currencies_model->get_base_currency();
        $currency_name = '';
        $currency_unit = '';
        if($currency){
            $currency_name = $currency->name;
            $currency_unit = $currency->symbol;
        }

        $where = $this->get_where_report_period();

        $data = $this->commission_model->get_data_detail_commission_table($staffid, $products_services, $where);
        echo json_encode([
            'html' => $data,
        ]);
        die();
    }

    /**
     * Recalculate commission
     */
    public function recalculate(){
        if (!has_permission('commission', '', 'create') && !is_admin() ) {
            access_denied('commission_policy');
        }
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $data = $this->input->post();

            $success = $this->commission_model->recalculate($data);

            if ($success) {
                set_alert('success', _l('recalculate'));
            }
        }

        redirect(admin_url('commission/commission_policy'));
    }

    /**
     * client groups change
     * 
     * @return json
     */
    public function client_groups_change(){
        if ($this->input->is_ajax_request()) {
            $this->load->model('commission_model');
            $data = $this->input->post();
            $where = [];
            if(isset($data['groups'])){
                $groups = implode(',', $data['groups']);
                if($groups != ''){
                    $where = '(SELECT count(*) FROM '.db_prefix().'customer_groups where customer_id = '.db_prefix().'clients.userid and find_in_set(groupid, "'.$groups.'")) > 0';
                }
            }
            $result = $this->commission_model->get_customer('', $where);

            echo json_encode(
                $result
            );
            die();
        }
    }

    /**
     * reload list invoice when checkbox "Recalculate the old invoice" change
     */
    public function recalculate_invoice_change(){
        $data = $this->input->post();
        $this->load->model('commission_model');

        if(isset($data['recalculate_the_old_invoice'])){
            if($data['recalculate_the_old_invoice'] === 'true'){
                $list_invoices = $this->commission_model->get_invoice_without_commission(true);
            }else{

                $list_invoices = $this->commission_model->get_invoice_without_commission();
            }
        }

        echo json_encode(
            $list_invoices
        );
        die();
    }

    /**
     * manage applicable client
     */
    public function applicable_client(){
        if (!has_permission('commission_applicable_staff', '', 'view') && !is_admin() ) {
            access_denied('applicable_staff');
        }
        $this->load->model('clients_model');
        $this->load->model('commission_model');
        $data['is_client'] = 1;
        $data['title'] = _l('applicable_client');
        $data['clients'] = $this->clients_model->get();
        $data['list_applicable_client'] = $this->commission_model->load_applicable_staff('', 1);
        $this->load->view('manage_applicable_staff', $data);
    }

    /**
     * new applicable client
     */
    public function new_applicable_client(){
        if (!has_permission('commission_applicable_client', '', 'create') && !is_admin()) {
            access_denied('applicable_client');
        }
        $this->load->model('staff_model');
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $data                = $this->input->post();
            if (!has_permission('commission_applicable_client', '', 'create')) {
                access_denied('applicable_client');
            }
            $success = $this->commission_model->add_applicable_staff($data);
            if ($success) {
                set_alert('success', _l('added_successfully', _l('applicable_client')));
            }
            redirect(admin_url('commission/applicable_client'));
        }
        $data['title'] = _l('applicable_client');
        $data['commission_policy'] = $this->commission_model->load_commission_policy();
        $data['is_client'] = 1;
        $data['clients'] = $this->clients_model->get();
        $list_staff = [];
        foreach ($data['clients'] as $key => $value) {
            $list_staff[$value['userid']] = trim($value['company']);
        }

        $data['list_staff_json'] = json_encode($list_staff);
        $this->load->view('applicable_staff', $data);
    }

    /**
     * setting
     * @return view
     */
    public function setting() {
        if (!has_permission('commission', '', 'view_setting') && !is_admin()) {
            access_denied('commission');
        }
        $this->load->model('commission_model');

        $data['group'] = $this->input->get('group');

        $data['title'] = _l('commission_setting');
        $data['tab'][] = 'hierarchy';
        $data['tab'][] = 'salesadmin_customer_group';
        if ($data['group'] == '' || $data['group'] == 'hierarchy') {
            $data['group'] = 'hierarchy';
            $data['hierarchys'] = $this->commission_model->get_hierarchy();
            $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        }elseif ($data['group'] == 'salesadmin_customer_group') {
            $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
            $data['customer_groups'] = $this->clients_model->get_groups();
            $data['salesadmin_customer_groups'] = $this->commission_model->get_salesadmin_group();
        }

        $data['tabs']['view'] = 'includes/' . $data['group'];

        $this->load->view('manage_setting', $data);
    }

    /**
     * Add or update hierarchy
     */
    public function hierarchy(){
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->commission_model->add_hierarchy($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->commission_model->update_hierarchy($data, $id);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
            }
        }

        redirect(admin_url('commission/setting'));
    }

    /**
     * delete hierarchy
     *
     * @param      <type>  $id     The identifier
     */
    public function delete_hierarchy($id){

        if (!has_permission('commission', '', 'view_setting')) {
            access_denied('commission');
        }
        if (!$id) {
            redirect(admin_url('commission/setting'));
        }
        $this->load->model('commission_model');
        $success = $this->commission_model->delete_hierarchy($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('hierarchy')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('hierarchy')));
        }
        redirect(admin_url('commission/setting'));
    }

    /**
     * Add or update salesadmin customer group
     */
    public function salesadmin_customer_group(){
        $this->load->model('commission_model');

        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->commission_model->add_salesadmin_group($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->commission_model->update_salesadmin_group($data, $id);
                if ($success) {
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
            }
        }

        redirect(admin_url('commission/setting?group=salesadmin_customer_group'));
    }

    /**
     * delete salesadmin customer group
     *
     * @param      <type>  $id     The identifier
     */
    public function delete_salesadmin_customer_group($id){
        if (!has_permission('commission', '', 'view_setting')) {
            access_denied('commission');
        }
        if (!$id) {
            redirect(admin_url('commission/setting?group=salesadmin_customer_group'));
        }
        $this->load->model('commission_model');
        $success = $this->commission_model->delete_salesadmin_group($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('hierarchy')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('hierarchy')));
        }
        redirect(admin_url('commission/setting?group=salesadmin_customer_group'));
    }

    /**
     * add, update or view detail receipt
     *
     * @param      string  $id     The identifier
     */
    public function receipt($id = ''){
        if (!has_permission('commission_receipt', '', 'view') && !is_admin()) {
            access_denied('commission_receipt');
        }
        $this->load->model('payment_modes_model');
        $this->load->model('commission_model');
        $this->load->model('currencies_model');
        if ($this->input->post()) {
            if ($id != '') {
                if (!has_permission('commission_receipt', '', 'edit')) {
                    access_denied('Update Commission Receipt');
                }
                $success = $this->commission_model->update_receipt($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('commission_receipt')));
                }
                redirect(admin_url('commission/receipt/' . $id));
            }else{
                if (!has_permission('commission_receipt', '', 'create')) {
                    access_denied('Add Commission Receipt');
                }
                $id = $this->commission_model->add_receipt($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('commission_receipt')));
                }
                redirect(admin_url('commission/receipt/' . $id));
            }

            
        }
        
        $data = [];
        if ($id != '') {
            $receipt = $this->commission_model->get_receipt($id);

            if (!$receipt) {
                show_404();
            }

            $data['receipt'] = $receipt;
            $data['payment_modes'] = $this->payment_modes_model->get('', [], true, true);
            $i = 0;
            foreach ($data['payment_modes'] as $mode) {
                if ($mode['active'] == 0 && $data['receipt']->paymentmode != $mode['id']) {
                    unset($data['payment_modes'][$i]);
                }
                $i++;
            }
            $where = '(paid = 0 or (select count(*) from '.db_prefix().'commission_receipt_detail where receipt_id = '.$id.' and commission_id = '.db_prefix() . 'commission.id))';
        }else{

            $data['payment_modes'] = $this->payment_modes_model->get('', [
                'expenses_only !=' => 1,
            ]);
            $where = 'paid = 0';
        }

        

        $list_commission = $this->commission_model->get_commission('', $where);
        $data['list_commission'] = [];
        $data['currency'] = $this->currencies_model->get_base_currency();
        $list_commission_json = [];

        foreach ($list_commission as $key => $value) {
            if(format_invoice_number($value['invoice_id']) == '' || $value['amount'] <= 0){
                continue;
            }
            $list_commission_json[$value['id']] = $value['amount'];
            $value['amount'] = app_format_money($value['amount'], $data['currency']->name);
            
            if($value['is_client'] == 1){
                $value['commission_info'] = _l('client').' - '.format_invoice_number($value['invoice_id']).' - '.get_company_name($value['staffid']);
            }else{
                $value['commission_info'] = _l('staff').' - '.format_invoice_number($value['invoice_id']).' - '.get_staff_full_name($value['staffid']);
            }
            $data['list_commission'][] = $value;
        }

        $data['list_commission_json'] = json_encode($list_commission_json);

        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['title'] = _l('commission_receipt');
        $data['list_commission_receipt'] = $this->commission_model->get_receipt('', 1);
        $this->load->view('commission/receipts/receipt', $data);
    }

    /**
     * view list receipt
     */
    public function manage_commission_receipt(){ 
        $this->load->model('commission_model');
        $this->load->model('payment_modes_model');
        $this->load->model('expenses_model');
        $this->load->model('currencies_model');

        $data['staffs'] = $this->staff_model->get('', ['active' => 1]);
        $data['title'] = _l('commission_receipt');
        $data['list_commission_receipt'] = $this->commission_model->get_receipt('', 1);
        $data['payment_modes'] = $this->payment_modes_model->get('', [], true);
        $data['currency'] = $this->currencies_model->get_base_currency();
        $data['currencies']         = $this->currencies_model->get();

        $data['expense_categories'] = $this->expenses_model->get_category();

        $this->load->view('receipts/manage_receipt', $data);
    }

    /**
     * commission receipt table
     */
    public function commission_receipt_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('commission_model');
            $currency = $this->currencies_model->get_base_currency();

            $select = [
                'id',
                'addedfrom', 
                'date',
                'amount', 
                'convert_expense', 
            ];
            $where = [];
            
            if ($this->input->post('is_client')) {
                $is_client  = $this->input->post('is_client');
            }else{
                $is_client = 0;
            }

            if ($this->input->post('staff_filter')) {
                $staff_filter  = $this->input->post('staff_filter');
                $staff_where = '';
                foreach ($staff_filter as $key => $value) {
                    if($staff_where != ''){
                        $staff_where .= ' or addedfrom = '.$value;
                    }else{
                        $staff_where .= 'addedfrom = '.$value;
                    }
                }

                if($staff_where != ''){
                    array_push($where, 'AND ('.$staff_where.')');
                }
            }

            if ($this->input->post('conver_to_expense')) {
                $conver_to_expense  = $this->input->post('conver_to_expense');
                $conver_to_expense_where = '';
                foreach ($conver_to_expense as $key => $value) {
                    if($value == 1){
                        if($conver_to_expense_where != ''){
                            $conver_to_expense_where .= ' or convert_expense = 0';
                        }else{
                            $conver_to_expense_where .= 'convert_expense = 0';
                        }
                    }else{
                        if($conver_to_expense_where != ''){
                            $conver_to_expense_where .= ' or convert_expense != 0';
                        }else{
                            $conver_to_expense_where .= 'convert_expense != 0';
                        }
                    }
                }

                if($conver_to_expense_where != ''){
                    array_push($where, 'AND ('.$conver_to_expense_where.')');
                }
            }

            $from_date = '';
            $to_date = '';
            if ($this->input->post('from_date')) {
                $from_date  = $this->input->post('from_date');
                if(!$this->commission_model->check_format_date($from_date)){
                    $from_date = to_sql_date($from_date);
                }
            }

            if ($this->input->post('to_date')) {
                $to_date  = $this->input->post('to_date');
                if(!$this->commission_model->check_format_date($to_date)){
                    $to_date = to_sql_date($to_date);
                }
            }

            if($from_date != '' && $to_date != ''){
                array_push($where, 'AND (date >= "'.$from_date.'" and date <= "'.$to_date.'")');
            }elseif($from_date != ''){
                array_push($where, 'AND (date >= "'.$from_date.'")');
            }elseif($to_date != ''){
                array_push($where, 'AND (date <= "'.$to_date.'"');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'commission_receipt';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,[
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total' => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];
                $link = admin_url('commission/receipt/' . $aRow['id']);

                $numberOutput = '<a href="' . $link . '">' . $aRow['id'] . '</a>';

                $numberOutput .= '<div class="row-options">';
                $numberOutput .= '<a href="' . $link . '">' . _l('view') . '</a>';
                $numberOutput .= ' | <a href="' . admin_url('commission/delete_receipt/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

                $numberOutput .= '</div>';

                $row[] = $numberOutput;
                
                $_data = '<a href="' . admin_url('staff/profile/' . $aRow['addedfrom']) . '" target="_blank">' . staff_profile_image($aRow['addedfrom'], [
                'staff-profile-image-small',
                ]) . '</a>';
                $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['addedfrom']) . '" target="_blank">' . get_staff_full_name($aRow['addedfrom'])  . '</a>';
                $row[] = $_data;


                $row[] = app_format_money($aRow['amount'], $currency->name);;
                $row[] = _d($aRow['date']);

                if($aRow['convert_expense'] == 0){
                    $_data = '<a href="javascript:void(0)" onclick="convert_expense('.$aRow['id'].','.$aRow['amount'].'); return false;" class="btn btn-warning btn-icon">'._l('convert').'</a>';
                }else{
                    $_data = '<a href="'.admin_url('expenses/list_expenses/'.$aRow['convert_expense']).'" class="btn btn-success btn-icon">'._l('view_expense').'</a>';
                }
                $row[]  = $_data;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * delete receipt
     *
     * @param      <type>  $id     The identifier
     */
    public function delete_receipt($id){
        if (!has_permission('commission_receipt', '', 'delete')) {
            access_denied('commission_receipt');
        }
        if (!$id) {
            redirect(admin_url('commission/manage_commission_receipt'));
        }
        $this->load->model('commission_model');
        $success = $this->commission_model->delete_receipt($id);
        if ($success == true) {
            set_alert('success', _l('deleted', _l('commission_receipt')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('commission_receipt')));
        }
        redirect(admin_url('commission/manage_commission_receipt'));
    }

    /**
     * Generate receipt pdf
     * @since  Version 1.0.1
     * @param  mixed $id Payment id
     */
    public function pdf($id)
    {
        if (!has_permission('commission_receipt', '', 'view')) {
            access_denied('View Receipt');
        }
        $this->load->model('commission_model');
        $receipt = $this->commission_model->get_receipt($id);

        try {
            $receiptpdf = $this->commission_model->receipt_pdf($receipt);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo html_entity_decode($message);
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $receiptpdf->Output(mb_strtoupper(slug_it(_l('commission_receipt') . '-' . $receipt->id)) . '.pdf', $type);
    }

    /**
     * Adds an expense.
     */
    public function add_expense()
    {
        if ($this->input->post()) {
            $this->load->model('expenses_model');
            $this->load->model('commission_model');

            $data = $this->input->post();
            if(isset($data['commission_receipt_id'])){
                $commission_receipt_id = $data['commission_receipt_id'];
                unset($data['commission_receipt_id']);
            }

            $id = $this->expenses_model->add($data);

            if ($id) {

                $this->commission_model->mark_converted_receipt($commission_receipt_id, $id);

                set_alert('success', _l('converted', _l('expense')));
                echo json_encode([
                    'url'       => admin_url('expenses/list_expenses/' .$id),
                    'expenseid' => $id,
                ]);
                die;

            }
        }
    }

    /**
     * Send receipt manually to salesperson
     * 
     * @param  mixed $id receipt id
     * @return mixed
     */
    public function send_to_email($id)
    {
        if (!has_permission('commission_receipt', '', 'view')) {
            access_denied('Send Commission Receipt');
        }
        $this->load->model('commission_model');
        $this->load->model('emails_model');

        $sent    = false;
        $sent_to = $this->input->post('sent_to');
        $email_template_custom = $this->input->post('email_template_custom', false);
        if (is_array($sent_to) && count($sent_to) > 0) {
            foreach ($sent_to as $email) {
                $this->emails_model->send_simple_email($email, _l('commission'), $email_template_custom);
            }
        }

        redirect(admin_url('commission/receipt/' . $id));
    }
}