<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>
<script>
var hidden_columns = [3,7];
    
  console.log('item_list_supplier_js.php start');
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
   console.log("init data table");
    table_item_list = $('table.table-table_item_list');
    _table_api = initDataTable(table_item_list, site_url+'purchase/vendors_portal/table_item_list', [0], [0], '',  [1, 'desc']);
    //_table_api = initDataTable(table_item_list, site_url+'purchase/vendors_portal/quotation_form', [0], [0], '',  [1, 'desc']);
    //_table_api = initDataTable(table_item_list, admin_url+'purchase/table_item_list', [0], [0], '',  [1, 'desc']);
   

  init_commodity_detail();
  function init_commodity_detail(id) {
    load_small_table_item_proposal(id, '#proposal_sm_view', 'item_id', 'purchase/vendors_portal/get_item_data_ajax', '.proposal_sm');
  }

  //9.9 AG: //modified function : changed url from 'admin purchase' -> 'site url, vendors_portal'
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
    $(selector).load(site_url + url + '/' + pr_id);

  //  console.log('load url', site_url + url + '/' + pr_id); 

    if (is_mobile()) {
        $('html, body').animate({
            scrollTop: $(selector).offset().top + 150
        }, 600);
    }
}

function toggle_small_view_proposal(table, main_data) {
  "use strict";
    console.log('toggle_small_view_proposal');

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
      
      $.post(form.action, $(form).serialize()).done(function(response) {
        response = JSON.parse(response);

        if (response.commodityid) {
         if(typeof(expenseDropzone) !== 'undefined'){
          if (expenseDropzone.getQueuedFiles().length > 0) {
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
  $('#commodity_list-add-edit').modal('show');
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
      $('#commodity_list-add-edit input[name="extra_time_min"]').val($(invoker).data('extra_time_min'));
      $('#commodity_list-add-edit input[name="extra_time_max"]').val($(invoker).data('extra_time_max'));
      $('#commodity_list-add-edit input[name="extra_time_step"]').val($(invoker).data('extra_time_step'));
      $('#commodity_list-add-edit input[name="group_id"]').val($(invoker).data('group_id'));
      $('#commodity_list-add-edit input[name="google_calendar_id"]').val($(invoker).data('google_calendar_id'));
      $('#commodity_list-add-edit textarea[name="google_calendar_settings"]').val($(invoker).data('google_calendar_settings'));

      //set switch values
      set_switch_state("default_category_enable", $(invoker).data('default_category_enable') );
      set_switch_state("extra_time_enable", $(invoker).data('extra_time_enable') );
      set_switch_state("price_type_variable", $(invoker).data('price_type_variable') );
      set_switch_state("vehicle_availability_enable", $(invoker).data('vehicle_availability_enable') );
      set_switch_state("google_calendar_enable", $(invoker).data('google_calendar_enable') );

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
      //9.9 AG: add csrf part
      var token_name =  csrfData['token_name'];
      var token_data = csrfData['hash'];
        $.post(site_url + 'purchase/vendors_portal/get_commodity_file_url/'+$(invoker).data('commodity_id'),
        {'csrf_token_name': token_data}).done(function(response) {
            response = JSON.parse(response);

            $('#images_old_preview').empty();

            if(response !=''){
              $('#images_old_preview').prepend(response.arr_images);

            }


        });


  }

  function new_commodity_item(){
    "use strict";

    // $.post(admin_url + 'purchase/get_commodity_barcode').done(function(response) {
    //   response = JSON.parse(response);
    //   $('#commodity_list-add-edit input[name="commodity_barcode"]').val(response);
    // });
    $('#commodity_list-add-edit').modal('show');

        $('#commodity_item_id').empty();

        $('.edit-commodity-title').addClass('hide');
        $('.add-commodity-title').removeClass('hide');

        $('.dropzone-previews').empty();
        $('#images_old_preview').empty();

      /*empty form*/
      $('#commodity_list-add-edit input[name="commodity_code"]').val('');
      
      $('#commodity_list-add-edit input[name="description"]').val('');
      $('#commodity_list-add-edit input[name="sku_code"]').val('');
      $('#commodity_list-add-edit input[name="sku_name"]').val('');
      $('#commodity_list-add-edit input[name="purchase_price"]').val('');

      $('#commodity_list-add-edit select[name="unit_id"]').val('').change();
      $('#commodity_list-add-edit select[name="commodity_type"]').val('').change();
      $('#commodity_list-add-edit select[name="group_id"]').val('').change();
      $('#commodity_list-add-edit select[name="purchase_id"]').val('').change();
      $('#commodity_list-add-edit select[name="tax"]').val('').change();
      $('#commodity_list-add-edit select[name="sub_group"]').val('').change();

      $('#commodity_list-add-edit input[name="origin"]').val('');
      $('#commodity_list-add-edit input[name="rate"]').val('');
      $('#commodity_list-add-edit input[name="type_product"]').val('');

      $('#commodity_list-add-edit select[name="style_id"]').val('').change();
      $('#commodity_list-add-edit select[name="model_id"]').val('').change();
      $('#commodity_list-add-edit select[name="size_id"]').val('').change();

      /*ngay sx, hsd*/
      $('#commodity_list-add-edit input[name="date_manufacture"]').val('').change();
      $('#commodity_list-add-edit input[name="expiry_date"]').val('').change();
     


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

  //get driver lang
  function get_driver_lang()
  {
    let items = $('.lang-select .items');
    let selected = [];
    //$('.lang-select .items').each().find('label').
    for (i in items)
    {
      //console.log(items[i].attr('lang'));
      //console.log(items[i]);
      // if(items[i].find('label').hasClass('active'))
      // {
      //   selected.push( items[i].attr('lang') ); 
      // }
    }
    console.log(selected);
  }
 
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
      //$(this).find('input').val ( $(this).find('.btn:first').hasClass('active') );
    });

    //select 2 component for country select
    $("#attribute_countries").select2({
      maximumSelectionLength: 5
    });

   
 
  
  });

  //9.3 AG: google map
  var map_src = null, var_dest = null;
  function initialize() {
    // The location of Uluru
    var uluru = {lat: -25.344, lng: 131.036};

    // The map, centered at Uluru
    map_src = new google.maps.Map(
    document.getElementById('map_src'), {
      zoom: 8, 
      center: uluru,
      draggableCursor:'pointer'
    });

    map_dest = new google.maps.Map(
    document.getElementById('map_dest'), {
      zoom: 8, 
      center: uluru,
      draggableCursor:'pointer'
    });

    var circle_src =null, circle_dest = null;
    
    //mouse up
    google.maps.event.addListener(map_src, 'mouseup', function(e) {            
        map.setOptions({draggable:true, draggableCursor:''}); //allow map dragging after the circle was already created 
        the_circle.setOptions({clickable:true});
    });
    google.maps.event.addListener(map_dest, 'mouseup', function(e) {            
        map.setOptions({draggable:true, draggableCursor:''}); //allow map dragging after the circle was already created 
        the_circle.setOptions({clickable:true});
    });
    
    //mouse down
    google.maps.event.addListenerOnce(map_src, 'mousedown', function (mousedown_event) {
      console.log('mouse down');
      console.log(mousedown_event.latLng);
      //var radius = google.maps.geometry.spherical.computeDistanceBetween(uluru, mousedown_event.latLng); //get distance in meters between our static position and clicked position, which is the radius of the circle
       var radius = 50000; //get distance in meters between our static position and clicked position, which is the radius of the circle
      the_circle = createCircle(map_src, mousedown_event.latLng, radius); //create circle with center in our static position and our radius
    });

    google.maps.event.addListenerOnce(map_dest, 'mousedown', function (mousedown_event) {
      console.log('mouse down');
      console.log(mousedown_event.latLng);
      //var radius = google.maps.geometry.spherical.computeDistanceBetween(uluru, mousedown_event.latLng); //get distance in meters between our static position and clicked position, which is the radius of the circle
       var radius = 50000; //get distance in meters between our static position and clicked position, which is the radius of the circle
      the_circle = createCircle(map_dest, mousedown_event.latLng, radius); //create circle with center in our static position and our radius
    });

  }
  function initMap(){
    google.maps.event.addDomListener(window, 'load', initialize);
  }

  function createCircle(map,center, radius) {
    var circle = new google.maps.Circle({
        fillColor: '#ffffff',
        fillOpacity: .1,
        strokeWeight: 2,
        strokeColor: '#000000',
        draggable: true,
        editable: true,
        map: map,
        center: center,
        radius: radius,
        clickable:false
    });

    google.maps.event.addListener(circle, 'radius_changed', function (event) {
        console.log('circle radius changed');
    });

    google.maps.event.addListener(circle, 'center_changed', function (event) {
        //if(circle.getCenter().toString() !== center.toString()) circle.setCenter(center);
    });
	
    return circle;
  }

  
</script>

