<div class="col-md-12">
<div class="panel_s">
  <div class="panel-body">
    
      <div class="row col-md-12">

        <h4 class="h4-color"><?php echo _l('general_infor'); ?></h4>
        <hr class="hr-color">

        <div class="col-md-6">
          <div class="gallery">
            <div class="wrapper-masonry">
              <div id="masonry" class="masonry-layout columns-2">
            <?php if(isset($item_file) && count($item_file) > 0){ ?>
              <?php foreach ($item_file as $key => $value) { ?>
                  <?php if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$value["rel_id"].'/'.$value["file_name"])){ ?>
                        <a  class="images_w_table" href="<?php echo site_url('modules/purchase/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]); ?>"><img class="images_w_table" src="<?php echo site_url('modules/purchase/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]); ?>" alt="<?php echo html_entity_decode($value['file_name']) ?>"/></a>
                    <?php }else{ ?>
                       <a  class="images_w_table" href="<?php echo site_url('modules/warehouse/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]); ?>"><img class="images_w_table" src="<?php echo site_url('modules/warehouse/uploads/item_img/'.$value["rel_id"].'/'.$value["file_name"]); ?>" alt="<?php echo html_entity_decode($value['file_name']) ?>"/></a>
                    <?php } ?>
            <?php } ?>
          <?php }else{ ?>

                <a href="<?php echo site_url('modules/purchase/uploads/nul_image.jpg'); ?>"><img class="images_w_table" src="<?php echo site_url('modules/purchase/uploads/nul_image.jpg'); ?>" alt="nul_image.jpg"/></a>

          <?php } ?>
            <div class="clear"></div>
          </div>
        </div>
        </div>
        </div>
        
        <div class="col-md-6 panel-padding">
          <table class="table border table-striped no-margin">
              <tbody>
                  <tr class="project-overview">
                    <td class="bold" width="30%"><?php echo _l('Vehicle_Make'); ?></td>
                    <td><?php echo html_entity_decode($item->vehicle_make) ; ?></td>
                 </tr>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('Vehicle_Model'); ?></td>
                    <td><?php echo html_entity_decode($item->vehicle_model) ; ?></td>
                 </tr>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('Vehicle_Type'); ?></td>
                    <td><?php echo get_group_name_item(html_entity_decode($item->group_id)) != null ? get_group_name_item(html_entity_decode($item->group_id))->name : '' ; ?></td>
                 </tr>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('Number_Of_Passengers'); ?></td>
                    <td><?php echo html_entity_decode($item->number_of_passengers) ; ?></td>
                 </tr>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('Number_Of_Suitcases'); ?></td>
                    <td><?php echo html_entity_decode($item->number_of_suitcases) ; ?></td>
                 </tr>
                 <tr class="project-overview">
                    <td class="bold"><?php echo _l('Base_Location'); ?></td>
                    <td><?php echo html_entity_decode($item->base_location) ; ?></td>
                 </tr>
                </tbody>
          </table>
      </div>
    </div>
      <div class="col-md-12">
      <?php if(isset($inventory_item)){ 
            foreach ($inventory_item as $value) {
              $purchase_code = $value['purchase_code'] ? $value['purchase_code'] :'' ;
              $inventory_number = $value['inventory_number'] ? $value['inventory_number'] :'' ;
              $unit_name = $value['unit_name'] ? $value['unit_name'] :'' ;
        ?>
        <div class="col-md-3 bg-c-blue card1" >
            <div class="card-block">
                <h3 class="text-right h3-card-block-margin"><i class="fa fa-cart-plus f-left"></i><span class="h3-span-font-size"><?php echo html_entity_decode($purchase_code); ?></span></h3>
                <p class="m-b-0 p-card-block-font-size"><?php echo _l('inventory_number') ;?><span class="f-right p-card-block-font-size" ><?php echo html_entity_decode($inventory_number); ?></span></p>
            </div>
        </div>
        <?php } ?>
      <?php } ?>
      </div>
    </div>
    </div>
  </div>
<script type="text/javascript">
  (function() {
        var gallery = new SimpleLightbox('.gallery a', {});
    })();
</script>