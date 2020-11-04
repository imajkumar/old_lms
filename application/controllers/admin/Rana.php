
<?php
header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed');
class Rana extends Admin_controller
{
    private $not_importable_leads_fields;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('leads_model');
        $this->load->model('currencies_model');
        $this->load->model('clients_model');
        $this->load->model('emails_model');
        $this->load->model('staff_model');
    }
    
    /* List all leads */
    public function update_lead_reportingto()
    { 
	    $this->db->order_by('staffid');
        $this->db->select('staffid,`firstname`,`lastname`,reporting_to,role');
		$userarray = $this->db->get('tblstaff')->result_array();
		foreach($userarray as $row)  
		{
			//---------------------update lead---------------//
			  $data_l = array(
				'reportingto'=>$row['reporting_to'],
			  );
			  $this->db->where('assigned', $row['staffid']);
			  $this->db->update('tblleads',$data_l);
			  
			 // $this->db->where('addedfrom', $row['staffid']);
			 // $this->db->update('tblclients', $data_l);
		}
       
    }
    
	
}
