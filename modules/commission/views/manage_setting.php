<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-3">
        <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
          <?php
          $i = 0;
          foreach($tab as $gr){
            ?>
            <li<?php if($i == 0){echo " class='active'"; } ?>>
            <a href="<?php echo admin_url('commission/setting?group='.$gr); ?>" data-group="<?php echo html_entity_decode($gr); ?>">

              <?php if($gr == 'commission' ){
                echo _l('_warehouse');

              }elseif($gr == 'rule_sale_price'){
                echo _l('rule_sale_price_export_type');
              }else{
                echo _l($gr);
              }
               ?>
                
              </a>
            </li>
          <?php $i++; } ?>
        </ul>
      </div>
      <div class="col-md-9">
        <div class="panel_s">
          <div class="panel-body">
            <?php $this->load->view($tabs['view']); ?>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
