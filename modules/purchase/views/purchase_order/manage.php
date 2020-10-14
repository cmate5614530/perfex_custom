<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
	              	<div class="row">    
	                    <div class="_buttons col-md-3">
	                    	<?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>
	                        <a href="<?php echo admin_url('purchase/pur_order'); ?>"class="btn btn-info pull-left mright10 display-block">
	                            <?php echo _l('new_pur_order'); ?>
	                        </a>
	                        <?php } ?>
	                    </div>
	                    
	                    <div class="_buttons col-md-1 pull-right">
	                    <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs pull-right" onclick="toggle_small_pur_order_view('.table-table_pur_order','#pur_order'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
	                	</div>
	            	</div>
	              	<div class="row">
	              		<hr>
	              		<div class="col-md-2">
	                        <?php echo render_date_input('from_date',_l('from_date'),''); ?>
	                    </div>
	                    <div class="col-md-2">
	                        <?php echo render_date_input('to_date',_l('to_date'),''); ?>
	                    </div>
                       <div class=" col-md-2">
                        <label for="item_filter"><?php echo _l('tags'); ?></label>
                         <select name="item_filter[]" id="item_filter" class="selectpicker" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('leads_all'); ?>">
                              <?php foreach($item_tags as $item_f) { ?>
                               <option value="<?php echo html_entity_decode($item_f['rel_id']); ?>"><?php echo html_entity_decode($item_f['name']); ?></option>
                               <?php } ?>
                        </select>
                       </div>
	                    <div class="col-md-3">
	                        <?php 
	                        $statuses = [0 => ['id' => '1', 'name' => _l('not_yet_approve')],
	                    	1 => ['id' => '2', 'name' => _l('approved')],
	                		2 => ['id' => '3', 'name' => _l('reject')],
	                		3 => ['id' => '4', 'name' => _l('canclled')],];

	                        echo render_select('status[]',$statuses,array('id','name'),'status','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
	                    </div>
	                    <div class="col-md-3">
	                        <?php echo render_select('vendor[]',$vendors,array('userid','company'),'vendor','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
	                    </div>
	                    
	              	</div>
	            </div>
            </div>
            <div class="row">
				<div class="col-md-12" id="small-table">
					<div class="panel_s">
						<div class="panel-body">
	                    <?php echo form_hidden('pur_orderid',$pur_orderid); ?>
	                    <?php $table_data = array(
                           _l('purchase_order'),
                           _l('total'),
                           _l('estimates_total_tax'),
                           _l('vendor'),
                           _l('tags'),
                           _l('order_date'),
                           _l('payment_status'),
                           _l('status'),
                           _l('convert_expense'),
                           );
                       $custom_fields = get_custom_fields('pur_order',array('show_on_table'=>1));
                        foreach($custom_fields as $field){
                         array_push($table_data,$field['name']);
                        }
                       render_datatable($table_data,'table_pur_order'); ?>
							
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
</div>

<div class="modal fade" id="pur_order_expense" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <?php echo form_open(admin_url('purchase/add_expense'),array('id'=>'pur_order-expense-form','class'=>'dropzone dropzone-manual')); ?>
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('add_new', _l('expense_lowercase')); ?></h4>
         </div>
         <div class="modal-body">
            <div id="dropzoneDragArea" class="dz-default dz-message">
               <span><?php echo _l('expense_add_edit_attach_receipt'); ?></span>
            </div>
            <div class="dropzone-previews"></div>
            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('expense_name_help'); ?>"></i>
            <?php echo render_input('expense_name','expense_name'); ?>
            <?php echo render_textarea('note','expense_add_edit_note','',array('rows'=>4),array()); ?>
            <?php echo render_select('category',$expense_categories,array('id','name'),'expense_category'); ?>
            <?php echo render_date_input('date','expense_add_edit_date',_d(date('Y-m-d'))); ?>
            <?php echo render_input('amount','expense_add_edit_amount','','number'); ?>
            <div class="row mbot15">
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label" for="tax"><?php echo _l('tax_1'); ?></label>
                     <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('no_tax'); ?></option>
                        <?php foreach($taxes as $tax){ ?>
                        <option value="<?php echo html_entity_decode($tax['id']); ?>" data-subtext="<?php echo html_entity_decode($tax['name']); ?>"><?php echo html_entity_decode($tax['taxrate']); ?>%</option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label>
                     <select class="selectpicker display-block" data-width="100%" name="tax2" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" disabled>
                        <option value=""><?php echo _l('no_tax'); ?></option>
                        <?php foreach($taxes as $tax){ ?>
                        <option value="<?php echo html_entity_decode($tax['id']); ?>" data-subtext="<?php echo html_entity_decode($tax['name']); ?>"><?php echo html_entity_decode($tax['taxrate']); ?>%</option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="hide">
               <?php echo render_select('currency',$currencies,array('id','name','symbol'),'expense_currency',$currency->id); ?>
            </div>
           
            <div class="checkbox checkbox-primary">
               <input type="checkbox" id="billable" name="billable" checked>
               <label for="billable"><?php echo _l('expense_add_edit_billable'); ?></label>
            </div>
            <?php echo render_input('reference_no','expense_add_edit_reference_no'); ?>
           
            <?php
               // Fix becuase payment modes are used for invoice filtering and there needs to be shown all
               // in case there is payment made with payment mode that was active and now is inactive
               $expenses_modes = array();
               foreach($payment_modes as $m){
               if(isset($m['invoices_only']) && $m['invoices_only'] == 1) {continue;}
               if($m['active'] == 1){
               $expenses_modes[] = $m;
               }
               }
               ?>
            <?php echo render_select('paymentmode',$expenses_modes,array('id','name'),'payment_mode'); ?>
            <div class="clearfix mbot15"></div>
            <?php echo render_custom_fields('expenses'); ?>
        	<div id="pur_order_additional"></div>
            <div class="clearfix"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>

<?php init_tail(); ?>
</body>
</html>