<script>  

var commodity_group_type_value = {};
    function new_commodity_group_type(){
    	"use strict";
        $('#commodity_group_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#commodity_group_type_id').html('');

        var handsontable_html ='<div id="hot_commodity_group_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }
       

    setTimeout(function(){
        "use strict";

      //hansometable for allowance no taxable
      var hotElement1 = document.querySelector('#hot_commodity_group_type');

       var commodity_group_type = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 22,
        minRows: 9,
        width: '100%',
        height: 330,
        licenseKey: 'non-commercial-and-evaluation',
        rowHeaders: true,
        autoColumncommodity_group: {
          samplingRatio: 23
        },
       

        filters: true,
        manualRowRecommodity_group: true,
        manualColumnRecommodity_group: true,
        allowInsertRow: true,
        allowRemoveRow: true,
      	columnHeaderHeight: 40,

        colWidths: [40, 100, 30,30, 30, 140],
        rowHeights: 30,
        
        rowHeaderWidth: [44],
        minRow : 10,

        columns: [
                    {
                      type: 'text',
                      data: 'commodity_group_code'
                    },
                     {
                      type: 'text',
                      data: 'name',
                      // set desired format pattern and
                    },
                    {
                      type: 'numeric',
                      data: 'order',
                    },
                    {
                      type: 'checkbox',
                      data: 'display',
                      checkedTemplate: 'yes',
                      uncheckedTemplate: 'no'
                    },
                    {
                      type: 'text',
                      data: 'note',
                    },
                  
                  ],

        colHeaders: true,
        nestedHeaders: [{"1":"<?php echo _l('commodity_group_code') ?>",
                          "2":"<?php echo _l('commodity_group_name') ?>",
                          "3":"<?php echo _l('order') ?>",
                         "4":"<?php echo _l('display') ?>",
                         "5":"<?php echo _l('note') ?>",
                        }],

        
        data: [
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        {"commodity_group_code":"","name":"","order":"","display":"yes","note":""},
        ],

      });
       commodity_group_type_value = commodity_group_type;
      },300);

    }

  function edit_commodity_group_type(invoker,id){
    "use strict";

    var commodity_group_code = $(invoker).data('commodity_group_code');
    var name = $(invoker).data('name');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#commodity_group_type_id').html('');
        $('#commodity_group_type_id').append(hidden_input('id',id));
        
        $('#commodity_group_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
      

        var handsontable_html ='<div id="hot_commodity_group_type" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
      "use strict";
      var hotElement1 = document.querySelector('#hot_commodity_group_type');

       var commodity_group_type = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 1,
        width: '100%',
        height: 130,
        rowHeaders: true,
        autoColumncommodity_group: {
          samplingRatio: 23
        },
        
		licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowRecommodity_group: true,
        manualColumnRecommodity_group: true,
       
      	columnHeaderHeight: 40,

        colWidths: [40, 100, 30,30, 30, 140],
        rowHeights: 30,
        
        rowHeaderWidth: [44],

        columns: [
                {
                  type: 'text',
                  data: 'commodity_group_code',
                  
                },
                 {
                  type: 'text',
                  data: 'name',
                  // set desired format pattern and
                },
                {
                  type: 'numeric',
                  data: 'order',
                },
                {
                  type: 'checkbox',
                  data: 'display',
                  checkedTemplate: 'yes',
                  uncheckedTemplate: 'no'
                },
                {
                  type: 'text',
                  data: 'note',
                },
              
              ],

        colHeaders: true,
        nestedHeaders: [{"1":"<?php echo _l('commodity_group_code') ?>",
                      "2":"<?php echo _l('commodity_group_name') ?>",
                      "3":"<?php echo _l('order') ?>",
                      "4":"<?php echo _l('display') ?>",
                      "5":"<?php echo _l('note') ?>",
                      }],

        data: [{"commodity_group_code":commodity_group_code,"name":name,"order":order,"display":display,"note":note}],

      });
       commodity_group_type_value = commodity_group_type;
      },300);

    }

    function add_commodity_group_type(invoker){
      	"use strict";
    
        var valid_commodity_group_type = $('#hot_commodity_group_type').find('.htInvalid').html();

        if(valid_commodity_group_type){
          alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
          
        }else{

          $('input[name="hot_commodity_group_type"]').val(commodity_group_type_value.getData());
          $('#add_commodity_group_type').submit(); 

        }
        
    }

</script>