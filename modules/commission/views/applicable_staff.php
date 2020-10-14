<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">

          <?php echo form_open($this->uri->uri_string(),array('id'=>'applicable-staff-form','autocomplete'=>'off')); ?>
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">
            <div class="col-md-12">
            <?php echo render_select('commission_policy', $commission_policy,array('id','name'),'commission_policy'); ?>
            </div>
            <div class="col-md-12">
              <?php if($is_client == 1){
                echo form_hidden('is_client', 1);
                echo render_select('applicable_staff[]',$clients,array('userid','company'),'applicable_client','',array('multiple'=>true,'data-actions-box'=>true),array(),'','',false);
              }else{
                echo render_select('applicable_staff[]',$staffs,array('staffid',array('firstname','lastname')),'applicable_staff','',array('multiple'=>true,'data-actions-box'=>true),array(),'','',false);
              } ?>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12" >
              <h4 class="font-bold"><?php if($is_client == 1){ echo _l('list_applicable_client'); }else{ echo _l('list_applicable_staff'); } ?></h4>
                <div id="list_applicable_staff" class="list-group">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">    
              <div class="modal-footer">
                <button type="submit" class="btn btn-info commission-policy-form-submiter"><?php echo _l('submit'); ?></button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/commission/assets/js/applicable_staff_js.php';?>