<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="form-group">
  <div class="checkbox checkbox-primary">
    <input onchange="purchase_order_setting(this); return false" type="checkbox" id="purchase_order_setting" name="purchase_setting[purchase_order_setting]" <?php if(get_purchase_option('purchase_order_setting') == 1 ){ echo 'checked';} ?> value="purchase_order_setting">
    <label for="purchase_order_setting"><?php echo _l('create_purchase_order_non_create_purchase_request_quotation'); ?>

    <a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('purchase_order_tooltip'); ?>"></i></a>
    </label>
  </div>
</div>

<?php echo form_open(admin_url('purchase/pur_order_setting'),array('id'=>'pur_order_setting-form')); ?>

	<?php echo render_input('pur_order_prefix','pur_order_prefix',get_purchase_option('pur_order_prefix')); ?>



<div class="modal-footer">
	
	<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	<?php echo form_close(); ?>
</div>
<div class="clearfix"></div>


