<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4><?php echo html_entity_decode($title) ?></h4>
				<hr>
				<table class="table dt-table" >
		            <thead>
		               <tr>
		                  <th ><?php echo _l('contracts'); ?></th>
		                  <th ><?php echo _l('contract_value'); ?></th>
		                  <th ><?php echo _l('purchase_order'); ?></th>
		                  <th ><?php echo _l('start_date'); ?></th>
		                  <th ><?php echo _l('end_date'); ?></th>
		               </tr>
		            </thead>
		            <tbody>
		            	<?php foreach($contracts as $p){ ?>
		            		<tr>
		            			<td><?php echo html_entity_decode($p['contract_number'].' - '.$p['contract_name']); ?></td>
		            			<td><?php echo html_entity_decode(app_format_money($p['contract_value'],'')); ?></td>
		            			<td><?php echo html_entity_decode(get_pur_order_subject($p['pur_order'])); ?></td>
		            			<td><?php echo html_entity_decode(_d($p['start_date'])); ?></td>
		            			<td><?php echo html_entity_decode(_d($p['end_date'])); ?></td>
		            		</tr>
		            	<?php } ?>
		            </tbody>
		         </table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>
