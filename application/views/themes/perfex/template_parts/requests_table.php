<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<table class="table dt-table table-tickets" >
  <thead>
    <th width="10%" class="th-ticket-number"><?php echo _l('requests_table_id'); ?></th>
    <th class="th-ticket-subject"><?php echo _l('requests_table_pickup_place'); ?></th>
    <th class="th-ticket-subject"><?php echo _l('requests_table_dropoff_place'); ?></th>
    <th class="th-ticket-subject"><?php echo _l('requests_table_pickup_dt'); ?></th>

    <?php if($show_submitter_on_table) { ?>
      <th class="th-ticket-submitter"><?php echo _l('requests_table_pickdrop'); ?></th>
    <?php } ?>
    <th class="th-ticket-department"><?php echo _l('requests_table_passengers'); ?></th>
    <th class="th-ticket-service"><?php echo _l('requests_table_vehicle_type'); ?></th>
    <th class="th-ticket-priority"><?php echo _l('Status'); ?></th>
    <th class="th-ticket-priority" style="min-width:100px;"><?php echo _l('Actions'); ?></th>
  </thead>
  <tbody>
    <?php foreach($requests as $request){ ?>
      <tr >
        <td data-order="<?php echo $request['request_id']; ?>">
          <a href="<?php echo site_url('clients/ticket/'.$request['ticketid']); ?>">
            #<?php echo $request['request_id']; ?>
          </a>
        </td>
        <td>
          <?php echo $request['pickup']; ?>
        </td>
        <td>
          <?php echo $request['dropoff']; ?>
        </td>
        <td>
          <?php echo $request['pickup_dt']; ?>
        </td>
        <td>
          <i class="fa fa-male" > x </i>
          <?php echo $request['passengers']; ?>
        </td>

        <td>
          <?php echo $request['vehicle_type_name']; ?>
        </td>
        <td>
          <?php if($request['status'] == 'active') { ?>
            <span class="btn btn-primary" style="border-radius:15px" > <?=_l('Active')?> </span>
          <?php } ?>
          <?php if($request['status'] == 'cancelled') { ?>
            <span class="btn btn-danger" style="border-radius:15px" > <?=_l('Cancelled')?> </span>
          <?php } ?>
          <?php if($request['status'] == 'accepted') { ?>
            <span class="btn btn-success" style="border-radius:15px" > 
            <i class="fa fa-check"> </i> <?=_l('Accepted')?> 
            </span>
          <?php } ?>
        </td>
        <td>
          <a href="<?php echo site_url('clients/offers/'.$request['request_id']); ?>" class="btn btn-success" 
            style="border-radius: 15px;">
            <?php echo _l('View_Offers'); ?>
            
          </a>
            <?php if($request['offers_count'] != 0) { ?>
              <span class="badge btn-danger" 
              style='background-color: #fc2d42; color: white; margin-left:-18px; margin-top: -30px;'> 
                <?=$request['offers_count']?> 
              </span>
            <?php } ?>  
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
