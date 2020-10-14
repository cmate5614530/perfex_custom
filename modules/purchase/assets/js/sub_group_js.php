<script> 

var sub_group_type_value = {};
    function new_sub_group_type(){
      "use strict";
        $('#sub_group_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#sub_group_type_id').html('');

        var handsontable_html ='<div id="hot_sub_group" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

      setTimeout(function(){
        "use strict";
        var hotElement1 = document.querySelector('#hot_sub_group');

         var sub_group_type = new Handsontable(hotElement1, {
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
          autoColumnsub_group: {
            samplingRatio: 23
          },

          filters: true,
          manualRowResub_group: true,
          manualColumnResub_group: true,
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
                        data: 'sub_group_code'
                      },
                       {
                        type: 'text',
                        data: 'name',
                        // set desired format pattern and
                      },
                      {
                        type: 'text',
                        data: 'group_id',
                        renderer: customDropdownRenderer,
                        editor: "chosen",
                        width: 150,
                        chosenOptions: {
                            data: <?php echo json_encode($item_group); ?>
                        },

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
          nestedHeaders: [{"1":"<?php echo _l('sub_group_code') ?>",
                            "2":"<?php echo _l('sub_group_name') ?>",
                            "3":"<?php echo _l('group_name') ?>",
                            "4":"<?php echo _l('order') ?>",
                           "5":"<?php echo _l('display') ?>",
                           "6":"<?php echo _l('note') ?>",
                          }],

          data: [
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          {"sub_group_code":"","name":"","group_id":"","order":"","display":"yes","note":""},
          ],

        });
         sub_group_type_value = sub_group_type;
        },300);


    }

  function edit_sub_group_type(invoker,id){
    "use strict";

    var sub_group_code = $(invoker).data('sub_group_code');
    var name = $(invoker).data('name');
    var group_id = $(invoker).data('group_id');

    var order = $(invoker).data('order');
    if($(invoker).data('display') == 0){
      var display = 'no';
    }else{
      var display = 'yes';
    }
    var note = $(invoker).data('note');

        $('#sub_group_type_id').html('');
        $('#sub_group_type_id').append(hidden_input('id',id));
        $('#sub_group_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');

        var handsontable_html ='<div id="hot_sub_group" class="hot handsontable htColumnHeaders"></div>';
        if($('#add_handsontable').html() != null){
          $('#add_handsontable').empty();

          $('#add_handsontable').html(handsontable_html);
        }else{
          $('#add_handsontable').html(handsontable_html);

        }

    setTimeout(function(){
      "use strict";
      var hotElement1 = document.querySelector('#hot_sub_group');

       var sub_group_type = new Handsontable(hotElement1, {
        contextMenu: true,
        manualRowMove: true,
        manualColumnMove: true,
        stretchH: 'all',
        autoWrapRow: true,
        rowHeights: 30,
        defaultRowHeight: 100,
        maxRows: 1,
        width: '100%',
        height: 250,
        rowHeaders: true,
        autoColumnsub_group: {
          samplingRatio: 23
        },
        licenseKey: 'non-commercial-and-evaluation',
        filters: true,
        manualRowResub_group: true,
        manualColumnResub_group: true,
        columnHeaderHeight: 40,

        colWidths: [40, 100, 30,30, 30, 140],
        rowHeights: 30,
        rowHeaderWidth: [44],

        columns: [
                {
                  type: 'text',
                  data: 'sub_group_code',
                  readOnly:true,
                  
                },
                 {
                  type: 'text',
                  data: 'name',
                  // set desired format pattern and
                },
                {
                    type: 'text',
                    data: 'group_id',
                    renderer: customDropdownRenderer,
                    editor: "chosen",
                    width: 150,
                    chosenOptions: {
                        data: <?php echo json_encode($item_group); ?>
                    },

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
        nestedHeaders: [{"1":"<?php echo _l('sub_group_code') ?>",
                      "2":"<?php echo _l('sub_group_name') ?>",
                      "3":"<?php echo _l('group_name') ?>",
                      "4":"<?php echo _l('order') ?>",
                      "5":"<?php echo _l('display') ?>",
                      "6":"<?php echo _l('note') ?>",
                      }],

        data: [{"sub_group_code":sub_group_code,"name":name,"group_id":group_id,"order":order,"display":display,"note":note}],


      });
       sub_group_type_value = sub_group_type;
      },300);

    }

    function add_sub_group_type(invoker){
      "use strict";

      var valid_sub_group_type = $('#hot_sub_group').find('.htInvalid').html();

      if(valid_sub_group_type){
        alert_float('danger', "<?php echo _l('data_must_number') ; ?>");
      }else{

        $('input[name="hot_sub_group"]').val(sub_group_type_value.getData());
        $('#add_sub_group').submit(); 

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