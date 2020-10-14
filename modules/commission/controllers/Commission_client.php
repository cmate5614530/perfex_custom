<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Commission client Controller
 */
class commission_client extends ClientsController
{
    public function index()
    {
        if(is_client_logged_in()){
            $this->load->model('commission_model');
            $this->load->model('currencies_model');

            $data['title'] = _l('commission');
            $data['currency'] = $this->currencies_model->get_base_currency();
            $data['products'] = $this->commission_model->get_product_select();
            $data['portal_client'] = 1;
            $data['commissions'] = $this->commission_model->get_commission('', ['is_client' => 1, 'staffid' => get_client_user_id()]);

            $this->data($data);

            $this->view('client/manage_commission');
            $this->layout();
        }else{
            redirect(site_url());
        }
    }

    /**
     * get data commission chart
     * 
     * @return     json
     */
    public function commission_chart(){
        $this->load->model('currencies_model');
        $this->load->model('commission_model');
        $staff_filter = [get_client_user_id()];
        

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
            'data' => $data['amount'],
            'month' => $data['month'],
            'unit' => $currency_unit,
            'name' => $currency_name,
        ]);
        die();
    }
}
