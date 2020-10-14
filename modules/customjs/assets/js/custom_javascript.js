/*
	Takes care of admin  and customers scripts inserted and sends data to controllers
 */
$(function(){
	var timeout = 2000;
	$('#updateJsBtn').on('click', function() {
		var data = $('#custom_js_admin_scripts').val();
		var url = site_url + 'customjs/Custom_js_controller/action_admin';
		$.post(url, {data: data}).done(function(r) {
			if(r == 'success'){
				alert_float('success','Custom JS Admin Scripts updated successfully.');
				setTimeout(function(){
					window.location.reload();	
				},timeout);				
			} else {
				alert_float('danger','Something went wrong, check your console and network log');
			}
		});
	});
	$('#customersUpdateJsBtn').on('click', function() {
		var data = $('#custom_js_customer_scripts').val();
		var url = site_url + 'customjs/Custom_js_controller/action_customers';
		$.post(url, {data: data}).done((r) => {
			if(r == 'success'){
				alert_float('success','Custom JS Customer Scripts updated successfully.');
				setTimeout(function(){
					window.location.reload();	
				},timeout);	
			} else {
				alert_float('danger','Something went wrong, check your console and network log');
			}
		});
	});
});
