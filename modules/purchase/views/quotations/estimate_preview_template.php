<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('_attachment_sale_id',$estimate->id); ?>
<?php echo form_hidden('_attachment_sale_type','estimate'); ?>
<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
         <?php if($estimate->status == 1){ ?>
           <div class="ribbon info"><span class="fontz9"><?php echo _l('not_yet_approve'); ?></span></div>
       <?php }elseif($estimate->status == 2){ ?>
         <div class="ribbon success"><span><?php echo _l('approved'); ?></span></div>
       <?php }elseif($estimate->status == 3){ ?>  
         <div class="ribbon danger"><span><?php echo _l('reject'); ?></span></div>
       <?php } ?>
         <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#tab_estimate" aria-controls="tab_estimate" role="tab" data-toggle="tab">
                     <?php echo _l('estimate'); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#tab_tasks" onclick="init_rel_tasks_table(<?php echo html_entity_decode($estimate->id); ?>,'pur_quotation'); return false;" aria-controls="tab_tasks" role="tab" data-toggle="tab">
                     <?php echo _l('tasks'); ?>
                     </a>
                  </li>  
                  <li role="presentation" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="tab-separator toggle_view">
                     <a href="#" onclick="small_table_full_view(); return false;">
                     <i class="fa fa-expand"></i></a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4">
              <p class="bold mtop15" ><?php echo _l('vendor').': '?><a href="<?php echo admin_url('purchase/vendor/'.$estimate->vendor->userid); ?>"><?php echo html_entity_decode($estimate->vendor->company); ?></a></p>
            </div>
            <div class="col-md-8">
              <?php if($estimate->status != 2){ ?>
               <div class="pull-right _buttons">
                  <?php if(has_permission('estimates','','edit')){ ?>
                  <a href="<?php echo admin_url('purchase/estimate/'.$estimate->id); ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('edit_estimate_tooltip'); ?>" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                  <?php } ?>

               </div>
             <?php } ?>

               <select name="status" id="status" class="selectpicker pull-right mright10" onchange="change_status_pur_estimate(this,<?php echo html_entity_decode($estimate->id); ?>); return false;" data-live-search="true" data-width="35%" data-none-selected-text="<?php echo _l('change_status_to'); ?>">
                 <option value=""></option>
                 <option value="1" class="<?php if($estimate->status == 1) { echo 'hide';}?>"><?php echo _l('not_yet_approve'); ?></option>
                 <option value="2" class="<?php if($estimate->status == 2) { echo 'hide';}?>"><?php echo _l('approved'); ?></option>
                 <option value="3" class="<?php if($estimate->status == 3) { echo 'hide';}?>"><?php echo _l('reject'); ?></option>
               </select>
               
               <div class="pull-right mright5">
                            <?php if($check_appr && $check_appr != false){
                            if($estimate->status != 2 && ($check_approve_status == false || $check_approve_status == 'reject')){ ?>
                        <a data-toggle="tooltip" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-success lead-top-btn lead-view" data-placement="top" href="#" onclick="send_request_approve(<?php echo html_entity_decode($estimate->id); ?>); return false;"><?php echo _l('send_request_approve'); ?></a>
                      <?php } }
                        if(isset($check_approve_status['staffid'])){
                            ?>
                            <?php 
                        if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && !in_array(get_staff_user_id(), $get_staff_sign)){ ?>
                            <div class="btn-group" >
                                   <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('approve'); ?><span class="caret"></span></a>
                                   <ul class="dropdown-menu dropdown-menu-right ul_style" >
                                    <li>
                                      <div class="col-md-12">
                                        <?php echo render_textarea('reason', 'reason'); ?>
                                      </div>
                                    </li>
                                      <li>
                                        <div class="row text-right col-md-12">
                                          <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="approve_request(<?php echo html_entity_decode($estimate->id); ?>); return false;" class="btn btn-success mright15" ><?php echo _l('approve'); ?></a>
                                         <a href="#" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="deny_request(<?php echo html_entity_decode($estimate->id); ?>); return false;" class="btn btn-warning"><?php echo _l('deny'); ?></a></div>
                                      </li>
                                   </ul>
                                </div>
                          <?php }
                            ?>
                            
                          <?php
                           if(in_array(get_staff_user_id(), $check_approve_status['staffid']) && in_array(get_staff_user_id(), $get_staff_sign)){ ?>
                            <button onclick="accept_action();" class="btn btn-success pull-right action-button"><?php echo _l('s'); ?></button>
                          <?php }
                            ?>
                            <?php 
                             }
                            ?>
                          </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="tab-content">
           <div role="tabpanel" class="tab-pane" id="tab_tasks">
               <?php init_relation_tasks_table(array('data-new-rel-id'=>$estimate->id,'data-new-rel-type'=>'pur_quotation')); ?>
            </div>
            <div role="tabpanel" class="tab-pane ptop10 active" id="tab_estimate">
               <div id="estimate-preview">
                  <div class="row">
                     <div class="project-overview-right">
                        <?php if(count($list_approve_status) > 0){ ?>
                          
                         <div class="row">
                           <div class="col-md-12 project-overview-expenses-finance">
                            <?php 
                              $this->load->model('staff_model');
                              $enter_charge_code = 0;
                            foreach ($list_approve_status as $value) {
                              $value['staffid'] = explode(', ',$value['staffid']);
                              if($value['action'] == 'sign'){
                             ?>
                             <div class="col-md-4 apr_div">
                                 <p class="text-uppercase text-muted no-mtop bold">
                                  <?php
                                  $staff_name = '';
                                  $st = _l('status_0');
                                  $color = 'warning';
                                  foreach ($value['staffid'] as $key => $val) {
                                    if($staff_name != '')
                                    {
                                      $staff_name .= ' or ';
                                    }
                                    $staff_name .= $this->staff_model->get($val)->firstname;
                                  }
                                  echo html_entity_decode($staff_name); 
                                  ?></p>
                                 <?php if($value['approve'] == 2){ 
                                  ?>
                                  <img src="<?php echo site_url(PURCHASE_PATH.'pur_estimate/signature/'.$estimate->id.'/signature_'.$value['id'].'.png'); ?>" class="img_style">
                                   <br><br>
                                 <p class="bold text-center text-success"><?php echo _l('signed').' '._dt($value['date']); ?></p> 
                                 <?php } ?> 
                                   
                            </div>
                            <?php }else{ ?>
                            <div class="col-md-4 apr_div" >
                                 <p class="text-uppercase text-muted no-mtop bold">
                                  <?php
                                  $staff_name = '';
                                  foreach ($value['staffid'] as $key => $val) {
                                    if($staff_name != '')
                                    {
                                      $staff_name .= ' or ';
                                    }
                                    $staff_name .= $this->staff_model->get($val)->firstname;
                                  }
                                  echo html_entity_decode($staff_name); 
                                  ?></p>
                                 <?php if($value['approve'] == 2){ 
                                  ?>
                                  <img src="<?php echo site_url(PURCHASE_PATH.'approval/approved.png'); ?>" class="img_style">
                                 <?php }elseif($value['approve'] == 3){ ?>
                                    <img src="<?php echo site_url(PURCHASE_PATH.'approval/rejected.png'); ?>" class="img_style">
                                <?php } ?> 
                                <br><br>  
                                <p class="bold text-center text-<?php if($value['approve'] == 2){ echo 'success'; }elseif($value['approve'] == 3){ echo 'danger'; } ?>"><?php echo _dt($value['date']); ?></p> 
                            </div>
                            <?php }
                            } ?>
                           </div>
                        </div>
                        
                        <?php } ?>
                        </div>

                     <?php if($estimate->pur_request->id != 0){ ?>
                     <div class="col-md-12">
                        <h4 class="font-medium mbot15"><?php echo _l('related_to_pur_request',array(
                           _l('estimate_lowercase'),
                           _l('pur_request'),
                           '<a href="'.admin_url('purchase/view_pur_request/'.$estimate->pur_request->id).'" target="_blank">' . $estimate->pur_request->pur_rq_name . '</a>',
                           )); ?></h4>
                     </div>
                     <?php } ?>
                     <div class="col-md-6 col-sm-6">
                        <h4 class="bold">
                           <?php
                              $tags = get_tags_in($estimate->id,'estimate');
                              if(count($tags) > 0){
                                echo '<i class="fa fa-tag" aria-hidden="true" data-toggle="tooltip" data-title="'.html_escape(implode(', ',$tags)).'"></i>';
                              }
                              ?>
                           <a href="<?php echo admin_url('purchase/estimate/'.$estimate->id); ?>">
                           <span id="estimate-number">
                           <?php echo format_pur_estimate_number($estimate->id); ?>
                           </span>
                           </a>
                        </h4>
                        <address>
                           <?php echo format_organization_info(); ?>
                        </address>
                     </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table items items-preview estimate-items-preview" data-type="estimate">
                              <thead>
                                 <tr>
                                    <th align="center">#</th>
                                    <th class="description" width="50%" align="left"><?php echo _l('items'); ?></th>
                                    <th align="right"><?php echo _l('quantity'); ?></th>
                                    <th align="right"><?php echo _l('unit_price'); ?></th>
                                    <th align="right"><?php echo _l('into_money'); ?></th>
                                    <th align="right"><?php echo _l('tax'); ?></th>
                                    <th align="right"><?php echo _l('total'); ?></th>
                                    <th align="right"><?php echo _l('discount(%)'); ?></th>
                                    <th align="right"><?php echo _l('discount(money)'); ?></th>
                                    <th align="right"><?php echo _l('into_money'); ?></th>
                                 </tr>
                              </thead>
                              <tbody class="ui-sortable">

                                 <?php if(count($estimate_detail) > 0){
                                    $count = 1;
                                    $t_mn = 0;
                                 foreach($estimate_detail as $es) { ?>
                                 <tr nobr="true" class="sortable">
                                    <td class="dragger item_no ui-sortable-handle" align="center"><?php echo html_entity_decode($count); ?></td>
                                    <td class="description" align="left;"><span ><strong><?php 
                                    $item = get_item_hp($es['item_code']); 
                                    if(isset($item)){
                                       echo html_entity_decode($item->commodity_code.' - '.$item->description);
                                    }else{
                                       echo '';
                                    }
                                    ?></strong></td>
                                    <td align="right"  width="12%"><?php echo html_entity_decode($es['quantity']); ?></td>
                                    <td align="right"><?php echo app_format_money($es['unit_price'],''); ?></td>
                                    <td align="right"><?php echo app_format_money($es['into_money'],''); ?></td>
                                    <td align="right"><?php echo app_format_money(($es['total'] - $es['into_money']),''); ?></td>
                                    <td class="amount" align="right"><?php echo app_format_money($es['total'],''); ?></td>
                                    <td class="amount" width="12%" align="right"><?php echo html_entity_decode($es['discount_%'].'%'); ?></td>
                                    <td class="amount" align="right"><?php echo app_format_money($es['discount_money'],''); ?></td>
                                    <td class="amount" align="right"><?php echo app_format_money($es['total_money'],''); ?></td>
                                 </tr>
                              <?php $t_mn += $es['total_money'];
                              $count++; } } ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="col-md-5 col-md-offset-7">
                        <table class="table text-right">
                           <tbody>
                              <?php if($estimate->discount_total > 0){ ?>
                              <tr id="subtotal">
                                 <td><span class="bold"><?php echo _l('subtotal'); ?></span>
                                 </td>
                                 <td class="subtotal">
                                    <?php echo app_format_money($t_mn,''); ?>
                                 </td>
                              </tr>
                              <tr id="subtotal">
                                 <td><span class="bold"><?php echo _l('discount(%)').'(%)'; ?></span>
                                 </td>
                                 <td class="subtotal">
                                    <?php echo app_format_money($estimate->discount_percent,'').' %'; ?>
                                 </td>
                              </tr>
                              <tr id="subtotal">
                                 <td><span class="bold"><?php echo _l('discount(money)'); ?></span>
                                 </td>
                                 <td class="subtotal">
                                    <?php echo app_format_money($estimate->discount_total, ''); ?>
                                 </td>
                              </tr>
                              <?php } ?>
                              <tr id="subtotal">
                                 <td><span class="bold"><?php echo _l('total'); ?></span>
                                 </td>
                                 <td class="subtotal">
                                    <?php echo app_format_money($estimate->total, ''); ?>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>                                          
                     <?php if($estimate->terms != ''){ ?>
                     <div class="col-md-12 mtop15">
                        <p class="bold text-muted"><?php echo _l('terms_and_conditions'); ?></p>
                        <p><?php echo html_entity_decode($estimate->terms); ?></p>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="add_action" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         
        <div class="modal-body">
         <p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>
            <div class="signature-pad--body">
              <canvas id="signature" height="130" width="550"></canvas>
            </div>
            <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput">
            <div class="dispay-block">
              <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo _l('clear'); ?></button>
            
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('cancel'); ?></button>
           <button onclick="sign_request(<?php echo html_entity_decode($estimate->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
          </div>

      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php require 'modules/purchase/assets/js/estimate_preview_template_js.php';?>


