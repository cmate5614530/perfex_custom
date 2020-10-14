<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <a href="#" onclick="new_unit_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_unit_type'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
 <thead>
    <th><?php echo _l('ID'); ?></th>
    <th><?php echo _l('unit_code'); ?></th>
    <th><?php echo _l('unit_name'); ?></th>
    <th><?php echo _l('unit_symbol'); ?></th>
    <th><?php echo _l('order'); ?></th>
    <th><?php echo _l('display'); ?></th>
    <th><?php echo _l('note'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
  <tbody>
    <?php foreach($unit_types as $unit_type){ ?>

    <tr>
        <td><?php echo _l($unit_type['unit_type_id']); ?></td>
        <td><?php echo _l($unit_type['unit_code']); ?></td>
        <td><?php echo _l($unit_type['unit_name']); ?></td>
        <td><?php echo _l($unit_type['unit_symbol']); ?></td>
        <td><?php echo _l($unit_type['order']); ?></td>
        <td><?php if($unit_type['display'] == 0){ echo _l('not_display'); }else{echo _l('display');} ?></td>
        <td><?php echo _l($unit_type['note']); ?></td>

        <td>
            <?php if (has_permission('purchase', '', 'edit') || is_admin()) { ?>
              <a href="#" onclick="edit_unit_type(this,<?php echo html_entity_decode($unit_type['unit_type_id']); ?>); return false;" data-unit_code="<?php echo html_entity_decode($unit_type['unit_code']); ?>" data-unit_name="<?php echo html_entity_decode($unit_type['unit_name']); ?>" data-unit_symbol="<?php echo html_entity_decode($unit_type['unit_symbol']); ?>" data-order="<?php echo html_entity_decode($unit_type['order']); ?>" data-display="<?php echo html_entity_decode($unit_type['display']); ?>" data-note="<?php echo html_entity_decode($unit_type['note']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i>
            </a>
            <?php } ?>

            <?php if (has_permission('purchase', '', 'delete') || is_admin()) { ?> 
            <a href="<?php echo admin_url('purchase/delete_unit_type/'.$unit_type['unit_type_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
             <?php } ?>
        </td>
    </tr>
    <?php } ?>
 </tbody>
</table>   

<div class="modal1 fade" id="unit_type" tabindex="-1" role="dialog">
        <div class="modal-dialog setting-handsome-table">
          <?php echo form_open_multipart(admin_url('purchase/unit_type'), array('id'=>'add_unit_type')); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="add-title"><?php echo _l('add_unit_type'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                             <div id="unit_type_id">
                             </div>   
                         <div class="form"> 
                            <div class="col-md-12" id="add_handsontable">

                            </div>
                              <?php echo form_hidden('hot_unit_type'); ?>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        
                         <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_unit_type(this); return false;" ><?php echo _l('submit'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
                <?php echo form_close(); ?>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->    
</div>
<?php require 'modules/purchase/assets/js/unit_js.php';?>

</body>
</html>
