<div role="tabpanel" class="tab-pane customer_commission" id="customer_commission">
	<table class="table dt-table">
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
       <tbody>
          <?php 
	        $CI = &get_instance();
	        $CI->load->model('commission/commission_model');
	        $CI->load->model('currencies_model');
	        $currency = $CI->currencies_model->get_base_currency();
	        $commissions = $CI->commission_model->get_commission('', ['is_client' => 1,'staffid' => $client->userid]);
	        foreach($commissions as $commission){ ?>
	           <tr>
	              <td><a href="<?php echo site_url('invoice/' . $commission['invoice_id'] . '/' . $commission['invoice_hash']); ?>" class="invoice-number"><?php echo format_invoice_number($commission['invoice_id']); ?></a></td>
	              <td><?php echo _d($commission['commission_date']); ?></td>
	              <td><?php echo html_entity_decode($commission['company']); ?></td>
	              <td><?php 
	                 echo app_format_money($commission['total'], $currency->name); ?></td>
	              <td><?php 
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
       </tbody>
    </table>
</div>