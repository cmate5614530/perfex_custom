(function($) {
	"use strict"; 
	initDataTable('.table-table_pur_request', admin_url+'purchase/table_pur_request');
	appValidateForm($('#send_rq-form'),{subject:'required',attachment:'required'});
})(jQuery);

 function send_request_quotation(id) {
 	"use strict"; 
 	$('#additional_rqquo').html('');
 	$('#additional_rqquo').append(hidden_input('pur_request_id',id));
 	$('#request_quotation').modal('show');
 }

