<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4><?php echo html_entity_decode($title) ?></h4>
				<hr>
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
			         <tr>
			         	<td><?php echo html_entity_decode($p['pur_order_name']); ?></td>
			         	<td><?php echo app_format_money($p['amount'],''); ?></td>
			         	<td><?php echo get_payment_mode_by_id($p['paymentmode']); ?></td>
			         	<td><?php echo html_entity_decode($p['transactionid']); ?></td>
			         	<td><span class="label label-primary"><?php echo _d($p['date']); ?></span></td>
			         </tr>
			         <?php } ?>
			      </tbody>
			   </table>	
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>
