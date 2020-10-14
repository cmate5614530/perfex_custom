<?php init_head(); ?>
<style>
  div#prices td select {
    padding:6px;
    width:100%;
  }
</style>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12" id="small-table">
            <div class="panel_s">
               <div class="panel-body">
                <?php echo form_hidden('item_id',$item_id); ?>
                  <div class="row">
                     <div class="col-md-12">
                      <h4 class="no-margin font-bold"><i class="fa fa-clone menu-icon menu-icon" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
                      <br>

                    </div>
                  </div>
                  <div class="row row-margin-bottom">
                    <div class="col-md-5  ">
                        <?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>

                          <!-- dung cho add 1 -->
                        <a href="#" onclick="new_commodity_item(); return false;" class="btn btn-info pull-left display-block mr-4 button-margin-r-b" data-toggle="sidebar-right" data-target=".commodity_list-add-edit-modal">
                            <?php echo _l('add'); ?>
                        </a>
                        <?php } ?>
                    </div>
                    
                     <div class="col-md-1 pull-right">
                        <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view_proposal('.proposal_sm','#proposal_sm_view'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
                    </div>
                    </div>

                    <div class="modal bulk_actions" id="table_commodity_list_bulk_actions" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              </div>
                              <div class="modal-body">
                                 <?php if(has_permission('rec_proposal','','delete') || is_admin()){ ?>
                                 <div class="checkbox checkbox-danger">
                                    <input type="checkbox" name="mass_delete" id="mass_delete">
                                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                 </div>
                                
                                 <?php } ?>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>

                                 <?php if(has_permission('purchase','','delete') || is_admin()){ ?>
                                 <a href="#" class="btn btn-info" onclick="purchase_delete_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                  <?php } ?>
                              </div>
                           </div>
                          
                        </div>
                        
                     </div>

                    <a href="#"  onclick="staff_bulk_actions(); return false;" data-toggle="modal" data-table=".table-table_item_list" data-target="#leads_bulk_actions" class=" hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
                      <?php render_datatable(array(
                        '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="table_item_list"><label></label></div>',
                        _l('_images'),
                        _l('Vehicle_Make'),
                        _l('Vehicle_Model'),
                        _l('Vehicle_Type'),
                        _l('Number_Of_Passengers'),
                        _l('Extra_Time_Enable'),
                        _l('Base_Location'),
                        _l('Vehicle_Availability_Enable'),
                        _l('Price_Type_Variable'),
                        ),'table_item_list',['proposal_sm' => 'proposal_sm'],
                          array(
                            'proposal_sm' => 'proposal_sm',
                             'id'=>'table-table_item_list',
                             'data-last-order-identifier'=>'table_item_list',
                             'data-default-order'=>get_table_last_order('table_item_list'),
                           )); ?>
               </div>
            </div>
         </div>
         <div class="col-md-7 small-table-right-col">
            <div id="proposal_sm_view" class="hide">
            </div>
         </div>
      </div>
   </div>
   
</div>


    <div class="modal" id="purchase_type" tabindex="-1" role="dialog">
    <div class="modal-dialog ht-dialog-width">

          <?php echo form_open_multipart(admin_url('purchase/add_commodity_list'), array('id'=>'add_purchase_type')); ?>
          <div class="modal-content" >
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title">
                        <span class="add-title"><?php echo _l('add'); ?></span>
                    </h4>
                   
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                         <div id="purchase_type_id">
                         </div>   
                     <div class="form"> 
                        <div class="col-md-12" id="add_handsontable">
                        </div>
                          <?php echo form_hidden('hot_purchase_type'); ?>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_purchase_type(this); return false;" ><?php echo _l('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
              </div>
              </div>
          </div>


  <!-- add one commodity list sibar start-->       

    <div class="modal" id="commodity_list-add-edit" tabindex="-1" role="dialog">
      <div class="modal-dialog ht-dialog-width">

        <?php echo form_open_multipart(admin_url('purchase/commodity_list_add_edit'),array('class'=>'commodity_list-add-edit','autocomplete'=>'off')); ?>

            <div class="modal-content">

              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> -->
                  <h4 class="modal-title">
                      <span class="edit-commodity-title"><?php echo _l('edit_item'); ?></span>
                      <span class="add-commodity-title"><?php echo _l('add_item'); ?></span>
                  </h4>
              </div>

              <div class="modal-body" style="padding:27px;">
                  <div id="commodity_item_id"></div>
                  <!-- interview process start -->
                    <div role="tabpanel" class="tab-pane active" id="interview_infor">
                      <div class="col-md-12" >
                        <div class="horizontal-scrollable-tabs">      
                          <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                          <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                          <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                              <li role="presentation" class="<?php  if(!$this->input->get('tab')){echo 'active';}; ?>">
                                  <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                                  <?php echo _l( 'general'); ?>
                                  </a>
                              </li>
                              <li role="presentation" >
                                  <a href="#prices" aria-controls="prices" role="tab" data-toggle="tab">
                                  <?php echo _l( 'prices'); ?>
                                  </a>
                              </li>
                              <li role="presentation" >
                                  <a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">
                                  <?php echo _l( 'attributes'); ?>
                                  </a>
                              </li>
                              <li role="availability" >
                                  <a href="#availability" aria-controls="availability" role="tab" data-toggle="tab">
                                  <?php echo _l( 'availability'); ?>
                                  </a>
                              </li>
                              <li role="driving_zone" >
                                  <a href="#driving_zone" aria-controls="driving_zone" role="tab" data-toggle="tab">
                                  <?php echo _l( 'driving_zone'); ?>
                                  </a>
                              </li>
                              <li role="google_calendar" >
                                  <a href="#google_calendar" aria-controls="google_calendar" role="tab" data-toggle="tab">
                                  <?php echo _l( 'google_calendar'); ?>
                                  </a>
                              </li>
                              <li role="pricing_rules" >
                                  <a href="#pricing_rules" aria-controls="pricing_rules" role="tab" data-toggle="tab">
                                  <?php echo _l( 'pricing_rules'); ?>
                                  </a>
                              </li>
                            </ul>

                          </div>
                        </div>
                        <div class="tab-content mtop15"> 
                          <!-- General       -->
                          <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="general">     
                            <!--
                              <div class="row">
                                  <div class="col-md-6">
                                      <?php echo render_input('commodity_code', 'commodity_code'); ?>
                                  </div>
                                  <div class="col-md-6">
                                    <?php echo render_input('description', 'commodity_name'); ?>
                                  </div>
                                  
                              </div>
                                
                              <div class="row">
                                <div class="col-md-4">
                                      <?php echo render_input('commodity_barcode', 'commodity_barcode','','text'); ?>
                                  </div>
                                <div class="col-md-4">
                                  <a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle skucode-tooltip"  data-toggle="tooltip" title="" data-original-title="<?php echo _l('commodity_sku_code_tooltip'); ?>"></i></a>
                                  <?php echo render_input('sku_code', 'sku_code','',''); ?>
                                </div>
                                <div class="col-md-4">
                                  <?php echo render_input('sku_name', 'sku_name'); ?>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-12">
                                      <?php echo render_textarea('long_description', 'description'); ?>
                                </div>
                              </div>

                              <div class="row">
                                
                                  <div class="col-md-6">
                                      <?php echo render_select('group_id',$commodity_groups,array('id','name'),'commodity_group'); ?>
                                  </div>
                                  <div class="col-md-6">
                                      <?php echo render_select('sub_group',$sub_groups,array('id','sub_group_name'),'sub_group'); ?>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-6">

                                      <?php $premium_rates = isset($premium_rates) ? $premium_rates : '' ?>
                                      <?php
                                      $attr = array();
                                      $attr = ['data-type' => 'currency'];
                                      echo render_input('rate', 'rate','', 'text', $attr); ?>
                                  </div>
                                  <div class="col-md-6">
                                      <?php 
                                      $attr = array();
                                      $attr = ['data-type' => 'currency'];
                                      echo render_input('purchase_price', 'purchase_price','', 'text', $attr); ?>
                                    
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                      <?php echo render_select('unit_id',$units,array('unit_type_id','unit_name'),'units'); ?>
                                  </div>
                                  
                                <div class="col-md-6">
                                      <?php echo render_select('tax',$taxes,array('id','label'),'taxes'); ?>
                                  </div>

                              </div>
                              <small class="req text-danger">*</small> 
                            -->     
                              <div class="row">
                                <div class="col-md-6">
                                  <label class=" vehicle-detail-subtitle"> <?php echo _l( 'DEFAULT_CATEGORY_VEHICLE'); ?> </label> <br>
                                  <div class="btn-group btn-toggle" name="default_category_enable"> 
                                    <button class="btn btn-default"> <?= _l('ENABLE') ?> </button>
                                    <button class="btn btn-primary active"> <?= _l('DISABLE') ?> </button>
                                    <input type="hidden" name="default_category_enable"> 
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <label for="group_id" class="control-label  vehicle-detail-subtitle">
                                  <?= _l('VEHICLE_TYPE') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> <?= _l('vehicle_type') ?> </span>
                                  <select id="group_id" name="group_id" class="form-control">  
                                    <?php 
                                    foreach($commodity_groups as $group)
                                    {?>
                                    <option value=<?php echo $group['id']; ?>> <?php echo $group['name']; ?> </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                                    
                              <div class="row">
                                <div class="col-md-6">
                                  <label for="vehicle_make" class="control-label  vehicle-detail-subtitle">
                                    <?= _l('VEHICLE_MAKE') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> <?= _l('vehicle_make') ?> </span>
                                  <input type="text" id="vehicle_make" name="vehicle_make" class="form-control">  
                                </div>
                                <div class="col-md-6" >
                                  <label for="vehicle_model" class="control-label  vehicle-detail-subtitle">
                                    <?= _l('VEHICLE_MODEL'); ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> <?= _l('vehicle_model') ?> </span>
                                  <input type="text" id="vehicle_model" name="vehicle_model" class="form-control"> 
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <label for="number_of_passengers" class="control-label  vehicle-detail-subtitle">
                                    <?= _l('NUMBER_OF_PASSENGERS') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation">
                                    <?= _l('nop_exp') ?>
                                  </span>
                                  <input type="number" min="1" max="99" id="number_of_passengers" name="number_of_passengers" class="form-control"> 
                                </div>
                                <div class="col-md-6" >
                                  <label for="number_of_suitcases" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('NUMBER_OF_SUITCASES') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('nos_exp') ?>
                                  </span>
                                  <input type="number" min="1" max="99" id="number_of_suitcases" name="number_of_suitcases" class="form-control">  
                                </div>
                              </div>
                            
                              <div class="row" style="margin-top:10px;">
                                <div class ="col-md-6"> 
                                  <span class=" vehicle-detail-subtitle"> <?= _l('EXTRA_TIME') ?></span> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?= _l('et_ex') ?>
                                  </span>

                                  <div class="btn-group btn-toggle" name="extra_time_enable"> 
                                    <button class="btn btn-default"><?=_l('ENABLE') ?></button>
                                    <button class="btn btn-primary active"><?=_l('DISABLE') ?></button>
                                    <input type="hidden" name="extra_time_enable"> 

                                  </div> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('et_exp1') ?>
                                    <br>
                                  </span>
                                  <input type="number" min="0" max="9999" placeholder="Min" id="extra_time_min" 
                                    name="extra_time_min" class="form-control">
                                  <input style="margin-top:10px;" type="number" min="1" max="9999" placeholder="Max" id="extra_time_max"
                                    name="extra_time_max" class="form-control">
                                  <span class="vehicle-detail-explanation">
                                    <?= _l('step1_9999') ?>
                                    <br>
                                  </span>
                                  <input style="margin-top:10px;" type="number" min="1" max="9999" placeholder="Step" id="extra_time_step" 
                                    name="extra_time_step" class="form-control">
                                </div>
                                <div class ="col-md-6">
                                  <label for="base_location" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('BASE_LOCATION') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> 
                                    <?=_l('be_exp') ?>
                                  </span>
                                  <input type="text" id="base_location" name="base_location" class="form-control"> 
                                </div>

                              </div>
                            
                              <div class="row" style="margin-top:10px;">
                                <div class ="col-md-6"> 
                                  <span class=" vehicle-detail-subtitle"> <?=_l('VEHICLES_AVAILABILITY') ?></span> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('va_exp') ?><br>
                                  </span>

                                  <div class="btn-group btn-toggle" name="vehicle_availability_enable"> 
                                    <button class="btn btn-default"><?=_l('ENABLE') ?></button>
                                    <button class="btn btn-primary active"> <?=_l('ENABLE') ?></button>
                                    <input type="hidden" name="vehicle_availability_enable"> 

                                  </div> <br>
                                </div>
                                <div class ="col-md-6">
                                  <label for="base_location" class="control-label  vehicle-detail-subtitle">
                                    
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> 
                                    <?=_l('bi_exp') ?>
                                  </span>
                                  <input type="number" min="1" max="999" placeholder="Max" id="bookings_interval"
                                    name="bookings_interval" class="form-control" value="1"> 
                                </div>

                              </div>
                              
                              <div class="row" style="margin-top:10px;">
                                <div class="col-md-12">
                                  <span class=" vehicle-detail-subtitle"> <?=_l('FIXED_LOCATIONS') ?> </span> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('fl_exp') ?><br>
                                  </span>

                                  <table class="table table-bordered table-striped">
                                    <thead class="table-header">
                                      <tr>
                                        <th> <?=_l('Service') ?> </th>
                                        <th> <?= _l('Pickup_location') ?> </th>
                                        <th>  <?=_l('Drop_off_location') ?> </th>
                                      <tr>  
                                    </thead> 
                                    <tbody>
                                      <tr>
                                        <td> <?=_l('Distance') ?> </td>
                                        <td> <input type="text"  name="distance_pl" class="form-control">  </td>
                                        <td> <input type="text"  name="distance_dol" class="form-control"> </td>
                                      <tr>
                                      <tr>
                                        <td> <?=_l('Hourly') ?> </td>
                                        <td> <input type="text"  name="service_pl" class="form-control"> </td>
                                        <td> <input type="text"  name="service_dol" class="form-control"> </td>
                                      <tr>   
                                    </tbody>  
                                  </table>
                                </div>
                              </div>
                              <?php if(!isset($expense) || (isset($expense) && $expense->attachment == '')){ ?>
                              <div id="dropzoneDragArea" class="dz-default dz-message">
                                <span><?php echo _l('item_add_edit_attach_image'); ?></span>
                              </div>
                              <div class="dropzone-previews"></div>
                              <?php } ?>

                              <div id="images_old_preview">
                              </div>
                            
                          </div>   
                          <!-- Prices  -->
                          <div role="tabpanel" class="tab-pane" id="prices">
                            <div class="row">
                              <div class ="col-md-12"> 
                                  <span class=" vehicle-detail-subtitle"> <?=_l('PRICE_TYPE') ?> </span> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('pt_exp') ?><br>
                                  </span>

                                  <div class="btn-group btn-toggle" name="price_type_variable"> 
                                    <button class="btn btn-default"><?=_l('Variable_pricing') ?></button>
                                    <button class="btn btn-primary active"><?=_l('Fixed_pricing') ?></button>
                                    <input type="hidden" name="price_type_variable"> 
                                  </div> <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                  <span class=" vehicle-detail-subtitle"> PRICES</span> <br>
                                  <span class="vehicle-detail-explanation">
                                  Net prices.<br> </span>
                                  <table class="table table-bordered table-striped" id="net_prices_table">
                                    <thead class="table-header">
                                      <tr >
                                        <th> <?=_l('Name') ?> </th>
                                        <th> <?=_l('Type') ?> </th>
                                        <th> <?=_l('Description') ?> </th>
                                        <th> <?=_l('Value') ?> </th>
                                        <th> <?=_l('Tax') ?> </th>
                                      </tr>  
                                    </thead> 
                                    <tbody>
                                      <?php 
                                      $prices = [
                                      ['name' => 'Fixed', 'type'=> 'Fixed', 'desc' => 'Fixed price of a ride', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Fixed(return)', 'type'=> 'Fixed', 'desc' => 'Fixed price of a return ride', 'value' => '0.0', 'tax'=>'none'],
                                      ['name' => 'Initial', 'type'=> 'Variable', 'desc' => 'Fixed value which is added to the order sum.' , 'value'=> '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Delivery', 'type'=> 'Variable', 'desc' => 'Price per kilometer of ride from base to customer pickup location.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Delivery(return)', 'type'=> 'Variable', 'desc' => 'Price per kilometer of ride from customer drop off location to base.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per kilometer', 'type'=> 'Variable', 'desc' => 'Price per distance.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per kilometer(return)', 'type'=> 'Variable', 'desc' => 'Price per distance.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per hour', 'type'=> 'Variable', 'desc' => 'Price per hour.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per extra time(hour)', 'type'=> 'Variable', 'desc' => 'Price per hour for extra time.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Car minimal price', 'type'=> 'Variable', 'desc' => 'Price per hour for extra time.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per adult passenger', 'type'=> 'Variable', 'desc' => 'Price per adult passenger.', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Per child passenger', 'type'=> 'Variable', 'desc' => 'Price per child passenger.', 'value' => '0.0', 'tax'=>'none'   ],

                                      ];
                                      foreach($prices as $price)
                                      {
                                      ?>
                                      <tr type="<?=$price['type']?>">
                                        <td> <?= $price['name'] ?> </td>
                                        <td> <?= $price['type'] ?> </td>
                                        <td> <?= $price['desc'] ?> </td>
                                        <td> <input type="text"  value="0.0"> </td>
                                        <td>
                                            <select  value="none">
                                              <option value="none"> Not Set </option>
                                              <option value="18"> 18% </option>
                                              <option value="19"> 19% </option>
                                              <option value="20"> 20% </option>
                                              <option value="21"> 21% </option>
                                              <option value="22"> 22% </option>
                                              <option value="23"> 23% </option>
                                            </select>
                                        </td>
                                      </tr>
                                      <?php } ?>  
                                    </tbody>  
                                  </table>
                                  </span>
                                </div>  
                            </div>      
                            <div class="row">
                                <div class="col-md-12">
                                  <span class=" vehicle-detail-subtitle"> BUS RENTAl PRICES</span> <br>
                                  <table class="table table-bordered table-striped" id="bus_prices_table">
                                    <thead class="table-header">
                                      <tr>
                                        <th> <?=_l('Name') ?> </th>
                                        <th> <?=_l('Type') ?> </th>
                                        <th> <?=_l('Description') ?> </th>
                                        <th> <?=_l('Value') ?> </th>
                                        <th> <?=_l('Tax') ?> </th>
                                      </tr>  
                                    </thead> 
                                    <tbody>
                                    <?php 
                                      $prices = [
                                      ['name' => 'Rental price Full day', 'type'=> 'Variable', 'desc' => 'Rental price Full day', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Rental price Half day', 'type'=> 'Variable', 'desc' => 'Rental price Half day', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Working hours per day', 'type'=> 'Variable', 'desc' => 'Working hours per day', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Driver salary per day', 'type'=> 'Variable', 'desc' => 'Driver salary per day', 'value' => '0.0', 'tax'=>'none'   ],
                                      ['name' => 'Fuel', 'type'=> 'Variable', 'desc' => 'Fuel (per 100 km)', 'value' => '0.0', 'tax'=>'none'   ],

                                      ];
                                      foreach($prices as $price)
                                      {
                                      ?>
                                      <tr type="<?=$price['type']?>">
                                        <td> <?= $price['name'] ?> </td>
                                        <td> <?= $price['type'] ?> </td>
                                        <td> <?= $price['desc'] ?> </td>
                                        <td> <input type="text" value="0.0"> </td>
                                        <td>
                                            <select  value="none">
                                              <option value="none"> <?=_l('Not_Set') ?> </option>
                                              <option value="18"> 18% </option>
                                              <option value="19"> 19% </option>
                                              <option value="20"> 20% </option>
                                              <option value="21"> 21% </option>
                                              <option value="22"> 22% </option>
                                              <option value="23"> 23% </option>
                                            </select>
                                        </td>
                                      </tr>
                                      <?php } ?>   
                                    </tbody>  
                                  </table>
                                  </span>
                                </div>  
                            </div>  
                          </div> 
                          <!-- Attribtues  -->
                          <div role="tabpanel" class="tab-pane" id="attributes">
                            <div class="row">
                                <div class="col-md-12">
                                  <label for="attribute_table" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('ATTRIBUTES') ?>
                                  </label><br>
                                  <span class="vehicle-detail-explanation"> 
                                    <?=_l('att_exp') ?> <br>
                                  </span>
                                  <table class="table table-bordered table-striped" id="attribute_table">
                                    <thead class="table-header">
                                      <tr>
                                        <th style="min-width:10vw;"> <?=_l('Attribute_name') ?> </th>
                                        <th> <?=_l('Attribute_value') ?> </th>
                                      </tr>  
                                    </thead> 
                                    <tbody>
                                      <tr>
                                        <td> <?=_l('Drivers_Language') ?> </td>
                                        <td> 
                                          <div class="row">
                                            <div class="form-group">
                                              <div class="lang-select">
                                              <?php foreach($langs as $lang)
                                                {
                                                ?>
                                                <div class="items col-md-2 " lang="<?php echo $lang['name'] ?>">
                                                    <div data-toggle="buttons" class="btn-group bizmoduleselect">
                                                        <label class="btn btn-default">
                                                            <div class="bizcontent">
                                                                <input type="checkbox" data-lang="<?php echo $lang['value']; ?>" autocomplete="off" >
                                                                <span class="glyphicon glyphicon-ok glyphicon-lg"></span>
                                                                <h6><?php echo $lang['name'] ?></h6>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php } ?>  
                                              </div>
                                            </div>  
                                          </div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td> <?=_l('Meet_Greet_included') ?> </td>
                                        <td> 
                                          <div class="btn-group btn-toggle" name="meet_included_enable"> 
                                            <button class="btn btn-default">No</button>
                                            <button class="btn btn-primary active">Yes</button>
                                            <input type="hidden" name="meet_included_enable"> 

                                          </div>
                                        </td>
                                      </tr>   
                                    </tbody>
                                  </table>     
                                </div>  
                            </div>  
                          </div> 
                          <!-- Availability  -->
                          <div role="tabpanel" class="tab-pane" id="availability">
                            <div class="row">
                              <div class="col-md-12">
                                <span class=" vehicle-detail-subtitle"> <?=_l('EXCLUDE_DATES') ?></span> <br>
                                <span class="vehicle-detail-explanation">
                                <?=_l('ed_exp') ?><br>
                                </span>
                                <table class="table table-bordered table-striped" id="exclude_ranges_table">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('Start_Date_Time') ?> </th>
                                      <th > <?=_l('End_Date_Time') ?> </th>
                                      <th> <?=_l('Remove') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <tr>
                                    </tr>
                                  </tbody>    
                                </table>
                              </div>
                              <div class="col-md-12" style="display:flex; justify-content: flex-end;">
                                <a href="#" onclick="add_exclude_range(); "> Add </a>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <span class=" vehicle-detail-subtitle"> <?=_l('BUSINESS_HOURS') ?> </span> <br>
                                <span class="vehicle-detail-explanation">
                                  <?=_l('bh_exp') ?>
                                </span>
                                <table class="table table-bordered table-striped" id="weekday_start_end">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('Weekday') ?> </th>
                                      <th > <?=_l('Start_Time') ?> </th>
                                      <th> <?=_l('End_Time') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <?php 
                                      $wds = [ 
                                        _l('Monday'),_l('Tuesday'),_l('Wednesday'), 
                                        _l('Thursday'), _l('Friday'), _l('Saturday'), _l('Sunday')
                                      ];
                                    foreach($wds as $wd){ ?>
                                    <tr>
                                      <td> <?= $wd ?> </td>
                                      <td> 
                                          <input type='text' class="form-control" id="<?= $wd ?>_start_time"/>
                                      </td>
                                      <td> 
                                        <input type='text' class="form-control" id="<?= $wd ?>_end_time"/>
                                        
                                      </td>
                                    </tr>
                                    <?php } ?>
                                  </tbody>  
                                </table>
                              </div>
                            </div>     
                          </div>
                          <!-- Driving Zone  -->
                          <div role="tabpanel" class="tab-pane" id="driving_zone">
                            <div class="row">
                              <div class="col-md-12">
                                <label for="attribute_countries" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('COUNTRIES') ?>
                                </label><br>
                                <span class="vehicle-detail-explanation"> 
                                <?=_l('cntr_exp') ?> <br>
                                </span>
                                <select style="max-width: 500px; z-index:-1" class="form-control" id="attribute_countries" name="attribute_countries" multiple="multiple">
                                  <?php foreach($countries as $country)
                                  {
                                  ?>
                                  <option value=" <?= $country['iso3'] ?>"> <?= $country['short_name'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>                       
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <label for="attribute_countries" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('CUSTOM_DRIVING_ZONE') ?>
                                </label><br>
                                <span class="vehicle-detail-explanation"> 
                                  <?=_l('cdz_exp') ?> <br>
                                </span>
                              </div>
                            </div> 
                            <div class="row">
                              <table class="table table-bordered table-striped" id="map_table">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('Pickup_location') ?> </th>
                                      <th > <?=_l('Drop_off_location') ?> </th>
                                      <th>  <?=_l('Remove') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <tr class="map_table_row"> </tr>
                                    <!-- <tr>
                                      <td>
                                        <div id="map_src" class="map" > </div>
                                      </td>  
                                      <td>
                                        <div id="map_dest" class="map" > </div>
                                      </td>
                                      <td class="map_table_action">
                                        <a href="#" class="table_action_button" onclick="remove_map(this); " > Remove </a>
                                      </td>     
                                    </tr>   -->
                                  </tbody>    
                              </table>
                              <div class="col-md-12" style="display:flex; justify-content: flex-end;">
                                <a href="#" class="table_action_button" onclick="add_map(this); "> Add </a>
                              </div>
                              
                              <div class="col-md-6" >
                                <h4 class="text-center"> <?=_l('Pickup_location') ?> </h4>
                                
                              </div>
                              <div class="col-md-6" >
                                <h4 class="text-center"> <?=_l('Drop_off_location') ?> </h4>
                              </div>                     
                            </div>     
                          </div> 
                          <!-- google calendar  -->
                          <div role="tabpanel" class="tab-pane" id="google_calendar">
                            <div class="row">
                              <div class ="col-md-12"> 
                                  <span class=" vehicle-detail-subtitle"> <?=_l('GOOGLE_CALENDAR') ?> </span> <br>
                                  <span class="vehicle-detail-explanation">
                                    <?=_l('gce_exp') ?><br>
                                  </span>

                                  <div class="btn-group btn-toggle" name="google_calendar_enable"> 
                                    <button class="btn btn-default"> <?=_l('ENABLE') ?> </button>
                                    <button class="btn btn-primary active"> <?=_l('DISABLE') ?> </button>
                                    <input type="hidden" name="google_calendar_enable"> 
                                  </div> <br>
                                </div>
                            </div>
                            <div class="row">
                              <div class ="col-md-6">
                                <label for="google_calendar_id" class="control-label  vehicle-detail-subtitle">
                                  ID
                                </label><br>
                                <span class="vehicle-detail-explanation"> 
                                Google Calendar ID.
                                </span>
                                <input type="text"  placeholder="ID" id="google_calendar_id"
                                    name="google_calendar_id" class="form-control"> 
                              </div>
                              <div class ="col-md-6">
                                <label for="google_calendar_settings" class="control-label  vehicle-detail-subtitle">
                                  <?=_l('SETTINGS') ?>
                                </label><br>
                                <span class="vehicle-detail-explanation"> 
                                  <?=_l('stt_exp') ?>
                                </span>
                                <textarea placeholder="JSON CONTENT" id="google_calendar_settings" style="height:100px;"
                                    name="google_calendar_settings" class="form-control">  </textarea>
                              </div>
                            </div>
                          </div>
                          <!-- Pricing Rules  -->
                          <div role="tabpanel" class="tab-pane" id="pricing_rules">
                            <!-- Dates  -->
                            <div class="row">
                              <div class="col-md-12">
                                <span class=" vehicle-detail-subtitle"> <?=_l('DATES') ?></span> <br>
                                <span class="vehicle-detail-explanation">
                                <?=_l('dates_exp') ?><br>
                                </span>
                                <table class="table table-bordered table-striped" id="dates_table">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('From') ?> </th>
                                      <th > <?=_l('To') ?> </th>
                                      <th> <?=_l('Remove') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <tr>
                                    </tr>
                                  </tbody>    
                                </table>
                              </div>
                              <div class="col-md-12" style="display:flex; justify-content: flex-end;">
                                <a href="#" onclick="add_date();"> <?=_l('Add') ?> </a>
                              </div>
                            </div> 
                            <div class="row" >
                              <div class="col-md-2" >   
                                <label for="pr_dates_acp" class="control-label  vehicle-detail-subtitle">
                                  <?=_l('Addtional_cost_percentage').':' ?>
                                </label>
                              </div>
                              <div class="col-md-3" >  
                                <input type="number" min="0" max="99" value="0" id="pr_dates_acp" name="pr_dates_acp" class="form-control"> 
                              </div>    
                            </div>
                            <!-- Hours  -->
                            <div class="row" style='margin-top: 25px;'>
                              <div class="col-md-12">
                                <span class=" vehicle-detail-subtitle"> <?=_l('HOURS') ?></span> <br>
                                <span class="vehicle-detail-explanation">
                                <?=_l('hours_exp') ?><br>
                                </span>
                                <table class="table table-bordered table-striped" id="hours_table">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('From') ?> </th>
                                      <th > <?=_l('To') ?> </th>
                                      <th> <?=_l('Remove') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <tr>
                                    </tr>
                                  </tbody>    
                                </table>
                              </div>
                              <div class="col-md-12" style="display:flex; justify-content: flex-end;">
                                <a href="#" onclick="add_hour();"> <?=_l('Add') ?> </a>
                              </div>
                                      
                            </div>  
                            <div class="row" >
                                <div class="col-md-2" >   
                                  <label for="pr_hours_acp" class="control-label  vehicle-detail-subtitle">
                                    <?=_l('Addtional_cost_percentage').':' ?>
                                  </label>
                                </div> 
                                <div class="col-md-3" >  
                                  <input type="number" min="0" max="99" value="0" id="pr_hours_acp" name="pr_hours_acp" class="form-control"> 
                                </div>    
                            </div>  
                            <!-- Distance  -->
                            <div class="row" style='margin-top: 25px;'>
                              <div class="col-md-12">
                                <span class=" vehicle-detail-subtitle"> <?=_l('DISTANCE') ?></span> <br>
                                <span class="vehicle-detail-explanation">
                                <?=_l('distance_exp') ?><br>
                                </span>
                                <table class="table table-bordered table-striped" id="distance_table">
                                  <thead class="table-header">
                                    <tr>
                                      <th > <?=_l('From') ?> </th>
                                      <th > <?=_l('To') ?> </th>
                                      <th > <?=_l('Price(per km)') ?> </th>
                                      <th > <?=_l('tax_add_edit_rate') ?> </th>
                                      <th> <?=_l('Remove') ?> </th>
                                    <tr>  
                                  </thead> 
                                  <tbody>
                                    <tr>
                                    </tr>
                                  </tbody>    
                                </table>
                              </div>
                              <div class="col-md-12" style="display:flex; justify-content: flex-end;">
                                <a href="#" onclick="add_distance();"> <?=_l('Add') ?> </a>
                              </div>
                                      
                            </div>   
                          </div>
                        </div>      
                      </div>

                          
                    </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit"  class="btn btn-info"><?php echo _l('submit'); ?></button>
              </div>
            </div>

          
        <?php echo form_close(); ?>
      </div>
  </div><!-- modal -->

<!-- add one commodity list sibar end -->   

<?php init_tail(); ?>
</body>

</html>
<script>
  var user_type = 'admin';
</script>
<?php require 'modules/purchase/assets/js/item_list_js.php';?>
