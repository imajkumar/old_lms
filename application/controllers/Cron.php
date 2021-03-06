<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CRM_Controller
{
    public function __construct()
    {
        parent::__construct();
        update_option('cron_has_run_from_cli', 1);
		$this->load->model('emails_model');
        $this->load->model('staff_model');
        $this->load->model('clients_model');
        $this->load->model('leads_model');
    }

    public function index($key = "")
    {
        if(defined('APP_CRON_KEY') && (APP_CRON_KEY != $key)){
            header('HTTP/1.0 401 Unauthorized');
            die('Passed cron job key is not correct. The cron job key should be the same like the one defined in APP_CRON_KEY constant.');
        }

        $last_cron_run = get_option('last_cron_run');
        if ($last_cron_run == '' || (time() > ($last_cron_run + do_action('cron_functions_execute_seconds',300)))) {
            do_action('before_cron_run');

            $this->load->model('cron_model');
            $this->cron_model->run();

            do_action('after_cron_run');
        }

    }
	
	public function counting_new_v1()
    {	
		$this->load->model('cron_model');	
		$this->cron_model->make_backup_db();
	}
	
	public function counting_new_v2() //run this sir 
    {	
		  $this->load->model('cron_model');
          $this->cron_model->run_cron_winloss(true);
	}
	
	public function counting_new()
    {		
		//$this->load->model('cron_model');
		//$this->cron_model->make_backup_db();
		
		$table_content ='<table class="table" border="1" width="100%">
		   <thead>
			  <tr role="row">
				 <th rowspan="1" colspan="3"> </th>
				 <th bgcolor="#fd4" rowspan="1" colspan="4">Customer</th>
				 <th bgcolor="#56ff63" rowspan="1" colspan="4">Lead(Nos.)</th>
				 <th bgcolor="#56FED4" rowspan="1" colspan="4">Lead Value(In Lakhs)</th>
				
			  </tr>
			   
		   </thead>
		   <tbody>';
		   
		$table_content .='
				<tr role="row">
				 <th>User</th>
				 <th>Last Login</th>
				 <th>Reporting Head</th>
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-2 month')).'</th>
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-1 month')).'</th>
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-0 month')).'</th>
				 <th bgcolor="#56FED4">'.date('d-M-Y', strtotime("-1 days")).'</th>
				 
				 <th bgcolor="#56ff63">'.date('M-Y', strtotime('-2 month')).'</th>
				 <th bgcolor="#56ff63">'.date('M-Y', strtotime('-1 month')).'</th>
				 <th bgcolor="#56ff63">'.date('M-Y', strtotime('-0 month')).'</th>
				 <th bgcolor="#56ff63">'.date('d-M-Y', strtotime("-1 days")).'</th>
				
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-2 month')).'</th>
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-1 month')).'</th>
				 <th bgcolor="#56FED4">'.date('M-Y', strtotime('-0 month')).'</th>
				 <th bgcolor="#56FED4">'.date('d-M-Y', strtotime("-1 days")).'</th>
				
				</tr>';  
		$thisMonth = date('Y-m');	
		$lastMonth = date('Y-m', strtotime("-1 month"));	
		$last2Month = date('Y-m', strtotime("-2 month"));	
		$last2MonthDate = date('Y-m-01', strtotime("-2 month"));
		 	
		$lastdate = date('Y-m-d', strtotime("-1 days"));
		
		//========================= Top Customer Total ===============================
		    $this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$where = "tblclients.datetime LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata = $this->db->get()->row();
			$top_2_total = $last2Monthdata->total;
		   
		    $this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$where = "tblclients.datetime LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$last2Monthdata = $this->db->get()->row();
			$top_1_total = $last2Monthdata->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$where = "tblclients.datetime LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$last2Monthdata = $this->db->get()->row();
			$top_total = $last2Monthdata->total;
		
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$where = "tblclients.datetime LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$last2Monthdata = $this->db->get()->row();
			$top_lastdatetotal = $last2Monthdata->total;
		//---------------Top Lead Count -------------//
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata_lead = $this->db->get()->row();
			$last2monthcount_lead = $last2Monthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$lastMonthdata_lead = $this->db->get()->row();
			$lastmonthcount_lead = $lastMonthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$thisMonthdata_lead = $this->db->get()->row();
			$thismonthcount_lead = $thisMonthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$lastdatedata_lead = $this->db->get()->row();
			$lastdatecount_lead = $lastdatedata_lead->total;
			
		//-----------------Top Lead Sum ----------------------//
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata_lead_sum = $this->db->get()->row();
			$last2monthcount_lead_sum = $last2Monthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$lastMonthdata_lead_sum = $this->db->get()->row();
			$lastmonthcount_lead_sum = $lastMonthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$thisMonthdata_lead_sum = $this->db->get()->row();
			$thismonthcount_lead_sum = $thisMonthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$where = "tblleads.dateadded LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$lastdatedata_lead_sum = $this->db->get()->row();
			$lastdatecount_lead_sum = $lastdatedata_lead_sum->total;
		
		$table_content .='
					<tr align="center">
					 <th bgcolor="#ffff1a" colspan="3">Grand Total:</th>
					 <th bgcolor="#ffff1a">'.$top_2_total.'</th>
					 <th bgcolor="#ffff1a">'.$top_1_total.'</th>
					 <th bgcolor="#ffff1a">'.$top_total.'</th>
					 <th bgcolor="#ffff1a">'.$top_lastdatetotal.'</th>
					 
					<th bgcolor="#ffff1a">'.$last2monthcount_lead.'</th>
					<th bgcolor="#ffff1a">'.$lastmonthcount_lead.'</th>
					<th bgcolor="#ffff1a">'.$thismonthcount_lead.'</th>
					<th bgcolor="#ffff1a">'.$lastdatecount_lead.'</th>
					
					<th bgcolor="#ffff1a">'.$last2monthcount_lead_sum.'</th>
					<th bgcolor="#ffff1a">'.$lastmonthcount_lead_sum.'</th>
					<th bgcolor="#ffff1a">'.$thismonthcount_lead_sum.'</th>
					<th bgcolor="#ffff1a">'.$lastdatecount_lead_sum.'</th>
				  </tr>';
		$table_content .='
					<tr align="center">
					 <th colspan="15"></th>
					 
				  </tr>';
        $regions = $this->db->get('tblregion')->result_array();
		foreach ($regions as $region) {
			
			$staffs = $this->db->get_where('tblstaff',array('region' => $region["id"],'active' => '1'))->result_array();	
			
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$this->db->join('tblstaff', 'tblclients.addedfrom = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblclients.datetime LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata = $this->db->get()->row();
			$last2Monthcount = $last2Monthdata->total;
			
			
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$this->db->join('tblstaff', 'tblclients.addedfrom = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblclients.datetime LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$lastMonthdata = $this->db->get()->row();
			$lastMonthcount = $lastMonthdata->total;
			
			
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$this->db->join('tblstaff', 'tblclients.addedfrom = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblclients.datetime LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$thisMonthdata = $this->db->get()->row();
			$thisMonthcount = $thisMonthdata->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblclients');
			$this->db->join('tblstaff', 'tblclients.addedfrom = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblclients.datetime LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$lastdatedata = $this->db->get()->row();
			$lastdatecount = $lastdatedata->total;
			
			//--------------- Lead Count -------------//
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata_lead = $this->db->get()->row();
			$last2monthcount_lead = $last2Monthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$lastMonthdata_lead = $this->db->get()->row();
			$lastmonthcount_lead = $lastMonthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$thisMonthdata_lead = $this->db->get()->row();
			$thismonthcount_lead = $thisMonthdata_lead->total;
			
			$this->db->select('count(*) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$lastdatedata_lead = $this->db->get()->row();
			$lastdatecount_lead = $lastdatedata_lead->total;
			
		//----------------- Lead Sum ----------------------//
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$last2Month."%'"; 
			$this->db->where($where);
			$last2Monthdata_lead_sum = $this->db->get()->row();
			$last2monthcount_lead_sum = $last2Monthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$lastMonth."%'"; 
			$this->db->where($where);
			$lastMonthdata_lead_sum = $this->db->get()->row();
			$lastmonthcount_lead_sum = $lastMonthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$thisMonth."%'"; 
			$this->db->where($where);
			$thisMonthdata_lead_sum = $this->db->get()->row();
			$thismonthcount_lead_sum = $thisMonthdata_lead_sum->total;
			
			$this->db->select('COALESCE(SUM(opportunity),0) as total');
			$this->db->from('tblleads');
			$this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid', 'left');
			$where = "tblstaff.region='".$region['id']."' AND tblleads.dateadded LIKE '%".$lastdate."%'"; 
			$this->db->where($where);
			$lastdatedata_lead_sum = $this->db->get()->row();
			$lastdatecount_lead_sum = $lastdatedata_lead_sum->total;
			
			$table_content .='
					<tr align="center">
						<th colspan="15" bgcolor="#f47b34">'.$region["region"].'</th>
					</tr>';
			foreach ($staffs as $staff) {
				if($staff['role'] == '5' || $staff['role'] == '2'){
					$conditionLead = array('assigned' => $staff["staffid"],'dateassigned <' => $last2MonthDate,'dateassigned >' => $lastdate); 
					$query = $this->db->get_where('tblleads', array('assigned' => $staff["staffid"]));
					$ifzsmleadvalue = $query->num_rows();
					
					$conditionClient = array('addedfrom' => $staff["staffid"],'datetime <' => $last2MonthDate,'datetime >' => $lastdate);
					$queryc = $this->db->get_where('tblclients', $conditionClient);
					$ifzsmhascustomer = $queryc->num_rows();
					
					
					if($ifzsmleadvalue > 0 || $ifzsmhascustomer > 0)
					{
																	
							$reporting_manager = $this->db->get_where('tblstaff', array('staffid =' => $staff["reporting_manager"]))->row();
							
							$last2Monthno_of_custTotal = $last2Monthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($last2Month,$staff["staffid"]);
							$lastMonthno_of_custTotal = $lastMonthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthno_of_custTotal = $thisMonthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($thisMonth,$staff["staffid"]);				
							$lastdateno_of_custTotal = $lastdateno_of_custTotal + $this->clients_model->no_of_cust_bydate($lastdate,$staff["staffid"]);
							
							$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($last2Month,$staff["staffid"]);
							$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($thisMonth,$staff["staffid"]);				
							$lastdateno_of_leadsTotal = $lastdateno_of_leadsTotal + $this->leads_model->no_of_leads_bydate($lastdate,$staff["staffid"]);
							
							$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($last2Month,$staff["staffid"]);
							$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($thisMonth,$staff["staffid"]);				
							$lastdatevalue_of_leadsTotal = $lastdatevalue_of_leadsTotal + $this->leads_model->value_of_leads_bydate($lastdate,$staff["staffid"]);
							
							if($staff["last_login"]=="")
							{
								$datelogin = "-"; 
							}else
							{ 
								$datelogin = date("d-M-Y", strtotime($staff["last_login"])); 
							}
							
							
							$table_content .='
								<tr align="center">
								 <td>'.$staff["firstname"].' '.$staff["lastname"].'</td>
								 <td>'.$datelogin.'</td>
								 <td>'.$reporting_manager->firstname.' '.$reporting_manager->lastname.'</td>
								 <td bgcolor="#56FED4">'.$this->clients_model->no_of_cust_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->clients_model->no_of_cust_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->clients_model->no_of_cust_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->clients_model->no_of_cust_bydate($lastdate,$staff["staffid"]).'</td>
								 
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bydate($lastdate,$staff["staffid"]).'</td>
							  
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bydate($lastdate,$staff["staffid"]).'</td>
							  </tr>';
						
													
						
					}
				}else if($staff['role'] == '1' || $staff['role'] == '3'){

							$reporting_manager = $this->db->get_where('tblstaff', array('staffid =' => $staff["reporting_manager"]))->row();
							
							$last2Monthno_of_custTotal = $last2Monthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($last2Month,$staff["staffid"]);
							$lastMonthno_of_custTotal = $lastMonthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthno_of_custTotal = $thisMonthno_of_custTotal + $this->clients_model->no_of_cust_bymonth($thisMonth,$staff["staffid"]);				
							$lastdateno_of_custTotal = $lastdateno_of_custTotal + $this->clients_model->no_of_cust_bydate($lastdate,$staff["staffid"]);
							
							$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($last2Month,$staff["staffid"]);
							$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_bymonth($thisMonth,$staff["staffid"]);				
							$lastdateno_of_leadsTotal = $lastdateno_of_leadsTotal + $this->leads_model->no_of_leads_bydate($lastdate,$staff["staffid"]);
							
							$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($last2Month,$staff["staffid"]);
							$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($lastMonth,$staff["staffid"]);
							$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_bymonth($thisMonth,$staff["staffid"]);				
							$lastdatevalue_of_leadsTotal = $lastdatevalue_of_leadsTotal + $this->leads_model->value_of_leads_bydate($lastdate,$staff["staffid"]);
							$datelogin = "";
							if($staff["last_login"]=="")
							{
								$datelogin = "-"; 
							}else
							{ 
								$datelogin = date("d-F-Y", strtotime($staff["last_login"])); 
							}
							$table_content .='
								<tr align="center">
								 <td>'.$staff["firstname"].' '.$staff["lastname"].'</td>
								 <td>'.$datelogin.'</td>
								 <td>'.$reporting_manager->firstname.' '.$reporting_manager->lastname.'</td>
								 <td bgcolor="#fd4">'.$this->clients_model->no_of_cust_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#fd4">'.$this->clients_model->no_of_cust_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#fd4">'.$this->clients_model->no_of_cust_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#fd4">'.$this->clients_model->no_of_cust_bydate($lastdate,$staff["staffid"]).'</td>
								 
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56ff63">'.$this->leads_model->no_of_leads_bydate($lastdate,$staff["staffid"]).'</td>
							  
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($last2Month,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($lastMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bymonth($thisMonth,$staff["staffid"]).'</td>
								 <td bgcolor="#56FED4">'.$this->leads_model->value_of_leads_bydate($lastdate,$staff["staffid"]).'</td>
							  </tr>';
						} 
			}
			
			$table_content .='
					<tr align="center">
						<th colspan="3" bgcolor="#ffff1a">'.$region["region"].' Total</th>
						<th bgcolor="#ffff1a">'.$last2Monthcount.'</th>
						<th bgcolor="#ffff1a">'.$lastMonthcount.'</th>
						<th bgcolor="#ffff1a">'.$thisMonthcount.'</th>
						<th bgcolor="#ffff1a">'.$lastdatecount.'</th>
						
						<th bgcolor="#ffff1a">'.$last2monthcount_lead.'</th>
						<th bgcolor="#ffff1a">'.$lastmonthcount_lead.'</th>
						<th bgcolor="#ffff1a">'.$thismonthcount_lead.'</th>
						<th bgcolor="#ffff1a">'.$lastdatecount_lead.'</th>
						
						<th bgcolor="#ffff1a">'.$last2monthcount_lead_sum.'</th>
						<th bgcolor="#ffff1a">'.$lastmonthcount_lead_sum.'</th>
						<th bgcolor="#ffff1a">'.$thismonthcount_lead_sum.'</th>
						<th bgcolor="#ffff1a">'.$lastdatecount_lead_sum.'</th>
					</tr>';
			
			
		}
		/* $table_content .='
					<tr align="center">
					 <th bgcolor="#f47b34" colspan="3">Total:</th>
					 <td bgcolor="#f47b34">'.$last2Monthno_of_custTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastMonthno_of_custTotal.'</td>
					 <td bgcolor="#f47b34">'.$thisMonthno_of_custTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastdateno_of_custTotal.'</td>
					 
					 <td bgcolor="#f47b34">'.$last2Monthno_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastMonthno_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$thisMonthno_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastdateno_of_leadsTotal.'</td>
				  
				     <td bgcolor="#f47b34">'.$last2Monthvalue_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastMonthvalue_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$thisMonthvalue_of_leadsTotal.'</td>
					 <td bgcolor="#f47b34">'.$lastdatevalue_of_leadsTotal.'</td>
				  </tr>'; */
		$table_content .='</tbody>
		</table>';
		$table_content .='
		<style>
		.container{
					
			display: block;
			margin: 0 auto !important;
			max-width: 100% !important;
			padding: 10px;
			width: 100% !important; 
		}
		.content{
			box-sizing: border-box;
			display: block;
			margin: 0 auto;
			max-width: 100% !important;
			padding: 10px;
		}
		</sytle>
		';
		
		//echo $table_content;
		
		
		
		$lastdate = date('d-F-Y', strtotime("-1 days"));
		$subject = "LMS Daily Status Reports - ".$lastdate;
		$to = get_option('daily_status_reports_to');
		$daily_status_reports_cc = get_option('daily_status_reports_cc');
			
		$array = explode(',', $daily_status_reports_cc);
		$commaCC = "".implode( " , ", $array). "";
		$ccc = explode(',', $commaCC);;
		//print_r($ccc);
		$newcc=array();
		foreach($ccc as $value){
			$companydomain = substr($value, strpos($value, "@") + 1);
			if($companydomain == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
		//print_r($newcc);
	$this->sent_smtp__email('sameer.jindal@halonix.co.in', $subject, $table_content,$ccc);
		
	/*----------------------- Stages Wise Daily Reports -------------------------*/		
	
	$this->stage();
	$this->stage_mtdrnew();
	$this->stage_user();
	$this->stage_mtdr_dynsm_nsm();
	
	//$this->load->model('cron_model');
    //$this->cron_model->make_backup_db();
		
    }
	
	//==================== Lead Stage Report ===========//	
	public function stage()
	{
			$from_month = date('Y').'-04-30';
			$to_month = date('Y-m').'-15';
			$start    = (new DateTime($from_month))->modify('first day of this month');
			$end      = (new DateTime($to_month))->modify('first day of next month');
			$interval = DateInterval::createFromDateString('1 month');
			$period   = new DatePeriod($start, $interval, $end);
			$this->db->order_by("id", "asc");
			$leadstage = $this->db->get('tblleadsstatus')->result_array();

			$table_content_stage = "<table class='table border' border='1'>";
			$table_content_stage .= '<tr bgcolor="#f47b34">	
				<th style="text-align:center;width:150px;"  rowspan="2" colspan="2"><strong>Stages<br>/<br>Month</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Total</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Alignment & Selection</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Closed Lost</strong></th>
			  </tr>
			  <tr bgcolor="#f47b34">											
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
            $identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
				
				//$currmonth = 11;//date('m');
				if (date('m') > 6) {    
					$d1 = date('Y-01-01');
				} else {
					$d1 = (date('Y')-1)."-".date('04-01');					
				}
				$d2 = date('Y-m-d');
				$currmonth = (int)abs((strtotime($d1) - strtotime($d2))/(60*60*24*30));

				$rowspan = $currmonth;//($currmonth -4)+ 1;
				//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));


				
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,'');

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,'');

				

			}
				$table_content_stage .= '<tr bgcolor="#c6e0b4">											

				<th colspan="2" style="text-align:center;"><strong>Total</strong></th>
				<th style="text-align:center;"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$total_no_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_lost_lead_total_value.'</strong></th>

				
			  </tr>';
				
				
				$rowspan = 10;//($currmonth -4)+ 1;
				/* for($m=$currmonth; $m>=4; $m--){
				
				$month = date('Y-m', mktime(0, 0, 0, $m, 1)); */
				//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));

				
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,'');

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,'');

				$table_content_stage .= '<tr>';
				if($m==$currmonth){
					$table_content_stage .='<th rowspan="'.$rowspan.'">All India</th>';
				}
				$table_content_stage .= "<td colspan='2' style='width:150px;text-align:center;'><strong>".date('M, Y', strtotime($month))."</strong></td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'')."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,'')."</td>";

				 
				 $table_content_stage .= '</tr>';

			}

			
			$table_content_stage .='
					<tr align="center">
					 <th colspan="18" style="padding-top:8px"></th>
					 
				  </tr>';
			$regions = $this->db->get('tblregion')->result_array();
			foreach ($regions as $region) {	
			$table_content_stage .= '<tr bgcolor="#f47b34">	
				<th style="text-align:center;width:150px;"  rowspan="2" colspan="2"><strong>Stages<br>/<br>Month</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Total</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Alignment & Selection</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;"  colspan="2"><strong>Closed Lost</strong></th>
			  </tr>
			  <tr bgcolor="#f47b34">											
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" ><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
				$identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
						
			
			/* for($m=$currmonth; $m>=4; $m--){

				$month = date('Y-m', mktime(0, 0, 0, $m, 1)); */
//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));

				
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);


			}
			if($region['region']=='North'){
			$table_content_stage .='<tr bgcolor="#00ffff">';				
							}else if($region['region']=='East'){
			$table_content_stage .='<tr bgcolor="#ff6699">';					
							}else if($region['region']=='South'){
			$table_content_stage .='<tr bgcolor="#cc66ff">';					
							}else if($region['region']=='West'){
			$table_content_stage .='<tr bgcolor="#66ffcc">';					
							}
			
			$table_content_stage .=	'<th colspan="2" style="text-align:center;"><strong>'.$region["region"].' Total</strong></th>
				<th style="text-align:center;"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$total_no_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;"><strong>'.$close_lost_lead_total_value.'</strong></th>

				
			  </tr>';

				
			/* for($m=$currmonth; $m>=4; $m--){

				$month = date('Y-m', mktime(0, 0, 0, $m, 1)); */
//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));

				$table_content_stage .= '<tr>';
				if($m==$currmonth){
					$table_content_stage .='<th rowspan="'.$rowspan.'">'.$region["region"].'</th>';
				}
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$table_content_stage .= "<td colspan='2' style='width:150px;text-align:center;'><strong>".date('M, Y', strtotime($month))."</strong></td>";
				
				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 
				 $table_content_stage .= '</tr>';

			}

			$table_content_stage .='
					<tr align="center">
					 <th colspan="18" style="padding-top:8px"></th>
					 
				  </tr>';

		}	
			
		$table_content_stage .= "</table>";
		
		
		//echo $table_content_stage;
		
		$lastdate = date('Y-m-d', strtotime("-1 days"));
		$subject = "Lead Stage Report - ".$lastdate;
		
		$to = get_option('stages_wise_daily_reports_to');
		$stages_wise_daily_reports_cc = get_option('stages_wise_daily_reports_cc');
		
		$array = explode(',', $stages_wise_daily_reports_cc);
		$commaCC = "".implode( " , ", $array). "";
		$ccc = explode(',', $commaCC);;
		
		$newcc=array();
		foreach($ccc as $value){
			if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
		//echo $table_content_stage;
		$this->sent_smtp__email($to, $subject, $table_content_stage,$newcc);
	   
	}

	//==================== Stage Wise Report ==============//
	public function stage_user()
	{
			$query = $this->db->query('SELECT * FROM tblstaff where role IN(2, 3, 5, 6, 8) AND active=1');
			$staffs =  $query->result_array();
			
			$this->db->order_by("id", "asc");
			$leadstage = $this->db->get('tblleadsstatus')->result_array();
		foreach ($staffs as $staff) {
			$staff_id = $staff["staffid"];
			$staff_name = $staff["firstname"];
			$staff_email = $staff["email"];
			$reg = $staff["region"];
			$table_content_stage = "<table class='table border' border='1'>";
			$table_content_stage .= '<tr>	
				<th style="text-align:center;width:150px;" bgcolor="#f47b34" rowspan="2"><strong>Stages<br>/<br>Month</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Alignment & Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Lost</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Total</strong></th>
			  </tr>
			  <tr>											
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
			$query = $this->db->query('SELECT * FROM tblregion where id IN(' . $reg . ')');
			$regions =  $query->result_array();
			$identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
			foreach ($regions as $region) {	
			
				
				if (date('m') > 6) {    
					$d1 = date('Y-01-01');
				} else {
					$d1 = (date('Y')-1)."-".date('04-01');					
				}
				$d2 = date('Y-m-d');
				$currmonth = (int)abs((strtotime($d1) - strtotime($d2))/(60*60*24*30));

				$rowspan = $currmonth;//($currmonth -4)+ 1;
				//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
					
				/* $currmonth = date('m');
				for($m=$currmonth; $m>=4; $m--){
				
				$month = date('Y-m', mktime(0, 0, 0, $m, 1)); */

				
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

			}
			}

			$table_content_stage .= '<tr>											

				<th style="text-align:center;" bgcolor="#56ff63"><strong>Lead Total</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total_value.'</strong></th>

			  </tr>';
			
			$table_content_stage .='
					<tr align="center">
					 <th colspan="17"></th>
					 
				  </tr>';
			
			 //echo 'SELECT * FROM tblregion where id IN(' . $reg . ')';
			 $query = $this->db->query('SELECT * FROM tblregion where id IN(' . $reg . ')');
				$regions =  $query->result_array();
		
			foreach ($regions as $region) {	
				$identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
						
			$table_content_stage .= '<tr align="center">
										<th colspan="17" bgcolor="#f47b34">'.$region["region"].'</th>
									</tr>';
									
			/* for($m=$currmonth; $m>=4; $m--){

				$month = date('Y-m', mktime(0, 0, 0, $m, 1)); */
				if (date('m') > 6) {    
					$d1 = date('Y-01-01');
				} else {
					$d1 = (date('Y')-1)."-".date('04-01');					
				}
				$d2 = date('Y-m-d');
				$currmonth = (int)abs((strtotime($d1) - strtotime($d2))/(60*60*24*30));

				$rowspan = $currmonth;//($currmonth -4)+ 1;
				//for($m=$currmonth; $m>=4; $m--){
				for ($i =0; $i <= $currmonth; $i++) {
					
				//$month = date('Y-m', mktime(0, 0, 0, $m, 1));
				    $month = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));

				$table_content_stage .= '<tr>';

				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"]);

				$table_content_stage .= "<td ><strong>".date('M, Y', strtotime($month))."</strong></td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region["id"])."</td>";

				 $table_content_stage .= '</tr>';

			}

			$table_content_stage .= '<tr>											

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$region["region"].' Total</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total_value.'</strong></th>

			  </tr>';

				}	

			$table_content_stage .= "</table>";
		
		
			//echo $table_content_stage;
		
			$lastdate = date('Y-m-d', strtotime("-1 days"));
			$subject = "Stages Wise Daily Reports - ".$lastdate;
			
			$to = get_option('stages_wise_daily_reports_to');
			$stages_wise_daily_reports_cc = get_option('stages_wise_daily_reports_cc');
			
			$array = explode(',', $stages_wise_daily_reports_cc);
			$commaCC = "".implode( " , ", $array). "";
			$ccc = explode(',', $commaCC);;
			
			$newcc=array();
			foreach($ccc as $value){
				if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
				{
					$newcc[]=$value;
				}
			}
			//$this->sent_smtp__email('rajeev.gangwar@halonix.co.in', $subject, $table_content_stage,$newcc);
			$this->sent_smtp__email($staff_email, $subject, $table_content_stage,'lms-noreply@halonix.co.in');
		}
		
	}

