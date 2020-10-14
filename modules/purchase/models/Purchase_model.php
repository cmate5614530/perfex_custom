<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This class describes a purchase model.
 */
class Purchase_model extends App_Model
{   
    private $shipping_fields = ['shipping_street', 'shipping_city', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'];

    private $contact_columns;

    public function __construct()
    {
        parent::__construct();
        
        $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);
    }

    /**
     * Gets the vendor.
     *
     * @param      string        $id     The identifier
     * @param      array|string  $where  The where
     *
     * @return     <type>        The vendor or list vendors.
     */
    public function get_vendor($id = '', $where = [])
    {
        $this->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'pur_vendor')) . ',' . get_sql_select_vendor_company());

        $this->db->join(db_prefix() . 'countries', '' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'pur_vendor.country', 'left');
        $this->db->join(db_prefix() . 'pur_contacts', '' . db_prefix() . 'pur_contacts.userid = ' . db_prefix() . 'pur_vendor.userid AND is_primary = 1', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        if (is_numeric($id)) {

            $this->db->where(db_prefix().'pur_vendor.userid', $id);
            $vendor = $this->db->get(db_prefix() . 'pur_vendor')->row();

            if ($vendor && get_option('company_requires_vat_number_field') == 0) {
                $vendor->vat = null;
            }


            return $vendor;

        }

        $this->db->order_by('company', 'asc');

        return $this->db->get(db_prefix() . 'pur_vendor')->result_array();
    }

    /**
     * Gets the contacts.
     *
     * @param      string  $vendor_id  The vendor identifier
     * @param      array   $where      The where
     *
     * @return     <type>  The contacts.
     */
    public function get_contacts($vendor_id = '', $where = ['active' => 1])
    {
        $this->db->where($where);
        if ($vendor_id != '') {
            $this->db->where('userid', $vendor_id);
        }
        $this->db->order_by('is_primary', 'DESC');

        return $this->db->get(db_prefix() . 'pur_contacts')->result_array();
    }

    /**
     * Gets the contact.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The contact.
     */
    public function get_contact($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'pur_contacts')->row();
    }

    /**
     * Gets the primary contacts.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The primary contacts.
     */
    public function get_primary_contacts($id)
    {
        $this->db->where('userid', $id);
        $this->db->where('is_primary', 1);
        return $this->db->get(db_prefix() . 'pur_contacts')->row();
    }

    /**
     * Adds a vendor.
     *
     * @param      <type>   $data       The data
     * @param      integer  $client_id  The client identifier
     *
     * @return     integer  ( id vendor )
     */
    public function add_vendor($data, $client_id = null,$client_or_lead_convert_request = false)
    {
        $contact_data = [];
        foreach ($this->contact_columns as $field) {
            if (isset($data[$field])) {
                $contact_data[$field] = $data[$field];
                // Phonenumber is also used for the company profile
                if ($field != 'phonenumber') {
                    unset($data[$field]);
                }
            }
        }
        // From customer profile register
        if (isset($data['contact_phonenumber'])) {
            $contact_data['phonenumber'] = $data['contact_phonenumber'];
            unset($data['contact_phonenumber']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        if (isset($data['groups_in'])) {
            $groups_in = $data['groups_in'];
            unset($data['groups_in']);
        }

        $data = $this->check_zero_columns($data);

        $data['datecreated'] = date('Y-m-d H:i:s');

        if (is_staff_logged_in()) {
            $data['addedfrom'] = get_staff_user_id();
        }

        // New filter action
        $data = hooks()->apply_filters('before_client_added', $data);

        if(isset($client_id) && $client_id > 0){
            $userid = $client_id;
        } else {
            $this->db->insert(db_prefix() . 'pur_vendor', $data);
            $userid = $this->db->insert_id();    
        }
        
        if ($userid) {
            if (isset($custom_fields)) {
                $_custom_fields = $custom_fields;
                // Possible request from the register area with 2 types of custom fields for contact and for comapny/customer
                if (count($custom_fields) == 1) {
                    unset($custom_fields);
                    $custom_fields['vendors']                = $_custom_fields['vendors'];
                } 

                handle_custom_fields_post($userid, $custom_fields);
            }
                
            /**
             * Used in Import, Lead Convert, Register
             */
            if ($client_or_lead_convert_request == true) {
                $contact_id = $this->add_contact($contact_data, $userid, $client_or_lead_convert_request);
            }
            
            /**
             * Used in Import, Lead Convert, Register
             */        

            $log = 'ID: ' . $userid;

            $isStaff = null;
            if (!is_client_logged_in() && is_staff_logged_in()) {
                $log .= ', From Staff: ' . get_staff_user_id();
                $isStaff = get_staff_user_id();
            }

            hooks()->do_action('after_client_added', $userid);
        }

        return $userid;
    }

    /**
     * { update vendor }
     *
     * @param      <type>   $data            The data
     * @param      <type>   $id              The identifier
     * @param      boolean  $client_request  The client request
     *
     * @return     boolean 
     */
    public function update_vendor($data, $id, $client_request = false)
    {
        if (isset($data['update_all_other_transactions'])) {
            $update_all_other_transactions = true;
            unset($data['update_all_other_transactions']);
        }

        if (isset($data['update_credit_notes'])) {
            $update_credit_notes = true;
            unset($data['update_credit_notes']);
        }

        $affectedRows = 0;
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        $data = $this->check_zero_columns($data);

        $data = hooks()->apply_filters('before_client_updated', $data, $id);

        $this->db->where('userid', $id);
        $this->db->update(db_prefix() . 'pur_vendor', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }


        if ($affectedRows > 0) {
            hooks()->do_action('after_client_updated', $id);


            return true;
        }

        return false;
    }

    /**
     * { check zero columns }
     *
     * @param      <type>  $data   The data
     *
     * @return     array  
     */
    private function check_zero_columns($data)
    {
        if (!isset($data['show_primary_contact'])) {
            $data['show_primary_contact'] = 0;
        }

        if (isset($data['default_currency']) && $data['default_currency'] == '' || !isset($data['default_currency'])) {
            $data['default_currency'] = 0;
        }

        if (isset($data['country']) && $data['country'] == '' || !isset($data['country'])) {
            $data['country'] = 0;
        }

        if (isset($data['billing_country']) && $data['billing_country'] == '' || !isset($data['billing_country'])) {
            $data['billing_country'] = 0;
        }

        if (isset($data['shipping_country']) && $data['shipping_country'] == '' || !isset($data['shipping_country'])) {
            $data['shipping_country'] = 0;
        }

        return $data;
    }

    /**
     * Gets the vendor admins.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <type>  The vendor admins.
     */
    public function get_vendor_admins($id)
    {
        $this->db->where('vendor_id', $id);

        return $this->db->get(db_prefix() . 'pur_vendor_admin')->result_array();
    }


    /**
     * { assign vendor admins }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function assign_vendor_admins($data, $id)
    {
        $affectedRows = 0;

        if (count($data) == 0) {
            $this->db->where('vendor_id', $id);
            $this->db->delete(db_prefix() . 'pur_vendor_admin');
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }
        } else {
            $current_admins     = $this->get_vendor_admins($id);
            $current_admins_ids = [];
            foreach ($current_admins as $c_admin) {
                array_push($current_admins_ids, $c_admin['staff_id']);
            }
            foreach ($current_admins_ids as $c_admin_id) {
                if (!in_array($c_admin_id, $data['customer_admins'])) {
                    $this->db->where('staff_id', $c_admin_id);
                    $this->db->where('vendor_id', $id);
                    $this->db->delete(db_prefix() . 'pur_vendor_admin');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
            foreach ($data['customer_admins'] as $n_admin_id) {
                if (total_rows(db_prefix() . 'pur_vendor_admin', [
                    'vendor_id' => $id,
                    'staff_id' => $n_admin_id,
                ]) == 0) {
                    $this->db->insert(db_prefix() . 'pur_vendor_admin', [
                        'vendor_id'   => $id,
                        'staff_id'      => $n_admin_id,
                        'date_assigned' => date('Y-m-d H:i:s'),
                    ]);
                    if ($this->db->affected_rows() > 0) {
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
     * { delete vendor }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_vendor($id)
    {
        $affectedRows = 0;

        hooks()->do_action('before_client_deleted', $id);

        $last_activity = get_last_system_activity_id();
        $company       = get_company_name($id);

        $this->db->where('userid', $id);
        $this->db->delete(db_prefix() . 'pur_vendor');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            // Delete all user contacts
            $this->db->where('userid', $id);
            $contacts = $this->db->get(db_prefix() . 'pur_contacts')->result_array();
            foreach ($contacts as $contact) {
                $this->delete_contact($contact['id']);
            }

            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'vendor');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            $this->db->where('vendor_id', $id);
            $this->db->delete(db_prefix() . 'pur_vendor_admin');

        }
        if ($affectedRows > 0) {
            hooks()->do_action('after_client_deleted', $id);

            return true;
        }

        return false;
    }

    /**
     * Adds a contact.
     *
     * @param      <type>   $data                The data
     * @param      <type>   $customer_id         The customer identifier
     * @param      boolean  $not_manual_request  Not manual request
     *
     * @return     boolean  or contact id
     */
    public function add_contact($data, $customer_id, $not_manual_request = false)
    {
        $send_set_password_email = isset($data['send_set_password_email']) ? true : false;

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

        $data['email_verified_at'] = date('Y-m-d H:i:s');


        if (isset($data['is_primary'])) {
            $data['is_primary'] = 1;
            $this->db->where('userid', $customer_id);
            $this->db->update(db_prefix() . 'pur_contacts', [
                'is_primary' => 0,
            ]);
        } else {
            $data['is_primary'] = 0;
        }

        $password_before_hash = '';
        $data['userid']       = $customer_id;
        if (isset($data['password'])) {
            $password_before_hash = $data['password'];
            $data['password'] = app_hash_password($data['password']);
        }

        $data['datecreated'] = date('Y-m-d H:i:s');

        if (!$not_manual_request) {
            $data['invoice_emails']     = isset($data['invoice_emails']) ? 1 :0;
            $data['estimate_emails']    = isset($data['estimate_emails']) ? 1 :0;
            $data['credit_note_emails'] = isset($data['credit_note_emails']) ? 1 :0;
            $data['contract_emails']    = isset($data['contract_emails']) ? 1 :0;
            $data['task_emails']        = isset($data['task_emails']) ? 1 :0;
            $data['project_emails']     = isset($data['project_emails']) ? 1 :0;
            $data['ticket_emails']      = isset($data['ticket_emails']) ? 1 :0;
        }

        $data['email'] = trim($data['email']);

        $data = hooks()->apply_filters('before_create_contact', $data);

        $this->db->insert(db_prefix() . 'pur_contacts', $data);
        $contact_id = $this->db->insert_id();

        if ($contact_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($contact_id, $custom_fields);
            }
           
            if ($not_manual_request == true) {
                // update all email notifications to 0
                $this->db->where('id', $contact_id);
                $this->db->update(db_prefix() . 'pur_contacts', [
                    'invoice_emails'     => 0,
                    'estimate_emails'    => 0,
                    'credit_note_emails' => 0,
                    'contract_emails'    => 0,
                    'task_emails'        => 0,
                    'project_emails'     => 0,
                    'ticket_emails'      => 0,
                ]);
            } 


            hooks()->do_action('contact_created', $contact_id);

            return $contact_id;
        }

        return false;
    }

    /**
     * { update contact }
     *
     * @param      <type>   $data            The data
     * @param      <type>   $id              The identifier
     * @param      boolean  $client_request  The client request
     *
     * @return     boolean 
     */
    public function update_contact($data, $id, $client_request = false)
    {
        $affectedRows = 0;
        $contact      = $this->get_contact($id);
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password']             = app_hash_password($data['password']);
            $data['last_password_change'] = date('Y-m-d H:i:s');
        }

        $send_set_password_email = isset($data['send_set_password_email']) ? true : false;
        $set_password_email_sent = false;
      
        $data['is_primary'] = isset($data['is_primary']) ? 1 : 0;

        // Contact cant change if is primary or not
        if ($client_request == true) {
            unset($data['is_primary']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        if ($client_request == false) {
            $data['invoice_emails']     = isset($data['invoice_emails']) ? 1 :0;
            $data['estimate_emails']    = isset($data['estimate_emails']) ? 1 :0;
            $data['credit_note_emails'] = isset($data['credit_note_emails']) ? 1 :0;
            $data['contract_emails']    = isset($data['contract_emails']) ? 1 :0;
            $data['task_emails']        = isset($data['task_emails']) ? 1 :0;
            $data['project_emails']     = isset($data['project_emails']) ? 1 :0;
            $data['ticket_emails']      = isset($data['ticket_emails']) ? 1 :0;
        }

        $data = hooks()->apply_filters('before_update_contact', $data, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if (isset($data['is_primary']) && $data['is_primary'] == 1) {
                $this->db->where('userid', $contact->userid);
                $this->db->where('id !=', $id);
                $this->db->update(db_prefix() . 'pur_contacts', [
                    'is_primary' => 0,
                ]);
            }
        }

       
        if ($affectedRows > 0 ) {
            return true;
        } 

        return false;
    }

    /**
     * { delete contact }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_contact($id)
    {
        hooks()->do_action('before_delete_contact', $id);

        $this->db->where('id', $id);
        $result      = $this->db->get(db_prefix() . 'pur_contacts')->row();
        $customer_id = $result->userid;

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_contacts');

        if ($this->db->affected_rows() > 0) {
            
            hooks()->do_action('contact_deleted', $id, $result);

            return true;
        }

        return false;
    }

    /**
     * Gets the approval setting.
     *
     * @param      string  $id     The identifier
     *
     * @return     <type>  The approval setting.
     */
    public function get_approval_setting($id = '')
    {
        if(is_numeric($id)){
            $this->db->where('id', $id);
            return $this->db->get(db_prefix().'pur_approval_setting')->row();
        }
        return $this->db->get(db_prefix().'pur_approval_setting')->result_array();
    }

    /**
     * Adds an approval setting.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function add_approval_setting($data)
    {
        unset($data['approval_setting_id']);

        if(isset($data['approver'])){
            $setting = [];
            foreach ($data['approver'] as $key => $value) {
                $node = [];
                $node['approver'] = $data['approver'][$key];
                $node['staff'] = $data['staff'][$key];
                $node['action'] = $data['action'][$key];

                $setting[] = $node;
            }
            unset($data['approver']);
            unset($data['staff']);
            unset($data['action']);
        }
        $data['setting'] = json_encode($setting);

        $this->db->insert(db_prefix() .'pur_approval_setting', $data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return true;
        }
        return false;
    }

    /**
     * { edit approval setting }
     *
     * @param      <type>   $id     The identifier
     * @param      <type>   $data   The data
     *
     * @return     boolean  
     */
    public function edit_approval_setting($id, $data)
    {
        unset($data['approval_setting_id']);

        if(isset($data['approver'])){
            $setting = [];
            foreach ($data['approver'] as $key => $value) {
                $node = [];
                $node['approver'] = $data['approver'][$key];
                $node['staff'] = $data['staff'][$key];
                $node['action'] = $data['action'][$key];

                $setting[] = $node;
            }
            unset($data['approver']);
            unset($data['staff']);
            unset($data['action']);
        }
        $data['setting'] = json_encode($setting);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() .'pur_approval_setting', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { delete approval setting }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean   
     */
    public function delete_approval_setting($id)
    {
        if(is_numeric($id)){
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() .'pur_approval_setting');

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the items.
     *
     * @return     <array>  The items.
     */
    public function get_items(){
       return $this->db->query('select id as id, CONCAT(vehicle_make," - " ,vehicle_model) as label from '.db_prefix().'items')->result_array();
    }

    /**
     * Gets the items by identifier.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The items by identifier.
     */
    public function get_items_by_id($id){
        $this->db->where('id',$id);
        return $this->db->get(db_prefix().'items')->row();
    }

    /**
     * Gets the units by identifier.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The units by identifier.
     */
    public function get_units_by_id($id){
        $this->db->where('unit_type_id',$id);
        return $this->db->get(db_prefix().'ware_unit_type')->row();
    }

    /**
     * Gets the units.
     *
     * @return     <array>  The list units.
     */
    public function get_units(){
        return $this->db->query('select unit_type_id as id, unit_name as label from '.db_prefix().'ware_unit_type')->result_array();
    }

    /**
     * { items change event}
     *
     * @param      <type>  $code   The code
     *
     * @return     <row>  ( item )
     */
    public function items_change($code){
        $this->db->where('id',$code);
        $rs = $this->db->get(db_prefix().'items')->row();

        $this->db->where('unit_type_id',$rs->unit_id);
        $unit = $this->db->get(db_prefix().'ware_unit_type')->row();

        $rs->unit = $unit->unit_name;

        
        if(get_status_modules_pur('warehouse') == true){
            $this->db->where('commodity_id',$code);
            $commo = $this->db->get(db_prefix().'inventory_manage')->result_array();
            $rs->inventory = 0;
            if(count($commo) > 0){
                foreach($commo as $co){
                    $rs->inventory += $co['inventory_number'];
                }
            }       
        }else{
            $rs->inventory = 0;
        }

        return $rs;
    }

    /**
     * Gets the purchase request.
     *
     * @param      string  $id     The identifier
     *
     * @return     <row or array>  The purchase request.
     */
    public function get_purchase_request($id = ''){
        if($id == ''){
            return $this->db->get(db_prefix().'pur_request')->result_array();
        }else{
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_request')->row();
        }
    }

    /**
     * Gets the pur request detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur request detail.
     */
    public function get_pur_request_detail($pur_request){
        $this->db->where('pur_request',$pur_request);
        return $this->db->get(db_prefix().'pur_request_detail')->result_array();
    }

    /**
     * Gets the pur request detail in estimate.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur request detail in estimate.
     */
    public function get_pur_request_detail_in_estimate($pur_request){
        $this->db->where('pur_request',$pur_request);
        $this->db->select('item_code');
        $this->db->select('unit_id');
        $this->db->select('unit_price');
        $this->db->select('quantity');
        $this->db->select('into_money');
        return $this->db->get(db_prefix().'pur_request_detail')->result_array();
    }

    /**
     * Gets the pur estimate detail in order.
     *
     * @param      <int>  $pur_estimate  The pur estimate
     *
     * @return     <array>  The pur estimate detail in order.
     */
    public function get_pur_estimate_detail_in_order($pur_estimate){
        $this->db->where('pur_estimate',$pur_estimate);
        $this->db->select('item_code');
        $this->db->select('unit_id');
        $this->db->select('unit_price');
        $this->db->select('quantity');
        $this->db->select('into_money');
        $this->db->select('tax');
        $this->db->select('total');
        $this->db->select('total_money');
        $this->db->select('discount_money');
        $this->db->select('discount_%');
        return $this->db->get(db_prefix().'pur_estimate_detail')->result_array();
    }

    /**
     * Gets the pur estimate detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur estimate detail.
     */
    public function get_pur_estimate_detail($pur_request){
        $this->db->where('pur_estimate',$pur_request);
        return $this->db->get(db_prefix().'pur_estimate_detail')->result_array();
    }

    /**
     * Gets the pur order detail.
     *
     * @param      <int>  $pur_request  The pur request
     *
     * @return     <array>  The pur order detail.
     */
    public function get_pur_order_detail($pur_request){
        $this->db->where('pur_order',$pur_request);
        return $this->db->get(db_prefix().'pur_order_detail')->result_array();
    }

    /**
     * Adds a pur request.
     *
     * @param      <array>   $data   The data
     *
     * @return     boolean  
     */
    public function add_pur_request($data){
        

        $data['requester'] = get_staff_user_id();
        $data['request_date'] = date('Y-m-d H:i:s');
        $check_appr = $this->get_approve_setting('pur_request');
        $data['status'] = 1;
        if($check_appr && $check_appr != false){
            $data['status'] = 1;
        }else{
            $data['status'] = 2;
        }

        $data['hash'] = app_generate_hash();

        if(isset($data['request_detail'])){
            $request_detail = json_decode($data['request_detail']);
            unset($data['request_detail']);
            $rq_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'inventory_quantity';

            foreach ($request_detail as $key => $value) {

                if($value[0] != ''){
                    $rq_detail[] = array_combine($header, $value);
                }
            }
        }
      
        $this->db->insert(db_prefix().'pur_request',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            foreach($rq_detail as $key => $rqd){
                $rq_detail[$key]['pur_request'] = $insert_id;
            }
            $this->db->insert_batch(db_prefix().'pur_request_detail',$rq_detail);
            return $insert_id;
        }
        return false;
    }

    /**
     * { update pur request }
     *
     * @param      <array>   $data   The data
     * @param      <int>   $id     The identifier
     *
     * @return     boolean   
     */
    public function update_pur_request($data,$id){
        $affectedRows = 0;

        if(isset($data['request_detail'])){
            $request_detail = json_decode($data['request_detail']);
            unset($data['request_detail']);
            $rq_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'prd_id';
            $header[] = 'pur_request';
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'inventory_quantity';

            foreach ($request_detail as $key => $values) {

                if($values[2] != ''){
                    $rq_detail[] = array_combine($header, $values);
                }
            }
        }
        
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_request',$data);
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

        $row = [];
        $row['update'] = []; 
        $row['insert'] = []; 
        $row['delete'] = [];
        foreach ($rq_detail as $key => $value) {
            if($value['prd_id'] != ''){
                $row['delete'][] = $value['prd_id'];
                $row['update'][] = $value;
            }else{
                unset($value['prd_id']);
                $value['pur_request'] = $id;
                $row['insert'][] = $value;
            }
        }

        if(count($row['delete']) != 0){
            $row['delete'] = implode(",",$row['delete']);
            $this->db->where('prd_id NOT IN ('.$row['delete'] .') and pur_request ='.$id);
            $this->db->delete(db_prefix().'pur_request_detail');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['insert']) != 0){
            $this->db->insert_batch(db_prefix().'pur_request_detail', $row['insert']);
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['update']) != 0){
            $this->db->update_batch(db_prefix().'pur_request_detail', $row['update'], 'prd_id');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }


        if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * { delete pur request }
     *
     * @param      <int>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_pur_request($id){
        $affectedRows = 0;
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_request');
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

        $this->db->where('pur_request',$id);
        $this->db->delete(db_prefix().'pur_request_detail');
        if($this->db->affected_rows() > 0){
            $affectedRows++;
        }

         if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * { change status pur request }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean 
     */
    public function change_status_pur_request($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_request',['status' => $status]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the pur request by status.
     *
     * @param      <type>  $status  The status
     *
     * @return     <array>  The pur request by status.
     */
    public function get_pur_request_by_status($status){
        $this->db->where('status',$status);
        return $this->db->get(db_prefix().'pur_request')->result_array();
    }

    /**
     * { function_description }
     *
     * @param      <type>  $data   The data
     *
     * @return     <array> data
     */
    private function map_shipping_columns($data)
    {
        if (!isset($data['include_shipping'])) {
            foreach ($this->shipping_fields as $_s_field) {
                if (isset($data[$_s_field])) {
                    $data[$_s_field] = null;
                }
            }
            $data['show_shipping_on_estimate'] = 1;
            $data['include_shipping']          = 0;
        } else {
            $data['include_shipping'] = 1;
            // set by default for the next time to be checked
            if (isset($data['show_shipping_on_estimate']) && ($data['show_shipping_on_estimate'] == 1 || $data['show_shipping_on_estimate'] == 'on')) {
                $data['show_shipping_on_estimate'] = 1;
            } else {
                $data['show_shipping_on_estimate'] = 0;
            }
        }

        return $data;
    }

    /**
     * Gets the estimate.
     *
     * @param      string  $id     The identifier
     * @param      array   $where  The where
     *
     * @return     <row , array>  The estimate, list estimate.
     */
    public function get_estimate($id = '', $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'currencies.id as currencyid, ' . db_prefix() . 'pur_estimates.id as id, ' . db_prefix() . 'currencies.name as currency_name');
        $this->db->from(db_prefix() . 'pur_estimates');
        $this->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'pur_estimates.currency', 'left');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'pur_estimates.id', $id);
            $estimate = $this->db->get()->row();
            if ($estimate) {
                
                $estimate->visible_attachments_to_customer_found = false;
                
                $estimate->items = get_items_by_type('pur_estimate', $id);

                if ($estimate->pur_request != 0) {
                   
                    $estimate->pur_request = $this->get_purchase_request($estimate->pur_request);
                }else{
                    $estimate->pur_request = '';
                }

                $estimate->vendor = $this->get_vendor($estimate->vendor);
                if (!$estimate->vendor) {
                    $estimate->vendor          = new stdClass();
                    $estimate->vendor->company = $estimate->deleted_customer_name;
                }
            }

            return $estimate;
        }
        $this->db->order_by('number,YEAR(date)', 'desc');

        return $this->db->get()->result_array();
    }

    /**
     * Gets the pur order.
     *
     * @param      <int>  $id     The identifier
     *
     * @return     <row>  The pur order.
     */
    public function get_pur_order($id){
        $this->db->where('id',$id);
        return $this->db->get(db_prefix().'pur_orders')->row();
    }


    /**
     * Adds an estimate.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  or in estimate
     */
    public function add_estimate($data)
    {   
        $check_appr = $this->get_approve_setting('pur_quotation');
        $data['status'] = 1;
        if($check_appr && $check_appr != false){
            $data['status'] = 1;
        }else{
            $data['status'] = 2;
        }
        $data['date'] = to_sql_date($data['date']);
        $data['expirydate'] = to_sql_date($data['expirydate']);

        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['addedfrom'] = get_staff_user_id();

        $data['prefix'] = get_option('estimate_prefix');

        $data['number_format'] = get_option('estimate_number_format');

        $this->db->where('prefix',$data['prefix']);
        $this->db->where('number',$data['number']);
        $check_exist_number = $this->db->get(db_prefix().'pur_estimates')->row();

        while($check_exist_number) {
          $data['number'] = $data['number'] + 1;
          
          $this->db->where('prefix',$data['prefix']);
          $this->db->where('number',$data['number']);
          $check_exist_number = $this->db->get(db_prefix().'pur_estimates')->row();
        }

        $save_and_send = isset($data['save_and_send']);

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['hash'] = app_generate_hash();

        $data = $this->map_shipping_columns($data);

        if (isset($data['shipping_street'])) {
            $data['shipping_street'] = trim($data['shipping_street']);
            $data['shipping_street'] = nl2br($data['shipping_street']);
        }

        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        if(isset($data['estimate_detail'])){
            $estimate_detail = json_decode($data['estimate_detail']);
            unset($data['estimate_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';

            foreach ($estimate_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        

        $this->db->insert(db_prefix() . 'pur_estimates', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $total = [];
            $total['total'] = 0;
            $total['total_tax'] = 0;
            $total['subtotal'] = 0;
            
            foreach($es_detail as $key => $rqd){
                $es_detail[$key]['pur_estimate'] = $insert_id;
                $total['total'] += $rqd['total_money'];
                $total['total_tax'] += ($rqd['total']-$rqd['into_money']);
                $total['subtotal'] += $rqd['into_money'];
            }

            if($data['discount_total'] > 0){
                $total['total'] = $total['total'] - $data['discount_total'];
            }

            $this->db->insert_batch(db_prefix().'pur_estimate_detail',$es_detail);

            $this->db->where('id',$insert_id);
            $this->db->update(db_prefix().'pur_estimates',$total);

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            
            hooks()->do_action('after_estimate_added', $insert_id);

            return $insert_id;
        }

        return false;
    }

    /**
     * { update estimate }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function update_estimate($data, $id)
    {
        $data['date'] = to_sql_date($data['date']);
        $data['expirydate'] = to_sql_date($data['expirydate']);
        $affectedRows = 0;

        $data['number'] = trim($data['number']);

        $original_estimate = $this->get_estimate($id);

        $original_status = $original_estimate->status;

        $original_number = $original_estimate->number;

        $original_number_formatted = format_estimate_number($id);

        $data = $this->map_shipping_columns($data);
        
        unset($data['isedit']);

        if(isset($data['estimate_detail'])){
            $estimate_detail = json_decode($data['estimate_detail']);
            unset($data['estimate_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'id';
            $header[] = 'pur_estimate';
            $header[] = 'item_code';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';

            foreach ($estimate_detail as $key => $value) {

                if($value[2] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }

        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_estimates', $data);

        if ($this->db->affected_rows() > 0) {
            if ($original_status != $data['status']) {
                if ($data['status'] == 2) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'pur_estimates', ['sent' => 1, 'datesend' => date('Y-m-d H:i:s')]);
                }
            }
            $affectedRows++;
        }

        

        $row = [];
        $row['update'] = []; 
        $row['insert'] = []; 
        $row['delete'] = [];
        $total = [];
        $total['total'] = 0;
        $total['total_tax'] = 0;
        $total['subtotal'] = 0;
        
        foreach ($es_detail as $key => $value) {
            if($value['id'] != ''){
                $row['delete'][] = $value['id'];
                $row['update'][] = $value;
            }else{
                unset($value['id']);
                $value['pur_estimate'] = $id;
                $row['insert'][] = $value;
            }

            $total['total'] += $value['total_money'];
            $total['total_tax'] += ($value['total']-$value['into_money']);
            $total['subtotal'] += $value['into_money'];
            
        }

        if($data['discount_total'] > 0){
            $total['total'] = $total['total'] - $data['discount_total'];
        }
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_estimates',$total);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if(empty($row['delete'])){
            $row['delete'] = ['0'];
        }
            $row['delete'] = implode(",",$row['delete']);
            $this->db->where('id NOT IN ('.$row['delete'] .') and pur_estimate ='.$id);
            $this->db->delete(db_prefix().'pur_estimate_detail');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        
        if(count($row['insert']) != 0){
            $this->db->insert_batch(db_prefix().'pur_estimate_detail', $row['insert']);
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['update']) != 0){
            $this->db->update_batch(db_prefix().'pur_estimate_detail', $row['update'], 'id');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }

        
        if ($affectedRows > 0) {
           

            return true;
        }

        return false;
    }

    /**
     * Gets the estimate item.
     *
     * @param      <type>  $id     The identifier
     *
     * @return     <row>  The estimate item.
     */
    public function get_estimate_item($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'itemable')->row();
    }

    /**
     * { delete estimate }
     *
     * @param      string   $id            The identifier
     * @param      boolean  $simpleDelete  The simple delete
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function delete_estimate($id, $simpleDelete = false)
    {
        
        
        hooks()->do_action('before_estimate_deleted', $id);

        $number = format_estimate_number($id);

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_estimates');

        if ($this->db->affected_rows() > 0) {
           
            $this->db->where('pur_estimate', $id);
            $this->db->delete(db_prefix() . 'pur_estimate_detail');

            $this->db->where('relid IN (SELECT id from ' . db_prefix() . 'itemable WHERE rel_type="pur_estimate" AND rel_id="' . $id . '")');
            $this->db->where('fieldto', 'items');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            $this->db->where('rel_type', 'pur_estimate');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'taggables');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'itemable');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'item_tax');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'pur_estimate');
            $this->db->delete(db_prefix() . 'sales_activity');

            return true;
        }

        return false;
    }

    /**
     * Gets the taxes.
     *
     * @return     <array>  The taxes.
     */
    public function get_taxes()
    {
       return $this->db->query('select id, name as label, taxrate from '.db_prefix().'taxes')->result_array();
    }

    /**
     * Gets the total tax.
     *
     * @param      <type>   $taxes  The taxes
     *
     * @return     integer  The total tax.
     */
    public function get_total_tax($taxes){
        $rs = 0;
        foreach($taxes as $tax){
            $this->db->where('id',$tax);
            $this->db->select('taxrate');
            $ta = $this->db->get(db_prefix().'taxes')->row();
            $rs += $ta->taxrate;
        }
        return $rs;
    }

    /**
     * { change status pur estimate }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean   
     */
    public function change_status_pur_estimate($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_estimates',['status' => $status]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { change status pur order }
     *
     * @param      <type>   $status  The status
     * @param      <type>   $id      The identifier
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function change_status_pur_order($status,$id){
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_orders',['approve_status' => $status]);
        if($this->db->affected_rows() > 0){

            hooks()->apply_filters('create_goods_receipt',['status' => $status,'id' => $id]);
            return true;
        }
        return false;
    }

    /**
     * Gets the estimates by status.
     *
     * @param      <type>  $status  The status
     *
     * @return     <array>  The estimates by status.
     */
    public function get_estimates_by_status($status){
        $this->db->where('status',$status);
        return $this->db->get(db_prefix().'pur_estimates')->result_array();
    }

    /**
     * { estimate by vendor }
     *
     * @param      <type>  $vendor  The vendor
     *
     * @return     <array>  ( list estimate by vendor )
     */
    public function estimate_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        $this->db->where('status', 2);
        return $this->db->get(db_prefix().'pur_estimates')->result_array();
    }

    /**
     * Adds a pur order.
     *
     * @param      <array>   $data   The data
     *
     * @return     boolean , int id purchase order
     */
    public function add_pur_order($data){

        $prefix = get_purchase_option('pur_order_prefix');
        $data['pur_order_number'] = $prefix.''.$data['pur_order_number'];

        $this->db->where('pur_order_number',$data['pur_order_number']);
        $check_exist_number = $this->db->get(db_prefix().'pur_orders')->row();

        while($check_exist_number) {
          $data['number'] = $data['number'] + 1;
          $data['pur_order_number'] =  $prefix.''.str_pad($data['number'],5,'0',STR_PAD_LEFT);
          $this->db->where('pur_order_number',$data['pur_order_number']);
          $check_exist_number = $this->db->get(db_prefix().'pur_orders')->row();
        }

        $data['order_date'] = to_sql_date($data['order_date']);

        $data['delivery_date'] = to_sql_date($data['delivery_date']);

        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['addedfrom'] = get_staff_user_id();

        $data['hash'] = app_generate_hash();

        if(isset($data['clients']) && count($data['clients']) > 0){
            $data['clients'] = implode(',', $data['clients']);
        } 

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';
            foreach ($pur_order_detail as $key => $value) {

                if($value[0] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }
        
        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        $this->db->insert(db_prefix() . 'pur_orders', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            // Update next estimate number in settings
            $total = [];
            $total['total'] = 0;
            $total['total_tax'] = 0;
            $total['subtotal'] = 0;
           
            foreach($es_detail as $key => $rqd){
                $es_detail[$key]['pur_order'] = $insert_id;
                $total['total'] += $rqd['total_money'];
                $total['total_tax'] += ($rqd['total']-$rqd['into_money']);
                $total['subtotal'] += $rqd['into_money'];
            }

            if($data['discount_total'] > 0){
                $total['total'] = $total['total'] - $data['discount_total'];
            }

            handle_tags_save($tags, $insert_id, 'pur_order');

            if (isset($custom_fields)) {

                handle_custom_fields_post($insert_id, $custom_fields);
            }

            $this->db->insert_batch(db_prefix().'pur_order_detail',$es_detail);

            $this->db->where('id',$insert_id);
            $this->db->update(db_prefix().'pur_orders',$total);

            return $insert_id;
        }

        return false;
    }

    /**
     * { update pur order }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function update_pur_order($data, $id)
    {
        $affectedRows = 0;

        $prefix = get_purchase_option('pur_order_prefix');
        $data['pur_order_number'] = $prefix.''.$data['pur_order_number'];

        $data['order_date'] = to_sql_date($data['order_date']);

        $data['delivery_date'] = to_sql_date($data['delivery_date']);

        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['addedfrom'] = get_staff_user_id();

        if(isset($data['clients']) && count($data['clients']) > 0){
            $data['clients'] = implode(',', $data['clients']);
        }

        if(isset($data['pur_order_detail'])){
            $pur_order_detail = json_decode($data['pur_order_detail']);
            unset($data['pur_order_detail']);
            $es_detail = [];
            $row = [];
            $rq_val = [];
            $header = [];
            $header[] = 'id';
            $header[] = 'pur_order';
            $header[] = 'item_code';
            $header[] = 'description';
            $header[] = 'unit_id';
            $header[] = 'unit_price';
            $header[] = 'quantity';
            $header[] = 'into_money';
            $header[] = 'tax';
            $header[] = 'total';
            $header[] = 'discount_%';
            $header[] = 'discount_money';
            $header[] = 'total_money';
            foreach ($pur_order_detail as $key => $value) {
                if($value[2] != ''){
                    $es_detail[] = array_combine($header, $value);
                }
            }
        }

        if(isset($data['dc_total'])){
            $data['discount_total'] = reformat_currency_pur($data['dc_total']);
            unset($data['dc_total']);
        }

        if(isset($data['dc_percent'])){
            $data['discount_percent'] = $data['dc_percent'];
            unset($data['dc_percent']);
        }

        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'pur_order')) {
                $affectedRows++;
            }
            unset($data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_orders', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $row = [];
        $row['update'] = []; 
        $row['insert'] = []; 
        $row['delete'] = [];
        $total = [];
        $total['total'] = 0;
        $total['total_tax'] = 0;
        $total['subtotal'] = 0;
        
        foreach ($es_detail as $key => $value) {
            if($value['id'] != ''){
                $row['delete'][] = $value['id'];
                $row['update'][] = $value;
            }else{
                unset($value['id']);
                $value['pur_order'] = $id;
                $row['insert'][] = $value;
            }

            $total['total'] += $value['total_money'];
            $total['total_tax'] += ($value['total']-$value['into_money']);
            $total['subtotal'] += $value['into_money'];
            
        }

        if($data['discount_total'] > 0){
            $total['total'] = $total['total'] - $data['discount_total'];
        }

        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_orders',$total);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if(empty($row['delete'])){
            $row['delete'] = ['0'];
        }
            $row['delete'] = implode(",",$row['delete']);
            $this->db->where('id NOT IN ('.$row['delete'] .') and pur_order ='.$id);
            $this->db->delete(db_prefix().'pur_order_detail');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        
        if(count($row['insert']) != 0){
            $this->db->insert_batch(db_prefix().'pur_order_detail', $row['insert']);
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }
        if(count($row['update']) != 0){
            $this->db->update_batch(db_prefix().'pur_order_detail', $row['update'], 'id');
            if($this->db->affected_rows() > 0){
                $affectedRows++;
            }
        }

        
        if ($affectedRows > 0) {
           

            return true;
        }

        return false;
    }

    /**
     * { delete pur order }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_pur_order($id)
    {
        $affectedRows = 0;
        $this->db->where('pur_order',$id);
        $this->db->delete(db_prefix().'pur_order_detail');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_order');
        $this->db->delete(db_prefix().'files');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id)) {
            delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id);
        }

        $this->db->where('pur_order',$id);
        $this->db->delete(db_prefix().'pur_order_payment');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $this->db->where('rel_type','purchase_order');
        $this->db->where('rel_id',$id);
        $this->db->delete(db_prefix().'notes');

        $this->db->where('rel_type','purchase_order');
        $this->db->where('rel_id',$id);
        $this->db->delete(db_prefix().'reminders');

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'pur_orders');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if($affectedRows > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the pur order approved.
     *
     * @return     <array>  The pur order approved.
     */
    public function get_pur_order_approved(){
        $this->db->where('approve_status', 2);
        return $this->db->get(db_prefix().'pur_orders')->result_array();
    }

    /**
     * Adds a contract.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  ( false) or int id contract
     */
    public function add_contract($data){
        
        $data['contract_value'] = reformat_currency_pur($data['contract_value']);
        $data['add_from'] = get_staff_user_id();
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);
        $data['time_payment'] = to_sql_date($data['time_payment']);
        $data['signed_date'] = to_sql_date($data['signed_date']);
        $this->db->insert(db_prefix().'pur_contracts',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return $insert_id;
        }
        return false;
        
    }

    /**
     * { update contract }
     *
     * @param      <type>   $data   The data
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function update_contract($data,$id) {
        $data['contract_value'] = reformat_currency_pur($data['contract_value']);
        $data['add_from'] = get_staff_user_id();
        $data['start_date'] = to_sql_date($data['start_date']);
        $data['end_date'] = to_sql_date($data['end_date']);
        $data['time_payment'] = to_sql_date($data['time_payment']);
        $data['signed_date'] = to_sql_date($data['signed_date']);
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'pur_contracts',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { delete contract }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean   
     */
    public function delete_contract($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_contracts');
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Gets the html vendor.
     *
     * @param      <type>  $vendor  The vendor
     *
     * @return     string  The html vendor.
     */
    public function get_html_vendor($vendor){
        
        $vendors = $this->get_vendor($vendor);
        $html = '<table class="table border table-striped ">
                            <tbody>
                               <tr class="project-overview">';
        $html .= '<td width="20%" class="bold">'._l('company').'</td>';
        $html .= '<td>'.$vendors->company.'</td>';
        $html .= '<td width="20%" class="bold">'._l('phonenumber').'</td>';
        $html .= '<td>'.$vendors->phonenumber.'</td>';                               
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="bold">'._l('city').'</td>';
        $html .= '<td>'.$vendors->city.'</td>';
        $html .= '<td width="20%" class="bold">'._l('address').'</td>';
        $html .= '<td>'.$vendors->address.'</td>';                               
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="bold">'._l('client_vat_number').'</td>';
        $html .= '<td>'.$vendors->vat.'</td>';
        $html .= '<td width="20%" class="bold">'._l('website').'</td>';
        $html .= '<td>'.$vendors->website.'</td>';                               
        $html .= '</tr>';
        $html .= '</tbody>
                </table>';

        return $html;
    }

    /**
     * Gets the contract.
     *
     * @param      string  $id     The identifier
     *
     * @return     <row>,<array>  The contract.
     */
    public function get_contract($id = ''){
        if($id == ''){
            return  $this->db->get(db_prefix().'pur_contracts')->result_array();
        }else{
            $this->db->where('id',$id);
            return $this->db->get(db_prefix().'pur_contracts')->row();
        }
    }

    /**
     * { sign contract }
     *
     * @param      <type>   $contract  The contract
     * @param      <type>   $status    The status
     *
     * @return     boolean 
     */
    public function sign_contract($contract,$status){
        $this->db->where('id',$contract);
        $this->db->update(db_prefix().'pur_contracts',[
            'signed_status' => $status,
            'signed_date' => date('Y-m-d'),
            'signer' => get_staff_user_id(),
        ]);
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * { check approval details }
     *
     * @param      <type>          $rel_id    The relative identifier
     * @param      <type>          $rel_type  The relative type
     *
     * @return     boolean|string 
     */
    public function check_approval_details($rel_id, $rel_type){
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $approve_status = $this->db->get(db_prefix().'pur_approval_details')->result_array();
        if(count($approve_status) > 0){
            foreach ($approve_status as $value) {
                if($value['approve'] == -1){
                    return 'reject';
                }
                if($value['approve'] == 0){
                    $value['staffid'] = explode(', ',$value['staffid']);
                    return $value;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Gets the list approval details.
     *
     * @param      <type>  $rel_id    The relative identifier
     * @param      <type>  $rel_type  The relative type
     *
     * @return     <array>  The list approval details.
     */
    public function get_list_approval_details($rel_id, $rel_type){
        $this->db->select('*');
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        return $this->db->get(db_prefix().'pur_approval_details')->result_array();
    }

    /**
     * Sends a request approve.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean   
     */
    public function send_request_approve($data){
        if(!isset($data['status'])){
            $data['status'] = '';
        }
        $date_send = date('Y-m-d H:i:s');
        $data_new = $this->get_approve_setting($data['rel_type'], $data['status']);
        if(!$data_new){
            return false;
        }
        $this->delete_approval_details($data['rel_id'], $data['rel_type']);
        $list_staff = $this->staff_model->get();
        $list = [];
        $staff_addedfrom = $data['addedfrom'];
        $sender = get_staff_user_id();
        
        foreach ($data_new as $value) {
            $row = [];
            
            if($value->approver !== 'staff'){
            $value->staff_addedfrom = $staff_addedfrom;
            $value->rel_type = $data['rel_type'];
            $value->rel_id = $data['rel_id'];
            
                $approve_value = $this->get_staff_id_by_approve_value($value, $value->approver);

                if(is_numeric($approve_value)){
                    $approve_value = $this->staff_model->get($approve_value)->email;
                }else{

                    $this->db->where('rel_id', $data['rel_id']);
                    $this->db->where('rel_type', $data['rel_type']);
                    $this->db->delete('tblpur_approval_details');


                    return $value->approver;
                }
                $row['approve_value'] = $approve_value;
            
            $staffid = $this->get_staff_id_by_approve_value($value, $value->approver);
            
            if(empty($staffid)){
                $this->db->where('rel_id', $data['rel_id']);
                $this->db->where('rel_type', $data['rel_type']);
                $this->db->delete('tblpur_approval_details');


                return $value->approver;
            }

                $row['action'] = $value->action;
                $row['staffid'] = $staffid;
                $row['date_send'] = $date_send;
                $row['rel_id'] = $data['rel_id'];
                $row['rel_type'] = $data['rel_type'];
                $row['sender'] = $sender;
                $this->db->insert('tblpur_approval_details', $row);

            }else if($value->approver == 'staff'){
                $row['action'] = $value->action;
                $row['staffid'] = $value->staff;
                $row['date_send'] = $date_send;
                $row['rel_id'] = $data['rel_id'];
                $row['rel_type'] = $data['rel_type'];
                $row['sender'] = $sender;

                $this->db->insert('tblpur_approval_details', $row);
            }
        }
        return true;
    }

    /**
     * Gets the approve setting.
     *
     * @param      <type>   $type    The type
     * @param      string   $status  The status
     *
     * @return     boolean  The approve setting.
     */
    public function get_approve_setting($type, $status = ''){
        $this->db->select('*');
        $this->db->where('related', $type);
        $approval_setting = $this->db->get('tblpur_approval_setting')->row();
        if($approval_setting){
            return json_decode($approval_setting->setting);
        }else{
            return false;
        }
    }

    /**
     * { delete approval details }
     *
     * @param      <type>   $rel_id    The relative identifier
     * @param      <type>   $rel_type  The relative type
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function delete_approval_details($rel_id, $rel_type)
    {
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->delete(db_prefix().'pur_approval_details');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Gets the staff identifier by approve value.
     *
     * @param      <type>  $data           The data
     * @param      string  $approve_value  The approve value
     *
     * @return     array   The staff identifier by approve value.
     */
    public function get_staff_id_by_approve_value($data, $approve_value){
        $list_staff = $this->staff_model->get();
        $list = [];
        $staffid = [];
        
        if($approve_value == 'department_manager'){
            $staffid = $this->departments_model->get_staff_departments($data->staff_addedfrom)[0]['manager_id'];
        }elseif($approve_value == 'direct_manager'){
            $staffid = $this->staff_model->get($data->staff_addedfrom)->team_manage;
        }
        
        return $staffid;
    }

    /**
     * Gets the staff sign.
     *
     * @param      <type>  $rel_id    The relative identifier
     * @param      <type>  $rel_type  The relative type
     *
     * @return     array   The staff sign.
     */
    public function get_staff_sign($rel_id, $rel_type){
        $this->db->select('*');

        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('action', 'sign');    
        $approve_status = $this->db->get(db_prefix().'pur_approval_details')->result_array();
        if(isset($approve_status))
        {
            $array_return = [];
            foreach ($approve_status as $key => $value) {
               array_push($array_return, $value['staffid']);
            }
            return $array_return;
        }
        return [];
    }


    /**
     * Sends a mail.
     *
     * @param      <type>  $data   The data
     */
    public function send_mail($data){
        $this->load->model('emails_model');
        if(!isset($data['status'])){
            $data['status'] = '';
        }
        $get_staff_enter_charge_code = '';
        $mes = 'notify_send_request_approve_project';
        $staff_addedfrom = 0;
        $additional_data = $data['rel_type'];
        $object_type = $data['rel_type'];
        switch ($data['rel_type']) {
            case 'pur_request':
                $staff_addedfrom = $this->get_purchase_request($data['rel_id'])->requester;
                $additional_data = $this->get_purchase_request($data['rel_id'])->pur_rq_name;
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_request';
                $mes_approve = 'notify_send_approve_pur_request';
                $mes_reject = 'notify_send_rejected_pur_request';
                $link = 'purchase/view_pur_request/' . $data['rel_id'];
                break;

            case 'pur_quotation':
                $staff_addedfrom = $this->get_estimate($data['rel_id'])->addedfrom;
                $additional_data = format_pur_estimate_number($data['rel_id']);
                $list_approve_status = $this->get_list_approval_details($data['rel_id'],$data['rel_type']);
                $mes = 'notify_send_request_approve_pur_quotation';
                $mes_approve = 'notify_send_approve_pur_quotation';
                $mes_reject = 'notify_send_rejected_pur_quotation';
                $link = 'purchase/quotations/' . $data['rel_id'];
                break;    

            default:
                
                break;
        }


        $check_approve_status = $this->check_approval_details($data['rel_id'], $data['rel_type'], $data['status']);
        if(isset($check_approve_status['staffid'])){

        $mail_template = 'send-request-approve';

            if(!in_array(get_staff_user_id(),$check_approve_status['staffid'])){
                foreach ($check_approve_status['staffid'] as $value) {
                    $staff = $this->staff_model->get($value);
                    $notified = add_notification([
                    'description'     => $mes,
                    'touserid'        => $staff->staffid,
                    'link'            => $link,
                    'additional_data' => serialize([
                        $additional_data,
                    ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$staff->staffid]);
                    }
                }
            }
        }

        if(isset($data['approve'])){
            if($data['approve'] == 2){
                $mes = $mes_approve;
                $mail_template = 'send_approve';
            }else{
                $mes = $mes_reject;
                $mail_template = 'send_rejected';
            }

            
            $staff = $this->staff_model->get($staff_addedfrom);
            $notified = add_notification([
            'description'     => $mes,
            'touserid'        => $staff->staffid,
            'link'            => $link,
            'additional_data' => serialize([
                $additional_data,
            ]),
            ]);
            if ($notified) {
                pusher_trigger_notification([$staff->staffid]);
            }

            foreach($list_approve_status as $key => $value){
            $value['staffid'] = explode(', ',$value['staffid']);
                if($value['approve'] == 1 && !in_array(get_staff_user_id(),$value['staffid'])){
                    foreach ($value['staffid'] as $staffid) {
                      
                    $staff = $this->staff_model->get($staffid);
                    $notified = add_notification([
                    'description'     => $mes,
                    'touserid'        => $staff->staffid,
                    'link'            => $link,
                    'additional_data' => serialize([
                        $additional_data,
                    ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$staff->staffid]);
                    }
        
                    }
                }
            }
        }
    }

    /**
     * { update approve request }
     *
     * @param      <type>   $rel_id    The relative identifier
     * @param      <type>   $rel_type  The relative type
     * @param      <type>   $status    The status
     *
     * @return     boolean
     */
    public function update_approve_request($rel_id , $rel_type, $status){ 
        $data_update = [];
        
        switch ($rel_type) {
            case 'pur_request':
                $data_update['status'] = $status;
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_request', $data_update);
                return true;
                break;
            case 'pur_quotation':
                $data_update['status'] = $status;
                $this->db->where('id', $rel_id);
                $this->db->update(db_prefix().'pur_estimates', $data_update);
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * { update approval details }
     *
     * @param      <int>   $id     The identifier
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_approval_details($id, $data){
        $data['date'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'pur_approval_details', $data);
        if($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { pur request pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return      ( pdf )
     */
    public function pur_request_pdf($pur_request)
    {
        return app_pdf('pur_request', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_request_pdf'), $pur_request);
    }

    /**
     * Gets the pur request pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The pur request pdf html.
     */
    public function get_pur_request_pdf_html($pur_request_id){
        $this->load->model('departments_model');

        $pur_request = $this->get_purchase_request($pur_request_id);
        $pur_request_detail = $this->get_pur_request_detail($pur_request_id);
        $company_name = get_option('invoice_company_name'); 
        $dpm_name = $this->departments_model->get($pur_request->department)->name;
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_request->request_date));
        $month = date('m',strtotime($pur_request->request_date));
        $year = date('Y',strtotime($pur_request->request_date));
        $list_approve_status = $this->get_list_approval_details($pur_request_id,'pur_request');

    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('company_name').': '. $company_name.'</td>
            <td class="width_20"></td>
            
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
            <td></td>
            
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('purchase_request')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('requester').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_request->requester).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('department').':</h4></td>
            <td>'. $dpm_name.'</td>
          </tr>
          
        </tbody>
      </table>
      <br><br>
      ';

      $html .=  '<table class="table table-hover table-bordered border_table">
        <tbody>
          <tr class="border_tr">
            <td class="border_td">'._l('items').'</td>
            <td class="border_td">'._l('unit').'</td>
            <td class="border_td">'._l('unit_price').'</td>
            <td class="border_td">'._l('quantity').'</td>
            <td class="border_td">'._l('into_money').'</td>
            <td class="border_td">'._l('inventory_quantity').'</td>
          </tr>';
      foreach($pur_request_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr class="border_tr">
            <td class="border_td_left">'.$items->commodity_code.' - '.$items->description.'</td>
            <td class="border_td_left">'.$units->unit_name.'</td>
            <td class="border_td_right">'.app_format_money($row['unit_price'],'').'</td>
            <td class="border_td_right">'.$row['quantity'].'</td>
            <td class="border_td_right">'.app_format_money($row['into_money'],'').'</td>
            <td class="border_td_right">'.$row['inventory_quantity'].'</td>
          </tr>';
      }  
      $html .=  '</tbody>
      </table>';

      $html .= '<br>
      <br>
      <br>
      <br>
      <table class="table">
        <tbody>
          <tr>';
     if(count($list_approve_status) > 0){
      
        foreach ($list_approve_status as $value) {
     $html .= '<td class="td_appr">';
        if($value['action'] == 'sign'){
            $html .= '<h3>'.mb_strtoupper(get_staff_full_name($value['staffid'])).'</h3>';
            if($value['approve'] == 2){ 
                $html .= '<img src="'.site_url('modules/purchase/uploads/pur_request/signature/'.$pur_request->id.'/signature_'.$value['id'].'.png').'" class="img_style">';
            }
                
        }else{ 
        $html .= '<h3>'.mb_strtoupper(get_staff_full_name($value['staffid'])).'</h3>';
              if($value['approve'] == 2){ 
        $html .= '<img src="'.site_url('modules/purchase/uploads/approval/approved.png').'" class="img_style">';
             }elseif($value['approve'] == 3){
        $html .= '<img src="'.site_url('modules/purchase/uploads/approval/rejected.png').'" class="img_style">';
             }
              
                }
       $html .= '</td>';
        }
       
    
    
     } 
            $html .= '<td class="td_ali_font"><h3>'.mb_strtoupper('Requestor').'</h3></td>
            <td class="td_ali_font"><h3>'.mb_strtoupper('Treasurer').'</h3></td></tr>
        </tbody>
      </table>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * { request quotation pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return      ( pdf )
     */
    public function request_quotation_pdf($pur_request)
    {
        return app_pdf('pur_request', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Request_quotation_pdf'), $pur_request);
    }

    /**
     * Gets the request quotation pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The request quotation pdf html.
     */
    public function get_request_quotation_pdf_html($pur_request_id){
        $this->load->model('departments_model');

        $pur_request = $this->get_purchase_request($pur_request_id);
        $pur_request_detail = $this->get_pur_request_detail($pur_request_id);
        $company_name = get_option('invoice_company_name'); 
        $dpm_name = $this->departments_model->get($pur_request->department)->name;
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_request->request_date));
        $month = date('m',strtotime($pur_request->request_date));
        $year = date('Y',strtotime($pur_request->request_date));
        $list_approve_status = $this->get_list_approval_details($pur_request_id,'pur_request');

    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('company_name').': '. $company_name.'</td>
            <td class="width_20"></td>
            
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
            <td></td>
            
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('request_quotation')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('requester').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_request->requester).'</td>
          </tr>
          <tr>
            <td class="font_500"><h4>'. _l('department').':</h4></td>
            <td>'. $dpm_name.'</td>
          </tr>
          
        </tbody>
      </table>
      <br><br>
      ';

      $html .=  '<table class="table table-hover table-bordered border_table">
        <tbody>
          <tr class="border_tr">
            <td class="border_td">'._l('items').'</td>
            <td class="border_td">'._l('unit').'</td>
            <td class="border_td">'._l('unit_price').'</td>
            <td class="border_td">'._l('quantity').'</td>
            <td class="border_td">'._l('into_money').'</td>
            
          </tr>';
      foreach($pur_request_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr class="border_tr">
            <td class="border_td_left">'.$items->commodity_code.' - '.$items->description.'</td>
            <td class="border_td_left">'.$units->unit_name.'</td>
            <td class="border_td_right">'.app_format_money($row['unit_price'],'').'</td>
            <td class="border_td_right">'.$row['quantity'].'</td>
            <td class="border_td_right">'.app_format_money($row['into_money'],'').'</td>
            
          </tr>';
      }  
      $html .=  '</tbody>
      </table>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * Sends a request quotation.
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean
     */
    public function send_request_quotation($data){
        $staff_id = get_staff_user_id();

        $inbox = array();

        $inbox['to'] = implode(',',$data['email']);
        $inbox['sender_name'] = get_staff_full_name($staff_id);
        $inbox['subject'] = _strip_tags($data['subject']);
        $inbox['body'] = _strip_tags($data['content']);        
        $inbox['body'] = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        $inbox['from_email'] = get_staff_email_by_id_pur($staff_id);
        
        if(strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0){

            $ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');    
            $ci->email->clear(true);
            $ci->email->from($inbox['from_email'], $inbox['sender_name']);
            $ci->email->to($inbox['to']);
            
            $ci->email->subject($inbox['subject']);
            $ci->email->message($inbox['body']);
            
            $attachment_url = site_url(PURCHASE_PATH.'request_quotation/'.$data['pur_request_id'].'/'.$_FILES['attachment']['name']);
            $ci->email->attach($attachment_url);

            $ci->email->send(true);
        }
        
        return true;
    }

    /**
     * { update purchase setting }
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_purchase_setting($data)
    {

            $val = $data['input_name_status'] == 'true' ? 1 : 0;
            $this->db->where('option_name',$data['input_name']);
            $this->db->update(db_prefix() . 'purchase_option', [
                    'option_val' => $val,
                ]);
            if ($this->db->affected_rows() > 0) {
                return true;
            }else{
                return false;
            }
    }


    /**
     * { update purchase setting }
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean 
     */
    public function update_po_number_setting($data)
    {
        $this->db->where('option_name','pur_order_prefix');
        $this->db->update(db_prefix() . 'purchase_option', [
                'option_val' => $data['pur_order_prefix'],
            ]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Gets the purchase order attachments.
     *
     * @param      <type>  $id     The purchase order
     *
     * @return     <type>  The purchase order attachments.
     */
    public function get_purchase_order_attachments($id){
   
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_order');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the file.
     *
     * @param      <type>   $id      The file id
     * @param      boolean  $rel_id  The relative identifier
     *
     * @return     boolean  The file.
     */
    public function get_file($id, $rel_id = false)
    {
        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix().'files')->row();

        if ($file && $rel_id) {
            if ($file->rel_id != $rel_id) {
                return false;
            }
        }
        return $file;
    }

    /**
     * Gets the part attachments.
     *
     * @param      <type>  $surope  The surope
     * @param      string  $id      The identifier
     *
     * @return     <type>  The part attachments.
     */
    public function get_purorder_attachments($surope, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_order');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete purorder attachment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean 
     */
    public function delete_purorder_attachment($id)
    {
        $attachment = $this->get_purorder_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the payment purchase order.
     *
     * @param      <type>  $id     The purcahse order id
     *
     * @return     <type>  The payment purchase order.
     */
    public function get_payment_purchase_order($id){
        $this->db->where('pur_order',$id);
        return $this->db->get(db_prefix().'pur_order_payment')->result_array();
    }

    /**
     * Adds a payment.
     *
     * @param      <type>   $data       The data
     * @param      <type>   $pur_order  The pur order id
     *
     * @return     boolean  ( return id payment after insert )
     */
    public function add_payment($data, $pur_order){
        $data['date'] = to_sql_date($data['date']);
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data['amount'] = str_replace(',', '', $data['amount']);
        $data['pur_order'] = $pur_order;

        $this->db->insert(db_prefix().'pur_order_payment',$data);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return $insert_id;
        }
        return false;
    }

    /**
     * { delete payment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  ( delete payment )
     */
    public function delete_payment($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_order_payment');
        if ($this->db->affected_rows() > 0) {
                return true;
        }
        return false;
    }

    /**
     * { purorder pdf }
     *
     * @param      <type>  $pur_request  The pur request
     *
     * @return     <type>  ( purorder pdf )
     */
    public function purorder_pdf($pur_order)
    {
        return app_pdf('pur_order', module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_order_pdf'), $pur_order);
    }


    /**
     * Gets the pur request pdf html.
     *
     * @param      <type>  $pur_request_id  The pur request identifier
     *
     * @return     string  The pur request pdf html.
     */
    public function get_purorder_pdf_html($pur_order_id){
        

        $pur_order = $this->get_pur_order($pur_order_id);
        $pur_order_detail = $this->get_pur_order_detail($pur_order_id);
        $company_name = get_option('invoice_company_name'); 
        
        $address = get_option('invoice_company_address'); 
        $day = date('d',strtotime($pur_order->order_date));
        $month = date('m',strtotime($pur_order->order_date));
        $year = date('Y',strtotime($pur_order->order_date));
        
    $html = '<table class="table">
        <tbody>
          <tr>
            <td class="font_td_cpn">'. _l('company_name').': '. $company_name.'</td>
            <td class="width_20"></td>
            
          </tr>
          <tr>
            <td class="font_500">'. _l('address').': '. $address.'</td>
            <td></td>
            
          </tr>
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            
            <td class="td_ali_font"><h2 class="h2_style">'.mb_strtoupper(_l('purchase_order')).'</h2></td>
           
          </tr>
          <tr>
            
            <td class="align_cen">'. _l('days').' '.$day.' '._l('month').' '.$month.' '._l('year') .' '.$year.'</td>
            
          </tr>
          
        </tbody>
      </table>
      <table class="table">
        <tbody>
          <tr>
            <td class="td_width_25"><h4>'. _l('add_from').':</h4></td>
            <td class="td_width_75">'. get_staff_full_name($pur_order->addedfrom).'</td>
          </tr>
          <tr>
            <td class="td_width_25"><h4>'. _l('vendor').':</h4></td>
            <td class="td_width_75">'. get_vendor_company_name($pur_order->vendor).'</td>
          </tr>
          
        </tbody>
      </table>

      <h3>
       '. html_entity_decode($pur_order->pur_order_number.' - '.$pur_order->pur_order_name).'
       </h3>
      <br><br>
      ';

      $html .=  '<table class="table purorder-item">
        <thead>
          <tr>
            <th class="thead-dark">'._l('items').'</th>
            <th class="thead-dark" align="right">'._l('unit_price').'</th>
            <th class="thead-dark" align="right">'._l('quantity').'</th>
         
            <th class="thead-dark" align="right">'._l('tax').'</th>
 
            <th class="thead-dark" align="right">'._l('discount').'</th>
            <th class="thead-dark" align="right">'._l('total').'</th>
          </tr>
          </thead>
          <tbody>';
        $t_mn = 0;
      foreach($pur_order_detail as $row){
        $items = $this->get_items_by_id($row['item_code']);
        $units = $this->get_units_by_id($row['unit_id']);
        $html .= '<tr nobr="true" class="sortable">
            <td >'.$items->commodity_code.' - '.$items->description.'</td>
            <td align="right">'.app_format_money($row['unit_price'],'').'</td>
            <td align="right">'.$row['quantity'].'</td>
         
            <td align="right">'.app_format_money($row['total'] - $row['into_money'],'').'</td>
       
            <td align="right">'.app_format_money($row['discount_money'],'').'</td>
            <td align="right">'.app_format_money($row['total_money'],'').'</td>
          </tr>';

        $t_mn += $row['total_money'];
      }  
      $html .=  '</tbody>
      </table><br><br>';

      $html .= '<table class="table text-right"><tbody>';
      if($pur_order->discount_total > 0){
        $html .= '<tr id="subtotal">
                    <td width="33%"></td>
                     <td>'._l('subtotal').' </td>
                     <td class="subtotal">
                        '.app_format_money($t_mn,'').'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(%)').'(%)'.'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_order->discount_percent,'').' %'.'
                     </td>
                  </tr>
                  <tr id="subtotal">
                  <td width="33%"></td>
                     <td>'._l('discount(money)').'</td>
                     <td class="subtotal">
                        '.app_format_money($pur_order->discount_total, '').'
                     </td>
                  </tr>';
      }
      $html .= '<tr id="subtotal">
                 <td width="33%"></td>
                 <td>'. _l('total').'</td>
                 <td class="subtotal">
                    '. app_format_money($pur_order->total, '').'
                 </td>
              </tr>';

      $html .= ' </tbody></table>';

      $html .= '<div class="col-md-12 mtop15">
                        <p class="bold">'. _l('terms_and_conditions').': '. html_entity_decode($pur_order->terms).'</p>
                       
                     </div>';
      $html .= '<br>
      <br>
      <br>
      <br>
      <table class="table">
        <tbody>
          <tr>';
     
            $html .= '<td class="td_width_55"></td><td class="td_ali_font"><h3>'.mb_strtoupper(_l('orderer')).'</h3></td>
            </tr>
        </tbody>
      </table>';
      $html .= '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_pdf.css') . '"  rel="stylesheet" type="text/css" />';
      return $html;
    }

    /**
     * clear signature
     *
     * @param      string   $id     The identifier
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function clear_signature($id)
    {
        $this->db->select('signature');
        $this->db->where('id', $id);
        $contract = $this->db->get(db_prefix() . 'pur_contracts')->row();

        if ($contract) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'pur_contracts', ['signed_status' => 'not_signed']);

            if (!empty($contract->signature)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER.'/contract_sign/' . $id . '/' . $contract->signature);
            }

            return true;
        }


        return false;
    }

    /**
     * get data Purchase statistics by cost
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function cost_of_purchase_orders_analysis($year = ''){
        if($year == ''){
            $year = date('Y');
        }
        $query = $this->db->query('SELECT DATE_FORMAT(order_date, "%m") AS month, Sum((SELECT SUM(total_money) as total FROM '.db_prefix().'pur_order_detail where pur_order = '.db_prefix().'pur_orders.id)) as total 
            FROM '.db_prefix().'pur_orders where DATE_FORMAT(order_date, "%Y") = '.$year.'
            group by month')->result_array();
        $result = [];
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $cost = [];
        $rs = 0;
        foreach ($query as $value) {
            if($value['total'] > 0){
                $result[$value['month'] - 1] =  (double)$value['total'];
            }
        }
        return $result;
    }

    /**
     * get data Purchase statistics by number of purchase orders
     *
     * @param      string  $year   The year
     *
     * @return     array
     */
    public function number_of_purchase_orders_analysis($year = ''){
        if($year == ''){
            $year = date('Y');
        }
        $query = $this->db->query('SELECT DATE_FORMAT(order_date, "%m") AS month, Count(*) as count 
            FROM '.db_prefix().'pur_orders where DATE_FORMAT(order_date, "%Y") = '.$year.'
            group by month')->result_array();
        $result = [];
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $result[] = 0;
        $cost = [];
        $rs = 0;
        foreach ($query as $value) {
            if($value['count'] > 0){
                $result[$value['month'] - 1] =  (int)$value['count'];
            }
        }
        return $result;
    }

    /**
     * Gets the payment by vendor.
     *
     * @param      <type>  $vendor  The vendor
     */
    public function get_payment_by_vendor($vendor){
        return  $this->db->query('select pop.pur_order, pop.id as pop_id, pop.amount, pop.date, pop.paymentmode, pop.transactionid, po.pur_order_name from '.db_prefix().'pur_order_payment pop left join '.db_prefix().'pur_orders po on po.id = pop.pur_order where po.vendor = '.$vendor)->result_array();
    }

/**
     * get unit add item 
     * @return array
     */
    public function get_unit_add_item()
    {
        return $this->db->query('select * from tblware_unit_type where display = 1 order by tblware_unit_type.order asc ')->result_array();
    }

    /**
     * get commodity
     * @param  boolean $id
     * @return array or object
     */
    public function get_item($id = false)
    {

        if (is_numeric($id)) {
        $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'items')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblitems')->result_array();
        }

    }

    /**
     * get inventory commodity
     * @param  integer $commodity_id 
     * @return array            
     */
    public function get_inventory_item($commodity_id){
        $sql ='SELECT '.db_prefix().'warehouse.warehouse_code, sum(inventory_number) as inventory_number, unit_name FROM '.db_prefix().'inventory_manage 
            LEFT JOIN '.db_prefix().'items on '.db_prefix().'inventory_manage.commodity_id = '.db_prefix().'items.id 
            LEFT JOIN '.db_prefix().'ware_unit_type on '.db_prefix().'items.unit_id = '.db_prefix().'ware_unit_type.unit_type_id
            LEFT JOIN '.db_prefix().'warehouse on '.db_prefix().'inventory_manage.warehouse_id = '.db_prefix().'warehouse.warehouse_id
             where commodity_id = '.$commodity_id. ' group by '.db_prefix().'inventory_manage.warehouse_id';
        return  $this->db->query($sql)->result_array();


    }

    /**
     * get warehourse attachments
     * @param  integer $commodity_id 
     * @return array               
     */
    public function get_item_attachments($commodity_id){

        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $commodity_id);
        $this->db->where('rel_type', 'commodity_item_file');

        return $this->db->get(db_prefix() . 'files')->result_array();

    }

    /**
     * generate commodity barcode
     *
     * @return     string 
     */
    public function generate_commodity_barcode(){
        $item = false;
        do{
            $length = 11;
            $chars = '0123456789';
            $count = mb_strlen($chars);
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $password .= mb_substr($chars, $index, 1);
            }
            $this->db->where('commodity_barcode',$password);
            $item = $this->db->get(db_prefix().'items')->row();
        }while ($item);

        return $password;
    }

    /**
     * add commodity one item
     * @param array $data
     * @return integer 
     */
    public function add_commodity_one_item_prev($data){
        /*add data tblitem*/
        $data['rate'] = reformat_currency_pur($data['rate']);
        $data['purchase_price'] = reformat_currency_pur($data['purchase_price']);

        /*create sku code*/
        if($data['sku_code'] != ''){
            $data['sku_code'] = $data['sku_code'];
        }else{
            $data['sku_code'] = $this->create_sku_code('', '');
        }
        
        /*create sku code*/

        $this->db->insert(db_prefix().'items', $data);
        $insert_id = $this->db->insert_id();

        /*add data tblinventory*/
        return $insert_id;

    }

    /**
     * add commodity one item
     * @param array $data
     * @return integer 
     */
    public function add_commodity_one_item($data){
        $this->db->insert(db_prefix().'items', $data);
        $insert_id = $this->db->insert_id();

        /*add data tblinventory*/
        return $insert_id;

    }

     /**
     * update commodity one item
     * @param  array $data 
     * @param  integer $id   
     * @return boolean        
     */
    public function update_commodity_one_item_prev($data,$id){
        /*add data tblitem*/
        $data['rate'] = reformat_currency_pur($data['rate']);
        $data['purchase_price'] = reformat_currency_pur($data['purchase_price']);

        
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'items',$data);
        

        return true;
    }

    /**
     * update commodity one item
     * @param  array $data 
     * @param  integer $id   
     * @return boolean        
     */
    public function update_commodity_one_item($data,$id){
        
        $this->db->where('id',$id);
        $this->db->update(db_prefix().'items',$data);
        

        return true;
    }

    /**
     * create sku code 
     * @param  int commodity_group 
     * @param  int sub_group 
     * @return string
     */
    public function  create_sku_code($commodity_group, $sub_group)
    {
        // input  commodity group, sub group
        //get commodity group from id
        $group_character = '';
        if(isset($commodity_group)){

            $sql_group_where = 'SELECT * FROM '.db_prefix().'items_groups where id = "'.$commodity_group.'"';
            $group_value = $this->db->query($sql_group_where)->row();
            if($group_value){

                if($group_value->commodity_group_code != ''){
                    $group_character = mb_substr($group_value->commodity_group_code, 0, 1, "UTF-8").'-';

                }
            }

        }

        //get sku code from sku id
        $sub_code = '';
        



        $sql_where = 'SELECT * FROM '.db_prefix().'items order by id desc limit 1';
        $last_commodity_id = $this->db->query($sql_where)->row();
        if($last_commodity_id){
            $next_commodity_id = (int)$last_commodity_id->id + 1;
        }else{
            $next_commodity_id = 1;
        }
        $commodity_id_length = strlen((string)$next_commodity_id);

        $commodity_str_betwen ='';

        $create_candidate_code='';

        switch ($commodity_id_length) {
            case 1:
                $commodity_str_betwen = '000';
                break;
            case 2:
                $commodity_str_betwen = '00';
                break;
            case 3:
                $commodity_str_betwen = '0';
                break;

            default:
                $commodity_str_betwen = '0';
                break;
        }

 
        return  $group_character.$sub_code.$commodity_str_betwen.$next_commodity_id; // X_X_000.id auto increment

        
    }


    /**
     * get commodity group add commodity
     * @return array
     */
    public function get_commodity_group_add_commodity()
    {

        return $this->db->query('select * from tblitems_groups where display = 1 order by tblitems_groups.order asc ')->result_array();
    }


    //delete _commodity_file file for any 
    /**
     * delete commodity file
     * @param  integer $attachment_id 
     * @return boolean                
     */
    public function delete_commodity_file($attachment_id)
    {
        $deleted    = false;
        $attachment = $this->get_commodity_attachments_delete($attachment_id);

        if ($attachment) {
            if (empty($attachment->external)) {
                if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name)){
                    unlink(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name);
                }else{
                    unlink('modules/warehouse/uploads/item_img/' .$attachment->rel_id.'/'.$attachment->file_name);
                }
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('commodity Attachment Deleted [commodityID: ' . $attachment->rel_id . ']');
            }
            if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id.'/'.$attachment->file_name)){
                if (is_dir(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id)) {
                    // Check if no attachments left, so we can delete the folder also
                    $other_attachments = list_files(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id);
                    if (count($other_attachments) == 0) {
                        // okey only index.html so we can delete the folder also
                        delete_dir(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$attachment->rel_id);
                    }
                }
            }else{
                if (is_dir(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id)) {
                    // Check if no attachments left, so we can delete the folder also
                    $other_attachments = list_files(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id);
                    if (count($other_attachments) == 0) {
                        // okey only index.html so we can delete the folder also
                        delete_dir(site_url('modules/warehouse/uploads/item_img/') .$attachment->rel_id);
                    }
                }
            }
        }

        return $deleted;
    }

    /**
     * get commodity attachments delete
     * @param  integer $id 
     * @return object     
     */
    public function get_commodity_attachments_delete($id){

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
    }

    /**
     * get unit type
     * @param  boolean $id
     * @return array or object
     */
    public function get_unit_type($id = false)
    {

        if (is_numeric($id)) {
        $this->db->where('unit_type_id', $id);

            return $this->db->get(db_prefix() . 'ware_unit_type')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblware_unit_type')->result_array();
        }

    }

    /**
     * add unit type 
     * @param array  $data
     * @param boolean $id
     * return boolean
     */
    public function add_unit_type($data, $id = false){
        
        $unit_type = str_replace(', ','|/\|',$data['hot_unit_type']);
        $data_unit_type = explode( ',', $unit_type);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        
        foreach ($data_unit_type as  $unit_type_key => $unit_type_value) {
            if($unit_type_value == ''){
                    $unit_type_value = 0;
                }
            if(($unit_type_key+1)%6 == 0){
                $arr_temp['note'] = str_replace('|/\|',', ',$unit_type_value);
                
                if($id == false && $flag_empty == 1){
                    $this->db->insert(db_prefix().'ware_unit_type', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if($insert_id){
                        $results++;
                    }
                }
                if(is_numeric($id) && $flag_empty == 1){
                    $this->db->where('unit_type_id', $id);
                    $this->db->update(db_prefix() . 'ware_unit_type', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    }else{
                        $results_update = false;
                    }
                }
                $flag_empty =0;
                $arr_temp = [];
            }else{

                switch (($unit_type_key+1)%6) {
                    case 1:
                     $arr_temp['unit_code'] = str_replace('|/\|',', ',$unit_type_value);

                        if($unit_type_value != '0'){
                            $flag_empty = 1;
                        }
                        break;
                    case 2:
                    $arr_temp['unit_name'] = str_replace('|/\|',', ',$unit_type_value);
                        break;
                    case 3:
                    $arr_temp['unit_symbol'] = $unit_type_value;
                        break;
                    case 4:
                    $arr_temp['order'] = $unit_type_value;
                        break;
                     case 5:
                     if($unit_type_value == 'yes'){
                        $display_value = 1;
                     }else{
                        $display_value = 0;
                     }
                    $arr_temp['display'] = $display_value;
                        break;
                }
            }

        }

        if($id == false){
            return $results > 0 ? true : false;
        }else{
            return $results_update ;
        }

    }

    /**
     * delete unit type
     * @param  integer $id
     * @return boolean
     */
    public function delete_unit_type($id){
        $this->db->where('unit_type_id', $id);
        $this->db->delete(db_prefix() . 'ware_unit_type');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * delete commodity
     * @param  integer $id
     * @return boolean
     */
        public function delete_commodity($id){
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { mark converted pur order }
     *
     * @param      <int>  $pur_order  The pur order
     * @param      <int>  $expense    The expense
     */
    public function mark_converted_pur_order($pur_order, $expense){
        $this->db->where('id',$pur_order);
        $this->db->update(db_prefix().'pur_orders',['expense_convert' => $expense]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * { delete purchase vendor attachment }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_ic_attachment($id)
    {
        $attachment = $this->get_ic_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_vendor/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Gets the ic attachments.
     *
     * @param      <type>  $assets  The assets
     * @param      string  $id      The identifier
     *
     * @return     <type>  The ic attachments.
     */
    public function get_ic_attachments($assets, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_vendor');
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * Change contact password, used from client area
     * @param  mixed $id          contact id to change password
     * @param  string $oldPassword old password to verify
     * @param  string $newPassword new password
     * @return boolean
     */
    public function change_contact_password($id, $oldPassword, $newPassword)
    {
        // Get current password
        $this->db->where('id', $id);
        $client = $this->db->get(db_prefix() . 'pur_contacts')->row();

        if (!app_hasher()->CheckPassword($oldPassword, $client->password)) {
            return [
                'old_password_not_match' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', [
            'last_password_change' => date('Y-m-d H:i:s'),
            'password'             => app_hash_password($newPassword),
        ]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Contact Password Changed [ContactID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Gets the pur order by vendor.
     *
     * @param      <type>  $vendor  The vendor
     */
    public function get_pur_order_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_orders')->result_array();
    }

    public function get_contracts_by_vendor($vendor){
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_contracts')->result_array();
    }

    /**
     * @param  integer ID
     * @param  integer Status ID
     * @return boolean
     * Update contact status Active/Inactive
     */
    public function change_contact_status($id, $status)
    {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', [
            'active' => $status,
        ]);
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }

        return false;
    }

    /**
     * Gets the item by group.
     *
     * @param        $group  The group
     *
     * @return      The item by group.
     */
    public function get_item_by_group($group){
        $this->db->where('group_id',$group);
        return $this->db->get(db_prefix().'items')->result_array();
    }  

    /**
     * Adds vendor items.
     *
     * @param      $data   The data
     *
     * @return     boolean 
     */
    public function add_vendor_items($data){
        $rs = 0;
        $data['add_from'] = get_staff_user_id();
        $data['datecreate'] = date('Y-m-d');
        foreach($data['items'] as $val){
            $this->db->insert(db_prefix().'pur_vendor_items',[
                'vendor' => $data['vendor'],
                'group_items' => $data['group_item'],
                'items' => $val,
                'add_from' => $data['add_from'],
                'datecreate' => $data['datecreate'],
            ]);
            $insert_id = $this->db->insert_id();

            if($insert_id){
                $rs++;
            }
        }

        if($rs > 0){
            return true;
        }
        return false;
    } 

    /**
     * { delete vendor items }
     *
     * @param      <type>   $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_vendor_items($id){
        $this->db->where('id',$id);
        $this->db->delete(db_prefix().'pur_vendor_items');
        if ($this->db->affected_rows() > 0) {
            
            return true;
        }
        return false;
    }

    /**
     * Gets the item by vendor.
     *
     * @param      $vendor  The vendor
     */
    public function get_item_by_vendor($vendor){
        
        acer_log('get_item_by_vendor'. $vendor);
        $this->db->where('vendor',$vendor);
        return $this->db->get(db_prefix().'pur_vendor_items')->result_array();  
    }

    /**
     * Gets the items.
     *
     * @return     <array>  The items.
     */
    public function get_items_hs_vendor($vendor){
       return $this->db->query('select items as id, CONCAT(it.vehicle_make," - " ,it.vehicle_model) as label from '.db_prefix().'pur_vendor_items pit LEFT JOIN '.db_prefix().'items it ON it.id = pit.items where pit.vendor = '.$vendor)->result_array();
    }

    /**
     * get commodity group type
     * @param  boolean $id
     * @return array or object
     */
    public function get_commodity_group_type($id = false) {

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'items_groups')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblitems_groups')->result_array();
        }

    }

    /**
     * add commodity group type
     * @param array  $data
     * @param boolean $id
     * return boolean
     */
    public function add_commodity_group_type($data, $id = false) {
        $data['commodity_group'] = str_replace(', ', '|/\|', $data['hot_commodity_group_type']);

        $data_commodity_group_type = explode(',', $data['commodity_group']);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_group_type as $commodity_group_type_key => $commodity_group_type_value) {
            if ($commodity_group_type_value == '') {
                $commodity_group_type_value = 0;
            }
            if (($commodity_group_type_key + 1) % 5 == 0) {

                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'items_groups', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'items_groups', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }

                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_group_type_key + 1) % 5) {
                case 1:
                    if(is_numeric($id)){
                        //update
                        $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                            $flag_empty = 1;

                    }else{
                        //add
                        $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                        if ($commodity_group_type_value != '0') {
                            $flag_empty = 1;
                        }
                        
                    }
                    break;
                case 2:
                    $arr_temp['name'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                    break;
                case 3:
                    $arr_temp['order'] = $commodity_group_type_value;
                    break;
                case 4:
                    //display 1: display (yes) , 0: not displayed (no)
                    if ($commodity_group_type_value == 'yes') {
                        $display_value = 1;
                    } else {
                        $display_value = 0;
                    }
                    $arr_temp['display'] = $display_value;
                    break;
                }
            }

        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }

    }

    /**
     * delete commodity group type
     * @param  integer $id
     * @return boolean
     */
    public function delete_commodity_group_type($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items_groups');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * get sub group
     * @param  boolean $id
     * @return array  or object
     */
    public function get_sub_group($id = false) {

        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'wh_sub_group')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblwh_sub_group')->result_array();
        }

    }

    /**
     * get item group
     * @return array 
     */
    public function get_item_group() {
        return $this->db->query('select id as id, CONCAT(name,"_",commodity_group_code) as label from ' . db_prefix() . 'items_groups')->result_array();
    }

    /**
     * add sub group
     * @param array  $data
     * @param boolean $id
     * @return boolean
     */
    public function add_sub_group($data, $id = false) {
        $commodity_type = str_replace(', ', '|/\|', $data['hot_sub_group']);

        $data_commodity_type = explode(',', $commodity_type);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_type as $commodity_type_key => $commodity_type_value) {
            if ($commodity_type_value == '') {
                $commodity_type_value = 0;
            }
            if (($commodity_type_key + 1) % 6 == 0) {
                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'wh_sub_group', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'wh_sub_group', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }
                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_type_key + 1) % 6) {
                case 1:
                    $arr_temp['sub_group_code'] = str_replace('|/\|', ', ', $commodity_type_value);
                    if ($commodity_type_value != '0') {
                        $flag_empty = 1;
                    }
                    break;
                case 2:
                    $arr_temp['sub_group_name'] = str_replace('|/\|', ', ', $commodity_type_value);
                    break;
                case 3:
                    $arr_temp['group_id'] = $commodity_type_value;
                    break;
                case 4:
                    $arr_temp['order'] = $commodity_type_value;
                    break;
                case 5:
                    //display 1: display (yes) , 0: not displayed (no)
                    if ($commodity_type_value == 'yes') {
                        $display_value = 1;
                    } else {
                        $display_value = 0;
                    }
                    $arr_temp['display'] = $display_value;
                    break;
                }
            }

        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }

    }

    /**
     * delete_sub_group
     * @param  integer $id
     * @return boolean
     */
    public function delete_sub_group($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'wh_sub_group');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * list subgroup by group
     * @param  integer $group 
     * @return string        
     */
    public function list_subgroup_by_group($group)
    {
        $this->db->where('group_id', $group);
        $arr_subgroup = $this->db->get(db_prefix().'wh_sub_group')->result_array();

        $options = '';
        if(count($arr_subgroup) > 0){
            foreach ($arr_subgroup as $value) {

              $options .= '<option value="' . $value['id'] . '">' . $value['sub_group_name'] . '</option>';
            }

        }
        return $options;

    }

    /**
     * get item tag filter
     * @return array 
     */
    public function get_item_tag_filter()
    {
        return $this->db->query('select * FROM '.db_prefix().'taggables left join '.db_prefix().'tags on '.db_prefix().'taggables.tag_id =' .db_prefix().'tags.id where '.db_prefix().'taggables.rel_type = "pur_order"')->result_array();
    }

    /**
     * Gets the pur contract attachment.
     *
     * @param        $id     The identifier
     */
    public function get_pur_contract_attachment($id){
        $this->db->where('rel_id',$id);
        $this->db->where('rel_type','pur_contract');
        return $this->db->get(db_prefix().'files')->result_array();
    }

    /**
     * Gets the pur contract attachments.
     *
     * @param        $assets  The assets
     * @param      string  $id      The identifier
     *
     * @return       The pur contract attachments.
     */
    public function get_pur_contract_attachments($assets, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $assets);
        }
        $this->db->where('rel_type', 'pur_contract');
        $result = $this->db->get(db_prefix().'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     * { delete purchase contract attachment }
     *
     * @param         $id     The identifier
     *
     * @return     boolean  
     */
    public function delete_pur_contract_attachment($id)
    {
        $attachment = $this->get_pur_contract_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix().'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
            }

            if (is_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_contract/'. $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

}