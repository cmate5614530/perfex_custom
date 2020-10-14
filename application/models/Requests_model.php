<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Requests_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('purchase_model');
    }


     /**
     * Gets the requests by client.
     *
     * @param      $client  The client
     */
    public function get_requests_by_vendor($supplier_id){
        $this->db->select('*, ' .db_prefix() . 'items_groups.name as vehicle_type_name, '. db_prefix() . 'requests.id as request_id');
        $this->db->join(db_prefix() . 'items_groups', db_prefix() . 'items_groups.id = ' . db_prefix() . 'requests.vehicle_type', 'left');
        //$this->db->where('client_id',$client);

        $requests = $this->db->get(db_prefix().'requests')->result_array();  

        for($i = 0; $i < count($requests); $i++)
        {
            $requests[$i]['offer_sent'] = 0;
            $this->db->select('*');
            $this->db->where('request_id', $requests[$i]['request_id']);
            $this->db->where('supplier_id', $supplier_id);
            acer_log('get_requests_by_vendor, rid, sid = '.$requests[$i]['request_id'].$supplier_id);
            $total_rows = $this->db->count_all_results(db_prefix().'offers');
            if($total_rows > 0)
                $requests[$i]['offer_sent'] = 1;
        }
        return $requests;
    }

    public function get_requests_by_client( $client_id )
    {
        $this->db->select('*, ' .db_prefix() . 'items_groups.name as vehicle_type_name, '. db_prefix() . 'requests.id as request_id');
        $this->db->join(db_prefix() . 'items_groups', db_prefix() . 'items_groups.id = ' . db_prefix() . 'requests.vehicle_type', 'left');

        $where = db_prefix(). 'requests.client_id='.$client_id;
        $this->db->where($where);
        $requests = $this->db->get(db_prefix() . 'requests')->result_array();
        for($i = 0; $i < count($requests); $i++)
        {
            $status = $requests[ $i ] ['status']; 
            $request_id = $requests[$i]['request_id'];
            $this->db->select('*');
            $this->db->where('request_id', $request_id);
            $total_rows = $this->db->count_all_results(db_prefix().'offers');
            $requests[$i]['offers_count'] = $total_rows;

            if($status == 'active')
            {
                $this->db->select('*');
                $this->db->where('request_id', $request_id);
                $this->db->where('status', 'accepted');
                $total_rows = $this->db->count_all_results(db_prefix().'offers');
                if($total_rows > 0)
                    $requests[$i]['status'] = 'accepted';
            }
        }
        return $requests;
    }

    //9.24 AG: get request by id
    public function get_request_by_id($request_id)
    {
        $this->db->select('*, ' .db_prefix() . 'items_groups.name as vehicle_type_name, '. db_prefix() . 'requests.id as request_id');
        $this->db->join(db_prefix() . 'items_groups', db_prefix() . 'items_groups.id = ' . db_prefix() . 'requests.vehicle_type', 'left');

        $this->db->where(db_prefix().'requests.id='.$request_id);

        return $this->db->get(db_prefix() . 'requests')->row();
    }
    //9.24 AG: get request by id : end
    
    /**
     * Gets the offers by vendor and item.
     *
     * @param      $vendor  The vendor
     */
    public function get_offers_by_vendor_request($vendor='', $request=''){
        $this->db->select('*,'.db_prefix().'offers.id as id, '.db_prefix().'offers.status as status');
        $this->db->join(db_prefix() . 'items', db_prefix() . 'items.id=' . db_prefix() . 'offers.vehicle_id', 'left');
        $this->db->join(db_prefix() . 'invoices', db_prefix() . 'offers.invoice_id=' . db_prefix() . 'invoices.id', 'left');
        
        if($vendor != '')
            $this->db->where(db_prefix().'offers.supplier_id', $vendor);
        if($request != '')
            $this->db->where(db_prefix().'offers.request_id', $request);
        $offers =  $this->db->get(db_prefix().'offers')->result_array();  
        for($i = 0; $i < count($offers); $i++)
        {
            $this->db->order_by('dateadded', 'desc');
            $this->db->where('rel_id', $offers[$i]['vehicle_id']);
            $this->db->where('rel_type', 'commodity_item_file');
            $arr_images = $this->db->get(db_prefix() . 'files')->result_array();

            $url = '';
            if(count($arr_images) > 0){
                if(file_exists(PURCHASE_MODULE_ITEM_UPLOAD_FOLDER .$arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name'])){
                    $url = site_url('modules/purchase/uploads/item_img/' . $arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name']);
                }else{
                    $url = site_url('modules/warehouse/uploads/item_img/' . $arr_images[0]['rel_id'] .'/'.$arr_images[0]['file_name']);
                }

            }else{

                $url = site_url('modules/purchase/uploads/nul_image.jpg' );
            }
            $offers[$i]['img_url'] = $url;
        }

        return $offers;
            
        
    }

    //9.24 AG: get offer by id
    public function get_offer_by_id($offer_id)
    {
        $this->db->select('*');
        $this->db->where(db_prefix().'offers.id='.$offer_id);
        return $this->db->get(db_prefix() . 'offers')->row();
    }
    //9.24 AG: get offer by id : end

     /**
     * Add new request to database
     * @param mixed $data  ticket $_POST data
     */
    public function add($data)
    {
        acer_log('requests_model.php: add');
        
        $data['client_id']    = get_client_user_id();    
        $data['post_date']      = date('Y-m-d H:i:s');
        $data['request_key'] = app_generate_hash();


        $this->db->insert(db_prefix() . 'requests', $data);
        $request_id = $this->db->insert_id();
        return $request_id;
    }

     /**
     * Add new offer to database
     * @param mixed $data  ticket $_POST data
     */
    public function add_offer($data)
    {
        
        
        $data['supplier_id']    = get_client_user_id();    
        $data['offer_key'] = app_generate_hash();

        //acer_log('requests_model.php: add_offer ');
        $this->db->insert(db_prefix() . 'offers', $data);
        //acer_log($this->db->error() );
        $offer_id = $this->db->insert_id();
        return $offer_id;
    }
    
    //AG 9.25: make a new  invoice from offer
    public function convert_to_invoice($offer_id)
    {
        // Recurring invoice date is okey lets convert it to new invoice
        $offer = $this->get_offer_by_id($offer_id);
        $request = $this->get_request_by_id($offer->request_id);

        $new_invoice_data = [];
        $new_invoice_data['clientid']   = $request->client_id;
        $new_invoice_data['project_id'] = $_estimate->project_id;
        $new_invoice_data['number']     = get_option('next_invoice_number');
        $new_invoice_data['date']       = _d(date('Y-m-d'));
        $new_invoice_data['duedate']    = _d(date('Y-m-d'));
        if (get_option('invoice_due_after') != 0) {
            $new_invoice_data['duedate'] = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
        }
        $new_invoice_data['show_quantity_as'] = 1;
        $new_invoice_data['currency']         = 1;
        $new_invoice_data['subtotal']         = $offer->price;
        $new_invoice_data['total']            = $offer->price;
        $new_invoice_data['adjustment']       = $_estimate->adjustment;
        $new_invoice_data['discount_percent'] = $_estimate->discount_percent;
        $new_invoice_data['discount_total']   = $_estimate->discount_total;
        $new_invoice_data['discount_type']    = '';
        // Since version 1.0.6
        $new_invoice_data['billing_street']   = clear_textarea_breaks($_estimate->billing_street);
        $new_invoice_data['billing_city']     = $_estimate->billing_city;
        $new_invoice_data['billing_state']    = $_estimate->billing_state;
        $new_invoice_data['billing_zip']      = $_estimate->billing_zip;
        $new_invoice_data['billing_country']  = $_estimate->billing_country;
        $new_invoice_data['terms']                    = get_option('predefined_terms_invoice');
        $new_invoice_data['clientnote']               = get_option('predefined_clientnote_invoice');
        // Set to unpaid status automatically
        $new_invoice_data['status']    = 1;
        $new_invoice_data['adminnote'] = '';

        $this->load->model('payment_modes_model');
        $modes = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);
        $temp_modes = [];
        foreach ($modes as $mode) {
            if ($mode['selected_by_default'] == 0) {
                continue;
            }
            $temp_modes[] = $mode['id'];
        }
        $new_invoice_data['allowed_payment_modes'] = $temp_modes;
        $new_invoice_data['newitems']              = [];
        //$key                                       = 1;
        // foreach ($_estimate->items as $item) {
        //     $new_invoice_data['newitems'][$key]['description']      = $item['description'];
        //     $new_invoice_data['newitems'][$key]['long_description'] = clear_textarea_breaks($item['long_description']);
        //     $new_invoice_data['newitems'][$key]['qty']              = $item['qty'];
        //     $new_invoice_data['newitems'][$key]['unit']             = $item['unit'];
        //     $new_invoice_data['newitems'][$key]['taxname']          = [];
        //     $taxes                                                  = get_estimate_item_taxes($item['id']);
        //     foreach ($taxes as $tax) {
        //         // tax name is in format TAX1|10.00
        //         array_push($new_invoice_data['newitems'][$key]['taxname'], $tax['taxname']);
        //     }
        //     $new_invoice_data['newitems'][$key]['rate']  = $item['rate'];
        //     $new_invoice_data['newitems'][$key]['order'] = $item['item_order'];
        //     $key++;
        // }
        $this->load->model('invoices_model');
        $id = $this->invoices_model->add($new_invoice_data);
        acer_log('requests_model: conver_to_invoice:error ');
        acer_log( $this->db->error() );
        return $id;
    }
    /**
     * Add new reply to ticket
     * @param mixed $data  reply $_POST data
     * @param mixed $id    ticket id
     * @param boolean $admin staff id if is staff making reply
     */
    public function add_reply($data, $id, $admin = null, $pipe_attachments = false)
    {
        if (isset($data['assign_to_current_user'])) {
            $assigned = get_staff_user_id();
            unset($data['assign_to_current_user']);
        }

        $unsetters = [
            'note_description',
            'department',
            'priority',
            'subject',
            'assigned',
            'project_id',
            'service',
            'status_top',
            'attachments',
            'DataTables_Table_0_length',
            'DataTables_Table_1_length',
            'custom_fields',
        ];

        foreach ($unsetters as $unset) {
            if (isset($data[$unset])) {
                unset($data[$unset]);
            }
        }

        if ($admin !== null) {
            $data['admin'] = $admin;
            $status        = $data['status'];
        } else {
            $status = 1;
        }

        if (isset($data['status'])) {
            unset($data['status']);
        }

        $cc = '';
        if (isset($data['cc'])) {
            $cc = $data['cc'];
            unset($data['cc']);
        }

        $data['ticketid'] = $id;
        $data['date']     = date('Y-m-d H:i:s');
        $data['message']  = trim($data['message']);

        if ($this->piping == true) {
            $data['message'] = preg_replace('/\v+/u', '<br>', $data['message']);
        }

        // admin can have html
        if ($admin == null && hooks()->apply_filters('ticket_message_without_html_for_non_admin', true)) {
            $data['message'] = _strip_tags($data['message']);
            $data['message'] = nl2br_save_html($data['message']);
        }

        if (!isset($data['userid'])) {
            $data['userid'] = 0;
        }

        $data['message'] = remove_emojis($data['message']);
        $data            = hooks()->apply_filters('before_ticket_reply_add', $data, $id, $admin);

        $this->db->insert(db_prefix() . 'ticket_replies', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            /**
             * When a ticket is in status "In progress" and the customer reply to the ticket
             * it changes the status to "Open" which is not normal.
             *
             * The ticket should keep the status "In progress"
             */
            $this->db->select('status');
            $this->db->where('ticketid', $id);
            $old_ticket_status = $this->db->get(db_prefix() . 'tickets')->row()->status;

            $newStatus = hooks()->apply_filters(
                'ticket_reply_status',
                ($old_ticket_status == 2 && $admin == null ? $old_ticket_status : $status),
                ['ticket_id' => $id, 'reply_id' => $insert_id, 'admin' => $admin, 'old_status' => $old_ticket_status]
            );

            if (isset($assigned)) {
                $this->db->where('ticketid', $id);
                $this->db->update(db_prefix() . 'tickets', [
                    'assigned' => $assigned,
                ]);
            }

            if ($pipe_attachments != false) {
                $this->process_pipe_attachments($pipe_attachments, $id, $insert_id);
            } else {
                $attachments = handle_ticket_attachments($id);
                if ($attachments) {
                    $this->tickets_model->insert_ticket_attachments_to_database($attachments, $id, $insert_id);
                }
            }

            $_attachments = $this->get_ticket_attachments($id, $insert_id);

            log_activity('New Ticket Reply [ReplyID: ' . $insert_id . ']');

            $this->db->where('ticketid', $id);
            $this->db->update(db_prefix() . 'tickets', [
                'lastreply'  => date('Y-m-d H:i:s'),
                'status'     => $newStatus,
                'adminread'  => 0,
                'clientread' => 0,
            ]);

            if ($old_ticket_status != $newStatus) {
                hooks()->do_action('after_ticket_status_changed', [
                    'id'     => $id,
                    'status' => $newStatus,
                ]);
            }

            $ticket    = $this->get_ticket_by_id($id);
            $userid    = $ticket->userid;
            $isContact = false;
            if ($ticket->userid != 0 && $ticket->contactid != 0) {
                $email     = $this->clients_model->get_contact($ticket->contactid)->email;
                $isContact = true;
            } else {
                $email = $ticket->ticket_email;
            }
            if ($admin == null) {
                $this->load->model('departments_model');
                $this->load->model('staff_model');
                $staff = $this->staff_model->get('', ['active' => 1]);

                $notifiedUsers                           = [];
                $notificationForStaffMemberOnTicketReply = get_option('receive_notification_on_new_ticket_replies') == 1;

                foreach ($staff as $staff_key => $member) {
                    if (get_option('access_tickets_to_none_staff_members') == 0
                         && !is_staff_member($member['staffid'])) {
                        continue;
                    }

                    $staff_departments = $this->departments_model->get_staff_departments($member['staffid'], true);

                    if (in_array($ticket->department, $staff_departments)) {
                        send_mail_template('ticket_new_reply_to_staff', $ticket, $member, $_attachments);

                        if ($notificationForStaffMemberOnTicketReply) {
                            $notified = add_notification([
                                    'description'     => 'not_new_ticket_reply',
                                    'touserid'        => $member['staffid'],
                                    'fromcompany'     => 1,
                                    'fromuserid'      => 0,
                                    'link'            => 'tickets/ticket/' . $id,
                                    'additional_data' => serialize([
                                        $ticket->subject,
                                    ]),
                                ]);
                            if ($notified) {
                                array_push($notifiedUsers, $member['staffid']);
                            }
                        }
                    }
                }
                pusher_trigger_notification($notifiedUsers);
            } else {
                $sendEmail = true;
                if ($isContact && total_rows(db_prefix() . 'contacts', ['ticket_emails' => 1, 'id' => $ticket->contactid]) == 0) {
                    $sendEmail = false;
                }
                if ($sendEmail) {
                    send_mail_template('ticket_new_reply_to_customer', $ticket, $email, $_attachments, $cc);
                }
            }
            hooks()->do_action('after_ticket_reply_added', [
                'data'    => $data,
                'id'      => $id,
                'admin'   => $admin,
                'replyid' => $insert_id,
            ]);

            return $insert_id;
        }

        return false;
    }

    /**
     *  Delete ticket reply
     * @param   mixed $ticket_id    ticket id
     * @param   mixed $reply_id     reply id
     * @return  boolean
     */
    public function delete_ticket_reply($ticket_id, $reply_id)
    {
        hooks()->do_action('before_delete_ticket_reply', ['ticket_id' => $ticket_id, 'reply_id' => $reply_id]);

        $this->db->where('id', $reply_id);
        $this->db->delete(db_prefix() . 'ticket_replies');

        if ($this->db->affected_rows() > 0) {
            // Get the reply attachments by passing the reply_id to get_ticket_attachments method
            $attachments = $this->get_ticket_attachments($ticket_id, $reply_id);
            if (count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $this->delete_ticket_attachment($attachment['id']);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Remove ticket attachment by id
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_ticket_attachment($id)
    {
        $deleted = false;
        $this->db->where('id', $id);
        $attachment = $this->db->get(db_prefix() . 'ticket_attachments')->row();
        if ($attachment) {
            if (unlink(get_upload_path_by_type('ticket') . $attachment->ticketid . '/' . $attachment->file_name)) {
                $this->db->where('id', $attachment->id);
                $this->db->delete(db_prefix() . 'ticket_attachments');
                $deleted = true;
            }
            // Check if no attachments left, so we can delete the folder also
            $other_attachments = list_files(get_upload_path_by_type('ticket') . $attachment->ticketid);
            if (count($other_attachments) == 0) {
                delete_dir(get_upload_path_by_type('ticket') . $attachment->ticketid);
            }
        }

        return $deleted;
    }

    /**
     * Get ticket attachment by id
     * @param  mixed $id attachment id
     * @return mixed
     */
    public function get_ticket_attachment($id)
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix() . 'ticket_attachments')->row();
    }

    /**
     * This functions is used when staff open client ticket
     * @param  mixed $userid client id
     * @param  mixed $id     ticketid
     * @return array
     */
    public function get_user_other_tickets($userid, $id)
    {
        $this->db->select(db_prefix() . 'departments.name as department_name, ' . db_prefix() . 'services.name as service_name,' . db_prefix() . 'tickets_status.name as status_name,' . db_prefix() . 'staff.firstname as staff_firstname, ' . db_prefix() . 'clients.lastname as staff_lastname,ticketid,subject,firstname,lastname,lastreply');
        $this->db->from(db_prefix() . 'tickets');
        $this->db->join(db_prefix() . 'departments', db_prefix() . 'departments.departmentid = ' . db_prefix() . 'tickets.department', 'left');
        $this->db->join(db_prefix() . 'tickets_status', db_prefix() . 'tickets_status.ticketstatusid = ' . db_prefix() . 'tickets.status', 'left');
        $this->db->join(db_prefix() . 'services', db_prefix() . 'services.serviceid = ' . db_prefix() . 'tickets.service', 'left');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'tickets.userid', 'left');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'tickets.admin', 'left');
        $this->db->where(db_prefix() . 'tickets.userid', $userid);
        $this->db->where(db_prefix() . 'tickets.ticketid !=', $id);
        $tickets = $this->db->get()->result_array();
        $i       = 0;
        foreach ($tickets as $ticket) {
            $tickets[$i]['submitter'] = $ticket['firstname'] . ' ' . $ticket['lastname'];
            unset($ticket['firstname']);
            unset($ticket['lastname']);
            $i++;
        }

        return $tickets;
    }

    /**
     * Get all ticket replies
     * @param  mixed  $id     ticketid
     * @param  mixed $userid specific client id
     * @return array
     */
    public function get_ticket_replies($id)
    {
        $ticket_replies_order = get_option('ticket_replies_order');
        // backward compatibility for the action hook
        $ticket_replies_order = hooks()->apply_filters('ticket_replies_order', $ticket_replies_order);

        $this->db->select(db_prefix() . 'ticket_replies.id,' . db_prefix() . 'ticket_replies.name as from_name,' . db_prefix() . 'ticket_replies.email as reply_email, ' . db_prefix() . 'ticket_replies.admin, ' . db_prefix() . 'ticket_replies.userid,' . db_prefix() . 'staff.firstname as staff_firstname, ' . db_prefix() . 'staff.lastname as staff_lastname,' . db_prefix() . 'contacts.firstname as user_firstname,' . db_prefix() . 'contacts.lastname as user_lastname,message,date,contactid');
        $this->db->from(db_prefix() . 'ticket_replies');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'ticket_replies.userid', 'left');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'ticket_replies.admin', 'left');
        $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.id = ' . db_prefix() . 'ticket_replies.contactid', 'left');
        $this->db->where('ticketid', $id);
        $this->db->order_by('date', $ticket_replies_order);
        $replies = $this->db->get()->result_array();
        $i       = 0;
        foreach ($replies as $reply) {
            if ($reply['admin'] !== null || $reply['admin'] != 0) {
                // staff reply
                $replies[$i]['submitter'] = $reply['staff_firstname'] . ' ' . $reply['staff_lastname'];
            } else {
                if ($reply['contactid'] != 0) {
                    $replies[$i]['submitter'] = $reply['user_firstname'] . ' ' . $reply['user_lastname'];
                } else {
                    $replies[$i]['submitter'] = $reply['from_name'];
                }
            }
            unset($replies[$i]['staff_firstname']);
            unset($replies[$i]['staff_lastname']);
            unset($replies[$i]['user_firstname']);
            unset($replies[$i]['user_lastname']);
            $replies[$i]['attachments'] = $this->get_ticket_attachments($id, $reply['id']);
            $i++;
        }

        return $replies;
    }


    /**
     * Get latest 5 client tickets
     * @param  integer $limit  Optional limit tickets
     * @param  mixed $userid client id
     * @return array
     */
    public function get_client_latests_ticket($limit = 5, $userid = '')
    {
        $this->db->select(db_prefix() . 'tickets.userid, ticketstatusid, statuscolor, ' . db_prefix() . 'tickets_status.name as status_name,' . db_prefix() . 'tickets.ticketid, subject, date');
        $this->db->from(db_prefix() . 'tickets');
        $this->db->join(db_prefix() . 'tickets_status', db_prefix() . 'tickets_status.ticketstatusid = ' . db_prefix() . 'tickets.status', 'left');
        if (is_numeric($userid)) {
            $this->db->where(db_prefix() . 'tickets.userid', $userid);
        } else {
            $this->db->where(db_prefix() . 'tickets.userid', get_client_user_id());
        }
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Delete ticket from database and all connections
     * @param  mixed $ticketid ticketid
     * @return boolean
     */
    public function delete($ticketid)
    {
        $affectedRows = 0;
        hooks()->do_action('before_ticket_deleted', $ticketid);
        // final delete ticket
        $this->db->where('ticketid', $ticketid);
        $this->db->delete(db_prefix() . 'tickets');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            $this->db->where('ticketid', $ticketid);
            $attachments = $this->db->get(db_prefix() . 'ticket_attachments')->result_array();
            if (count($attachments) > 0) {
                if (is_dir(get_upload_path_by_type('ticket') . $ticketid)) {
                    if (delete_dir(get_upload_path_by_type('ticket') . $ticketid)) {
                        foreach ($attachments as $attachment) {
                            $this->db->where('id', $attachment['id']);
                            $this->db->delete(db_prefix() . 'ticket_attachments');
                            if ($this->db->affected_rows() > 0) {
                                $affectedRows++;
                            }
                        }
                    }
                }
            }

            $this->db->where('relid', $ticketid);
            $this->db->where('fieldto', 'tickets');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            // Delete replies
            $this->db->where('ticketid', $ticketid);
            $this->db->delete(db_prefix() . 'ticket_replies');

            $this->db->where('rel_id', $ticketid);
            $this->db->where('rel_type', 'ticket');
            $this->db->delete(db_prefix() . 'notes');

            $this->db->where('rel_id', $ticketid);
            $this->db->where('rel_type', 'ticket');
            $this->db->delete(db_prefix() . 'taggables');

            $this->db->where('rel_type', 'ticket');
            $this->db->where('rel_id', $ticketid);
            $this->db->delete(db_prefix() . 'reminders');

            // Get related tasks
            $this->db->where('rel_type', 'ticket');
            $this->db->where('rel_id', $ticketid);
            $tasks = $this->db->get(db_prefix() . 'tasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }
        }
        if ($affectedRows > 0) {
            log_activity('Ticket Deleted [ID: ' . $ticketid . ']');

            return true;
        }

        return false;
    }

    /**
     * Update ticket data / admin use
     * @param  mixed $data ticket $_POST data
     * @return boolean
     */
    public function update_single_ticket_settings($data)
    {
        $affectedRows = 0;
        $data         = hooks()->apply_filters('before_ticket_settings_updated', $data);

        $ticketBeforeUpdate = $this->get_ticket_by_id($data['ticketid']);

        if (isset($data['custom_fields']) && count($data['custom_fields']) > 0) {
            if (handle_custom_fields_post($data['ticketid'], $data['custom_fields'])) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (handle_tags_save($tags, $data['ticketid'], 'ticket')) {
            $affectedRows++;
        }

        if (isset($data['priority']) && $data['priority'] == '' || !isset($data['priority'])) {
            $data['priority'] = 0;
        }

        if ($data['assigned'] == '') {
            $data['assigned'] = 0;
        }

        if (isset($data['project_id']) && $data['project_id'] == '') {
            $data['project_id'] = 0;
        }

        if (isset($data['contactid']) && $data['contactid'] != '') {
            $data['name']  = null;
            $data['email'] = null;
        }

        $this->db->where('ticketid', $data['ticketid']);
        $this->db->update(db_prefix() . 'tickets', $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action(
                'ticket_settings_updated',
            [
                'ticket_id'       => $data['ticketid'],
                'original_ticket' => $ticketBeforeUpdate,
                'data'            => $data, ]
            );
            $affectedRows++;
        }

        $sendAssignedEmail = false;

        $current_assigned = $ticketBeforeUpdate->assigned;
        if ($current_assigned != 0) {
            if ($current_assigned != $data['assigned']) {
                if ($data['assigned'] != 0 && $data['assigned'] != get_staff_user_id()) {
                    $sendAssignedEmail = true;
                    $notified          = add_notification([
                        'description'     => 'not_ticket_reassigned_to_you',
                        'touserid'        => $data['assigned'],
                        'fromcompany'     => 1,
                        'fromuserid'      => 0,
                        'link'            => 'tickets/ticket/' . $data['ticketid'],
                        'additional_data' => serialize([
                            $data['subject'],
                        ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$data['assigned']]);
                    }
                }
            }
        } else {
            if ($data['assigned'] != 0 && $data['assigned'] != get_staff_user_id()) {
                $sendAssignedEmail = true;
                $notified          = add_notification([
                    'description'     => 'not_ticket_assigned_to_you',
                    'touserid'        => $data['assigned'],
                    'fromcompany'     => 1,
                    'fromuserid'      => 0,
                    'link'            => 'tickets/ticket/' . $data['ticketid'],
                    'additional_data' => serialize([
                        $data['subject'],
                    ]),
                ]);

                if ($notified) {
                    pusher_trigger_notification([$data['assigned']]);
                }
            }
        }
        if ($sendAssignedEmail === true) {
            $this->db->where('staffid', $data['assigned']);
            $assignedEmail = $this->db->get(db_prefix() . 'staff')->row()->email;

            send_mail_template('ticket_assigned_to_staff', $assignedEmail, $data['assigned'], $data['ticketid'], $data['userid'], $data['contactid']);
        }
        if ($affectedRows > 0) {
            log_activity('Ticket Updated [ID: ' . $data['ticketid'] . ']');

            return true;
        }

        return false;
    }

    /**
     * C<ha></ha>nge ticket status
     * @param  mixed $id     ticketid
     * @param  mixed $status status id
     * @return array
     */
    public function change_ticket_status($id, $status)
    {
        $this->db->where('ticketid', $id);
        $this->db->update(db_prefix() . 'tickets', [
            'status' => $status,
        ]);
        $alert   = 'warning';
        $message = _l('ticket_status_changed_fail');
        if ($this->db->affected_rows() > 0) {
            $alert   = 'success';
            $message = _l('ticket_status_changed_successfully');
            hooks()->do_action('after_ticket_status_changed', [
                'id'     => $id,
                'status' => $status,
            ]);
        }

        return [
            'alert'   => $alert,
            'message' => $message,
        ];
    }

    // Priorities

    /**
     * Get ticket priority by id
     * @param  mixed $id priority id
     * @return mixed     if id passed return object else array
     */
    public function get_priority($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('priorityid', $id);

            return $this->db->get(db_prefix() . 'tickets_priorities')->row();
        }

        return $this->db->get(db_prefix() . 'tickets_priorities')->result_array();
    }

    /**
     * Add new ticket priority
     * @param array $data ticket priority data
     */
    public function add_priority($data)
    {
        $this->db->insert(db_prefix() . 'tickets_priorities', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Ticket Priority Added [ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }

        return $insert_id;
    }

    /**
     * Update ticket priority
     * @param  array $data ticket priority $_POST data
     * @param  mixed $id   ticket priority id
     * @return boolean
     */
    public function update_priority($data, $id)
    {
        $this->db->where('priorityid', $id);
        $this->db->update(db_prefix() . 'tickets_priorities', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Ticket Priority Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete ticket priorit
     * @param  mixed $id ticket priority id
     * @return mixed
     */
    public function delete_priority($id)
    {
        $current = $this->get($id);
        // Check if the priority id is used in tickets table
        if (is_reference_in_table('priority', db_prefix() . 'tickets', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('priorityid', $id);
        $this->db->delete(db_prefix() . 'tickets_priorities');
        if ($this->db->affected_rows() > 0) {
            if (get_option('email_piping_default_priority') == $id) {
                update_option('email_piping_default_priority', '');
            }
            log_activity('Ticket Priority Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    // Predefined replies

    /**
     * Get predefined reply  by id
     * @param  mixed $id predefined reply id
     * @return mixed if id passed return object else array
     */
    public function get_predefined_reply($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'tickets_predefined_replies')->row();
        }

        return $this->db->get(db_prefix() . 'tickets_predefined_replies')->result_array();
    }

    /**
     * Add new predefined reply
     * @param array $data predefined reply $_POST data
     */
    public function add_predefined_reply($data)
    {
        $this->db->insert(db_prefix() . 'tickets_predefined_replies', $data);
        $insertid = $this->db->insert_id();
        log_activity('New Predefined Reply Added [ID: ' . $insertid . ', ' . $data['name'] . ']');

        return $insertid;
    }

    /**
     * Update predefined reply
     * @param  array $data predefined $_POST data
     * @param  mixed $id   predefined reply id
     * @return boolean
     */
    public function update_predefined_reply($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'tickets_predefined_replies', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Predefined Reply Updated [ID: ' . $id . ', ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete predefined reply
     * @param  mixed $id predefined reply id
     * @return boolean
     */
    public function delete_predefined_reply($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'tickets_predefined_replies');
        if ($this->db->affected_rows() > 0) {
            log_activity('Predefined Reply Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    // Ticket statuses

    /**
     * Get ticket status by id
     * @param  mixed $id status id
     * @return mixed     if id passed return object else array
     */
    public function get_ticket_status($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('ticketstatusid', $id);

            return $this->db->get(db_prefix() . 'tickets_status')->row();
        }
        $this->db->order_by('statusorder', 'asc');

        return $this->db->get(db_prefix() . 'tickets_status')->result_array();
    }

    /**
     * Add new ticket status
     * @param array ticket status $_POST data
     * @return mixed
     */
    public function add_ticket_status($data)
    {
        $this->db->insert(db_prefix() . 'tickets_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Ticket Status Added [ID: ' . $insert_id . ', ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update ticket status
     * @param  array $data ticket status $_POST data
     * @param  mixed $id   ticket status id
     * @return boolean
     */
    public function update_ticket_status($data, $id)
    {
        $this->db->where('ticketstatusid', $id);
        $this->db->update(db_prefix() . 'tickets_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Ticket Status Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete ticket status
     * @param  mixed $id ticket status id
     * @return mixed
     */
    public function delete_ticket_status($id)
    {
        $current = $this->get_ticket_status($id);
        // Default statuses cant be deleted
        if ($current->isdefault == 1) {
            return [
                'default' => true,
            ];
        // Not default check if if used in table
        } elseif (is_reference_in_table('status', db_prefix() . 'tickets', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('ticketstatusid', $id);
        $this->db->delete(db_prefix() . 'tickets_status');
        if ($this->db->affected_rows() > 0) {
            log_activity('Ticket Status Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    // Ticket services
    public function get_service($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('serviceid', $id);

            return $this->db->get(db_prefix() . 'services')->row();
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'services')->result_array();
    }

    public function add_service($data)
    {
        $this->db->insert(db_prefix() . 'services', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Ticket Service Added [ID: ' . $insert_id . '.' . $data['name'] . ']');
        }

        return $insert_id;
    }

    public function update_service($data, $id)
    {
        $this->db->where('serviceid', $id);
        $this->db->update(db_prefix() . 'services', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Ticket Service Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_service($id)
    {
        if (is_reference_in_table('service', db_prefix() . 'tickets', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('serviceid', $id);
        $this->db->delete(db_prefix() . 'services');
        if ($this->db->affected_rows() > 0) {
            log_activity('Ticket Service Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @return array
     * Used in home dashboard page
     * Displays weekly ticket openings statistics (chart)
     */
    public function get_weekly_tickets_opening_statistics()
    {
        $departments_ids = [];
        if (!is_admin()) {
            if (get_option('staff_access_only_assigned_departments') == 1) {
                $this->load->model('departments_model');
                $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                $departments_ids      = [];
                if (count($staff_deparments_ids) == 0) {
                    $departments = $this->departments_model->get();
                    foreach ($departments as $department) {
                        array_push($departments_ids, $department['departmentid']);
                    }
                } else {
                    $departments_ids = $staff_deparments_ids;
                }
            }
        }

        $chart = [
            'labels'   => get_weekdays(),
            'datasets' => [
                [
                    'label'           => _l('home_weekend_ticket_opening_statistics'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
            ],
        ];

        $monday = new DateTime(date('Y-m-d', strtotime('monday this week')));
        $sunday = new DateTime(date('Y-m-d', strtotime('sunday this week')));

        $thisWeekDays = get_weekdays_between_dates($monday, $sunday);

        $byDepartments = count($departments_ids) > 0;
        if (isset($thisWeekDays[1])) {
            $i = 0;
            foreach ($thisWeekDays[1] as $weekDate) {
                $this->db->like('DATE(date)', $weekDate, 'after');
                if ($byDepartments) {
                    $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                }
                $chart['datasets'][0]['data'][$i] = $this->db->count_all_results(db_prefix() . 'tickets');

                $i++;
            }
        }

        return $chart;
    }

    public function get_tickets_assignes_disctinct()
    {
        return $this->db->query('SELECT DISTINCT(assigned) as assigned FROM ' . db_prefix() . 'tickets WHERE assigned != 0')->result_array();
    }

    /**
     * Check for previous tickets opened by this email/contact and link to the contact
     * @param  string $email      email to check for
     * @param  mixed $contact_id the contact id to transfer the tickets
     * @return boolean
     */
    public function transfer_email_tickets_to_contact($email, $contact_id)
    {
        // Some users don't want to fill the email
        if (empty($email)) {
            return false;
        }

        $customer_id = get_user_id_by_contact_id($contact_id);

        $this->db->where('userid', 0)
                ->where('contactid', 0)
                ->where('admin IS NULL')
                ->where('email', $email);

        $this->db->update(db_prefix() . 'tickets', [
                    'email'     => null,
                    'name'      => null,
                    'userid'    => $customer_id,
                    'contactid' => $contact_id,
                ]);

        $this->db->where('userid', 0)
                ->where('contactid', 0)
                ->where('admin IS NULL')
                ->where('email', $email);

        $this->db->update(db_prefix() . 'ticket_replies', [
                    'email'     => null,
                    'name'      => null,
                    'userid'    => $customer_id,
                    'contactid' => $contact_id,
                ]);

        return true;
    }
}
