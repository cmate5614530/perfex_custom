<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Pur_order_pdf extends App_pdf
{
    protected $pur_order;

    public function __construct($pur_order)
    {
        $pur_order                = hooks()->apply_filters('request_html_pdf_data', $pur_order);
        $GLOBALS['pur_order_pdf'] = $pur_order;

        parent::__construct();

        $this->pur_order = $pur_order;

        $this->SetTitle(_l('pur_order'));
        # Don't remove these lines - important for the PDF layout
        $this->pur_order = $this->fix_editor_html($this->pur_order);
    }

    public function prepare()
    {
        $this->set_view_vars('pur_order', $this->pur_order);

        return $this->build();
    }

    protected function type()
    {
        return 'pur_order';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/purchase/views/purchase_order/pur_orderpdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}