<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="_buttons">
    <a href="#" onclick="new_salesadmin_customer_group(); return false;" class="btn btn-info pull-left display-block">
        <?php echo _l('add_salesadmin_customer_group'); ?>
    </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<div class="clearfix"></div>
<table class="table dt-table border table-striped">
    <thead>
        <th><?php echo _l('customer_group'); ?></th>
        <th><?php echo _l('sale_admin'); ?></th>
        <th><?php echo _l('options'); ?></th>
    </thead>
    <tbody>
        <?php foreach($salesadmin_customer_groups as $value){ ?>
        <tr>
            
            <td><?php echo html_entity_decode($value['customer_group_name']); ?></td>
            <td><a href="<?php echo admin_url('staff/profile/' . $value['salesadmin']); ?>" target="_blank"><?php echo staff_profile_image($value['salesadmin'], [
                    'staff-profile-image-small',
                    ]); ?> </a>
                <a href="<?php echo admin_url('staff/profile/' . $value['salesadmin']); ?>" target="_blank"><?php echo get_staff_full_name($value['salesadmin']);  ?> </a></td>
            <td>
                <a href="#" onclick="edit_salesadmin_customer_group(this,<?php echo html_entity_decode($value['id']); ?>); return false;" data-customer_group="<?php echo html_entity_decode($value['customer_group']); ?>" data-salesadmin="<?php echo html_entity_decode($value['salesadmin']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i>
                </a>
                <a href="<?php echo admin_url('commission/delete_salesadmin_customer_group/'.$value['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>   


<div class="modal fade" id="salesadmin_customer_group_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <?php echo form_open_multipart(admin_url('commission/salesadmin_customer_group'), array('id'=>'salesadmin_customer_group_setting')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('add_salesadmin_customer_group'); ?></span>
                    <span class="edit-title"><?php echo _l('edit_salesadmin_customer_group'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div id="salesadmin_group_id"></div>
                <div class="row">
                    <div class="col-md-12">
                      <?php echo render_select('customer_group',$customer_groups,array('id','name'),'customer_group'); ?>
                    </div>

                    <div class="col-md-12">
                      <?php echo render_select('salesadmin',$staffs,array('staffid',array('firstname','lastname')),'sale_admin'); ?>
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

