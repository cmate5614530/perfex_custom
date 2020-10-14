<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\ValidatesContact;

class Vendors_portal extends App_Controller
{
    /**
     * @since  2.3.3
     */
    

    public $template = [];

    public $data = [];

    public $use_footer = true;

    public $use_submenu = true;

    public $use_navigation = true;

    public function __construct()
    {
        parent::__construct();

        hooks()->do_action('after_clients_area_init', $this);

        if (is_staff_logged_in()
            && $this->app->is_db_upgrade_required($this->current_db_version)) {
            redirect(admin_url());
        }

        $this->load->library('app_vendor_area_constructor');

        if (method_exists($this, 'validateContact')) {
            $this->validateContact();
        }

        $this->load->model('purchase_model');
        $this->load->model('requests_model');
    }

    public function layout($notInThemeViewFiles = false)
    {
        /**
         * Navigation and submenu
         * @deprecated 2.3.2
         * @var boolean
         */

        $this->data['use_navigation'] = $this->use_navigation == true;
        $this->data['use_submenu']    = $this->use_submenu == true;

        /**
         * @since  2.3.2 new variables
         * @var array
         */
        $this->data['navigationEnabled'] = $this->use_navigation == true;
        $this->data['subMenuEnabled']    = $this->use_submenu == true;

        /**
         * Theme head file
         * @var string
         */
        $this->template['head'] = $this->load->view('vendor_portal/head', $this->data, true);

        $GLOBALS['customers_head'] = $this->template['head'];

        /**
         * Load the template view
         * @var string
         */
        $module                       = CI::$APP->router->fetch_module();
        $this->data['current_module'] = $module;

        $viewPath = !is_null($module) || $notInThemeViewFiles ? $this->view : 'vendor_portal/' . $this->view;

        $this->template['view']    = $this->load->view($viewPath, $this->data, true);
        $GLOBALS['customers_view'] = $this->template['view'];

        /**
         * Theme footer
         * @var string
         */
        $this->template['footer'] = $this->use_footer == true
        ? $this->load->view('vendor_portal/footer', $this->data, true)
        : '';
        $GLOBALS['customers_footer'] = $this->template['footer'];

        /**
         * @deprecated 2.3.0
         * Theme scripts.php file is no longer used since vresion 2.3.0, add app_customers_footer() in themes/[theme]/index.php
         * @var string
         */

        $this->template['scripts'] = '';
        if (file_exists(VIEWPATH . 'vendor_portal/scripts.php')) {
            if (ENVIRONMENT != 'production') {
                trigger_error(sprintf('%1$s', 'Clients area theme file scripts.php file is no longer used since version 2.3.0, add app_customers_footer() in themes/[theme]/index.php. You can check the original theme index.php for example.'));
            }

            $this->template['scripts'] = $this->load->view('vendor_portal/scripts', $this->data, true);
        }

        /**
         * Load the theme compiled template
         */
        $this->load->view('vendor_portal/index', $this->template);
    }

