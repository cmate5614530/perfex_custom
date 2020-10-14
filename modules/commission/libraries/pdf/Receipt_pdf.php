<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Receipt_pdf extends App_pdf
{
    protected $receipt;

    public function __construct($receipt, $tag = '')
    {
        $GLOBALS['receipt_pdf'] = $receipt;

        parent::__construct();

        $this->receipt = $receipt;

        $this->SetTitle(_l('receipt') . ' #' . $this->receipt->id);
    }

    public function prepare()
    {
        $this->set_view_vars([
            'receipt'   => $this->receipt,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'receipt';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_receiptpdf.php';
        $actualPath = APP_MODULES_PATH . '/commission/views/receipts/receiptpdf.php';
        
        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}
