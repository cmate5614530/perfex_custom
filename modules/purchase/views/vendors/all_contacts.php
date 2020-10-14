<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php if(isset($consent_purposes)) { ?>
            <div class="row mbot15">
              <div class="col-md-3 contacts-filter-column">
               <div class="select-placeholder">
                <select name="custom_view" title="<?php echo _l('gdpr_consent'); ?>" id="custom_view" class="selectpicker" data-width="100%">
                 <option value=""></option>
                 <?php foreach($consent_purposes as $purpose) { ?>
                 <option value="consent_<?php echo html_entity_decode($purpose['id']); ?>">
                  <?php echo html_entity_decode($purpose['name']); ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        <?php
        $table_data = array(_l('client_firstname'),_l('client_lastname'));
        if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){
         array_push($table_data, array(
          'name'=>_l('gdpr_consent') .' ('._l('gdpr_short').')',
          'th_attrs'=>array('id'=>'th-consent', 'class'=>'not-export')
        ));
       }
       $table_data = array_merge($table_data, array(
        _l('client_email'),
        _l('clients_list_company'),
        _l('client_phonenumber'),
        _l('contact_position'),
        _l('contact_active'),
      ));
       $custom_fields = get_custom_fields('contacts',array('show_on_table'=>1));
       foreach($custom_fields as $field){
        array_push($table_data,$field['name']);
      }
      render_datatable($table_data,'all-contacts');
      ?>
    </div>
  </div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<div id="contact_data"></div>
<div id="consent_data"></div>
<?php require 'modules/purchase/assets/js/vendor_js.php';?>
</body>
</html>