    /**
     * Sets view data
     * @param  array $data
     * @return core/ClientsController
     */
    public function data($data)
    {
        if (!is_array($data)) {
            return false;
        }

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Set view to load
     * @param  string $view view file
     * @return core/ClientsController
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Sets view title
     * @param  string $title
     * @return core/ClientsController
     */
    public function title($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableNavigation()
    {
        $this->use_navigation = false;

        return $this;
    }

    /**
     * Disables theme navigation
     * @return core/ClientsController
     */
    public function disableSubMenu()
    {
        $this->use_submenu = false;

        return $this;
    }

    /**
    * Disables theme footer
    * @return core/ClientsController
    */
    public function disableFooter()
    {
        $this->use_footer = false;

        return $this;
    }
    /**
     * { index }
     */
    public function index()
    {
        if(is_client_logged_in()){
            $data['is_home'] = true;    

            $data['project_statuses'] = $this->projects_model->get_project_statuses();
            $data['title']            = get_vendor_company_name(get_client_user_id());
            $data['payment'] = $this->purchase_model->get_payment_by_vendor(get_client_user_id());
            $data['pur_order'] = $this->purchase_model->get_pur_order_by_vendor(get_client_user_id());

            $this->data($data);
            $this->view('vendor_portal/home');
            $this->layout();
        }else{
            redirect(site_url('purchase/authentication_vendor'));
        }
    }

    /**
     * { profile contact }
     */
    public function profile()
    {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }
        if ($this->input->post('profile')) {
            $this->form_validation->set_rules('firstname', _l('client_firstname'), 'required');
            $this->form_validation->set_rules('lastname', _l('client_lastname'), 'required');

            $this->form_validation->set_message('contact_email_profile_unique', _l('form_validation_is_unique'));
            $this->form_validation->set_rules('email', _l('clients_email'), 'required|valid_email');
            if ($this->form_validation->run() !== false) {

                handle_vendor_contact_profile_image_upload(get_contact_user_id());

                $data = $this->input->post();

                $success = $this->purchase_model->update_contact([
                    'firstname'          => $this->input->post('firstname'),
                    'lastname'           => $this->input->post('lastname'),
                    'title'              => $this->input->post('title'),
                    'email'              => $this->input->post('email'),
                    'phonenumber'        => $this->input->post('phonenumber'),
                    'direction'          => $this->input->post('direction'),
                  
                ], get_contact_user_id(), true);

                if ($success == true) {
                    set_alert('success', _l('clients_profile_updated'));
                }

                redirect(site_url('purchase/vendors_portal/profile'));
            }
        } elseif ($this->input->post('change_password')) {
            $this->form_validation->set_rules('oldpassword', _l('clients_edit_profile_old_password'), 'required');
            $this->form_validation->set_rules('newpassword', _l('clients_edit_profile_new_password'), 'required');
            $this->form_validation->set_rules('newpasswordr', _l('clients_edit_profile_new_password_repeat'), 'required|matches[newpassword]');
            if ($this->form_validation->run() !== false) {
                $success = $this->purchase_model->change_contact_password(
                    get_contact_user_id(),
                    $this->input->post('oldpassword', false),
                    $this->input->post('newpasswordr', false)
                );

                if (is_array($success) && isset($success['old_password_not_match'])) {
                    set_alert('danger', _l('client_old_password_incorrect'));
                } elseif ($success == true) {
                    set_alert('success', _l('client_password_changed'));
                }

                redirect(site_url('purchase/vendors_portal/profile'));
            }
        }
        $data['contact'] = $this->purchase_model->get_contact(get_contact_user_id());
        $data['title'] = _l('clients_profile_heading');
        $this->data($data);
        $this->view('vendor_portal/vendors/profile_contact');
        $this->layout();
    }

    /**
     * { company profile }
     */
    public function company()
    {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }
        if ($this->input->post() && is_primary_contact()) {
            if (get_option('company_is_required') == 1) {
                $this->form_validation->set_rules('company', _l('clients_company'), 'required');
            }

            if (active_clients_theme() == 'perfex') {
                // Fix for custom fields checkboxes validation
                $this->form_validation->set_rules('company_form', '', 'required');
            }

           

            if ($this->form_validation->run() !== false) {
                $data['company'] = $this->input->post('company');

                if (!is_null($this->input->post('vat'))) {
                    $data['vat'] = $this->input->post('vat');
                }

                if (!is_null($this->input->post('default_language'))) {
                    $data['default_language'] = $this->input->post('default_language');
                }

                if (!is_null($this->input->post('custom_fields'))) {
                    $data['custom_fields'] = $this->input->post('custom_fields');
                }

                $data['phonenumber'] = $this->input->post('phonenumber');
                $data['website']     = $this->input->post('website');
                $data['country']     = $this->input->post('country');
                $data['city']        = $this->input->post('city');
                $data['address']     = $this->input->post('address');
                $data['zip']         = $this->input->post('zip');
                $data['state']       = $this->input->post('state');

                if (get_option('allow_primary_contact_to_view_edit_billing_and_shipping') == 1
                    && is_primary_contact()) {

                    // Dynamically get the billing and shipping values from $_POST
                    for ($i = 0; $i < 2; $i++) {
                        $prefix = ($i == 0 ? 'billing_' : 'shipping_');
                        foreach (['street', 'city', 'state', 'zip', 'country'] as $field) {
                            $data[$prefix . $field] = $this->input->post($prefix . $field);
                        }
                    }
                }

                $success = $this->purchase_model->update_vendor($data, get_client_user_id());
                if ($success == true) {
                    set_alert('success', _l('clients_profile_updated'));
                }

                redirect(site_url('purchase/vendors_portal/company'));
            }
        }

        $data['client'] = $this->purchase_model->get_vendor(get_client_user_id());
        $data['title'] = _l('client_company_info');
        $this->data($data);
        $this->view('vendor_portal/vendors/company_profile');
        $this->layout();
    }

