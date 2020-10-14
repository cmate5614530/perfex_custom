<script>
	var list_staff;
	(function($) {
	  "use strict";

	  list_staff = <?php echo html_entity_decode($list_staff_json); ?>;

	  $('select[name="applicable_staff[]"]').on('change', function() {
	  	var html = '';
	  	$.each($(this).val(), function( index, value ) {
		  html += '<a class="list-group-item">'+list_staff[value]+'</a>';
		});
		$('div[id="list_applicable_staff"]').html(html);
	  });
	appValidateForm($('#applicable-staff-form'),{
		commission_policy: 'required',
		'applicable_staff[]': 'required',
	 });
	})(jQuery);

</script>