<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <?php if($is_client == 1){ ?>
            <a href="<?php echo admin_url('commission/new_applicable_client'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
          <?php }else{ ?>
            <a href="<?php echo admin_url('commission/new_applicable_staff'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
          <?php } ?>
          <div class="row">
            <div class="col-md-3">
              <?php echo form_hidden('is_client', $is_client); ?>
              <?php if($is_client == 1){
                echo render_select('staff_filter', $clients, array('userid', 'company'), 'client', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
              }else{
                echo render_select('staff_filter', $staffs, array('staffid', 'firstname', 'lastname'), 'staff', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
              } ?>
            </div>
            <div class="col-md-3">
              <?php $commission_policy_type = [ 0 => ['id' => '1', 'name' => _l('calculated_as_ladder')],
                                              1 => ['id' => '2', 'name' => _l('calculated_as_percentage')],
                                              2 => ['id' => '3', 'name' => _l('calculated_by_the_product')]];
              echo render_select('commission_policy_type', $commission_policy_type, array('id', 'name'), 'commission_policy_type', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_date_input('from_date','from_date'); ?>
              </div>
            <div class="col-md-3">
              <?php echo render_date_input('to_date','to_date'); ?>
            </div>
            <div class="clearfix"></div>
          </div>
          <table class="table table-applicable-staff">
            <thead>
              <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('commission_policy'); ?></th>
              <th><?php echo _l('from_date'); ?></th>
              <th><?php echo _l('to_date'); ?></th>
              <th><?php echo _l('options'); ?></th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
