<script>
(function($) {
  "use strict";
   var discussion_id = '<?php echo html_entity_decode($file->id); ?>';
   var discussion_user_profile_image_url = '<?php echo html_entity_decode($discussion_user_profile_image_url); ?>';
   var current_user_is_admin = '<?php echo is_admin(); ?>';
   $('body').on('shown.bs.modal', '._project_file', function() {
     var content_height = ($('body').find('._project_file .modal-content').height() - 165);
     if($('iframe').length > 0){
       $('iframe').css('height',content_height);
     }
     if(!is_mobile()){
      $('.project_file_area,.project_file_discusssions_area').css('height',content_height);
    }
   });
   $('body').find('._project_file').modal({show:true, backdrop:'static', keyboard:false});
})(jQuery);
</script>