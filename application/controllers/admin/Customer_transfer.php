<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');
class Customer_transfer extends Admin_controller
{
    private $not_importable_leads_fields;

    public function __construct()
    {
        parent::__construct();
        $this->not_importable_leads_fields = do_action('not_importable_leads_fields', array('id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language'));
        $this->load->model('leads_model');
		$this->load->model('currencies_model');
        $this->load->model('clients_model'); 
    }

    /* List all leads */
    public function index($id='')
    {
        close_setup_menu();

        if (!is_staff_member()) {
            access_denied('Leads');
        }
		
		
        if($this->input->post()){
			$data['staff'] = $this->staff_model->get_by_role($id);
			$data['list_leads_data']  = $this->clients_model->getlist_client_data($this->input->post('lead_manager'));
			
			$data['select_staff']  = $this->input->post('lead_manager');
		}else{
			$data['staff'] = $this->staff_model->get_by_role();
		
		}
	
        $data['title']    = 'Clients Transfer';
        // in case accesed the url leads/index/ directly with id - used in search
       
		
        $this->load->view('admin/lead_transfer/customer_transfer', $data);
    }
	
	public function table()
    {
        $this->app->get_table_data('lead_transfer');
    }

	
   public function add_lead_transfer(){
	   if (!is_staff_member()) {
            access_denied('Leads');
        }

        $data['switch_kanban'] = true;
		$this->load->model('currencies_model');
		
        if ($this->session->userdata('leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
		$data['list_leads_data']  = $this->leads_model->getlist_leads_data(); 
		$data_lead = array(		
				'transfer1' => $this->input->post('view_assigned'),
				'description' => $this->input->post('view_transfer'),);
	
   } 
   public function get_lead_transfer($id){
	   if (!is_staff_member()) {
            access_denied('Clients');
        }
		$data['list_leads_data']  = $this->clients_model->getlist_client_data($this->input->post('lead_manager'));

		
      	$this->load->view('admin/lead_transfer/customer_transfer', $data);  
   }
   
   
   public function lead_transfer_done(){
	   if (!is_staff_member()) {
            access_denied('Clients');
        }
		$lead_id = $this->input->post('lead_id');
	    $lead_manager_to = $this->input->post('lead_manager_to');
	   $data['list_leads_data']  = $this->clients_model->getlist_client_data($this->input->post('lead_manager'));
	    $total_record = sizeof($lead_id);
			
		for($i=0;$i<$total_record;$i++) {
			$req_data = array(
				'userid' => $lead_id[$i],
				'addedfrom' => $lead_manager_to[$i],
			);
			$this->db->where('userid', $lead_id[$i]);
			$this->db->update('tblclients', $req_data);
			
			$req_data_contact = array(
				'addedby' => $lead_manager_to[$i],
			);
		    $name          = get_staff_full_name($lead_manager_to[$i]);
			$this->db->where('userid', $lead_id[$i]);
			$this->db->update('tblcontacts', $req_data_contact);
			
			logActivity($lead_id[$i],'Customer and Contact Transfer  to [' . $name . ']');
		}
		redirect('admin/Customer_transfer/');
   }
   
   
   
   
}
