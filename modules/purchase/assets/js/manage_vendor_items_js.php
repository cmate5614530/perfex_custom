<script>
var fnServerParams;

(function($) {
	"use strict";

	fnServerParams = {
		"vendor_filter": '[name="vendor_filter"]',
    	"items_filter": '[name="items_filter"]',
    	"group_items_filter": '[name="group_items_filter"]'
	}

	init_vendor_items_table();

	$('select[name="vendor_filter"]').on('change', function() {
		init_vendor_items_table();

	});
	$('select[name="items_filter"]').on('change', function() {
		init_vendor_items_table();
	});
	$('select[name="group_items_filter"]').on('change', function() {
		init_vendor_items_table();
	});

})(jQuery);


	function init_vendor_items_table() {
	  "use strict";

	 if ($.fn.DataTable.isDataTable('.table-vendor-items')) {
	   $('.table-vendor-items').DataTable().destroy();
	 }
	 initDataTable('.table-vendor-items', admin_url + 'purchase/vendor_items_table', false, false, fnServerParams);
	}
</script>