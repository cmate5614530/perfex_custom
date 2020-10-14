<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart('clients/new_request',array('id'=>'open-new-ticket-form')); ?>
<div class="row">
   <div class="col-md-12">

      <?php hooks()->do_action('before_client_open_ticket_form_start'); ?>

      <div class="panel_s">
         <div class="panel-heading text-uppercase open-ticket-subject">
            <?php echo _l('New_Request'); ?>
         </div>
         <div class="panel-body">
            <div class="row" >
               <div class="col-md-6" >
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group" >
                           <label for="pickup"><?php echo _l('From'); ?></label>
                           <input type="text" class="form-control" name="pickup" id="pickup" value="<?php echo set_value('pickup'); ?>">
                           <?php echo form_error('pickup'); ?>

                        </div>
                     </div> 
                     <div class="col-md-12">
                        <div class="form-group" >
                           <label for="dropoff"><?php echo _l('To'); ?></label>
                           <input type="text" class="form-control" name="dropoff" id="dropoff" value="<?php echo set_value('dropoff'); ?>">
                           <?php echo form_error('dropoff'); ?>
                        </div>
                     </div> 
                     <div class="col-md-12">
                        <div class="form-group" >
                           <label for="pickup_dt"><?php echo _l('Pickup_Dt'); ?></label>
                           <input type="text" class="form-control" name="pickup_dt" id="pickup_dt" value="<?php echo set_value('pickup_dt'); ?>">
                           <?php echo form_error('pickup_dt'); ?>
                        </div>
                     </div> 
                     <div class="col-md-12">
                        <div class="form-group" >
                           <label for="passengers"><?php echo _l('Passengers'); ?></label>
                           <input type="number" class="form-control" name="passengers" id="passengers" value="<?php echo set_value('passengers'); ?>">
                           <?php echo form_error('passengers'); ?>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group" >
                           <label for="vehicle_type"><?php echo _l('Vehicle_Type'); ?></label>
                           <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="vehicle_type" id="vehicle_type" class="form-control selectpicker">
                              <option value=""></option>
                              <?php foreach($vehicle_types as $vehicle_type){ ?>
                              <option value="<?php echo $vehicle_type['id']; ?>"
                                 <?php echo set_select('vehicle_type',$vehicle_type['id']); ?><?php if($this->input->get('vehicle_type') == $vehicle_type['id']){echo ' selected';} ?> >
                                 <?php echo $vehicle_type['name']; ?>
                              </option>
                              <?php } ?>
                           </select>
                           <?php echo form_error('vehicle_type'); ?>
                        </div>
                     </div> 
                  </div>      
               </div>
               <div class="col-md-6" id="map" style="height:300px;" >
                  map
               </div>   
            </div>
           
         </div>
      </div>
   </div>
   
   <div class="col-md-12 text-center mtop20">
      <button type="submit" class="btn btn-info" data-form="#open-new-ticket-form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('submit'); ?></button>
   </div>
</div>
<?php echo form_close(); ?>
<script defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRELIrSWwZTXpwxUZESejkVWtQjtaQTWE&callback=initMap&libraries=places">
</script>
<script>
   $(document).ready( function() {
      $('#pickup_dt').datetimepicker({});
   });
   let pickup_place, dropoff_place;
   let directionsService, directionsRenderer;
   function initMap() {
      const map = new google.maps.Map(document.getElementById("map"), {
         center: { lat: -33.8688, lng: 151.2195 },
         zoom: 13
      });
      //pickup, dropoff auto complete
      var pickup = document.getElementById('pickup');
      const pickup_complete = new google.maps.places.Autocomplete(pickup);

      var dropoff = document.getElementById('dropoff');
      const dropoff_complete = new google.maps.places.Autocomplete(dropoff);

      //get pickup dropoff place if already set
      let pickup_val = $('#pickup').val();
      let dropoff_val = $('#dropoff').val();
      let service = new google.maps.places.PlacesService(map);
      if(pickup_val != "")
      {
         const request = {
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
      }
      if(dropoff_val != "")
      {
         const request = {
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
      }

      
      
      //direction render
      directionsService = new google.maps.DirectionsService();
      directionsRenderer = new google.maps.DirectionsRenderer();
      directionsRenderer.setMap(map);


      pickup_complete.addListener( 'place_changed', () => {
         pickup_place = pickup_complete.getPlace(); 
         if(!pickup_place.geometry)
         {
            window.alert('Wrong pickup place');
         }

         if (pickup_place.geometry.viewport) {
            map.fitBounds(pickup_place.geometry.viewport);
         } else {
            map.setCenter(pickup_place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
         }
         calculateAndDisplayRoute();

      });

      dropoff_complete.addListener( 'place_changed', () => {
         dropoff_place = dropoff_complete.getPlace(); 
         if(!dropoff_place.geometry)
         {
            window.alert('Wrong pickup place');
         }

         if (dropoff_place.geometry.viewport) {
            map.fitBounds(dropoff_place.geometry.viewport);
         } else {
            map.setCenter(dropoff_place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
         }

         calculateAndDisplayRoute();
      });
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

      
</script>
