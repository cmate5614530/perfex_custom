<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo html_entity_decode($locale); ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php if (isset($title)){ echo html_entity_decode($title); } ?></title>
	<!-- vendor admin  css-->
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/builds/vendor-admin.css'); ?>" > 
	<!-- vendor admin  css: end-->


	<?php echo compile_theme_css(); ?>
	<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
		<?php app_vendor_head(); ?>


	<!-- head.php start -->
	<link rel="stylesheet" type="text/css"  id="items-css" href="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/style.css'); ?>" >
	<!-- select2 css -->
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/plugins/select2/css/select2.min.css'); ?>" >   
	  

	<!-- commodity list class -->
	<link rel="stylesheet" type="text/css"  id="items-css" href="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/commodity_list.css'); ?>" >
    
	<!-- light box css -->
    <link rel="stylesheet" type="text/css" href="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.css'); ?>" > 
    <link rel="stylesheet" type="text/css" href="<?php echo module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.css'); ?>" > 
	<!-- dropzone js -->
	<script type="text/javascript" src="<?php echo site_url('assets/plugins/dropzone/min/dropzone.min.js'); ?>" > </script>  
	<script type="text/javascript" src="<?php echo site_url('assets/js/main.js'); ?>" > </script>  

	
		
</head>
<body class="customers<?php if(is_mobile()){echo ' mobile';}?><?php if(isset($bodyclass)){echo ' ' . $bodyclass; } ?>" <?php if($isRTL == 'true'){ echo 'dir="rtl"';} ?>>
	<?php hooks()->do_action('customers_after_body_start'); ?>
