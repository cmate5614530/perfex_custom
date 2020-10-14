<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s section-heading section-tickets">
  <div class="panel-body">
    <h4 class="no-margin section-text"><?php echo _l('Requests'); ?></h4>
  </div>
</div>  
<div class="panel_s">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-success pull-left no-mtop tickets-summary-heading"><?php echo _l('Requests_Summary'); ?></h3>
        <a href="<?php echo site_url('clients/new_request'); ?>" class="btn btn-info new-ticket pull-right">
          <?php echo _l('New_Request'); ?>
        </a>
        <div class="clearfix"></div>
        <hr />
      </div>
  </div>
  <div class="clearfix"></div>
  <hr />
  <div class="clearfix"></div>
  <?php get_template_part('requests_table'); ?>
</div>
</div>
</div>
