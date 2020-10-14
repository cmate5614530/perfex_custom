<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade email-template" data-editor-id=".<?php echo 'tinymce-'.$receipt->id; ?>" id="receipt_send_to_salesperson" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open('admin/commission/send_to_email/'.$receipt->id); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('send_payment_receipt_to_salesperson'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php

                            $selected = array();
                            $contacts = [];
                            $list_staff_isset = [];
                            $list_contact_isset = [];
                            foreach ($receipt->list_commission as $key => $value) {
                                if($value['is_client'] == 1){
                                    if(!in_array($value['staffid'], $list_contact_isset)){
                                        $contacts_ = $this->clients_model->get_contacts($value['staffid'],array('active'=>1, 'invoice_emails'=>1));
                                        foreach($contacts_ as $contact){
                                            array_push($selected,$contact['email']);
                                            array_push($contacts,['id' => $contact['email'], 'email' => $contact['email'], 'name' =>  _l('contact').': '.$contact['firstname'].' '.$contact['lastname']]);
                                        }
                                        $list_contact_isset[] = $value['staffid'];
                                    }
                                }else {
                                    if(!in_array($value['staffid'], $list_staff_isset)){
                                        $staff =$this->staff_model->get($value['staffid']);
                                        array_push($selected,$staff->email);
                                        array_push($contacts,['id' => $staff->email, 'email' => $staff->email, 'name' => _l('staff').': '.get_staff_full_name($value['staffid'])]);
                                        $list_staff_isset[] = $value['staffid'];
                                    }
                                }
                            }

                            echo render_select('sent_to[]', $contacts, array('id', 'email', 'name'), 'invoice_estimate_sent_to_email', $selected, array( 'multiple'=>true ), array(), '', '', false);

                            ?>
                        </div>
                        <hr />
                        <?php echo render_textarea('email_template_custom', '', '', array(), array(), '', 'tinymce-'.$receipt->id); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info"><?php echo _l('send'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
