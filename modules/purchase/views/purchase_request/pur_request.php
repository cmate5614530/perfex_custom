<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="customer-profile-group-heading"><?php echo _l($title). ' '._l('purchase_request') ; ?></h4>
                  <?php if(isset($pur_request)){
                           echo form_open(admin_url('purchase/pur_request/'.$pur_request->id),array('id'=>'add_edit_pur_request-form'));
                        }else{
                           echo form_open(admin_url('purchase/pur_request'),array('id'=>'add_edit_pur_request-form'));
                        }?>
                <div class="row">
                  <div class="col-md-12">
                    <p class="bold p_style" ><?php echo _l('information'); ?></p>
                    <hr class="hr_style"  />
                  </div>

                  <div class=" col-md-7">
                  <div class="panel panel-success">
                    <div class="panel-heading"><?php echo _l('other_infor'); ?></div>
                    <div class="panel-body">
                      <div class="col-md-4">
                      <?php $pur_rq_code = ( isset($pur_request) ? $pur_request->pur_rq_code : '');
                      echo render_input('pur_rq_code','pur_rq_code',$pur_rq_code ); ?>
                    </div>
                    <div class="col-md-8">
                      <?php $pur_rq_name = ( isset($pur_request) ? $pur_request->pur_rq_name : '');
                      echo render_input('pur_rq_name','pur_rq_name', $pur_rq_name); ?>
                    </div>
                    <div class="col-md-12 form-group">
                      <label for="department"><?php echo _l('department'); ?></label>
                        <select name="department" id="department" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                          <option value=""></option>
                          <?php foreach($departments as $s) { ?>
                            <option value="<?php echo html_entity_decode($s['departmentid']); ?>" <?php if(isset($pur_request) && $s['departmentid'] == $pur_request->department){ echo 'selected'; } ?>><?php echo html_entity_decode($s['name']); ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                    </div>
                    <div class="col-md-12">
                      <?php $rq_description = ( isset($pur_request) ? $pur_request->rq_description : '');
                      echo render_textarea('rq_description','rq_description',$rq_description); ?>
                    </div>
                    </div>
                  </div>
                  </div>

                  <div class="col-md-5">
                   <div class="panel panel-success">
                    <div class="panel-heading"><?php echo _l('general_infor'); ?></div>
                    <div class="panel-body">
                    <table class="table border table-striped mtop0" >
                    <tbody>
                       <tr class="project-overview">
                          <td class="bold" width="30%"><?php echo _l('company'); ?></td>
                          <td><?php echo get_option('invoice_company_name'); ?></td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('address'); ?></td>
                          <td><?php echo get_option('invoice_company_address'); ?></td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('requester'); ?></td>
                          <td><?php echo get_staff_full_name(get_staff_user_id()); ?></td>
                       </tr>
                       <tr class="project-overview">
                          <td class="bold"><?php echo _l('date_request'); ?></td>
                          <td><?php echo _d(date('Y-m-d')); ?></td>
                       </tr>
                        </tbody>
                    </table>
                  </div>
                  </div>
                  </div>
                  
                  <div class="col-md-12">
                    <p class="bold p_style"><?php echo _l('detail'); ?></p>
                    <hr class="hr_style"  />
                    <div class="col-md-12 " id="example">
                      <hr>
                    </div>

                  </div>
                  </div>
                  <?php echo form_hidden('request_detail'); ?>
                    <div class="clearfix"></div>
                    <button id="sm_btn" class="btn btn-info save_detail pull-right"><?php echo _l('submit'); ?></button>
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
<?php require 'modules/purchase/assets/js/pur_request_js.php';?>
