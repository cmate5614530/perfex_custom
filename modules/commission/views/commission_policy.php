<?php init_head();?>
<div id="wrapper" class="commission">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency'; ?>
          <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'commission-policy-form','autocomplete'=>'off')); ?>
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">

            <div class="col-md-12">
              <?php $value = (isset($commission_policy) ? $commission_policy->name : ''); ?>
              <?php echo render_input('name','name',$value,'text'); ?>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <?php $value = (isset($commission_policy) ? $commission_policy->from_date : ''); ?>
                  <?php echo render_date_input('from_date','from_date',$value); ?>
                </div>
                <div class="col-md-6">
                  <?php $value = (isset($commission_policy) ? $commission_policy->to_date : ''); ?>
                  <?php echo render_date_input('to_date','to_date',$value); ?>
                </div>
              </div>
            </div>
            <div class="col-md-12">
            <?php
                $selected = (isset($commission_policy) ? explode(',', $commission_policy->client_groups) : ''); 
                echo render_select('client_groups[]',$client_groups,array('id','name'),'client_groups',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
            </div>
            <div class="col-md-12">
            <?php
                $selected = (isset($commission_policy) ? explode(',',$commission_policy->clients) : '');
                echo render_select('clients[]',$clients,array('userid','company','customerGroups'),'clients',$selected,array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
            </div>
            <div class="col-md-12">
              <?php $commission_policy_type = [ 0 => ['id' => '1', 'name' => _l('calculated_as_ladder')],
                                              1 => ['id' => '2', 'name' => _l('calculated_as_percentage')],
                                              2 => ['id' => '3', 'name' => _l('calculated_by_the_product')]];
                $value = (isset($commission_policy) ? $commission_policy->commission_policy_type : '');                      
              echo render_select('commission_policy_type', $commission_policy_type,array('id','name'),'commission_policy_type', $value); ?>
            </div>
          </div>
          <div class="row <?php if(isset($commission_policy) && $commission_policy->commission_policy_type == '1'){ echo '';}else{echo 'hide';}?>" id = "calculated_as_ladder">
            <div class="col-md-12">
              <div class="row list_ladder_setting">
                <?php if(!isset($commission_policy)) { ?>
                <div id="item_ladder_setting">
                  <div class="row">
                    <div class="col-md-11">
                      <div class="col-md-4">
                        <?php echo render_input('from_amount[0]','from_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('to_amount[0]','to_amount','','text', $arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('percent_enjoyed_ladder[0]','percent_enjoyed','','number', array('min' => 0, 'max' => 100)); ?>
                      </div>
                    </div>
                    <div class="col-md-1 no-padding">
                    <span class="pull-bot">
                        <button name="add" class="btn new_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                  </div>
                </div>
                <?php }else{ 
                  $setting = json_decode($commission_policy->ladder_setting);
                  ?>
                  <?php foreach ($setting as $key => $value) { ?>
                  <div id="item_ladder_setting">
                    <div class="row">
                      <div class="col-md-11">
                        <div class="col-md-4">
                        <?php echo render_input('from_amount['.$key.']','from_amount',$value->from_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4">
                        <?php echo render_input('to_amount['.$key.']','to_amount',$value->to_amount,'text',$arrAtt); ?>
                      </div>
                      <div class="col-md-4" id="is_staff_0">
                        <?php echo render_input('percent_enjoyed_ladder['.$key.']','percent_enjoyed',$value->percent_enjoyed_ladder,'text',$arrAtt); ?>
                      </div>
                      </div>
                      <div class="col-md-1">
                      <span class="pull-bot">
                          <?php if($key != 0){ ?>
                            <button name="add" class="btn remove_item_ladder btn-danger mtop25" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                          <?php }else{ ?>
                            <button name="add" class="btn new_item_ladder btn-success mtop25" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          <?php } ?>
                            </span>
                      </div>
                    </div>
                  </div>
                  <?php }
                  } ?>
              </div>
            </div>
          </div>
          <div class="row <?php if(isset($commission_policy) && $commission_policy->commission_policy_type == '2'){ echo '';}else{echo 'hide';}?>" id="calculated_as_percentage">
            <div class="col-md-12">
              <?php $value = (isset($commission_policy) ? $commission_policy->percent_enjoyed : ''); ?>
              <?php echo render_input('percent_enjoyed','percent_enjoyed',$value,'number',array('min' => 0, 'max' => 100)); ?>

              <div class="form-group">
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" name="commmission_first_invoices" id="commmission_first_invoices" value="1" <?php if(isset($commission_policy) && $commission_policy->commmission_first_invoices == '1'){ echo 'checked';}?>>
                  <label for="commmission_first_invoices"><?php echo _l('commmission_first_invoices'); ?></label>
                </div>
              </div>
              <div id="div_commmission_first_invoices" class="<?php if(isset($commission_policy) && $commission_policy->commmission_first_invoices == '1'){ echo '';}else{echo 'hide';}?>">
                <?php $value = (isset($commission_policy) ? $commission_policy->number_first_invoices : ''); ?>
                <?php echo render_input('number_first_invoices','number_first_invoices',$value,'number',array('min' => 0)); ?>
                <?php $value = (isset($commission_policy) ? $commission_policy->percent_first_invoices : ''); ?>
                <?php echo render_input('percent_first_invoices','percent_first_invoices',$value,'number',array('min' => 0, 'max' => 100)); ?>
              </div>
            </div>
            <div>
            </div>
          </div>
          <div class="row <?php if(isset($commission_policy) && $commission_policy->commission_policy_type == '3'){ echo '';}else{echo 'is_hide';}?>" id="calculated_by_the_product">
            <div class="col-md-12">
              <h4 class="font-bold"><?php echo _l('calculated_by_the_product'); ?></h4>
                <div id="product_setting" class="mbot10"></div>
              <?php echo form_hidden('product_setting'); ?>
              <?php 
                    if(isset($commission_policy) && $commission_policy->commission_policy_type == '3'){
                      $product_setting = json_decode($commission_policy->product_setting);
                      $financial_col = ['product_groups','product','number_from','number_to','percent'];
                      foreach ($product_setting as $key => $value) {
                            $product_setting[$key] = array_combine($financial_col, $value);
                      }
                      $product_setting = json_encode($product_setting);
                    }else{
                      $product_setting = '[[]]';
                    }
              ?>
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
<?php require 'modules/commission/assets/js/commission_policy_js.php';?>