    /**
     * Removes a profile image.
     */
    public function remove_profile_image()
    {
        $id = get_contact_user_id();

        if (file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/contact_profile/' . $id)) {
            delete_dir(PURCHASE_MODULE_UPLOAD_FOLDER.'/contact_profile/'. $id);
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'pur_contacts', [
            'profile_image' => null,
        ]);

        if ($this->db->affected_rows() > 0) {
            redirect(site_url('purchase/vendors_portal/profile'));
        }
        redirect(site_url('purchase/vendors_portal/profile'));
    }

    /**
     * { change language }
     *
     * @param      string  $lang   The language
     */
    public function change_language($lang = '')
    {
       
        hooks()->do_action('before_customer_change_language', $lang);

        $this->db->where('userid', get_client_user_id());
        $this->db->update(db_prefix() . 'pur_vendor', ['default_language' => $lang]);

        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(site_url('purchase/vendors_portal'));
        }
    }

    /**
     * { Purchase order }
     */
    public function purchase_order(){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        $data['title']            = _l('purchase_order');
      
        $data['pur_order'] = $this->purchase_model->get_pur_order_by_vendor(get_client_user_id());

        $this->data($data);
        $this->view('vendor_portal/purchase_order');
        $this->layout();
    }

    /**
     * { list contracts }
     */
    public function contracts(){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        $data['title']            = _l('contracts');
      
        $data['contracts'] = $this->purchase_model->get_contracts_by_vendor(get_client_user_id());

        $this->data($data);
        $this->view('vendor_portal/contracts');
        $this->layout();
    }

    //9.23 AG: show requests
    public function requests() {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }
        $data['title'] = _l('Requests');
        $data['requests'] = $this->requests_model->get_requests_by_vendor( get_client_user_id() );

        $this->data($data);
        $this->view('vendor_portal/requests');
        $this->layout();

    }
    //9.23 AG: show requests: end

    //9.24 AG: offer price
    public function offer_price($request_id) {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }
        $post_data = $this->input->post();
        //acer_log('Vendors_portal -> offer_price : post_data');
        //acer_log($post_data);
        if( $post_data )
        {
            $this->form_validation->set_rules('vehicle_id', _l('Vehicle'), 'required');
            $this->form_validation->set_rules('price', _l('Price'), 'required');
            $b_due_dt = $post_data['cb_due_dt'];
            if($b_due_dt == 'on')
                $this->form_validation->set_rules('due_dt', _l('Due_Dt'), 'required');
            if($this->form_validation->run() !== false)
            {
                unset($post_data['cb_due_dt']);
                $id = $this->requests_model->add_offer($post_data);
                if($id){
                    set_alert('success', _l('your_offer_submitted_successfully', $id));
                    redirect(site_url('purchase/vendors_portal/requests'));
                }
            }
        }

        $data['title'] = _l('Offer_Price');
        $request = $this->requests_model->get_request_by_id( $request_id);
        $data['request'] = $request;

        acer_log('offer price : id = '. get_client_user_id() );

        //vehicles for supplier
        $this->db->select('*,'.db_prefix().'items.id as item_id');
        $this->db->join(db_prefix() . 'pur_vendor_items', db_prefix() . 'pur_vendor_items.items = ' . db_prefix() . 'items.id', 'left');
        $this->db->where('vendor='.get_client_user_id() );
        $vehicles = $this->db->get(db_prefix() . 'items')->result_array();  
        //acer_log($vehicles);  
        
        $data['vehicles'] = $vehicles;
        $data['request_id'] = $request_id;
        $this->data($data);
        $this->view('vendor_portal/offer_price');
        $this->layout();

    }

   
    //9.24 AG: offer price: end

    /**
     * { list items }
     */
    public function items(){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }
        $data['title']            = _l('items');
        $data['items'] = $this->purchase_model->get_item_by_vendor(get_client_user_id());
        $data['commodity_groups'] = $this->purchase_model->get_commodity_group_add_commodity();

        //$this->app->get_table_data(module_views_path('purchase', 'items/table_item_list'));

        //9.10 AG: set languages attribute
        $this->db->select();
        $this->db->from(db_prefix() . 'languages');
        $langs  = $this->db->get()->result_array();
        $data['langs'] = $langs;
        //9.10 AG: set languages attribute: end

        //9.12 AG: set countries attribute
         $this->db->select();
         $this->db->from(db_prefix() . 'countries');
         $countries  = $this->db->get()->result_array();
         $data['countries'] = $countries;
         //9.12 AG: set countries attribute: end

        $this->data($data);
        $this->view('vendor_portal/items');
        $this->layout();
    }

    /**
     * { list quotations }
     */
    public function quotations(){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        $data['title']            = _l('quotations');
      
        $data['quotations'] = $this->purchase_model->get_estimate('',['vendor' => get_client_user_id()]);

        $this->data($data);
        $this->view('vendor_portal/quotations');
        $this->layout();
    }

    /**
     * { list payments }
     */
    public function payments(){
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        $data['title']            = _l('payments');
      
        $data['payments'] = $this->purchase_model->get_payment_by_vendor(get_client_user_id());

        $this->data($data);
        $this->view('vendor_portal/payments');
        $this->layout();
    }

    

    /**
     * Uploads an attachment.
     *
     * @param      <type>  $id     The identifier
     */
    public function upload_attachment()
    {
       $check = handle_pur_vendor_attachments_upload(get_client_user_id());
    }

    /**
     * { preview file pur vendor }
     *
     * @param      <type>  $id      The identifier
     * @param      <type>  $rel_id  The relative identifier
     */
    public function file_pur_vendor($id, $rel_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin']             = is_admin();
        $data['file'] = $this->purchase_model->get_file($id, $rel_id);
        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }


        $this->load->view('vendor_portal/_file',$data);
      
    }

    /**
     * Adds an update quotation.
     *
     * @param      string  $id     The identifier
     */
    public function add_update_quotation($id = '',$view = ''){

        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        if ($id == '') {
            $title = _l('create_new_estimate');
            $data['items'] = $this->purchase_model->get_items_hs_vendor(get_client_user_id());
        } else {
            $estimate = $this->purchase_model->get_estimate($id);

            $data['items'] = $this->purchase_model->get_items_hs_vendor(get_client_user_id());

            $data['etm_detail'] = $this->purchase_model->get_pur_estimate_detail($id);

            $item_edit = [];
            $item_ = [];
            foreach($data['items'] as $it){
                $item_[] = $it['id'];
            }
            foreach($data['etm_detail'] as $es){
                if(!in_array($es['item_code'], $item_)){
                    $item = get_item_hp($es['item_code']);
                    $item_edit['id'] = $es['item_code'];
                    if($item){
                        $item_edit['label'] = $item->commodity_code.' - '.$item->description;
                    }else{
                        $item_edit['label'] = '';
                    }
                    $data['items'][] = $item_edit;
                }
            }

           

            $data['estimate_detail'] = json_encode($this->purchase_model->get_pur_estimate_detail($id));


            $data['estimate'] = $estimate;
            $data['edit']     = true;
            $title            = _l('edit', _l('estimate_lowercase'));
        }
        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }
        $this->load->model('taxes_model');
        $data['taxes'] = $this->purchase_model->get_taxes();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $this->load->model('invoice_items_model');

        
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $data['view'] = $view;
        $data['staff']             = $this->purchase_model->get_vendor_admins(get_client_user_id());
        $data['vendors'] = $this->purchase_model->get_vendor();
        $data['pur_request'] = $this->purchase_model->get_pur_request_by_status(2);
        $data['units'] = $this->purchase_model->get_units();
        

        $data['title']             = $title;
       

        $this->data($data);
        $this->view('vendor_portal/estimate');
        $this->layout();
    }

    /**
     * { items change event}
     *
     * @param      <type>  $val    The value
     * @return      json
     */
    public function items_change($val){

        $value = $this->purchase_model->items_change($val);
        
        echo json_encode([
            'value' => $value
        ]);
        die;
    }

    /**
     * { tax change event }
     *
     * @param      <type>  $tax    The tax
     * @return   json
     */
    public function tax_change($tax){
        $taxes = explode('%7C', $tax);
        $total_tax = $this->purchase_model->get_total_tax($taxes);
        
        echo json_encode([
            'total_tax' => $total_tax,
        ]);
    }

    /**
     * { quotation form }
     *
     * @param      string  $id     The identifier
     */
    public function quotation_form($id='')
    {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        if ($this->input->post()) {
            $estimate_data = $this->input->post();
            $estimate_data['vendor'] = get_client_user_id();
            if ($id == '') {
           
                $id = $this->purchase_model->add_estimate($estimate_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('estimate')));
                    
                    redirect(site_url('purchase/vendors_portal/add_update_quotation/' . $id));
                    
                }
            } else {
            
                $success = $this->purchase_model->update_estimate($estimate_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('estimate')));
                }
                redirect(site_url('purchase/vendors_portal/add_update_quotation/' . $id));
                
            }
        }
    }

     /**
     * { delete estimate }
     *
     * @param      <type>  $id     The identifier
     * @return     redirect
     */
    public function delete_estimate($id)
    {
        if (!is_client_logged_in() && !is_staff_logged_in()) {
            
            redirect(site_url('purchase/authentication_vendor/login'));
        }

        if (!$id) {
            redirect(site_url('purchase/vendors_portal/quotations'));
        }
        $success = $this->purchase_model->delete_estimate($id);
        if (is_array($success)) {
            set_alert('warning', _l('is_invoiced_estimate_delete_error'));
        } elseif ($success == true) {
            set_alert('success', _l('deleted', _l('estimate')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('estimate_lowercase')));
        }
        redirect(site_url('purchase/vendors_portal/quotations'));
    }

    /**
     * { view purchase order }
     */
    public function pur_order($id,$hash){

        check_pur_order_restrictions($id, $hash);
        
        $data['pur_order_detail'] = json_encode($this->purchase_model->get_pur_order_detail($id));
        $data['pur_order'] = $this->purchase_model->get_pur_order($id);
        $title = _l('pur_order_detail');


        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['taxes'] = $this->purchase_model->get_taxes();
        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
        $data['vendors'] = $this->purchase_model->get_vendor();
        $data['estimates'] = $this->purchase_model->get_estimates_by_status(2);
        $data['units'] = $this->purchase_model->get_units();
        $data['items'] = $this->purchase_model->get_items();
        $data['title'] = $title;

        $this->data($data);
        $this->view('vendor_portal/pur_order');
        $this->layout();
    }
    
    
    /**
     * { view purchase order }
     */
    public function pur_request($id,$hash){

        check_pur_request_restrictions($id, $hash);
        
        $this->load->model('departments_model');

        $data['pur_request_detail'] = json_encode($this->purchase_model->get_pur_request_detail($id));
        $data['pur_request'] = $this->purchase_model->get_purchase_request($id);
        $data['title'] = $data['pur_request']->pur_rq_name;
        $data['departments'] = $this->departments_model->get();
        $data['units'] = $this->purchase_model->get_units();
        $data['items'] = $this->purchase_model->get_items();
        
        $data['check_appr'] = $this->purchase_model->get_approve_setting('pur_request');
        $data['get_staff_sign'] = $this->purchase_model->get_staff_sign($id,'pur_request');
        $data['check_approve_status'] = $this->purchase_model->check_approval_details($id,'pur_request');
        $data['list_approve_status'] = $this->purchase_model->get_list_approval_details($id,'pur_request');

        $this->data($data);
        $this->view('vendor_portal/pur_request');
        $this->layout();
    }
    ///////////////////////////////////////////////////////////////////
    /* AG: added coding here: new funcitons.
    * 9.4 -
    *
    */
    ////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////
    /* AG: customization coding here: copied from Purchase Controller.
    * 9.4 -
    *
    */
    ////////////////////////////////////////////////////////////////////



    /**
     * 9.4 AG: added to obtain item list
     */
    public function table_item_list()
    {
        //echo 'table_item_list';
        $items  = $this->purchase_model->get_item_by_vendor(get_client_user_id());
        $this->app->get_table_data(module_views_path('purchase', 'items/table_item_list'), [], 'supplier', $items);
    }
    /**
     * 9.9 AG: added to obtain detailed item data : view btn @ from Purchase controller
     */
    public function get_item_data_ajax($id){

        
        $data = [];
        $data['id'] = $id;
        $data['item'] = $this->purchase_model->get_item($id);
        $data['item_file'] = $this->purchase_model->get_item_attachments($id);
        $this->load->view('items/item_detail', $data);
    }

    /**
     * 9.9 AG: added to get group list : from Purchase controller
     */
    public function get_subgroup_fill_data()
    {
        $data = $this->input->post();
        $subgroup = $this->purchase_model->list_subgroup_by_group($data['group_id']);
        echo json_encode([
        'subgroup' => $subgroup
        ]);

    }
    /**
     * 9.9 AG: added to get commodity file 
     */
    public function get_commodity_file_url($commodity_id){
        $arr_commodity_file = $this->purchase_model->get_item_attachments($commodity_id);
        /*get images old*/
        $images_old_value='';


        if(count($arr_commodity_file) > 0){
            foreach ($arr_commodity_file as $key => $value) {
                $images_old_value .='<div class="dz-preview dz-image-preview image_old'.$value["id"].'">';

                    $images_old_value .='<div class="dz-image">';
                    if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$value["rel_id"].'/'.$value["file_name"])){
                        $images_old_value .='<img class="image-w-h" data-dz-thumbnail alt="'.$value["file_name"].'" src="'.site_url('modules/purchase/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]).'">';
                    }else{
                        $images_old_value .='<img class="image-w-h" data-dz-thumbnail alt="'.$value["file_name"].'" src="'.site_url('modules/warehouse/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]).'">';
                    }
                    $images_old_value .='</div>';

                    $images_old_value .='<div class="dz-error-mark">';
                        $images_old_value .='<a class="dz-remove" data-dz-remove>Remove file';
                        $images_old_value .='</a>';
                    $images_old_value .='</div>';

                    $images_old_value .='<div class="remove_file">';
                        $images_old_value .= '<a href="#" class="text-danger" onclick="delete_contract_attachment(this,'.$value["id"].'); return false;"><i class="fa fa fa-times"></i></a>';
                    $images_old_value .='</div>';

                $images_old_value .='</div>';
            }
        }
            echo json_encode([
                'arr_images' => $images_old_value,
            ]);
            die();
    }
    /*
    * 9.10 AG: url of the form
    */
    public function commodity_list_add_edit($id=''){
        $data = $this->input->post();
       
        if($data){
            if(!isset($data['id'])){
                $ids = $this->purchase_model->add_commodity_one_item($data);
                //9.10 AG: inert a new relation item <-> supplier
                $r = [];
                $r['vendor'] = get_client_user_id();
                $r['items'] = $ids;
                $r['add_from'] = 4;
                $this->db->insert(db_prefix().'pur_vendor_items', $r);
                $r_id = $this->db->insert_id();
                //9.10 AG: end
                if ($ids) {

                    // handle commodity list add edit file
                    $success = true;
                    $message = _l('added_successfully');
                    set_alert('success', $message);
                    /*upload multifile*/
                    echo json_encode([
                        'url'       => site_url("purchase/vendors_portal/items/". $id ),
                        'commodityid' => $ids,
                    ]);
                    die;

                }
                echo json_encode([
                    'url' => admin_url('purchase/items'),
                ]);
                die;
               
            }else{
                $id = $data['id'];
                unset($data['id']);
                $success = $this->purchase_model->update_commodity_one_item($data,$id);

                /*update file*/

                if($success == true){
                    
                    $message = _l('updated_successfully');
                    set_alert('success', $message);
                }
               
                echo json_encode([
                    'url'       => site_url("purchase/vendors_portal/items/". $id ),
                    'commodityid' => $id,
                ]);
                die;
            }
        }

    }

    /**
     * 9.10 AG: delete a commodity
     * delete commodity
     * @param  integer $id 
     * @return redirect
     */
    public function delete_commodity($id){
        if (!$id) {
            redirect(site_url('purchase/vendors_portal/items'));
        }
        $response = $this->purchase_model->delete_commodity($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('commodity_list')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('commodity_list')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('commodity_list')));
        }
        redirect(site_url('purchase/vendors_portal/items'));
    }

    /**
     * 9.10 AG: add attachment file
     * add commodity attachment
     * @param  integer $id
     * @return json
     */
    public function add_commodity_attachment($id)
    {

        handle_item_attachments($id);
        echo json_encode([
            'url' => site_url('purchase/vendors_portal/items'),
        ]);
    }
}