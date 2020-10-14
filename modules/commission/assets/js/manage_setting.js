(function($) {
  	"use strict";

	appValidateForm($('#hierarchy_setting'),{
		salesman: 'required',
		coordinator: 'required',
		percent: 'required'
	 });

	appValidateForm($('#salesadmin_customer_group_setting'),{
		customer_group: 'required',
		salesadmin: 'required'
	 });

})(jQuery);

function new_hierarchy(){
	"use strict";

	$('#hierarchy_model').modal('show');
	$('.edit-title').addClass('hide');
	$('.add-title').removeClass('hide');

	$('#hierarchy_id').html('');

	$('#hierarchy_model select[name="salesman"]').val('').change();
	$('#hierarchy_model select[name="coordinator"]').val('').change();
	$('#hierarchy_model input[name="percent"]').val('');
}

function edit_hierarchy(invoker,id){
  	"use strict";
  
	$('#hierarchy_model').modal('show');
	$('.edit-title').removeClass('hide');
	$('.add-title').addClass('hide');

	$('#hierarchy_id').html('');
	$('#hierarchy_id').append(hidden_input('id',id));

	$('#hierarchy_model select[name="salesman"]').val($(invoker).data('salesman')).change();
	$('#hierarchy_model select[name="coordinator"]').val($(invoker).data('coordinator')).change();
	$('#hierarchy_model input[name="percent"]').val($(invoker).data('percent'));
	
}

function new_salesadmin_customer_group(){
	"use strict";

	$('#salesadmin_customer_group_modal').modal('show');
	$('.edit-title').addClass('hide');
	$('.add-title').removeClass('hide');

	$('#salesadmin_group_id').html('');

	$('#salesadmin_customer_group_modal select[name="customer_group"]').val('').change();
	$('#salesadmin_customer_group_modal select[name="salesadmin"]').val('').change();
}

function edit_salesadmin_customer_group (invoker,id){
  	"use strict";
  
	$('#salesadmin_customer_group_modal').modal('show');
	$('.edit-title').removeClass('hide');
	$('.add-title').addClass('hide');

	$('#salesadmin_group_id').html('');
	$('#salesadmin_group_id').append(hidden_input('id',id));

	$('#salesadmin_customer_group_modal select[name="customer_group"]').val($(invoker).data('customer_group')).change();
	$('#salesadmin_customer_group_modal select[name="salesadmin"]').val($(invoker).data('salesadmin')).change();
	
}