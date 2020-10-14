<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('commission'); ?>">
<div class="panel_s user-data">
  <div class="panel-body">
    <div class="widget-dragger"></div>
    <div class="row">
      <div class="col-md-12">
         <p class="text-dark text-uppercase bold"><?php echo _l('commission'); ?></p>
         <hr>
      </div>
      <div class="col-lg-6 col-xs-12 col-md-12 total-column">
      <div class="panel_s">
         <div class="panel-body">
            <h3 class="text-muted _total">
               <?php
               $this->load->model('currencies_model');
               $currency = $this->currencies_model->get_base_currency(); 
                echo app_format_money(get_commission(get_staff_user_id()), $currency->name); ?>
            </h3>
            <span class="text-warning"><?php echo _l('total'); ?></span>
         </div>
      </div>
   </div>
      <div class="col-lg-6 col-xs-12 col-md-12 total-column">
        <div class="panel_s">
           <div class="panel-body">
              <h3 class="text-muted _total">
                 <?php echo app_format_money(get_commission(get_staff_user_id(), true), $currency->name); ?>
              </h3>
              <span class="text-success"><?php echo _l('commission_in_month'); ?></span>
           </div>
        </div>
      </div>
      <div id="commission-chart">
        <div class="row">
          <figure class="highcharts-figure col-md-12">
            <div id="commission_chart"></div>
          </figure>
        </div>
      </div>
     </div>
    </div>
  </div>
</div>
