<?php
header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed');
class Leads extends Admin_controller
{
    private $not_importable_leads_fields;
    
    public function __construct()
    {
        parent::__construct();
        $this->not_importable_leads_fields = do_action('not_importable_leads_fields', array(
            'id',
            'source',
            'assigned',
            'status',
            'dateadded',
            'last_status_change',
            'addedfrom',
            'leadorder',
            'date_converted',
            'lost',
            'junk',
            'is_imported_from_email_integration',
            'email_integration_uid',
            'is_public',
            'dateassigned',
            'client_id',
            'lastcontact',
            'last_lead_status',
            'from_form_id',
            'default_language'
        ));
        $this->load->model('leads_model');
        $this->load->model('currencies_model');
        $this->load->model('clients_model');
        $this->load->model('emails_model');
    }
    
    /* List all leads */
    public function index($id = '')
    {
        
        close_setup_menu();
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        
        $data['switch_kanban'] = true;
        $this->load->model('currencies_model');
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
        
        if (get_staff_role() == 0 || get_staff_role() > 8) {
            $data['staff'] = $this->staff_model->get_staff_data('', array('is_not_staff'=>0));
        }else if (get_staff_role() == 4 || get_staff_role() > 7) {
            $data['staff'] = $this->staff_model->get_staff_data('', array('is_not_staff'=>0));
        } else if (get_staff_role() > 1) {
            /* $data['staff'] = $this->staff_model->get_staff_data('', array(
                'reporting_manager' => get_staff_user_id()
            )); */
			$qry = "SELECT staffid,firstname,lastname,emp_code FROM `tblstaff` WHERE FIND_IN_SET('".get_staff_user_id()."', reporting_to)";
			
            $data['staff'] = $this->db->query($qry)->result_array();
        }
        $Hidden = explode(',','4,7,9,10,11,12,13');
		$customer_group1 = array();	
		
		
		//$this->db->select('customer_group');
		//$this->db->from('tblleads');
		
		if (!(in_array(get_staff_role(), $Hidden))){
			
			$this->db->select('customer_group');  //m;15;07;2020;Ajay
		    $this->db->from('tblleads');
		
			$where = "FIND_IN_SET('".get_staff_user_id()."', reportingto) OR assigned IN(". get_staff_user_id() .")";
			$this->db->where($where);
			$customer_group = $this->db->get()->result_array();
			if(empty($customer_group)){
				$data['customer_groups'] = $customer_group1;
			}else{
				foreach($customer_group as $cg){
					array_push($customer_group1,$cg['customer_group']);
				}
				$customer_group_unique = array_unique($customer_group1);
				$cust_group_id = implode(',', $customer_group_unique);
				
				$qry = "SELECT * FROM `tblcustomersgroups` WHERE `id` IN(".$cust_group_id.")";
				$customer_groups =  $this->db->query($qry)->result_array();
				$data['customer_groups'] = $customer_groups;
			}
		}else{
		     $data['customer_groups'] = $this->clients_model->get_groups();
		} 
		
		
		if(get_staff_role() > 8 || get_staff_role() == 7 || get_staff_role() == 4 || is_admin()){
			$data['customer_groups'] = $this->clients_model->get_groups();
		}                           
       
        
        $data['statuses'] = $this->leads_model->get_status();
        
        $data['sources'] = $this->leads_model->get_source();
        
        //$data['customer_groups'] = $this->clients_model->get();
        
        $data['regions'] = $this->currencies_model->get_region();
        
        $data['title']  = _l('leads');
        // in case accesed the url leads/index/ directly with id - used in search
        $data['leadid'] = $id;
        
        $this->load->view('admin/leads/manage_leads', $data);
    }
    
