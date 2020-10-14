<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Commission model
 */
class Commission_model extends App_Model {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Adds a commission policy.
	 *
	 * @param      Object   $data   The data
	 *
	 * @return     boolean
	 */
	public function add_commission_policy($data) {
		$ladder_setting = [];
		foreach ($data['from_amount'] as $key => $value) {
			$node = [];
			$node['from_amount'] = $value;
			$node['to_amount'] = $data['to_amount'][$key];
			$node['percent_enjoyed_ladder'] = $data['percent_enjoyed_ladder'][$key];
			$ladder_setting[] = $node;
		}

		if(isset($data['clients'])){
			$data['clients'] = implode(',', $data['clients']);
		}

		if(isset($data['client_groups'])){
			$data['client_groups'] = implode(',', $data['client_groups']);
		}

		unset($data['from_amount']);
		unset($data['to_amount']);
		unset($data['percent_enjoyed_ladder']);

		$data['addedfrom'] = get_staff_user_id();
		$data['datecreated'] = date('Y-m-d H:i:s');

		if (!$this->check_format_date($data['from_date'])) {
			$data['from_date'] = to_sql_date($data['from_date']);
		}
		if (!$this->check_format_date($data['to_date'])) {
			$data['to_date'] = to_sql_date($data['to_date']);
		}

		$data['ladder_setting'] = json_encode($ladder_setting);
		$this->db->insert(db_prefix() . 'commission_policy', $data);

		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			return true;
		}
		return false;
	}

	/**
	 * update commission policy
	 *
	 * @param      Object   $data   The data
	 *
	 * @return     boolean
	 */
	public function update_commission_policy($data, $id) {
		$ladder_setting = [];
		foreach ($data['from_amount'] as $key => $value) {
			$node = [];
			$node['from_amount'] = $value;
			$node['to_amount'] = $data['to_amount'][$key];
			$node['percent_enjoyed_ladder'] = $data['percent_enjoyed_ladder'][$key];
			$ladder_setting[] = $node;
		}

		if(isset($data['clients'])){
			$data['clients'] = implode(',', $data['clients']);
		}else{
			$data['clients'] = '';
		}

		if(isset($data['client_groups'])){
			$data['client_groups'] = implode(',', $data['client_groups']);
		}else{
			$data['client_groups'] = '';
		}

		if(!isset($data['commmission_first_invoices'])){
			$data['commmission_first_invoices'] = 0;
		}

		unset($data['from_amount']);
		unset($data['to_amount']);
		unset($data['percent_enjoyed_ladder']);

		if (!$this->check_format_date($data['from_date'])) {
			$data['from_date'] = to_sql_date($data['from_date']);
		}
		if (!$this->check_format_date($data['to_date'])) {
			$data['to_date'] = to_sql_date($data['to_date']);
		}
		$data['ladder_setting'] = json_encode($ladder_setting);

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'commission_policy', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Loads a commission policy.
	 *
	 * @param      string  $id     The identifier
	 *
	 * @return     object
	 */
	public function load_commission_policy($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'commission_policy')->row();
		}

		return $this->db->get(db_prefix() . 'commission_policy')->result_array();
	}

	/**
	 * Adds a applicable staff
	 *
	 * @param      Object   $data   The data
	 *
	 * @return     boolean
	 */
	public function add_applicable_staff($data) {

		$data['addedfrom'] = get_staff_user_id();

		$data['datecreated'] = date('Y-m-d H:i:s');
		$is_client = 0;
		if(isset($data['is_client'])){
			$is_client = 1;
		}
		foreach ($data['applicable_staff'] as $key => $value) {

			$this->db->insert(db_prefix() . 'applicable_staff', [
				'addedfrom' => $data['addedfrom'],
				'datecreated' => $data['datecreated'],
				'applicable_staff' => $value,
				'is_client' => $is_client,
				'commission_policy' => $data['commission_policy'],
			]);
		}

		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			return true;
		}
		return false;
	}

	/**
	 * update applicable staff
	 *
	 * @param      Object   $data   The data
	 *
	 * @return     boolean
	 */
	public function update_applicable_staff($data, $id) {

		$data['applicable_staff'] = implode(',', $data['applicable_staff']);

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'applicable_staff', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Loads a applicable staff
	 *
	 * @param      string  $id     The identifier
	 *
	 * @return     object
	 */
	public function load_applicable_staff($id = '', $is_client = 0) {
		if ($id != '') {
			$this->db->where('id', $id);
			$applicable_staff = $this->db->get(db_prefix() . 'applicable_staff')->row();

			if ($applicable_staff) {
				$applicable_staff->list_staff_html = '';
				foreach (explode(',', $applicable_staff->applicable_staff) as $key => $value) {
					$applicable_staff->list_staff_html .= '<a class="list-group-item">' . get_staff_full_name($value) . '</a>';
				}
			}

			return $applicable_staff;
		}

		$this->db->where('is_client', $is_client);
		return $this->db->get(db_prefix() . 'applicable_staff')->result_array();
	}

	/**
	 * Gets the commission policy by staff.
	 *
	 * @param      string  $staff  The staff
	 *
	 * @return     object  The commission policy by staff.
	 */
	public function get_commission_policy_by_staff($staff, $client_id, $is_client = 0) {
		if($client_id == ''){
			$client_id = 0;
		}
		if($staff == ''){
			$staff = 0;
		}
		
		$date = date('Y-m-d');

		$this->db->select('userid, company, (SELECT GROUP_CONCAT(groupid SEPARATOR ",") FROM '.db_prefix().'customer_groups WHERE customer_id = '.db_prefix().'clients.userid) as customerGroups');
		$this->db->where('userid', $client_id);
		$client = $this->db->get(db_prefix().'clients')->row();
		$where_group = '';
		if($client->customerGroups != ''){
			foreach (explode(',', $client->customerGroups) as $key => $value) {
				if($where_group != ''){
					$where_group .= ' OR IF(client_groups IS NOT NULL,IF(client_groups != "",find_in_set('.$value.',client_groups), 1=1), 1=1)';
				}else{
					$where_group = 'IF(client_groups IS NOT NULL, IF(client_groups != "",find_in_set('.$value.',client_groups), 1=1), 1=1)';
				}
			}

			if($where_group != ''){
				$where_group = ' and ('.$where_group.')';
			}
		}

		return $this->db->query('SELECT '.db_prefix().'commission_policy.name, from_date, to_date, percent_enjoyed, product_setting, ladder_setting, commission_policy_type, clients, client_groups, commmission_first_invoices, number_first_invoices, percent_first_invoices FROM ' . db_prefix() . 'applicable_staff JOIN '.db_prefix().'commission_policy ON '.db_prefix().'applicable_staff.commission_policy = '.db_prefix().'commission_policy.id where applicable_staff = "'.$staff . '" and is_client = "'.$is_client.'" and from_date <= "' . $date . '" and to_date >= "' . $date . '" and IF(clients IS NOT NULL, IF(clients != "",find_in_set('.$client_id.',clients), 1=1), 1=1)'.$where_group.' order by '.db_prefix().'commission_policy.datecreated desc')->row();

	}

	/**
	 * get data commission chart
	 *
	 * @param      string  $year   The year
	 *
	 * @return     array
	 */
	public function commission_chart($year = '', $staff_filter = [], $products_services = [], $is_client = 0) {
		$this->load->model('staff_model');
		$this->load->model('clients_model');
		if ($year == '') {
			$year = date('Y');
		}
		$amount = [];
		$amount_paid = [];
		$month = [];
		if ($staff_filter == []) {
			if($is_client == 0){
				$staffs = $this->staff_model->get('', ['active' => 1]);
				foreach ($staffs as $key => $value) {
					$count = $this->sum_commission($value['staffid'], 'year(date) = ' . $year, $products_services, $is_client);
					if ($count) {
						$amount[] = (double) $count;
					} else {
						$amount[] = 0;
					}

					$count_paid = $this->sum_commission($value['staffid'], 'year(date) = ' . $year.' and paid = 1', $products_services, $is_client);
					if ($count_paid) {
						$amount_paid[] = (double) $count_paid;
					} else {
						$amount_paid[] = 0;
					}

					$month[] = trim($value['firstname'] . ' ' . $value['lastname']);
				}
			}else{
				$clients = $this->clients_model->get();
				foreach ($clients as $key => $value) {
					$count = $this->sum_commission($value['userid'], 'year(date) = ' . $year, $products_services, $is_client);
					if ($count) {
						$amount[] = (double) $count;
					} else {
						$amount[] = 0;
					}

					$count_paid = $this->sum_commission($value['userid'], 'year(date) = ' . $year.' and paid = 1', $products_services, $is_client);
					if ($count_paid) {
						$amount_paid[] = (double) $count_paid;
					} else {
						$amount_paid[] = 0;
					}
					$month[] = trim($value['company']);
				}
			}

			return ['amount' => $amount, 'amount_paid' => $amount_paid, 'month' => $month];
		} else {
			if (count($staff_filter) == 1) {
				$date_minus = $year . '-01-01';
				for ($i = 0; $i < 12; $i++) {
					$count = $this->sum_commission($staff_filter[0], 'year(date) = ' . date('Y', strtotime($date_minus)) . ' and month(date) = ' . date('m', strtotime($date_minus)), $products_services, $is_client);
					if ($count) {
						$amount[] = (double) $count;
					} else {
						$amount[] = 0;
					}

					$count_paid = $this->sum_commission($staff_filter[0], 'year(date) = ' . date('Y', strtotime($date_minus)) . ' and month(date) = ' . date('m', strtotime($date_minus)).' and paid = 1', $products_services, $is_client);
					if ($count_paid) {
						$amount_paid[] = (double) $count_paid;
					} else {
						$amount_paid[] = 0;
					}

					$month[] = date("M Y", strtotime($date_minus));
					$date_minus = date("Y-m-d", strtotime($date_minus . " +1 month"));
				}
			} else {
				foreach ($staff_filter as $key => $value) {
					$count = $this->sum_commission($value, 'year(date) = ' . $year, $products_services, $is_client);
					if ($count) {
						$amount[] = (double) $count;
					} else {
						$amount[] = 0;
					}

					$count_paid = $this->sum_commission($value, 'year(date) = ' . $year.' and paid = 1', $products_services, $is_client);
					if ($count_paid) {
						$amount_paid[] = (double) $count_paid;
					} else {
						$amount_paid[] = 0;
					}

					if($is_client == 0){
						$month[] = trim(get_staff_full_name($value));
					}else{
						$month[] = trim(get_company_name($value));
					}
				}
			}
			return ['amount' => $amount, 'amount_paid' => $amount_paid, 'month' => $month];
		}
	}

	/**
	 * get data dashboard commission chart
	 *
	 * @param      string  $year   The year
	 *
	 * @return     array
	 */
	public function dashboard_commission_chart($staffid = '') {
		if ($staffid == '') {
			$staffid = get_staff_user_id();
		}
		$date_minus = date("Y-m-d", strtotime(date('Y-m-1') . " -11 month"));
		$amount = [];
		$month = [];
		for ($i = 0; $i < 12; $i++) {
			$count = sum_from_table(db_prefix() . 'commission', array('field' => 'amount', 'where' => array('staffid' => $staffid, 'year(date)' => date('Y', strtotime($date_minus)), 'month(date)' => date('m', strtotime($date_minus)))));
			if ($count) {
				$amount[] = (double) $count;
			} else {
				$amount[] = 0;
			}
			$month[] = date("M Y", strtotime($date_minus));
			$date_minus = date("Y-m-d", strtotime($date_minus . " +1 month"));
		}

		return ['amount' => $amount, 'month' => $month];
	}

	/**
	 * Adds a commission.
	 *
	 * @param      integer   $payment_id  The payment identifier
	 *
	 * @return     boolean
	 */
	public function add_commission($payment_id) {
		$this->load->model('payments_model');
		$this->load->model('invoices_model');
		$this->load->model('invoice_items_model');

		$payment = $this->payments_model->get($payment_id);
		$affectedRows = 0;
		$invoices = $this->invoices_model->get($payment->invoiceid);
		$count = 0;
		$salesperson = '';
		if($invoices->sale_agent){
			$salesperson = $invoices->sale_agent;
		}else{
			$this->db->where('customer_id', $invoices->clientid);
			$customer_admins = $this->db->get(db_prefix() . 'customer_admins')->row();
			if($customer_admins){
				$salesperson = $customer_admins->staff_id;
			}else{
				$this->db->where('customer_id', $invoices->clientid);
				$customer_groups = $this->db->get(db_prefix() . 'customer_groups')->result_array();
				if($customer_groups){
					foreach ($customer_groups as $key => $value) {
						$this->db->where('customer_group', $value['groupid']);
						$salesadmin_group = $this->db->get(db_prefix() . 'commission_salesadmin_group')->row();
						if($salesadmin_group){
							$salesperson = $salesadmin_group->salesadmin;
							break;
						}
					}
				}
			}
		}
		if ($invoices) {
			$commission_policy = $this->get_commission_policy_by_staff($salesperson, $invoices->clientid);
			if ($commission_policy) {
				if ($commission_policy->commission_policy_type == '2') {
					$payments_amount = $payment->amount - round(($invoices->total_tax * ($payment->amount/$invoices->total)), 2);
					if($commission_policy->commmission_first_invoices == 1){
						$list_first_invoices = $this->get_first_invoices($salesperson, $payment->invoiceid, $commission_policy->number_first_invoices, $commission_policy);
						if(in_array($payment->invoiceid, $list_first_invoices)){
							$count += $payments_amount * ($commission_policy->percent_first_invoices / 100);
						}else{
							$count += $payments_amount * ($commission_policy->percent_enjoyed / 100);
						}
					}else{
						$count += $payments_amount * ($commission_policy->percent_enjoyed / 100);
					}
				} elseif ($commission_policy->commission_policy_type == '3') {
					$product_setting = json_decode($commission_policy->product_setting);
					if ($invoices->items) {
						$payments_amount = $payment->amount - round(($invoices->total_tax * ($payment->amount/$invoices->total)), 2);
						foreach ($invoices->items as $item) {
							
							$item_amount = $item['qty'] * $item['rate'];

							$percent = $item_amount / $invoices->subtotal;
							$item_id = $this->get_item_id_by_name($item['description']);
							$it = '';
							if($item_id != ''){
								$it = $this->invoice_items_model->get($item_id);
							}
							foreach ($product_setting as $value){
								$group_setting = explode('|', $value[0]);
								$item_setting = explode('|', $value[1]);
								$from_number_setting = $value[2];
								$to_number_setting = $value[3];
								$percent_setting = $value[4];

								$check = true;
								if($item_id != ''){
									if($it != ''){
										if($value[0] != '' && !in_array($it->group_id, $group_setting)){
											$check = false;
										}
									}else{
										if($value[0] != ''){
											$check = false;
										}
									}

									if($value[1] != '' && !in_array($item_id, $item_setting)){
										$check = false;
									}
								}else{
									if($value[1] != '' || $value[0] != ''){
										$check = false;
									}
								}

								if(($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)){
									$check = false;
								}

								if ($check == true) {
									$count += ($percent * $payments_amount) * ($percent_setting / 100);
								}
							}
						}
					}
				} elseif ($commission_policy->commission_policy_type == '1') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					$ladder_setting = json_decode($commission_policy->ladder_setting);

					foreach ($ladder_setting as $key => $value) {
						$from_amount = str_replace(',', '', $value->from_amount);
						if ($payments_amount > $from_amount) {
							$to_amount = str_replace(',', '', $value->to_amount);
							if ($to_amount == '') {
								$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
								$payments_amount = 0;
							} else {
								if ($payments_amount > $to_amount) {
									$count += $to_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = $payments_amount - $to_amount;
								} else {
									$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = 0;
								}
							}
						} else {
							break;
						}
					}
				}
			}

			if ($count > 0) {

				$data = [];
				$data[$salesperson] = $count;
				$list_isset = [];
				$list_isset[] = $salesperson;
				do {
					foreach ($data as $k => $count) {
						$hierarchy = $this->get_hierarchy('', ['salesman' => $k]);
						$count_discount = 0;
						if($hierarchy){
							foreach ($hierarchy as $key => $value) {
								if(!in_array($value['coordinator'], $list_isset)){
									$data[$value['coordinator']] = round($count * ($value['percent'] / 100), 2);
									$count_discount += $data[$value['coordinator']];
									$list_isset[] = $value['coordinator'];
								}
							}
							$count = $count - $count_discount;
						}

						$this->db->where('invoice_id', $invoices->id);
						$this->db->where('is_client', 0);
						$this->db->where('staffid', $k);
						$commission = $this->db->get(db_prefix() . 'commission')->row();

						if ($commission) {
							if ($commission_policy->commission_policy_type == '2' || $commission_policy->commission_policy_type == '3') {
								$count = $count + $commission->amount;
							}

							$this->db->where('id', $commission->id);
							$this->db->update(db_prefix() . 'commission', ['amount' => round($count, 2), 'date' => date('Y-m-d')]);
							if ($this->db->affected_rows() > 0) {
					            $affectedRows++;
					        }
						} else {
							$note = [];
							$note['staffid'] = $k;
							$note['invoice_id'] = $invoices->id;
							$note['amount'] = round($count, 2);
							$note['date'] = date('Y-m-d');
							$note['is_client'] = 0;
							$this->db->insert(db_prefix() . 'commission', $note);
							$insert_id = $this->db->insert_id();

							if ($insert_id) {
		                    	$affectedRows++;
							}
						}
						unset($data[$k]);
					}
				} while (count($data) > 0);
			}

			
			$count = 0;
			$commission_policy_contact = $this->get_commission_policy_by_staff($invoices->clientid, $invoices->clientid, 1);
			if ($commission_policy_contact) {
				if ($commission_policy_contact->commission_policy_type == '2') {
					$payments_amount = $payment->amount - round(($invoices->total_tax * ($payment->amount/$invoices->total)), 2);
					if($commission_policy_contact->commmission_first_invoices == 1){
						$list_first_invoices = $this->get_first_invoices($invoices->clientid, $payment->invoiceid, $commission_policy_contact->number_first_invoices, $commission_policy_contact, 1);
						if(in_array($payment->invoiceid, $list_first_invoices)){
							$count += $payments_amount * ($commission_policy_contact->percent_first_invoices / 100);
						}else{
							$count += $payments_amount * ($commission_policy_contact->percent_enjoyed / 100);
						}
					}else{
						$count += $payments_amount * ($commission_policy_contact->percent_enjoyed / 100);
					}
				} elseif ($commission_policy_contact->commission_policy_type == '3') {
					$product_setting = json_decode($commission_policy_contact->product_setting);
					if ($invoices->items) {
						$payments_amount = $payment->amount - round(($invoices->total_tax * ($payment->amount/$invoices->total)), 2);
						foreach ($invoices->items as $item) {
							$item_amount = $item['qty'] * $item['rate'];
							
							$percent = $item_amount / $invoices->subtotal;
							$item_id = $this->get_item_id_by_name($item['description']);
							$it = '';
							if($item_id != ''){
								$it = $this->invoice_items_model->get($item_id);
							}

							foreach ($product_setting as $value){
								$group_setting = explode('|', $value[0]);
								$item_setting = explode('|', $value[1]);
								$from_number_setting = $value[2];
								$to_number_setting = $value[3];
								$percent_setting = $value[4];
								$check = true;
								if($item_id != ''){
									if($it != ''){
										if($value[0] != '' && !in_array($it->group_id, $group_setting)){
											$check = false;
										}
									}else{
										if($value[0] != ''){
											$check = false;
										}
									}

									if($value[1] != '' && !in_array($item_id, $item_setting)){
										$check = false;
									}
								}else{
									if($value[1] != '' || $value[0] != ''){
										$check = false;
									}
								}

								if(($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)){
									$check = false;
								}

								if ($check == true) {
									$count += ($percent * $payments_amount) * ($percent_setting / 100);
								}
							}
						}
					}
				} elseif ($commission_policy_contact->commission_policy_type == '1') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					$ladder_setting = json_decode($commission_policy_contact->ladder_setting);

					foreach ($ladder_setting as $key => $value) {
						$from_amount = str_replace(',', '', $value->from_amount);
						if ($payments_amount > $from_amount) {
							$to_amount = str_replace(',', '', $value->to_amount);
							if ($to_amount == '') {
								$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
								$payments_amount = 0;
							} else {
								if ($payments_amount > $to_amount) {
									$count += $to_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = $payments_amount - $to_amount;
								} else {
									$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = 0;
								}
							}
						} else {
							break;
						}
					}
				}
			}

			if ($count > 0) {
				$this->db->where('invoice_id', $invoices->id);
				$this->db->where('is_client', 1);
				$this->db->where('staffid', $invoices->clientid);
				$commission = $this->db->get(db_prefix() . 'commission')->row();

				if ($commission) {
					if ($commission_policy_contact->commission_policy_type == '2' || $commission_policy_contact->commission_policy_type == '3') {
						$count = $count + $commission->amount;
					}

					$this->db->where('id', $commission->id);
					$this->db->update(db_prefix() . 'commission', ['amount' => round($count, 2), 'date' => date('Y-m-d')]);
					if ($this->db->affected_rows() > 0) {
			            $affectedRows++;
			        }
				} else {
					$note = [];
					$note['staffid'] = $invoices->clientid;
					$note['invoice_id'] = $invoices->id;
					$note['amount'] = round($count, 2);
					$note['is_client'] = 1;
					$note['date'] = date('Y-m-d');
					$this->db->insert(db_prefix() . 'commission', $note);
					$insert_id = $this->db->insert_id();

					if ($insert_id) {
                    	$affectedRows++;
					}
				}
			}
		}

		if ($affectedRows > 0) {
        	return true;
		}

		return false;
	}

	/**
	 * sum commission amount
	 *
	 * @param      integer        $staffid  The staffid
	 * @param      array|string  $where    The where
	 *
	 * @return     integer
	 */
	public function sum_commission($staffid, $where = [], $products_services = '', $is_client = 0) {
		$where_item = '';
		if ($products_services != '') {
			foreach ($products_services as $key => $value) {
				$item_name = $this->get_item_name($value);
				if ($where_item == '') {
					$where_item .= '(select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
				} else {
					$where_item .= ' or (select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
				}
			}
		}
		$this->db->select_sum('amount');
		$this->db->where('staffid', $staffid);
		$this->db->where('is_client', $is_client);
		if ($where != '') {
			$this->db->where($where);
		}
		if ($where_item != '') {
			$where_item = '(' . $where_item . ')';
			$this->db->where($where_item);
		}
		$this->db->from(db_prefix() . 'commission');
		$result = $this->db->get()->row();
		if ($result) {
			return $result->amount;
		}
		return 0;
	}

	/**
	 * Gets the product select.
	 *
	 * @param      string  $staffid  The staffid
	 *
	 * @return     array   The product select.
	 */
	public function get_product_select() {

		$items = $this->db->get(db_prefix() . 'items')->result_array();
		$list_item = [];
		foreach ($items as $key => $item) {
			$note = [];
			$note['id'] = $item['id'];
			$note['label'] = $item['description'];
			$list_item[] = $note;
		}
		return $list_item;
	}

	/**
	 * Gets the item name.
	 *
	 * @param      string  $itemid  The itemid
	 *
	 * @return     string  The item name.
	 */
	public function get_item_name($itemid) {
		$this->db->where('id', $itemid);
		$items = $this->db->get(db_prefix() . 'items')->row();

		if ($items) {
			return $items->description;
		}
		return '';
	}

	/**
	 * delete commission policy
	 *
	 * @param      integer  $id     The identifier
	 */
	public function delete_commission_policy($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'commission_policy');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete applicable staff
	 *
	 * @param      integer  $id     The identifier
	 */
	public function delete_applicable_staff($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'applicable_staff');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * check format date Y-m-d
	 *
	 * @param      String   $date   The date
	 *
	 * @return     boolean
	 */
	public function check_format_date($date) {
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Gets the data detail commission table.
	 *
	 * @param      string        $staff_id           The staff identifier
	 * @param      array|string  $products_services  The products services
	 * @param      array         $where              The where
	 *
	 * @return     string        The data detail commission table.
	 */
	public function get_data_detail_commission_table($staff_id = '', $products_services = [], $where = []) {
		$this->load->model('currencies_model');
		$this->load->model('staff_model');
		$currency = $this->currencies_model->get_base_currency();

		if ($staff_id == '') {
			$staff_id = get_staff_user_id();
		}

		$where_item = '';
		if ($products_services != '') {
			foreach ($products_services as $key => $value) {
				$item_name = $this->get_item_name($value);
				if ($where_item == '') {
					$where_item .= '(select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
				} else {
					$where_item .= ' or (select count(*) from ' . db_prefix() . 'itemable where rel_id = invoice_id and rel_type = "invoice" and description = "' . $item_name . '") > 0';
				}
			}
		}
		$this->db->select('*');
		$this->db->where('staffid', $staff_id);
		$this->db->where($where);

		if ($where_item != '') {
			$where_item = '(' . $where_item . ')';
			$this->db->where($where_item);
		}
		$this->db->from(db_prefix() . 'commission');
		$result = $this->db->get()->result_array();
		$html = '';
		if ($result) {
			foreach ($result as $key => $value) {
				$html .= '<li class="list-group-item"><a href="' . admin_url('invoices/list_invoices/' . $value['invoice_id']) . '" target="_blank">' . format_invoice_number($value['invoice_id']) . '</a>: ' . app_format_money($value['amount'], $currency->name) . '</li>';
			}
		}

		return $html;
	}

	/**
	 * Gets the invoice without commission.
	 * 
	 * @param      bool        $old_invoice 
	 * 
	 * @return     array  The invoice without commission.
	 */
	public function get_invoice_without_commission($old_invoice = false){

		$where = '';
		if($old_invoice == false){
			$where = 'where (select count(*) from ' . db_prefix() . 'commission where ' . db_prefix() . 'commission.invoice_id = ' . db_prefix() . 'invoices.id) = 0';
		}

		$invoices = $this->db->query('SELECT * FROM ' . db_prefix() . 'invoices '.$where)->result_array();
		
		$invoice_return = [];

		foreach ($invoices as $key => $value) {
			$payments_amount = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $value['id'])));

			if($payments_amount > 0){
				$node = [];
				$node['id'] = $value['id'];
				$node['name'] = format_invoice_number($value['id']);
				$invoice_return[] = $node;
			}
		}

		return $invoice_return;
	}

	/**
	 * Recalculate
	 *
	 * @param      array   $data   list invoice id
	 *
	 * @return     boolean
	 */
	public function recalculate($data){
		$affectedRows = 0;
		if(isset($data['invoice'])){
			foreach ($data['invoice'] as $value) {
				$success = $this->add_commission_by_invoice($value);
				if ($success) {
                    $affectedRows++;
                }
			}
		}
		if($affectedRows > 0){
			return true;
		}
		return false;
	}


	/**
	 * Adds a commission by invoice.
	 *
	 * @param      int   $invoice_id  The invoice identifier
	 *
	 * @return     boolean 
	 */
	public function add_commission_by_invoice($invoice_id) {
		$this->load->model('payments_model');
		$this->load->model('invoices_model');
		$this->load->model('invoice_items_model');
		$invoices = $this->invoices_model->get($invoice_id);
		$affectedRows = 0;
		$count = 0;
		$salesperson = '';
		if($invoices->sale_agent){
			$salesperson = $invoices->sale_agent;
		}else{
			$this->db->where('customer_id', $invoices->clientid);
			$customer_admins = $this->db->get(db_prefix() . 'customer_admins')->row();
			if($customer_admins){
				$salesperson = $customer_admins->staff_id;
			}else{
				$this->db->where('customer_id', $invoices->clientid);
				$customer_groups = $this->db->get(db_prefix() . 'customer_groups')->result_array();
				if($customer_groups){
					foreach ($customer_groups as $key => $value) {
						$this->db->where('customer_group', $value['groupid']);
						$salesadmin_group = $this->db->get(db_prefix() . 'commission_salesadmin_group')->row();
						if($salesadmin_group){
							$salesperson = $salesadmin_group->salesadmin;
							break;
						}
					}
				}
			}
		}
		if ($invoices) {
			$commission_policy = $this->get_commission_policy_by_staff($salesperson, $invoices->clientid);

			if ($commission_policy) {
				if ($commission_policy->commission_policy_type == '2') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					if($commission_policy->commmission_first_invoices == 1){
						$list_first_invoices = $this->get_first_invoices($salesperson, $invoices->id, $commission_policy->number_first_invoices, $commission_policy);
						if(in_array($invoices->id, $list_first_invoices)){
							$count += $payments_amount * ($commission_policy->percent_first_invoices / 100);
						}else{
							$count += $payments_amount * ($commission_policy->percent_enjoyed / 100);
						}
					}else{
						$count += $payments_amount * ($commission_policy->percent_enjoyed / 100);
					}
				} elseif ($commission_policy->commission_policy_type == '3') {
					$product_setting = json_decode($commission_policy->product_setting);
					if ($invoices->items) {
						$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
						foreach ($invoices->items as $item) {
							$item_tax = $this->get_item_tax($item['id'], $item['rel_id']);
							if($item_tax != 0){
								$item_amount = $item['qty'] * $item['rate'] + (($item['qty'] * $item['rate']) * ($item_tax / 100));
							}else{
								$item_amount = $item['qty'] * $item['rate'];
							}
							$percent = $item_amount / $invoices->subtotal;
							$item_id = $this->get_item_id_by_name($item['description']);
							$it = '';
							if($item_id != ''){
								$it = $this->invoice_items_model->get($item_id);
							}

							foreach ($product_setting as $value){
								$group_setting = explode('|', $value[0]);
								$item_setting = explode('|', $value[1]);
								$from_number_setting = $value[2];
								$to_number_setting = $value[3];
								$percent_setting = $value[4];
								$check = true;
								if($item_id != ''){
									if($it != ''){
										if($value[0] != '' && !in_array($it->group_id, $group_setting)){
											$check = false;
										}
									}else{
										if($value[0] != ''){
											$check = false;
										}
									}

									if($value[1] != '' && !in_array($item_id, $item_setting)){
										$check = false;
									}
								}else{
									if($value[1] != '' || $value[0] != ''){
										$check = false;
									}
								}

								if(($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)){
									$check = false;
								}

								if ($check == true) {
									$count += ($percent * $payments_amount) * ($percent_setting / 100);
								}
							}
						}
					}
				} elseif ($commission_policy->commission_policy_type == '1') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					$ladder_setting = json_decode($commission_policy->ladder_setting);
					foreach ($ladder_setting as $key => $value) {
						$from_amount = str_replace(',', '', $value->from_amount);
						if ($payments_amount > $from_amount) {
							$to_amount = str_replace(',', '', $value->to_amount);
							if ($to_amount == '') {
								$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
								$payments_amount = 0;
							} else {
								if ($payments_amount > $to_amount) {
									$count += $to_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = $payments_amount - $to_amount;
								} else {
									$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = 0;
								}
							}
						} else {
							break;
						}
					}
				}
			}

			$this->db->where('invoice_id', $invoices->id);
			$this->db->where('is_client', 0);
			$this->db->where('paid', 0);
			$this->db->delete(db_prefix() . 'commission');

			if ($count > 0) {

				$data = [];
				$data[$salesperson] = $count;
				$list_isset = [];
				$list_isset[] = $salesperson;
				do {
					foreach ($data as $k => $count) {
						$hierarchy = $this->get_hierarchy('', ['salesman' => $k]);
						$count_discount = 0;
						if($hierarchy){
							foreach ($hierarchy as $key => $value) {
								if(!in_array($value['coordinator'], $list_isset)){
									$data[$value['coordinator']] = round($count * ($value['percent'] / 100), 2);
									$count_discount += $data[$value['coordinator']];
									$list_isset[] = $value['coordinator'];
								}
							}
							$count = $count - $count_discount;
						}

						
						$note = [];
						$note['staffid'] = $k;
						$note['invoice_id'] = $invoices->id;
						$note['amount'] = $count;
						$note['is_client'] = 0;
						$note['date'] = date('Y-m-d');
						$this->db->insert(db_prefix() . 'commission', $note);
						$insert_id = $this->db->insert_id();

						if ($insert_id) {
	                    	$affectedRows++;
						}
						unset($data[$k]);
					}
				} while (count($data) > 0);
			}

			$count = 0;
			$commission_policy_contact = $this->get_commission_policy_by_staff($invoices->clientid, $invoices->clientid, 1);

			if ($commission_policy_contact) {
				if ($commission_policy_contact->commission_policy_type == '2') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					if($commission_policy_contact->commmission_first_invoices == 1){
						$list_first_invoices = $this->get_first_invoices($invoices->clientid, $invoices->id, $commission_policy_contact->number_first_invoices, $commission_policy_contact, 1);
						if(in_array($invoices->id, $list_first_invoices)){
							$count += $payments_amount * ($commission_policy_contact->percent_first_invoices / 100);
						}else{
							$count += $payments_amount * ($commission_policy_contact->percent_enjoyed / 100);
						}
					}else{
						$count += $payments_amount * ($commission_policy_contact->percent_enjoyed / 100);
					}
				} elseif ($commission_policy_contact->commission_policy_type == '3') {
					$product_setting = json_decode($commission_policy_contact->product_setting);
					if ($invoices->items) {
						$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
						foreach ($invoices->items as $item) {
							$item_tax = $this->get_item_tax($item['id'], $item['rel_id']);
							if($item_tax != 0){
								$item_amount = $item['qty'] * $item['rate'] + (($item['qty'] * $item['rate']) * ($item_tax / 100));
							}else{
								$item_amount = $item['qty'] * $item['rate'];
							}
							$percent = $item_amount / $invoices->subtotal;
							$item_id = $this->get_item_id_by_name($item['description']);
							$it = '';
							if($item_id != ''){
								$it = $this->invoice_items_model->get($item_id);
							}

							foreach ($product_setting as $value){
								$group_setting = explode('|', $value[0]);
								$item_setting = explode('|', $value[1]);
								$from_number_setting = $value[2];
								$to_number_setting = $value[3];
								$percent_setting = $value[4];
								$check = true;
								if($item_id != ''){
									if($it != ''){
										if($value[0] != '' && !in_array($it->group_id, $group_setting)){
											$check = false;
										}
									}else{
										if($value[0] != ''){
											$check = false;
										}
									}

									if($value[1] != '' && !in_array($item_id, $item_setting)){
										$check = false;
									}
								}else{
									if($value[1] != '' || $value[0] != ''){
										$check = false;
									}
								}

								if(($from_number_setting != '' && $item['qty'] < $from_number_setting) || ($to_number_setting != '' && $item['qty'] > $to_number_setting)){
									$check = false;
								}

								if ($check == true) {
									$count += ($percent * $payments_amount) * ($percent_setting / 100);
								}
							}
						}
					}
				} elseif ($commission_policy_contact->commission_policy_type == '1') {
					$total_payments = sum_from_table(db_prefix() . 'invoicepaymentrecords', array('field' => 'amount', 'where' => array('invoiceid' => $invoices->id))) ;
					$payments_amount = $total_payments - round(($invoices->total_tax * ($total_payments/$invoices->total)), 2);
					$ladder_setting = json_decode($commission_policy_contact->ladder_setting);

					foreach ($ladder_setting as $key => $value) {
						$from_amount = str_replace(',', '', $value->from_amount);
						if ($payments_amount > $from_amount) {
							$to_amount = str_replace(',', '', $value->to_amount);
							if ($to_amount == '') {
								$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
								$payments_amount = 0;
							} else {
								if ($payments_amount > $to_amount) {
									$count += $to_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = $payments_amount - $to_amount;
								} else {
									$count += $payments_amount * ($value->percent_enjoyed_ladder / 100);
									$payments_amount = 0;
								}
							}
						} else {
							break;
						}
					}
				}
			}

			if ($count >= 0) {
				$this->db->where('invoice_id', $invoices->id);
				$this->db->where('is_client', 1);
				$this->db->where('staffid', $invoices->clientid);
				$commission = $this->db->get(db_prefix() . 'commission')->row();

				if ($commission) {

					$this->db->where('id', $commission->id);
					$this->db->update(db_prefix() . 'commission', ['amount' => round($count, 2), 'date' => date('Y-m-d')]);
					if ($this->db->affected_rows() > 0) {
			            $affectedRows++;
			        }
				} else {
					$note = [];
					$note['staffid'] = $invoices->clientid;
					$note['invoice_id'] = $invoices->id;
					$note['amount'] = round($count, 2);
					$note['is_client'] = 1;
					$note['date'] = date('Y-m-d');
					$this->db->insert(db_prefix() . 'commission', $note);
					$insert_id = $this->db->insert_id();

					if ($insert_id) {
                    	$affectedRows++;
					}
				}
			}
		}
		if ($affectedRows > 0) {
        	return true;
		}
		return false;
	}

	/**
	 * Gets the customer.
	 *
	 * @param      string  $id     The identifier
	 * @param      array   $where  The where
	 *
	 * @return     object  The customer.
	 */
	public function get_customer($id = '', $where = []){
		if($id != ''){
			$this->load->model('clients_model');
			return $this->clients_model->get($id);
		}

		$this->db->select('userid, company, (SELECT GROUP_CONCAT(name SEPARATOR ",") FROM '.db_prefix().'customer_groups JOIN '.db_prefix().'customers_groups ON '.db_prefix().'customer_groups.groupid = '.db_prefix().'customers_groups.id WHERE customer_id = '.db_prefix().'clients.userid ORDER by name ASC) as customerGroups');
		$this->db->where($where);
		return $this->db->get(db_prefix().'clients')->result_array();
	}

	/**
	 * Gets the product group select.
	 *
	 * @return     array  The product group select.
	 */
	public function get_product_group_select() {

		$items_groups = $this->db->get(db_prefix() . 'items_groups')->result_array();
		$list_item_groups = [];
		foreach ($items_groups as $key => $group) {
			$note = [];
			$note['id'] = $group['id'];
			$note['label'] = $group['name'];
			$list_item_groups[] = $note;
		}
		return $list_item_groups;
	}

	/**
	 * Gets the item id by name.
	 *
	 * @param      string  $item_name  The itemid
	 *
	 * @return     string  The item name.
	 */
	public function get_item_id_by_name($item_name) {

		$this->db->where('description', $item_name);
		$items = $this->db->get(db_prefix() . 'items')->row();

		if ($items) {
			return $items->id;
		}
		return '';
	}

	/**
	 * Gets the item tax.
	 *
	 * @param      integer          $itemid  The itemid
	 * @param      integer          $rel_id  The relative identifier
	 *
	 * @return     integer  The item tax.
	 */
	public function get_item_tax($itemid, $rel_id){
		$this->db->where('itemid', $itemid);
		$this->db->where('rel_id', $rel_id);
		$item_tax = $this->db->get(db_prefix() . 'item_tax')->result_array();
		$tax = 0;
		if ($item_tax) {
			foreach ($item_tax as $key => $value) {
				$tax += $value['taxrate'];
			}
		}
		return $tax;
	}

	/**
	 * Gets the clientid by contact.
	 *
	 * @param      integer   $contact_id  The contact identifier
	 *
	 * @return     integer  The clientid by contact.
	 */
	public function get_clientid_by_contact($contact_id){
		$this->db->where('id', $contact_id);
		$contact = $this->db->get(db_prefix() . 'contacts')->row();
		if ($contact) {
			return $contact->userid;
		}
		return 0;
	}

	/**
	 * Gets the first invoices.
	 *
	 * @param      integer   $staffid            The staffid
	 * @param      integer   $invoiceid          The invoiceid
	 * @param      integer  $max                The maximum
	 * @param      object   $commission_policy  The commission policy
	 * @param      integer  $is_client         Indicates if contact
	 *
	 * @return     array    The first invoices.
	 */
	public function get_first_invoices($staffid, $invoiceid, $max = 0, $commission_policy, $is_client = 0){
		if($is_client == 1){
			$where = 'clientid = '. $staffid;
		}else{
			$where = 'sale_agent = '. $staffid;
		}

		$where_group = '';
		if($commission_policy->client_groups != ''){
			foreach (explode(',', $commission_policy->client_groups) as $value) {
				if($where_group != ''){
					$where_group .= ' OR '.$value.' IN (select groupid from '.db_prefix().'customer_groups where clientid)';
				}else{
					$where_group = ' '.$value.' IN (select groupid from '.db_prefix().'customer_groups where clientid)';
				}
			}

			if($where_group != ''){
				$where .= ' and ('.$where_group.')';
			}
		}
		if($commission_policy->clients != ''){
			$where .= ' and find_in_set(clientid, "'.$commission_policy->clients.'")';
		}

		$this->db->where($where);
		$this->db->order_by('datecreated', 'asc');
		$invoices = $this->db->get(db_prefix() . 'invoices')->result_array();

		$list_invoices = [];
		foreach ($invoices as $key => $value) {
			if($key == $max){
				break;
			}
			$list_invoices[] = $value['id'];
		}
		return $list_invoices;
	}

	/**
	 * Gets the commission.
	 *
	 * @param      string        $id     The identifier
	 * @param      array|string  $where  The where
	 *
	 * @return     array|object        The commission.
	 */
	public function get_commission($id = '', $where = []){

		$this->db->select(db_prefix() . 'commission.id as id, invoice_id, '.db_prefix() . 'commission.date as commission_date, '. get_sql_select_client_company().', staffid, total, amount, '.db_prefix() . 'invoices.clientid, is_client, '. db_prefix() . 'invoices.hash as invoice_hash, paid');
		$this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'commission.invoice_id', 'left');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid', 'left');
		if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

		if($id != ''){
			return $this->db->get(db_prefix() . 'commission')->row();
		}

		return $this->db->get(db_prefix() . 'commission')->result_array();
	}

	/**
	 * Adds a hierarchy.
	 *
	 * @param      array   $data   The data
	 *
	 * @return     boolean
	 */
	public function add_hierarchy($data){
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['addedfrom'] = get_staff_user_id();
		$this->db->insert(db_prefix() . 'commission_hierarchy', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}

	/**
	 * update a hierarchy
	 *
	 * @param      array   $data   The data
	 * @param      int   $id     The identifier
	 *
	 * @return     boolean
	 */
	public function update_hierarchy($data, $id){
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'commission_hierarchy', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Gets the hierarchy.
	 *
	 * @param      string        $id     The identifier
	 * @param      array|string  $where  The where
	 *
	 * @return     array|object       The hierarchy.
	 */
	public function get_hierarchy($id = '', $where = []){
		if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'commission_hierarchy')->row();
		}

		return $this->db->get(db_prefix() . 'commission_hierarchy')->result_array();
	}

	/**
	 * delete a hierarchy
	 *
	 * @param      int   $id     The identifier
	 *
	 * @return     boolean
	 */
	public function delete_hierarchy($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'commission_hierarchy');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Adds a salesadmin group.
	 *
	 * @param      array   $data   The data
	 *
	 * @return     boolean
	 */
	public function add_salesadmin_group($data){
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['addedfrom'] = get_staff_user_id();
		$this->db->insert(db_prefix() . 'commission_salesadmin_group', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}

	/**
	 * Update a salesadmin group.
	 *
	 * @param      array   $data   The data
	 * @param      int   $id     The identifier
	 *
	 * @return     boolean
	 */
	public function update_salesadmin_group($data, $id){
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'commission_salesadmin_group', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Gets the salesadmin group.
	 *
	 * @param      string        $id     The identifier
	 * @param      array|string  $where  The where
	 *
	 * @return     array|object        The salesadmin group.
	 */
	public function get_salesadmin_group($id = '', $where = []){

		if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

		if($id != ''){
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'commission_salesadmin_group')->row();
		}

		$this->db->select('*, (select name from '. db_prefix() . 'customers_groups where '. db_prefix() . 'customers_groups.id = customer_group) as customer_group_name');
		
		return $this->db->get(db_prefix() . 'commission_salesadmin_group')->result_array();
	}

	/**
	 * Delete the salesadmin group.
	 *
	 * @param      <type>   $id     The identifier
	 *
	 * @return     boolean
	 */
	public function delete_salesadmin_group($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'commission_salesadmin_group');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Adds a receipt.
	 *
	 * @param      array         $data   The data
	 *
	 * @return     boolean|string
	 */
	public function add_receipt($data)
    {
        if (isset($data['date'])) {
            $data['date'] = to_sql_date($data['date']);
        } else {
            $data['date'] = date('Y-m-d H:i:s');
        }
        if (isset($data['note'])) {
            $data['note'] = nl2br($data['note']);
        }
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data['addedfrom'] = get_staff_user_id();

        if (isset($data['list_commission'])) {
            $list_commission = $data['list_commission'];
            unset($data['list_commission']);
        }
        
        $this->db->insert(db_prefix() . 'commission_receipt', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
    		foreach ($list_commission as $key => $value){
    			$this->db->where('id', $value);
    			$this->db->update(db_prefix() . 'commission', ['paid' => 1]);

    			$this->db->insert(db_prefix() . 'commission_receipt_detail', ['receipt_id' => $insert_id,'commission_id' =>  $value]);
    		}
        	
        	$currency = $this->currencies_model->get_base_currency();
            log_activity('Add Commission Receipt [ID:' . $insert_id . ', Total: ' . app_format_money($data['amount'], $currency->name) . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update a receipt.
     *
     * @param      array   $data   The data
     * @param      string   $id     The identifier
     *
     * @return     boolean
     */
	public function update_receipt($data, $id)
    {
        $list_commission_id = $this->get_receipt_detail($id, true);

        $data['date'] = to_sql_date($data['date']);
        $data['note'] = nl2br($data['note']);
        if (isset($data['list_commission'])) {
            $list_commission = $data['list_commission'];
            unset($data['list_commission']);
        }
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'commission_receipt', $data);
        if ($this->db->affected_rows() > 0) {

        	foreach ($list_commission as $key => $value){
        		if(!in_array($value, $list_commission_id)){
	    			$this->db->where('id', $value);
	    			$this->db->update(db_prefix() . 'commission', ['paid' => 1]);

	    			$this->db->insert(db_prefix() . 'commission_receipt_detail', ['receipt_id' => $id,'commission_id' =>  $value]);
        		}
    		}
    		foreach ($list_commission_id as $key => $value){
        		if(!in_array($value, $list_commission)){
	    			$this->db->where('id', $value);
	    			$this->db->update(db_prefix() . 'commission', ['paid' => 0]);

	    			$this->db->where('receipt_id', $id);
	    			$this->db->where('commission_id', $value);
	    			$this->db->delete(db_prefix() . 'commission_receipt_detail');
        		}
    		}
            log_activity('Commission Receipt Updated [Number:' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete a receipt
     *
     * @param      string   $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_receipt($id){
        $list_commission_id = $this->get_receipt_detail($id, true);

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'commission_receipt');

		if ($this->db->affected_rows() > 0) {
			$this->db->where('receipt_id', $value);
			$this->db->delete(db_prefix() . 'commission_receipt_detail');

			foreach ($list_commission_id as $key => $value){
    			$this->db->where('id', $value);
    			$this->db->update(db_prefix() . 'commission', ['paid' => 0]);
    		}

			log_activity('Commission Receipt Deleted [Number:' . $id . ']');
			return true;
		}
		return false;
	}

	/**
	 * Gets the receipt.
	 *
	 * @param      string        $id     The identifier
	 * @param      array|string  $where  The where
	 *
	 * @return     array|object        The receipt.
	 */
    public function get_receipt($id = '', $where = []){
    	if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

		if($id != ''){
			$this->db->where('id', $id);

			$receipt = $this->db->get(db_prefix() . 'commission_receipt')->row();

			if($receipt){
				$this->db->where('id', $receipt->paymentmode);
				$paymentmode = $this->db->get(db_prefix() . 'payment_modes')->row();
				if($paymentmode){
					$receipt->paymentmode_name = $paymentmode->name;
				}else{
					$receipt->paymentmode_name = '';
				}
    			$this->load->model('currencies_model');
				$currency = $this->currencies_model->get_base_currency();

				$receipt->currency_name = $currency->name;
				$receipt->list_commission_id = $this->get_receipt_detail($id, true);
				$receipt->list_commission = $this->get_receipt_detail($id);
			}

			return $receipt;
		}

		return $this->db->get(db_prefix() . 'commission_receipt')->result_array();
	}

	/**
	 * Gets the receipt detail.
	 *
	 * @param      int   $id       The identifier
	 * @param      boolean  $only_id  The only identifier
	 *
	 * @return     array    The receipt detail.
	 */
	public function get_receipt_detail($id, $only_id = false){
		$this->db->select('*, '. db_prefix() . 'commission.id as commission_id');
		$this->db->where('receipt_id', $id);
		$this->db->join(db_prefix() . 'commission', '' . db_prefix() . 'commission.id = ' . db_prefix() . 'commission_receipt_detail.commission_id', 'left');
		$this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'commission.invoice_id', 'left');
		
		$commission_receipt_detail = $this->db->get(db_prefix() . 'commission_receipt_detail')->result_array();
		if($only_id == true){
			$list_commission_id = [];
			foreach ($commission_receipt_detail as $key => $value) {
				$list_commission_id[] = $value['commission_id'];
			}
			return $list_commission_id;
		}else{
			if($commission_receipt_detail){
				foreach ($commission_receipt_detail as $key => $value) {
					if($value['is_client'] == 1){
						$commission_receipt_detail[$key]['sale_name'] = get_company_name($value['staffid']);
					}else{
						$commission_receipt_detail[$key]['sale_name'] = get_staff_full_name($value['staffid']);
					}
					$commission_receipt_detail[$key]['company'] = get_company_name($value['clientid']);
				}
			}
		}
		return $commission_receipt_detail;
	}

	/**
     * Generate receipt pdf
     * @param  object $receipt object db
     * @return mixed object
     */
    function receipt_pdf($receipt)
    {
        return app_pdf('receipt', module_dir_path(COMMISSION_MODULE_NAME, 'libraries/pdf/Receipt_pdf'), $receipt);
    }

    /**
     * mark converted receipt
     *
     * @param      int   $id          The identifier
     * @param      int   $expense_id  The expense identifier
     *
     * @return     boolean
     */
    public function mark_converted_receipt($id, $expense_id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'commission_receipt',['convert_expense' => $expense_id]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}