<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <a href="<?php echo admin_url('commission/new_commission_policy'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
          <a href="#" onclick="recalculate_modal(); return false;" class="btn btn-info mbot10"><?php echo _l('recalculate'); ?></a>
          <i class="fa fa-question-circle recalculate_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('recalculate_tooltip'); ?>"></i>
          <div class="row">
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
          <table class="table table-commission-policy">
            <thead>
              <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('commission_policy_type'); ?></th>
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
<div class="modal fade" id="recalculate_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('recalculate'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/commission/recalculate',array('id'=>'recalculate-modal')); ?>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                  <div class="form-group">
                    <div class="checkbox checkbox-primary">
                      <input type="checkbox" name="recalculate_the_old_invoice" id="recalculate_the_old_invoice" value="1">
                      <label for="recalculate_the_old_invoice"><i class="fa fa-question-circle" data-toggle="tooltip" title="" data-original-title="<?php echo _l('recalculate_the_old_invoice_tooltip'); ?>"></i> <?php echo _l('recalculate_the_old_invoice'); ?></label>
                    </div>
                  </div>
                    <?php echo render_select('invoice[]', $invoices, array('id', 'name'), 'invoice', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/commission/assets/js/manage_commission_policy_js.php';?>