<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .job_card {
    border: 1px solid #778877;
    border-radius: 3px;
    margin-top: 20px;
    padding:15px;
    box-shadow:3px 3px #bbbbbb;
    background: white;
 }

</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
              <div class="col-md-12">
              <h4 class="no-margin font-bold"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
              </div>
		        </div>
		              	
					</div>
				</div>
        <?php foreach($offers as $offer) { ?>
          <div class="row job_card">
            <div class="col-md-3 text-center">
              <div class="row" >
                <img style='height:20vh;' src="<?= $offer['img_url'] ?>" alt="L39574403000.png">
              </div>
              <div class="row">
                <span style='font-size:1.5rem;'> <?= $offer['vehicle_make'] .' - '.$offer['vehicle_model'] ?></span>
              </div>  
            </div>
            <div class="col-md-6 text-center">
              <h1> offer content  </h1>
            </div>
            <div class="col-md-3">

              <div class="row text-center" style="margin-top:5px;">
                <?php if($offer['status'] == 'active') {?>
                  <span class='badge btn-primary' style='font-weight:100;' > Active </span>
                <?php } ?>

                <?php if($offer['status'] == 'cancelled') {?>
                  <span class='badge btn-danger' style='font-weight:100;' > Cancelled </span>
                <?php } ?>

                <?php if($offer['status'] == 'accepted') {?>
                  <span class='badge btn-success' style='font-weight:100;' > Accepted </span>
                  <a href="<?=site_url('invoice/' . $offer['invoice_id']. '/' . $offer['hash']) ?>"> 
                    <span class='badge btn-info' style='font-weight:100;' > View Invoice </span>
                  </a> 
                <?php } ?>
              </div>
              <div class="row text-center" style="margin-top:10px;" >
                <span style='font-size:5rem;'> $<?=$offer['price'] ?> </span>
              </div>
              <div class="row text-center" >
                <span style='font-size:1.5rem;'> ( <?=_l('Extra_options_included') ?> ) </span>
              </div>
              <!-- <div class="row text-center" style="margin-top:5px;">
                <a href="<?=site_url('clients/accept_offer/'.$offer['id']) ?>"> 
                  <span class='btn btn-info' style='border-radius:20px;' > Accept </span>
                </a>  
              </div> -->
            </div>
          </div>
        <?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="request_quotation" tabindex="-1" role="dialog">
  <div class="modal-dialog">
      <?php echo form_open_multipart(admin_url('purchase/send_request_quotation'),array('id'=>'send_rq-form')); ?>
      <div class="modal-content modal_withd">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">
                  <span><?php echo _l('request_quotation'); ?></span>
              </h4>
          </div>
          <div class="modal-body">
          	  <div id="additional_rqquo"></div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="vendor"><span class="text-danger">* </span><?php echo _l('vendor'); ?></label>
                    <select name="vendor[]" id="vendor" class="selectpicker" required multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                        <?php foreach($vendors as $s) { ?>
                        <option value="<?php echo html_entity_decode($s['userid']); ?>"><?php echo html_entity_decode($s['company']); ?></option>
                          <?php } ?>
                    </select>
                    <br>
                </div>     
                          
                <div class="col-md-12">
                  <?php echo render_input('subject','subject'); ?>
                </div>
                <div class="col-md-12">
                  <?php echo render_input('attachment','attachment','','file'); ?>
                </div>
                <div class="col-md-12">
                  <?php echo render_textarea('content','content','',array(),array(),'','tinymce') ?>
                </div>     
                <div id="type_care">
                  
                </div>        
              </div>
          </div>
          <div class="modal-footer">
              <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
              <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
          </div>
      </div><!-- /.modal-content -->
          <?php echo form_close(); ?>
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>