//================ Stage Summary Report ==========//	
	public function stage_mtdrnew()
	{
		
		$curr_month = date('Y-m');
		//$curr_month = '2019-09';
		
		if ( date('m') > 3 ) {
			$year = date('Y') + 1;
		}
		else {
			$year = date('Y');
		}
		$fromyear = ($year-1);
		$toyear = $year;
		
		$fromyearmonth = $fromyear.'-04';
		$toyearmonth = $year.'-03';
		
		
		
		$this->db->order_by("id", "asc");
		$leadstage = $this->db->get('tblleadsstatus')->result_array();
			
		$tbl_data = '<table class="table border" border="1" style="font-size:13px;text-align:center;font-weight: bold;"><tbody>
			<tr align="center">
						<th colspan="20" style="background:#f58a4c">All Regions</th>
					</tr>	
			 <tr>	
				<th style="text-align:center;width:250px;" bgcolor="#f4b084" rowspan="2" colspan="2" width="150"><strong>Stages</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Total</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Alignment &amp; Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Lost</strong></th>
				<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Open</strong></th>
			  </tr>
			  <tr>											
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
		$mtd_total_lead = 0; $mtd_total_lead_value = 0; $mtd_identified_lead = 0; $mtd_identified_lead_value = 0;
		$mtd_qualified_lead = 0; $mtd_qualified_lead_value = 0;	$mtd_alignment_lead = 0; $mtd_alignment_lead_value = 0;
		$mtd_finalselection_lead = 0; $mtd_finalselection_lead_value = 0; $mtd_finalcontract_lead = 0;
		$mtd_finalcontract_lead_value = 0;	$mtd_closewon_lead = 0;	$mtd_closewon_lead_value = 0;	$mtd_closeloss_lead = 0;
		$mtd_closeloss_lead_value = 0;
			
		$mtd_stage_total_lead = 0;	$mtd_stage_total_lead_value = 0;	$mtd_stage_identified_lead = 0;			$mtd_stage_identified_lead_value = 0;	$mtd_stage_qualified_lead = 0;	$mtd_stage_qualified_lead_value = 0;		$mtd_stage_alignment_lead = 0;	$mtd_stage_alignment_lead_value = 0;	$mtd_stage_finalselection_lead = 0;
		$mtd_stage_finalselection_lead_value = 0; $mtd_stage_finalcontract_lead = 0; $mtd_stage_finalcontract_lead_value = 0;
		$mtd_stage_closewon_lead = 0; $mtd_stage_closewon_lead_value = 0; $mtd_stage_closeloss_lead = 0;
		$mtd_stage_closeloss_lead_value = 0;
		
		$itd_total_lead = 0; $itd_total_lead_value = 0; $itd_closewon_lead = 0; $itd_closewon_lead_value = 0;$itd_closeloss_lead = 0; $itd_closeloss_lead_value = 0;
		
		$regions = $this->db->get('tblregion')->result_array();
		foreach ($regions as $region) {
			
			$mtd_total_lead = $mtd_total_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region['id']);
			$mtd_total_lead_value = $mtd_total_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region['id']);
			
			$itd_total_lead = $itd_total_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region['id']);
			$itd_total_lead_value = $itd_total_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region['id']);
			
			$itd_closewon_lead = $itd_closewon_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region['id']);
			$itd_closewon_lead_value = $itd_closewon_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region['id']);
			$itd_closeloss_lead = $itd_closeloss_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region['id']);
			$itd_closeloss_lead_value = $itd_closeloss_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region['id']);
			
			
			$mtd_stage_identified_lead = $mtd_stage_identified_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region['id']);
			$mtd_stage_identified_lead_value = $mtd_stage_identified_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region['id']);
			$mtd_stage_qualified_lead = $mtd_stage_qualified_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region['id']);
			$mtd_stage_qualified_lead_value = $mtd_stage_qualified_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region['id']);
			$mtd_stage_alignment_lead = $mtd_stage_alignment_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region['id']);
			$mtd_stage_alignment_lead_value = $mtd_stage_alignment_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region['id']);
			$mtd_finalselection_lead = $mtd_finalselection_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region['id']);
			$mtd_finalselection_lead_value = $mtd_finalselection_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region['id']);
			$mtd_finalcontract_lead = $mtd_finalcontract_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region['id']);
			$mtd_finalcontract_lead_value = $mtd_finalcontract_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region['id']);
			$mtd_closewon_lead = $mtd_closewon_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region['id']);
			$mtd_closewon_lead_value = $mtd_closewon_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region['id']);
			$mtd_closeloss_lead = $mtd_closeloss_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region['id']);
			$mtd_closeloss_lead_value = $mtd_closeloss_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region['id']);
			
			
		}
		
		
		$tbl_data .='<tr bgcolor="#c6e0b4">	
						<th rowspan="3" style="text-align:center;width:120px;">Value</th>
						<th style="text-align:center;width:160px;" ><strong>MTD('.date('M-y', strtotime($curr_month)).')</strong></th>
						
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','').'</strong></th>
						
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],'').'</strong></th>
						
						<th style="text-align:center;background:#9ccc7c;" ><strong>'. ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')- ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'') + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],''))).'</strong></th>
						
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') -($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'') + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],''))).'</strong></th>
						
					 </tr>';
		$tbl_data .='<tr bgcolor="#c6e0b4">											
						<th style="text-align:center;width:160px;" ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','').'</strong></th>
						
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') - ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'') + $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],''))).'</strong></th>
						
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.( $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') - ( $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'') + $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],''))).'</strong></th>
						
					 </tr>';
		$tbl_data .='<tr bgcolor="#c6e0b4">											
						<th style="text-align:center;width:160px" ><strong>ITD(May-19)</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff('','').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff('','').'</strong></th>
						
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],'').'</strong></th>
						
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.( $this->leads_model->itd_no_of_leads_by_stage_month_staff('','') - ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],'') + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],'')) ).'</strong></th>
						
						<th style="text-align:center;background:#9ccc7c;" ><strong>'.($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') - ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],'') + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],''))).'</strong></th>
						
					 </tr>
					 <tr align="center">
						<th colspan="20" style="padding-top:5px;"></th>
					</tr>';
					$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
							
							$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
							
			$tbl_data .='<tr bgcolor="#ffe699" style="font-style: italic;">
						<th rowspan="3">Stage Wise %</th>
						<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0) .' %</td>
						
						<td style="text-align:center;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0) .' %</td>
						
						<td style="text-align:center;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0).' %</td>
						<td style="text-align:center;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0).' %</td>
						
						<td style="text-align:center;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round((($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]))*100),0) .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_no),0) .'%</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_value),0) .'%</td>
					</tr>';
					
					$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
							
					$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
							
							
			$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						<td style="text-align:center;background:#ffd34e;">'.round((100 - $ytd_pipline_no),0).'%</td>
						
						<td style="text-align:center;background:#ffd34e;">'.round((100 - $ytd_pipline_value),0).'%</td>
					</tr>';
					$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 );
							
					$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100);
							
		$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>ITD(May-19)</strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('','')/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $itd_pipline_no),0) .'%</td>
						
						<td style="text-align:center;background:#ffd34e;">'.  round((100 - $itd_pipline_value),0) .'%</td>
						
					</tr>
					';
		
		$regions = $this->db->get('tblregion')->result_array();
		foreach ($regions as $region) {
		$tbl_data .='<tr align="center">
						<th colspan="20"><br></th>
					</tr>';	
		$tbl_data .='<tr align="center">';
						if($region['region']=='North'){
			$tbl_data .='<th colspan="20" bgcolor="#00ffff">'.$region['region'].'</th>';				
							}else if($region['region']=='East'){
			$tbl_data .='<th colspan="20" bgcolor="#ff6699">'.$region['region'].'</th>';					
							}else if($region['region']=='South'){
			$tbl_data .='<th colspan="20" bgcolor="#cc66ff">'.$region['region'].'</th>';					
							}else if($region['region']=='West'){
			$tbl_data .='<th colspan="20" bgcolor="#66ffcc">'.$region['region'].'</th>';					
							}
		$tbl_data .='</tr>';
		$tbl_data .='<tr>	
					<th style="text-align:center;width:250px;" bgcolor="#f4b084" rowspan="2" colspan="2" width="150"><strong>Stages</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Total</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Identified</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Qualified</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Alignment &amp; Selection</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Selection</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Contract Signed</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Won</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Lost</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Open</strong></th>
				  </tr>
				  <tr>											
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
				  </tr>';
		$tbl_data .='<tr bgcolor="#bdd7ee">
						<th rowspan="3">Value</th>
						<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]))).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) )) .'</td>
						
					</tr>
					
					<tr bgcolor="#bdd7ee">
						<td><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ( $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]))).'</td>
						<td style="text-align:center;background:#7cc1ff;">'. ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]))).'</td>
					</tr>
					
					
					<tr bgcolor="#bdd7ee">
						<td><strong>ITD(May-19)</strong></td>
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])  - ( $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]))).'</td>
						<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) - ( $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]))).'</td>
						
						
					</tr>
					<tr align="center">
						<th colspan="20" style="padding-top:5px;"></th>
					</tr>';
					$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
							
					$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
							
			$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
						<th rowspan="3">Stage Wise %</th>
						<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_no),0) .'%</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_value),0) .'%</td>
					</tr>';
					
					$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
							
					$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
				
			$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $ytd_pipline_no),0).'%</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $ytd_pipline_value),0) .' %</td>
					</tr>';
					
					$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 );
							
					$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100);
					
			$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>ITD(May-19)</strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('','')/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
						<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
						<td style="text-align:center;background:#ffd34e;">'.round((100 - $itd_pipline_no),0).' %</td>
						
						<td style="text-align:center;background:#ffd34e;">'. round((100 - $itd_pipline_value),0) .' %</td>
					</tr>
					
					<tr align="center">
						<th colspan="20" style="padding-top:5px;"></th>
					</tr>';
					$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 );
							
					$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100);
			$tbl_data	.='<tr bgcolor="#f1dcdc">
						<th rowspan="3">Region Share</th>
						<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						<td style="text-align:center;background:#dec3c3;">'.  round((100 - $mtd_pipline_no),0) .' %</td>
						
						<td style="text-align:center;background:#dec3c3;">'.  round((100 - $mtd_pipline_value),0) .' %</td>
					</tr>';
					$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 );
							
					$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100);
							
			$tbl_data	.='<tr bgcolor="#f1dcdc">
						<td ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						<td style="text-align:center;background:#dec3c3;">'. round((100 - $ytd_pipline_no),0)  .' %</td>
						
						<td style="text-align:center;background:#dec3c3;">'. round((100 - $ytd_pipline_value),0) .' %</td>
					</tr>';
					$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 );
							
					$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100);
					
			$tbl_data	.='<tr bgcolor="#f1dcdc">
						<td ><strong>ITD(May-19)</strong></td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						<td style="text-align:center;background:#dec3c3;">'. round((100 - $itd_pipline_no),0) .' %</td>
						
						<td style="text-align:center;background:#dec3c3;">'. round((100 - $itd_pipline_value),0) .' %</td>
					</tr>';
		    }
				
		$tbl_data .= '</tbody></table>';
			 
		//echo $tbl_data;
		$lastdate = date('d-F-Y', strtotime("-1 days"));
		$subject = "Stage Summary Report - ".$lastdate;
		$to = get_option('stages_summary_daily_reports_to');
		$daily_status_reports_cc = get_option('stages_summary_daily_reports_cc');
			
		$array = explode(',', $daily_status_reports_cc);
		$commaCC = "".implode( " , ", $array). "";
		$ccc = explode(',', $commaCC);;
		
		$newcc=array();
		foreach($ccc as $value){
			/* if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{ */
				$newcc[]=$value;
			/* } */
		}
		
		//$this->sent_smtp__email('rajeev.gangwar@halonix.co.in', $subject, $tbl_data);
		$this->sent_smtp__email($to, $subject, $tbl_data,$newcc);
	}

	//================ Stage Summary Report Dynsm, NSM==========//	
	
	public function stage_mtdr_dynsm_nsm()
	{
		
		$curr_month = date('Y-m');
		//$curr_month = '2019-09';
		
		if ( date('m') > 6 ) {
			$year = date('Y') + 1;
		}
		else {
			$year = date('Y');
		}
		$fromyear = ($year-1);
		$toyear = $year;
		
		$fromyearmonth = $fromyear.'-04';
		$toyearmonth = $year.'-03';
		
		
		
		$this->db->order_by("id", "asc");
		$leadstage = $this->db->get('tblleadsstatus')->result_array();
		$tbl_data = '';	
		
		$query = $this->db->query('SELECT * FROM tblstaff where role IN(6, 8) AND active=1');
			$staffs =  $query->result_array();
			
			$this->db->order_by("id", "asc");
			$leadstage = $this->db->get('tblleadsstatus')->result_array();
			foreach ($staffs as $staff) {
			$tbl_data = '<table class="table border" border="1" style="font-size:13px;text-align:center;font-weight: bold;"><tbody>';
				$staff_id = $staff["staffid"];
				$staff_name = $staff["firstname"];
				$staff_email = $staff["email"];
				$reg = $staff["region"];
				
			$query = $this->db->query('SELECT * FROM tblregion where id IN(' . $reg . ')');
			$regions =  $query->result_array();
			foreach ($regions as $region) {
			$tbl_data .='<tr align="center">
							<th colspan="20">'.$staff_name.'<br></th>
						</tr>';	
			$tbl_data .='<tr align="center">';
							if($region['region']=='North'){
				$tbl_data .='<th colspan="20" bgcolor="#00ffff">'.$region['region'].'</th>';				
								}else if($region['region']=='East'){
				$tbl_data .='<th colspan="20" bgcolor="#ff6699">'.$region['region'].'</th>';					
								}else if($region['region']=='South'){
				$tbl_data .='<th colspan="20" bgcolor="#cc66ff">'.$region['region'].'</th>';					
								}else if($region['region']=='West'){
				$tbl_data .='<th colspan="20" bgcolor="#66ffcc">'.$region['region'].'</th>';					
								}
			$tbl_data .='</tr>';
			$tbl_data .='<tr>	
						<th style="text-align:center;width:250px;" bgcolor="#f4b084" rowspan="2" colspan="2" width="150"><strong>Stages</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Total</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Identified</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Qualified</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Alignment &amp; Selection</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Selection</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Contract Signed</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Won</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Lost</strong></th>
						<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Open</strong></th>
					  </tr>
					  <tr>											
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead<br>(Nos.)</strong></th>
						<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value(In Lakhs)</strong></th>
					  </tr>';
			$tbl_data .='<tr bgcolor="#bdd7ee">
							<th rowspan="3">Value</th>
							<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]))).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) )) .'</td>
							
						</tr>
						
						<tr bgcolor="#bdd7ee">
							<td><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ( $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]))).'</td>
							<td style="text-align:center;background:#7cc1ff;">'. ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]))).'</td>
						</tr>
						
						
						<tr bgcolor="#bdd7ee">
							<td><strong>ITD(May-19)</strong></td>
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
							<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
							
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])  - ( $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]))).'</td>
							<td style="text-align:center;background:#7cc1ff;">'.( $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) - ( $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]))).'</td>
							
							
						</tr>
						<tr align="center">
							<th colspan="20" style="padding-top:5px;"></th>
						</tr>';
						$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
								
						$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);
								
				$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
							<th rowspan="3">Stage Wise %</th>
							<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_no),0) .'%</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round((100 - $mtd_pipline_value),0) .'%</td>
						</tr>';
						
						$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
								
						$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
					
				$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
							<td ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>						
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round((100 - $ytd_pipline_no),0).'%</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round((100 - $ytd_pipline_value),0) .' %</td>
						</tr>';
						
						$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 );
								
						$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100);
						
				$tbl_data	.='<tr bgcolor="#ffe699" style="font-style: italic;">
							<td ><strong>ITD(May-19)</strong></td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('','')/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   .' %</td>
							<td style="text-align:center;background:#ffd34e;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) .' %</td>
							<td style="text-align:center;background:#ffd34e;">'.round((100 - $itd_pipline_no),0).' %</td>
							
							<td style="text-align:center;background:#ffd34e;">'. round((100 - $itd_pipline_value),0) .' %</td>
						</tr>
						
						<tr align="center">
							<th colspan="20" style="padding-top:5px;"></th>
						</tr>';
						$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 );
								
						$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100);
				$tbl_data	.='<tr bgcolor="#f1dcdc">
							<th rowspan="3">Region Share</th>
							<td ><strong>MTD('.date('M-y', strtotime($curr_month)).') </strong></td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
							<td style="text-align:center;background:#dec3c3;">'.  round((100 - $mtd_pipline_no),0) .' %</td>
							
							<td style="text-align:center;background:#dec3c3;">'.  round((100 - $mtd_pipline_value),0) .' %</td>
						</tr>';
						$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 );
								
						$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100);
								
				$tbl_data	.='<tr bgcolor="#f1dcdc">
							<td ><strong>YTD('.date('y', strtotime($fromyearmonth)).'-'.date('y', strtotime($toyearmonth)).')</strong></td>						
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
							<td style="text-align:center;background:#dec3c3;">'. round((100 - $ytd_pipline_no),0)  .' %</td>
							
							<td style="text-align:center;background:#dec3c3;">'. round((100 - $ytd_pipline_value),0) .' %</td>
						</tr>';
						$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 );
								
						$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100);
						
				$tbl_data	.='<tr bgcolor="#f1dcdc">
							<td ><strong>ITD(May-19)</strong></td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0 ).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0).' %</td>
							
							<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0)   .' %</td>
							<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
							
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
							<td style="text-align:center;background:#d0cece;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
							<td style="text-align:center;background:#dec3c3;">'. round((100 - $itd_pipline_no),0) .' %</td>
							
							<td style="text-align:center;background:#dec3c3;">'. round((100 - $itd_pipline_value),0) .' %</td>
						</tr>';
				}
				
			$tbl_data .= '</tbody></table>';
				 
			//echo $tbl_data;
			$lastdate = date('d-F-Y', strtotime("-1 days"));
			$subject = "Stage Summary Report - ".$lastdate;
			$to = get_option('stages_summary_daily_reports_to');
			$daily_status_reports_cc = get_option('stages_summary_daily_reports_cc');
				
			$array = explode(',', $daily_status_reports_cc);
			$commaCC = "".implode( " , ", $array). "";
			$ccc = explode(',', $commaCC);;
			
			$newcc=array();
			foreach($ccc as $value){
				/* if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
				{ */
					$newcc[]=$value;
				/* } */
			}
			
			$this->sent_smtp__email($staff_email, $subject, $tbl_data,'lms-noreply@halonix.co.in');
		}
	}
	
	
