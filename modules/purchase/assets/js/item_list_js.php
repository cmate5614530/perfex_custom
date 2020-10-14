<script defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIABcm7Pk9jAIg7opit0NWdSCJzoBz95k&callback=initMap&libraries=drawing">
</script>

<script>

var hidden_columns = [3,7];
    

  $(function(){

     if($('#dropzoneDragArea').length > 0){
        expenseDropzone = new Dropzone(".commodity_list-add-edit", appCreateDropzoneOptions({
          autoProcessQueue: false,
          clickable: '#dropzoneDragArea',
          previewsContainer: '.dropzone-previews',
          addRemoveLinks: true,
          maxFiles: 10,

            success:function(file,response){
             response = JSON.parse(response);
             if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
               window.location.assign(response.url);
             }else{

      

                /*Upload the remaining files */
                expenseDropzone.processQueue();

             }

           },

       }));
     }

     appValidateForm($("body").find('.commodity_list-add-edit'), {
        'vehicle_make': 'required',
        'vehicle_model': 'required',
        'base_location': 'required',
        'number_of_passengers': 'required',

    },expenseSubmitHandler);

   });
     
    table_item_list = $('table.table-table_item_list');
    if(user_type == 'admin')
      _table_api = initDataTable(table_item_list, admin_url+'purchase/table_item_list', [0], [0], '',  [1, 'desc']);
    else
      _table_api = initDataTable(table_item_list, site_url+'purchase/vendors_portal/table_item_list', [0], [0], '',  [1, 'desc']);


  init_commodity_detail();
  function init_commodity_detail(id) {
    if(user_type=='admin')
      load_small_table_item_proposal(id, '#proposal_sm_view', 'item_id', 'purchase/get_item_data_ajax', '.proposal_sm');
    else 
      load_small_table_item_proposal(id, '#proposal_sm_view', 'item_id', 'purchase/vendors_portal/get_item_data_ajax', '.proposal_sm');

  }

  function load_small_table_item_proposal(pr_id, selector, input_name, url, table) {
    "use strict";
    var _tmpID = $('input[name="' + input_name + '"]').val();
    // Check if id passed from url, hash is prioritized becuase is last
    if (_tmpID !== '' && !window.location.hash) {
        pr_id = _tmpID;
        // Clear the current id value in case user click on the left sidebar credit_note_ids
        $('input[name="' + input_name + '"]').val('');
    } else {
        // check first if hash exists and not id is passed, becuase id is prioritized
        if (window.location.hash && !pr_id) {
            pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        }
    }
    if (typeof(pr_id) == 'undefined' || pr_id === '') { return; }
    if (!$("body").hasClass('small-table')) { toggle_small_view_proposal(table, selector); }
    $('input[name="' + input_name + '"]').val(pr_id);
    do_hash_helper(pr_id);

    if(user_type == 'admin')
      $(selector).load(admin_url + url + '/' + pr_id);
    else 
      $(selector).load(site_url + url + '/' + pr_id);

    if (is_mobile()) {
        $('html, body').animate({
            scrollTop: $(selector).offset().top + 150
        }, 600);
    }
}

function toggle_small_view_proposal(table, main_data) {
  "use strict";

    $("body").toggleClass('small-table');
    var tablewrap = $('#small-table');
    if (tablewrap.length === 0) { return; }
    var _visible = false;
    if (tablewrap.hasClass('col-md-5')) {
        tablewrap.removeClass('col-md-5').addClass('col-md-12');
        _visible = true;
        $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
    } else {
        tablewrap.addClass('col-md-5').removeClass('col-md-12');
        $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
    }
    var _table = $(table).DataTable();
    // Show hide hidden columns
    _table.columns(hidden_columns).visible(_visible, false);
    _table.columns.adjust();
    $(main_data).toggleClass('hide');
    $(window).trigger('resize');
}

function close_modal_preview(){
 $('._project_file').modal('hide');
}

$(document).ready(function(){
// Prepare the preview for profile picture
    $("#wizard-picture").change(function(){
        readURL(this);
    });
});

  //remove license info hasometable
