<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="_buttons">
    <a href="#" onclick="new_hierarchy(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_salesadmin_customer_group'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
    <thead>
        <th><?php echo _l('salesman'); ?></th>
        <th><?php echo _l('coordinator'); ?></th>
        <th><?php echo _l('percent_enjoyed'); ?></th>
        <th><?php echo _l('options'); ?></th>
    </thead>
    <tbody>
        <?php foreach($hierarchys as $hierarchy){ ?>
        <tr>
            <td><a href="<?php echo admin_url('staff/profile/' . $hierarchy['salesman']); ?>" target="_blank"><?php echo staff_profile_image($hierarchy['salesman'], [
                    'staff-profile-image-small',
                    ]); ?> </a>
                 <a href="<?php echo admin_url('staff/profile/' . $hierarchy['salesman']); ?>" target="_blank"><?php echo get_staff_full_name($hierarchy['salesman']);  ?> </a></td>
            <td><a href="<?php echo admin_url('staff/profile/' . $hierarchy['coordinator']); ?>" target="_blank"><?php echo staff_profile_image($hierarchy['coordinator'], [
                    'staff-profile-image-small',
                    ]); ?> </a>
                 <a href="<?php echo admin_url('staff/profile/' . $hierarchy['coordinator']); ?>" target="_blank"><?php echo get_staff_full_name($hierarchy['coordinator']);  ?> </a></td>
            <td><?php echo html_entity_decode($hierarchy['percent']); ?>%</td>
            <td>
                <a href="#" onclick="edit_hierarchy(this,<?php echo html_entity_decode($hierarchy['id']); ?>); return false;" data-salesman="<?php echo html_entity_decode($hierarchy['salesman']); ?>" data-coordinator="<?php echo html_entity_decode($hierarchy['coordinator']); ?>" data-percent="<?php echo html_entity_decode($hierarchy['percent']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i>
                </a>
                <a href="<?php echo admin_url('commission/delete_hierarchy/'.$hierarchy['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>   


<div class="modal fade" id="hierarchy_model" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <?php echo form_open_multipart(admin_url('commission/hierarchy'), array('id'=>'hierarchy_setting')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_hierarchy'); ?></span>
                    <span class="edit-title"><?php echo _l('edit_hierarchy'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div id="hierarchy_id"></div>
                <div class="row">
                    <div class="col-md-12">
                      <?php echo render_select('salesman',$staffs,array('staffid',array('firstname','lastname')),'salesman'); ?>
                    </div>

                    <div class="col-md-12">
                      <?php echo render_select('coordinator',$staffs,array('staffid',array('firstname','lastname')),'coordinator'); ?>
                    </div>

                    <div class="col-md-12">
                      <?php echo render_input('percent', 'percent_enjoyed'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                
                 <button type="submit" class="btn btn-info intext-btn"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

