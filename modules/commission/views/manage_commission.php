<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" >
   <div class="content">
      <div class="row">
          <div class="panel_s">
             <div class="panel-body">
                 <div class="row">
                     <div class="col-md-4 border-right">
                      <h4 class="no-margin font-medium"><i class="fa fa-balance-scale" aria-hidden="true"></i> <?php echo _l('report_by_table'); ?></h4>
                      <hr />
                      <p><a href="#" class="font-medium" onclick="init_report(this,'commission_table'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('commission_table'); ?></a></p>
                      <p><a href="#" class="font-medium" onclick="init_report(this,'commission_client_table'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('commission_client_table'); ?></a></p>
                     </div>
                    <div class="col-md-4 border-right">
                      <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('charts_based_report'); ?></h4>
                      <hr />
                      <p><a href="#" class="font-medium" onclick="init_report(this,'commission_chart'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('commission_chart'); ?></a></p>
                      <p><a href="#" class="font-medium" onclick="init_report(this,'commission_client_chart'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('commission_client_chart'); ?></a></p>
                   </div>
                   <div class="col-md-4">
                    <div class="form-group" id="report-time">
                          <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
                          <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                             <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                             <option value="this_month"><?php echo _l('this_month'); ?></option>
                             <option value="1"><?php echo _l('last_month'); ?></option>
                             <option value="this_year"><?php echo _l('this_year'); ?></option>
                             <option value="last_year"><?php echo _l('last_year'); ?></option>
                             <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
                             <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
                             <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
                          </select>
                       </div>
                       <?php $current_year = date('Y');
                              $y0 = (int)$current_year;
                              $y1 = (int)$current_year - 1;
                              $y2 = (int)$current_year - 2;
                              $y3 = (int)$current_year - 3;
                           ?>
                       <div class="form-group hide" id="year_requisition">
                          <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
                          <select  name="year_requisition" id="year_requisition"  class="selectpicker"  data-width="100%" data-none-selected-text="<?php echo _l('filter_by').' '._l('year'); ?>">
                                <option value="<?php echo html_entity_decode($y0); ?>" <?php echo 'selected' ?>><?php echo _l('year').' '. html_entity_decode($y0) ; ?></option>
                                <option value="<?php echo html_entity_decode($y1); ?>"><?php echo _l('year').' '. html_entity_decode($y1) ; ?></option>
                                <option value="<?php echo html_entity_decode($y2); ?>"><?php echo _l('year').' '. html_entity_decode($y2) ; ?></option>
                                <option value="<?php echo html_entity_decode($y3); ?>"><?php echo _l('year').' '. html_entity_decode($y3) ; ?></option>

                          </select>
                       </div>
                       <?php echo form_hidden('is_client', 0); ?>
                   </div>
                </div>
                
            <div class="row">
                <div class="col-md-12" id="container1" ></div>
                <div class="col-md-12" id="container2" ></div>
            </div> 
            <hr>
            <div class="row">
                  <div class="col-md-6" id="container4" ></div>
                <div class="col-md-6" id="container3" ></div>
            </div> 
            <div id="report" class="hide">
                <div class="col-md-12">
                    <?php $this->load->view('commission_table'); ?>
                </div>
                <div class="col-md-12">
                    <?php $this->load->view('commission_chart'); ?>
                </div>
              </div>
            </div>      
          </div>
        </div>
     </div>
  </div>
</div>
<div class="modal fade" id="commission_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span><?php echo _l('commission'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="list_commission">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/commission/assets/js/manage_commission_js.php';?>