<div class="col-md-12" id="small-table">
	<div class="row">
      <h4 class="no-margin font-bold"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo _l('contracts'); ?></h4>
      <hr />
  	</div>  
    <?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>
      <a href="<?php echo admin_url('purchase/contract?vendor='.$client->userid); ?>"class="btn btn-info pull-left mright10 display-block">
        <i class="fa fa-plus"></i>&nbsp;<?php echo _l('new_pur_order'); ?>
      </a>
    <?php } ?> 	
    <br><br><br>
        
        <?php render_datatable(array(
            _l('contract_name'),
            _l('vendor'),
            _l('pur_order'),
            _l('contract_value'),
            _l('start_date'),
            _l('end_date'),
            _l('add_from'),
            ),'table_contracts'); ?>	
</div>
