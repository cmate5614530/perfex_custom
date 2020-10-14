<script>
(function($) {
  "use strict";
		validate_estimates_form();
		function validate_estimates_form(selector) {

    selector = typeof(selector) == 'undefined' ? '#estimate-form' : selector;

    appValidateForm($(selector), {
        vendor: 'required',
        pur_request: 'required',
        date: 'required',
        currency: 'required',
        number: {
            required: true
        }
    });

    $("body").find('input[name="number"]').rules('add', {
        remote: {
            url: admin_url + "purchase/validate_estimate_number",
            type: 'post',
            data: {
                number: function() {
                    return $('input[name="number"]').val();
                },
                isedit: function() {
                    return $('input[name="number"]').data('isedit');
                },
                original_number: function() {
                    return $('input[name="number"]').data('original-number');
                },
                date: function() {
                    return $('body').find('.estimate input[name="date"]').val();
                },
            }
        },
        messages: {
            remote: app.lang.estimate_number_exists,
        }
    });

}
		// Init accountacy currency symbol
		init_currency();
		// Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
})(jQuery);

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}

function dc_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;

  $('input[name="dc_total"]').val(numberWithCommas(rs));
  $('input[name="after_discount"]').val(numberWithCommas(t_mn - rs));

}

function dc_total_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseFloat(removeCommas(total_mn));
  var rs = t_mn - parseFloat(removeCommas(invoker.value));

   $('input[name="after_discount"]').val(numberWithCommas(rs));
}

<?php if(!isset($estimate)){
 ?>	

function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

var dataObject = [
      
    ];
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
          	  multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'discount_%',
          type: 'numeric',
      
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
      
        }
        
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 22,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('items'); ?>',
        '<?php echo _l('unit'); ?>',
        '<?php echo _l('unit_price'); ?>',
        '<?php echo _l('quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
	      if(prop == 'item_code'){
	        $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
	          response = JSON.parse(response);

	          hot.setDataAtCell(row,1, response.value.unit_id);
	          hot.setDataAtCell(row,2, response.value.purchase_price);
	          hot.setDataAtCell(row,4, response.value.purchase_price*hot.getDataAtCell(row,3));
	        });
	      }else if(prop == 'quantity'){
	        hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,2));
	        hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,2));
          hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,2));
	      }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,3));
          hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,3));
          hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,3));
        }else if(prop == 'tax'){
	      	$.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
	          response = JSON.parse(response);
	          hot.setDataAtCell(row,6, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 + parseFloat(hot.getDataAtCell(row,4)));
            hot.setDataAtCell(row,9, (response.total_tax*parseFloat(hot.getDataAtCell(row,4)))/100 + parseFloat(hot.getDataAtCell(row,4)));
	      	});
	      }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,8, (newValue*parseFloat(hot.getDataAtCell(row,6)))/100);

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,9, (parseFloat(hot.getDataAtCell(row,6)) - newValue));
        }else if(prop == 'total_money'){
         var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 9)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 9)));
            }
          }
           $('input[name="total_mn"]').val(numberWithCommas(total_money));
        }
      }

	    });
	}
  });
$('.save_detail').on('click', function() {
  $('input[name="estimate_detail"]').val(JSON.stringify(hot.getData()));   
});
<?php } else{ ?>

  function numberWithCommas(x) {
    "use strict";
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

	var dataObject = <?php echo html_entity_decode($estimate_detail); ?>;
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
      	{
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_estimate',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
          	  multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'discount_%',
          type: 'numeric',
      
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 22,
      rowHeaders: true,
      colWidths: [0,0,200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
      	'',
        '',
        '<?php echo _l('items'); ?>',
        '<?php echo _l('unit'); ?>',
        '<?php echo _l('unit_price'); ?>',
        '<?php echo _l('quantity'); ?>',
        '<?php echo _l('subtotal_before_tax'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      hiddenColumns: {
        columns: [0,1],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
	      if(prop == 'item_code'){
	        $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
	          response = JSON.parse(response);

	          hot.setDataAtCell(row,3, response.value.unit_id);
	          hot.setDataAtCell(row,4, response.value.purchase_price);
	          hot.setDataAtCell(row,6, response.value.purchase_price*hot.getDataAtCell(row,5));
	        });
	      }else if(prop == 'quantity'){
	        hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,4));
	        hot.setDataAtCell(row,8, newValue*hot.getDataAtCell(row,4));
          hot.setDataAtCell(row,11, newValue*hot.getDataAtCell(row,4));
	      }else if(prop == 'unit_price'){
          hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,8, newValue*hot.getDataAtCell(row,5));
          hot.setDataAtCell(row,11, newValue*hot.getDataAtCell(row,5));
        }else if(prop == 'tax'){
	      	$.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
	          response = JSON.parse(response);
	          hot.setDataAtCell(row,8, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 + parseFloat(hot.getDataAtCell(row,6)));
            hot.setDataAtCell(row,11, (response.total_tax*parseFloat(hot.getDataAtCell(row,6)))/100 + parseFloat(hot.getDataAtCell(row,6)));
	      	});
	      }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,10, (newValue*parseFloat(hot.getDataAtCell(row,8)))/100);

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,11, (parseFloat(hot.getDataAtCell(row,8)) - newValue));
        }else if(prop == 'total_money'){
         var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0){
              total_money += (parseFloat(hot.getDataAtCell(row_index, 11)));
            }
          }
          $('input[name="total_mn"]').val(numberWithCommas(total_money));
        }
      }
	    });
	}
  });
$('.save_detail').on('click', function() {
  $('input[name="estimate_detail"]').val(JSON.stringify(hot.getData()));   
});

var total_money = 0;
for (var row_index = 0; row_index <= 40; row_index++) {
  if(parseFloat(hot.getDataAtCell(row_index, 11)) > 0){
    total_money += (parseFloat(hot.getDataAtCell(row_index, 11)));
  }
}
$('input[name="total_mn"]').val(numberWithCommas(total_money));

<?php } ?>

function coppy_pur_request(){
  "use strict";
	var pur_request = $('select[name="pur_request"]').val();
	if(pur_request != ''){
		 hot.alter('remove_row',0,hot.countRows ());
		$.post(admin_url + 'purchase/coppy_pur_request/'+pur_request).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
	      data: response.result,
	      });
      	});
	}else{
		alert_float('warning', '<?php echo _l('please_chose_pur_request'); ?>')
	}
}

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
	  var selectedId;
	  var optionsList = cellProperties.chosenOptions.data;
	  
	  if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
	      Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	      return td;
	  }

	  var values = (value + "").split("|");
	  value = [];
	  for (var index = 0; index < optionsList.length; index++) {

	      if (values.indexOf(optionsList[index].id + "") > -1) {
	          selectedId = optionsList[index].id;
	          value.push(optionsList[index].label);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}

</script>