<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="commission_table" class="hide">
   <div class="row">
      <div class="clearfix"></div>
      <table class="table table-commission scroll-responsive">
         <thead>
            <tr>
               <th><?php echo _l('invoice_dt_table_heading_number'); ?></th>
               <th><?php echo _l('date_sold'); ?></th>
               <th><?php echo _l('customer'); ?></th>
               <th><?php echo _l('sale_amount'); ?></th>
               <th><?php echo _l('commission'); ?></th>
               <th><?php echo _l('invoice_dt_table_heading_status'); ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <?php 
            $total = 0;
            $total_commission = 0;
            foreach($commissions as $commission){ ?>
               <tr>
                  <td><a href="<?php echo site_url('invoice/' . $commission['invoice_id'] . '/' . $commission['invoice_hash']); ?>" class="invoice-number"><?php echo format_invoice_number($commission['invoice_id']); ?></a></td>
                  <td><?php echo _d($commission['commission_date']); ?></td>
                  <td><?php echo html_entity_decode($commission['company']); ?></td>
                  <td><?php 
                     $total += $commission['total'];
                     echo app_format_money($commission['total'], $currency->name); ?></td>
                  <td><?php 
                     $total_commission += $commission['amount'];
                     echo app_format_money($commission['amount'], $currency->name); ?></td>
                    <td>
                     <?php if($commission['paid'] == 1){
                    $status_name = _l('invoice_status_paid');
                    $label_class = 'success';
                }else{
                    $status_name = _l('invoice_status_unpaid');
                    $label_class = 'danger';
                }
                 ?>
                  <span class="label label-<?php echo html_entity_decode($label_class); ?> s-status commission-status-<?php echo html_entity_decode($commission['paid']); ?>"><?php echo html_entity_decode($status_name);  ?></span></td>
               </tr>
            <?php } ?>
            <tr>
               <td><?php echo _l('total'); ?></td>
               <td></td>
               <td></td>
               <td class="total"><?php echo app_format_money($total, $currency->name); ?></td>
               <td class="total_commission"><?php echo app_format_money($total_commission, $currency->name); ?></td>
            </tr>
         </tfoot>
      </table>
   </div>
</div>
