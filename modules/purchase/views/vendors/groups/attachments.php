<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="no-mtop bold"><?php echo _l('customer_attachments'); ?>


</h4>
<hr />
<?php if(isset($client)){ ?>
    <?php echo form_open_multipart(admin_url('purchase/upload_attachment/'.$client->userid),array('class'=>'dropzone','id'=>'client-attachments-upload')); ?>
    <input type="file" name="file" multiple />
    <?php echo form_close(); ?>
    <div class="text-right mtop15">
        <button class="gpicker" data-on-pick="customerGoogleDriveSave">
            <i class="fa fa-google" aria-hidden="true"></i>
            <?php echo _l('choose_from_google_drive'); ?>
        </button>
        <div id="dropbox-chooser"></div>
    </div>
    <div class="col-md-12" id="ic_pv_file">
                        <?php
                            $file_html = '';
                            if(count($attachments) > 0){
                                $file_html .= '<hr />
                                        <p class="bold text-muted">'._l('customer_attachments').'</p>';
                                foreach ($attachments as $f) {
                                    $href_url = site_url(PURCHASE_PATH.'pur_vendor/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
<?php } ?>
<div id="ic_file_data"></div>
