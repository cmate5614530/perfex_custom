<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12" id="small-table">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
		                 <div class="col-md-4 border-right">
		                  <h4 class="no-margin font-bold"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
		                  <hr />
		                 </div>
		              	</div>
		              	<div class="row">    
	                        <div class="_buttons col-md-3">
	                        	<?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>
		                        <a href="<?php echo admin_url('purchase/contract'); ?>"class="btn btn-info pull-left mright10 display-block">
		                            <?php echo _l('new_pur_order'); ?>
		                        </a>
		                        <?php } ?>
		                    </div>
		                    
                    	</div>
                    	

                    <br><br>
                    
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
				</div>
			</div>
			<div class="col-md-7 small-table-right-col">
			    <div id="pur_order" class="hide">
			    </div>
			 </div>
		</div>
	</div>
</div>

<?php init_tail(); ?>
</body>
</html>