$('#hot-display-license-info').empty();
Dropzone.options.expenseForm = false;
var expenseDropzone;


   function expenseSubmitHandler(form){
    //9.10 AG: add driver_lang parameter to the form data
      let lang = get_driver_lang();  
      let form_data = $(form).serialize();
      form_data = form_data + "&driver_lang=" + lang;
      //console.log('expenseSubmitHandler', form_data); 
    //9.10 AG: end  

    //9.11 AG: add net, bus prices parameter to the form data
    let net_prices = get_net_prices();  
    let bus_prices = get_bus_prices();  
    
    form_data = form_data + "&net_prices=" + net_prices;
    form_data = form_data + "&bus_prices=" + bus_prices;
    //9.11 AG: end  

    //9.11 AG: add weekday start end time
    wse = get_wse();
    form_data = form_data + "&wse=" + wse;
    //9.11 AG: end

    //9.11 AG: add exclude ranges
    ers = get_exclude_ranges();
    form_data = form_data + "&ers=" + ers;
    //console.log('ers',ers);
    //9.11 AG: end

    //9.11 AG: add countries
    cts = get_countries();
    form_data = form_data + "&cts=" + cts;
    //9.11 AG: end

    //9.12 AG: add map areas
    areas = get_map_data();
    form_data = form_data + "&areas=" + areas;
    //9.12 AG: end

    //9.16 AG: add pricing rules dates
    pr_dates = get_dates_data();
    form_data = form_data + "&pr_dates=" + pr_dates;
    //9.16 AG: end

    //9.16 AG: add pricing rules hours
    pr_hours = get_hours_data();
    form_data = form_data + "&pr_hours=" + pr_hours;
    //9.16 AG: end

    //9.16 AG: add pricing rules distance
    distance = get_distance_data();
    form_data = form_data + "&pr_distance=" + distance;
    //9.16 AG: end

      $.post(form.action, form_data).done(function(response) {
        response = JSON.parse(response);
        if (response.commodityid) {
         if(typeof(expenseDropzone) !== 'undefined'){
          if (expenseDropzone.getQueuedFiles().length > 0) {
            if(user_type == 'admin')
              expenseDropzone.options.url = admin_url + 'purchase/add_commodity_attachment/' + response.commodityid;
            else 
              expenseDropzone.options.url = site_url + 'purchase/vendors_portal/add_commodity_attachment/' + response.commodityid;
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

      //function delete contract attachment file 
  function delete_contract_attachment(wrapper, id) {
    "use strict";
    
    if (confirm_delete()) {
       $.get(admin_url + 'purchase/delete_commodity_file/' + id, function (response) {
          if (response.success == true) {
             $(wrapper).parents('.dz-preview').remove();

             var totalAttachmentsIndicator = $('.dz-preview'+id);
             var totalAttachments = totalAttachmentsIndicator.text().trim();

             if(totalAttachments == 1) {
               totalAttachmentsIndicator.remove();
             } else {
               totalAttachmentsIndicator.text(totalAttachments-1);
             }
             alert_float('success', "<?php echo _l('delete_commodity_file_success') ?>");

          } else {
             alert_float('danger', "<?php echo _l('delete_commodity_file_false') ?>");
          }
       }, 'json');
    }
    return false;
  }

  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function edit_commodity_item(invoker){
  "use strict";
  $('#commodity_list-add-edit').modal({backdrop: 'static', keyboard: false});
      /*add id item when edit*/
      $('#commodity_item_id').empty();
      $('#commodity_item_id').append(hidden_input('id',$(invoker).data('commodity_id')));

      $('.edit-commodity-title').removeClass('hide');
      $('.add-commodity-title').addClass('hide');

      //set normal values
      $('#commodity_list-add-edit input[name="vehicle_make"]').val($(invoker).data('vehicle_make'));
      $('#commodity_list-add-edit input[name="vehicle_model"]').val($(invoker).data('vehicle_model'));
      $('#commodity_list-add-edit input[name="number_of_passengers"]').val($(invoker).data('number_of_passengers'));
      $('#commodity_list-add-edit input[name="number_of_suitcases"]').val($(invoker).data('number_of_suitcases'));
      $('#commodity_list-add-edit input[name="base_location"]').val($(invoker).data('base_location'));
      $('#commodity_list-add-edit input[name="service_pl"]').val($(invoker).data('service_pl'));
      $('#commodity_list-add-edit input[name="service_dol"]').val($(invoker).data('service_dol'));
      $('#commodity_list-add-edit input[name="distance_pl"]').val($(invoker).data('distance_pl'));
      $('#commodity_list-add-edit input[name="distance_dol"]').val($(invoker).data('distance_dol'));
      $('#commodity_list-add-edit input[name="extra_time_min"]').val($(invoker).data('extra_time_min'));
      $('#commodity_list-add-edit input[name="extra_time_max"]').val($(invoker).data('extra_time_max'));
      $('#commodity_list-add-edit input[name="extra_time_step"]').val($(invoker).data('extra_time_step'));
      $('#commodity_list-add-edit select[name="group_id"]').val($(invoker).data('group_id'));
      $('#commodity_list-add-edit input[name="google_calendar_id"]').val($(invoker).data('google_calendar_id'));
      $('#commodity_list-add-edit textarea[name="google_calendar_settings"]').val($(invoker).data('google_calendar_settings'));

      //set switch values
      set_switch_state("default_category_enable", $(invoker).data('default_category_enable') );
      set_switch_state("extra_time_enable", $(invoker).data('extra_time_enable') );
      set_switch_state("price_type_variable", $(invoker).data('price_type_variable') );
      set_switch_state("vehicle_availability_enable", $(invoker).data('vehicle_availability_enable') );
      set_switch_state("google_calendar_enable", $(invoker).data('google_calendar_enable') );
      //set price table
      set_price_table_enable( $(invoker).data('price_type_variable') );
      set_price_values($(invoker).data('net_prices'), $(invoker).data('bus_prices'));
      
      //9.10 AG: set language values
      set_driver_lang($(invoker).data('driver_lang'));
      
      //set week day start end time
      set_wse( $(invoker).data('wse') );
      //9.11 AG: set exclude ranges
      set_exclude_ranges( $(invoker).data('ers') );

      //9.11 AG: set countries
      set_countries( $(invoker).data('cts'));

      //9.11 AG: set pickup and drop off areas maps: from db 
      set_map_data( $(invoker).data('areas'));

      //9.16 AG: set pricing rules dates: from db 
      set_dates_data( $(invoker).data('pr_dates'));
      $('#commodity_list-add-edit input[name="pr_dates_acp"]').val($(invoker).data('pr_dates_acp'));


      //9.16 AG: set pricing rules hours: from db 
      set_hours_data( $(invoker).data('pr_hours'));
      $('#commodity_list-add-edit input[name="pr_hours_acp"]').val($(invoker).data('pr_hours_acp'));

      //9.16 AG: set pricing rules distance: from db 
      set_distance_data( $(invoker).data('pr_distance'));



      $('#commodity_list-add-edit select[name="unit_id"]').val($(invoker).data('unit_id')).change();
      $('#commodity_list-add-edit select[name="commodity_type"]').val($(invoker).data('commodity_type')).change();
      if($(invoker).data('group_id') != '0' && $(invoker).data('group_id') != ''){
        $('#commodity_list-add-edit select[name="group_id"]').val($(invoker).data('group_id')).change();
      }else{
        $('#commodity_list-add-edit select[name="group_id"]').val('').change();
      }
      $('#commodity_list-add-edit select[name="purchase_id"]').val($(invoker).data('purchase_id')).change();

      if($(invoker).data('tax') != '0' && $(invoker).data('tax') != ''){
        $('#commodity_list-add-edit select[name="tax"]').val($(invoker).data('tax')).change();
      }else{
        $('#commodity_list-add-edit select[name="tax"]').val('').change();
      }


      $('#commodity_list-add-edit input[name="origin"]').val($(invoker).data('origin'));
      $('#commodity_list-add-edit input[name="rate"]').val($(invoker).data('rate'));
      $('#commodity_list-add-edit input[name="type_product"]').val($(invoker).data('type_product'));

      $('#commodity_list-add-edit select[name="style_id"]').val($(invoker).data('style_id')).change();
      $('#commodity_list-add-edit select[name="model_id"]').val($(invoker).data('model_id')).change();
      $('#commodity_list-add-edit select[name="size_id"]').val($(invoker).data('size_id')).change();
      $('#commodity_list-add-edit select[name="sub_group"]').val($(invoker).data('sub_group')).change();

      /*ngay sx, hsd*/
      $('#commodity_list-add-edit input[name="date_manufacture"]').val($(invoker).data('date_manufacture')).change();
      $('#commodity_list-add-edit input[name="expiry_date"]').val($(invoker).data('expiry_date')).change();
      /*get file url*/
              /*get description*/
        let url = admin_url + 'purchase/get_commodity_file_url/';
        if(user_type != 'admin')
          url = site_url + 'purchase/vendors_portal/get_commodity_file_url/'; 
        //9.9 AG: add csrf part
        var token_name =  csrfData['token_name'];
        var token_data = csrfData['hash'];  
        
        $.post(url + $(invoker).data('commodity_id'), {'csrf_token_name': token_data} ).done(function(response) {
            response = JSON.parse(response);
            $('#images_old_preview').empty();
            if(response !=''){
              $('#images_old_preview').prepend(response.arr_images);
            }
        });
  }

  function new_commodity_item(){
    "use strict";
    $('#commodity_list-add-edit').modal('show');

        $('#commodity_item_id').empty();

        $('.edit-commodity-title').addClass('hide');
        $('.add-commodity-title').removeClass('hide');

        $('.dropzone-previews').empty();
        $('#images_old_preview').empty();

      /*empty form*/
      $('#commodity_list-add-edit input[name="vehicle_make"]').val('');
      
      $('#commodity_list-add-edit input[name="vehicle_model"]').val('');
      $('#commodity_list-add-edit input[name="number_of_passengers"]').val('');
      $('#commodity_list-add-edit input[name="number_of_suitcases"]').val('');
      $('#commodity_list-add-edit input[name="base_location"]').val('');
      $('#commodity_list-add-edit input[name="extra_time_min"]').val('');
      $('#commodity_list-add-edit input[name="extra_time_max"]').val('');
      $('#commodity_list-add-edit input[name="extra_time_step"]').val('');
      $('#commodity_list-add-edit input[name="google_calendar_id"]').val('');
      $('#commodity_list-add-edit input[name="google_calendar_settings"]').val('');

      $('#commodity_list-add-edit select[name="group_id"]').val('').change();

      //set switch values
      set_switch_state("default_category_enable",false );
      set_switch_state("extra_time_enable", false);
      set_switch_state("price_type_variable",false );
      set_switch_state("vehicle_availability_enable", false);
      set_switch_state("google_calendar_enable",false );

      //9.10 AG: set language values
      set_driver_lang('');


    }

  function view_commodity_images(){
    "use strict";
    $('#item_list_carosel').modal('show');
  }

  function staff_bulk_actions(){
  "use strict";
  $('#table_commodity_list_bulk_actions').modal('show');
}

function purchase_delete_bulk_action(event) {
    "use strict";

      if (confirm_delete()) {
          var mass_delete = $('#mass_delete').prop('checked');

          if(mass_delete == true){
              var ids = [];
              var data = {};

              data.mass_delete = true;
              data.rel_type = 'commodity_list';

              var rows = $('#table-table_item_list').find('tbody tr');
              $.each(rows, function() {
                  var checkbox = $($(this).find('td').eq(0)).find('input');
                  if (checkbox.prop('checked') === true) {
                      ids.push(checkbox.val());
                  }
              });

              data.ids = ids;
              $(event).addClass('disabled');
              setTimeout(function() {
                  $.post(admin_url + 'purchase/purchase_delete_bulk_action', data).done(function() {
                      window.location.reload();
                  }).fail(function(data) {
                      $('#table_commodity_list_bulk_actions').modal('hide');
                      alert_float('danger', data.responseText);
                  });
              }, 200);
          }else{
              window.location.reload();
          }

      }
  }

  
  //////////////////////////////////////////////////////////////
  /*Here are my added functions
  */
  //////////////////////////////////////////////////////////////
  
  //set countries
  function set_countries(c)
  {
    let sels = c.split('_');
    //console.log('sels', sels);
    $('#attribute_countries').select2({ maximumSelectionLength: 5 }).val(sels).trigger('change');
  }

  //get countries
  function get_countries()
  {
    let sels = $('#attribute_countries').select2('data');
    let ret = [];
    sels.forEach( function(s)
    {
      ret.push(s['id']);
    });
    return ret.join('_');
  }
  
  ////////////////////////////////////////////////////////////
  //Exclude ranges related js scripts 
  ////////////////////////////////////////////////////////////
  //set exclude ranges
  function set_exclude_ranges(ers)
  {
     //9.15 remove prev lines first
     $('#exclude_ranges_table tbody tr:not(:first)').remove();
    //
    
    let rs = ers.split('_');
    if(rs.length == 0 || rs.length % 2 == 1)
    {
      return;
    }
   

    let i ;
    for(i = 0; i<rs.length/2; i++)
    {
      add_exclude_range();
      $('#exclude_ranges_table tr:last').find('input').each( function (index) {
        $(this).val( rs[i*2 + index] );
      });
    }
    return rs.join("_");
  }
  //get exclude ranges
  function get_exclude_ranges()
  {
    let rs = [];
    $('#exclude_ranges_table tr input').each(function(index){
      rs.push( $(this).val() );
    });
    return rs.join("_");
  }
  //add exclude range
  function add_exclude_range()
  {
    //test
    //  console.log(get_countries());
    //test end
    $('#exclude_ranges_table tr:last').after("\
    <tr>\
    <td> <input type='text' > </td>\
    <td> <input type='text' >  </td>\
    <td> <a href='#' onclick='remove_exclude_range(this); '> Remove </a> </td>\
    </tr>");
    //set date time picker
    $('#exclude_ranges_table tr:last').find('input').each(function(index) {
      $(this).datetimepicker();
    });
  }
  //remove exclude range
  function remove_exclude_range(invoker)
  {
    $(invoker).closest('tr').remove();

  }

  ////////////////////////////////////////////////////////////
  //Business hours: weekday  related js scripts 
  ////////////////////////////////////////////////////////////

  //get,set weekday start end time
  function get_wse()
  {
    let sel = '#weekday_start_end tbody tr input'; 
    let ts = [];
    $(sel).each(function(index)
    {
      ts.push( $(this).val());
    });
    return ts.join('_');
  }

  function set_wse(wse)
  {
    let ts = wse.split("_");
    if(ts.length != 14) return;

    let sel = '#weekday_start_end tbody tr input'; 
    $(sel).each(function(index)
    {
        $(this).val(ts[index]);
    });
    return ts.join('_');
  }

  ////////////////////////////////////////////////////////////
  //prices related js scripts 
  ////////////////////////////////////////////////////////////

  //set price table row enable/disable
  function set_price_table_enable(val)
  {
    let pt = 'Fixed';
    if(val.toString() == 'true')
      pt = 'Variable';
            
    //net prices table
    let sel = '#net_prices_table tbody tr'; 
    
    $(sel).each(function(index)
    {
      if ( $(this).attr('type') == pt)
        $(this).find(":input").attr('disabled', false);
      else 
        $(this).find(":input").attr('disabled', true);
    });

    sel = '#bus_prices_table tbody tr'; 
    $(sel).each(function(index)
    {
      if ( $(this).attr('type') == pt)
        $(this).find(":input").attr('disabled', false);
      else 
        $(this).find(":input").attr('disabled', true);
    });

  }
  //get net prices
  function get_net_prices()
  {
    let sel = '#net_prices_table tbody tr'; 
    let ret = [];
    $(sel).each(function(index)
    {
      //console.log(index);
      let row = [];
      row[0] = $(this).find('input').val();
      row[1] = $(this).find('select').val();
      ret.push(row.join('_'));
    });
    return ret.join('@');
  }

  //get bus prices
  function get_bus_prices()
  {
    let sel = '#bus_prices_table tbody tr'; 
    let ret = [];
    $(sel).each(function(index)
    {
      //console.log(index);
      let row = [];
      row[0] = $(this).find('input').val();
      row[1] = $(this).find('select').val();
      ret.push(row.join('_'));
    });
    return ret.join('@');
  }
  //set prices to the table
  function set_price_values(net_prices, bus_prices)
  {
    //console.log('set_price_values', net_prices);
    
    let nps = net_prices.split('@');
    if(nps == 0) return;
    let sel = '#net_prices_table tbody tr'; 
    //empty table : end
    $(sel).each(function(index)
    {
      let np = nps[index].split('_');

      $(this).find('input').val(np[0]);
      $(this).find('select').val(np[1]);
    });  

    nps = bus_prices.split('@');
    sel = '#bus_prices_table tbody tr'; 
    $(sel).each(function(index)
    {
      let np = nps[index].split('_');

      $(this).find('input').val(np[0]);
      $(this).find('select').val(np[1]);
    }); 


  }

  ////////////////////////////////////////////////////////////
  //switch related js scripts 
  ////////////////////////////////////////////////////////////
  
  //set data to switch
  function set_switch_state(name,state)
  {
    
    let selector = '#commodity_list-add-edit div[name="' + name + '"]';
    $(selector).find('button').removeClass();
    if(state.toString() == "true")
    {
      $(selector).find('button:even').addClass("btn btn-primary active");
      $(selector).find('button:odd').addClass("btn btn-default");
    } else{
      $(selector).find('button:odd').addClass("btn btn-primary active");
      $(selector).find('button:even').addClass("btn btn-default");
    }
    $(selector).find('input').val(state);

  }

  //get data to switch
  function get_switch_state(name)
  {
    let selector = '#commodity_list-add-edit div[name="' + name + '"]';
    return $(selector).find('button:first').hasClass('active');
  }

  ////////////////////////////////////////////////////////////
  //Driver's language related js scripts 
  ////////////////////////////////////////////////////////////

  //9.10 AG: get driver lang
  function get_driver_lang()
  {
    let items = $('.lang-select .items');
    let selected = [];
    items.each(function(index){
      let ip = $(this).find('input');
      if(ip.is(':checked'))
        selected.push(ip.data('lang'));
    });
    return selected.join('_');
  }

  //9.10 AG: set driver lang
  function set_driver_lang(lang)
  {
    //clear first
    let items = $('.lang-select .items');
    items.each(function(index){
      let ip = $(this).find('input');
      ip.prop('checked', false);

      let lb = $(this).find('label');
      lb.removeClass('active');

    });
    //clear :end
    
    let langs = lang.split('_');
    //console.log('set_dirver_lang',langs);
    for(i = 0; i<langs.length; i++)
    {
      if(langs[i] == '') continue;
      //console.log(langs[i]);
      let sel = '*[data-lang="' + langs[i] + '"]';
      $(sel).prop('checked', true);
      $(sel).parents('label.btn').addClass('active');
    }
    return;

  }

  ////////////////////////////////////////////////////////////
  //Pricing rules : dates related js
  ////////////////////////////////////////////////////////////
  function set_dates_data(data)
  {
    //9.15 remove prev lines first
    $('#dates_table tbody tr:not(:first)').remove();
    //

    let rs = data.split('_');
    if(rs.length == 0 || rs.length % 2 == 1)
    {
      return;
    }
    

    let i ;
    for(i = 0; i<rs.length/2; i++)
    {
      add_date();
      $('#dates_table tr:last').find('input').each( function (index) {
        $(this).val( rs[i*2 + index] );
      });
    }
    return rs.join("_");
  }
  //get dates
  function get_dates_data()
  {
    let rs = [];
    $('#dates_table tr input').each(function(index){
      rs.push( $(this).val() );
    });
    return rs.join("_");
  }
  //add a date
  function add_date()
  {
    //test
    //  console.log(get_countries());
    //test end
    $('#dates_table tr:last').after("\
    <tr>\
    <td> <input type='text' class='all_width' > </td>\
    <td> <input type='text' class='all_width' >  </td>\
    <td> <a href='#' onclick='remove_date(this); '> Remove </a> </td>\
    </tr>");
    //set date time picker
    $('#dates_table tr:last').find('input').each(function(index) {
      $(this).datetimepicker({timepicker:false, format:'d-m-yy'});
    });
  }
  //remove a date
  function remove_date(invoker)
  {
    $(invoker).closest('tr').remove();

  }

  ////////////////////////////////////////////////////////////
  //Pricing rules : hours related js
  ////////////////////////////////////////////////////////////
  function set_hours_data(data)
  {
     //9.15 remove prev lines first
     $('#hours_table tbody tr:not(:first)').remove();
    //

    let rs = data.split('_');
    if(rs.length == 0 || rs.length % 2 == 1)
    {
      return;
    }
   

    let i ;
    for(i = 0; i<rs.length/2; i++)
    {
      add_hour();
      $('#hours_table tr:last').find('input').each( function (index) {
        $(this).val( rs[i*2 + index] );
      });
    }
    return rs.join("_");
  }
  //get hours
  function get_hours_data()
  {
    let rs = [];
    $('#hours_table tr input').each(function(index){
      rs.push( $(this).val() );
    });
    return rs.join("_");
  }
  //add a hour
  function add_hour()
  {
    //test
    //  console.log(get_countries());
    //test end
    $('#hours_table tr:last').after("\
    <tr>\
    <td> <input type='text' class='all_width' > </td>\
    <td> <input type='text' class='all_width' >  </td>\
    <td> <a href='#' onclick='remove_distance(this); '> Remove </a> </td>\
    </tr>");
    //set date time picker
    $('#hours_table tr:last').find('input').each(function(index) {
      $(this).datetimepicker({datepicker:false, format:'H:i'});
    });
  }
  
  //remove a hour
  function remove_hour(invoker)
  {
    $(invoker).closest('tr').remove();

  }

  ////////////////////////////////////////////////////////////
  //Pricing rules : distance related js
  ////////////////////////////////////////////////////////////
  function set_distance_data(data)
  {
    //9.15 remove prev lines first
    $('#distance_table tbody tr:not(:first)').remove();
    //

    let rs = data.split('_');
    if(rs.length == 0 || rs.length % 4 != 0)
    {
      return;
    }
    

    let i ;
    for(i = 0; i<rs.length/4; i++)
    {
      add_distance();
      $('#distance_table tr:last').find('.all_width').each( function (index) {
        $(this).val( rs[i*4 + index] );
      });
    }
    return rs.join("_");
  }
  //get distance
  function get_distance_data()
  {
    let rs = [];
    $('#distance_table tr .all_width').each(function(index){
      rs.push( $(this).val() );
    });
    return rs.join("_");
  }
  //add a distance
  function add_distance()
  {
    //test
    //  console.log(get_countries());
    //test end
    $('#distance_table tr:last').after("\
    <tr>\
    <td> <input type='text' class='all_width' > </td>\
    <td> <input type='text' class='all_width' >  </td>\
    <td> <input type='text' class='all_width' >  </td>\
    <td> <select  value='none' class='all_width form-control'> \
      <option value='none'> <?=_l('Not_Set') ?> </option>\
      <option value='18'> 18% </option>\
      <option value='19'> 19% </option>\
      <option value='20'> 20% </option>\
      <option value='21'> 21% </option>\
      <option value='22'> 22% </option>\
      <option value='23'> 23% </option>\
    </select> </td>\
    <td> <a href='#' onclick='remove_distance(this); '> Remove </a> </td>\
    </tr>");
    
  }
  //remove a hour
  function remove_distance(invoker)
  {
    $(invoker).closest('tr').remove();

  }


  ////////////////////////////////////////////////////////////
  //initialization related functions
  ////////////////////////////////////////////////////////////

  $(document).ready(function(){
    //set_switch_state('default_category_enable', 'true');
    //console.log(get_switch_state('default_category_enable') );

    //9.2 AG: code for customization
    //switch button
    $('.btn-toggle').click(function(e) {
      e.preventDefault();
      $(this).find('.btn').toggleClass('active'); 
      $(this).find('.btn').toggleClass('btn-primary');
      $(this).find('.btn').toggleClass('btn-default');
      //set value
      let val = $(this).find('.btn:first').hasClass('active');
      $(this).find('input').val ( val );

      //9.10 AG: if price type => show hide columns
      if( $(this).attr('name') == 'price_type_variable')
      {
        set_price_table_enable(val);

        let wse = get_wse();
    //console.log('wse', wse);
    return;
      }
    });

    //select 2 component for country select
    $("#attribute_countries").select2({
      maximumSelectionLength: 5
    });

    //9.11 AG: start, end time for weekdays
    $('#weekday_start_end input').datetimepicker({datepicker: false, format:'H:i'});
   
  
  });

  ////////////////////////////////////////////////////////////
  //Map related js scripts 9.12 AG: coded hard at night :)
  ////////////////////////////////////////////////////////////
  
  //add map
  var map_count = 0;
  var map_count_removed = 0;
  var maps = [];
  var center = {
    lat: 39.810866,
    lng: -104.990347
  };
  
  
  var circleOptions = {
    fillColor: "#000000",
    fillOpacity: 0.2,
    strokeColor: "#000000",
    strokeWeight: 4,
    strokeOpacity: 1,
    clickable: false,
    editable: true,
    suppressUndo: true,
    zIndex: 999
  };
  var polygonOptions = {
		editable: true,
		fillColor: "#000000",
        fillOpacity: 0.2,
		strokeColor: "#000000",
		strokeWeight: 4,
	    strokeOpacity: 1,
	    suppressUndo: true,
	    zIndex: 999
  };
  
  function addPolygon(map, data)
  {
    let paths = []; 
    for(let i = 0; i<data.length /2 ; i++ )
    {
      paths.push( { lat: parseFloat(data[i*2]) , lng: parseFloat(data[i*2 + 1]) });
    }
    const polygon = new google.maps.Polygon({
      paths: paths,
      strokeColor: "#000000",
      strokeOpacity: 1,
      strokeWeight: 4,
      fillColor: "#000000",
      fillOpacity: 0.2,
      editable: true,
      map: map
    });
      //drawing mode
    map.drawing_manager.setDrawingMode(null);
    map.drawing_manager.setOptions({
      drawingControl: false
    });
      //draw delete button
    onOverlayComplete( { type:'polygon', overlay: polygon} );
  }
  function addCircle(map, data)
  {
    let co = {
      fillColor: "#000000",
      fillOpacity: 0.2,
      strokeColor: "#000000",
      strokeWeight: 4,
      strokeOpacity: 1,
      clickable: false,
      editable: true,
      suppressUndo: true,
      zIndex: 999
    };
    let ccx = data[ 0 ];
    let ccy = data[ 1 ];
    let radius = data[ 2 ];
    co.radius = parseFloat(radius);
    let center = {
        lat: parseFloat(ccx),
        lng: parseFloat(ccy)
    };
    co.center = center;
    co.map = map;
    var areaCircle = new google.maps.Circle(co);
      //drawing mode
    map.drawing_manager.setDrawingMode(null);
    map.drawing_manager.setOptions({
      drawingControl: false
    });
      //draw delete button
    onOverlayComplete( { type:'circle', overlay: areaCircle} );
  }
  function set_map_data(as)
  {
    //remove all prev data
    $('#map_table tr.map_table_row:not(:first)').remove();
    map_count = 0;
    maps = [];
    map_count_removed = 0;
    ////////////////////////

    let areas = as.split('@');
    //console.log('areas', areas);
    //get count
    let count = areas.length; 
    if(count == 0 || count % 2 == 1) return;
    console.log('count', count);
    count /= 2;
    
    for(let j = 0; j< count; j++)
    {

       add_map();
       //left
       let data = areas[2*j].split("_");
       let type = data.shift();
       let zoom = data[0];
       let cx = data[1];
       let cy = data[2];

       let map = maps[ map_count - 2 ];
       map.setZoom( parseInt(zoom));
       map.setCenter(new google.maps.LatLng(cx, cy));
       data.splice(0, 3);
       //console.log('one item', data);
       if(type == 'circle')
        addCircle(map, data);
       else if(type == 'polygon')
        addPolygon(map, data);

      //right
       data = areas[2*j + 1].split("_");
       type = data.shift();
       zoom = data[0];
       cx = data[1];
       cy = data[2];

       map = maps[ map_count - 1 ];
       map.setZoom( parseInt(zoom));
       map.setCenter(new google.maps.LatLng(cx, cy));
       data.splice(0, 3);
       if(type == 'circle')
        addCircle(map, data);
       else if(type == 'polygon')
        addPolygon(map, data);  
    }
  }

   function get_map_data()
   {
     //console.log('maps', maps);
     let data = [];
     for(let i = 0; i< maps.length; i++)
     {
       let one = [];
       
       //console.log('zoom', maps[i].zoom);
      if(maps[i].sel_area == undefined)
      {
        one.push('none');
      }
      else if(maps[i].sel_area.type == 'circle')
      {
        one.push('circle');
      } else{
        one.push('polygon');
      }
      one.push(maps[i].zoom);
      one.push(maps[i].center.lat());
      one.push(maps[i].center.lng());
      //console.log('maps[i].sel_area.type', maps[i].sel_area.type);
      if(maps[i].sel_area == undefined)
       {
         one.push('');
         one.push('');
         one.push('');
       } else if(maps[i].sel_area.type == 'circle'){
        one.push( maps[i].sel_area.overlay.center.lat());
        one.push( maps[i].sel_area.overlay.center.lng());
        one.push( maps[i].sel_area.overlay.radius);
       } else{ //polygon
        const vs = maps[i].sel_area.overlay.getPath().i;
        for(let i = 0; i<vs.length; i++)
        {
          one.push( vs[i].lat() );
          one.push( vs[i].lng() );
        }

       }
       data.push(one.join('_'));
     }

     return data.join("@");
   }
   function add_map()
  {

    //add div tag
    $('#map_table tr.map_table_row:last').after("\
    <tr class='map_table_row'>\
    <td> <div type='text' class='map' id='map_src_" + map_count + "' > </div> </td>\
    <td> <div type='text' class='map' id='map_dest_" + map_count + "' > </div> </td>\
    <td class='map_table_action' > <a href='#' class='table_action_button' onclick='remove_map(this);' > Remove </a></td>\
    </tr>");
    //create map 
    var id_src = 'map_src_' + map_count;
    var id_dest = 'map_dest_' + map_count;
    let map = createMap( document.getElementById( id_src ));
    maps.push (map);
    
    map = createMap( document.getElementById( id_dest ));
    maps.push (map);
    //inc count 
    map_count += 2;
  }
  //remove map
  function remove_map(invoker)
  {

    let row = $(invoker).closest('tr.map_table_row');
    //console.log(row);
    //find index of row in table
    let ind = -1;
    $("#map_table tr.map_table_row:not(:first)").each(function(index){
      if( $(this)[0] == row[0]){
        ind = index;
      }
      //console.log( $(this) );
    });
    //console.log('ind',ind);
    //reorder map
    if(ind != -1){
      maps.splice(ind * 2 , 2);
      map_count_removed += 2;
      //console.log('remove', map_count);
    }
    //remove element
    row.remove();

  }
  $(document).ready(function(){
    //createMap( document.getElementById('map_src'));
    //createMap( document.getElementById('map_dest'));
  });
  
  
  function initMap() {
    
    //int delete button library
    initializeDeleteOverlayButtonLibrary();

  }
  function createMap(ele)
  {
    //set center, option
    let mapOptions = setInitialMapOptions(center);
    //create map, set filter_applied
    let map = new google.maps.Map( ele, mapOptions);
    map['filter_applied'] = false;
    //get drawing manager obejct
    let drawingManager = getDrawingManagerObject(map);
    map['drawing_manager'] = drawingManager;
    //add handler
    google.maps.event.addListener(drawingManager, 'overlaycomplete', onOverlayComplete);
    
    return map;

  }

  function setInitialMapOptions(center) {
    let mapOptions = {
      zoom: 4,
      center: center,
      mapTypeControl: false,
      panControl: true,
      panControlOptions: {
        position: google.maps.ControlPosition.RIGHT_CENTER
      },
      streetViewControl: false,
      scaleControl: false,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL,
        position: google.maps.ControlPosition.RIGHT_BOTTOM
      },
      minZoom: 2
    };
    return mapOptions;
  }
  function getDrawingManagerObject(map) {
    var drawingManager = new google.maps.drawing.DrawingManager({
      drawingMode: null,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT,
        drawingModes: [
          google.maps.drawing.OverlayType.CIRCLE,
          google.maps.drawing.OverlayType.POLYGON
        ]
      },
      circleOptions: circleOptions,
      polygonOptions: polygonOptions
    });
    drawingManager.setMap(map);
    return drawingManager;
  }

  function onOverlayComplete(shape) {
    //console.log('on overlay complete', shape);
    let map = this.map;
    if(map == undefined)  
      map = shape.overlay.map;
    map.sel_area = shape;

    addDeleteButtonToOverlay(shape);
    addOverlayListeners(shape);
    if (map.filter_applied) {
      map.filter_applied = false;
    }
    //draw only one, hide tool bar
    map.drawing_manager.setDrawingMode(null);
    map.drawing_manager.setOptions({
      drawingControl: false
    });
  }

  function addOverlayListeners(shape) {
    let map = this.map;
    if(map == undefined)  
      map = shape.overlay.map;

    // Filters already applied.
    if (map.filter_applied) {
      return;
    }
    //console.log(shape.overlay);
    if (shape.type == google.maps.drawing.OverlayType.CIRCLE) {
      google.maps.event.addListener(shape.overlay, 'center_changed', function() {
        map.filter_applied = true;
        //map.sel_area = shape.overlay;
        onOverlayComplete(shape);
      });
      google.maps.event.addListener(shape.overlay, 'radius_changed', function() {
        map.filter_applied = true;
        //map.sel_area = shape.overlay;
        onOverlayComplete(shape);
      });
    } 
    if (shape.type == google.maps.drawing.OverlayType.POLYGON) {
		  setBoundsChangedListener(shape);
  	}
    
  }
  function setBoundsChangedListener(shape) {
    let map = this.map;
    if(map == undefined)  
      map = shape.overlay.map;
    //console.log('shape', shape);
    // Add listeners for each path of the polygon.
    shape.overlay.getPaths().forEach(function(path, index){
    // New point
    google.maps.event.addListener(path, 'insert_at', function(){
      map.filter_applied = true;
      onOverlayComplete(shape);
    });
    // Point was removed
    google.maps.event.addListener(path, 'remove_at', function(){
      map.filter_applied = true;

      onOverlayComplete(shape);
    });
    // Point was moved
    google.maps.event.addListener(path, 'set_at', function(){
      map.filter_applied = true;
      onOverlayComplete(shape);
    });
	});
}

  function addDeleteButtonToOverlay(shape) {
    //console.log('shape' , shape);
    //console.log('shape overlay', shape.overlay);
    let map = this.map;
    if(map == undefined)  
      map = shape.overlay.map;

    var deleteOverlayButton = new DeleteOverlayButton();
    if (("deleteButton" in shape) && (shape.deleteButton != null)) {
      shape.deleteButton.div.remove();
      shape.deleteButton = deleteOverlayButton;
    } else {
      shape.deleteButton = deleteOverlayButton;
    }
    if (shape.type == google.maps.drawing.OverlayType.CIRCLE) {
      var radiusInKms = convertDistance(Math.round(shape.overlay.getRadius()), "metres", "kms");
      var circleCenter = new google.maps.LatLng(shape.overlay.getCenter().lat(), shape.overlay.getCenter().lng());
      var deleteOverlayButtonPosition = circleCenter.destinationPoint(30, radiusInKms);
      deleteOverlayButton.open(map, deleteOverlayButtonPosition, shape);
    } else if (shape.type == google.maps.drawing.OverlayType.POLYGON) 
    {
		  deleteOverlayButton.open(map, shape.overlay.getPath().getArray()[0], shape);
	  }
  }
  function DeleteOverlayButton() {
    this.div = document.createElement('div');
    this.div.id = 'deleteOverlayButton';
    this.div.className = 'deleteOverlayButton';
    this.div.title = 'Delete';
    this.div.innerHTML = '<span id="x">X</span>';
    var button = this;
    google.maps.event.addDomListener(this.div, 'click', function() {
      button.removeShape();
      button.div.remove();

      

    });

  }

  function initializeDeleteOverlayButtonLibrary() {

    /* This needs to be initialized by initMap() */
    DeleteOverlayButton.prototype = new google.maps.OverlayView();

    /**
     * Add component to map.
     */
    DeleteOverlayButton.prototype.onAdd = function() {
      var deleteOverlayButton = this;
      var map = this.getMap();
      this.getPanes().floatPane.appendChild(this.div);
    };

    /**
     * Clear data.
     */
    DeleteOverlayButton.prototype.onRemove = function() {


      google.maps.event.removeListener(this.divListener_);
      this.div.parentNode.removeChild(this.div);
      // Clear data
      this.set('position');
      this.set('overlay');


    };

    /**
     * Deletes an overlay.
     */
    DeleteOverlayButton.prototype.close = function() {

      this.setMap(null);

    };

    /**
     * Displays the Button at the position(in degrees) on the circle's circumference.
     */
    DeleteOverlayButton.prototype.draw = function() {
      var position = this.get('position');
      var projection = this.getProjection();
      if (!position || !projection) {
        return;
      }
      var point = projection.fromLatLngToDivPixel(position);
      this.div.style.top = point.y + 'px';
      this.div.style.left = point.x + 'px';
      if (this.get('overlay').type == google.maps.drawing.OverlayType.POLYGON) {
        this.div.style.marginTop = '-16px';
        this.div.style.marginLeft = '0px';
      }
    };

    /**
     * Displays the Button at the position(in degrees) on the circle's circumference.
     */
    DeleteOverlayButton.prototype.open = function(map, deleteOverlayButtonPosition, overlay) {
      this.set('position', deleteOverlayButtonPosition);
      this.set('overlay', overlay);
      this.setMap(map);
      this.draw();
    };

    /**
     * Deletes the shape it is associated with.
     */
    DeleteOverlayButton.prototype.removeShape = function() {
      //enable drawing mode 
      //console.log(this.getMap());
      this.getMap().drawing_manager.setOptions({
        drawingControl: true
      });
      //end
      var position = this.get('position');
      var shape = this.get('overlay');
      if (shape != null) {
        shape.overlay.setMap(null);
        return;
      }


      this.close();

      

    };

    Number.prototype.toRadians = function() {
      return this * Math.PI / 180;
    }

    Number.prototype.toDegrees = function() {
      return this * 180 / Math.PI;
    }

    /* Based the on the Latitude/Longitude spherical geodesy formulae & scripts
      at http://www.movable-type.co.uk/scripts/latlong.html
    */
    google.maps.LatLng.prototype.destinationPoint = function(bearing, distance) {
      distance = distance / 6371;
      bearing = bearing.toRadians();
      var latitude1 = this.lat().toRadians(),
        longitude1 = this.lng().toRadians();
      var latitude2 = Math.asin(Math.sin(latitude1) * Math.cos(distance) + Math.cos(latitude1) * Math.sin(distance) * Math.cos(bearing));
      var longitude2 = longitude1 + Math.atan2(Math.sin(bearing) * Math.sin(distance) * Math.cos(latitude1), Math.cos(distance) - Math.sin(latitude1) * Math.sin(latitude2));
      if (isNaN(latitude2) || isNaN(longitude2)) return null;
      return new google.maps.LatLng(latitude2.toDegrees(), longitude2.toDegrees());
    }
  }

  function convertDistance(distanceValue, actualDistanceUnit, expectedDistanceUnit) {
  var distanceInKms = 0;
  switch (actualDistanceUnit) {
    case "miles":
      distanceInKms = distanceValue / 0.62137;
      break;
    case "kms":
      distanceInKms = distanceValue;
      break;
    case "metres":
      distanceInKms = distanceValue / 1000;
      break;
    default:
      distanceInKms = undefined;
  }

  switch (expectedDistanceUnit) {
    case "miles":
      return distanceInKms * 0.62137;
    case "kms":
      return distanceInKms;
    case "metres":
      return distanceInKms * 1000;
    default:
      return undefined;
  }
}
  
</script>

