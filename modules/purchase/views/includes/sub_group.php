<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
<div class="_buttons">
    <?php if (has_permission('purchase', '', 'create') || is_admin() ) { ?>

    <a href="#" onclick="new_sub_group_type(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_sub_group_type'); ?>
    </a>
<?php } ?>

</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table">
 <thead>
    <th><?php echo _l('ID'); ?></th>
    <th><?php echo _l('sub_group_code'); ?></th>
    <th><?php echo _l('sub_group_name'); ?></th>
    <th><?php echo _l('group_name'); ?></th>
    <th><?php echo _l('order'); ?></th>
    <th><?php echo _l('display'); ?></th>
    <th><?php echo _l('note'); ?></th>
    <th><?php echo _l('options'); ?></th>
 </thead>
  <tbody>
    <?php foreach($sub_groups as $sub_group_type){ ?>
    <?php 
        $group_name='';
        if($sub_group_type['group_id']){
            $group = get_group_name_pur($sub_group_type['group_id']);
            if($group){
                $group_name = $group->name;
            }
        }
     ?>
    <tr>
        <td><?php echo html_entity_decode($sub_group_type['id']); ?></td>
        <td><?php echo html_entity_decode($sub_group_type['sub_group_code']); ?></td>
        <td><?php echo html_entity_decode($sub_group_type['sub_group_name']); ?></td>
        <td><?php echo html_entity_decode($group_name); ?></td>
        <td><?php echo html_entity_decode($sub_group_type['order']); ?></td>
        <td><?php if($sub_group_type['display'] == 0){ echo _l('not_display'); }else{echo _l('display');} ?></td>
        <td><?php echo html_entity_decode($sub_group_type['note']); ?></td>

        <td>
            <?php if (has_permission('purchase', '', 'edit') || is_admin()) { ?>
              <a href="#" onclick="edit_sub_group_type(this,<?php echo html_entity_decode($sub_group_type['id']); ?>); return false;" data-sub_group_code="<?php echo html_entity_decode($sub_group_type['sub_group_code']); ?>" data-name="<?php echo html_entity_decode($sub_group_type['sub_group_name']); ?>" data-group_id="<?php echo html_entity_decode($sub_group_type['group_id']); ?>" data-order="<?php echo html_entity_decode($sub_group_type['order']); ?>" data-display="<?php echo html_entity_decode($sub_group_type['display']); ?>" data-note="<?php echo html_entity_decode($sub_group_type['note']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i>
            </a>
            <?php } ?>

            <?php if (has_permission('purchase', '', 'delete') || is_admin()) { ?> 
            <a href="<?php echo admin_url('purchase/delete_sub_group/'.$sub_group_type['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
             <?php } ?>
        </td>
    </tr>
    <?php } ?>
 </tbody>
</table>   

<div class="modal1 fade" id="sub_group_type" tabindex="-1" role="dialog">
        <div class="modal-dialog setting-handsome-table">
          <?php echo form_open_multipart(admin_url('purchase/sub_group'), array('id'=>'add_sub_group')); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="add-title"><?php echo _l('add_sub_group_type'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                             <div id="sub_group_type_id">
                             </div>   
                         <div class="form"> 
                            <div class="col-md-12" id="add_handsontable">

                            </div>
                              <?php echo form_hidden('hot_sub_group'); ?>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        
                         <button id="latch_assessor" type="button" class="btn btn-info intext-btn" onclick="add_sub_group_type(this); return false;" ><?php echo _l('submit'); ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</div>

</body>
</html>
