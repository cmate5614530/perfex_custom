<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s accounting-template estimate">
   <div class="panel-body">
      
      <div class="row">
         <div class="col-md-6">
            <div class="col-md-6 form-group">
              <label for="vendor"><?php echo _l('vendor'); ?></label>
              <select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                  <option value=""></option>
                  <?php foreach($vendors as $s) { ?>
                  <option value="<?php echo html_entity_decode($s['userid']); ?>" <?php if(isset($estimate) && $estimate->vendor->userid == $s['userid']){ echo 'selected'; } ?>><?php echo html_entity_decode($s['company']); ?></option>
                    <?php } ?>
              </select>
              <br><br>
            </div>
            <div class="col-md-5 form-group">
              <label for="pur_request"><?php echo _l('pur_request'); ?></label>
              <select name="pur_request" id="pur_request" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                <option value=""></option>
                  <?php foreach($pur_request as $s) { ?>
                  <option value="<?php echo html_entity_decode($s['id']); ?>" <?php if(isset($estimate) && $estimate->pur_request != '' && $estimate->pur_request->id == $s['id']){ echo 'selected'; } ?> ><?php echo html_entity_decode($s['pur_rq_code'].' - '.$s['pur_rq_name']); ?></option>
                    <?php } ?>
              </select>
              <br><br>
            </div>
            <div class="col-md-1 pad_div_0">
              <a href="#" onclick="coppy_pur_request(); return false;" class="btn btn-success mtop25" data-toggle="tooltip" title="<?php echo _l('coppy_pur_request'); ?>">
              <i class="fa fa-clone"></i>
              </a>
            </div>

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
                         <?php
                        $selected = '';
                        foreach($staff as $member){
                         if(isset($estimate)){
                           if($estimate->buyer == $member['staffid']) {
                             $selected = $member['staffid'];
                           }
                         }
                        }
                        echo render_select('buyer',$staff,array('staffid',array('firstname','lastname')),'buyer',$selected);
                        ?>
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

                        $currency_attr = array('disabled'=>true,'data-show-subtext'=>true);
                        $currency_attr = apply_filters_deprecated('estimate_currency_disabled', [$currency_attr], '2.3.0', 'estimate_currency_attributes');
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
                        $currency_attr = hooks()->apply_filters('estimate_currency_attributes',$currency_attr);
                        ?>
                     <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'estimate_add_edit_currency', $selected, $currency_attr); ?>
                  </div>
                  <div class="col-md-6">
                  <?php $value = (isset($estimate) ? _d($estimate->date) : _d(date('Y-m-d'))); ?>
                  <?php echo render_date_input('date','estimate_add_edit_date',$value); ?>
               </div>
               <div class="col-md-6">
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
              
              <button type="button" class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
              </button>
            </div>
         </div>
           <div class="btn-bottom-pusher"></div>
      </div>
   </div>
</div>
