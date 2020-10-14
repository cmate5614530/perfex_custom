<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--
Module Name: Perfex CRM Custom JS Module
Description: The easiest way to implement own JavaScript 
Version: 1.0.0
Requires at least: 2.3.4
-->
<div class="row mbot10 padding">
<div class="form-group"> 
	<button type="button" class="btn btn-success pull-right btnCustomJsInfo" data-toggle="collapse" data-target="#info">INFO</button>
	<h5>Custom JavaScript Admin Area</h5>
	<hr>
	<div id="info" class="collapse">
		<h3 class="text-danger">Important to read before applying any scripts.</h3>
		<h4>All scripts inserted here will be included in the header after reloading the browser. And they will be all rendered before admin login or before customer login. Note that when you delete any script from here it will be permanently deleted.<br><br>
			We recommend that you don't insert here any simple text or unknown characters, this area is only used for pre admin and pre customer load scripts in header. Because if you add any letters or code that doesn't starts and ends with <?php echo htmlspecialchars('<script></script>'); ?> it may cause application to crush. Comments are allowed for inserting before script tags.</h4>
			<p><strong>Example:</strong> <span class="text-info"><br><?php echo htmlspecialchars('<!- Comment here -> (optional) '); ?> <br> <?php echo htmlspecialchars('<script> Your code here... </script>') ?></span></p>
			
		</div>
		<textarea
		name="textarea"
		spellcheck="false"
		class="pull-left custom_js_admin_scripts ays-ignore padding-10"
		id="custom_js_admin_scripts"><?php echo get_custom_js_admin_active_data(); ?></textarea>
		<button type="button" id="updateJsBtn" class="pull-left btn btn-info btn-block" >Update Admin Scripts</button>
	</div>
</div>
<div class="row padding">
	<div class="form-group"> 
		<h5 >Custom JavaScript Customers Area</h5>
		<hr>
		<textarea
		name="textarea"
		spellcheck="false"
		class="pull-left custom_js_customer_scripts ays-ignore padding-10"
		id="custom_js_customer_scripts"><?php echo get_custom_js_customer_active_data(); ?></textarea>
		<button type="button" id="customersUpdateJsBtn" class="pull-left btn btn-info btn-block" >Update Customer Scripts</button>
	</div>
</div>
<style scoped type="text/css" media="screen">
	textarea.custom_js_customer_scripts, textarea.custom_js_admin_scripts {
		font-family: monospace;
		border: 1px solid #e4dddd;
		width: 100%;
		margin-bottom: 16px;
		overflow-x: hidden;
		border-radius: 4px;
		overflow-y: auto;
		resize: none;
		background: #b7b2b214;
		height: 30vh;
	}
	.settings .btn-bottom-toolbar.text-right {
		display: none;
	}
	.btnCustomJsInfo { margin-top:-10px; }
</style>