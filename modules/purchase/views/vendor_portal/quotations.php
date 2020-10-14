<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel_s">
			<div class="panel-body">
				<h4><?php echo html_entity_decode($title) ?></h4>
				<hr>
				<a href="<?php echo site_url('purchase/vendors_portal/add_update_quotation'); ?>" class="btn btn-info"><?php echo _l('add_new'); ?></a>
				<br><br>
				<table class="table dt-table" >
		            <thead>
		               <tr>
		                  <th ><?php echo _l('quotations'); ?></th>
		                  <th ><?php echo _l('estimate_dt_table_heading_amount'); ?></th>
		                  <th ><?php echo _l('estimates_total_tax'); ?></th>
		                  <th ><?php echo _l('estimate_dt_table_heading_date'); ?></th>
		                  <th ><?php echo _l('estimate_dt_table_heading_expirydate'); ?></th>
		                  <th ><?php echo _l('status'); ?></th>
		                  <th ><?php echo _l('options'); ?></th>
		               </tr>
		            </thead>
		            <tbody>
		            	<?php foreach($quotations as $p){ ?>
		            		<tr>
		            			<td><a href="<?php echo site_url('purchase/vendors_portal/add_update_quotation/'.$p['id'].'/1'); ?>"><?php echo html_entity_decode(format_pur_estimate_number($p['id'])); ?></a></td>
		            			<td><?php echo html_entity_decode(app_format_money($p['total'], '')); ?></td>
		            			<td><?php echo app_format_money($p['total_tax'], ''); ?></td>
		            			<td><span class="label label-primary"><?php echo html_entity_decode(_d($p['date'])); ?></span></td>
		            			<td><span class="label label-danger"><?php echo html_entity_decode(_d($p['expirydate'])); ?></span></td>
		            			<td><?php echo get_status_approve($p['status']); ?></td>
		            			<td>
		            				<?php if($p['status'] != 2){ ?>
		            				<a href="<?php echo site_url('purchase/vendors_portal/add_update_quotation/'.$p['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-edit"></i></a>

		            				<a href="<?php echo site_url('purchase/vendors_portal/delete_estimate/'.$p['id']); ?>" class="btn btn-danger btn-icon"><i class="fa fa-remove"></i></a>
		            				<?php } ?>
		            			</td>
		            		</tr>
		            	<?php } ?>
		            </tbody>
		         </table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>
