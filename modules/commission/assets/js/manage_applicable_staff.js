
var commission_table,
report_from_choose,
fnServerParams,
commission_chart,
statistics_cost_of_purchase_orders;
(function($) {
	"use strict";

	fnServerParams = {
		"commission_policy_type": '[name="commission_policy_type"]',
    	"staff_filter": '[name="staff_filter"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
    	"is_client": '[name="is_client"]',
	}

	init_applicable_staff_table();

	$('select[name="commission_policy_type"]').on('change', function() {
		init_applicable_staff_table();

	});
	$('select[name="staff_filter"]').on('change', function() {
		init_applicable_staff_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_applicable_staff_table();

	});

	$('input[name="to_date"]').on('change', function() {
		init_applicable_staff_table();

	});
})(jQuery);


function init_applicable_staff_table() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-applicable-staff')) {
   $('.table-applicable-staff').DataTable().destroy();
 }
 initDataTable('.table-applicable-staff', admin_url + 'commission/applicable_staff_table', false, false, fnServerParams);
}
