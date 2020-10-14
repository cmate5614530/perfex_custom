<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_js_controller extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_admin()) {
            redirect('admin');
        }
    }
    public function index(){}
    /**
     * [ Update scripts in database for admin area]
     * @return string
     */
    public function action_admin()
    {
        $data = $this->input->post('data', false);
        $data = json_encode($data);
        $sql = "UPDATE ".db_prefix()."options SET value = {$data} WHERE name = 'custom_js_admin_scripts';";
        $query = $this->db->query($sql);
        if ($query) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
     /**
     * [ Update scripts in database for customers area]
     * @return string
     */
    public function action_customers()
    {
        $data = $this->input->post('data', false);
        $data = json_encode($data);
        $sql = "UPDATE ".db_prefix()."options SET value = {$data} WHERE name = 'custom_js_customer_scripts';";
        $query = $this->db->query($sql);
        if ($query) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
}
/* End of file Custom_js_controller.php */

