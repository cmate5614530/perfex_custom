<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="row content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php ?>
            <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
            <hr />
            <a href="<?php echo admin_url('purchase/new_vendor_items'); ?>" class="btn btn-info mbot10"><?php echo _l('new'); ?></a>
            <div class="row">
              <div class="col-md-3">
                <?php echo render_select('vendor_filter', $vendors, array('userid', 'company'), 'vendors', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
              <div class="col-md-3">
                <?php 
                echo render_select('group_items_filter', $commodity_groups, array('id','name'), 'group_item', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
              <div class="col-md-3">
                <?php 
                echo render_select('items_filter', $items, array('id', 'vehicle_make'), 'items', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              </div>
              
              <div class="clearfix"></div>
            </div>
            <table class="table table-vendor-items">
              <thead>
                <th><?php echo _l('vendors'); ?></th>
                <th><?php echo _l('items'); ?></th>
                <th><?php echo _l('date_create'); ?></th>
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
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/manage_vendor_items_js.php';?>