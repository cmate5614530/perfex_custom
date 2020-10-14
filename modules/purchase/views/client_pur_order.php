<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-md-12">
		<?php $pur_order = get_pur_order_by_client($client->userid) ?>
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
          <?php if(count($pur_order) > 0){ ?>
          <?php foreach($pur_order as $p){ ?>
            <tr>
              <td><a href="<?php echo admin_url('purchase/purchase_order/'.$p['id']); ?>"><?php echo html_entity_decode($p['pur_order_number'].' - '.$p['pur_order_name']); ?></a></td>
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
        <?php } ?>
        </tbody>
     </table>
	</div>
</div>