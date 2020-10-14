
var commission_table, 
report_from_choose, 
fnServerParams, 
commission_chart, 
statistics_cost_of_purchase_orders;
(function($) {
	"use strict";
	
	fnServerParams = {
		"commission_policy_type": '[name="commission_policy_type"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
	}

	init_commission_policy_table();

	$('select[name="commission_policy_type"]').on('change', function() {
		init_commission_policy_table();

	});
	$('select[name="staff_filter"]').on('change', function() {
		init_commission_policy_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_commission_policy_table();

	});

	$('input[name="to_date"]').on('change', function() {
		init_commission_policy_table();

	});

	$('input[name="recalculate_the_old_invoice"]').on('change', function() {
		var data = {};
		data.recalculate_the_old_invoice = $('#recalculate_the_old_invoice').is(':checked');
		$.post(admin_url + 'commission/recalculate_invoice_change', data).done(function(response) {
     		response = JSON.parse(response);

     		var html = '';
	      	$.each(response, function() {
	          	html += '<option value="'+ this.id +'">'+ this.name +'</option>';
	       	});
	      	$('select[name="invoice[]"]').html(html);
	      	$('select[name="invoice[]"]').selectpicker('refresh');
		});
	});
	
})(jQuery);


function init_commission_policy_table() {
  "use strict";
  
 if ($.fn.DataTable.isDataTable('.table-commission-policy')) {
   $('.table-commission-policy').DataTable().destroy();
 }
 initDataTable('.table-commission-policy', admin_url + 'commission/commission_policy_table', false, false, fnServerParams);
}

function recalculate_modal(){
  "use strict";
  $('#recalculate_modal').modal('show');
}
