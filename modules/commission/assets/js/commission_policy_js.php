<script>
(function($) {
  "use strict";
  var dataObject = [
    {
      product: '',
      percent_enjoyed: '',
    },
  ];

  var hotElement = document.querySelector('#product_setting');
  var hotSettings = {
    data: dataObject,
    columns: [
      {
        data: 'product_groups',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
            multiple: true,
            data: <?php echo json_encode($product_groups); ?>
        }
      },
      {
        data: 'product',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
          multiple: true,
          data: <?php echo json_encode($products); ?>
        }
      },
      {
        data: 'number_from',
        type: 'numeric'
      },
      {
        data: 'number_to',
        type: 'numeric'
      },
      {
        data: 'percent',
        type: 'numeric'
      },
    ],
    licenseKey: 'non-commercial-and-evaluation',
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 25,
     defaultRowHeight: 100,
    maxRows: 22,
    rowHeaders: true,
    colHeaders: [
      '<?php echo _l('product_groups'); ?>',
      '<?php echo _l('product'); ?>',
      '<?php echo _l('from_number'); ?>',
      '<?php echo _l('to_number'); ?>',
      '<?php echo _l('percent_enjoyed'); ?>',
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
  var addMoreLadderInputKey = $('.list_ladder_setting #item_ladder_setting').length;
  $("body").on('click', '.new_item_ladder', function() {
    if ($(this).hasClass('disabled')) { return false; }

    addMoreLadderInputKey++;
    var newItem = $('.list_ladder_setting').find('#item_ladder_setting').eq(0).clone().appendTo('.list_ladder_setting');
    newItem.find('button[role="button"]').remove();
    newItem.find('select').selectpicker('refresh');

    newItem.find('input[id="from_amount[0]"]').attr('name', 'from_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="from_amount[0]"]').attr('id', 'from_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="to_amount[0]"]').attr('name', 'to_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="to_amount[0]"]').attr('id', 'to_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="percent_enjoyed_ladder[0]"]').attr('name', 'percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="percent_enjoyed_ladder[0]"]').attr('id', 'percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');

    newItem.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newItem.find('button[name="add"]').removeClass('new_item_ladder').addClass('remove_item_ladder').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
  });

  $('.commission-policy-form-submiter').on('click', function() {
    $('input[name="product_setting"]').val(JSON.stringify(hot.getData()));
  });

  $("body").on('click', '.remove_item_ladder', function() {
      $(this).parents('#item_ladder_setting').remove();
  });

  $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });
  hot.loadData(<?php echo html_entity_decode($product_setting); ?>);
  $('select[name="commission_policy_type"]').on('change', function() {
    if($(this).val() == '2'){
      $("div[id='calculated_as_percentage']").removeClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
    }else if($(this).val() == '3'){
      $("div[id='calculated_by_the_product']").removeClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
    }else if($(this).val() == '1'){
      $("div[id='calculated_as_ladder']").removeClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
    }else{
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
    }
  });

  appValidateForm($('#commission-policy-form'),{
    name: 'required',
    from_date: 'required',
    to_date: 'required',
    commission_policy_type: 'required',
   });

  setTimeout(
      function()
      {
        if($("div[id='calculated_by_the_product']").hasClass('is_hide')){
          $("div[id='calculated_by_the_product']").addClass('hide');
        }
      }, 100);

  $('select[name="client_groups[]"]').on('change', function() {
    var data = {};
    data.groups = $('select[name="client_groups[]"]').val();
    $.post(admin_url + 'commission/client_groups_change', data).done(function(response) {
      response = JSON.parse(response);
      var html = '';
      $.each(response, function() {
          html += '<option value="'+ this.userid +'" data-subtext="'+this.customerGroups+'">'+ this.company +'</option>';
       });
      $('select[name="clients[]"]').html(html);
      $('select[name="clients[]"]').selectpicker('refresh');
    });
  });

  $('input[name="commmission_first_invoices"]').on('change', function() {
    if($('#commmission_first_invoices').is(':checked') == true){
      $('#div_commmission_first_invoices').removeClass('hide');
    }else{
      $('#div_commmission_first_invoices').addClass('hide');
    }
  });
})(jQuery);
function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  "use strict";
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
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