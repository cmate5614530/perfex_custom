<script>
	var list_commission;
	$(function(){
		"use strict";

		appValidateForm($('form'),{'list_commission[]':'required', amount:'required', date:'required', paymentmode: 'required'});

		list_commission = <?php echo html_entity_decode($list_commission_json); ?>;
		$('select[name="list_commission[]"]').on('change', function() {
		  	var amount = 0;
		  	$.each($(this).val(), function( index, value ) {
			  amount = amount + parseInt(list_commission[value]);
			});
			$('input[id="amount"]').val(amount);
	  });
	});
</script>