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
		                  <th ><?php echo _l('purchase_order'); ?></th>
		                  <th ><?php echo _l('total'); ?></th>
		                  <th ><?php echo _l('order_date'); ?></th>
		                  <th ><?php echo _l('payment_status'); ?></th>
		               </tr>
		            </thead>
		            <tbody>
		            	<?php foreach($pur_order as $p){ ?>
		            		<tr>
		            			<td><a href="<?php echo site_url('purchase/vendors_portal/pur_order/'.$p['id'].'/'.$p['hash']); ?>"><?php echo html_entity_decode($p['pur_order_number'].' - '.$p['pur_order_name']); ?></a></td>
		            			<td><?php echo html_entity_decode(app_format_money($p['total'],'')); ?></td>
		            			<td>
		            				<span class="label label-primary"><?php echo html_entity_decode(_d($p['order_date'])); ?></span></td>
		            			<td>
		            				<?php
		            				$paid = $p['total'] - purorder_left_to_pay($p['id']);
						            $percent = 0;
						            if($p['total'] > 0){
						                $percent = ($paid / $p['total'] ) * 100;
						            }

						                $_data = '<div class="progress-bar bg-secondary task-progress-bar-ins-31" id="31" style="width: '.round($percent).'%; border-radius: 1em;">'.round($percent).'%</div>';
						               echo html_entity_decode($_data);
		            				 ?>
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
