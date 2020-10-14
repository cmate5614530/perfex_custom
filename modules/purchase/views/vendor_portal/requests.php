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
      </div>
  </div>
  <hr />
  <div class="clearfix"></div>
  <?php get_template_part_pur('requests_table'); ?>
</div>
</div>
</div>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>

