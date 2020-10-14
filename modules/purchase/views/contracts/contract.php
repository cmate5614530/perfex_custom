<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">

			<?php if(isset($contract)){
                    echo form_hidden('contractid',$contract->id);
                  }
			echo form_open_multipart($this->uri->uri_string(),array('id'=>'contract-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-5 left-column">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
          
          <div class="row">
             <div class="col-md-12">
                <p class="bold p_style" ><?php echo html_entity_decode($title); ?></p>
                <hr class="hr_style" />
                <div class="row">
                <div class="col-md-6">
                  <?php $contract_number = (isset($contract) ? $contract->contract_number : '');
                  echo render_input('contract_number','contract_number',$contract_number); ?>
        
                </div>
                <div class="col-md-6">
                  <?php $contract_name = (isset($contract) ? $contract->contract_name : '');
                  echo render_input('contract_name','contract_name',$contract_name); ?>
        
                </div>
                <div class="col-md-12">
                  <label for="pur_order"><?php echo _l('pur_order'); ?></label>
                  <select name="pur_order" id="pur_order" class="selectpicker" onchange="view_pur_order(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    <option value=""></option>
                    <?php foreach($pur_orders as $or){ ?>
                      <option value="<?php echo html_entity_decode($or['id']); ?>" <?php if(isset($contract) && $contract->pur_order == $or['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($or['pur_order_number']).' - '.html_entity_decode($or['pur_order_name']); ?></option>
                    <?php } ?>
                  </select>
                  <br><br>
                </div>
                <div class="col-md-6">
                  <label for="vendor"><?php echo _l('vendor'); ?></label>
                  <select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    <option value=""></option>
                    <?php foreach($vendors as $or){ ?>
                      <option value="<?php echo html_entity_decode($or['userid']); ?>" <?php if(isset($contract) && $contract->vendor == $or['userid']){ echo 'selected'; }else{ if(isset($ven) && $ven == $or['userid']){ echo 'selected'; } } ?>><?php echo html_entity_decode($or['company']); ?></option>
                    <?php } ?>
                  </select>
                  
                </div>
                <div class="col-md-6">
                  <?php $contract_value = (isset($contract) ? app_format_money($contract->contract_value,'') : '');
                  echo render_input('contract_value','contract_value',$contract_value); ?>
        
                </div>
                <div class="col-md-6">
                  <?php $start_date = (isset($contract) ? _d($contract->start_date) : _d(date('Y-m-d')));
                  echo render_date_input('start_date','start_date',$start_date); ?>
                </div>

                <div class="col-md-6">
                  <?php $end_date = (isset($contract) ? _d($contract->end_date) : '');
                   echo render_date_input('end_date','end_date',$end_date); ?>
                </div>
                <div class="col-md-6">
                             <?php
                            $selected = '';
                            foreach($staff as $member){
                             if(isset($contract)){
                               if($contract->buyer == $member['staffid']) {
                                 $selected = $member['staffid'];
                               }
                             }
                            }
                            echo render_select('buyer',$staff,array('staffid',array('firstname','lastname')),'buyer',$selected);
                            ?>
                </div>
                <div class="col-md-6">
                  <?php $time_payment = (isset($contract) ? _d($contract->time_payment) : '');
                   echo render_date_input('time_payment','time_for_payment',$time_payment); ?>
                </div>

                <div class="col-md-6">
                  <label for="signed_status"><?php echo _l('signed_status'); ?></label>
                  <select name="signed_status" readonly="true" id="signed_status" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                    <option value=""></option>
                    <option value="not_signed" <?php if(isset($contract) && $contract->signed_status == 'not_signed'){ echo 'selected'; }elseif(!isset($contract)){ echo 'selected'; } ?>><?php echo _l('not_signed'); ?></option>
                    <option value="signed"  <?php if(isset($contract) && $contract->signed_status == 'signed'){ echo 'selected'; } ?>><?php echo _l('signed'); ?></option>
                  </select>
                  
                </div>
                <div class="col-md-6">
                  <?php $signed_date = (isset($contract) ? _d($contract->signed_date) : '');
                   echo render_date_input('signed_date','signed_date',$signed_date); ?>
        
                </div>

                <div class="col-md-12">
                  <div class="attachments">
                    <div class="attachment">
                      <div class="mbot15">
                        <div class="form-group">
                          <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
                          <div class="input-group">
                            <input type="file" extension="<?php echo str_replace('.','',get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                            <span class="input-group-btn">
                              <button class="btn btn-success add_more_attachments p8-half" data-max="5" type="button"><i class="fa fa-plus"></i></button>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

             </div>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-12 mtop15">
             <div class="panel-body bottom-transaction">
                <?php $value = (isset($contract) ? $contract->note : ''); ?>
                <?php echo render_textarea('note','decription',$value,array('rows'=>8),array(),'mtop15'); ?>
               
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

			</div>
			<?php echo form_close(); ?>
			<?php if(isset($contract)) { ?>
        <div class="col-md-7 right-column">
          <div class="panel_s">
              <div class="panel-body">
                <div class="horizontal-scrollable-tabs preview-tabs-top">
                     <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                     <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                     <div class="horizontal-tabs">
                        <ul class="nav nav-tabs tabs-in-body-no-margin contract-tab nav-tabs-horizontal mbot15" role="tablist">
                           <li role="presentation" class="<?php if(!$this->input->get('tab') || $this->input->get('tab') == 'tab_content'){echo 'active';} ?>">
                              <a href="#tab_content" aria-controls="tab_content" role="tab" data-toggle="tab">
                              <?php echo _l('contract_content'); ?>
                              </a>
                           </li>

                           <li role="presentation" >
                              <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
                              <?php echo _l('attachments'); ?>
                              </a>
                           </li>

                           <li role="presentation" class="tab-separator toggle_view">
                              <a href="#" onclick="contract_full_view(); return false;" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>">
                              <i class="fa fa-expand"></i></a>
                           </li>
                        </ul>
                     </div>
                  </div>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab') || $this->input->get('tab') == 'tab_content'){echo ' active';} ?>" id="tab_content">
                    <div class="col-md-12 text-right _buttons">
                      <div class="btn-group">
                         <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                         <ul class="dropdown-menu dropdown-menu-right">
                            <li class="hidden-xs"><a href="<?php echo admin_url('purchase/pdf_contract/'.$contract->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                            <li class="hidden-xs"><a href="<?php echo admin_url('purchase/pdf_contract/'.$contract->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                            <li><a href="<?php echo admin_url('purchase/pdf_contract/'.$contract->id); ?>"><?php echo _l('download'); ?></a></li>
                            <li>
                               <a href="<?php echo admin_url('purchase/pdf_contract/'.$contract->id.'?print=true'); ?>" target="_blank">
                               <?php echo _l('print'); ?>
                               </a>
                            </li>
                         </ul>
                      </div>
                      <?php if($contract->signed_status == 'not_signed') { ?>
                      <button onclick="accept_action();" class="btn btn-success pull-right action-button mleft5"><?php echo _l('e_signature_sign'); ?></button> 
                      <?php }elseif($contract->signed_status == 'signed'){ ?>
                        <span class="btn success-bg content-view-status contract-html-is-signed" ><?php echo _l('signed'); ?></span>
                      <?php } ?>
                    </div>
                    <hr class="hr-panel-heading" />
                    <div class="editable tc-content div_content">
                       <?php
                          if(empty($contract->content)){
                           echo hooks()->apply_filters('new_contract_default_content', '<span class="text-danger text-uppercase mtop15 editor-add-content-notice"> ' . _l('click_to_add_content') . '</span>');
                          } else {
                           echo html_entity_decode($contract->content);
                          }
                          ?>
                    </div>
                    <?php if($contract->signed_status == 'signed') { ?>
                        <div class="row mtop25">
                           <div class="col-md-6 col-md-offset-6 text-right">
                              <p class="bold"><?php echo _l('document_signature_text'); ?>
                                
                              </p>
                              <div class="pull-right">
                                 <img src="<?php echo site_url(PURCHASE_PATH.'contract_sign/'.$contract->id.'/signature.png'); ?>" class="img-responsive" alt="">
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                  </div>

                  <div role="tabpanel" class="tab-pane"  id="attachments">
                    <div class="col-md-12" id="ic_pv_file">
                      <?php
                         
                          $file_html = '';
                         
                          if(count($attachments) > 0){
                              $file_html .= '<hr />
                                      <p class="bold text-muted">'._l('attachments').'</p>';
                              foreach ($attachments as $f) {
                                  $href_url = site_url(PURCHASE_PATH.'pur_contract/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                                                  if(!empty($f['external'])){
                                                    $href_url = $f['external_link'];
                                                  }
                                 $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
                                <div class="col-md-8">
                                   <a name="preview-ic-btn" onclick="preview_ic_btn(this); return false;" rel_id = "'. $f['rel_id']. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left mright5" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
                                   <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
                                   <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
                                   <br />
                                   <small class="text-muted">'.$f['filetype'].'</small>
                                </div>
                                <div class="col-md-4 text-right">';
                                  if($f['staffid'] == get_staff_user_id() || is_admin()){
                                  $file_html .= '<a href="#" class="text-danger" onclick="delete_ic_attachment('. $f['id'].'); return false;"><i class="fa fa-times"></i></a>';
                                  } 
                                 $file_html .= '</div></div>';
                              }
                              $file_html .= '<hr />';
                              echo html_entity_decode($file_html);
                          }
                       ?>
                    </div>
                  
                    <div id="ic_file_data"></div>
                  </div>

              </div>
          </div>
        </div>
        <div class="modal fade" id="add_action" tabindex="-1" role="dialog">
           <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-body">
                   <?php
                            $selected = '';
                            foreach($staff as $member){
                             if(isset($contract)){
                               if(get_staff_user_id() == $member['staffid']) {
                                 $selected = $member['staffid'];
                               }
                             }
                            }
                            echo render_select('signer',$staff,array('staffid',array('firstname','lastname')),'signer',$selected,array('disabled'=> true));
                            ?>
                   <?php echo render_input('email','email',get_staff(get_staff_user_id())->email,'text',array('disabled'=> true)); ?>
                 <p class="bold" id="signatureLabel"><?php echo _l('signature'); ?></p>
                    <div class="signature-pad--body">
                      <canvas id="signature" height="160" width="550"></canvas>
                    </div>
                    <input type="text" class="ip_style" tabindex="-1" name="signature" id="signatureInput">
                    <div class="dispay-block">
                      <button type="button" class="btn btn-default btn-xs clear" tabindex="-1" onclick="signature_clear();"><?php echo _l('clear'); ?></button>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('cancel'); ?></button>
                   <button onclick="sign_request(<?php echo html_entity_decode($contract->id); ?>);" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success"><?php echo _l('e_signature_sign'); ?></button>
                  </div>
              </div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      <?php } ?>
		</div>
	</div>
</div>
</div>

<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/contract_js.php';?>