//=====================================================================================================================//






	//==================== MTD/YTD/ITD Report ==============//
	public function stage_mtdr(){
		
		//$curr_month = date('Y-m');
		$curr_month = '2019-09';
		
		if ( date('m') > 6 ) {
			$year = date('Y') + 1;
		}
		else {
			$year = date('Y');
		}
		$fromyear = ($year-1);
		$toyear = $year;
		
		$fromyearmonth = $fromyear.'-04';
		$toyearmonth = $year.'-03';
		
		
		
		$this->db->order_by("id", "asc");
		$leadstage = $this->db->get('tblleadsstatus')->result_array();
			
		$tbl_data = '<table class="table border" border="1"><tbody><tr>	
				<th style="text-align:center;width:150px;" bgcolor="#f47b34" rowspan="2"><strong>Stages</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Total</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Alignment &amp; Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Lost</strong></th>
			  </tr>
			  <tr>											
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
			 
		$tbl_data .='<tr>											
						<th style="text-align:center;" bgcolor="#56ff63"><strong>MTD ('.date('M-Y', strtotime($curr_month)).')</strong></th>
						
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','').'</strong></th>
						
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],'').'</strong></th>
					 </tr>';
		$tbl_data .='<tr>											
						<th style="text-align:center;" bgcolor="#56ff63"><strong>YTD ('.date('My', strtotime($fromyearmonth)).'-'.date('My', strtotime($toyearmonth)).')</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>50</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>4114</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>80</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>3345</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>118</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>2395</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>28</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>5391</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>32</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>349</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>18</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>266</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>14</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>904</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>340</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>16764</strong></th>
					 </tr>';
		$tbl_data .='<tr>											
						<th style="text-align:center;" bgcolor="#56ff63"><strong>ITD (May -19)</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff('','').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff('','').'</strong></th>
						
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],'').'</strong></th>
						<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],'').'</strong></th>
					 </tr>';
					 
		$tbl_data .='<tr align="center">
						<th colspan="17"></th>
					</tr>';
		
		$regions = $this->db->get('tblregion')->result_array();
		foreach ($regions as $region) {
			
		$tbl_data .='<tr align="center">
						<th colspan="17" bgcolor="#f47b34">'.$region['region'].'</th>
					</tr>';
		$tbl_data .='<tr>
						<td rowspan="2"><strong>MTD</strong></td>
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]).'</td>
						
					</tr>
					<tr>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) .' %</td>
						
					</tr>
					<tr>
						<td rowspan="2"><strong>YTD</strong></td>
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]).'</td>
						
					</tr>
					<tr>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) .' %</td>
						
					</tr>
					
					<tr>
						<td rowspan="2"><strong>ITD</strong></td>
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]).'</td>
						
						<td style="text-align:center;">'.$this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
						<td style="text-align:center;">'.$this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]).'</td>
					</tr>
					<tr>
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0 ).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0).' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0).' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
						
						<td style="text-align:center;">'. round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   .' %</td>
						<td style="text-align:center;">'. round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) .' %</td>
					</tr>';
		    }
				
		$tbl_data .= '</tbody></table>';
			 
		//echo $tbl_data;
		$lastdate = date('d-F-Y', strtotime("-1 days"));
		$subject = "Testing MTD/ITD/YTD Reports - ".$lastdate;
		$to = get_option('daily_status_reports_to');
		$daily_status_reports_cc = get_option('daily_status_reports_cc');
			
		$array = explode(',', $daily_status_reports_cc);
		$commaCC = "".implode( " , ", $array). "";
		$ccc = explode(',', $commaCC);;
		
		$newcc=array();
		foreach($ccc as $value){
			if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
		
	    $this->sent_smtp__email('rajeev.gangwar@halonix.co.in', $subject, $table_content,$newcc);
	    //$this->sent_smtp__email('rana.euam@gmail.com', $subject, $tbl_data);
	}
	
	/*
	public function stage()
	{
			$from_month = date('Y').'-04-30';
			$to_month = date('Y-m').'-15';
			$start    = (new DateTime($from_month))->modify('first day of this month');
			$end      = (new DateTime($to_month))->modify('first day of next month');
			$interval = DateInterval::createFromDateString('1 month');
			$period   = new DatePeriod($start, $interval, $end);
			$this->db->order_by("id", "asc");
			$leadstage = $this->db->get('tblleadsstatus')->result_array();

			$table_content_stage = "<table class='table border' border='1'>";
			$table_content_stage .= '<tr>	
				<th style="text-align:center;width:150px;" bgcolor="#f47b34" rowspan="2"><strong>Stages<br>/<br>Month</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Identified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Qualified</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Alignment & Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Selection</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Final Contract Signed</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Won</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Closed Lost</strong></th>
				<th style="text-align:center;" bgcolor="#f47b34" colspan="2"><strong>Total</strong></th>
			  </tr>
			  <tr>											
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead(Nos.)</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value(In Lakhs)</strong></th>
			  </tr>';
            $identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
			foreach ($period as $dt) {
				
				$month = $dt->format("Y-m");

				
				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,'');

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[0]['id'],$staff_id,'');

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[1]['id'],$staff_id,'');

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[2]['id'],$staff_id,'');

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[3]['id'],$staff_id,'');

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[4]['id'],$staff_id,'');

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[5]['id'],$staff_id,'');

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[6]['id'],$staff_id,'');

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,'');

				

			}

			$table_content_stage .= '<tr>											

				<th style="text-align:center;" bgcolor="#56ff63"><strong>Grand Total</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total_value.'</strong></th>

			  </tr>';
			$table_content_stage .='
					<tr align="center">
					 <th colspan="15"></th>
					 
				  </tr>';
			$regions = $this->db->get('tblregion')->result_array();
			foreach ($regions as $region) {	
				$identified_lead_total = 0;
				$qualified_lead_total = 0;
				$alignment_lead_total = 0;
				$final_selection_lead_total = 0;
				$final_contract_lead_total = 0;
				$close_won_lead_total = 0;
				$close_lost_lead_total = 0;
				$total_no_lead_total = 0;
			

				$identified_lead_total_value = 0;
				$qualified_lead_total_value = 0;
				$alignment_lead_total_value = 0;
				$final_selection_lead_total_value = 0;
				$final_contract_lead_total_value = 0;
				$close_won_lead_total_value = 0;
				$close_lost_lead_total_value = 0;
				$total_no_lead_total_value = 0;
						
			$table_content_stage .= '<tr align="center">
										<th colspan="17" bgcolor="#f47b34">'.$region["region"].'</th>
									</tr>';
									
			foreach ($period as $dt) {

				$month = $dt->format("Y-m");

				$table_content_stage .= '<tr>';

				$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,$region["id"]);

				$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[0]['id'],$staff_id,$region["id"]);

				$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[1]['id'],$staff_id,$region["id"]);

				$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[2]['id'],$staff_id,$region["id"]);

				$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[3]['id'],$staff_id,$region["id"]);

				$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[4]['id'],$staff_id,$region["id"]);

				$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[5]['id'],$staff_id,$region["id"]);

				$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[6]['id'],$staff_id,$region["id"]);

				$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,$region["id"]);

				$table_content_stage .= "<td ><strong>".date('M, Y', strtotime($dt->format("Y-m-01")))."</strong></td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->no_of_leads_by_stage_month_staff($dt->format("Y-m"),$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_no_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,$region["id"])."</td>";

				 $table_content_stage .= "<td style='text-align:center;'>".$this->leads_model->total_value_of_leads_by_stage_month_staff($dt->format("Y-m"),$staff_id,$region["id"])."</td>";

				 $table_content_stage .= '</tr>';

			}

			$table_content_stage .= '<tr>											

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$region["region"].' Total</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$identified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$qualified_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$alignment_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_selection_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$final_contract_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_won_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$close_lost_lead_total_value.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total.'</strong></th>

				<th style="text-align:center;" bgcolor="#56ff63"><strong>'.$total_no_lead_total_value.'</strong></th>

			  </tr>';

				}	

		$table_content_stage .= "</table>";
		
		
		echo $table_content_stage;
		
		$lastdate = date('Y-m-d', strtotime("-1 days"));
		$subject = "Stages Wise Daily Reports - ".$lastdate;
		
		$to = get_option('stages_wise_daily_reports_to');
		$stages_wise_daily_reports_cc = get_option('stages_wise_daily_reports_cc');
		
		$array = explode(',', $stages_wise_daily_reports_cc);
		$commaCC = "".implode( " , ", $array). "";
		$ccc = explode(',', $commaCC);;
		
		$newcc=array();
		foreach($ccc as $value){
			if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
		$this->sent_smtp__email($to, $subject, $table_content_stage,$newcc);
	   
	}
	
	*/
	
	
	public function sum()
	{
		echo $lastMonth = date('Y-m', strtotime("-1 month"));	
		echo $this->clients_model->no_of_cust_bymonth($lastMonth,68);
		//$this->leads_model->value_of_leads_bymonth($last2Month,'61');
	}
	public function getcc()
	{
		
		$stages_wise_daily_reports_to = get_option('stages_wise_daily_reports_to');
		$daily_status_reports_cc = get_option('stages_wise_daily_reports_cc');
		$array = explode(',', $daily_status_reports_cc);
		
		$commaList = "'".implode( "' , ' ", $array). "'";
		echo $commaList;

	}
	public function sent_smtp__email($to_email, $subject, $message,$cc='')
    {
        
        // Simulate fake template to be parsed
        $template           = new StdClass();
        //$template->message  = get_option('email_header') . ' ' . $message . get_option('email_footer');
        $template->message  = $message;
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
	
	
	
public function clear_all_cache()
{
    $CI =& get_instance();
    $path = $CI->config->item('cache_path');

    echo $cache_path = ($path == '') ? APPPATH.'cache/' : $path;

    $handle = opendir($cache_path);
    while (($file = readdir($handle))!== FALSE) 
    {
        //Leave the directory protection alone
        if ($file != '.htaccess' && $file != 'index.html')
        {
           @unlink($cache_path.'/'.$file);
        }
    }
    closedir($handle);       
}
    
	
}
