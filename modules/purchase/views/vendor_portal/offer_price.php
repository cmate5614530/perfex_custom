<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
  @media screen and (min-width: 800px) {
    .offer_price_your_offer {
      padding-left: 10%;
      padding-right: 10%;
    }
  }
  
</style>
<div class="panel_s section-heading section-tickets">
  <div class="panel-body">
    <h4 class="no-margin section-text"><?php echo _l('Offer_Price'); ?></h4>
  </div>
</div>  
<div class="panel_s">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-success pull-left no-mtop tickets-summary-heading"><?php echo _l('Request_Information'); ?></h3>
      </div>
      <div class="clearfix"></div>
      <hr/>
    </div>
    <div class="row">  
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-1 col-xs-3">
            <span class="btn btn-danger "  style='border-radius: 20px;'> A </span> 
          </div>
          <div class="col-md-11 col-xs-9 " style="margin-top:5px; font-size:16px;">
            <span > <?= $request->pickup ?> </span>
          </div>  
        </div>
        <div class="row" style='margin-top:5px;'>
          <div class="col-md-1 col-xs-3">
            <span class="fa fa-arrow-down" style='margin-left:10px; font-size:16px;' > </span>
          </div>
        </div>  
        <div class="row" style='margin-top50px;'>
          <div class="col-md-1 col-xs-3">
            <span class="btn btn-danger "  style='border-radius: 20px;'> B </span> 
          </div>
          <div class="col-md-11 col-xs-9 " style="margin-top:5px; font-size:16px;">
            <span > <?= $request->dropoff ?> </span>
          </div>  
        </div>
        <div class="row" style='margin-top:15px;'>
          <div class="col-md-4 col-xs-5">
            <span style='font-size:16px;'> <?=_l('Pickup_Dt').':' ?> </span> 
          </div>
          <div class="col-md-8 col-xs-7" style=" font-size:16px;">
            <span > <?= $request->pickup_dt ?> </span>
          </div>  
        </div>
        <div class="row" style='margin-top:15px;'>
          <div class="col-md-4 col-xs-5">
            <span style='font-size:16px;'> <?=_l('Passengers').':' ?> </span> 
          </div>
          <div class="col-md-8 col-xs-7" style=" font-size:16px;">
            <span > <?= $request->passengers ?> </span>
          </div>  
        </div>
        <div class="row" style='margin-top:15px;'>
          <div class="col-md-4 col-xs-5">
            <span style='font-size:16px;'> <?=_l('Vehicle_Type').':' ?> </span> 
          </div>
          <div class="col-md-8 col-xs-7" style=" font-size:16px;">
            <span > <?= $request->name ?> </span>
          </div>  
        </div>
      </div>
      <div class="col-md-6" >
        <div id='map' style='width:100%; height: 300px;'> </div>
      </div>
    </div>
  </div>
</div>
<div class="panel_s offer_price_your_offer">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-info pull-left no-mtop tickets-summary-heading"><?php echo _l('Your_Offer'); ?></h3>
      </div>
    </div>
    <?php echo form_open_multipart("purchase/vendors_portal/offer_price/".$request_id ,array('id'=>'offer-price-form')); ?>
    <div class="row" >
      <div class="col-md-12">
        <div class="form-group">
          <input type="hidden" name="request_id" value="<?=$request_id?>" >
          <label for = "vehicle_id" > <?=_l('Select a Vehicle') ?> </label>
          <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" 
              name="vehicle_id" id="vehicle_id"  class="form-control selectpicker" >
            <option value=""> </option>
            <?php foreach($vehicles as $vehicle){ ?>
              <option value="<?php echo $vehicle['item_id']; ?>" <?php echo set_select('vehicle_id',$vehicle['item_id']); ?>
                <?php if($this->input->get('vehicle_id') == $vehicle['item_id']){echo ' selected';} ?>><?php echo $vehicle['vehicle_make'].'-'.$vehicle['vehicle_model']; ?>
              </option>
            <?php } ?>
          </select>
          <?= form_error('vehicle_id'); ?>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for = "price" > <?=_l('Offer_Price') ?> </label>
          <input type="text" class="form-control" name="price" id="price" value="<?=set_value('price') ?>"> 
          <?= form_error('price'); ?>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <input type="checkbox"  id="cb_due_dt" >     
          <label for = "due_dt" > <?=_l('Offer_Due_Dt') ?> </label>
          <input type="text" class="form-control" style="display:none;" name="due_dt" id="due_dt" value="<?=set_value('due_dt') ?>"> 
          <?= form_error('due_dt'); ?>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label  > <?=_l('offer_price_exp') ?> </label>
        </div>
      </div>
      <div class="col-md-12 text-center mtop20">
        <button type="submit" class="btn btn-primary" style='border-radius:20px;' autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('Offer_Price'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>    

<script type="text/javascript" src="<?php echo site_url('assets/plugins/datatables/datatables.min.js'); ?>" > </script>
<script defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRELIrSWwZTXpwxUZESejkVWtQjtaQTWE&callback=initMap&libraries=places">
</script>
<script>
  let pickup_place, dropoff_place;
  let directionsService, directionsRenderer;
  function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: -33.8688, lng: 151.2195 },
      zoom: 13
    });
    

    //get pickup dropoff place if already set
    let pickup_val = '<?= $request->pickup?> ';
    let dropoff_val = '<?= $request->dropoff?> ';;
    let service = new google.maps.places.PlacesService(map);
    //pickup place
    let request = {
      query: pickup_val,
      fields: ["name", "geometry"]
    };
    service.findPlaceFromQuery(request, (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
          if(results.length > 0)
          {
            pickup_place = results[0];
            if (pickup_place.geometry.viewport) {
                map.fitBounds(pickup_place.geometry.viewport);
            } else {
                map.setCenter(pickup_place.geometry.location);
                map.setZoom(17); // Why 17? Because it looks good.
            }
            calculateAndDisplayRoute();
          }
      }
    });
    //drop off place
    request = {
      query: dropoff_val,
      fields: ["name", "geometry"]
    };
    
    service.findPlaceFromQuery(request, (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
          if(results.length > 0)
          {
            dropoff_place = results[0];
            if (dropoff_place.geometry.viewport) {
                map.fitBounds(dropoff_place.geometry.viewport);
            } else {
                map.setCenter(dropoff_place.geometry.location);
                map.setZoom(17); // Why 17? Because it looks good.
            }
            calculateAndDisplayRoute();
          }
      }
    });

    //direction service
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
   
  }

  function calculateAndDisplayRoute() {
      if(pickup_place == undefined || dropoff_place == undefined)
      {
         return;
      }
      directionsService.route(
         {
            origin: pickup_place.geometry.location,
            destination: dropoff_place.geometry.location,
            travelMode: google.maps.TravelMode.DRIVING
         },
         (response, status) => {
            if (status === "OK") {
            directionsRenderer.setDirections(response);
            } else {
               window.alert("Directions request failed due to " + status);
            }
         }
      );
   }
   $(document).ready(function() {
    //enable/disable change 
    $('#cb_due_dt').change( function(){
      if(this.checked)
        $('#due_dt').show();
      else $('#due_dt').hide();
    });

    //date time picker
    $('#due_dt').datetimepicker({});
   });
</script>      

