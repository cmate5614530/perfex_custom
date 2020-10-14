<div class="modal fade _project_file modal_index" tabindex="-1" role="dialog" data-toggle="modal">
   <div class="modal-dialog full-screen-modal dialog_withd" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" onclick="close_modal_preview(); return false;"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo html_entity_decode($file->file_name); ?></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12 border-right project_file_area">
                  <?php if(!empty($file->external) && $file->external == 'dropbox'){ ?>
                  <a href="<?php echo html_entity_decode($file->external_link); ?>" target="_blank" class="btn btn-info mbot20"><i class="fa fa-dropbox" aria-hidden="true"></i> <?php echo _l('open_in_dropbox'); ?></a><br />
                  <?php } ?>
                  <?php
                     $path = PURCHASE_MODULE_UPLOAD_FOLDER.'/pur_order/'.$file->rel_id.'/'.$file->file_name;
                     if(is_image($path)){ ?>
                  <img src="<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>" class="img img-responsive img_style">
                  <?php } else if(!empty($file->external) && !empty($file->thumbnail_link)){ ?>
                  <img src="<?php echo optimize_dropbox_thumbnail($file->thumbnail_link); ?>" class="img img-responsive">
                  <?php } else if(strpos($file->file_name,'.pdf') !== false && empty($file->external)){ ?>
                  <iframe src="<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>" height="100%" width="100%" frameborder="0"></iframe>
                  <?php } else if(strpos($file->file_name,'.xls') !== false && empty($file->external)){ ?>
                  <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>' width='100%' height='100%' frameborder='0'>
                  </iframe>
                  <?php } else if(strpos($file->file_name,'.xlsx') !== false && empty($file->external)){ ?>
                  <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>' width='100%' height='100%' frameborder='0'>
                  </iframe>
                  <?php } else if(strpos($file->file_name,'.doc') !== false && empty($file->external)){ ?>
                  <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>' width='100%' height='100%' frameborder='0'>
                  </iframe>
                  <?php } else if(strpos($file->file_name,'.docx') !== false && empty($file->external)){ ?>
                  <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(PURCHASE_PATH.'pur_order/'.$file->rel_id.'/'.$file->file_name); ?>' width='100%' height='100%' frameborder='0'>
                  </iframe>
                  <?php } else if(is_html5_video($path)) { ?>
                  <video width="100%" height="100%" src="<?php echo site_url('download/preview_video?path='.protected_file_url_by_path($path).'&type='.$file->filetype); ?>" controls>
                     Your browser does not support the video tag.
                  </video>
                  <?php } else if(is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
                     echo html_entity_decode($previewMarkdown);
                  } else {
                     
                     echo '<p class="text-muted">'._l('no_preview_available_for_file').'</p>';
                     } ?>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="close_modal_preview(); return false;"><?php echo _l('close'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php $discussion_lang = get_project_discussions_language_array(); ?>
<?php require 'modules/purchase/assets/js/_file_js.php';?>
