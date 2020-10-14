<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <?php if (has_permission('purchase', '', 'create') || is_admin() ) { ?>

    <a href="#" onclick="new_commodity_group_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_commodity_group_type'); ?>
    </a>
<?php } ?>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
 <thead>
    <th><?php echo _l('ID'); ?></th>
    <th><?php echo _l('commodity_group_code'); ?></th>
    <th><?php echo _l('commodity_group_name'); ?></th>
    <th><?php echo _l('order'); ?></th>
    <th><?php echo _l('display'); ?></th>
    <th><?php echo _l('note'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
  <tbody>
    <?php foreach($commodity_group_types as $commodity_group_type){ ?>

    <tr>
        <td><?php echo _l($commodity_group_type['id']); ?></td>
        <td><?php echo _l($commodity_group_type['commodity_group_code']); ?></td>
        <td><?php echo _l($commodity_group_type['name']); ?></td>
        <td><?php echo _l($commodity_group_type['order']); ?></td>
        <td><?php if($commodity_group_type['display'] == 0){ echo _l('not_display'); }else{echo _l('display');} ?></td>
        <td><?php echo _l($commodity_group_type['note']); ?></td>

        <td>
            <?php if (has_permission('purchase', '', 'edit') || is_admin()) { ?>
              <a href="#" onclick="edit_commodity_group_type(this,<?php echo html_entity_decode($commodity_group_type['id']); ?>); return false;" data-commodity_group_code="<?php echo html_entity_decode($commodity_group_type['commodity_group_code']); ?>" data-name="<?php echo html_entity_decode($commodity_group_type['name']); ?>" data-order="<?php echo html_entity_decode($commodity_group_type['order']); ?>" data-display="<?php echo html_entity_decode($commodity_group_type['display']); ?>" data-note="<?php echo html_entity_decode($commodity_group_type['note']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i>
            </a>
            <?php } ?>

            <?php if (has_permission('purchase', '', 'delete') || is_admin()) { ?> 
            <a href="<?php echo admin_url('purchase/delete_commodity_group_type/'.$commodity_group_type['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
             <?php } ?>
        </td>
    </tr>
    <?php } ?>
 </tbody>
</table>   

<div class="modal1 fade" id="commodity_group_type" tabindex="-1" role="dialog">
        <div class="modal-dialog setting-handsome-table">
          <?php echo form_open_multipart(admin_url('purchase/commodity_group_type'), array('id'=>'add_commodity_group_type')); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="add-title"><?php echo _l('add_commodity_group_type'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                             <div id="commodity_group_type_id">
                             </div>   
                         <div class="form"> 
                            <div class="col-md-12" id="add_handsontable">

                            </div>
                              <?php echo form_hidden('hot_commodity_group_type'); ?>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        
                         <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_commodity_group_type(this); return false;" ><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</div>


</body>
</html>
