<?php defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

// Get Y position for the separation
$y = $pdf->getY();

$company_info = '<div>';
$company_info .= format_organization_info();
$company_info .= '</div>';

// Bill to
$client_details = get_staff_full_name($receipt->addedfrom);

$left_info  = $swap == '1' ? $client_details : $company_info;
$right_info = $swap == '1' ? $company_info : $client_details;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->SetFontSize(15);

$receit_heading = '<div style="text-align:center"><center>' . mb_strtoupper(_l('commission_payment_receipt'), 'UTF-8') . '</center></div>';
$pdf->Ln(20);
$pdf->writeHTMLCell(0, '', '', '', $receit_heading, 0, 1, false, true, 'L', true);
$pdf->SetFontSize($font_size);
$pdf->Ln(20);
$pdf->Cell(0, 0, _l('payment_date') . ' ' . _d($receipt->date), 0, 1, 'L', 0, '', 0);
$pdf->Ln(2);
$pdf->writeHTMLCell(80, '', '', '', '<hr/>', 0, 1, false, true, 'L', true);
$receipt_name = $receipt->paymentmode_name;
if (!empty($receipt->paymentmethod)) {
    $receipt_name .= ' - ' . $receipt->paymentmethod;
}
$pdf->Cell(0, 0, _l('payment_view_mode') . ' ' . $receipt_name, 0, 1, 'L', 0, '', 0);
if (!empty($receipt->transactionid)) {
    $pdf->Ln(2);
    $pdf->writeHTMLCell(80, '', '', '', '<hr/>', 0, 1, false, true, 'L', true);
    $pdf->Cell(0, 0, _l('payment_transaction_id') . ': ' . $receipt->transactionid, 0, 1, 'L', 0, '', 0);
}
$pdf->Ln(2);
$pdf->writeHTMLCell(80, '', '', '', '<hr />', 0, 1, false, true, 'L', true);
$pdf->SetFillColor(132, 197, 41);
$pdf->SetTextColor(255);
$pdf->SetFontSize(12);
$pdf->Ln(3);
$pdf->Cell(80, 10, _l('payment_total_amount'), 0, 1, 'C', '1');
$pdf->SetFontSize(11);
$pdf->Cell(80, 10, app_format_money($receipt->amount, $receipt->currency_name), 0, 1, 'C', '1');

$pdf->Ln(10);
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, 'B', 14);
$pdf->Cell(0, 0, _l('payment_for_string'), 0, 1, 'L', 0, '', 0);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Ln(5);

// Header
$tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
<tr height="30" style="color:#fff;" bgcolor="#3A4656">
    <th width="15%;">' . _l('invoice_dt_table_heading_number') . '</th>
    <th width="15%;">' . _l('date_sold') . '</th>
    <th width="25%;">' . _l('client') . '</th>
    <th width="25%;">' . _l('sale_agent_string') . '</th>
    <th width="10%;">' . _l('sale_amount') . '</th>
    <th width="10%;">' . _l('commission') . '</th>';

$tblhtml .= '</tr>';

$tblhtml .= '<tbody>';
foreach ($receipt->list_commission as $key => $value) {
    $tblhtml .= '<tr>';
    $tblhtml .= '<td>' . format_invoice_number($value['invoice_id']) . '</td>';
    $tblhtml .= '<td>' . _d($value['date']) . '</td>';
    $tblhtml .= '<td>' . $value['company'] . '</td>';
    $tblhtml .= '<td>' . $value['sale_name'] . '</td>';
    $tblhtml .= '<td>' . app_format_money($value['total'], $receipt->currency_name) . '</td>';
    $tblhtml .= '<td>' . app_format_money($value['amount'], $receipt->currency_name) . '</td>';
    $tblhtml .= '</tr>';
}

$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');
