<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Utilities_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('leads_model');
    }

	public function list_leads_data($id = null)
    {
        $this->db->select()->from('tblleads');
		$this->db->where('assigned', get_staff_user_id());
        $query = $this->db->get();
        
        return $query->result_array();
        
    }
    /**
     * Add new event
     * @param array $data event $_POST data
     */
    public function event($data)
    {
        $data['leadid'] = $data['leadid'];
        $data['userid'] = get_staff_user_id();
        $data['start']  = to_sql_date($data['start'], true);
		$data['reportingto'] = get_staff_reporting_to();
        $data['state']     = get_staff_state_id();
        
        if ($data['end'] == '') {
            unset($data['end']);
        } else {
            $data['end'] = to_sql_date($data['start'], true);
        }
        if (isset($data['public'])) {
            $data['public'] = 1;
        } else {
            $data['public'] = 0;
        }
        $data['description'] = nl2br($data['description']);
        if (isset($data['eventid'])) {
            unset($data['userid']);
            $this->db->where('eventid', $data['eventid']);
            $event = $this->db->get('tblevents')->row();
            if (!$event) {
                return false;
            }
            if ($event->isstartnotified == 1) {
                if ($data['start'] > $event->start) {
                    $data['isstartnotified'] = 0;
                }
            }

            $this->db->where('eventid', $data['eventid']);
            $this->db->update('tblevents', $data);
			logActivity($data['leadid'],'Meeting updated [<strong>Date:<strong>'.$data['start']. '<br><strong> Description:</strong> ' . $data['description'] . ']');
			$this->leads_model->log_lead_activity($data['leadid'], 'Meeting updated [<strong>Date:<strong>'.$data['start']. '<br><strong> Description:</strong> ' . $data['description'] . ']', false, serialize(array(
                    get_staff_full_name(get_staff_user_id()),
                    _dt($data['start'])
                    )));
            if ($this->db->affected_rows() > 0) {
				
                return true;
            }

            return false;
        } else {
			$this->db->insert('tblevents', $data);
            $insert_id = $this->db->insert_id();
			logActivity($data['leadid'],'New Meeting Added [<strong>Date:<strong>'.$data['start']. '<br><strong> Description:</strong> ' . $data['description'] . ']');
			$this->leads_model->log_lead_activity($data['leadid'], 'Set a new Meeting:- <strong>"'.$data['description'].' on date '._dt($data['start']).'"</strong>', false, serialize(array(
                    get_staff_full_name(get_staff_user_id()),
                    _dt($data['start'])
                    )));
        }
        if ($insert_id) {
            return true;
        }

        return false;
    }

	public function add_reminder($data)
    {
		$data_l['notify_by_email'] = 0;
        $data_l['date']        = to_sql_date($data['start'], true);
        $data_l['createddate'] = date('Y-m-d H:i:s');
        $data_l['description'] = nl2br($data['description']);
        $data_l['creator']     = get_staff_user_id();
        $data_l['staff']     = get_staff_user_id();
        $data_l['reporting_to']     = get_staff_reporting_to();
        $data_l['assigned_to']     = $data['assigned_to'];
        $data_l['rel_id']     = $data['rel_id'];
        $data_l['rel_type']     = $data['rel_type'];
		if($data['reminder_status'] =='')
			$data_l['reminder_status'] = 'Pending';
        else
			$data_l['reminder_status'] = $data['reminder_status'];
		
		$this->db->where('id', $data['rel_id']);
        $state = $this->db->get('tblleads')->row()->state;
		$data_l['state']       = $state;
		
        $this->db->insert('tblreminders', $data_l);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if ($data['rel_type'] == 'lead') {
                $this->load->model('leads_model');
                $this->leads_model->log_lead_activity($data['rel_id'], 'Set a new Follow up:- <strong>"'.$data['description'].' on date '._dt($data['start']).'"</strong>', false, serialize(array(
                    get_staff_full_name($data['staff']),
                    _dt($data['start'])
                    )));
            }
            logActivity($data['rel_id'],'New Reminder Added [' . ucfirst($data['rel_type']) . 'ID: ' . $data['rel_id'] . ' Description: ' . $data['description'] . ']');

			
			$rel_data   = get_relation_data($data['rel_type'], $data['rel_id']);
            $rel_values = get_relation_values($rel_data, $data['rel_type']);

			$notificationLink = str_replace(admin_url(), '', $rel_values['link']);
			$notificationLink = ltrim($notificationLink, '/');
			$notifiedUsers = array();	
			$notified = add_notification(array(
                    'fromcompany' => true,
                    'touserid' => get_staff_user_id(),
                    'description' => 'not_new_reminder_for',
                    'link' => $notificationLink,
                    'additional_data' => serialize(array(
                        'Lead No. #'.$data['rel_id'] . ' - ' . strip_tags(mb_substr($data['description'], 0, 50)) . '...',
                    )),
                ));

			if ($notified) {
				$notifiedUsers = array();
				array_push($notifiedUsers, get_staff_user_id());
				pusher_trigger_notification($notifiedUsers);
			}
			$notified1 = add_notification(array(
                    'fromcompany' => true,
                    'touserid' => $data['assigned_to'],
                    'description' => 'not_new_reminder_for',
                    'link' => $notificationLink,
                    'additional_data' => serialize(array(
                        'Lead No. #'.$data['rel_id'] . ' - ' . strip_tags(mb_substr($data['description'], 0, 50)) . '...',
                    )),
                ));

			if ($notified1) {
				$notifiedUsers = array();
				array_push($notifiedUsers, $data['assigned_to']);
				pusher_trigger_notification($notifiedUsers);
			}
			
			
			//------------ Mail ------------------------//		
            $subject = "Halonix LMS - Meeting/Followup Created";
            $message = "<html>	<head>		<title>HTML email</title>	</head>	<body>	New Meeting/Followup by " . get_staff_full_name();
            $message .= "<br>Description : " . $data['description'];
            $message .= "<br>Meeting Date: : " . to_sql_date($data['start'], true);
            $message .= "</body></html>";
            
            $this->load->model('staff_model');
            $this->load->model('leads_model');
			$staff_email  = $this->staff_model->get_staff_email();
           
			$reporting_manager = $this->leads_model->get_reporting_to(get_staff_user_id());
			$reporting_manager = array_map('trim',explode(',',$reporting_manager));
			$reporting_manager = array_unique($reporting_manager);
			$reporting_manager = implode(', ', $reporting_manager);
			$reporting_manager = array_map('trim',explode(',',$reporting_manager));
			
			$emailIDS = $this->leads_model->getEmailIDReportingTo('email', 'tblstaff', 'staffid', $reporting_manager);
			$cc = array();
			foreach ($emailIDS as $emails) {
				array_push($cc,$emails['email']);
			}
			
            $this->sent_smtp__email($staff_email, $subject, $message,$cc);
			
			
			
            return true;
        } //$insert_id
        return false;
    }

	public function add_task($data)
    {


		$data_l['name'] = nl2br($data['description']);
		$data_l['status'] = 1;
		$data_l['priority'] = 2;
		$data_l['repeat_every'] = 0;
        $data_l['startdate']        = to_sql_date($data['start'], true);
        $data_l['dateadded'] = date('Y-m-d H:i:s');
        $data_l['datefinished'] = '0000-00-00 00:00:00';
        $data_l['addedfrom']     = $data['view_assigned'];
        $data_l['assigned_to']     = get_staff_user_id();
        $data_l['reportingto']     = get_staff_reporting_to();        
		$data_l['rel_id']     = $data['rel_id1'];
        $data_l['rel_type']     = $data['rel_type1'];
        $data_l['is_public']     = 1;
		$this->db->where('id', $data['rel_id1']);
        $state = $this->db->get('tblleads')->row()->state;
		$data_l['state']       = $state;
		
        $this->db->insert('tblstafftasks', $data_l);
        $insert_id = $this->db->insert_id();
		
		
        if ($insert_id) {
            if ($data['rel_type1'] == 'lead') {
                $this->load->model('leads_model');
                $this->leads_model->log_lead_activity($data['rel_id1'], 'New Task addigned:- <strong>"'.$data['description'].' on date '._dt($data['start']).'"</strong>', false, serialize(array(
                    get_staff_full_name(get_staff_user_id()),
                    _dt($data['start'])
                    )));
            }
			
			$data_l1['staffid']     = $data['view_assigned'];        
			$data_l1['taskid']     = $insert_id;
			$data_l1['assigned_from']     = get_staff_user_id();
			$this->db->insert('tblstafftaskassignees', $data_l1);
			$this->db->insert_id();
		
            
			$this->db->insert('tblnotifications', array(
                    'isread'=>'0',
                    'isread_inline'=>'0',
                    'date'=>date('Y-m-d H:i:s'),
                    'description'=>'not_task_assigned_to_you',
                    'fromuserid'=>get_staff_user_id(),
                    'fromclientid'=>'0',
                    'from_fullname'=>get_staff_full_name(),
                    'touserid'=>$data['view_assigned'],
                    'fromcompany'=>'',
                    'link'=>'#taskid='.$insert_id,
                    'additional_data'=>'',
                    ));
			if (isset($data['rel_type1']) && $data['rel_type1'] == 'lead') {
                $this->load->model('leads_model');
                $this->leads_model->log_lead_activity($data['rel_id1'], 'Task Assigned', false, serialize(array(
                    '<a href="'.admin_url('tasks/view/'.$insert_id).'" onclick="init_task_modal('.$insert_id.');return false;">'.$data['description'].'</a>',
                    )));
            }
			logActivity($data['rel_id'],'New Task Added [' . ucfirst($data['rel_type1']) . 'ID: ' . $data['rel_id1'] . ' Description: ' . $data['description'] . ']');

			
            return true;
        } //$insert_id
        return false;
    }


    /**
     * Get event by passed id
     * @param  mixed $id eventid
     * @return object
     */
    public function get_event_by_id($id)
    {
        $this->db->where('eventid', $id);

        return $this->db->get('tblevents')->row();
    }

    /**
     * Get all user events
     * @return array
     */
    public function get_all_events($start, $end)
    {
        $is_staff_member = is_staff_member();
        $this->db->select('title,start,end,eventid,userid,color,public');
        // Check if is passed start and end date
        $this->db->where('(start BETWEEN "' . $start . '" AND "' . $end . '")');
        $this->db->where('userid', get_staff_user_id());
        if ($is_staff_member) {
            $this->db->or_where('public', 1);
        }

        return $this->db->get('tblevents')->result_array();
    }

    public function get_event($id)
    {
        $this->db->where('eventid', $id);

        return $this->db->get('tblevents')->row();
    }
	
	public function get_meeting($id)
    {
        $this->db->where('id', $id);

        return $this->db->get('tblreminders')->row();
    }

   public function get_calendar_data($start, $end, $client_id = '', $contact_id = '', $filters = false)
    {
        $is_admin                     = is_admin();
        $has_permission_tasks_view      = has_permission('tasks', '', 'view');
        $has_permission_projects_view      = has_permission('projects', '', 'view');
        $has_permission_invoices      = has_permission('invoices', '', 'view');
        $has_permission_invoices_own  = has_permission('invoices', '', 'view_own');
        $has_permission_estimates     = has_permission('estimates', '', 'view');
        $has_permission_estimates_own = has_permission('estimates', '', 'view_own');
        $has_permission_contracts     = has_permission('contracts', '', 'view');
        $has_permission_contracts_own = has_permission('contracts', '', 'view_own');
        $has_permission_proposals     = has_permission('proposals', '', 'view');
        $has_permission_proposals_own = has_permission('proposals', '', 'view_own');
        $data                         = array();

        $client_data = false;
        if (is_numeric($client_id) && is_numeric($contact_id)) {
            $client_data                      = true;
            $has_contact_permission_invoices  = has_contact_permission('invoices', $contact_id);
            $has_contact_permission_estimates = has_contact_permission('estimates', $contact_id);
            $has_contact_permission_proposals = has_contact_permission('proposals', $contact_id);
            $has_contact_permission_contracts = has_contact_permission('contracts', $contact_id);
            $has_contact_permission_projects  = has_contact_permission('projects', $contact_id);
        }

        $hook_data = array(
            'data' => $data,
            'client_data' => $client_data,
        );

        if ($client_data == true) {
            $hook_data['client_id']  = $client_id;
            $hook_data['contact_id'] = $contact_id;
        }

        $hook_data = do_action('before_fetch_events', $hook_data);
        $data      = $hook_data['data'];

        // excluded calendar_filters from post
        $ff = (count($filters) > 1 && isset($filters['calendar_filters']) ? true : false);

        if (get_option('show_invoices_on_calendar') == 1 && !$ff || $ff && array_key_exists('invoices', $filters)) {
            $this->db->select('duedate as date,number,id,clientid,hash,'.get_sql_select_client_company());
            $this->db->from('tblinvoices');
            $this->db->join('tblclients','tblclients.userid=tblinvoices.clientid');
            $this->db->where_not_in('status', array(
                2,
                5,
            ));

            $this->db->where('(duedate BETWEEN "' . $start . '" AND "' . $end . '")');

            if ($client_data) {
                $this->db->where('clientid', $client_id);

                if (get_option('exclude_invoice_from_client_area_with_draft_status') == 1) {
                    $this->db->where('status !=', 6);
                }
            } else {
                if (!$has_permission_invoices) {
                    $this->db->where('tblinvoices.addedfrom', get_staff_user_id());
                }
            }
            $invoices = $this->db->get()->result_array();
            foreach ($invoices as $invoice) {
                if (!$has_permission_invoices && !$has_permission_invoices_own && !$client_data) {
                    continue;
                } elseif ($client_data && !$has_contact_permission_invoices) {
                    continue;
                }

                $rel_showcase = '';

                /**
                 * Show company name on calendar tooltip for admins
                 */
                if (!$client_data) {
                    $rel_showcase = ' (' . $invoice['company'] . ')';
                }

                $number              = format_invoice_number($invoice['id']);

                $invoice['_tooltip'] = _l('calendar_invoice') . ' - ' . $number . $rel_showcase;
                $invoice['title']    = $number;
                $invoice['color']    = get_option('calendar_invoice_color');

                if (!$client_data) {
                    $invoice['url'] = admin_url('invoices/list_invoices/' . $invoice['id']);
                } else {
                    $invoice['url'] = site_url('viewinvoice/' . $invoice['id'] . '/' . $invoice['hash']);
                }

                array_push($data, $invoice);
            }
        }
        if (get_option('show_estimates_on_calendar') == 1  && !$ff || $ff && array_key_exists('estimates', $filters)) {
            $this->db->select('number,id,clientid,hash,CASE WHEN expirydate IS NULL THEN date ELSE expirydate END as date,'.get_sql_select_client_company(), false);
            $this->db->from('tblestimates');
            $this->db->join('tblclients','tblclients.userid=tblestimates.clientid');
            $this->db->where('status !=', 3, false);
            $this->db->where('status !=', 4, false);
            // $this->db->where('expirydate IS NOT NULL');

            $this->db->where("CASE WHEN expirydate IS NULL THEN (date BETWEEN '$start' AND '$end') ELSE (expirydate BETWEEN '$start' AND '$end') END", null, false);

            if ($client_data) {
                $this->db->where('clientid', $client_id, false);

                if (get_option('exclude_estimate_from_client_area_with_draft_status') == 1) {
                    $this->db->where('status !=', 1, false);
                }
            } else {
                if (!$has_permission_estimates) {
                    $this->db->where('tblestimates.addedfrom', get_staff_user_id(), false);
                }
            }

            $estimates = $this->db->get()->result_array();

            foreach ($estimates as $estimate) {
                if (!$has_permission_estimates && !$has_permission_estimates_own && !$client_data) {
                    continue;
                } elseif ($client_data && !$has_contact_permission_estimates) {
                    continue;
                }

                $rel_showcase = '';
                if (!$client_data) {
                    $rel_showcase = ' (' . $estimate['company'] . ')';
                }

                $number               = format_estimate_number($estimate['id']);
                $estimate['_tooltip'] = _l('calendar_estimate') . ' - ' . $number . $rel_showcase;
                $estimate['title']    = $number;
                $estimate['color']    = get_option('calendar_estimate_color');
                if (!$client_data) {
                    $estimate['url'] = admin_url('estimates/list_estimates/' . $estimate['id']);
                } else {
                    $estimate['url'] = site_url('viewestimate/' . $estimate['id'] . '/' . $estimate['hash']);
                }
                array_push($data, $estimate);
            }
        }
        if (get_option('show_proposals_on_calendar') == 1 && !$ff || $ff && array_key_exists('proposals', $filters)) {
            $this->db->select('subject,id,hash,CASE WHEN open_till IS NULL THEN date ELSE open_till END as date', false);
            $this->db->from('tblproposals');
            $this->db->where('status !=', 2, false);
            $this->db->where('status !=', 3, false);


            $this->db->where("CASE WHEN open_till IS NULL THEN (date BETWEEN '$start' AND '$end') ELSE (open_till BETWEEN '$start' AND '$end') END", null, false);

            if ($client_data) {
                $this->db->where('rel_type', 'customer');
                $this->db->where('rel_id', $client_id, false);

                if (get_option('exclude_proposal_from_client_area_with_draft_status')) {
                    $this->db->where('status !=', 6, false);
                }
            } else {
                if (!$has_permission_proposals) {
                    $this->db->where('addedfrom', get_staff_user_id(), false);
                }
            }

            $proposals = $this->db->get()->result_array();
            foreach ($proposals as $proposal) {
                if (!$has_permission_proposals && !$has_permission_proposals_own && !$client_data) {
                    continue;
                } elseif ($client_data && !$has_contact_permission_proposals) {
                    continue;
                }

                $proposal['_tooltip'] = _l('proposal');
                $proposal['title']    = $proposal['subject'];
                $proposal['color']    = get_option('calendar_proposal_color');
                if (!$client_data) {
                    $proposal['url'] = admin_url('proposals/list_proposals/' . $proposal['id']);
                } else {
                    $proposal['url'] = site_url('viewproposal/' . $proposal['id'] . '/' . $proposal['hash']);
                }
                array_push($data, $proposal);
            }
        }

        if (get_option('show_tasks_on_calendar') == 1 && !$ff || $ff && array_key_exists('tasks', $filters)) {
            $this->db->select('name as title,firstname,lastname,addedfrom,tblstafftasks.reportingto as reportingmanager,assigned_to,id,'.tasks_rel_name_select_query() . ' as rel_name,rel_id,status,CASE WHEN duedate IS NULL THEN startdate ELSE duedate END as date', false);
            $this->db->from('tblstafftasks');
            $this->db->where('status !=', 5);

            $this->db->where("CASE WHEN duedate IS NULL THEN (startdate BETWEEN '$start' AND '$end') ELSE (duedate BETWEEN '$start' AND '$end') END", null, false);

            if ($client_data) {
                $this->db->where('rel_type', 'project');
                $this->db->where('rel_id IN (SELECT id FROM tblprojects WHERE clientid='.$client_id.')');
                $this->db->where('rel_id IN (SELECT project_id FROM tblprojectsettings WHERE name="view_tasks" AND value=1)');
                $this->db->where('visible_to_client', 1);
            }

            if (!$has_permission_tasks_view && !$client_data) {
                $this->db->where('(id IN (SELECT taskid FROM tblstafftaskassignees WHERE staffid = ' . get_staff_user_id() . '))');
            }
			$this->db->join('tblstaff', 'tblstaff.staffid = tblstafftasks.addedfrom');
            $tasks = $this->db->get()->result_array();

            foreach ($tasks as $task) {
				$reportingto = explode(',',$task['reportingmanager']);
				if (get_staff_user_id() == $task['addedfrom'] || get_staff_user_id() == $task['assigned_to'] || $is_admin || in_array(get_staff_user_id(), $reportingto)) {
					$rel_showcase = '';

					if (!empty($task['rel_id']) && !$client_data) {
						$rel_showcase   = ' (' . $task['rel_name'] . ')';
					}
					if (get_staff_user_id() != $task['addedfrom']) {
						$task['title'] .= '(' . $task['firstname'] . ' ' . $task['lastname'] . ') ';
					}
					$name             = mb_substr($task['title'], 0, 60) . '...';
					$task['_tooltip'] = _l('calendar_task') . ' - ' . $name . $rel_showcase;
					$task['title']    .= $name;
					$task['date'] = $task['date'];
					$status = get_task_status_by_id($task['status']);
					$task['color']    = $status['color'];

					if (!$client_data) {
						$task['onclick'] = 'init_task_modal(' . $task['id'] . '); return false';
						$task['url']     = '#';
					} else {
						$task['url'] = site_url('clients/project/' . $task['rel_id'] . '?group=project_tasks&taskid=' . $task['id']);
					}
					array_push($data, $task);
				}
			}
        }

        if (!$client_data) {
            $available_reminders = $this->app->get_available_reminders_keys();
            $hideNotifiedReminders = get_option('hide_notified_reminders_from_calendar');
            foreach ($available_reminders as $key) {
                if (get_option('show_' . $key . '_reminders_on_calendar') == 1  && !$ff || $ff && array_key_exists($key.'_reminders', $filters)) {
                    $this->db->select('tblreminders.id as reminderid,reminder_status,date,description,firstname,lastname,creator,staff,rel_id,tblreminders.assigned_to as technical,tblreminders.reporting_to as reportingmanager')
                    ->from('tblreminders')
                    ->where('(date BETWEEN "' . $start . '" AND "' . $end . '")')
                    ->where('rel_type', $key)
                    ->join('tblstaff', 'tblstaff.staffid = tblreminders.staff');
                    if ($hideNotifiedReminders == '1') {
                        $this->db->where('isnotified', 0);
                    }
					$this->db->where('reminder_status !=', 'Closed');
                    if (get_staff_role() == '7') {
                        $this->db->where('assigned_to', get_staff_user_id());
                    }
                    $reminders = $this->db->get()->result_array();
					
                    foreach ($reminders as $reminder) {
						$reportingto = explode(',',$reminder['reportingmanager']);
						if (get_staff_role() == '7') {
							$_reminder['title'] = '';
                            if (get_staff_user_id() != $reminder['staff']) {
                                $_reminder['title'] .= '(' . $reminder['firstname'] . ' ' . $reminder['lastname'] . ') ';
                            }
                            $name                  = mb_substr($reminder['description'], 0, 60) . '...';
                            $_reminder['_tooltip'] = _l('calendar_' . $key . '_reminder') . ' - ' . $name;
                            $_reminder['title'] .= $name;
                            $_reminder['date']  = $reminder['date'];
                            $_reminder['color'] = get_option('calendar_reminder_color');
                            if ($key == 'lead') {
                                $url = '#';
								//=================== changes on 08-08-2019 =====//  
                                $_reminder['onclick'] = 'view_meeting('.$reminder['reminderid'].'); return false;';
                                //$_reminder['onclick'] = 'edit_reminder('.$reminder['reminderid'].')';
							  
			
                            }
                            $_reminder['url'] = $url;
                            array_push($data, $_reminder);							
						}
						else if ((get_staff_user_id() == $reminder['creator'] || in_array(get_staff_user_id(), $reportingto)) || $is_admin) {
                            $_reminder['title'] = '';
                            if (get_staff_user_id() != $reminder['staff']) {
                                $_reminder['title'] .= '(' . $reminder['firstname'] . ' ' . $reminder['lastname'] . ') ';
                            }
                            $name                  = mb_substr($reminder['description'], 0, 60) . '...';
                            $_reminder['_tooltip'] = _l('calendar_' . $key . '_reminder') . ' - ' . $name;
                            $_reminder['title'] .= $name;
                            $_reminder['date']  = $reminder['date'];
                            $_reminder['color'] = get_option('calendar_reminder_color');
                            if ($key == 'lead') {
                                $url = '#';
								//=================== changes on 08-08-2019 =====//  
                                $_reminder['onclick'] = 'view_meeting('.$reminder['reminderid'].'); return false;';
                                //$_reminder['onclick'] = 'edit_reminder('.$reminder['reminderid'].')';
							  
			
                            }
                            $_reminder['url'] = $url;
                            array_push($data, $_reminder);
                        }
                    }
                }
            }
        }

        if (get_option('show_contracts_on_calendar') == 1 && !$ff || $ff && array_key_exists('contracts', $filters)) {
            $this->db->select('subject as title,dateend,datestart,id,client,content,'.get_sql_select_client_company());
            $this->db->from('tblcontracts');
            $this->db->join('tblclients','tblclients.userid=tblcontracts.client');
            $this->db->where('trash', 0);

            if ($client_data) {
                $this->db->where('client', $client_id);
                $this->db->where('not_visible_to_client', 0);
            } else {
                if (!$has_permission_contracts) {
                    $this->db->where('tblcontracts.addedfrom', get_staff_user_id());
                }
            }

            $this->db->where('(dateend > "' . date('Y-m-d') . '" AND dateend IS NOT NULL AND dateend BETWEEN "' . $start . '" AND "' . $end . '" OR datestart >"' . date('Y-m-d') . '")');


            $contracts = $this->db->get()->result_array();

            foreach ($contracts as $contract) {
                if (!$has_permission_contracts && !$has_permission_contracts_own && !$client_data) {
                    continue;
                } elseif ($client_data && !$has_contact_permission_contracts) {
                    continue;
                }

                $rel_showcase = '';
                if (!$client_data) {
                    $rel_showcase = ' (' . $contract['company'] . ')';
                }

                $name                  = $contract['title'];
                $_contract['title']    = $name;
                $_contract['color']    = get_option('calendar_contract_color');
                $_contract['_tooltip'] = _l('calendar_contract') . ' - ' . $name . $rel_showcase;
                if (!$client_data) {
                    $_contract['url'] = admin_url('contracts/contract/' . $contract['id']);
                } else {
                    if (empty($contract['content'])) {
                        // No url for contracts
                        $_contract['url'] = '#';
                    } else {
                        $_contract['url'] = site_url('clients/contract_pdf/' . $contract['id']);
                    }
                }
                if (!empty($contract['dateend'])) {
                    $_contract['date'] = $contract['dateend'];
                } else {
                    $_contract['date'] = $contract['datestart'];
                }
                array_push($data, $_contract);
            }
        }
        //calendar_project
        if (get_option('show_projects_on_calendar') == 1 && !$ff || $ff && array_key_exists('projects', $filters)) {
            $this->load->model('projects_model');
            $this->db->select('name as title,id,clientid, CASE WHEN deadline IS NULL THEN start_date ELSE deadline END as date,'.get_sql_select_client_company(), false);

            $this->db->from('tblprojects');

            // Exclude cancelled and finished
            $this->db->where('status !=', 4);
            $this->db->where('status !=', 5);
            $this->db->where("CASE WHEN deadline IS NULL THEN (start_date BETWEEN '$start' AND '$end') ELSE (deadline BETWEEN '$start' AND '$end') END", null, false);

            $this->db->join('tblclients','tblclients.userid=tblprojects.clientid');

            if (!$client_data && !$has_permission_projects_view) {
                $this->db->where('id IN (SELECT project_id FROM tblprojectmembers WHERE staff_id='.get_staff_user_id().')');
            } else if($client_data) {
                $this->db->where('clientid', $client_id);
            }

            $projects = $this->db->get()->result_array();
            foreach ($projects as $project) {
                $rel_showcase = '';

                if (!$client_data) {
                    $rel_showcase = ' (' . $project['company'] . ')';
                } else {
                    if (!$has_contact_permission_projects) {
                        continue;
                    }
                }

                $name                 = $project['title'];
                $_project['title']    = $name;
                $_project['color']    = get_option('calendar_project_color');
                $_project['_tooltip'] = _l('calendar_project') . ' - ' . $name . $rel_showcase;
                if (!$client_data) {
                    $_project['url'] = admin_url('projects/view/' . $project['id']);
                } else {
                    $_project['url'] = site_url('clients/project/' . $project['id']);
                }

                $_project['date'] = $project['date'];

                array_push($data, $_project);
            }
        }
        if (!$client_data && !$ff || (!$client_data && $ff && array_key_exists('events', $filters))) {
            $events = $this->get_all_events($start, $end);
            foreach ($events as $event) {
                if ($event['userid'] != get_staff_user_id() && !$is_admin) {
                    $event['is_not_creator'] = true;
                    $event['onclick']        = true;
                }
                $event['_tooltip'] = _l('calendar_event') . ' - ' . $event['title'];
                $event['color']    = $event['color'];
                array_push($data, $event);
            }
        }

        return $data;
    }
	
	
	function test(){
		
		echo get_staff_role();
		$this->db->select('tblreminders.id as reminderid,date,description,firstname,lastname,creator,staff,rel_id,tblreminders.assigned_to as technical,tblreminders.reporting_to as reportingmanager')
                    ->from('tblreminders')
                    ->where('rel_type', 'lead')
                    ->join('tblstaff', 'tblstaff.staffid = tblreminders.staff');
                   
                    $reminders = $this->db->get()->result_array();
					print_r($reminders);
                    foreach ($reminders as $reminder) {
						$reportingto = explode(',',$reminder['reportingmanager']);
                        if ((get_staff_user_id() == $reminder['creator'] || get_staff_user_id() == $reminder['staff'] || get_staff_user_id() == $reminder['technical']) || $is_admin || in_array(get_staff_user_id(), $reportingto)  || get_staff_user_id() > 8) {
                            $_reminder['title'] = '';

                            if (get_staff_user_id() != $reminder['staff']) {
                                $_reminder['title'] .= '(' . $reminder['firstname'] . ' ' . $reminder['lastname'] . ') ';
                            }

                            $name                  = mb_substr($reminder['description'], 0, 60) . '...';

                            $_reminder['_tooltip'] = _l('calendar_' . $key . '_reminder') . ' - ' . $name;
                            $_reminder['title'] .= $name;
                            $_reminder['date']  = $reminder['date'];
                            $_reminder['color'] = get_option('calendar_reminder_color');

                            if ($key == 'customer') {
                                $url = admin_url('clients/client/' . $reminder['rel_id']);
                            } elseif ($key == 'invoice') {
                                $url = admin_url('invoices/list_invoices/' . $reminder['rel_id']);
                            } elseif ($key == 'estimate') {
                                $url = admin_url('estimates/list_estimates/' . $reminder['rel_id']);
                            } elseif ($key == 'lead') {
                                $url = '#';
								//=================== changes on 08-08-2019 =====//  
                                $_reminder['onclick'] = 'view_meeting('.$reminder['reminderid'].'); return false;';
                                //$_reminder['onclick'] = 'edit_reminder('.$reminder['reminderid'].')';
                            } elseif ($key == 'proposal') {
                                $url = admin_url('proposals/list_proposals/' . $reminder['rel_id']);
                            } elseif ($key == 'expense') {
                                $url = 'expenses/list_expenses/' . $reminder['rel_id'];
                            } elseif ($key == 'credit_note') {
                                $url = 'credit_notes/list_credit_notes/' . $reminder['rel_id'];
                            }

                            $_reminder['url'] = $url;
                            array_push($data, $_reminder);
                        }
                    }
					return $data;
	}
 /**
     * Delete user event
     * @param  mixed $id event id
     * @return boolean
     */
    public function delete_event($id)
    {
        $this->db->where('eventid', $id);
        $this->db->delete('tblevents');
        if ($this->db->affected_rows() > 0) {
            logActivity('Event Deleted [' . $id . ']');

            return true;
        }

        return false;
    }
	
	
	 
    public function sent_smtp__email($to_email, $subject, $message,$cc='')
    {
        
        // Simulate fake template to be parsed
        $template           = new StdClass();
        $template->message  = get_option('email_header') . ' ' . $message . get_option('email_footer');
        $template->fromname = get_option('companyname');
        $template->subject  = $subject;
        
        $template = parse_email_template($template);
        
        do_action('before_send_test_smtp_email');
        
        $this->email->initialize();
        
        $this->email->set_newline("\r\n");
        
        $this->email->from(get_option('smtp_email'), $template->fromname);
        
        $this->email->to($to_email);
		
        $systemBCC = get_option('bcc_emails');
		$this->email->bcc($systemBCC);
		
		 if(isset($cc)){
			$cc = array_filter(array_unique($cc));
			$cc = implode(', ', $cc);
			$ccmail = "'".$cc."'";
            $this->email->cc($cc);
			
        }
		
        $this->email->subject($template->subject);
        $this->email->message($template->message);
        $this->email->send(true);
        
        
    }
    
}
