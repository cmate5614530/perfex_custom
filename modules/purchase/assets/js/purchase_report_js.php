<script>
var report_import_goods, 
report_from_choose, 
fnServerParams, 
statistics_number_of_purchase_orders, 
statistics_cost_of_purchase_orders;
 var report_from = $('input[name="report-from"]');
 var report_to = $('input[name="report-to"]');
  var date_range = $('#date-range');
(function($) {
  "use strict";

  report_import_goods = $('#list_import_goods');
  statistics_number_of_purchase_orders = $('#number-purchase-orders-report');
  statistics_cost_of_purchase_orders = $('#cost-purchase-orders-report');
  report_from_choose = $('#report-time');
  fnServerParams = {
    "products_services": '[name="products_services"]',
    "report_months": '[name="months-report"]',
    "report_from": '[name="report-from"]',
    "report_to": '[name="report-to"]',
    "year_requisition": "[name='year_requisition']",
  }
  
  $('select[name="products_services"]').on('change', function() {
    gen_reports();
  });

  $('select[name="months-report"]').on('change', function() {
    if($(this).val() != 'custom'){
     gen_reports();
    }
   });

   $('select[name="year_requisition"]').on('change', function() {
     gen_reports();
   });

   report_from.on('change', function() {
     var val = $(this).val();
     var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         gen_reports();
       }
     } else {
       report_to.attr('disabled', true);
     }
   });

   report_to.on('change', function() {
     var val = $(this).val();
     if (val != '') {
       gen_reports();
     }
   });

   $('.table-import-goods-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total);
   });

   $('select[name="months-report"]').on('change', function() {
     var val = $(this).val();
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       date_range.addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!date_range.hasClass('hide')) {
         date_range.removeClass('fadeIn').addClass('hide');
       }
     }
     gen_reports();
   });
})(jQuery);


 function init_report(e, type) {
  "use strict";

   var report_wrapper = $('#report');

   if (report_wrapper.hasClass('hide')) {
        report_wrapper.removeClass('hide');
   }

   $('head title').html($(e).text());
   

   report_from_choose.addClass('hide');

   $('#year_requisition').addClass('hide');

   report_import_goods.addClass('hide');
  statistics_cost_of_purchase_orders.addClass('hide');
  statistics_number_of_purchase_orders.addClass('hide');

  $('select[name="months-report"]').selectpicker('val', 'this_month');
    // Clear custom date picker
      $('#currency').removeClass('hide');

      if (type != 'statistics_number_of_purchase_orders' && type != 'statistics_cost_of_purchase_orders') {
        report_from_choose.removeClass('hide');
      }
      if (type == 'list_import_goods') {
        report_import_goods.removeClass('hide');
      }else if(type == 'statistics_number_of_purchase_orders'){
        statistics_number_of_purchase_orders.removeClass('hide');
        $('#year_requisition').removeClass('hide');
      }else if(type == 'statistics_cost_of_purchase_orders'){
        statistics_cost_of_purchase_orders.removeClass('hide');
        $('#year_requisition').removeClass('hide');
      }

      gen_reports();
}


function import_goods_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-import-goods-report')) {
   $('.table-import-goods-report').DataTable().destroy();
 }
 initDataTable('.table-import-goods-report', admin_url + 'purchase/import_goods_report', false, false, fnServerParams);
}

function number_of_purchase_orders_analysis() {
  "use strict";

  var data = {};
   data.year = $('select[name="year_requisition"]').val();
  $.post(admin_url + 'purchase/number_of_purchase_orders_analysis/', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('container_number_purchase_orders', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('number_of_purchase_orders') ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('month_1') ?>',
                '<?php echo _l('month_2') ?>',
                '<?php echo _l('month_3') ?>',
                '<?php echo _l('month_4') ?>',
                '<?php echo _l('month_5') ?>',
                '<?php echo _l('month_6') ?>',
                '<?php echo _l('month_7') ?>',
                '<?php echo _l('month_8') ?>',
                '<?php echo _l('month_9') ?>',
                '<?php echo _l('month_10') ?>',
                '<?php echo _l('month_11') ?>',
                '<?php echo _l('month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: ''
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr><td>{series.name}: </td>' +
                 '<td><b>{point.y:.0f}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: '<?php echo _l('quantity') ?>',
            data: response,
            showInLegend: false
         }]
     });
        
  })
}

function cost_of_purchase_orders_analysis() {
  "use strict";

  var data = {};
   data.year = $('select[name="year_requisition"]').val();
  $.post(admin_url + 'purchase/cost_of_purchase_orders_analysis', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('container_cost_purchase_orders', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('cost_of_purchase_orders') ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('month_1') ?>',
                '<?php echo _l('month_2') ?>',
                '<?php echo _l('month_3') ?>',
                '<?php echo _l('month_4') ?>',
                '<?php echo _l('month_5') ?>',
                '<?php echo _l('month_6') ?>',
                '<?php echo _l('month_7') ?>',
                '<?php echo _l('month_8') ?>',
                '<?php echo _l('month_9') ?>',
                '<?php echo _l('month_10') ?>',
                '<?php echo _l('month_11') ?>',
                '<?php echo _l('month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span >{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false,
         }]
     });
        
  })
}

// Main generate report function
function gen_reports() {
  "use strict";

 if (!report_import_goods.hasClass('hide')) {
   import_goods_report();
 }else if (!statistics_number_of_purchase_orders.hasClass('hide')) {
    number_of_purchase_orders_analysis();
 }else if (!statistics_cost_of_purchase_orders.hasClass('hide')) {
    cost_of_purchase_orders_analysis();
 }
}
</script>


