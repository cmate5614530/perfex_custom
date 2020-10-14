<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('_attachment_sale_id',$estimate->id); ?>
<?php echo form_hidden('_attachment_sale_type','estimate'); ?>
<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
         <?php if($estimate->approve_status == 1){ ?>
           <div class="ribbon info span_style"><span><?php echo _l('not_yet_approve'); ?></span></div>
       <?php }elseif($estimate->approve_status == 2){ ?>
         <div class="ribbon success"><span><?php echo _l('approved'); ?></span></div>
       <?php }elseif($estimate->approve_status == 3){ ?>  
         <div class="ribbon warning"><span><?php echo _l('reject'); ?></span></div>
       <?php }elseif ($estimate->approve_status == 4) { ?>
         <div class="ribbon danger"><span><?php echo _l('cancelled'); ?></span></div>
      <?php  } ?>
         <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#tab_estimate" aria-controls="tab_estimate" role="tab" data-toggle="tab">
                     <?php echo _l('pur_order'); ?>
                     </a>
                  </li>
                  <li role="presentation">
                     <a href="#payment_record" aria-controls="payment_record" role="tab" data-toggle="tab">
                     <?php echo _l('payment_record'); ?>
                     </a>
                  </li>   
                  <li role="presentation">
                     <a href="#tab_reminders" onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?php echo html_entity_decode($estimate->id) ;?> + '/' + 'purchase_order', undefined, undefined, undefined,[1,'asc']); return false;" aria-controls="tab_reminders" role="tab" data-toggle="tab">
                     <?php echo _l('estimate_reminders'); ?>
                     <?php
                        $total_reminders = total_rows(db_prefix().'reminders',
                          array(
                           'isnotified'=>0,
                           'staff'=>get_staff_user_id(),
                           'rel_type'=>'purchase_order',
                           'rel_id'=>$estimate->id
                           )
                          );
                        if($total_reminders > 0){
                          echo '<span class="badge">'.$total_reminders.'</span>';
                        }
                        ?>
                     </a>
                  </li>
                     <?php
                     $customer_custom_fields = false;
                     if(total_rows(db_prefix().'customfields',array('fieldto'=>'pur_order','active'=>1)) > 0 ){
                          $customer_custom_fields = true;
                      ?>
                  <li role="presentation" >
                     <a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">
                     <?php echo _l( 'custom_fields'); ?>
                     </a>
                  </li>
                  <?php } ?>
                  <li role="presentation">
                     <a href="#tab_tasks" onclick="init_rel_tasks_table(<?php echo html_entity_decode($estimate->id); ?>,'pur_order'); return false;" aria-controls="tab_tasks" role="tab" data-toggle="tab">
                     <?php echo _l('tasks'); ?>
                     </a>
                  </li>
                  <li role="presentation" class="tab-separator">
                     <a href="#tab_notes" onclick="get_sales_notes(<?php echo html_entity_decode($estimate->id); ?>,'purchase'); return false" aria-controls="tab_notes" role="tab" data-toggle="tab">
                     <?php echo _l('estimate_notes'); ?>
                     <span class="notes-total">
                        <?php $totalNotes       = total_rows(db_prefix().'notes', ['rel_id' => $estimate->id, 'rel_type' => 'purchase_order']);
                        if($totalNotes > 0){ ?>
                           <span class="badge"><?php echo ($totalNotes); ?></span>
                        <?php } ?>
                     </span>
                     </a>
                  </li>
                  <li role="presentation" class="tab-separator">
                     <a href="#attachment" aria-controls="attachment" role="tab" data-toggle="tab">
                     <?php echo _l('attachment'); ?>
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
              <p class="bold p_mar"><?php echo _l('vendor').': '?><a href="<?php echo admin_url('purchase/vendor/'.$estimate->vendor); ?>"><?php echo get_vendor_company_name($estimate->vendor); ?></a></p>

              <p class="bold p_mar"><?php echo _l('clients').': '?></p>
               <?php $clients_ids = explode(',', $estimate->clients);
                  foreach ($clients_ids as $ids) {
                ?>
                  <a href="<?php echo admin_url('clients/client/'.$ids); ?>"><span class="label label-tag"><?php echo get_company_name($ids); ?></span></a>
               <?php } ?>
              
            </div>
            <div class="col-md-8">
               <div class="btn-group pull-right">
                  <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                  <ul class="dropdown-menu dropdown-menu-right">
                     <li class="hidden-xs"><a href="<?php echo admin_url('purchase/purorder_pdf/'.$estimate->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                     <li class="hidden-xs"><a href="<?php echo admin_url('purchase/purorder_pdf/'.$estimate->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                     <li><a href="<?php echo admin_url('purchase/purorder_pdf/'.$estimate->id); ?>"><?php echo _l('download'); ?></a></li>
                     <li>
                        <a href="<?php echo admin_url('purchase/purorder_pdf/'.$estimate->id.'?print=true'); ?>" target="_blank">
                        <?php echo _l('print'); ?>
                        </a>
                     </li>
                  </ul>
               </div>
               <?php if($estimate->approve_status != 2){ ?>
                  <div class="pull-right _buttons mright5">
                     <?php if(has_permission('purchase','','edit')){ ?>
                     <a href="<?php echo admin_url('purchase/pur_order/'.$estimate->id); ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('edit_pur_order_tooltip'); ?>" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                     <?php } ?>

                  </div>
               <?php } ?>

                  
               <?php if($estimate->approve_status != 2) {?>
               <select name="status" id="status" class="selectpicker pull-right mright10" onchange="change_status_pur_order(this,<?php echo ($estimate->id); ?>); return false;" data-live-search="true" data-width="35%" data-none-selected-text="<?php echo _l('change_status_to'); ?>">
                 <option value=""></option>
                 <option value="1" class="<?php if($estimate->approve_status == 1) { echo 'hide';}?>"><?php echo _l('not_yet_approve'); ?></option>
                 <option value="2" class="<?php if($estimate->approve_status == 2) { echo 'hide';}?>"><?php echo _l('approved'); ?></option>
                 <option value="3" class="<?php if($estimate->approve_status == 3) { echo 'hide';}?>"><?php echo _l('reject'); ?></option>
                 <option value="4" class="<?php if($estimate->approve_status == 4) { echo 'hide';}?>"><?php echo _l('cancel'); ?></option>
               </select>
               <?php } ?>
               
               <div class="col-md-12 padr_div_0">
                  <br>
                  <div class="pull-right _buttons mright5">
                     <a href="javascript:void(0)" onclick="copy_public_link(<?php echo html_entity_decode($estimate->id); ?>); return false;" class="btn btn-warning btn-with-tooltip" data-toggle="tooltip" title="<?php if($estimate->hash == ''){ echo _l('create_public_link'); }else{ echo _l('copy_public_link'); } ?>" data-placement="bottom"><i class="fa fa-clone "></i></a>
                  </div>
                  <div class="pull-right col-md-6">
                     <?php if($estimate->hash != '' && $estimate->hash != null){
                      echo render_input('link_public','', site_url('purchase/vendors_portal/pur_order/'.$estimate->id.'/'.$estimate->hash)); 
                     }else{
                         echo render_input('link_public','', ''); 
                     } ?>
                  </div>
               </div>
            </div>
         </div>

         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="tab-content">
            <?php if($customer_custom_fields) { ?>
              <div role="tabpanel" class="tab-pane" id="custom_fields">
                 <?php $rel_id=( isset($estimate) ? $estimate->id : false); ?>
                 <?php echo render_custom_fields( 'pur_order',$rel_id); ?>
              </div>
             <?php } ?>
            <div role="tabpanel" class="tab-pane" id="tab_tasks">
               <?php init_relation_tasks_table(array('data-new-rel-id'=>$estimate->id,'data-new-rel-type'=>'pur_order')); ?>
            </div>
            <div role="tabpanel" class="tab-pane ptop10 active" id="tab_estimate">
               <div id="estimate-preview">
                  <div class="row">
                     
                    
                     <?php if($estimate->estimate != 0){ ?>
                     <div class="col-md-12">
                        <h4 class="font-medium mbot15"><?php echo _l('',array(
                          '',
                           '',
                           '<a href="'.admin_url('purchase/quotations/'.$estimate->estimate).'" target="_blank">' . format_pur_estimate_number($estimate->id) . '</a>',
                           )); ?></h4>
                     </div>
                     <?php } ?>
                     <div class="col-md-6 col-sm-6">
                        <h4 class="bold">
                         
                           <a href="<?php echo admin_url('purchase/purchase_order/'.$estimate->id); ?>">
                           <span id="estimate-number">
                           <?php echo html_entity_decode($estimate->pur_order_number.' - '.$estimate->pur_order_name); ?>
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
                                    <th align="right"><?php echo _l('sub_total'); ?></th>
                                    <th align="right"><?php echo _l('discount(%)'); ?></th>
                                    <th align="right"><?php echo _l('discount(money)'); ?></th>
                                    <th align="right"><?php echo _l('total'); ?></th>
                                 </tr>
                              </thead>
                              <tbody class="ui-sortable">

                                 <?php if(count($estimate_detail) > 0){
                                    $count = 1;
                                    $t_mn = 0;
                                 foreach($estimate_detail as $es) { ?>
                                 <tr nobr="true" class="sortable">
                                    <td class="dragger item_no ui-sortable-handle" align="center"><?php echo html_entity_decode($count); ?></td>
                                    <td class="description" align="left;"><span><strong><?php 
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
                                    <td class="amount" width="12%" align="right"><?php echo ($es['discount_%'].'%'); ?></td>
                                    <td class="amount" align="right"><?php echo app_format_money($es['discount_money'],''); ?></td>
                                    <td class="amount" align="right"><?php echo app_format_money($es['total_money'],''); ?></td>
                                 </tr>
                              <?php 
                              $t_mn += $es['total_money'];
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

                     <?php if($estimate->vendornote != ''){ ?>
                     <div class="col-md-12 mtop15">
                        <p class="bold text-muted"><?php echo _l('estimate_note'); ?></p>
                        <p><?php echo html_entity_decode($estimate->vendornote); ?></p>
                     </div>
                     <?php } ?>
                                                            
                     <?php if($estimate->terms != ''){ ?>
                     <div class="col-md-12 mtop15">
                        <p class="bold text-muted"><?php echo _l('terms_and_conditions'); ?></p>
                        <p><?php echo html_entity_decode($estimate->terms); ?></p>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_reminders">
               <a href="#" data-toggle="modal" class="btn btn-info" data-target=".reminder-modal-purchase_order-<?php echo html_entity_decode($estimate->id); ?>"><i class="fa fa-bell-o"></i> <?php echo _l('estimate_set_reminder_title'); ?></a>
               <hr />
               <?php render_datatable(array( _l( 'reminder_description'), _l( 'reminder_date'), _l( 'reminder_staff'), _l( 'reminder_is_notified')), 'reminders'); ?>
               <?php $this->load->view('admin/includes/modals/reminder',array('id'=>$estimate->id,'name'=>'purchase_order','members'=>$members,'reminder_title'=>_l('estimate_set_reminder_title'))); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_notes">
               <?php echo form_open(admin_url('purchase/add_note/'.$estimate->id),array('id'=>'sales-notes','class'=>'estimate-notes-form')); ?>
               <?php echo render_textarea('description'); ?>
               <div class="text-right">
                  <button type="submit" class="btn btn-info mtop15 mbot15"><?php echo _l('estimate_add_note'); ?></button>
               </div>
               <?php echo form_close(); ?>
               <hr />
               <div class="panel_s mtop20 no-shadow" id="sales_notes_area">
               </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="attachment">
               <?php echo form_open_multipart(admin_url('purchase/purchase_order_attachment/'.$estimate->id),array('id'=>'partograph-attachments-upload')); ?>
                <?php echo render_input('file','file','','file'); ?>

                <div class="col-md-12 pad_div_0">

               </div>
               <div class="modal-footer bor_top_0" >
                   <button id="obgy_btn2" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
               </div>
                <?php echo form_close(); ?>
               
               <div class="col-md-12" id="purorder_pv_file">
                                    <?php
                                        $file_html = '';
                                        if(count($pur_order_attachments) > 0){
                                            $file_html .= '<hr />
                                                    <p class="bold text-muted">'._l('customer_attachments').'</p>';
                                            foreach ($pur_order_attachments as $f) {
                                                $href_url = site_url(PURCHASE_PATH.'pur_order/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                                                                if(!empty($f['external'])){
                                                                  $href_url = $f['external_link'];
                                                                }
                                               $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
                                              <div class="col-md-8">
                                                 <a name="preview-purorder-btn" onclick="preview_purorder_btn(this); return false;" rel_id = "'. $f['rel_id']. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 mright5 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
                                                 <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
                                                 <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
                                                 <br />
                                                 <small class="text-muted">'.$f['filetype'].'</small>
                                              </div>
                                              <div class="col-md-4 text-right">';
                                                if($f['staffid'] == get_staff_user_id() || is_admin()){
                                                $file_html .= '<a href="#" class="text-danger" onclick="delete_purorder_attachment('. $f['id'].'); return false;"><i class="fa fa-times"></i></a>';
                                                } 
                                               $file_html .= '</div></div>';
                                            }
                                            $file_html .= '<hr />';
                                            echo html_entity_decode($file_html);
                                        }
                                     ?>
                                  </div>

               <div id="purorder_file_data"></div>
            </div>

            <div role="tabpanel" class="tab-pane" id="payment_record">
               <div class="col-md-6 pad_div_0" >
               <h4 class="font-medium mbot15 bold text-success"><?php echo _l('payment_for_pur_order').' '.$estimate->pur_order_number; ?></h4>
               </div>
               <div class="col-md-6 padr_div_0">
               <?php if(purorder_left_to_pay($estimate->id) > 0){ ?>
               <a href="#" onclick="add_payment(<?php echo html_entity_decode($estimate->id); ?>); return false;" class="btn btn-success pull-right"><i class="fa fa-plus"></i><?php echo ' '._l('payment'); ?></a>
               <?php } ?>
               </div>
               <div class="clearfix"></div>
               <table class="table dt-table">
                   <thead>
                     <th><?php echo _l('payments_table_amount_heading'); ?></th>
                      <th><?php echo _l('payments_table_mode_heading'); ?></th>
                      <th><?php echo _l('payment_transaction_id'); ?></th>
                      
                      <th><?php echo _l('payments_table_date_heading'); ?></th>
                      <th><?php echo _l('options'); ?></th>
                   </thead>
                  <tbody>
                     <?php foreach($payment as $pay) { ?>
                        <tr>
                           <td><?php echo app_format_money($pay['amount'],''); ?></td>
                           <td><?php echo get_payment_mode_by_id($pay['paymentmode']); ?></td>
                           <td><?php echo html_entity_decode($pay['transactionid']); ?></td>
                           <td><?php echo _d($pay['date']); ?></td>
                           <td> <a href="<?php echo admin_url('purchase/delete_payment/'.$pay['id'].'/'.$estimate->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a></td>
                        </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>

         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="payment_record_pur" tabindex="-1" role="dialog">
    <div class="modal-dialog dialog_30" >
        <?php echo form_open(admin_url('purchase/add_payment/'.$estimate->id),array('id'=>'purorder-add_payment-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_payment'); ?></span>
                    <span class="add-title"><?php echo _l('new_payment'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                     <div id="additional"></div>
                     <?php echo render_input('amount','amount',app_format_money(purorder_left_to_pay($estimate->id),''),'text',array('data-type' => 'currency')); ?>
                        <?php echo render_date_input('date','payment_edit_date'); ?>
                        <?php echo render_select('paymentmode',$payment_modes,array('id','name'),'payment_mode'); ?>
                        
                        <?php echo render_input('transactionid','payment_transaction_id'); ?>
                        <?php echo render_textarea('note','note','',array('rows'=>7)); ?>

                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php require 'modules/purchase/assets/js/pur_order_preview_js.php';?>
