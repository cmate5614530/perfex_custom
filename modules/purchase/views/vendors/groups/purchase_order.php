<div class="col-md-12" id="small-table">
	<div class="row">
      <h4 class="no-margin font-bold"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?php echo _l('purchase_order'); ?></h4>
      <hr />
  	</div> 
    <?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>
      <a href="<?php echo admin_url('purchase/pur_order?vendor='.$client->userid); ?>"class="btn btn-info pull-left mright10 display-block">
        <i class="fa fa-plus"></i>&nbsp;<?php echo _l('new_pur_order'); ?>
      </a>
    <?php } ?> 	
    <br><br><br>
        <?php $table_data = array(
        _l('purchase_order'),
        _l('total'),
        _l('estimates_total_tax'),
        _l('vendor'),
        _l('order_date'),
        _l('payment_status'),
        _l('status'),
        );
        $custom_fields = get_custom_fields('pur_order',array('show_on_table'=>1));
        foreach($custom_fields as $field){
         array_push($table_data,$field['name']);
        }
        render_datatable($table_data,'table_pur_order'); ?>
</div>
