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
	public function counting()
    {
		$table_content ='<table class="table" border="1" width="100%">
		   <thead>
			  <tr role="row">
				 <th rowspan="1" colspan="3"> </th>
				 <th bgcolor="#fd4" rowspan="1" colspan="4">Customer</th>
				 <th bgcolor="#56ff63" rowspan="1" colspan="4">Lead</th>
				 <th bgcolor="#56FED4" rowspan="1" colspan="4">Lead Value(In Lakhs)</th>
				
			  </tr>
			   
		   </thead>
		   <tbody>';
		 $table_content .='
				<tr role="row">
				 <th>User</th>
				 <th>Last Login</th>
				 <th>Reporting Head</th>
				 <th bgcolor="#fd4">'.date('M', strtotime('-2 month')).'-'.date('Y').'</th>
				 <th bgcolor="#fd4">'.date('M', strtotime('-1 month')).'-'.date('Y').'</th>
				 <th bgcolor="#fd4">'.date('M', strtotime('-0 month')).'-'.date('Y').'</th>
				 <th bgcolor="#fd4">'.date('d-F-Y', strtotime("-1 days")).'</th>
				 
				 <th bgcolor="#56ff63">'.date('M', strtotime('-2 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56ff63">'.date('M', strtotime('-1 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56ff63">'.date('M', strtotime('-0 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56ff63">'.date('d-F-Y', strtotime("-1 days")).'</th>
				
				 <th bgcolor="#56FED4">'.date('M', strtotime('-2 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56FED4">'.date('M', strtotime('-1 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56FED4">'.date('M', strtotime('-0 month')).'-'.date('Y').'</th>
				 <th bgcolor="#56FED4">'.date('d-F-Y', strtotime("-1 days")).'</th>
				
				</tr>';  
		$thisMonth = date('Y-m');	
		$lastMonth = date('Y-m', strtotime("-1 month"));	
		$last2Month = date('Y-m', strtotime("-2 month"));	
		$last2MonthDate = date('Y-m-01', strtotime("-2 month"));	
		$lastdate = date('Y-m-d', strtotime("-1 days"));
			
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
						<th colspan="3" bgcolor="#f47b34">'.$region["region"].'</th>
						<th bgcolor="#f47b34">'.$last2Monthcount.'</th>
						<th bgcolor="#f47b34">'.$lastMonthcount.'</th>
						<th bgcolor="#f47b34">'.$thisMonthcount.'</th>
						<th bgcolor="#f47b34">'.$lastdatecount.'</th>
						
						<th bgcolor="#f47b34">'.$last2monthcount_lead.'</th>
						<th bgcolor="#f47b34">'.$lastmonthcount_lead.'</th>
						<th bgcolor="#f47b34">'.$thismonthcount_lead.'</th>
						<th bgcolor="#f47b34">'.$lastdatecount_lead.'</th>
						
						<th bgcolor="#f47b34">'.$last2monthcount_lead_sum.'</th>
						<th bgcolor="#f47b34">'.$lastmonthcount_lead_sum.'</th>
						<th bgcolor="#f47b34">'.$thismonthcount_lead_sum.'</th>
						<th bgcolor="#f47b34">'.$lastdatecount_lead_sum.'</th>
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
		}
		$table_content .='
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
				  </tr>';
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
		
		$commaCC = "".implode( ",", $array). "";
		$ccc = explode(',', $commaCC);
		
		$newcc=array();
		foreach($ccc as $value){
			//if(!preg_match('/^([\w-\.]+@(?=halonix.co.in)([\w-]+\.)+[\w-]{2,6})?$/',$value))
			if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
	    $this->sent_smtp__email('sameer.jindal@halonix.co.in', $subject, $table_content,$newcc);
		
	/*----------------------- Stages Wise Daily Reports -------------------------*/		
		$table_content_stage = '<table class="table" border="1" width="100%">
                        <thead style="">
                           <tr>
							<th style="text-align:center;" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#56ff63" colspan="4"><strong>Lead</strong></th>
							<th style="text-align:center;" bgcolor="#56FED4" colspan="4"><strong>Lead Value(In Lakhs)</strong></th>
						  </tr>
						  <tr>
								<td style="text-align:center;" bgcolor="#56ff63"><strong>'. date('M', strtotime('-2 month')).'-'.date('Y') .'</strong></td>
								<td style="text-align:center;"bgcolor="#56ff63"><strong>'. date('M', strtotime('-1 month')).'-'.date('Y').'</strong></td>
								<td style="text-align:center;" bgcolor="#56ff63"><strong>'. date('M', strtotime('-0 month')).'-'.date('Y') .'</strong></td>
								<td bgcolor="#56ff63"><strong>Total</strong></td>
								<td style="text-align:center;" bgcolor="#56FED4"><strong>'. date('M', strtotime('-2 month')).'-'.date('Y') .'</strong></td>
								<td style="text-align:center;" bgcolor="#56FED4"><strong>'. date('M', strtotime('-1 month')).'-'.date('Y') .'</strong></td>
								<td style="text-align:center;" bgcolor="#56FED4"><strong>'. date('M', strtotime('-0 month')).'-'.date('Y') .'</strong></td>
								<td bgcolor="#56FED4"><strong>Total</strong></td>
							 </tr>
                        </thead>
                        <tbody>';
							
							$thisMonth = date('Y-m');	
							$lastMonth = date('Y-m', strtotime("-1 month"));	
							$last2Month = date('Y-m', strtotime("-2 month"));	
							$lastdate = date('Y-m-d', strtotime("-1 days"));
							
							$this->db->order_by("id", "asc");
							$leadstage = $this->db->get('tblleadsstatus')->result_array();
							
							$last2Monthno_of_leadsTotal = 0;
							$lastMonthno_of_leadsTotal = 0;
							$thisMonthno_of_leadsTotal = 0;
							$lastdateno_of_leadsTotal = 0;
							
							$last2Monthvalue_of_leadsTotal = 0;
							$lastMonthvalue_of_leadsTotal = 0;
							$thisMonthvalue_of_leadsTotal = 0;
							$lastdatevalue_of_leadsTotal = 0;
							
							$leadsTotal_of_total = 0;
							$leadsvalue_of_total = 0;
							
							  foreach($leadstage as $data_l)
                              {
                              	
								$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]);
								$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]);
								$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]);				
								
								$leadsTotal = $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]) + $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]) + $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]);
								
								$leadsTotal_of_total = $leadsTotal_of_total + $leadsTotal;
								
								$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]);
								$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]);
								$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]);	

								$leadsAmountTotal = $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]);
								
								$leadsvalue_of_total = $leadsvalue_of_total + $leadsAmountTotal;
								
		$table_content_stage .= '<tr>
									<td style="text-align:left;">'. $data_l['name'] .'</td>
									<td style="text-align:center;">'. $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $leadsTotal .'</td>
									
									<td style="text-align:center;">'. $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]) .'</td>
									<td style="text-align:center;">'. $leadsAmountTotal .'</td>
									
								  </tr>';
						   
                              
                              } 
		$table_content_stage .= '<tr>
									<td style="text-align:left;"><strong>Total</strong></td>
									<td style="text-align:center;"><strong>'. $last2Monthno_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $lastMonthno_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $thisMonthno_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $leadsTotal_of_total .'</strong></td>
									<td style="text-align:center;"><strong>'. $last2Monthvalue_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $lastMonthvalue_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $thisMonthvalue_of_leadsTotal .'</strong></td>
									<td style="text-align:center;"><strong>'. $leadsvalue_of_total .'</strong></td>
									
								  </tr>
                        </tbody>
                     </table>';
        $table_content_stage .='
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
		
		//echo $table_content_stage;
		
		$lastdate = date('Y-m-d', strtotime("-1 days"));
		$subject = "Stages Wise Daily Reports - ".$lastdate;
		
		$to = get_option('stages_wise_daily_reports_to');
		$stages_wise_daily_reports_cc = get_option('stages_wise_daily_reports_cc');
		$array = explode(',', $stages_wise_daily_reports_cc);
		
		$commaCC = "'".implode( "' , ' ", $array). "'";
		$ccc = array($commaCC);
		
		$newcc=array();
		foreach($ccc as $value){
			//if(!preg_match('/^([\w-\.]+@(?=halonix.co.in)([\w-]+\.)+[\w-]{2,6})?$/',$value))
			if(substr($value, strpos($value, "@") + 1) == 'halonix.co.in')
			{
				$newcc[]=$value;
			}
		}
	   // $this->sent_smtp__email($to, $subject, $table_content_stage,$newcc);
		
		
    }
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