    public function table()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $this->app->get_table_data('leads');
    }
    
    public function kanban()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        $data['statuses'] = $this->leads_model->get_status();
        
        echo $this->load->view('admin/leads/kan-ban', $data, true);
    }
	
	public function lead_add($id = '')
    {
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        
        $data['switch_kanban'] = true;
        
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
        $data['region'] = $this->currencies_model->get_region();
        
        $data['staff'] = $this->staff_model->get('', 1);
        
        $data['all_status']           = $this->leads_model->get_status();
        $data['all_status_loss']      = $this->leads_model->get_status_loss();
        $data['customer_detail_type'] = $this->leads_model->get_customer_type();
        $data['customer_groups']      = $this->clients_model->get_groups();
        
        $data['all_country'] = $this->leads_model->get_country();
        
        $data['sources']       = $this->leads_model->get_source();
        $data['all_lead_type'] = $this->leads_model->get_lead_type();
        
//--------------- Lead Requirnment Add -------------//
		$this->load->model('invoice_items_model');
		$data['items_groups']        = $this->invoice_items_model->get_groups();
        $data['get_sub_groups']      = $this->invoice_items_model->get_sub_groups();
        $data['get_groups_document'] = $this->invoice_items_model->get_groups_document();
        
		
        $data['title']  = _l('leads');
        // in case accesed the url leads/index/ directly with id - used in search
        $data['leadid'] = $id;
        $customer_name  = "";
        $customer_type  = "";
        $lead_status    = "";
        if ($this->input->post()) {
			
            if ($this->input->post('customer_namec') == 'ncustomer') {
                $customer_name = $this->input->post('customer');
                $customer_type = $this->input->post('customer_type');
            } else {
                $customer_type = $this->input->post('customer_typehidden_');
                
            }
            
            if ($this->input->post('lead_status_lo') == '') {
                $lead_status = $this->input->post('lead_status_losss');
            } else {
                $lead_status = $this->input->post('lead_status_lo');
                
            }
            $reportingmanager = $this->leads_model->get_reporting_to(get_staff_user_id());
			$reportingmanager = array_map('trim',explode(',',$reportingmanager));
			$reportingmanager = array_unique($reportingmanager);
			$reportingmanager = implode(', ', $reportingmanager);
			$reportingmanager = trim($reportingmanager,",");
           
		    $sql_state    = "SELECT state FROM tblstaff WHERE staffid='" . get_staff_user_id() . "'";
			$staff_state = $this->db->query($sql_state)->row()->state; 
			
		   $data_lead = array(
                'company' => $this->input->post('lead_name'),
                'description' => $this->input->post('lead_description'),
                'email' => $this->input->post('email'),
                'accepacted_date' => $this->input->post('accepted_date'),
                'phonenumber' => $this->input->post('phone'),
                'mobile_number' => $this->input->post('mobile'),
                'competition' => $this->input->post('competition'),
                'competition1' => $this->input->post('competition1'),
                'competition2' => $this->input->post('competition2'),
                'competition3' => $this->input->post('competition3'),
                'competition4' => $this->input->post('competition4'),
                'can_be_called' => $this->input->post('can_be_called'),
                'zip' => $this->input->post('zipcode'),
                'assigned' => $this->input->post('lead_owner'),
                'status' => $this->input->post('lead_status'),
                'project_total_amount' => $this->input->post('project_total_amount'),
                'project_awarded_to' => $this->input->post('project_awarded_to'),
                'dateadded' => date('Y-m-d H:i:s'),
                'dateassigned' => date('Y-m-d H:i:s'),
                'source' => $this->input->post('lead_source'),
                'opportunity' => $this->input->post('opportunity_amount'),
                'name' => $this->input->post('first_name'),
                'title' => $this->input->post('position'),
                'address' => $this->input->post('address'),
                'country' => $this->input->post('country_id'),
                'state' => $staff_state,
                'city' => $this->input->post('city_id'),
                'dillar_data' => $this->input->post('dillar_data'),
                'project_location' => $this->input->post('project_location'),
                'dilar' => $this->input->post('dilar'),
                'contractor' => $this->input->post('contractor'),
                'customer_new_existing' => 'ecustomer',
                'status_lost' => $lead_status,
                'status_closed_won' => $this->input->post('lead_status_losss'),
                'customer_type' => $customer_type,
                'customer_group' => $this->input->post('customer_group'),
                'customer_name' => $this->input->post('customer_existing'),
                'lead_contact' => $this->input->post('lead_contact'),
                'reportingto' => $reportingmanager,
                'region' => $this->leads_model->get_region_name($this->input->post('state_id'))
                
            );
            $query_check_rec = $this->db->query('SELECT id FROM tblleads where customer_name = "'.$this->input->post('customer_existing').'" AND customer_group = "'.$this->input->post('customer_group').'" AND opportunity = "'.$this->input->post('opportunity').'" AND description = "'.$this->input->post('lead_description').'"');
            $total_rec =  $query_check_rec->num_rows();
			if($total_rec > 0){
				redirect('admin/leads');
			}else{
				
			
				$this->db->insert('tblleads', $data_lead);
				$insert_id     = $this->db->insert_id();
				$data_customer = array(
					
					'customer_name' => $this->input->post('customer'),
					'customer_type_code' => $this->input->post('customer_type')
					
				);
				
				if ($this->input->post('customer_namec') == 'ncustomer') {
					$this->db->insert('customer_type_value', $data_customer);
					
				}
				
				$cust_id   = $this->input->post('customer_group');
				$cust_name = $this->input->post('customer_existing');
				
				$additional_data = "<br><br><strong> Details: </strong>";
				$additional_data .= "<br><strong>Lead Title : </strong>" . $this->input->post('lead_name');
				$additional_data .= "<strong><br/>Description : </strong>" . $this->input->post('lead_description');
				$additional_data .= "<strong><br/>Customer Group: </strong>" . $this->leads_model->customer_group_byname($cust_id);
				$additional_data .= "<strong><br/>Opportunity Amount: </strong>" . $this->input->post('opportunity_amount');
				$additional_data .= "<strong><br/>Lead Source: </strong>" . $this->leads_model->lead_source_byname($this->input->post('lead_source'));
				$additional_data .= "<strong><br/>Project Location: </strong>" . $this->input->post('project_location');
				$additional_data .= "<strong><br/>Competitor 1: </strong>" . $this->input->post('competition');
				$additional_data .= "<strong><br/>Lead Status: </strong>" . $this->leads_model->lead_status_byname($this->input->post('lead_status'));
				$additional_data .= "<strong><br/>Lead Status Remarks: </strong>" . $this->input->post('lead_status_lo');
				$additional_data .= "<strong><br/>Finalization Month (Expected): </strong>" . date("d-m-Y", strtotime($this->input->post('accepted_date')));
				
				
				$data_lead_activity = array(
					'leadid' => $insert_id,
					'description' => '<a href="#tab_lead_profile" aria-controls="tab_lead_profile" role="tab" data-toggle="tab" class="leadpro">Lead Added :</a>' . $additional_data,
					'additional_data' => '',
					'date' => date('Y-m-d H:i:s'),
					'staffid' => $this->input->post('lead_owner'),
					'full_name' => get_staff_full_name(),
					'custom_activity' => '0'
				);
				$this->db->insert('tblleadactivitylog', $data_lead_activity);
				
				//------------ Mail ------------------------//		
				$subject = "Halonix LMS - New Lead Added";
				$message = "<html>	<head>		<title>HTML email</title>	</head>	<body>	New Lead added by " . get_staff_full_name();
				$message .= "<br>Lead Description : " . $this->input->post('lead_name');
				$message .= "<br/>Customer Group: " . $this->leads_model->customer_group_byname($cust_id);
				$message .= "<br/>Customer Name: " . $this->leads_model->get_customer_name($cust_name);
				$message .= "<br/>Opportunity Amount: " . $this->input->post('opportunity_amount') . ' Lacs';
				$message .= "<br/>Description : " . $this->input->post('lead_description');
				$message .= "</body></html>";
				
				$merge_fields = array();
				$merge_fields = array_merge($merge_fields, get_lead_merge_fields($id));
				
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
			 
				 
				if ($insert_id > 0) {
					$Return['result'] = "Lead Added";
					
					//---------------------- Item Requirnment ---------------//
					$itemcatavail = $this->input->post('item_cat');
					if(isset($itemcatavail)){
						//print_r($this->input->post());exit;
						$project_manager_data = array(
							'project_manager_approval' => '0',
							'assproject_manager_approval' => '0',
						);
						
						$this->db->where('id',$insert_id );
						$this->db->update('tblleads', $project_manager_data);
						
					$estimate_data = array(
						'lead_id' =>$insert_id ,
						'added_on' => date('Y-m-d H:s'),
						'added_by' => $this->input->post('lead_owner'),
						'remark' => $this->input->post('remark')
						
					);
					$document_due_date = array(
						'document_due_date' => $this->input->post('document_due_date'),
				   
					);
					$this->db->where('id',$insert_id );
					$this->db->update('tblleads', $document_due_date);
						
						
					$this->db->insert('tbllead_requirment', $estimate_data);
					$item_insert_id = $this->db->insert_id();
					
					$item_cat                 = $this->input->post('item_cat');
					$subcategory              = $this->input->post('item_sub_cat');
					$item                     = $this->input->post('item');
					$item_description         = $this->input->post('item_description');
					$warranty                 = $this->input->post('warranty');
					$quantity                 = $this->input->post('quantity');
					$rate                     = $this->input->post('rate');
					$document                 = $this->input->post('document');
					$proposed_item            = $this->input->post('proposed_item');
					$proposed_rate            = $this->input->post('proposed_rate');
					$item_description_propsed = $this->input->post('item_description_propsed');
					
					$total_record = sizeof($item_cat);
					$additional_data = '';
					for ($i = 0; $i < $total_record; $i++) {
						
						$array_doc        = explode(',', $document[$i]);
						$total_record_doc = sizeof($array_doc);
					 for($j=0;$j<$total_record_doc;$j++) {
						
					   if($item_description[$i]==''){
								$item_name = $this->leads_model->get_item($item[$i]);
								$title = $array_doc[$j];
								$item_detail = $item_name;
							}else{
								$title = $array_doc[$j];
								$item_detail=$item_description[$i];
							}
						
						$tbl_lead_required_doc = array(
						'lead_id' =>$insert_id ,
						'category_id' => $item_cat[$i],
						'title' => $item_detail,'wattage' => $subcategory[$i],'wattage_title' => $title,
						);
						
						$this->db->insert('tbl_lead_required_doc', $tbl_lead_required_doc);    
						} 
						
						$req_data = array(
							'lead_requirment_id' =>$insert_id ,
							'category_id' => $item_cat[$i],
							'subcategory_id' => $subcategory[$i],
							'item_id' => $item[$i],
							'item_description' => $item_description[$i],
							'warranty' => $warranty[$i],
							'quantity' => $quantity[$i],
							'rate' => $rate[$i],
							'document' => $document[$i],
							'proposed_item_id' => $proposed_item[$i],
							'proposed_item_qty' => $proposed_rate[$i],
							'item_description_propsed' => $item_description_propsed[$i],
							'addedon' => date('Y-m-d H:s')
							
						);
					   $this->db->insert('tbllead_requirment_detail', $req_data);
						
				   
					 $additional_data = explode('', $req_data);
						 $additional_data = "<br><br><strong> Details: </strong>";
					$additional_data .= "<br><strong>Category  : </strong>" . $this->leads_model->lead_requirment_category($item_cat[$i]);
					$additional_data .= "<br><strong>Wattage  : </strong>" . $this->leads_model->lead_requirment_wattage($subcategory[$i]);
					$additional_data .= "<br><strong>Item   : </strong>" . $this->leads_model->lead_requirment_items($item[$i]);
					$additional_data .= "<br><strong>Item Warranty    : </strong>" . $warranty[$i] ;
					$additional_data .= "<br><strong>Quantity   : </strong>" . $quantity[$i] ;
					$additional_data .= "<br><strong>Required Price   : </strong>" . $rate[$i];	
					$additional_data .= "<br><strong>Document Required : </strong>" .$document[$i];
					
				  
					
					
					$data_lead_activity = array(
						'leadid' =>$insert_id ,
						'description' => '<a href="#lead_requirment"  aria-controls="lead_requirment" role="tab"  data-toggle="tab" class="leadreq">Lead requirement added</a>'.$additional_data,
						'additional_data' => '',
						'date' => date('Y-m-d H:i:s'),
						'staffid' => get_staff_user_id(),
						'full_name' => get_staff_full_name(),
						'custom_activity' => '0'
					);
						$this->db->insert('tblleadactivitylog', $data_lead_activity);
						
						$subject = "Halonix LMS- Item Requirement Added";
						$message = "<html><head>   <title>HTML email</title>    </head>    <body>    Item Requirement added by ".get_staff_full_name();
						
						$message .= "</br></br><b>Details:</b>";
						$message .= "</br>".$additional_data;
						$message .= "</body></html>";
					   
					
					 }
					
					
					
					
					if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
						$uploaddir = './uploads/lead_documents/' .$insert_id  . '/';
						if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
							die("Error creating folder $uploaddir");
						}
						$fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
						$first_title = $this->input->post('first_title');
						$img_name    = $uploaddir . basename($_FILES['first_doc']['name']);
						move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
						
						$data_img = array(
							'lead_id' =>$insert_id ,
							'title' => $first_title,
							'doc' => basename($_FILES['first_doc']['name'])
						);
						
						$this->db->insert('tbllead_requirment_file', $data_img);
					}
					if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
						$uploaddir = './uploads/lead_documents/' .$insert_id  . '/';
						if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
							die("Error creating folder $uploaddir");
						}
						$fileInfo     = pathinfo($_FILES["second_doc"]["name"]);
						$second_title = $this->input->post('second_title');
						$img_name     = $uploaddir . basename($_FILES['second_doc']['name']);
						move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
						$data_img = array(
							'lead_id' =>$insert_id ,
							'title' => $second_title,
							'doc' => basename($_FILES['second_doc']['name'])
						);
						$this->db->insert('tbllead_requirment_file', $data_img);
					}
					
					if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
						$uploaddir = './uploads/lead_documents/' .$insert_id  . '/';
						if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
							die("Error creating folder $uploaddir");
						}
						$fileInfo    = pathinfo($_FILES["third_doc"]["name"]);
						$third_title = $this->input->post('third_title');
						$img_name    = $uploaddir . basename($_FILES['third_doc']['name']);
						move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
						$data_img = array(
							'lead_id' =>$insert_id ,
							'title' => $third_title,
							'doc' => basename($_FILES['third_doc']['name'])
						);
						$this->db->insert('tbllead_requirment_file', $data_img);
					}
					if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
						$uploaddir = './uploads/lead_documents/' .$insert_id  . '/';
						if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
							die("Error creating folder $uploaddir");
						}
						$fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
						$fourth_title = $this->input->post('fourth_title');
						$img_name     = $uploaddir . basename($_FILES['fourth_doc']['name']);
						move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
						$data_img = array(
							'lead_id' =>$insert_id ,
							'title' => $fourth_title,
							'doc' => basename($_FILES['fourth_doc']['name'])
						);
						$this->db->insert('tbllead_requirment_file', $data_img);
					}
					if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
						$uploaddir = './uploads/lead_documents/' .$insert_id  . '/';
						if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
							die("Error creating folder $uploaddir");
						}
						$fileInfo    = pathinfo($_FILES["fifth_doc"]["name"]);
						$fifth_title = $this->input->post('fifth_title');
						$img_name    = $uploaddir . basename($_FILES['fifth_doc']['name']);
						move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
						$data_img = array(
							'lead_id' =>$insert_id ,
							'title' => $fifth_title,
							'doc' => basename($_FILES['fifth_doc']['name'])
						);
						$this->db->insert('tbllead_requirment_file', $data_img);
					}
					
					//----------------- Mail --------------------------//
					
					$subject = "Halonix LMS - Item Requirement Added";
					$message = "<html><head>   <title>HTML email</title>    </head>    <body> Item Requirement added by ".get_staff_full_name();
					
					$message .= "</br></br><b>New Item Requirement Added Please Check.</b>";
					$message .= "</body></html>";
					
					$this->db->where('id',$insert_id );
					$state = $this->db->get('tblleads')->row()->state;
					
					$emailIDSPM = $this->staff_model->getEmailIDPM('email','tblstaff','state', $state);
				   
					foreach($emailIDSPM as $emails) {
						$this->sent_smtp__email($emails['email'], $subject, $message);
					}
					
					$getEmailIDASM = $this->staff_model->getEmailIDASM('email','tblstaff','state', $state);
				 
					foreach($getEmailIDASM as $emails) {
						$this->sent_smtp__email($emails['email'], $subject, $message);
					}
					
					$this->sent_smtp_bcc_email($subject, $message);
						
					}	
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				
			}	
			
            redirect('admin/leads');
            //redirect('admin/leads?confirm=1&lead_id=' . $insert_id);
        }
        
        $this->load->view('admin/leads/lead_add', $data);
        
    }
  /*  
    public function lead_add($id = '')
    {
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        
        $data['switch_kanban'] = true;
        
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
        $data['region'] = $this->currencies_model->get_region();
        
        $data['staff'] = $this->staff_model->get('', 1);
        
        $data['all_status']           = $this->leads_model->get_status();
        $data['all_status_loss']      = $this->leads_model->get_status_loss();
        $data['customer_detail_type'] = $this->leads_model->get_customer_type();
        $data['customer_groups']      = $this->clients_model->get_groups();
        
        $data['all_country'] = $this->leads_model->get_country();
        
        $data['sources']       = $this->leads_model->get_source();
        $data['all_lead_type'] = $this->leads_model->get_lead_type();
        
        $data['title']  = _l('leads');
        // in case accesed the url leads/index/ directly with id - used in search
        $data['leadid'] = $id;
        $customer_name  = "";
        $customer_type  = "";
        $lead_status    = "";
        if (isset($_POST['submit'])) {
            if ($this->input->post('customer_namec') == 'ncustomer') {
                $customer_name = $this->input->post('customer');
                $customer_type = $this->input->post('customer_type');
            } else {
                $customer_type = $this->input->post('customer_typehidden_');
                
            }
            
            if ($this->input->post('lead_status_lo') == '') {
                $lead_status = $this->input->post('lead_status_losss');
            } else {
                $lead_status = $this->input->post('lead_status_lo');
                
            }
            $reportingmanager = $this->leads_model->get_reporting_to(get_staff_user_id());
			$reportingmanager = array_map('trim',explode(',',$reportingmanager));
			$reportingmanager = array_unique($reportingmanager);
			$reportingmanager = implode(', ', $reportingmanager);
			$reportingmanager = trim($reportingmanager,",");
           
		   $data_lead = array(
                'company' => $this->input->post('lead_name'),
                'description' => $this->input->post('lead_description'),
                'email' => $this->input->post('email'),
                'accepacted_date' => $this->input->post('accepted_date'),
                'phonenumber' => $this->input->post('phone'),
                'mobile_number' => $this->input->post('mobile'),
                'competition' => $this->input->post('competition'),
                'competition1' => $this->input->post('competition1'),
                'competition2' => $this->input->post('competition2'),
                'competition3' => $this->input->post('competition3'),
                'competition4' => $this->input->post('competition4'),
                'can_be_called' => $this->input->post('can_be_called'),
                'zip' => $this->input->post('zipcode'),
                'assigned' => $this->input->post('lead_owner'),
                'status' => $this->input->post('lead_status'),
                'project_total_amount' => $this->input->post('project_total_amount'),
                'project_awarded_to' => $this->input->post('project_awarded_to'),
                'dateadded' => date('Y-m-d H:i:s'),
                'dateassigned' => date('Y-m-d H:i:s'),
                'source' => $this->input->post('lead_source'),
                'opportunity' => $this->input->post('opportunity_amount'),
                'name' => $this->input->post('first_name'),
                'title' => $this->input->post('position'),
                'address' => $this->input->post('address'),
                'country' => $this->input->post('country_id'),
                'state' => get_staff_state_id(),
                'city' => $this->input->post('city_id'),
                'dillar_data' => $this->input->post('dillar_data'),
                'project_location' => $this->input->post('project_location'),
                'dilar' => $this->input->post('dilar'),
                'contractor' => $this->input->post('contractor'),
                'customer_new_existing' => 'ecustomer',
                'status_lost' => $lead_status,
                'status_closed_won' => $this->input->post('lead_status_losss'),
                'customer_type' => $customer_type,
                'customer_group' => $this->input->post('customer_group'),
                'customer_name' => $this->input->post('customer_existing'),
                'lead_contact' => $this->input->post('lead_contact'),
                'reportingto' => $reportingmanager,
                'region' => $this->leads_model->get_region_name($this->input->post('state_id'))
                
            );
            
            
            $this->db->insert('tblleads', $data_lead);
            $insert_id     = $this->db->insert_id();
            $data_customer = array(
                
                'customer_name' => $this->input->post('customer'),
                'customer_type_code' => $this->input->post('customer_type')
                
            );
            
            if ($this->input->post('customer_namec') == 'ncustomer') {
                $this->db->insert('customer_type_value', $data_customer);
                
            }
            
            $cust_id   = $this->input->post('customer_group');
            $cust_name = $this->input->post('customer_existing');
            
            $additional_data = "<br><br><strong> Details: </strong>";
            $additional_data .= "<br><strong>Lead Title : </strong>" . $this->input->post('lead_name');
            $additional_data .= "<strong><br/>Description : </strong>" . $this->input->post('lead_description');
            $additional_data .= "<strong><br/>Customer Group: </strong>" . $this->leads_model->customer_group_byname($cust_id);
            $additional_data .= "<strong><br/>Opportunity Amount: </strong>" . $this->input->post('opportunity_amount');
            $additional_data .= "<strong><br/>Finalization Month (Expected): </strong>" . $this->input->post('accepted_date');
            
            
            
            $data_lead_activity = array(
                'leadid' => $insert_id,
                'description' => '<a href="#tab_lead_profile" aria-controls="tab_lead_profile" role="tab" data-toggle="tab" class="leadpro">Lead Added :</a>' . $additional_data,
                'additional_data' => '',
                'date' => date('Y-m-d H:i:s'),
                'staffid' => $this->input->post('lead_owner'),
                'full_name' => get_staff_full_name(),
                'custom_activity' => '0'
            );
            $this->db->insert('tblleadactivitylog', $data_lead_activity);
            
            //------------ Mail ------------------------//		
            $subject = "Halonix LMS - New Lead Added";
            $message = "<html>	<head>		<title>HTML email</title>	</head>	<body>	New Lead added by " . get_staff_full_name();
            $message .= "<br>Lead Description : " . $this->input->post('lead_name');
            $message .= "<br/>Customer Group: " . $this->leads_model->customer_group_byname($cust_id);
            $message .= "<br/>Customer Name: " . $this->leads_model->get_customer_name($cust_name);
            $message .= "<br/>Opportunity Amount: " . $this->input->post('opportunity_amount') . ' Lacs';
            $message .= "<br/>Description : " . $this->input->post('lead_description');
            $message .= "</body></html>";
            
            $merge_fields = array();
            $merge_fields = array_merge($merge_fields, get_lead_merge_fields($id));
            
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
           
			 
            if ($insert_id > 0) {
                $Return['result'] = "Lead Added";
            } else {
                $Return['error'] = $this->lang->line('xin_error_msg');
            }
            redirect('admin/leads?confirm=1&lead_id=' . $insert_id);
        }
        
        $this->load->view('admin/leads/lead_add', $data);
        
    }
   */
    
	
    public function customer_type_value_byname()
    {
        $customer_type = $this->input->get('customer_type');
        
        $data = $this->clients_model->get_details($customer_type);
        
        echo json_encode($data);
        
    }
    
    public function getlead_contact()
    {
        $id = $this->input->get('id');
        
        $data = $this->clients_model->getlead_contact($id);
        
        echo json_encode($data);
        
    }
    
    public function gettransfer_contact()
    {
        $id = $this->input->get('id');
        
        $data = $this->leads_model->get_company_detail($id);
        
        echo json_encode($data);
        
    }
    public function getlead_contact_edit()
    {
        $id = $this->input->get('lead_contact');
        
        $data = $this->clients_model->getlead_contact($id);
        
        echo json_encode($data);
        
    }
    
    
    public function customer_type_value_byname_ghtd($id)
    {
        
        $data['userid'] = $id;
        $data           = $this->clients_model->get($id);
        echo $data;
        
    }
    
    
    public function getCustomerByGroup()
    {
        
        $customer_group = $this->input->get('customer_group');
        $this->db->select('tblclients.company as name,tblclients.userid as id,tblcustomergroups_in.customer_id');
        $this->db->from('tblcustomergroups_in');
        $this->db->join('tblclients', 'tblclients.userid = tblcustomergroups_in.customer_id', 'left');
        
		if( get_staff_role() == 1 ){
			$condt = "tblcustomergroups_in.groupid='".$customer_group."' AND tblclients.addedfrom LIKE '%".get_staff_user_id()."%' AND tblclients.active='1' AND tblclients.approve='1'";				
		}else{
			$condt = "tblcustomergroups_in.groupid='".$customer_group."' AND (tblclients.reportingto LIKE '%".get_staff_user_id()."%' OR tblclients.addedfrom LIKE '%".get_staff_user_id()."%') AND tblclients.active='1' AND tblclients.approve='1'";				
		}
        /* $condt = array(
            'tblclients.active' => 1,
            'tblclients.approve' => 1,
            'tblcustomergroups_in.groupid' => $customer_group,
            'tblclients.addedfrom' => get_staff_user_id()
        );
         */
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    } 
	public function getCustomerByGroupCal()
    {
        
        $customer_group = $this->input->get('customer_group');
        $this->db->select('tblclients.company as name,tblclients.userid as id,tblcustomergroups_in.customer_id');
        $this->db->from('tblcustomergroups_in');
        $this->db->join('tblclients', 'tblclients.userid = tblcustomergroups_in.customer_id', 'left');
        //$this->db->join('tblleads', 'tblclients.userid = tblleads.customer_name', 'left');
       /*  
        $condt = array(
            'tblclients.active' => 1,
            'tblclients.approve' => 1,
            'tblcustomergroups_in.groupid' => $customer_group
        ); */
        if( get_staff_role() == 1 ){
			$condt = "tblcustomergroups_in.groupid='".$customer_group."' AND tblclients.addedfrom LIKE '%".get_staff_user_id()."%' AND tblclients.userid IN (SELECT tblleads.customer_name from tblleads)";				
		}else{
			$condt = "tblcustomergroups_in.groupid='".$customer_group."' AND (tblclients.reportingto LIKE '%".get_staff_user_id()."%' OR tblclients.addedfrom LIKE '%".get_staff_user_id()."%') AND tblclients.userid IN (SELECT customer_name from tblleads)";				
		}
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    } 
	public function getCustomerByGroupUser()
    {
        
        $customer_group = $this->input->get('customer_group');
        $user_id = $this->input->get('user_id');
		
        $this->db->select('tblclients.company as name,tblclients.userid as id,tblcustomergroups_in.customer_id');
        $this->db->from('tblcustomergroups_in');
        $this->db->join('tblclients', 'tblclients.userid = tblcustomergroups_in.customer_id', 'left');
        
        $condt = array(
            'tblclients.active' => 1,
            'tblclients.approve' => 1,
            'tblcustomergroups_in.groupid' => $customer_group,
            'tblclients.addedfrom' => $user_id,
        );
        
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    }
    public function getLeadByCustomer()
    {
        $customer = $this->input->get('customer');
        $this->db->select('id,company');
        $this->db->from('tblleads');
        $condt = array(
            'customer_name' => $customer,
            'assigned' => get_staff_user_id()
        );
        
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    }
	public function getLeadByCustomerCal()
    {
        $customer = $this->input->get('customer');
        $this->db->select('id,company,assigned,reportingto');
        $this->db->from('tblleads');
       
        $condt = "customer_name='".$customer."' AND ( status != 6 OR status != 7)";
		
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    }
	
	public function getLeadByCustomerStaff()
    {
        $customer = $this->input->get('customer');
        $this->db->select('id,company');
        $this->db->from('tblleads');
        $condt = array(
            'customer_name' => $customer,
        );
        
        $this->db->where($condt);
        $data = $this->db->get()->result_array();
        
        echo json_encode($data);
    }
	
	
	public function getTechnicalByLead()
    {
        $lead_id = $this->input->get('lead_id');
        
		$this->db->where('id', $lead_id);
		$region = $this->db->get('tblleads')->row()->region;
		
		$this->db->where('region', $region);
		$region_id = $this->db->get('tblregion')->row()->id;
		
		$this->db->select('staffid,firstname,lastname');
		$this->db->from('tblstaff');
		$where = "role='7' AND region LIKE '%".$region_id."%'";				
		$this->db->where($where);
		$assignto = $this->db->get()->result_array();
		
        echo json_encode($assignto);
    }
    public function getUserCustomerGroup()
    {
        $user = $this->input->get('user');
        
		$customer_group1 = array();
		
		$this->db->select('customer_group');
		$this->db->from('tblleads');
		$where = "assigned LIKE '%".$user."%'";				
		$this->db->where($where);
		$customer_group = $this->db->get()->result_array();
		
		foreach($customer_group as $cg){
			array_push($customer_group1,$cg['customer_group']);
		}
		$customer_group_unique = array_unique($customer_group1);
		$cust_group_id = implode(',', $customer_group_unique);
		
		$this->db->select('id,name');
		$this->db->from('tblcustomersgroups');
		$where = "id IN(".$cust_group_id.")";				
		$this->db->where($where);
		$assignto = $this->db->get()->result_array();
		 
        echo json_encode($assignto);
    }
    public function getReasonByStatus()
    {
        $id   = $this->input->get('status_loss');
        $data = $this->db->where(array('status_id' => $id, 'status' => '1'))->get('status_loss')->result_array();
        
        echo json_encode($data);
    }
    
    public function lookup()
    {
        
        $keyword          = $this->input->post('lead_name');
        $data['response'] = 'false'; //Set default response  
        $query            = $this->leads_model->lookup($keyword);
        
        if (!empty($query)) {
            $data['response'] = 'true'; //Set response  
            $data['message']  = array(); //Create array  
            foreach ($query as $row) {
                $data['message'][] = array(
                    'id' => $row->id,
                    'value' => $row->customer,
                    ''
                ); //Add a row to array  
            }
        }
        if ('IS_AJAX') {
            echo json_encode($data); //echo json string if ajax request  
        } else {
            $this->load->view('admin/leads/lead_add', $data); //Load html view of search results  
        }
    }
    
    public function mylead()
    {
        $data['title']         = "Leads";
        $data['breadcrumbs']   = "Leads";
        $data['path_url']      = 'admin/leads/mylead';
        $data['all_employees'] = $this->leads_model->all_employees();
        $data['all_status']    = $this->leads_model->get_status();
        $data['all_lead_type'] = $this->leads_model->get_lead_type();
        
        
        $data['all_lead_source'] = $this->leads_model->get_lead_source();
        $data['result']          = $this->leads_model->result_getall(id);
        
        $this->load->view('admin/leads/mylead', $data);
   
    }
    
    
    
    public function manage($id)
    {
        
        $data['all_status']  = $this->leads_model->get_status();
        $data['all_country'] = $this->leads_model->get_country();
        $data['all_state']   = $this->leads_model->getcountryBystate('104');
        
        $data['sources'] = $this->leads_model->get_source();
        
        //$data['get_contact_type']  = $this->leads_model->get_contact_type();
        
        $data['all_lead_type']      = $this->leads_model->get_lead_type();
        $data['get_company_detail'] = $this->leads_model->get_company_detail($id);
        /* die(print_r($data['get_company_detail'])); */
        $data['posts']              = $this->db->get_where('tblleads', array(
            'id' => $id
        ))->row();
        
        $data['all_status_loss']      = $this->leads_model->get_status_loss();
        $data['customer_detail_type'] = $this->leads_model->get_customer_type();
        
        $this->load->view("admin/leads/lead_edit", $data);
    }
    public function update($id)
    {
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        
        $data['switch_kanban'] = true;
        
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
        
        $data['get_company_detail'] = $this->leads_model->get_company_detail($id);
        $data['all_status']         = $this->leads_model->get_status_name($id);
        $data['staff']              = $this->staff_model->get('', 1);
        $data['title']              = _l('leads');
        
        // in case accesed the url leads/index/ directly with id - used in search
        $customer_name = "";
        $customer_type = "";
        $lead_status   = "";
        if ($this->input->post('lead_status_lo') == '') {
            $lead_status = $this->input->post('lead_status_losss');
        } else {
            $lead_status = $this->input->post('lead_status_lo');
        }
      
        
        
        $customer_type = $this->input->post('customer_typehidden_');
        $data1         = array(
            'company' => $this->input->post('lead_name'),
            'description' => $this->input->post('lead_description'),
            'email' => $this->input->post('email'),
            'accepacted_date' => $this->input->post('accepted_date'),
            'phonenumber' => $this->input->post('phone'),
            'mobile_number' => $this->input->post('mobile'),
            'competition' => $this->input->post('competition'),
            'competition1' => $this->input->post('competition1'),
            'competition2' => $this->input->post('competition2'),
            'competition3' => $this->input->post('competition3'),
            'competition4' => $this->input->post('competition4'),
            'can_be_called' => $this->input->post('can_be_called'),
            'project_total_amount' => $this->input->post('project_total_amount'),
            'project_awarded_to' => $this->input->post('project_awarded_to'),
            'zip' => $this->input->post('zipcode'),
            'assigned' => $this->input->post('lead_owner'),
            'status' => $this->input->post('lead_status'),
            'dateassigned' => date('Y-m-d H:i:s'),
            'source' => $this->input->post('lead_source'),
            'opportunity' => $this->input->post('opportunity_amount'),
            'name' => $this->input->post('first_name'),
            'title' => $this->input->post('position'),
            'address' => $this->input->post('address'),
            'country' => $this->input->post('country_id'),
            'state' => get_staff_state_id(),
            'dillar_data' => $this->input->post('dillar_data'),
            'project_location' => $this->input->post('project_location'),
            'dilar' => $this->input->post('dilar'),
            'contractor' => $this->input->post('contractor'),
            'customer_new_existing' => 'ecustomer',
            'status_lost' => $lead_status,
            'status_closed_won' => $this->input->post('lead_status_losss'),
            'lead_contact' => $this->input->post('lead_contact'),
            
        );
        
        
        $data_status = $this->db->get_where('tblleadsstatus', array('id' => $this->input->post('lead_status')))->row()->name;
        
        $data_accepted_date = $this->db->get_where('tblleads', array('id' => $id))->row()->accepacted_date;
        $opportunity = $this->db->get_where('tblleads', array('id' => $id))->row()->opportunity;
        
        $additional_data = "<br><br><strong> Details: </strong>";
        $additional_data .= "<br><strong>Lead Title : </strong>" . $this->input->post('lead_name');
        $additional_data .= "<strong><br/>Description : </strong>" . $this->input->post('lead_description');
        $additional_data .= "<strong><br/>Interaction stage changed to : </strong>" . $data_status;
        if ($opportunity != $this->input->post('opportunity_amount')) {
            $additional_data .= "<strong><br/>Opportunity Amount(In Lacs): </strong>" . $this->input->post('opportunity_amount');
        }
		if ($this->input->post('project_total_amount') > 0) {
            $additional_data .= "<strong><br/>Win Amount(In Lacs): </strong>" . $this->input->post('project_total_amount');
        }
		
        if (strtotime($data_accepted_date) < strtotime($this->input->post('accepted_date'))) {
            $additional_data .= "<strong><br/>New Finalization Month: </strong>" . $this->input->post('accepted_date');
        }
        
        
        $status_lost_get = $this->db->get_where('tblleads', array('id' => $id))->row()->status_lost;
        if ($status_lost_get != $lead_status) {
            $additional_data .= "<strong><br/>Remark : </strong>" . $lead_status;
        }
        
        $datal = $this->db->get_where('tblleads', array('id' => $id))->row();
        if ($datal->name != $this->input->post('first_name')) {
            $additional_data .= "<strong><br/>Contact person changed to : </strong>" . $this->input->post('first_name');
        }
		if ('' != $this->input->post('dilar')) {
			$additional_data .= "<strong><br/>Dealer : </strong>" . $this->input->post('dilar');
		}
		if ('' != $this->input->post('contractor')) {
			$additional_data .= "<strong><br/>Contractor : </strong>" . $this->input->post('contractor');
        }
		
        $this->leads_model->update_leads($id, $data1);
        
		$reportingmanager = $this->leads_model->get_reporting_to(get_staff_user_id());
		$reportingmanager = array_map('trim',explode(',',$reportingmanager));
		$reportingmanager = array_unique($reportingmanager);
		$reportingmanager = implode(', ', $reportingmanager);
		$reportingmanager = trim($reportingmanager,",");
			
		if ($this->input->post('lead_status') == 6) {
            $month          = date('F');
            $year           = date('Y');
            $customer_group = $this->input->post('customer_group');
            $customer       = $this->input->post('customer');
            $data_won       = array(
                'lead_id' => $id,
                'staff_id' => $this->input->post('lead_owner'),
                'repoting_to' => $reportingmanager,
                'close_won' => $this->input->post('project_total_amount'),
                'last_executed' => '0',
                'state' => $this->input->post('state_id'),
                'executed' => $this->input->post('project_total_amount'),
                'carry_forward' => '0',
                'status' => '0',
                'month' => $month,
                'year' => $year,
                'customer_name' => $customer_group,
                'customer_group' => $customer,
                'created' => date('Y-m-d H:i:s'),
                'last_changed' => $this->input->post('accepted_date')
            );
            
            $this->db->insert('tbl_carry_leadwon', $data_won);
            
        }
		
		
		$this->db->where('leadid', $id);
		$this->db->update('tblleadchangerequest', array(
			'status' => 'Updated'
		));
		
        
        
        $data_lead_activity = array(
            'leadid' => $id,
            'description' => '<a href="#tab_lead_profile" aria-controls="tab_lead_profile" role="tab" data-toggle="tab" class="leadpro">Lead updated :</a>' . $additional_data,
            'additional_data' => '',
            'date' => date('Y-m-d H:i:s'),
            'staffid' => $this->input->post('lead_owner'),
            'full_name' => get_staff_full_name(),
            'custom_activity' => '0'
        );
        
        $this->db->insert('tblleadactivitylog', $data_lead_activity);
        
		//------------ Lead Updated Mail---------------------//
        
		$subject = "Halonix LMS - Lead Updated by - ".get_staff_full_name();
		
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
		
		$this->sent_smtp__email($staff_email, $subject, $additional_data,$cc);
        
		
        
        redirect('admin/leads');
        
        
        
    }
    
    public function update_customer($id)
    {
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        
        $data = array(
            'customer_name' => $this->input->post('customer'),
            'customer_type_code' => $this->input->post('customer_type_code')
            
        );
        
        $this->leads_model->update_customer_value($id, $data);
        
        redirect('admin/customer_update');
        
        
        
    }
    
    public function update_customer_value($id)
    {
        
        if (!is_staff_member()) {
            access_denied('Leads');
        }
        $data['customer_type_value'] = $this->leads_model->customer_type_value($id);
        $this->load->view("admin/leads/customer_value", $data);
        
    }
    
    public function customer_update()
    {
        if (!is_admin()) {
            access_denied('Customer Value');
        }
        $data['customer'] = $this->leads_model->customer_type_value();
        
        $data['title'] = 'Customer Value';
        $this->load->view('admin/leads/customer_value_detail', $data);
    }
    
    
    public function getBystate()
    {
        $country_id = $this->input->get('country_id');
        $data       = $this->leads_model->getcountryBystate($country_id);
        
        echo json_encode($data);
    }
    public function getBydynsm_id()
    {
        $nsm  = $this->input->get('nsm');
        $data = $this->leads_model->getdynsm_id($nsm);
        
        echo json_encode($data);
    }
    public function getByrsm_id()
    {
        $dynsm_id = $this->input->get('dynsm_id');
        $data     = $this->leads_model->getrsm_id($dynsm_id);
        
        echo json_encode($data);
    }
	public function getByzsm_id()
    {
        $rsm_id = $this->input->get('rsm_id');
        $data     = $this->leads_model->getzsm_id($rsm_id);
        
        echo json_encode($data);
    }
    
    public function getByasm_id()
    {
        $rsm_id = $this->input->get('list_rsm');
        $data   = $this->leads_model->getasm_id($rsm_id);
        
        echo json_encode($data);
    }
    public function getByse_id()
    {
        $list_asm = $this->input->get('list_asm');
        $data     = $this->leads_model->getse_id($list_asm);
        
        echo json_encode($data);
    }
    
    
    public function getcontanct_details()
    {
        $id   = $this->input->get('assigned');
        $data = $this->leads_model->get_company_detail($id);
        
        echo json_encode($data);
    }
    
    public function getBysubcategory()
    {
        $group_id = $this->input->get('group_id');
        $data     = $this->leads_model->getinvoiceBysubcategory($group_id);
        
        echo json_encode($data);
    }
    public function getBycity()
    {
        $state_id = $this->input->get('state_id');
        
        $data = $this->leads_model->getstateBycity($state_id);
        
        echo json_encode($data);
    }
    
    
    /* Add or update lead */
    public function lead($id = '')
    {
        if (!is_staff_member() || ($id != '' && !$this->leads_model->staff_can_access_lead($id))) {
            $this->access_denied_ajax();
        }
        
        if ($this->input->post()) {
            if ($id == '') {
                $id      = $this->leads_model->add($this->input->post());
                $message = $id ? _l('added_successfully', _l('lead')) : '';
                
                echo json_encode(array(
                    'success' => $id ? true : false,
                    'id' => $id,
                    'message' => $message,
                    'leadView' => $id ? $this->_get_lead_data($id) : array()
                ));
            } else {
                $emailOriginal   = $this->db->select('email')->where('id', $id)->get('tblleads')->row()->email;
                $proposalWarning = false;
                $message         = '';
                $success         = $this->leads_model->update($this->input->post(), $id);
                
                if ($success) {
                    $emailNow = $this->db->select('email')->where('id', $id)->get('tblleads')->row()->email;
                    
                    $proposalWarning = (total_rows('tblproposals', array(
                        'rel_type' => 'lead',
                        'rel_id' => $id
                    )) > 0 && ($emailOriginal != $emailNow) && $emailNow != '') ? true : false;
                    
                    $message = _l('updated_successfully', _l('lead'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message,
                    'id' => $id,
                    'proposal_warning' => $proposalWarning,
                    'leadView' => $this->_get_lead_data($id)
                ));
            }
            die;
        }
        
        echo json_encode(array(
            'leadView' => $this->_get_lead_data($id)
        ));
    }
    
    private function _get_lead_data($id = '')
    {
        $reminder_data       = '';
        $data['lead_locked'] = false;
        $data['members']     = $this->staff_model->get('', 1, array(
            'is_not_staff' => 0
        ));
        $data['status_id']   = $this->input->get('status_id') ? $this->input->get('status_id') : get_option('leads_default_status');
        
        if (is_numeric($id)) {
            
            $leadWhere = (has_permission('leads', '', 'view') ? array() : '(assigned = ' . get_staff_user_id() . ' OR addedfrom=' . get_staff_user_id() . ' OR is_public=1)');
            
            $lead = $this->leads_model->get($id, $leadWhere);
            
            
            if (!$lead) {
                header("HTTP/1.0 404 Not Found");
                echo _l('lead_not_found');
                die;
            }
            
            if (total_rows('tblclients', array(
                'leadid' => $id
            )) > 0) {
                $data['lead_locked'] = ((!is_admin() && get_option('lead_lock_after_convert_to_customer') == 1) ? true : false);
            }
            
            $reminder_data = $this->load->view('admin/includes/modals/reminder', array(
                'id' => $lead->id,
                'name' => 'lead',
                'members' => $data['members'],
                'reminder_title' => _l('lead_set_reminder_title')
            ), true);
            
            $data['lead']          = $lead;
            $data['mail_activity'] = $this->leads_model->get_mail_activity($id);
            $data['notes']         = $this->misc_model->get_notes($id, 'lead');
            $data['activity_log']  = $this->leads_model->get_lead_activity_log($id);
            
            
        }
        
        
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();
        
        
        $data = do_action('lead_view_data', $data);
        
        return array(
            'data' => $this->load->view('admin/leads/lead', $data, true),
            'reminder_data' => $reminder_data
        );
    }
    
    public function leads_kanban_load_more()
    {
        if (!is_staff_member()) {
            $this->access_denied_ajax();
        }
        
        $status = $this->input->get('status');
        $page   = $this->input->get('page');
        
        $this->db->where('id', $status);
        $status = $this->db->get('tblleadsstatus')->row_array();
        
        $leads = $this->leads_model->do_kanban_query($status['id'], $this->input->get('search'), $page, array(
            'sort_by' => $this->input->get('sort_by'),
            'sort' => $this->input->get('sort')
        ));
        
        foreach ($leads as $lead) {
            $this->load->view('admin/leads/_kan_ban_card', array(
                'lead' => $lead,
                'status' => $status
            ));
        }
    }
    
    public function switch_kanban($set = 0)
    {
        if ($set == 1) {
            $set = 'true';
        } else {
            $set = 'false';
        }
        $this->session->set_userdata(array(
            'leads_kanban_view' => $set
        ));
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    /* Delete lead from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('leads'));
        }
        
        if (!is_lead_creator($id) && !has_permission('leads', '', 'delete')) {
            access_denied('Delte Lead');
        }
        
        $response = $this->leads_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_lowercase')));
        } elseif ($response === true) {
            set_alert('success', _l('deleted', _l('lead')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_lowercase')));
        }
        $ref = $_SERVER['HTTP_REFERER'];
        
        // if user access leads/inded/ID to prevent redirecting on the same url because will throw 404
        if (!$ref || strpos($ref, 'index/' . $id) !== FALSE) {
            redirect(admin_url('leads'));
        }
        
        redirect($ref);
    }
    
    public function mark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->mark_as_lost($id);
        if ($success) {
            $message = _l('lead_marked_as_lost');
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id
        ));
    }
    
    public function unmark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_lost($id);
        if ($success) {
            $message = _l('lead_unmarked_as_lost');
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id
        ));
    }
    
    public function mark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->mark_as_junk($id);
        if ($success) {
            $message = _l('lead_marked_as_junk');
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id
        ));
    }
    
    public function unmark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_junk($id);
        if ($success) {
            $message = _l('lead_unmarked_as_junk');
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'leadView' => $this->_get_lead_data($id),
            'id' => $id
        ));
    }
    
    public function add_activity()
    {
        $leadid = $this->input->post('leadid');
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($leadid)) {
            $this->access_denied_ajax();
        }
        if ($this->input->post()) {
            $message = $this->input->post('activity');
            $aId     = $this->leads_model->log_lead_activity($leadid, $message);
            
            if ($aId) {
                $this->db->where('id', $aId);
                $this->db->update('tblleadactivitylog', array(
                    'custom_activity' => 1
                ));
            }
            echo json_encode(array(
                'leadView' => $this->_get_lead_data($leadid),
                'id' => $leadid
            ));
        }
    }
    
    public function get_convert_data($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        $data['lead'] = $this->leads_model->get($id);
        $this->load->view('admin/leads/convert_to_customer', $data);
    }
    
    /**
     * Convert lead to client
     * @since  version 1.0.1
     * @return mixed
     */
    public function convert_to_customer()
    {
        if (!is_staff_member()) {
            access_denied('Lead Convert to Customer');
        }
        
        if ($this->input->post()) {
            $default_country  = get_option('customer_default_country');
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);
            
            $original_lead_email = $data['original_lead_email'];
            unset($data['original_lead_email']);
            
            if (isset($data['transfer_notes'])) {
                $notes = $this->misc_model->get_notes($data['leadid'], 'lead');
                unset($data['transfer_notes']);
            }
            
            if (isset($data['merge_db_fields'])) {
                $merge_db_fields = $data['merge_db_fields'];
                unset($data['merge_db_fields']);
            }
            
            if (isset($data['merge_db_contact_fields'])) {
                $merge_db_contact_fields = $data['merge_db_contact_fields'];
                unset($data['merge_db_contact_fields']);
            }
            
            if (isset($data['include_leads_custom_fields'])) {
                $include_leads_custom_fields = $data['include_leads_custom_fields'];
                unset($data['include_leads_custom_fields']);
            }
            
            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }
            
            $data['valid_from']      = $data['valid_from'];
            $data['valid_to']        = $data['valid_to'];
            $data['billing_street']  = $data['address'];
            $data['billing_city']    = $data['city'];
            $data['billing_state']   = $data['state'];
            $data['billing_zip']     = $data['zip'];
            $data['billing_country'] = $data['country'];
            
            $data['is_primary'] = 1;
            
            $id = $this->clients_model->add($data, true);
            if ($id) {
                if (isset($notes)) {
                    foreach ($notes as $note) {
                        $this->db->insert('tblnotes', array(
                            'rel_id' => $id,
                            'rel_type' => 'customer',
                            'dateadded' => $note['dateadded'],
                            'addedfrom' => $note['addedfrom'],
                            'description' => $note['description'],
                            'date_contacted' => $note['date_contacted']
                        ));
                    }
                }
                if (!has_permission('customers', '', 'view') && get_option('auto_assign_customer_admin_after_lead_convert') == 1) {
                    $this->db->insert('tblcustomeradmins', array(
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id' => $id,
                        'staff_id' => get_staff_user_id()
                    ));
                }
                $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted', false, serialize(array(
                    get_staff_full_name()
                )));
                $default_status = $this->leads_model->get_status('', array(
                    'isdefault' => 1
                ));
                $this->db->where('id', $data['leadid']);
                $this->db->update('tblleads', array(
                    'date_converted' => date('Y-m-d H:i:s'),
                    'status' => $default_status[0]['id'],
                    'junk' => 0,
                    'lost' => 0
                ));
                // Check if lead email is different then client email
                $contact = $this->clients_model->get_contact(get_primary_contact_user_id($id));
                if ($contact->email != $original_lead_email) {
                    if ($original_lead_email != '') {
                        $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted_email', false, serialize(array(
                            $original_lead_email,
                            $contact->email
                        )));
                    }
                }
                if (isset($include_leads_custom_fields)) {
                    foreach ($include_leads_custom_fields as $fieldid => $value) {
                        // checked don't merge
                        if ($value == 5) {
                            continue;
                        }
                        // get the value of this leads custom fiel
                        $this->db->where('relid', $data['leadid']);
                        $this->db->where('fieldto', 'leads');
                        $this->db->where('fieldid', $fieldid);
                        $lead_custom_field_value = $this->db->get('tblcustomfieldsvalues')->row()->value;
                        // Is custom field for contact ot customer
                        if ($value == 1 || $value == 4) {
                            if ($value == 4) {
                                $field_to = 'contacts';
                            } else {
                                $field_to = 'customers';
                            }
                            $this->db->where('id', $fieldid);
                            $field = $this->db->get('tblcustomfields')->row();
                            // check if this field exists for custom fields
                            $this->db->where('fieldto', $field_to);
                            $this->db->where('name', $field->name);
                            $exists               = $this->db->get('tblcustomfields')->row();
                            $copy_custom_field_id = null;
                            if ($exists) {
                                $copy_custom_field_id = $exists->id;
                            } else {
                                // there is no name with the same custom field for leads at the custom side create the custom field now
                                $this->db->insert('tblcustomfields', array(
                                    'fieldto' => $field_to,
                                    'name' => $field->name,
                                    'required' => $field->required,
                                    'type' => $field->type,
                                    'options' => $field->options,
                                    'display_inline' => $field->display_inline,
                                    'field_order' => $field->field_order,
                                    'slug' => slug_it($field_to . '_' . $field->name, array(
                                        'separator' => '_'
                                    )),
                                    'active' => $field->active,
                                    'only_admin' => $field->only_admin,
                                    'show_on_table' => $field->show_on_table,
                                    'bs_column' => $field->bs_column
                                ));
                                $new_customer_field_id = $this->db->insert_id();
                                if ($new_customer_field_id) {
                                    $copy_custom_field_id = $new_customer_field_id;
                                }
                            }
                            if ($copy_custom_field_id != null) {
                                $insert_to_custom_field_id = $id;
                                if ($value == 4) {
                                    $insert_to_custom_field_id = get_primary_contact_user_id($id);
                                }
                                $this->db->insert('tblcustomfieldsvalues', array(
                                    'relid' => $insert_to_custom_field_id,
                                    'fieldid' => $copy_custom_field_id,
                                    'fieldto' => $field_to,
                                    'value' => $lead_custom_field_value
                                ));
                            }
                        } elseif ($value == 2) {
                            if (isset($merge_db_fields)) {
                                $db_field = $merge_db_fields[$fieldid];
                                // in case user don't select anything from the db fields
                                if ($db_field == '') {
                                    continue;
                                }
                                if ($db_field == 'country' || $db_field == 'shipping_country' || $db_field == 'billing_country') {
                                    $this->db->where('iso2', $lead_custom_field_value);
                                    $this->db->or_where('short_name', $lead_custom_field_value);
                                    $this->db->or_like('long_name', $lead_custom_field_value);
                                    $country = $this->db->get('tblcountries')->row();
                                    if ($country) {
                                        $lead_custom_field_value = $country->country_id;
                                    } else {
                                        $lead_custom_field_value = 0;
                                    }
                                }
                                $this->db->where('userid', $id);
                                $this->db->update('tblclients', array(
                                    $db_field => $lead_custom_field_value
                                ));
                            }
                        } elseif ($value == 3) {
                            if (isset($merge_db_contact_fields)) {
                                $db_field = $merge_db_contact_fields[$fieldid];
                                if ($db_field == '') {
                                    continue;
                                }
                                $primary_contact_id = get_primary_contact_user_id($id);
                                $this->db->where('id', $primary_contact_id);
                                $this->db->update('tblcontacts', array(
                                    $db_field => $lead_custom_field_value
                                ));
                            }
                        }
                    }
                }
                // set the lead to status client in case is not status client
                $this->db->where('isdefault', 1);
                $status_client_id = $this->db->get('tblleadsstatus')->row()->id;
                $this->db->where('id', $data['leadid']);
                $this->db->update('tblleads', array(
                    'status' => $status_client_id
                ));
                set_alert('success', _l('lead_to_client_base_converted_success'));
                logActivity('Created Lead Client Profile [LeadID: ' . $data['leadid'] . ', ClientID: ' . $id . ']');
                do_action('lead_converted_to_customer', array(
                    'lead_id' => $data['leadid'],
                    'customer_id' => $id
                ));
                redirect(admin_url('clients/client/' . $id));
            }
        }
    }
    
    // Ajax
    /* Used in kanban when dragging */
    public function update_kan_ban_lead_status()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->leads_model->update_lead_status($this->input->post());
        }
    }
    
    public function update_status_order()
    {
        if ($post_data = $this->input->post()) {
            $this->leads_model->update_status_order($post_data);
        }
    }
    
    public function add_lead_attachment()
    {
        $id       = $this->input->post('id');
        $lastFile = $this->input->post('last_file');
        
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        
        handle_lead_attachments($id);
        echo json_encode(array(
            'leadView' => $lastFile ? $this->_get_lead_data($id) : array(),
            'id' => $id
        ));
    }
    
    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->leads_model->add_attachment_to_database($this->input->post('lead_id'), $this->input->post('files'), $this->input->post('external'));
        }
    }
    
    public function delete_attachment($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        echo json_encode(array(
            'success' => $this->leads_model->delete_lead_attachment($id)
        ));
    }
    
    public function delete_note($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            $this->access_denied_ajax();
        }
        echo json_encode(array(
            'success' => $this->misc_model->delete_note($id)
        ));
    }
    
    public function update_all_proposal_emails_linked_to_lead($id)
    {
        $success = false;
        $email   = '';
        if ($this->input->post('update')) {
            $this->load->model('proposals_model');
            
            $this->db->select('email');
            $this->db->where('id', $id);
            $email = $this->db->get('tblleads')->row()->email;
            
            $proposals     = $this->proposals_model->get('', array(
                'rel_type' => 'lead',
                'rel_id' => $id
            ));
            $affected_rows = 0;
            
            foreach ($proposals as $proposal) {
                $this->db->where('id', $proposal['id']);
                $this->db->update('tblproposals', array(
                    'email' => $email
                ));
                if ($this->db->affected_rows() > 0) {
                    $affected_rows++;
                }
            }
            
            if ($affected_rows > 0) {
                $success = true;
            }
        }
        
        echo json_encode(array(
            'success' => $success,
            'message' => _l('proposals_emails_updated', array(
                _l('lead_lowercase'),
                $email
            ))
        ));
    }
    
    public function save_form_data()
    {
        $data = $this->input->post();
        
        // form data should be always sent to the request and never should be empty
        // this code is added to prevent losing the old form in case any errors
        if (!isset($data['formData']) || isset($data['formData']) && !$data['formData']) {
            echo json_encode(array(
                'success' => false
            ));
            die;
        }
        $this->db->where('id', $data['id']);
        $this->db->update('tblwebtolead', array(
            'form_data' => $data['formData']
        ));
        if ($this->db->affected_rows() > 0) {
            echo json_encode(array(
                'success' => true,
                'message' => _l('updated_successfully', _l('web_to_lead_form'))
            ));
        } else {
            echo json_encode(array(
                'success' => false
            ));
        }
    }
    
    public function form($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->leads_model->add_form($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('web_to_lead_form')));
                    redirect(admin_url('leads/form/' . $id));
                }
            } else {
                $success = $this->leads_model->update_form($id, $this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('web_to_lead_form')));
                }
                redirect(admin_url('leads/form/' . $id));
            }
        }
        
        $data['formData'] = array();
        $custom_fields    = get_custom_fields('leads', 'type != "link"');
        
        $cfields       = format_external_form_custom_fields($custom_fields);
        $data['title'] = _l('web_to_lead');
        
        if ($id != '') {
            $data['form']     = $this->leads_model->get_form(array(
                'id' => $id
            ));
            $data['title']    = $data['form']->name . ' - ' . _l('web_to_lead_form');
            $data['formData'] = $data['form']->form_data;
        }
        
        $this->load->model('roles_model');
        $data['roles']    = $this->roles_model->get();
        $data['sources']  = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();
        
        $data['members'] = $this->staff_model->get('', 1, array(
            'is_not_staff' => 0
        ));
        
        $data['languages']           = $this->app->get_available_languages();
        $data['cfields']             = $cfields;
        $data['form_builder_assets'] = true;
        
        $db_fields = array();
        $fields    = array(
            'name',
            'title',
            'email',
            'phonenumber',
            'company',
            'address',
            'city',
            'state',
            'country',
            'zip',
            'description',
            'website'
        );
        
        $fields = do_action('lead_form_available_database_fields', $fields);
        
        $className = 'form-control';
        
        foreach ($fields as $f) {
            $_field_object = new stdClass();
            $type          = 'text';
            
            if ($f == 'email') {
                $type = 'email';
            } elseif ($f == 'description' || $f == 'address') {
                $type = 'textarea';
            } elseif ($f == 'country') {
                $type = 'select';
            }
            
            if ($f == 'name') {
                $label = _l('lead_add_edit_name');
            } elseif ($f == 'email') {
                $label = _l('lead_add_edit_email');
            } elseif ($f == 'phonenumber') {
                $label = _l('lead_add_edit_phonenumber');
            } else {
                $label = _l('lead_' . $f);
            }
            
            $field_array = array(
                'type' => $type,
                'label' => $label,
                'className' => $className,
                'name' => $f
            );
            
            if ($f == 'country') {
                $field_array['values'] = array();
                $countries             = get_all_countries();
                foreach ($countries as $country) {
                    $selected = false;
                    if (get_option('customer_default_country') == $country['country_id']) {
                        $selected = true;
                    }
                    array_push($field_array['values'], array(
                        'label' => $country['short_name'],
                        'value' => (int) $country['country_id'],
                        'selected' => $selected
                    ));
                }
            }
            
            if ($f == 'name') {
                $field_array['required'] = true;
            }
            
            $_field_object->label    = $label;
            $_field_object->name     = $f;
            $_field_object->fields   = array();
            $_field_object->fields[] = $field_array;
            $db_fields[]             = $_field_object;
        }
        $data['bodyclass'] = 'web-to-lead-form';
        $data['db_fields'] = $db_fields;
        $this->load->view('admin/leads/formbuilder', $data);
    }
    
    public function forms($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }
        
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('web_to_lead');
        }
        
        $data['title'] = _l('web_to_lead');
        $this->load->view('admin/leads/forms', $data);
    }
    
    public function delete_form($id)
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }
        
        $success = $this->leads_model->delete_form($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('web_to_lead_form')));
        }
        
        redirect(admin_url('leads/forms'));
    }
    
    // Sources
    /* Manage leads sources */
    public function sources()
    {
        if (!is_admin()) {
            access_denied('Leads Sources');
        }
        $data['sources'] = $this->leads_model->get_source();
        $data['title']   = 'Leads sources';
        $this->load->view('admin/leads/manage_sources', $data);
    }
    public function sources_status_inactive($id)
    {
        if (!is_admin()) {
            access_denied('sources');
        }
        if (!$id) {
            redirect(admin_url('leads/sources'));
        }
        $response = $this->leads_model->sources_status_inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Inactive', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/sources'));
    }
	public function sources_status_active($id)
    {
        if (!is_admin()) {
            access_denied('sources');
        }
        if (!$id) {
            redirect(admin_url('leads/sources'));
        }
        $response = $this->leads_model->sources_status_active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Active', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/sources'));
    }
    
    
    public function sources_region()
    {
        if (!is_admin()) {
            access_denied('Leads Sources');
        }
        $this->load->model('region_model');
        $data['sources'] = $this->region_model->get();
        $data['title']   = 'Leads sources';
        $this->load->view('admin/leads/manage_region', $data);
    }
    
    /* Add or update leads sources */
    public function source()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_source') == '0') {
            access_denied('Leads Sources');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                
                $id = $this->leads_model->add_source($data);
                
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_source')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_source($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_source')));
                }
            }
        }
    }
    
    public function region_addd()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_source') == '0') {
            access_denied('Leads Sources');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                
                $id = $this->leads_model->add_region($data);
                
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_source')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_region($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_source')));
                }
            }
        }
    }
    
    /* Delete leads source */
    public function delete_source($id)
    {
        if (!is_admin()) {
            access_denied('Delete Lead Source');
        }
        if (!$id) {
            redirect(admin_url('leads/sources'));
        }
        $response = $this->leads_model->delete_source($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_source')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('leads/sources'));
    }
    
    // Statuses
    /* View leads statuses */
    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        $data['statuses'] = $this->leads_model->get_status();
        $data['title']    = 'Leads statuses';
        $this->load->view('admin/leads/manage_statuses', $data);
    }
	
	public function depo_master()
    {
    	 if (!is_admin()) {
            access_denied('Segment');
        }
        $data['depo_master'] = $this->leads_model->get_depo_master();
		
        
        $data['title'] = 'Depo Master';
        $this->load->view('admin/leads/manage_depo_master', $data);
		
		
		
    }
	
	  public function depo_master_add()
    {
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                
                $id = $this->leads_model->add_depo_master($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully','Depo Mater'));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_depo_master($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully','Depo Mater'));
                }
            }
        }
    }
    
    
	
	
    public function customer()
    {
        if (!is_admin()) {
            access_denied('Leads Customer');
        }
        $data['customer'] = $this->leads_model->get_customer_type();
        
        $data['title'] = 'Leads customer';
        $this->load->view('admin/leads/manage_customer', $data);
    }
    
    public function segment()
    {
        if (!is_admin()) {
            access_denied('Segment');
        }
        $data['segment'] = $this->leads_model->get_segment();
        
        $data['title'] = 'Segment';
        $this->load->view('admin/leads/manage_segment', $data);
    }
    public function segment_status_inactive($id)
    {
        if (!is_admin()) {
            access_denied('Customer Type');
        }
        if (!$id) {
            redirect(admin_url('leads/segment'));
        }
        $response = $this->leads_model->segment_status_inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Inactive', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/segment'));
    }
	public function segment_status_active($id)
    {
        if (!is_admin()) {
            access_denied('segment');
        }
        if (!$id) {
            redirect(admin_url('leads/segment'));
        }
        $response = $this->leads_model->segment_status_active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Active', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/segment'));
    }
    
    
    public function document_required()
    {
        if (!is_admin()) {
            access_denied('Document Required');
        }
        $data['customer'] = $this->leads_model->document_required_value();
        
        $data['title'] = 'Document Required';
        
        $this->load->view('admin/lead_requirment/manage_document_required', $data);
    }
    public function document_required_status_inactive($id)
    {
        if (!is_admin()) {
            access_denied('document_required');
        }
        if (!$id) {
            redirect(admin_url('leads/document_required'));
        }
        $response = $this->leads_model->document_required_status_inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Inactive', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/document_required'));
    }
	public function document_required_status_active($id)
    {
        if (!is_admin()) {
            access_denied('sources');
        }
        if (!$id) {
            redirect(admin_url('leads/document_required'));
        }
        $response = $this->leads_model->document_required_status_active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Active', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/document_required'));
    }
    
    public function loss_status()
    {
        
        if (!is_admin()) {
            access_denied('Loss Status');
        }
        $data['customer']   = $this->leads_model->get_loss_status();
        $data['all_status'] = $this->leads_model->get_status();
        
        $data['title'] = 'Loss status';
        $this->load->view('admin/leads/manage_status', $data);
    }
    
    
    public function city()
    {
        
        if (!is_admin()) {
            access_denied('');
        }
        $data['city'] = $this->leads_model->get_tbl_city();
        
        $data['all_country'] = $this->leads_model->get_country();
        
        $data['title'] = 'City';
        $this->load->view('admin/leads/city', $data);
    }
	
	/* Delete leads city */
    public function delete_city($id)
    {
        if (!is_admin()) {
            access_denied('Delete City');
        }
		$this->db->where('id', $id);
        $this->db->delete('tbl_city');
        set_alert('success', _l('deleted', 'City'));
        redirect(admin_url('leads/city'));
    }
	/* Update leads city */
    public function update_city()
    {
        if (!is_admin()) {
            access_denied('Update City');
        }
		$data = $this->input->post();
		$id = $data['id'];
        unset($data['id']);
        $datau = array(
				'city' => $data['name'],
			);        
		$this->db->where('id', $id);
		$this->db->update('tbl_city', $datau);
        set_alert('success', _l('updated_successfully', 'City'));
        redirect(admin_url('leads/city'));
    }
	
    public function state()
    {
        
        if (!is_admin()) {
            access_denied('');
        }
        $data['states'] = $this->leads_model->get_tbl_state();
        
        $data['all_country'] = $this->leads_model->get_country();
        
        $data['title'] = 'State';
        $this->load->view('admin/leads/state', $data);
    }
	
	/* Delete leads state */
    public function delete_state($id)
    {
        if (!is_admin()) {
            access_denied('Delete state');
        }
		$this->db->where('id', $id);
        $this->db->delete('tbl_state');
        set_alert('success', _l('deleted', 'State'));
        redirect(admin_url('leads/state'));
    }
	/* Update leads state */
    public function update_state()
    {
        if (!is_admin()) {
            access_denied('Update State');
        }
		$data = $this->input->post();
		$id = $data['id'];
        unset($data['id']);
        $datau = array(
				'state' => $data['name'],
			);        
		$this->db->where('id', $id);
		$this->db->update('tbl_state', $datau);
        set_alert('success', _l('updated_successfully', 'State'));
        redirect(admin_url('leads/state'));
    }
	
	public function state_add()
    {
        $data = array(
            'country_id' => '104',
            'state' => $this->input->post('name')
        );
        $this->db->insert('tbl_state', $data);
        
        $this->load->view('admin/leads/add_state');
        redirect(admin_url('leads/state'));
        
    }
    
	
    public function region()
    {
        
        if (!is_admin()) {
            access_denied('Region');
        }
        $data['customer'] = $this->leads_model->get_loss_status();
        
        $data['title'] = 'Loss status';
        $this->load->view('admin/staff/region', $data);
    }
    
    /* Add or update leads status */
    public function status()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads Statuses');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->leads_model->add_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_status')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_status')));
                }
            }
        }
    }
    public function customer_add()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads customer');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $data['all_country'] = $this->leads_model->get_country();
                $id                  = $this->leads_model->add_customer($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_customer')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_customer($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_customer')));
                }
            }
        }
    }
    
    public function segment_add()
    {
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                
                $id = $this->leads_model->add_segment($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('segment')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_segment($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('segment')));
                }
            }
        }
    }
    
    
    
    public function document_required_add()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads customer');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->leads_model->add_document_required($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', 'document_required'));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_document_required($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', 'document_required'));
                }
            }
        }
    }
    
    public function loass_status_add()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads status');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->leads_model->add_loss_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_status')));
                    }
                } else {
                    echo json_encode(array(
                        'success' => $id ? true : fales,
                        'id' => $id
                    ));
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_loss_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_status')));
                }
            }
        }
    }
    
    
    public function city_add()
    {
        $data = array(
            'country_id' => $this->input->post('country_id'),
            'state_id' => $this->input->post('state_id'),
            'city' => $this->input->post('name')
        );
        
        $this->leads_model->city_add($data);
        
        $this->load->view('admin/leads/add_city');
        redirect(admin_url('leads/city'));
        
    }
    
    
    /* Delete leads status from databae */
    public function delete_status($id)
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        if (!$id) {
            redirect(admin_url('leads/statuses'));
        }
        $response = $this->leads_model->delete_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/statuses'));
    }
    
    public function delete_customer($id)
    {
        if (!is_admin()) {
            access_denied('Leads Customer');
        }
        if (!$id) {
            redirect(admin_url('leads/customer'));
        }
        $response = $this->leads_model->delete_customer($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_customer_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_customer')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_customer_lowercase')));
        }
        redirect(admin_url('leads/customer'));
    }
    
    public function delete_segment($id)
    {
        if (!is_admin()) {
            access_denied('Segment');
        }
        if (!$id) {
            redirect(admin_url('leads/segment'));
        }
        $response = $this->leads_model->delete_segment($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_customer_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_customer')));
        } else {
            set_alert('warning', _l('', _l('Segment delete successfully')));
        }
        redirect(admin_url('leads/segment'));
    }
     public function delete_depo_master($id)
    {
        if (!is_admin()) {
            access_denied('Depo Mater');
        }
        if (!$id) {
            redirect(admin_url('leads/depo_master'));
        }
        $response = $this->leads_model->delete_depo_master($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_customer_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_customer')));
        } else {
            set_alert('warning', _l('', _l('Depo Master delete successfully')));
        }
        redirect(admin_url('leads/depo_master'));
    }
    
    
    
    public function delete_loss_status($id)
    {
        if (!is_admin()) {
            access_denied('Leads status');
        }
        if (!$id) {
            redirect(admin_url('leads/loss_status'));
        }
        $response = $this->leads_model->delete_loss_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/loss_status'));
    }
	public function loss_status_inactive($id)
    {
        if (!is_admin()) {
            access_denied('Leads status');
        }
        if (!$id) {
            redirect(admin_url('leads/loss_status'));
        }
        $response = $this->leads_model->loss_status_inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Inactive', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/loss_status'));
    }
	public function loss_status_active($id)
    {
        if (!is_admin()) {
            access_denied('Leads status');
        }
        if (!$id) {
            redirect(admin_url('leads/loss_status'));
        }
        $response = $this->leads_model->loss_status_active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Active', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/loss_status'));
    }
    
	public function customer_status_inactive($id)
    {
        if (!is_admin()) {
            access_denied('Customer Type');
        }
        if (!$id) {
            redirect(admin_url('leads/customer'));
        }
        $response = $this->leads_model->customer_status_inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Inactive', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/customer'));
    }
	public function customer_status_active($id)
    {
        if (!is_admin()) {
            access_denied('Leads status');
        }
        if (!$id) {
            redirect(admin_url('leads/customer'));
        }
        $response = $this->leads_model->customer_status_active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('Active', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('leads/customer'));
    }
    
    /* Add new lead note */
    public function add_note($rel_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($rel_id)) {
            $this->access_denied_ajax();
        }
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if ($data['contacted_indicator'] == 'yes') {
                $contacted_date         = to_sql_date($data['custom_contact_date'], true);
                $data['date_contacted'] = $contacted_date;
            }
            
            unset($data['contacted_indicator']);
            unset($data['custom_contact_date']);
            
            // Causing issues with duplicate ID or if my prefixed file for lead.php is used
            $data['description'] = isset($data['lead_note_description']) ? $data['lead_note_description'] : $data['description'];
            
            if (isset($data['lead_note_description'])) {
                unset($data['lead_note_description']);
            }
            
            $note_id = $this->misc_model->add_note($data, 'lead', $rel_id);
            
            /* if ($note_id) {
            if (isset($contacted_date)) {
            $this->db->where('id', $rel_id);
            $this->db->update('tblleads', array(
            'lastcontact' => $contacted_date,
            ));
            if ($this->db->affected_rows() > 0) {
            $this->leads_model->log_lead_activity($rel_id, 'not_lead_activity_contacted', false, serialize(array(
            get_staff_full_name(get_staff_user_id()),
            _dt($contacted_date),
            )));
            }
            }
            } */
        }
        echo json_encode(array(
            'leadView' => $this->_get_lead_data($rel_id),
            'id' => $rel_id
        ));
    }
    
    public function test_email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Test Email Integration');
        }
        
        require_once(APPPATH . 'third_party/php-imap/Imap.php');
        
        $mail = $this->leads_model->get_email_integration();
        $ps   = $mail->password;
        if (false == $this->encryption->decrypt($ps)) {
            set_alert('danger', _l('failed_to_decrypt_password'));
            redirect(admin_url('leads/email_integration'));
        }
        $mailbox    = $mail->imap_server;
        $username   = $mail->email;
        $password   = $this->encryption->decrypt($ps);
        $encryption = $mail->encryption;
        // open connection
        $imap       = new Imap($mailbox, $username, $password, $encryption);
        
        if ($imap->isConnected() === false) {
            set_alert('danger', _l('lead_email_connection_not_ok') . '<br /><b>' . $imap->getError() . '</b>');
        } else {
            set_alert('success', _l('lead_email_connection_ok'));
        }
        
        redirect(admin_url('leads/email_integration'));
    }
    
    public function email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Email Intregration');
        }
        if ($this->input->post()) {
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);
            
            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }
            
            $success = $this->leads_model->update_email_integration($data);
            if ($success) {
                set_alert('success', _l('leads_email_integration_updated'));
            }
            redirect(admin_url('leads/email_integration'));
        }
        $data['roles']    = $this->roles_model->get();
        $data['sources']  = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();
        
        $data['members'] = $this->staff_model->get('', 1, array(
            'is_not_staff' => 0
        ));
        
        $data['title']     = _l('leads_email_integration');
        $data['mail']      = $this->leads_model->get_email_integration();
        $data['bodyclass'] = 'leads-email-integration';
        $this->load->view('admin/leads/email_integration', $data);
    }
    
    public function change_status_color()
    {
        if ($this->input->post()) {
            $this->leads_model->change_status_color($this->input->post());
        }
    }
    
    public function import()
    {
        if (!is_admin() && get_option('allow_non_admin_members_to_import_leads') != '1') {
            access_denied('Leads Import');
        }
        
        $simulate_data  = array();
        $total_imported = 0;
        if ($this->input->post()) {
            $simulate = $this->input->post('simulate');
            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $newFilePath = TEMP_FOLDER . $_FILES['file_csv']['name'];
                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 777);
                    }
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $import_result = true;
                        $fd            = fopen($newFilePath, 'r');
                        $rows          = array();
                        while ($row = fgetcsv($fd)) {
                            $rows[] = $row;
                        }
                        fclose($fd);
                        $data['total_rows_post'] = count($rows);
                        if (count($rows) <= 1) {
                            set_alert('warning', 'Not enought rows for importing');
                            redirect(admin_url('leads/import'));
                        }
                        
                        unset($rows[0]);
                        if ($simulate) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }
                        $db_temp_fields = $this->db->list_fields('tblleads');
                        array_push($db_temp_fields, 'tags');
                        
                        $db_fields = array();
                        foreach ($db_temp_fields as $field) {
                            if (in_array($field, $this->not_importable_leads_fields)) {
                                continue;
                            }
                            $db_fields[] = $field;
                        }
                        $custom_fields = get_custom_fields('leads');
                        $_row_simulate = 0;
                        foreach ($rows as $row) {
                            // do for db fields
                            $insert = array();
                            for ($i = 0; $i < count($db_fields); $i++) {
                                // Avoid errors on nema field. is required in database
                                if ($db_fields[$i] == 'name' && $row[$i] == '') {
                                    $row[$i] = '/';
                                } elseif ($db_fields[$i] == 'country') {
                                    if ($row[$i] != '') {
                                        if (!is_numeric($row[$i])) {
                                            $this->db->where('iso2', $row[$i]);
                                            $this->db->or_where('short_name', $row[$i]);
                                            $this->db->or_where('long_name', $row[$i]);
                                            $country = $this->db->get('tblcountries')->row();
                                            if ($country) {
                                                $row[$i] = $country->country_id;
                                            } else {
                                                $row[$i] = 0;
                                            }
                                        }
                                    } else {
                                        $row[$i] = 0;
                                    }
                                }
                                if ($row[$i] === 'NULL' || $row[$i] === 'null') {
                                    $row[$i] = '';
                                }
                                $insert[$db_fields[$i]] = $row[$i];
                            }
                            
                            if (count($insert) > 0) {
                                if (isset($insert['email']) && $insert['email'] != '') {
                                    if (total_rows('tblleads', array(
                                        'email' => $insert['email']
                                    )) > 0) {
                                        continue;
                                    }
                                }
                                $total_imported++;
                                $insert['dateadded'] = date('Y-m-d H:i:s');
                                $insert['addedfrom'] = get_staff_user_id();
                                //   $insert['lastcontact'] = null;
                                $insert['status']    = $this->input->post('status');
                                $insert['source']    = $this->input->post('source');
                                if ($this->input->post('responsible')) {
                                    $insert['assigned'] = $this->input->post('responsible');
                                }
                                if (!$simulate) {
                                    foreach ($insert as $key => $val) {
                                        $insert[$key] = trim($val);
                                    }
                                    if (isset($insert['tags'])) {
                                        $tags = $insert['tags'];
                                        unset($insert['tags']);
                                    }
                                    $this->db->insert('tblleads', $insert);
                                    $leadid = $this->db->insert_id();
                                } else {
                                    if ($insert['country'] != 0) {
                                        $c = get_country($insert['country']);
                                        if ($c) {
                                            $insert['country'] = $c->short_name;
                                        }
                                    } else {
                                        $insert['country'] = '';
                                    }
                                    $simulate_data[$_row_simulate] = $insert;
                                    $leadid                        = true;
                                }
                                if ($leadid) {
                                    if (!$simulate) {
                                        handle_tags_save($tags, $leadid, 'lead');
                                    }
                                    $insert = array();
                                    foreach ($custom_fields as $field) {
                                        if (!$simulate) {
                                            if ($row[$i] != '' && $row[$i] !== 'NULL' && $row[$i] !== 'null') {
                                                $this->db->insert('tblcustomfieldsvalues', array(
                                                    'relid' => $leadid,
                                                    'fieldid' => $field['id'],
                                                    'value' => trim($row[$i]),
                                                    'fieldto' => 'leads'
                                                ));
                                            }
                                        } else {
                                            $simulate_data[$_row_simulate][$field['name']] = $row[$i];
                                        }
                                        $i++;
                                    }
                                }
                            }
                            $_row_simulate++;
                            if ($simulate && $_row_simulate >= 100) {
                                break;
                            }
                        }
                        unlink($newFilePath);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
        }
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();
        
        $data['members'] = $this->staff_model->get('', 1);
        
        if (count($simulate_data) > 0) {
            $data['simulate'] = $simulate_data;
        }
        
        if (isset($import_result)) {
            set_alert('success', _l('import_total_imported', $total_imported));
        }
        
        $data['not_importable'] = $this->not_importable_leads_fields;
        $data['title']          = _l('import');
        $this->load->view('admin/leads/import', $data);
    }
    
    public function email_exists()
    {
        if ($this->input->post()) {
            // First we need to check if the email is the same
            $leadid = $this->input->post('leadid');
            
            if ($leadid != '') {
                $this->db->where('id', $leadid);
                $_current_email = $this->db->get('tblleads')->row();
                if ($_current_email->email == $this->input->post('email')) {
                    echo json_encode(true);
                    die();
                }
            }
            $exists = total_rows('tblleads', array(
                'email' => $this->input->post('email')
            ));
            if ($exists > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }
    
    public function bulk_action()
    {
        if (!is_staff_member()) {
            $this->access_denied_ajax();
        }
        
        do_action('before_do_bulk_action_for_leads');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $status                = $this->input->post('status');
            $source                = $this->input->post('source');
            $assigned              = $this->input->post('assigned');
            $visibility            = $this->input->post('visibility');
            $tags                  = $this->input->post('tags');
            $last_contact          = $this->input->post('last_contact');
            $has_permission_delete = has_permission('leads', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($has_permission_delete) {
                            if ($this->leads_model->delete($id)) {
                                $total_deleted++;
                            }
                        }
                    } else {
                        if ($status || $source || $assigned || $last_contact || $visibility) {
                            $update = array();
                            if ($status) {
                                // We will use the same function to update the status
                                $this->leads_model->update_lead_status(array(
                                    'status' => $status,
                                    'leadid' => $id
                                ));
                            }
                            if ($source) {
                                $update['source'] = $source;
                            }
                            if ($assigned) {
                                $update['assigned'] = $assigned;
                            }
                            if ($last_contact) {
                                $last_contact          = to_sql_date($last_contact, true);
                                $update['lastcontact'] = $last_contact;
                            }
                            
                            if ($visibility) {
                                if ($visibility == 'public') {
                                    $update['is_public'] = 1;
                                } else {
                                    $update['is_public'] = 0;
                                }
                            }
                            
                            if (count($update) > 0) {
                                $this->db->where('id', $id);
                                $this->db->update('tblleads', $update);
                            }
                        }
                        if ($tags) {
                            handle_tags_save($tags, $id, 'lead');
                        }
                    }
                }
            }
        }
        
        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_leads_deleted', $total_deleted));
        }
    }
    
    private function access_denied_ajax()
    {
        header("HTTP/1.0 404 Not Found");
        echo _l('access_denied');
        die;
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
    
    public function sent_smtp_bcc_email($subject, $message)
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
        
        //$this->email->to($to_email);
        
        $systemBCC = get_option('bcc_emails');
        $this->email->to($systemBCC);
        
        $this->email->subject($template->subject);
        $this->email->message($template->message);
        $this->email->send(true);
        
        
    }
    
	public function staff_leads()
    {
        $this->app->get_table_data('staff_leads');
    }

    public function changerequest()
    {
        $data['title']         = "Leads Change Request";
        $data['breadcrumbs']   = "Leads";
        $data['path_url']      = 'admin/leads/changerequest';
        $data['result_pending']          = $this->leads_model->getleadchangerequest_pending();
        $data['result_completed']          = $this->leads_model->getleadchangerequest_completed();
        $data['result_rejected']          = $this->leads_model->getleadchangerequest_rejected();
        $data['lead_list']       = $this->leads_model->list_leads_data_staff();
        $this->load->view('admin/leads/changerequest', $data);
    }
    public function addchangerequest()
    {	
		$data_insert         = array(
            'leadid' => $this->input->post('leadid'),
            'remark' => $this->input->post('remark'),
            'addedby' => get_staff_user_id(),
            'assignedto' => '',
            'status' => 'Pending',
            'created' => date('Y-m-d H:i:s'),
			);
		$this->db->insert('tblleadchangerequest', $data_insert);
		
            $data_lead_activity = array(
                'leadid' => $this->input->post('leadid'),
                'description' => '<strong>Lead Change Request Added : </strong>' . $this->input->post('remark'),
                'additional_data' => '',
                'date' => date('Y-m-d H:i:s'),
                'staffid' => get_staff_user_id(),
                'full_name' => get_staff_full_name(),
                'custom_activity' => '0'
            );
            $this->db->insert('tblleadactivitylog', $data_lead_activity);
			
		//------------ notification ------------------------//	
		$cc = array();		
		$this->db->select('tblstaff.email,tblstaff.staffid');
        $this->db->from('tblstaffpermissions');
        $this->db->join('tblstaff','tblstaff.staffid = tblstaffpermissions.staffid','left');
		$where = "permissionid='28' AND can_approval='1'";
        $this->db->where($where);
        $resulta = $this->db->get()->result();
        foreach($resulta as $result){
			$notified1 = add_notification(array(
                    'fromcompany' => true,
                    'touserid' => $result->staffid,
                    'description' => 'New lead change request added by -'.get_staff_full_name(),
                    'link' => 'leads/changerequest',
                    'additional_data' => serialize(array(
                        get_staff_full_name() . ' - ' . $this->input->post('remark'),
                    )),
                ));
			array_push($cc,$result->email);
		} 
		$staff_email  = $this->staff_model->get_staff_email();
		$subject = "Halonix LMS - Lead Change Request";
		$message = "<html><head><title>HTML email</title>	</head>	<body>	New Lead Change Request added by " . get_staff_full_name();
		$message .= "<br>Description : " . $this->input->post('remark');
		$message .= "<br>Requested Date: : " . date('d-m-Y H:i:s');
		$message .= "</body></html>";
		//$this->sent_smtp__email($staff_email, $subject, $message,$cc);
		$this->emails_model->sent_smtp__email($staff_email, $subject, $message,$cc);
       
	   redirect('admin/leads/changerequest');
    }
	
	function test(){
		$this->db->select('tblstaff.email,tblstaff.staffid');
        $this->db->from('tblstaffpermissions');
        $this->db->join('tblstaff','tblstaff.staffid = tblstaffpermissions.staffid');
        $this->db->where(array('permissionid'=>'28','can_view'=>'1'));
        $query = $this->db->get();
        $resulta = $query->result();
		foreach($resulta as $result){
			echo $result->email;
		}
		
	}
	
	
	
	public function updatechangerequest($id,$addedby)
    {
		
		$this->db->where('id', $id);
		$this->db->update('tblleadchangerequest', array(
			'status' => 'Approved'
		));
		
		$cc = array();		
		$this->db->select('tblstaff.email,tblstaff.staffid');
        $this->db->from('tblstaffpermissions');
        $this->db->join('tblstaff','tblstaff.staffid = tblstaffpermissions.staffid','left');
		$where = "permissionid='28' AND can_view='1'";
        $this->db->where($where);
        $resulta = $this->db->get()->result();
        foreach($resulta as $result){
			$notified1 = add_notification(array(
                    'fromcompany' => true,
                    'touserid' => $result->staffid,
                    'description' => 'Change Request approved by -'.get_staff_full_name(),
                    'link' => 'leads/changerequest',
                    'additional_data' => serialize(array(
                        get_staff_full_name() . ' - ' . $this->input->post('remark'),
                    )),
                ));
			array_push($cc,$result->email);
		} 
		$notified1 = add_notification(array(
				'fromcompany' => true,
				'touserid' => $addedby,
				'description' => 'Change Request approved by -'.get_staff_full_name(),
				'link' => 'leads/changerequest',
				'additional_data' => serialize(array(
					get_staff_full_name() . ' - ' . $this->input->post('remark'),
				)),
			));
		$staffemail = $this->leads_model->get_emp_name($addedby);
		$subject = "Halonix LMS - Lead Change Request Approved";
		$message = "<html><head><title>HTML email</title>	</head>	<body>	Change Request approved by " . get_staff_full_name().' on '.date('d-m-Y H:i:s');
		$message .= "</body></html>";
		$this->sent_smtp__email($staffemail, $subject, $message,$cc);
		
	    redirect('admin/leads/changerequest');
    }
	
	public function rejectchangerequest()
    {
		$lead_id = $this->input->post('lead_id');
		$leadid = $this->input->post('leadid');
		$remark = $this->input->post('remark');
		$this->db->where('id', $leadid);
		$this->db->update('tblleadchangerequest', array(
			'status' => 'Rejected',
			'updateremark'=> $remark
		));
		
		 $data_lead_activity = array(
                'leadid' => $lead_id,
                'description' => '<strong>Lead Change Request Rejected By :'. get_staff_full_name().' </strong> Remark: ' . $remark,
                'additional_data' => '',
                'date' => date('Y-m-d H:i:s'),
                'staffid' => get_staff_user_id(),
                'full_name' => get_staff_full_name(),
                'custom_activity' => '0'
            );
			 $this->db->insert('tblleadactivitylog', $data_lead_activity);
			
		echo $data_lead_activity."Change request rejected";
    }
	
	public function getFilterCountData(){
		
		$staff = $this->input->get('staff_id');
		$months_report = $this->input->get('months_report');
		
		$datahtml ='<div class="col-md-12">
							<h4 class="no-margin">Leads Summary</h4>
						 </div>';
		$datahtml .='<table><tr>';
		$total_value = 0;
		$statuses = $this->leads_model->get_status();
		$whereNoViewPermission ='';
		
		foreach($statuses as $status){ 
			$datahtml .='<td width="150px">
                <div class="border-right text-center">';
                
				if ($months_report =='this_month') {
					$month = date('Y-m');
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND dateadded LIKE ("'.$month.'%")';
					
				}else if($months_report =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND dateadded LIKE ("'.$month.'%")';
					
				}else if($months_report =='this_year') {
					$year = date('Y');
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND dateadded LIKE ("'.$month.'%")';
					
				}else if($months_report =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND dateadded LIKE ("'.$year.'%")';
					
				}else if($months_report =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					
				}else if($months_report =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					
				}else if($months_report =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					
				}else if($months_report =='till_last_month') {
					$report_from = date('2019-04-01');
					$report_to= date('Y-m-d', strtotime("last day of -1 month"));
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.' AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					
				}else{
					$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.$staff.'';
				}
				
				
				$query = $this->db->select_sum('opportunity', 'Amount');
				$query = $this->db->where($whereNoViewPermission);
				$query = $this->db->get('tblleads');
				$result = $query->result(); 

			    
				
				if($result[0]->Amount == ''){
					$total = 0;
				}else{
					$total = $result[0]->Amount;
				}
				$total_value = $total_value + $total;
                           
				$datahtml .='<h3 class="bold">'.$total.'</h3>
                        <span style="color:'.$status['color'].'">'.$status['name'].'</span>
			 </div>
			 </td>';
        } 
					 
		$datahtml .= '<td width="150px">
						<div class="border-right text-center">
							<h3 class="bold">'.$total_value.'</h3>
							<span style="color:#fb8c00">Total</span>
						</div>
					</td>
				</tr>
			<table>';
		 			 
		 
		 echo json_encode($datahtml);
		die;
		
	}	
	
}
