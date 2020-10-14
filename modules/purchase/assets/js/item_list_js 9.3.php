<!-- <script defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIABcm7Pk9jAIg7opit0NWdSCJzoBz95k&callback=initMap">   </script> -->
<script>
var hidden_columns = [3,7];
    

  $(function(){
  "use strict";

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
        'commodity_code': 'required',
        'commodity_barcode': 'required',
        'unit_id': 'required',
        'purchase_id': 'required',
        'commodity_type': 'required',
        'rate': 'required',
        'sku_code': 'required',
        'sku_name': 'required',
        // 'tax': 'required',

    },expenseSubmitHandler);


     $('select[name="group_id"]').on('change',function(){

      var data_select = {};
        data_select.group_id = $('select[name="group_id"]').val();


          $.post(admin_url + 'purchase/get_subgroup_fill_data',data_select).done(function(response){
             response = JSON.parse(response);
             $("select[name='sub_group']").html('');
             
             $("select[name='sub_group']").append(response.subgroup);
             $("select[name='sub_group']").selectpicker('refresh');

           });

    });


   });

     
    table_item_list = $('table.table-table_item_list');
    _table_api = initDataTable(table_item_list, admin_url+'purchase/table_item_list', [0], [0], '',  [1, 'desc']);


  init_commodity_detail();
  function init_commodity_detail(id) {
    load_small_table_item_proposal(id, '#proposal_sm_view', 'item_id', 'purchase/get_item_data_ajax', '.proposal_sm');
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
    $(selector).load(admin_url + url + '/' + pr_id);
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

      $.post(form.action, $(form).serialize()).done(function(response) {

        response = JSON.parse(response);

        if (response.commodityid) {
         if(typeof(expenseDropzone) !== 'undefined'){
          if (expenseDropzone.getQueuedFiles().length > 0) {
            expenseDropzone.options.url = admin_url + 'purchase/add_commodity_attachment/' + response.commodityid;
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


      $('#commodity_list-add-edit input[name="commodity_code"]').val($(invoker).data('commodity_code'));
      $('#commodity_list-add-edit input[name="commodity_barcode"]').val($(invoker).data('commodity_barcode'));
      $('#commodity_list-add-edit input[name="description"]').val($(invoker).data('description'));

      $('#commodity_list-add-edit input[name="sku_code"]').val($(invoker).data('sku_code'));
      $('#commodity_list-add-edit input[name="sku_name"]').val($(invoker).data('sku_name'));
      $('#commodity_list-add-edit input[name="purchase_price"]').val($(invoker).data('purchase_price'));

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
        $.post(admin_url + 'purchase/get_commodity_file_url/'+$(invoker).data('commodity_id')).done(function(response) {
            response = JSON.parse(response);

            $('#images_old_preview').empty();

            if(response !=''){
              $('#images_old_preview').prepend(response.arr_images);

            }


        });


  }

  function new_commodity_item(){
    "use strict";

    $.post(admin_url + 'purchase/get_commodity_barcode').done(function(response) {
      response = JSON.parse(response);
      $('#commodity_list-add-edit input[name="commodity_barcode"]').val(response);
    });
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
  $(document).ready(function(){
    //9.2 AG: code for customization
    //switch button
    $('.btn-toggle').click(function(e) {
      e.preventDefault();
      $(this).find('.btn').toggleClass('active'); 
      if ($(this).find('.btn-primary').size()>0) {
        $(this).find('.btn').toggleClass('btn-primary');
      }
      $(this).find('.btn').toggleClass('btn-default');
    });

    //select 2 component for country select
    $("#attribute_countries").select2({
      maximumSelectionLength: 5
    });

 
  
  });

  //9.3 AG: google map
  var map = null;
  function initialize() {
    // The location of Uluru
    var uluru = {lat: -25.344, lng: 131.036};
    // The map, centered at Uluru
    map = new google.maps.Map(
        document.getElementById('map'), {
          zoom: 4, 
          center: uluru,
          draggableCursor:'pointer'
        });
    // The marker, positioned at Uluru
    // var marker = new google.maps.Marker({position: uluru, map: map});

    var mousemove_handler;
    var the_circle = null;
  
    google.maps.event.addListener(map, 'mouseup', function(e) {            
        if(mousemove_handler) google.maps.event.removeListener(mousemove_handler);
        map.setOptions({draggable:true, draggableCursor:''}); //allow map dragging after the circle was already created 
        the_circle.setOptions({clickable:true});
    });
    
    google.maps.event.addListenerOnce(map, 'mousedown', function (mousedown_event) {
      console.log('mouse down');
      //var radius = google.maps.geometry.spherical.computeDistanceBetween(uluru, mousedown_event.latLng); //get distance in meters between our static position and clicked position, which is the radius of the circle
       var radius = 50000; //get distance in meters between our static position and clicked position, which is the radius of the circle
      the_circle = createCircle(uluru, radius); //create circle with center in our static position and our radius
      mousemove_handler = google.maps.event.addListener(map, 'mousemove', function(mousemove_event) { //if after mousedown user starts dragging mouse, let's update the radius of the new circle
        var new_radius = google.maps.geometry.spherical.computeDistanceBetween(static_position, mousemove_event.latLng);
        console.log('mouse move');
        the_circle.setOptions({radius:new_radius}); 
	    });
    });

  }
  function initMap(){
    google.maps.event.addDomListener(window, 'load', initialize);
  }

  function createCircle(center, radius) {

    var circle = new google.maps.Circle({
        fillColor: '#ffffff',
        fillOpacity: .6,
        strokeWeight: 1,
        strokeColor: '#ff0000',
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

