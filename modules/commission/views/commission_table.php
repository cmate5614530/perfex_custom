<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="commission_table" class="hide">
   <div class="row">
         <div class="col-md-3" id="div_staff_filter">
         <?php echo render_select('staff_filter', $staffs, array('staffid', 'firstname', 'lastname'), 'staff', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
         </div>
         <div class="col-md-3" id="div_client_filter">
         <?php echo render_select('client_filter', $clients, array('userid', 'company'), 'client', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
         </div>
         <div class="col-md-3">
         <?php echo render_select('products_services', $products, array('id', 'label'), 'products_services', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
         </div>
         <div class="col-md-3">
         <?php
         $statuss = [['id' => '2', 'label' => _l('invoice_status_unpaid')],['id' => '1', 'label' => _l('invoice_status_paid')]];
          echo render_select('status', $statuss, array('id', 'label'), 'invoice_dt_table_heading_status', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
         </div>
   <div class="clearfix"></div>
</div>
<table class="table table-commission scroll-responsive">
   <thead>
      <tr>
         <th><?php echo _l('invoice_dt_table_heading_number'); ?></th>
         <th><?php echo _l('date_sold'); ?></th>
         <th><?php echo _l('client'); ?></th>
         <th><?php echo _l('sale_agent_string'); ?></th>
         <th><?php echo _l('sale_amount'); ?></th>
         <th><?php echo _l('commission'); ?></th>
         <th><?php echo _l('invoice_dt_table_heading_status'); ?></th>
      </tr>
   </thead>
   <tbody></tbody>
   <tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="total"></td>
         <td class="total_commission"></td>
      </tr>
   </tfoot>
</table>
</div>
