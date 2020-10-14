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
		                  <th ><?php echo _l('Vehicle_Make'); ?></th>
		                  <th ><?php echo _l('Vehicle_Model'); ?></th>
		               </tr>
		            </thead>
		            <tbody>
		            	<?php foreach($items as $p){ 
		            		$item = get_item_hp($p['items']);
		            		if($item){
		            		?>
		            		<tr>
		            			<td><?php echo html_entity_decode($item->vehicle_make); ?></td>
		            			<td><?php echo html_entity_decode($item->vehicle_model); ?></td>
		            		</tr>
		            		<?php }else{ ?>
		            			<tr>
		            			<td></td>
		            			<td></td>
		            		</tr>
		            	<?php }  } ?>
		            </tbody>
		         </table>
			</div>
		</div>
	</div>
</div>