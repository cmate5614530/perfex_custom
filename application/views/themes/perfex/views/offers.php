<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s section-heading section-tickets">
  <div class="panel-body">
    <h3 class="text-success pull-left no-mtop tickets-summary-heading"><?php echo _l('Offers'); ?></h3>
    <a href="<?php echo site_url('clients/requests'); ?>" class="btn btn-info new-ticket pull-right">
      <span class="fa fa-arrow-left"> </span> <?php echo _l('Back_To_Requests'); ?>
    </a>
  </div>
</div>  
<style>
  .job_card {
    border: 1px solid #556655;
    border-radius: 3px;
    margin-top: 20px;
    padding:15px;
    box-shadow:3px 3px #bbbbbb;
  }
</style>

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
      
      <div class="row text-center" style="margin-top:5px;">
        <?php if($offer['status'] == 'active') {?>
          <a href="<?=site_url('clients/accept_offer/'.$offer['id']) ?>"> 
            <span class='btn btn-info' style='border-radius:20px;' > Accept </span>
          </a>  
        <?php } ?>

        <?php if($offer['status'] == 'accepted') {?>
          <a href="<?=site_url('clients/cancel_offer/'.$offer['id']) ?>"> 
            <span class='btn btn-danger' style='border-radius:20px;' > Cancel </span>
          </a>  
        <?php } ?>

      </div>
    </div>
  </div>
<?php } ?>