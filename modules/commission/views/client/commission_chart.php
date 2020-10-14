<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="commission-chart" class="hide">
	<div class="row">
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