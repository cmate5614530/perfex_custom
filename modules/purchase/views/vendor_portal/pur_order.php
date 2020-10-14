<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
          
          <div class="row">
            <div class="col-md-12">
              <p class="bold p_style"><?php echo _l('general_infor'); ?></p>
              <hr class="hr_style"/>
            </div>
             <div class="col-md-6">
                <table class="table table-striped table-bordered">
                  <tr>
                    <td width="30%"><?php echo _l('pur_order_number'); ?></td>
                    <td><?php echo html_entity_decode($pur_order->pur_order_number) ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('pur_order_name'); ?></td>
                    <td><?php echo html_entity_decode($pur_order->pur_order_name) ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('status'); ?></td>
                    <td><?php echo get_status_approve($pur_order->approve_status) ?></td>
                  </tr>
                </table>
             </div>
             <div class="col-md-6">
                <table class="table table-striped table-bordered">
                  <tr>
                    <td width="30%"><?php echo _l('order_date'); ?></td>
                    <td><?php echo _d($pur_order->order_date) ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('delivery_date'); ?></td>
                    <td><?php echo _d($pur_order->delivery_date) ?></td>
                  </tr>
                  <tr>
                    <td><?php echo _l('total'); ?></td>
                    <td><?php echo app_format_money($pur_order->total,'') ?></td>
                  </tr>
                </table>
             </div>  
               
          </div>
        </div>
        <div class="panel-body mtop10">
        <div class="row">
        <div class="col-md-12">
        <p class="bold p_style"><?php echo _l('pur_order_detail'); ?></p>
        <hr class="hr_style"/>
         <div class="" id="example">
         </div>
         <?php echo form_hidden('pur_order_detail'); ?>
         <div class="col-md-6 col-md-offset-6">
            <table class="table text-right">
               <tbody>
                  <tr id="subtotal">
                     <td class="td_style"><span class="bold"><?php echo _l('subtotal'); ?></span>
                     </td>
                     <td width="65%" id="total_td">
                      
                       <div class="input-group" id="discount-total">

                              <input type="text" disabled="true" class="form-control text-right" name="total_mn" value="">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name) ;?>
                                   </span>
                                   
                                   
                                </div>
                             </div>

                          </div>
                     </td>
                  </tr>
                  <tr id="discount_area">
                      <td>
                          <span class="bold"><?php echo _l('estimate_discount'); ?></span>
                      </td>
                      <td>  
                          <div class="input-group" id="discount-total">
                             <input type="number" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_percent,''); } ?>"  class="form-control pull-left input-percent text-right" min="0" max="100" name="dc_percent">
                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">%</span>
                                  
                                </div>
                             </div>
                          </div>
                     </td>
                  </tr>
                  <tr id="discount_area">
                      <td>
                          <span class="bold"><?php echo _l('estimate_discount'); ?></span>
                      </td>
                      <td>  
                          <div class="input-group" id="discount-total">

                             <input type="text" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->discount_total,''); } ?>" class="form-control pull-left text-right" data-type="currency" name="dc_total">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name) ;?>
                                   </span>
                                   
                                   
                                </div>
                             </div>

                          </div>
                     </td>
                  </tr>
                  <tr>
                     <td class="td_style"><span class="bold"><?php echo _l('after_discount'); ?></span>
                     </td>
                     <td width="55%" id="total_td">
                      
                       <div class="input-group" id="discount-total">

                             <input type="text" disabled="true" class="form-control text-right" name="after_discount" value="<?php if(isset($pur_order)){ echo app_format_money($pur_order->total,''); } ?>">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name) ;?>
                                   </span>
                                   
                                   
                                </div>
                             </div>

                          </div>
                     </td>

                  </tr>
               </tbody>
            </table>
         </div> 
        </div>
      </div>
        </div>
        <div class="row">
          <div class="col-md-12 mtop15">
             <div class="panel-body bottom-transaction">
                <?php $value = (isset($pur_order) ? $pur_order->vendornote : ''); ?>
                <?php echo render_textarea('vendornote','estimate_add_edit_vendor_note',$value,array(),array(),'mtop15'); ?>
                <?php $value = (isset($pur_order) ? $pur_order->terms : ''); ?>
                <?php echo render_textarea('terms','terms_and_conditions',$value,array(),array(),'mtop15'); ?>
               
             </div>
               <div class="btn-bottom-pusher"></div>
          </div>
        </div>
        </div>

			</div>
		
			
		</div>
	</div>
</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/pur_order_vendor_js.php';?>
