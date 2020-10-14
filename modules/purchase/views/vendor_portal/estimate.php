<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			
			if(isset($estimate)){
				echo form_open(site_url('purchase/vendors_portal/quotation_form/'.$estimate->id),array('autocomplete'=>'off'));
			}else{
				echo form_open(site_url('purchase/vendors_portal/quotation_form'),array('autocomplete'=>'off'));
			}
			?>
			<div class="col-md-12">
				<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
   <div class="panel-body">
      
      <div class="row">
         <div class="col-md-6">
            
            

            <?php
               $next_estimate_number = max_number_estimates()+1;
               $format = get_option('estimate_number_format');

                if(isset($estimate)){
                  $format = $estimate->number_format;
                }

               $prefix = get_option('estimate_prefix');

               if ($format == 1) {
                 $__number = $next_estimate_number;
                 if(isset($estimate)){
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">' . $estimate->prefix . '</span>';
                 }
               } else if($format == 2) {
                 if(isset($estimate)){
                   $__number = $estimate->number;
                   $prefix = $estimate->prefix;
                   $prefix = '<span id="prefix">'. $prefix . '</span><span id="prefix_year">' . date('Y',strtotime($estimate->date)).'</span>/';
                 } else {
                   $__number = $next_estimate_number;
                   $prefix = $prefix.'<span id="prefix_year">'.date('Y').'</span>/';
                 }
               } else if($format == 3) {
                  if(isset($estimate)){
                   $yy = date('y',strtotime($estimate->date));
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">'. $estimate->prefix . '</span>';
                 } else {
                  $yy = date('y');
                  $__number = $next_estimate_number;
                }
               } else if($format == 4) {
                  if(isset($estimate)){
                   $yyyy = date('Y',strtotime($estimate->date));
                   $mm = date('m',strtotime($estimate->date));
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">'. $estimate->prefix . '</span>';
                 } else {
                  $yyyy = date('Y');
                  $mm = date('m');
                  $__number = $next_estimate_number;
                }
               }

               $_estimate_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               $isedit = isset($estimate) ? 'true' : 'false';
               $data_original_number = isset($estimate) ? $estimate->number : 'false';
               ?>
            <div class="col-md-6">
              <div class="form-group">
                 <label for="number"><?php echo _l('estimate_add_edit_number'); ?></label>
                 <div class="input-group">
                    <span class="input-group-addon">
                    <?php if(isset($estimate)){ ?>
                    <a href="#" onclick="return false;" data-toggle="popover" data-container='._transaction_form' data-html="true" data-content="<label class='control-label'><?php echo _l('settings_sales_estimate_prefix'); ?></label><div class='input-group'><input name='s_prefix' type='text' class='form-control' value='<?php echo html_entity_decode($estimate->prefix); ?>'></div><button type='button' onclick='save_sales_number_settings(this); return false;' data-url='<?php echo admin_url('estimates/update_number_settings/'.$estimate->id); ?>' class='btn btn-info btn-block mtop15'><?php echo _l('submit'); ?></button>"><i class="fa fa-cog"></i></a>
                     <?php }
                      echo html_entity_decode($prefix);
                    ?>
                   </span>
                    <input type="text" name="number" class="form-control" value="<?php echo html_entity_decode($_estimate_number); ?>" data-isedit="<?php echo html_entity_decode($isedit); ?>" data-original-number="<?php echo html_entity_decode($data_original_number); ?>">
                    <?php if($format == 3) { ?>
                    <span class="input-group-addon">
                       <span id="prefix_year" class="format-n-yy"><?php echo html_entity_decode($yy); ?></span>
                    </span>
                    <?php } else if($format == 4) { ?>
                     <span class="input-group-addon">
                       <span id="prefix_month" class="format-mm-yyyy"><?php echo html_entity_decode($mm); ?></span>
                       /
                       <span id="prefix_year" class="format-mm-yyyy"><?php echo html_entity_decode($yyyy); ?></span>
                    </span>
                    <?php } ?>
                 </div>
              </div>
            </div>
            <div class="col-md-6">
                   

                <label for="buyer" class="control-label"><?php echo _l('buyer'); ?></label>
                <select name="buyer" class="selectpicker" id="buyer" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <option value=""></option>
                   <?php foreach($staff as $st){ ?>
                    <option value="<?php echo html_entity_decode($st['staff_id']); ?>" <?php if(isset($estimate) && $estimate->buyer == $st['staff_id']){ echo 'selected';} ?>><?php echo get_staff_full_name($st['staff_id']); ?></option>
                   <?php } ?>
                 </select>
            </div>
             <div class="col-md-12">
                  <?php $value = (isset($estimate) ? _d($estimate->date) : _d(date('Y-m-d'))); ?>
                  <?php echo render_date_input('date','estimate_add_edit_date',$value); ?>
               </div>
            
            <div class="clearfix mbot15"></div>
            <?php $rel_id = (isset($estimate) ? $estimate->id : false); ?>
            <?php
                  if(isset($custom_fields_rel_transfer)) {
                      $rel_id = $custom_fields_rel_transfer;
                  }
             ?>
            <?php echo render_custom_fields('estimate',$rel_id); ?>
         </div>
         <div class="col-md-6">
            <div class="panel_s no-shadow">
              
               <div class="row">
                  <div class="col-md-12">
                     <?php

                        //$currency_attr = array('disabled'=>true,'data-show-subtext'=>true);
                      
                        foreach($currencies as $currency){
                          if($currency['isdefault'] == 1){
                            $currency_attr['data-base'] = $currency['id'];
                          }
                          if(isset($estimate)){
                            if($currency['id'] == $estimate->currency){
                              $selected = $currency['id'];
                            }
                          } else{
                           if($currency['isdefault'] == 1){
                            $selected = $currency['id'];
                          }
                        }
                        }
                        
                        ?>
                     <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'estimate_add_edit_currency', $selected); ?>
                  </div>
                 
               <div class="col-md-12">
                  <?php
                  $value = '';
                  if(isset($estimate)){
                    $value = _d($estimate->expirydate);
                  } else {
                      if(get_option('estimate_due_after') != 0){
                          $value = _d(date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                      }
                  }
                  echo render_date_input('expirydate','estimate_add_edit_expirydate',$value); ?>
               </div>
                 
               </div>
            </div>
         </div>

               
           
      </div>
   </div>
   <div class="panel-body mtop10">
  <div class="row">
   <div class="col-md-12">
    <p class="bold p_style"><?php echo _l('estimate_detail'); ?></p>
    <hr class="hr_style" />
     <div id="example">
     </div>
     <?php echo form_hidden('estimate_detail'); ?>
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
                                   <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name) ;?>
                                   </span>
                                   </a>
                                   
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
                             <input type="number" value="<?php if(isset($estimate)){ echo app_format_money($estimate->discount_percent,''); } ?>" onchange="dc_percent_change(this); return false;" class="form-control pull-left input-percent text-right" min="0" max="100" name="dc_percent">
                             <div class="input-group-addon">
                                <div class="dropdown">
                                   <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   <span class="discount-type-selected">%</span>
                                   </a>
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

                             <input type="text" value="<?php if(isset($estimate)){ echo app_format_money($estimate->discount_total,''); } ?>" class="form-control pull-left text-right" onchange="dc_total_change(this); return false;" data-type="currency" name="dc_total">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name) ;?>
                                   </span>
                                   </a>
                                   
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

                             <input type="text" disabled="true" class="form-control text-right" name="after_discount" value="<?php if(isset($estimate)){ echo app_format_money($estimate->total,''); } ?>">

                             <div class="input-group-addon">
                                <div class="dropdown">
                                   <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                   <span class="discount-type-selected">
                                    <?php echo html_entity_decode($base_currency->name);?>
                                   </span>
                                   </a>
                                   
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
            <?php $value = (isset($estimate) ? $estimate->vendornote : get_option('predefined_clientnote_estimate')); ?>
            <?php echo render_textarea('vendornote','estimate_add_edit_vendor_note',$value,array(),array(),'mtop15'); ?>
            <?php $value = (isset($estimate) ? $estimate->terms : get_option('predefined_terms_estimate')); ?>
            <?php echo render_textarea('terms','terms_and_conditions',$value,array(),array(),'mtop15'); ?>
            <div class="btn-bottom-toolbar text-right">
              <?php if($view == ''){ ?>
              <button type="submit" onclick="get_hs_data();" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
              </button>
              <?php } ?>
            </div>
         </div>
           <div class="btn-bottom-pusher"></div>
      </div>
   </div>
</div>

			</div>
			<?php echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>
<?php hooks()->do_action('app_admin_footer'); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/estimate_vendor_js.php';?>