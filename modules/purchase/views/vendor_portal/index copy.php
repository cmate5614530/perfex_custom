<?php defined('BASEPATH') or exit('No direct script access allowed');
echo theme_head_view();
get_template_part_pur($navigationEnabled ? 'navigation' : '');
?>
<div id="wrapper">
   <div id="content">
      <div class="container">
         <div class="row">
            <?php get_template_part_pur('alerts'); ?>
         </div>
      </div>
  
      <div class="container">
         <?php hooks()->do_action('customers_content_container_start'); ?>
         <div class="row">
           
            <?php echo theme_template_view(); ?>
         </div>
      </div>
   </div>
   <?php
   echo theme_footer_view();
   ?>
</div>
<?php
/* Always have app_customers_footer() just before the closing </body>  */
app_customers_footer();
   /**
   * Check for any alerts stored in session
   */
   app_js_alerts();

   //9.4 AG: for test
   
   ?>
   <!-- select 2 js -->
<script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script>;
   <!-- custom js -->
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/acer-custom.css" rel="stylesheet">
   <!-- light box  -->
<script type="text/javascript" src="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.js'); ?> " > </script>
<script type="text/javascript" src="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.jquery.min.js'); ?> " > </script>
<script type="text/javascript" src="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.js'); ?> " > </script>
<!-- push notifications here  -->
<script type="text/javascript" id="pusher-js" src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script type="text/javascript">
   let url = '<?= site_url('assets/js/service-worker.js') ?>';
   async function getSW(){
      return navigator.serviceWorker.getRegistration(url);
   }
   async function registerSW() {
      return navigator.serviceWorker.register(url);
   }
   
   async function onNotifyMessage() {
        let notification = new Notification('New post alert!'
            //   , {
            //     body: 'body', // content for the alert
            //     icon: "https://pusher.com/static_logos/320x320.png" // optional image url
            //   }
              );
              console.log('notification', notification);
              // link to page on clicking the notification
              notification.onclick = () => {
                window.open('http://localhost/admin');
              };
            return;
      //const reg = await getSW();
      //console.log('reg', reg);
      /**** START iconNotification ****/
      // const title = 'New Travel Request';
      // const options = {
      //    icon: '<?= site_url('assets/images/notify-logo.png') ?>',
        
      // };
      // reg.showNotification(title, options);
      // reg.addEventListener('notificationclick', function (event) {
      //    event.notification.close();
      //    clients.openWindow("https://www.google.com");
      // });
      /**** END iconNotification ****/
   }
   $(function(){
         if (! ('Notification' in window)) {
            alert('Web Notification is not supported');
            return;
         }

         Notification.requestPermission( permission => {
            
            console.log('permission got', permission);
            if(permission == 'granted')
            {
               //registerSW();
               onNotifyMessage();
            }
            //   let notification = new Notification('New post alert!'
            // //   , {
            // //     body: 'body', // content for the alert
            // //     icon: "https://pusher.com/static_logos/320x320.png" // optional image url
            // //   }
            //   );
            //   console.log('notification', notification);
            //   // link to page on clicking the notification
            //   notification.onclick = () => {
            //     window.open(window.location.href);
            //   };
            });
         // Enable pusher logging - don't include this in production
         // Pusher.logToConsole = true;
         <?php 
            $pusher_options = hooks()->apply_filters('pusher_options', array(['disableStats'=>true]));
            if(!isset($pusher_options['cluster']) && get_option('pusher_cluster') != ''){
                  $pusher_options['cluster'] = get_option('pusher_cluster');
            }
         ?>
         var pusher_options = <?php echo json_encode($pusher_options); ?>;
         var pusher = new Pusher("<?php echo get_option('pusher_app_key'); ?>", pusher_options);
        // var channel = pusher.subscribe('notifications-channel-<?php echo get_client_user_id(); ?>');
         var channel = pusher.subscribe('notifications-channel-4');
         channel.bind('notification', function(data) {
            //fetch_notifications();
            console.log('notification : ', data);
            //show notification
            
            //show notification : end
         });
   });
</script>
</body>
</html>
