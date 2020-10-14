<div class="col-md-12" id="small-table">
	<div class="row">
      <h4 class="no-margin font-bold"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo _l('payments'); ?></h4>
      <hr />
  	</div>  	
    <br>
    <table class="table dt-table">
       <thead>
       	<th><?php echo _l('purchase_order'); ?></th>
         <th><?php echo _l('payments_table_amount_heading'); ?></th>
          <th><?php echo _l('payments_table_mode_heading'); ?></th>
          <th><?php echo _l('payment_transaction_id'); ?></th>
          <th><?php echo _l('payments_table_date_heading'); ?></th>
       </thead>
      <tbody>
         <?php foreach($payments as $p) { ?>
         	<td><a href="<?php echo admin_url('purchase/purchase_order/' . $p['pur_order']); ?>" ><?php echo html_entity_decode($p['pur_order_name']); ?></a></td>
         	<td><?php echo app_format_money($p['amount'],''); ?></td>
         	<td><?php echo get_payment_mode_by_id($p['paymentmode']); ?></td>
         	<td><?php echo html_entity_decode($p['transactionid']); ?></td>
         	<td><?php echo _d($p['date']); ?></td>
         <?php } ?>
      </tbody>
   </table>	
</div>
