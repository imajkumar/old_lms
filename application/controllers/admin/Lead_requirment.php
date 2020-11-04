<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lead_requirment extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('estimates_model');
        $this->load->helper('file');
        $this->load->model('emails_model');
		$this->load->model('leads_model');
    }
    
    /* Get all estimates in case user go on index page */
    public function index($id = '')
    {
        $this->list_estimates($id);
    }
	
 /*  public function check_deletion($_GET['status'],$_GET['id']){
 if(isset($_GET['status']) && $_GET['id'])
{
	
$data=	$this->leads_model->update_user_comment($_GET['status'],$_GET['id']);

$obj_common->redirect(‘user_comments.php’);

}

  } */
    
    /* List all estimates datatables */
    public function list_estimates($id = '')
    {
        
        $data['title']     = _l('Lead Requirement');
        $data['bodyclass'] = 'estimates_total_manual';
        $data['leads']     = $this->estimates_model->requirement_data();
        
        $this->load->view('admin/lead_requirment/manage', $data);
        
    }
    public function lead_requirnment_file($id = '')
    {
        
        $data['title']     = _l('Lead Requirement Document');
        $data['bodyclass'] = 'estimates_total_manual';
        
        if ($this->input->post()) {
            
            $doc_id       = $this->input->post('doc_id');
            $first_title  = $this->input->post('first_title');
            $total_record = sizeof($doc_id);
		    $total_submitted = 0;
            for ($i = 1; $i <= $total_record; $i++) {
                if (isset($_FILES["first_doc_" . $i]) && !empty($_FILES['first_doc_' . $i]['name'])) {
					
                    $uploaddir = './uploads/lead_documents/' . $this->input->post('lead_id') . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
					//$this->input->post('lead_id');
                    $fileInfo = pathinfo($_FILES["first_doc_" . $i]["name"]);
                    $img_name = $uploaddir . basename($_FILES['first_doc_' . $i]['name']);
                   
                    move_uploaded_file($_FILES["first_doc_" . $i]["tmp_name"], $img_name);
                    
                    $data_img = array(
                        'document' => basename($_FILES['first_doc_' . $i]['name'])
                    );
                    $doc_id[$i-1];
                    $this->db->where('id', $doc_id[$i-1]);
                    $this->db->update('tbl_lead_required_doc', $data_img);
                    
                    $doc_name = $first_title[$i-1];
                    
                    $data_lead_activity = array(
                        'leadid' => $this->input->post('lead_id'),
                        'description' => '<a href="#lead_requirment"  aria-controls="lead_requirment" role="tab" data-toggle="tab" class="leadreq">Document Uploaded: ' . $doc_name . '</a>',
                        'additional_data' => $doc_name,
                        'date' => date('Y-m-d H:i:s'),
                        'staffid' => get_staff_user_id(),
                        'full_name' => get_staff_full_name(),
                        'custom_activity' => '0'
                    );
                    $this->db->insert('tblleadactivitylog', $data_lead_activity);
                    $total_submitted = $total_submitted + 1;
                }
                
            }
			
            $total_doc_submitted = $this->input->post('total_doc_id');
			
            
			$this->db->where('id', $this->input->post('lead_id'));
			$description = $this->db->get('tblleads')->row()->description;
			
			$this->db->where('id', $this->input->post('lead_id'));
			$staff_id = $this->db->get('tblleads')->row()->assigned;
			
			$this->db->where('staffid', $staff_id);
			$staff_email = $this->db->get('tblstaff')->row()->email; 
			
			if($total_submitted == $total_doc_submitted){
				$data_lead_approval = array(
					'assproject_manager_approval' => '1',
					'dateassigned' => date('Y-m-d H:i:s'),
				);
				$additional_data = "<br><br><strong> Details: </strong>";
				$additional_data .= "<br><strong>Technical Approval : Approved</strong>";
				$additional_data .= "<strong><br/>Description : </strong>" . $description;
				$this->sent_smtp__email($staff_email, 'Lead has been approved', $additional_data); 
			}else{
				$data_lead_approval = array(
					'assproject_manager_approval' => '2',
					'dateassigned' => date('Y-m-d H:i:s'),
				);
				$additional_data = "<br><br><strong> Details: </strong>";
				$additional_data .= "<br><strong>Technical Approval :Partial Approved</strong>";
				$additional_data .= "<strong><br/>Description : </strong>" . $description;
				$this->sent_smtp__email($staff_email, 'Lead has been partial approved', $additional_data);
			}
			
			$this->db->where('id', $this->input->post('lead_id'));
            $this->db->update('tblleads', $data_lead_approval);
            
            
            redirect('admin/leads');
            
        } else {
           if($id == ''){
				redirect('admin/leads');
			}else{
				$sql   = "SELECT * FROM tbl_lead_required_doc where lead_id='" . $id . "' AND document ='none' ";
				$query = $this->db->query($sql);
				
				$data['lead_doc_data'] = $query->result_array();
				
				$this->load->view('admin/lead_requirment/lead_requirnment_file', $data);
			}
        }
        
    }
	public function update_requirment_status(){

	$data['list_status']=$this->leads_model->list_leads_data_region($id);

    $lead_id = $this->input->post('lead_id'); 
    $status = $this->input->post('status'); 
	$reason = $this->input->post('reason');
    $item_id= $this->input->post('item_id');
    $category_id= $this->input->post('category_id');
    $wattage= $this->input->post('wattage');
    $item_name= $this->input->post('item_name');
	
	$this->leads_model->update_lead_requirment_status($item_id,$status,$reason);
	
	if($status =='1')
	{
		$data = array(
			'document'=>'Item Inactive',
		);
	  $this->db->where(array('lead_id' => $lead_id, 'category_id' => $category_id, 'wattage' => $wattage, 'title' => $item_name));
	  $this->db->update('tbl_lead_required_doc',$data);
	  
	  //====== check item =======// 
	  
	  $query = $this->db->query("SELECT id FROM tbl_lead_required_doc where lead_id='".$lead_id."' and document= 'none'");
					
		if($query->num_rows() == 0){
			$data_lead_approval = array(
				'assproject_manager_approval' => '1'
			);
			$this->db->where('id', $this->input->post('lead_id'));
			$this->db->update('tblleads', $data_lead_approval);
        
		}
		
		    
	  echo $lead_id.':'.$wattage.':'.$category_id;
	  
		
	}
		redirect('admin/lead_requirment/lead_requirment_status/'.$lead_id );
	}
	
	
    public function lead_requirment_status($id)
    {
		$this->load->model('invoice_items_model');
        $data['leads'] = $this->leads_model->get($id);
	
	
        $data['items_groups'] = $this->invoice_items_model->get_groups();
		
		 $this->load->view('admin/leads/item_requirment',$data);
		
		
		
	}  
	public function item_lead($id)
    {
		$this->load->model('invoice_items_model');
        $data['leads'] = $this->leads_model->get($id);
	
        $data['items_groups'] = $this->invoice_items_model->get_groups();
		
		 $this->load->view('admin/leads/item_lead',$data);
		
		
		
	}
    public function lead_carry_forward_data()
    {
        
        
        $button = $this->input->post('search');
        
        if (isset($button)) {
            $from_months         = $this->input->post('from-months');
            $to_months         = $this->input->post('to-months');
            $from_years          = $this->input->post('from-years');
            $to_years          = $this->input->post('to-years');
            $view_assigned = $this->input->post('view_assigned');
            
			
            $resultlist  = $this->leads_model->lead_carry_won_data_show($from_months,$from_years,$to_months, $to_years,$view_assigned);
			
            $data['resultlist'] = $resultlist;
         
            
            $this->load->view('admin/lead_requirment/lead_carry_data', $data);
        }else {
			$resultlist         = $this->leads_model->lead_carry_won_data_show($from_months,$to_months, $from_years,$to_years,$view_assigned);
            $data['resultlist'] = $resultlist;
            $this->load->view('admin/lead_requirment/lead_carry_data', $data);
        }
        
    }
    public function change_item_status()
    {
        $active_inactive = $this->input->post('active_inactive');
        $req_data = array(
                    'status' => $active_inactive,                    
                );
                
		$this->db->where('id', $item_id);
		$this->db->update('tbllead_requirment_detail', $req_data);          
    }
    
    
    public function lead_carry_forward()
    {
        $data['lead_carry'] = $this->leads_model->lead_won_carry();

        if ($this->input->post()) {
            
            $lead_id        = $this->input->post('lead_id');
            $item_id        = $this->input->post('doc_id');
            $close_won      = $this->input->post('close_won');
            $month          = $this->input->post('month');
            $year           = $this->input->post('year');
            $last_executed  = $this->input->post('last_executed');
            $executed       = $this->input->post('executed');
            $carry_total    = $this->input->post('carry_total');
            $customer_group = $this->input->post('customer_group');
            $customer_name  = $this->input->post('customer_name');
            $won_date       = $this->input->post('won_date');
			
			
            $total_record = sizeof($item_id);
            
            for ($i = 0; $i < $total_record; $i++) {
				
				$wonmonth = date('Y-m', strtotime($won_date[$i])).'<br>';
				$nmonthyear = $year.'-'.date("m", strtotime($month));
				
				/* if($wonmonth <= $nmonthyear){ */
					
					$req_data = array(
						'id' => $item_id[$i],
						'month' => $month,
						'year' => $year,
						'close_won' => $close_won[$i],
						'last_executed' => $last_executed[$i] + $executed[$i],
						'executed' => $executed[$i],
						'carry_forward' => $carry_total[$i], 
					);
					
					$this->db->where('id', $item_id[$i]);
					$this->db->update('tbl_carry_leadwon', $req_data);
					$req_data1 = array(
						'lead_id' => $lead_id[$i],
						'staff_id' => get_staff_user_id(),
						'month' => $month,
						'year' => $year,
						'carry_forward_date' => $year.'-'.date("m", strtotime($month)),
						'won_amount' => $close_won[$i],
						'executed_amount' => $executed[$i],
						'customer_group' => $customer_group[$i],
						'customer_name' => $customer_name[$i],
						'won_date' => $won_date[$i],
						'created_date' => date('Y-m-d H:i:s'),
						
					);
				
					$this->db->insert('tbl_carry_leadwon_report', $req_data1);
                /* } */
            }
            
			redirect('admin/lead_requirment/lead_carry_forward');
        } else {
            $data['title']      = 'Lead Carry Forward';
            $data['bodyclass']  = 'estimates_total_manual';
            $data['lead_carry'] = $this->leads_model->lead_won_carry();
            
            
            $this->load->view('admin/lead_requirment/lead_carry', $data);
        }
    }
    
    
    public function list_view($id)
    {
        
        
        $this->load->model('estimates_model');
        $this->load->model('invoice_items_model');
        $data['lead_requirment_id'] = $id;
        
        
        $data['leads_view'] = $this->estimates_model->list_requirment_data($id);
        
        $data['leads_view_detail_data'] = $this->estimates_model->list_requirment_detail($id);
        $data['detail_data']            = $this->estimates_model->requirement_data_lead($id);
        
        
        
        $data['items_groups']  = $this->invoice_items_model->list_category($id);
        $data['items_groupsd'] = $this->invoice_items_model->get_groups();
        
        $this->load->view('admin/lead_requirment/lead_requirment_view', $data);
        
    }
    public function table($clientid = '')
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('estimates', array(
            'clientid' => $clientid
        ));
    }
    public function getStaff($id = '')
    {
        $this->db->where('staffid', $id);
        return $this->db->get('tblstaff')->row('firstname');
    }
    
    /* Add new estimate or update existing */
    public function add_lead_requirement_data($id = '',$leadstatus='')
    {
        if ($this->input->post()) {
            
			
			$project_manager_data = array(
                    'project_manager_approval' => '0',
                    'assproject_manager_approval' => '0',
                    'dateassigned' => date('Y-m-d H:s'),
                );
                
			$this->db->where('id', $this->input->post('client_id'));
			$this->db->update('tblleads', $project_manager_data);
			
			if($this->input->post('lead_status')){
				$lead_status = array(
					'status' => $this->input->post('lead_status'),
				);					
				$this->db->where('id', $this->input->post('client_id'));
				$this->db->update('tblleads', $lead_status);
			}	
			
            $estimate_data = array(
                'lead_id' => $this->input->post('client_id'),
                'added_on' => date('Y-m-d H:s'),
                'added_by' => $this->input->post('lead_owner'),
                'remark' => $this->input->post('remark')
                
            );
			$document_due_date = array(
                'document_due_date' => $this->input->post('document_due_date'),
           
            );
            $this->db->where('id', $this->input->post('client_id'));
            $this->db->update('tblleads', $document_due_date);
				
				
            $this->db->insert('tbllead_requirment', $estimate_data);
            $insert_id = $this->db->insert_id();
            
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
                'lead_id' => $this->input->post('client_id'),
                'category_id' => $item_cat[$i],
                'title' => $item_detail,'wattage' => $subcategory[$i],'wattage_title' => $title,
                );
                
                $this->db->insert('tbl_lead_required_doc', $tbl_lead_required_doc);    
                } 
                
                $req_data = array(
                    'lead_requirment_id' => $this->input->post('client_id'),
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
                'leadid' => $this->input->post('client_id'),
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
           
            /* $this->db->where('id', $this->input->post('client_id'));
            $state = $this->db->get('tblleads')->row()->state;
            
            $emailIDS = $this->leads_model->getEmailID('email','tblstaff','state', $state);
           
            foreach($emailIDS as $emails) {
				$this->sent_smtp__email($emails['email'], $subject, $message);
            }
			$this->sent_smtp_bcc_email($subject, $message);
			 */
			
             }
            
            
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = $this->input->post('first_title');
                $img_name    = $uploaddir . basename($_FILES['first_doc']['name']);
                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);
                
                $data_img = array(
                    'lead_id' => $this->input->post('client_id'),
                    'title' => $first_title,
                    'doc' => basename($_FILES['first_doc']['name'])
                );
                
                $this->db->insert('tbllead_requirment_file', $data_img);
            }
            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo     = pathinfo($_FILES["second_doc"]["name"]);
                $second_title = $this->input->post('second_title');
                $img_name     = $uploaddir . basename($_FILES['second_doc']['name']);
                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);
                $data_img = array(
                    'lead_id' => $this->input->post('client_id'),
                    'title' => $second_title,
                    'doc' => basename($_FILES['second_doc']['name'])
                );
                $this->db->insert('tbllead_requirment_file', $data_img);
            }
            
            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo    = pathinfo($_FILES["third_doc"]["name"]);
                $third_title = $this->input->post('third_title');
                $img_name    = $uploaddir . basename($_FILES['third_doc']['name']);
                move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);
                $data_img = array(
                    'lead_id' => $this->input->post('client_id'),
                    'title' => $third_title,
                    'doc' => basename($_FILES['third_doc']['name'])
                );
                $this->db->insert('tbllead_requirment_file', $data_img);
            }
            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);
                $fourth_title = $this->input->post('fourth_title');
                $img_name     = $uploaddir . basename($_FILES['fourth_doc']['name']);
                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);
                $data_img = array(
                    'lead_id' => $this->input->post('client_id'),
                    'title' => $fourth_title,
                    'doc' => basename($_FILES['fourth_doc']['name'])
                );
                $this->db->insert('tbllead_requirment_file', $data_img);
            }
            if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo    = pathinfo($_FILES["fifth_doc"]["name"]);
                $fifth_title = $this->input->post('fifth_title');
                $img_name    = $uploaddir . basename($_FILES['fifth_doc']['name']);
                move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);
                $data_img = array(
                    'lead_id' => $this->input->post('client_id'),
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
			
			$this->db->where('id', $this->input->post('client_id'));
            $state = $this->db->get('tblleads')->row()->state;
            
            $emailIDSPM = $this->staff_model->getEmailIDPM('email','tblstaff','state', $state);
           
            foreach($emailIDSPM as $emails) {
				$this->sent_smtp__email($emails['email'], $subject, $message);
            }
			
			$getEmailIDASM = $this->staff_model->getEmailIDASM('email','tblstaff','state', $state);
         
            foreach($getEmailIDASM as $emails) {
				$this->sent_smtp__email($emails['email'], $subject, $message);
            }
			
			//$this->sent_smtp_bcc_email($subject, $message);
			
           
            
            redirect(admin_url('leads'));
        }
        
    }
    
    
    public function add_lead_document_data($id = '')

    {

        if ($this->input->post()) {



            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';

                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {

                    die("Error creating folder $uploaddir");

                }

                $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);

                $first_title = $this->input->post('first_title');

                $img_name    = $uploaddir . basename($_FILES['first_doc']['name']);

                move_uploaded_file($_FILES["first_doc"]["tmp_name"], $img_name);

                

                $data_img = array(

                    'lead_id' => $this->input->post('client_id'),

                    'title' => $first_title,

                    'doc' => basename($_FILES['first_doc']['name'])

                );

                

                $this->db->insert('tbllead_requirment_file', $data_img);

            }

            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {

                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';

                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {

                    die("Error creating folder $uploaddir");

                }

                $fileInfo     = pathinfo($_FILES["second_doc"]["name"]);

                $second_title = $this->input->post('second_title');

                $img_name     = $uploaddir . basename($_FILES['second_doc']['name']);

                move_uploaded_file($_FILES["second_doc"]["tmp_name"], $img_name);

                $data_img = array(

                    'lead_id' => $this->input->post('client_id'),

                    'title' => $second_title,

                    'doc' => basename($_FILES['second_doc']['name'])

                );

                $this->db->insert('tbllead_requirment_file', $data_img);

            }

            

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';

                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {

                    die("Error creating folder $uploaddir");

                }

                $fileInfo    = pathinfo($_FILES["third_doc"]["name"]);

                $third_title = $this->input->post('third_title');

                $img_name    = $uploaddir . basename($_FILES['third_doc']['name']);

                move_uploaded_file($_FILES["third_doc"]["tmp_name"], $img_name);

                $data_img = array(

                    'lead_id' => $this->input->post('client_id'),

                    'title' => $third_title,

                    'doc' => basename($_FILES['third_doc']['name'])

                );

                $this->db->insert('tbllead_requirment_file', $data_img);

            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {

                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';

                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {

                    die("Error creating folder $uploaddir");

                }

                $fileInfo     = pathinfo($_FILES["fourth_doc"]["name"]);

                $fourth_title = $this->input->post('fourth_title');

                $img_name     = $uploaddir . basename($_FILES['fourth_doc']['name']);

                move_uploaded_file($_FILES["fourth_doc"]["tmp_name"], $img_name);

                $data_img = array(

                    'lead_id' => $this->input->post('client_id'),

                    'title' => $fourth_title,

                    'doc' => basename($_FILES['fourth_doc']['name'])

                );

                $this->db->insert('tbllead_requirment_file', $data_img);

            }

            if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {

                $uploaddir = './uploads/lead_documents/' . $this->input->post('client_id') . '/';

                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {

                    die("Error creating folder $uploaddir");

                }

                $fileInfo    = pathinfo($_FILES["fifth_doc"]["name"]);

                $fifth_title = $this->input->post('fifth_title');

                $img_name    = $uploaddir . basename($_FILES['fifth_doc']['name']);

                move_uploaded_file($_FILES["fifth_doc"]["tmp_name"], $img_name);

                $data_img = array(

                    'lead_id' => $this->input->post('client_id'),

                    'title' => $fifth_title,

                    'doc' => basename($_FILES['fifth_doc']['name'])

                );

                $this->db->insert('tbllead_requirment_file', $data_img);

            }

            

            //----------------- Mail --------------------------//

			

			$subject = "Halonix LMS - Project Related Document Uploaded";

            $message = "<html><head>   <title>HTML email</title>    </head>    <body> Project Related Documents
           added by ".get_staff_full_name();

            

            $message .= "</br></br><b>New Project Related Documents Added Please Check.</b>";

            $message .= "</body></html>";

			

			$this->db->where('id', $this->input->post('client_id'));

            $state = $this->db->get('tblleads')->row()->state;

            

            $emailIDSPM = $this->staff_model->getEmailIDPM('email','tblstaff','state', $state);

           

            foreach($emailIDSPM as $emails) {

				$this->sent_smtp__email($emails['email'], $subject, $message);

            }

			

			$getEmailIDASM = $this->staff_model->getEmailIDASM('email','tblstaff','state', $state);

         

            foreach($getEmailIDASM as $emails) {

				$this->sent_smtp__email($emails['email'], $subject, $message);

            }

            redirect(admin_url('leads'));

        }

        

    }

   
   
    public function document_required()
    {
        
        $data['customer'] = $this->leads_model->document_required_value();
        
        $this->load->view('admin/lead_requirment/manage_document_required', $data);
    }
    public function document_required_add()
    {
        
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
                        set_alert('success', _l('added_successfully', _l('document_required')));
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
                    set_alert('success', _l('updated_successfully', _l('document_required')));
                }
            }
        }
    }
    
    public function projectlead()
    {
        
        $this->load->model('invoice_items_model');
        $data['Leads']        = $this->leads_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $this->load->view('admin/leads/lead', $data);
        
    }
    
    
    
    
    
    public function edit_lead_requirement_data($id)
    {
        
        
        $this->load->model('invoice_items_model');
        $data['Leads']        = $this->leads_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['leads_list']   = $this->estimates_model->list_requirment_data($id);
        $data['leads_data']   = $this->estimates_model->requirement_data_lead($id);
        
        
        $data['ajaxItems'] = false;
        if (total_rows('tblitems') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped();
        } else {
            $data['items']     = array();
            $data['ajaxItems'] = true;
        }
        if (!is_staff_member()) {
            access_denied('Leads Requirement');
        }
        
        $data['switch_kanban'] = true;
        
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            
        }
        
        $data['id'] = $id;
        
        
        if ($this->input->post()) {
            
            $data = array(
                'id' => $id,
                'lead_id' => $this->input->post('client_id'),
                'added_on' => date('Y-m-d H:s'),
                'added_by' => $this->input->post('lead_owner'),
                'remark' => $this->input->post('remark'),
                'status' => '0',
                'title1' => $this->input->post('first_title'),
                'title2' => $this->input->post('second_title'),
                'title3' => $this->input->post('third_title'),
                'title4' => $this->input->post('fourth_title'),
                'title5' => $this->input->post('fifth_title')
                
            );
            
            
            $this->db->update('tbllead_requirment', $data, $id);
            $insert_id = $this->db->insert_id();
            
            $item_cat      = $this->input->post('item_cat');
            $item          = $this->input->post('item');
            $quantity      = $this->input->post('quantity');
            $rate          = $this->input->post('rate');
            $document      = $this->input->post('document');
            $proposed_item = $this->input->post('proposed_item');
            $proposed_rate = $this->input->post('proposed_rate');
            
            $total_record = sizeof($item_cat);
            
            for ($i = 0; $i < $total_record; $i++) {
                
                $req_data = array(
                    'lead_requirment_id' => $insert_id,
                    'category_id' => $item_cat[$i],
                    'item_id' => $item[$i],
                    'quantity' => $quantity[$i],
                    'rate' => $rate[$i],
                    'document' => $document[$i],
                    'proposed_item_id' => $proposed_item[$i],
                    'proposed_item_qty' => $proposed_rate[$i]
                    
                );
                
                
                $this->db->update('tbllead_requirment_detail', $req_data);
            }
            
            
            redirect(admin_url('lead_requirment/'));
        }
        
        $this->load->view('admin/lead_requirment/edit_lead_requirment', $data);
        
        
    }
    
    /* public function update_proposed_item()
    {
        
        $lead_id = $this->input->post('lead_id');
        $item_id = $this->input->post('tblitem_id');
        
        $proposed_item    = $this->input->post('item_proposed');
        $proposed_rate    = $this->input->post('proposed_price');
        $item_description = $this->input->post('item_description');
        
        $total_record = sizeof($item_id);
        
        for ($i = 0; $i < $total_record; $i++) {
            $req_data = array(
                'id' => $item_id[$i],
                'proposed_item_id' => $proposed_item[$i],
                'proposed_item_qty' => $proposed_rate[$i],
                'item_description' => $item_description[$i]
                
            );
            
            $this->db->where('id', $item_id[$i]);
            $this->db->update('tbllead_requirment_detail', $req_data);
            
        }
        
        $this->db->where('id', $lead_id);
        $data_lead_approval = array(
            'project_manager_approval' => '1'
        );
        $this->db->update('tblleads', $data_lead_approval);
        
        $data_lead_activity = array(
            'leadid' => $lead_id,
            'description' => 'Item requirement updated',
            'additional_data' => $req_data,
            'date' => date('Y-m-d H:i:s'),
            'staffid' => get_staff_user_id(),
            'full_name' => get_staff_full_name(),
            'custom_activity' => '0'
        );
        
        $this->db->insert('tblleadactivitylog', $data_lead_activity);
        
        redirect(admin_url('leads'));
        
    }
     */
    
	public function update_proposed_item()
    {
		$lead_id = $this->input->post('lead_id');
		 $data_lead_activity = array(
			'leadid' => $lead_id,
			'description' => 'Item requirement updated',
			'additional_data' => '',
			'date' => date('Y-m-d H:i:s'),
			'staffid' => get_staff_user_id(),
			'full_name' => get_staff_full_name(),
			'custom_activity' => '0',
		);
		$this->db->insert('tblleadactivitylog', $data_lead_activity); 
					
        $is_approved = $this->input->post('is_approved');
       
	    $approved = 0;
	    $item_id = $this->input->post('tblitem_id');
		
		$proposed_item_text    = $this->input->post('item_proposed_text');
		$proposed_item    = $this->input->post('item_proposed');
		
		$proposed_rate    = $this->input->post('proposed_price');
        $item_description = $this->input->post('item_description');
		
        $total_record_lead = sizeof($item_id);
        $total_record_selected = sizeof($is_approved);
     
		for ($i = 0; $i <= $total_record_lead; $i++) {			
			for ($j = 0; $j <= $total_record_selected; $j++) {			
			   
			   if($item_id[$i] == $is_approved[$j]){
					if(get_staff_role() == 4) 
					{
						$proposeditem ='';
				       if($proposed_item_text[$i] != '' && substr($proposed_item[$i], 0, 3) == 'new'){
						   $proposeditem = $proposed_item_text[$i];
					   }else{
						   $proposeditem = $proposed_item[$i];
					   }
					   $req_data = array(
							'id' => $item_id[$i],
							'proposed_item_id' => $proposeditem,
							'is_approved' => 1,
							'proposed_item_qty' => $proposed_rate[$i],
							'item_description' => $item_description[$i]
						);
					}else{
						 $proposeditem ='';
						 if($proposed_item_text[$i] != '' && substr($proposed_item[$i], 0, 3) == 'new'){
							   $proposeditem = $proposed_item_text[$i];
						   }else{
							   $proposeditem = $proposed_item[$i];
						   }
						$req_data = array(
							'id' => $item_id[$i],
							'proposed_item_id' => $proposeditem,
							'is_approved' => 0,
							'proposed_item_qty' => $proposed_rate[$i],
							'item_description' => $item_description[$i]
						);
					}
					$this->db->where('id', $item_id[$i]);
					$this->db->update('tbllead_requirment_detail', $req_data);
					
					
			   }
		   
			}
        }
      
	   
        $this->db->select("is_approved");
		$this->db->from("tbllead_requirment_detail");
		$this->db->where('lead_requirment_id',$lead_id);
		$query = $this->db->get();
       
		$array_check = array();
		foreach($query->result_array() as $result){
			array_push($array_check,$result['is_approved']);
		}
		
		if (in_array("0", $array_check)){ 
		  $approved = 0;
		} else { 
		  $approved = 1; 
		} 
		
		if(get_staff_role() == 4) 
		{
			if($approved == 0){
				$this->db->where('id', $lead_id);
				$data_lead_approval = array(
					'project_manager_approval' => '2',
					'dateassigned' => date('Y-m-d H:i:s'),
				);
				$this->db->update('tblleads', $data_lead_approval);
			}else{
				$this->db->where('id', $lead_id);
				$data_lead_approval = array(
					'project_manager_approval' => '1',
					'dateassigned' => date('Y-m-d H:i:s'),
				);
				$this->db->update('tblleads', $data_lead_approval);
			} 
		}
		if(get_staff_role() == 7) 
		{
			redirect(admin_url('leads/index/'.$lead_id));
        }else{
			redirect(admin_url('leads'));
		}
    }
	
    /* Add new estimate or update existing */
    public function add_lead_requirement($id = '')
    {
        if ($this->input->post()) {
            $estimate_data = $this->input->post();
            if ($id == '') {
                if (!has_permission('leads', '', 'create')) {
                    access_denied('estimates');
                }
                $id = $this->estimates_model->add($estimate_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('estimate')));
                    if ($this->set_estimate_pipeline_autoload($id)) {
                        redirect(admin_url('lead_requirment/list_estimates/'));
                    } else {
                        redirect(admin_url('lead_requirment/' . $id));
                    }
                }
            } else {
                if (!has_permission('estimates', '', 'edit')) {
                    access_denied('estimates');
                }
                $success = $this->estimates_model->update($estimate_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('estimate')));
                }
                if ($this->set_estimate_pipeline_autoload($id)) {
                    redirect(admin_url('lead_requirment/list_estimates/'));
                } else {
                    redirect(admin_url('lead_requirment/list_estimates/' . $id));
                }
            }
        }
        if ($id == '') {
            $title = _l('create_new_estimate');
        } else {
            $estimate = $this->estimates_model->get($id);
            
            if (!$estimate || (!has_permission('estimates', '', 'view') && $estimate->addedfrom != get_staff_user_id())) {
                blank_page(_l('estimate_not_found'));
            }
            $data['estimate'] = $estimate;
            $data['edit']     = true;
            $title            = _l('edit', _l('estimate_lowercase'));
        }
        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }
        $this->load->model('taxes_model');
        
        
        $data['taxes'] = $this->taxes_model->get();
        
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $this->load->model('invoice_items_model');
        
        $data['ajaxItems'] = false;
        if (total_rows('tblitems') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped();
        } else {
            $data['items']     = array();
            $data['ajaxItems'] = true;
        }
        
        $data['items_groups']        = $this->invoice_items_model->get_groups();
        $data['get_sub_groups']      = $this->invoice_items_model->get_sub_groups();
        $data['get_groups_document'] = $this->invoice_items_model->get_groups_document();
        
        
        $data['staff'] = $this->staff_model->get('', 1);
        $data['Leads'] = $this->leads_model->get_lead();
        
        $data['title'] = $title;
        $this->load->view('admin/lead_requirment/add_lead_requirement', $data);
        
    }
     public function add_lead_document($id = '')
	 {

        $this->load->view('admin/lead_requirment/add_lead_document', $data);

        

     }
    public function getItemBysubCatID()
    {
        $item_cat = $this->input->get('item_cat');
        
        if ($item_cat != '0'){
			$this->db->group_by('name');
			$where = array(
						'group_id' => $item_cat,
						'sub_group_status' => '0',
						);
            $data = $this->db->where($where)->get('tblitems_sub_groups')->result_array();
        }else{
			$where = array(
						'sub_group_status' => '0',
						);
			$this->db->group_by('name');
            $data = $this->db->where($where)->get('tblitems_sub_groups')->result_array();
        }
        echo json_encode($data);
    }
    
    public function getItemByCatID()
    {
        $item_cat = $this->input->get('subgroup_id');
        
        if ($item_cat != '0'){
			$condtn = array('subgroup_id'=> $item_cat, 'status' => '0');
			$this->db->group_by('description');
            $data = $this->db->where($condtn)->get('tblitems')->result_array();
        }else{
			$condtn = array('status' => '0');
			
			$this->db->group_by('description');
			
            $data = $this->db->where($condtn)->get('tblitems')->result_array();
        }
        echo json_encode($data);
    }
    
    
    public function getItemByCateID($item_cat)
    {
        $condtn = array('group_id' => $item_cat, 'status' => '0');
        $data = $this->db->where($condtn)->get('tblitems')->result_array();
        return $data;
    }
    
    
    /* Add new estimate or update existing */
    public function estimate($id = '')
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            access_denied('estimates');
        }
        
        if ($this->input->post()) {
            $estimate_data = $this->input->post();
            if ($id == '') {
                if (!has_permission('estimates', '', 'create')) {
                    access_denied('estimates');
                }
                $id = $this->estimates_model->add($estimate_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('estimate')));
                    if ($this->set_estimate_pipeline_autoload($id)) {
                        redirect(admin_url('lead_requirment/list_estimates/'));
                    } else {
                        redirect(admin_url('lead_requirment/' . $id));
                    }
                }
            } else {
                if (!has_permission('estimates', '', 'edit')) {
                    access_denied('estimates');
                }
                $success = $this->estimates_model->update($estimate_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('estimate')));
                }
                if ($this->set_estimate_pipeline_autoload($id)) {
                    redirect(admin_url('lead_requirment/list_estimates/'));
                } else {
                    redirect(admin_url('lead_requirment/list_estimates/' . $id));
                }
            }
        }
        if ($id == '') {
            $title = _l('create_new_estimate');
        } else {
            $estimate = $this->estimates_model->get($id);
            
            if (!$estimate || (!has_permission('estimates', '', 'view') && $estimate->addedfrom != get_staff_user_id())) {
                blank_page(_l('estimate_not_found'));
            }
            $data['estimate'] = $estimate;
            $data['edit']     = true;
            $title            = _l('edit', _l('estimate_lowercase'));
        }
        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }
        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        
        $this->load->model('invoice_items_model');
        
        $data['ajaxItems'] = false;
        if (total_rows('tblitems') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped();
        } else {
            $data['items']     = array();
            $data['ajaxItems'] = true;
        }
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        
        $data['staff']             = $this->staff_model->get('', 1);
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();
        $data['title']             = $title;
        $this->load->view('admin/lead_requirment/estimate', $data);
    }
    
    public function update_number_settings($id)
    {
        $response = array(
            'success' => false,
            'message' => ''
        );
        if (has_permission('estimates', '', 'edit')) {
            $this->db->where('id', $id);
            $this->db->update('tblestimates', array(
                'prefix' => $this->input->post('prefix')
            ));
            if ($this->db->affected_rows() > 0) {
                $response['success'] = true;
                $response['message'] = _l('updated_successfully', _l('estimate'));
            }
        }
        
        echo json_encode($response);
        die;
    }
    
    public function validate_estimate_number()
    {
        $isedit          = $this->input->post('isedit');
        $number          = $this->input->post('number');
        $date            = $this->input->post('date');
        $original_number = $this->input->post('original_number');
        $number          = trim($number);
        $number          = ltrim($number, '0');
        
        if ($isedit == 'true') {
            if ($number == $original_number) {
                echo json_encode(true);
                die;
            }
        }
        
        if (total_rows('tblestimates', array(
            'YEAR(date)' => date('Y', strtotime(to_sql_date($date))),
            'number' => $number
        )) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    
    public function delete_attachment($id)
    {
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo $this->estimates_model->delete_attachment($id);
        } else {
            header('HTTP/1.0 400 Bad error');
            echo _l('access_denied');
            die;
        }
    }
    
    /* Get all estimate data used when user click on estimate number in a datatable left side*/
    public function get_estimate_data_ajax($id, $to_return = false)
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            echo _l('access_denied');
            die;
        }
        if (!$id) {
            die('No estimate found');
        }
        $estimate = $this->estimates_model->get($id);
        if (!$estimate || (!has_permission('estimates', '', 'view') && $estimate->addedfrom != get_staff_user_id())) {
            echo _l('estimate_not_found');
            die;
        }
        
        $estimate->date       = _d($estimate->date);
        $estimate->expirydate = _d($estimate->expirydate);
        if ($estimate->invoiceid !== null) {
            $this->load->model('invoices_model');
            $estimate->invoice = $this->invoices_model->get($estimate->invoiceid);
        }
        
        if ($estimate->sent == 0) {
            $template_name = 'estimate-send-to-client';
        } else {
            $template_name = 'estimate-already-send';
        }
        
        $contact = $this->clients_model->get_contact(get_primary_contact_user_id($estimate->clientid));
        $email   = '';
        if ($contact) {
            $email = $contact->email;
        }
        
        $data['template']      = get_email_template_for_sending($template_name, $email);
        $data['template_name'] = $template_name;
        
        $this->db->where('slug', $template_name);
        $this->db->where('language', 'english');
        $template_result = $this->db->get('tblemailtemplates')->row();
        
        $data['template_system_name'] = $template_result->name;
        $data['template_id']          = $template_result->emailtemplateid;
        
        $data['template_disabled'] = false;
        if (total_rows('tblemailtemplates', array(
            'slug' => $data['template_name'],
            'active' => 0
        )) > 0) {
            $data['template_disabled'] = true;
        }
        
        $data['activity']          = $this->estimates_model->get_estimate_activity($id);
        $data['estimate']          = $estimate;
        $data['members']           = $this->staff_model->get('', 1);
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();
        $data['totalNotes']        = total_rows('tblnotes', array(
            'rel_id' => $id,
            'rel_type' => 'estimate'
        ));
        if ($to_return == false) {
            $this->load->view('admin/estimates/estimate_preview_template', $data);
        } else {
            return $this->load->view('admin/estimates/estimate_preview_template', $data, true);
        }
    }
    
    public function get_estimates_total()
    {
        if ($this->input->post()) {
            $data['totals'] = $this->estimates_model->get_estimates_total($this->input->post());
            
            $this->load->model('currencies_model');
            
            if (!$this->input->post('customer_id')) {
                $multiple_currencies = call_user_func('is_using_multiple_currencies', 'tblestimates');
            } else {
                $multiple_currencies = call_user_func('is_client_using_multiple_currencies', $this->input->post('customer_id'), 'tblestimates');
            }
            
            if ($multiple_currencies) {
                $data['currencies'] = $this->currencies_model->get();
            }
            
            $data['estimates_years'] = $this->estimates_model->get_estimates_years();
            
            if (count($data['estimates_years']) >= 1 && $data['estimates_years'][0]['year'] != date('Y')) {
                array_unshift($data['estimates_years'], array(
                    'year' => date('Y')
                ));
            }
            
            $data['_currency'] = $data['totals']['currencyid'];
            unset($data['totals']['currencyid']);
            $this->load->view('admin/estimates/estimates_total_template', $data);
        }
    }
    
    public function add_note($rel_id)
    {
        if ($this->input->post() && has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own')) {
            $this->misc_model->add_note($this->input->post(), 'estimate', $rel_id);
            echo $rel_id;
        }
    }
    
    public function get_notes($id)
    {
        if (has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own')) {
            $data['notes'] = $this->misc_model->get_notes($id, 'estimate');
            $this->load->view('admin/includes/sales_notes_template', $data);
        }
    }
    
    public function mark_action_status($status, $id)
    {
        if (!has_permission('estimates', '', 'edit')) {
            access_denied('estimates');
        }
        $success = $this->estimates_model->mark_action_status($status, $id);
        if ($success) {
            set_alert('success', _l('estimate_status_changed_success'));
        } else {
            set_alert('danger', _l('estimate_status_changed_fail'));
        }
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }
    
    public function send_expiry_reminder($id)
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            access_denied('estimates');
        }
        $success = $this->estimates_model->send_expiry_reminder($id);
        if ($success) {
            set_alert('success', _l('sent_expiry_reminder_success'));
        } else {
            set_alert('danger', _l('sent_expiry_reminder_fail'));
        }
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }
    
    /* Send estimate to email */
    public function send_to_email($id)
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            access_denied('estimates');
        }
        $success = $this->estimates_model->send_estimate_to_client($id, '', $this->input->post('attach_pdf'), $this->input->post('cc'));
        // In case client use another language
        load_admin_language();
        if ($success) {
            set_alert('success', _l('estimate_sent_to_client_success'));
        } else {
            set_alert('danger', _l('estimate_sent_to_client_fail'));
        }
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }
    
    /* Convert estimate to invoice */
    public function convert_to_invoice($id)
    {
        if (!has_permission('invoices', '', 'create')) {
            access_denied('invoices');
        }
        if (!$id) {
            die('No estimate found');
        }
        $draft_invoice = false;
        if ($this->input->get('save_as_draft')) {
            $draft_invoice = true;
        }
        $invoiceid = $this->estimates_model->convert_to_invoice($id, false, $draft_invoice);
        if ($invoiceid) {
            set_alert('success', _l('estimate_convert_to_invoice_successfully'));
            redirect(admin_url('invoices/list_invoices/' . $invoiceid));
        } else {
            if ($this->session->has_userdata('estimate_pipeline') && $this->session->userdata('estimate_pipeline') == 'true') {
                $this->session->set_flashdata('estimateid', $id);
            }
            if ($this->set_estimate_pipeline_autoload($id)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect(admin_url('estimates/list_estimates/' . $id));
            }
        }
    }
    
    public function copy($id)
    {
        if (!has_permission('estimates', '', 'create')) {
            access_denied('estimates');
        }
        if (!$id) {
            die('No estimate found');
        }
        $new_id = $this->estimates_model->copy($id);
        if ($new_id) {
            set_alert('success', _l('estimate_copied_successfully'));
            if ($this->set_estimate_pipeline_autoload($new_id)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect(admin_url('estimates/estimate/' . $new_id));
            }
        }
        set_alert('danger', _l('estimate_copied_fail'));
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/estimate/' . $id));
        }
    }
    
    /* Delete estimate */
    public function delete($id)
    {
        if (!has_permission('estimates', '', 'delete')) {
            access_denied('estimates');
        }
        if (!$id) {
            redirect(admin_url('estimates/list_estimates'));
        }
        $success = $this->estimates_model->delete($id);
        if (is_array($success)) {
            set_alert('warning', _l('is_invoiced_estimate_delete_error'));
        } elseif ($success == true) {
            set_alert('success', _l('deleted', _l('estimate')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('estimate_lowercase')));
        }
        redirect(admin_url('estimates/list_estimates'));
    }
    
    public function clear_acceptance_info($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->update('tblestimates', get_acceptance_info_array(true));
        }
        
        redirect(admin_url('estimates/list_estimates/' . $id));
    }
    
    /* Generates estimate PDF and senting to email  */
    public function pdf($id)
    {
        if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own')) {
            access_denied('estimates');
        }
        if (!$id) {
            redirect(admin_url('estimates/list_estimates'));
        }
        $estimate        = $this->estimates_model->get($id);
        $estimate_number = format_estimate_number($estimate->id);
        
        try {
            $pdf = estimate_pdf($estimate);
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }
        
        $type = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($estimate_number)) . '.pdf', $type);
    }
    
    // Pipeline
    public function get_pipeline()
    {
        if (has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own')) {
            $data['estimate_statuses'] = $this->estimates_model->get_statuses();
            $this->load->view('admin/estimates/pipeline/pipeline', $data);
        }
    }
    
    public function pipeline_open($id)
    {
        if (has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own')) {
            $data['id']       = $id;
            $data['estimate'] = $this->get_estimate_data_ajax($id, true);
            $this->load->view('admin/estimates/pipeline/estimate', $data);
        }
    }
    
    public function update_pipeline()
    {
        if (has_permission('estimates', '', 'edit')) {
            $this->estimates_model->update_pipeline($this->input->post());
        }
    }
    
    public function pipeline($set = 0, $manual = false)
    {
        if ($set == 1) {
            $set = 'true';
        } else {
            $set = 'false';
        }
        $this->session->set_userdata(array(
            'estimate_pipeline' => $set
        ));
        if ($manual == false) {
            redirect(admin_url('estimates/list_estimates'));
        }
    }
    
    public function pipeline_load_more()
    {
        $status = $this->input->get('status');
        $page   = $this->input->get('page');
        
        $estimates = $this->estimates_model->do_kanban_query($status, $this->input->get('search'), $page, array(
            'sort_by' => $this->input->get('sort_by'),
            'sort' => $this->input->get('sort')
        ));
        
        foreach ($estimates as $estimate) {
            $this->load->view('admin/estimates/pipeline/_kanban_card', array(
                'estimate' => $estimate,
                'status' => $status
            ));
        }
    }
    
    public function set_estimate_pipeline_autoload($id)
    {
        if ($id == '') {
            return false;
        }
        if ($this->session->has_userdata('estimate_pipeline') && $this->session->userdata('estimate_pipeline') == 'true') {
            $this->session->set_flashdata('estimateid', $id);
            
            return true;
        }
        
        return false;
    }
    
    public function get_due_date()
    {
        if ($this->input->post()) {
            $date    = $this->input->post('date');
            $duedate = '';
            if (get_option('estimate_due_after') != 0) {
                $date    = to_sql_date($date);
                $d       = date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime($date)));
                $duedate = _d($d);
                echo $duedate;
            }
        }
    }
    
    
    public function sent_smtp__email($to_email, $subject, $message)
    {
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
        
       
        $this->email->subject($template->subject);
        $this->email->message($template->message);
        $this->email->send(true);
        
        
    }
	
	
	public function sent_smtp_bcc_email($subject,$message)
    {
        
            // Simulate fake template to be parsed
            $template = new StdClass();
            $template->message = get_option('email_header').' '.$message.get_option('email_footer');
            $template->fromname = get_option('companyname');
            $template->subject = $subject;

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
	
}