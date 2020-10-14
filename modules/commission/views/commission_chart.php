<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="commission-chart" class="hide">
	<div class="row">
      <div class="col-md-4" id="div_staff_filter_chart">
         <?php echo render_select('staff_filter_chart', $staffs, array('staffid', 'firstname', 'lastname'), 'staff', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
      </div>
      <div class="col-md-4" id="div_client_filter_chart">
         <?php echo render_select('client_filter_chart', $clients, array('userid', 'company'), 'client', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
   		</div>
   		<div class="col-md-4">
         <?php echo render_select('products_services_chart', $products, array('id', 'label'), 'products_services', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
         </div>
   		<div class="clearfix"></div>
	</div>
   <div class="row">
     <figure class="highcharts-figure col-md-12">
       <div id="commission_chart"></div>
       
      </figure>
   </div>
</div>