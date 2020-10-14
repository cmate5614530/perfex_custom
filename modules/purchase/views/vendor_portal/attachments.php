<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="panel_s">
      <div class="panel-body">
    <?php if(count($attachments) == 0){ ?>    
      <h4><?php echo _l('there_is_no_attachment'); ?></h4>
    <?php } ?>
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
                </div> ';
                  
                 $file_html .= '</div>';
              }
              $file_html .= '<hr />';
              echo html_entity_decode($file_html);
          }
       ?>
    </div>
</div>
</div></div>
<div id="ic_file_data"></div>