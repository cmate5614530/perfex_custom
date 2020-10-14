
var fnServerParams;
Dropzone.autoDiscover = false;
var expenseDropzone;
(function($) {
	"use strict";

	fnServerParams = {
		"conver_to_expense": '[name="conver_to_expense"]',
    	"staff_filter": '[name="staff_filter"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
	}

	init_applicable_staff_table();

	$('select[name="conver_to_expense"]').on('change', function() {
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

	if ($('#convert-expense-form').length > 0) {
          expenseDropzone = new Dropzone("#convert-expense-form", appCreateDropzoneOptions({
              autoProcessQueue: false,
              clickable: '#dropzoneDragArea',
              previewsContainer: '.dropzone-previews',
              addRemoveLinks: true,
              maxFiles: 1,
              success: function(file, response) {
                  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                      window.location.reload();
                  }
              }
        }));
    }

    appValidateForm($('#convert-expense-form'), {
          category: 'required',
          date: 'required',
          amount: 'required'
    }, projectExpenseSubmitHandler);

})(jQuery);


function init_applicable_staff_table() {
	"use strict";

	 if ($.fn.DataTable.isDataTable('.table-commission-receipt')) {
	   $('.table-commission-receipt').DataTable().destroy();
	 }
	 initDataTable('.table-commission-receipt', admin_url + 'commission/commission_receipt_table', false, false, fnServerParams, [0, 'desc']);
	}

function convert_expense(commission_receipt,total){
    "use strict";
    $('#convert_expense').modal('show');
    $('input[id="amount"]').val(total);
    $('#commission_receipt_additional').html('');
    $('#commission_receipt_additional').append(hidden_input('commission_receipt_id',commission_receipt));
}

function projectExpenseSubmitHandler(form) {
    "use strict";
      $.post(form.action, $(form).serialize()).done(function(response) {
          response = JSON.parse(response);
          if (response.expenseid) {
              if (typeof(expenseDropzone) !== 'undefined') {
                  if (expenseDropzone.getQueuedFiles().length > 0) {
                      expenseDropzone.options.url = admin_url + 'expenses/add_expense_attachment/' + response.expenseid;
                      expenseDropzone.processQueue();
                  } else {
                      window.location.assign(response.url);
                  }
              } else {
                  window.location.assign(response.url);
              }
          } else {
              window.location.assign(response.url);
          }
      });
      return false;
}
