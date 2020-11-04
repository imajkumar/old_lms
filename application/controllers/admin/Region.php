<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Region extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
		 $this->load->model('currencies_model'); $this->load->model('region_model');$this->load->model('leads_model');
    }

	public function index(){
	
		
$data['stateList'] = $this->currencies_model->getStateByCountry("104");

$data['stateList_region'] = $this->currencies_model->get_states_id();
$data['lists_region_data'] = $this->currencies_model->lists_region_data();

$data['list_region'] = $this->currencies_model->list_region();
$data['region'] = $this->currencies_model->get_region();
$data['get_region'] = $this->region_model->get();	



	
		if (!is_staff_member()) {
            access_denied('Region');
        }
	
		 if ($this->input->post()) {
            
            // Don't do XSS clean here.
            
           
			$state_data = $this->input->post('state');
			
			 foreach($state_data as $row)
			 {
				 $data = array();
				 $data['region'] = $this->input->post('region', false);
				 $data['state_id'] = $row;
			  $this->db->insert('tblregion_state', $data);
			  
			 }
			 $state = substr($state, 0, -2);
			
			 $data['state'] = $state;
			 $insert_id = $this->db->insert_id();

		if ($insert_id>0) {
			echo "region Added";
			} else {
				echo  'region  not Added';
			}
			redirect('admin/region');
		}
		
		 $this->load->view('admin/staff/region', $data);
		
	}
	public function add_region($id){
	 if ($this->input->post()) {
			
			$data['get_region'] = $this->region_model->get($id);
            $region_data = array(
		
					'region' => $this->input->post('name'),
					
					
				);
			if($id==''){
				
				$this->db->insert('tblregion', $region_data);
				$insert_id = $this->db->insert_id();
			}
			$this->db->update('tblregion', $region_data);
			
		
	 }
		
	
		 
			redirect('admin/region');
	
		
		 $this->load->view('admin/staff/region', $region_data);
		
	}
	
	
	
	
	public function get_region_list(){
		$state_data = 'id';
			
			 foreach($state_data as $row)
			 {
			  $state .= $row . ', ';
			 }
			 $state = substr($state, 0, -2);
			
			 $data['stat_id'] = $state;
		
	}
	
	
	 public function getBystate() {
        $region_id = $this->input->get('region');

        $data = $this->currencies_model->getregionBystate($region_id);
		
		 echo json_encode($data);
    }
	public function getByStaff_zone() {

        $region_id = $this->input->get('region');



        $data = $this->currencies_model->getstaffByregion($region_id);

		

		 echo json_encode($data);

    }

	public function getstaffid() {
       $staffreporting_manager = $this->input->get('staffreporting_manager');

	   $this->db->where('staffid', $staffreporting_manager);

        echo $this->db->get('tblstaff')->row()->reporting_to;
		
    }
	
	
	
	public function edit_region(){
		
		$data['get_region'] = $this->region_model->get();	
		
		 $this->load->view('admin/region', $data);
		
	}
	
	public function loss_region()
    {
		
        if (!is_admin()) {
            access_denied('Region');
        }
       $data['get_region'] = $this->region_model->get();	
	
        $data['title']    = 'Region';
        $this->load->view('admin/leads/manage_region', $data);
    }
	
	
	
	 public function add_region_data()
    {
        
        if ($this->input->post()) {
            $data = $this->input->post();
			
			
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->region_model->add_region($data);
				json_encode($id);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('region')));
                    }
                } else {
                    echo json_encode(array('success'=>$id ? true : fales, 'id'=>$id));
                }
            } else {
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->region_model->update_region($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('region')));
                }
            }
        }
    }
	
	
	
	
}