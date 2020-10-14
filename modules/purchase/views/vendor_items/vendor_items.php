<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="row content">
    <div class="row">
      <div class="col-md-6">
        <div class="panel_s">
          <div class="panel-body">

            <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'applicable-staff-form','autocomplete'=>'off')); ?>
            <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <label for="vendor"><?php echo _l('vendor'); ?></label>
                    <select name="vendor" id="vendor" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                      <option value=""></option>
                        <?php foreach($vendors as $s) { ?>
                        <option value="<?php echo html_entity_decode($s['userid']); ?>" ><?php echo html_entity_decode($s['company']); ?></option>
                          <?php } ?>
                    </select>  
                    <br><br>
              </div>

               <div class="col-md-6">
                  <label for="group_item"><?php echo _l('group_item'); ?></label>
                    <select name="group_item" id="group_item" class="selectpicker" onchange="group_it_change(this); return false;" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('all'); ?>" >
                      <option value=""></option>
                        <?php foreach($commodity_groups as $s) {  ?>
                        <option value="<?php echo html_entity_decode($s['id']); ?>" ><?php echo html_entity_decode($s['name']); ?></option>
                          <?php } ?>
                    </select>  
                    <br>
                </div>
              <div class="col-md-6 form-group">
                <label for="items"><?php echo _l('items'); ?></label>
                    <select name="items[]" id="items" class="selectpicker" data-live-search="true" multiple data-width="100%" required data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                        <?php foreach($items as $s) { ?>
                        <option value="<?php echo html_entity_decode($s['id']); ?>">
                          <?php echo html_entity_decode($s['vehicle_make']).' - '.html_entity_decode($s['vehicle_model']); ?>
                        </option>
                          <?php } ?>
                    </select>
                    <br> 
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
</div>
<?php init_tail(); ?>
</body>
</html>
