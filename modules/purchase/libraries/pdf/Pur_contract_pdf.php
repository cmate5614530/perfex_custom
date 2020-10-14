<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Pur_contract_pdf extends App_pdf
{
    protected $contract;

    public function __construct($contract)
    {
        $contract                = hooks()->apply_filters('contract_html_pdf_data', $contract);
        $GLOBALS['contract_pdf'] = $contract;

        parent::__construct();

        $this->contract = $contract;
        $this->load_language($this->contract->vendor);
        $this->SetTitle($this->contract->contract_number);

        # Don't remove these lines - important for the PDF layout
        $this->contract->content = $this->fix_editor_html($this->contract->content);
    }

    public function prepare()
    {
        $this->set_view_vars('contract', $this->contract);

        return $this->build();
    }

    protected function type()
    {
        return 'contract';
    }

    protected function file_path()
    {
        $actualPath = APP_MODULES_PATH . '/purchase/views/contracts/contractpdf.php';
        return $actualPath;
    }
}
