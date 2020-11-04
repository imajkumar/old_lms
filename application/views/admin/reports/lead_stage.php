<?php init_head(); 

?>
<style>
   .main{ 
   background: #fbfdff;
   }
   .panel-body.bottom-transaction {
   background: #fff !important; 
   }
   .panel_s .panel-body {
   border: 1px solid #fff !important;
   }
   .table>tbody>tr>td, .table>tfoot>tr>td {
    padding: 6px 1px 1px 1px;
    text-align: center;
    border: 1px solid !important;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border: 1px solid #000000 !important;
}

</style>
<div id="wrapper">
   <div class="content">
      
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Stage Wise Reports</h4>                    
                  </div>                  
				  <div class="col-md-12">
				  <form method="post">
				  
					<div class="col-md-2 leads-filter-column ">
					  <h5>Zone:</h5>
						 <?php 
						 $regions = $this->db->get('tblregion')->result_array();
						 ?>
						<select <?php if(get_staff_role() == 1 || get_staff_role() == 3 || get_staff_role() == 5){ echo 'disabled'; }else{ echo 'required'; } ?>  id="region_id" name="region_id" class="form-control">
							<option value="--Select--">--Select--</option>
							
							<?php 
							$sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
							$region_staff = $this->db->query($sql)->row()->region;
							
							$arruser = "id IN(".$region_staff.")";
							$this->db->select()->from('tblregion');
							if(get_staff_role() < 9 || !is_admin())
							$this->db->where($arruser);						
							$query = $this->db->get();
							$region = $query->result_array();
							foreach ($region as $regiond) { 
							?>
							
							<option <?php if($regiond['id']==$this->input->post('region_id')){ echo 'selected'; } ?> value="<?php echo $regiond['id'] ?>"><?php echo $regiond['region'] ?></option>
							<?php } ?>
						</select>
					  </div> 
					<div class="col-md-2">
						<h5>Staff:</h5>
						  <?php 
							
							 if(!($this->input->post('region_id'))){
								
								$this->db->select()->from('tblstaff');
								if(get_staff_role() == 2 || get_staff_role() == 5 || get_staff_role() == 6 || get_staff_role() == 8){
									$where = 'is_not_staff=0 AND ( CONCAT(",", reporting_to, ",") LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", reporting_to, ",")  LIKE "%,'.get_staff_user_id().',%" )';
									$this->db->where($where);
								}else if(get_staff_role() == 3){
									$where = 'is_not_staff=0 AND reporting_manager = "'.get_staff_user_id().'"';
									$this->db->where($where);
								} 
								$query = $this->db->get();
								$staff = $query->result_array();
							}else if($this->input->post('region_id') != '--Select--'){
						 		$this->db->select()->from('tblstaff');
								$condt = "region IN(".$this->input->post('region_id').") AND active = 1";
								$this->db->where($condt);
								$query = $this->db->get();
								$staff = $query->result_array();
							}else{
								
								$this->db->select()->from('tblstaff');
								if(get_staff_role() == 2 || get_staff_role() == 5 || get_staff_role() == 6 || get_staff_role() == 8){
									$where = 'is_not_staff=0 AND ( CONCAT(",", reporting_to, ",") LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", reporting_to, ",")  LIKE "%,'.get_staff_user_id().',%" )';
									$this->db->where($where);
								}else if(get_staff_role() == 3){
									$where = 'is_not_staff=0 AND reporting_manager = "'.get_staff_user_id().'"';
									$this->db->where($where);
								} 
								$query = $this->db->get();
								$staff = $query->result_array();
							}
							$selected = array();
						  if($this->input->post('view_assigned')){
							array_push($selected,$this->input->post('view_assigned'));
						  }
							 
						 ?>
						  
							<select class="form-control" name="view_assigned" id="view_assigned">
									<option value="">--Select User--</option>
									
								<?php foreach ($staff as $staffd) { 
								
								$where = "assigned = '".$staffd["staffid"]."'";
								$this->db->select()->from('tblleads');
								$this->db->where($where);
								$query = $this->db->get();
								$ifzsmleadvalue = $query->num_rows();
								if($ifzsmleadvalue > 0)
								{
							?>
								<option <?php if($staffd["staffid"]==$this->input->post('view_assigned')){ echo 'selected'; } ?> value="<?php echo $staffd["staffid"]; ?>"><?php echo $staffd["firstname"].' '.$staffd["lastname"].' - '.$staffd['emp_code']; ?></option>
							<?php } } ?>
						   </select>
						 
					</div>
			   
					<div class="col-md-4">
						<h5>Filter From:</h5>
						<div class="col-md-6">
							<select class="selectpicker" name="from-months" required data-width="100%" data-none-selected-text="Select Month" tabindex="-98">
							   <option value="">Select Month</option>
							   <option <?php if($this->input->post('from-months')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('from-months')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('from-months')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('from-months')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('from-months')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('from-months')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('from-months')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('from-months')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('from-months')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('from-months')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('from-months')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('from-months')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							</select>
						
						</div>
						<div class="col-md-6">
							<select class="selectpicker" name="from-years" required data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
							   <option value="">Nothing selected</option>
							  
								<option <?php if($this->input->post('from-years')=='2019'){ echo 'selected'; }else if($this->input->post('from-years')=='') { echo 'selected'; } ?> value="2019">2019</option>
								<option <?php if($this->input->post('from-years')=='2020'){ echo 'selected'; } ?>  value="2020">2020</option>
								<option <?php if($this->input->post('from-years')=='2021'){ echo 'selected'; } ?>  value="2021">2021</option>
								<option <?php if($this->input->post('from-years')=='2022'){ echo 'selected'; } ?>  value="2022">2022</option>
								<option <?php if($this->input->post('from-years')=='2023'){ echo 'selected'; } ?>  value="2023">2023</option>
								<option <?php if($this->input->post('from-years')=='2024'){ echo 'selected'; } ?>  value="2024">2024</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<h5>Filter To:</h5>
						<div class="col-md-6">
							<select class="selectpicker" name="to-months" required data-width="100%" data-none-selected-text="Select Month" tabindex="-98">
							   <option value="">Select Month</option>
							   <option <?php if($this->input->post('to-months')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('to-months')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('to-months')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('to-months')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('to-months')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('to-months')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('to-months')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('to-months')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('to-months')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('to-months')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('to-months')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('to-months')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							</select>
						
						</div>
						<div class="col-md-6">
							<select class="selectpicker" name="to-years" required data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
							   <option value="">Nothing selected</option>
							   
								<option <?php if($this->input->post('to-years')=='2019'){ echo 'selected'; }else if($this->input->post('to-years')=='') { echo 'selected'; } ?> value="2019">2019</option>
								<option <?php if($this->input->post('to-years')=='2020'){ echo 'selected'; } ?>  value="2020">2020</option>
								<option <?php if($this->input->post('to-years')=='2021'){ echo 'selected'; } ?>  value="2021">2021</option>
								<option <?php if($this->input->post('to-years')=='2022'){ echo 'selected'; } ?>  value="2022">2022</option>
								<option <?php if($this->input->post('to-years')=='2023'){ echo 'selected'; } ?>  value="2023">2023</option>
								<option <?php if($this->input->post('to-years')=='2024'){ echo 'selected'; } ?>  value="2024">2024</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-12 text-right">
						<br>
						<br>
						<button class="btn btn-success" type="submit">Apply</button>
					</div>
						
					</form>
                    
				  </div>
				  <div class="col-md-12">
					 <br>
                     
					 <?php
					 //$region_id = $this->input->post('zone_name');
					 $region_id = $this->input->post('region_id');
					 $staff_id = $this->input->post('view_assigned');
					 $from_year = $this->input->post('from-years');
					 $to_year = $this->input->post('to-years');
					 $from_month = $this->input->post('from-years').'-'.$this->input->post('from-months').'-15';
					 $to_month = $this->input->post('to-years').'-'.$this->input->post('to-months').'-27';
					 
					if($to_month !='--27' || $from_month !='--15'){
						 
						$start    = (new DateTime($from_month))->modify('first day of this month');
						$end      = (new DateTime($to_month))->modify('first day of next month');
						$interval = DateInterval::createFromDateString('1 month');
						$period   = new DatePeriod($start, $interval, $end);
						
						 $this->db->order_by("id", "asc");
						 $leadstage = $this->db->get('tblleadsstatus')->result_array();
						
						echo "<table class='table border' border='1'>";
						echo '<tr>											

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
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							
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
						
						$from_month = $this->input->post('from-months');
						$to_month = $this->input->post('to-months');
					 
						$currmonth = $to_month;
						//$rowspan = ($currmonth -4)+ 1;
						
						//=======================new
						foreach ($period as $dt) {
							$month = $dt->format("Y-m");

					/* 
						
						for($m=$currmonth; $m >= $from_month; $m--){
						
						 $month = date('Y-m', mktime(0, 0, 0, $m, 1));
						 */
							
							$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id);
							$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id);
							$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id);
							$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id);
							$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id);
							$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id);
							$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id);
							$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region_id);
							
							
							$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id);
							$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id);
							$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id);
							$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id);
							$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id);
							$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id);
							$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id);
							$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region_id);
							
							
							echo "<td ><strong>".date('M, Y', strtotime($month))."</strong></td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region_id)."</td>";
							 echo '</tr>';
						}
						
						echo '<tr>											
							<th style="text-align:center;" bgcolor="#56ff63"><strong>Total</strong></th>
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
						
						echo "</table>";
					 
					 }
					 else{
						
						if(date('m') < 4){
							$lastyear = date('Y')-1;
						}else{
							$lastyear = date('Y');
						}
						
						$from_month = $lastyear.'-04-30';
						$to_month = date('Y-m').'-15';
					 
						$start    = (new DateTime($from_month))->modify('first day of this month');
						$end      = (new DateTime($to_month))->modify('first day of next month');
						$interval = DateInterval::createFromDateString('1 month');
						$period   = new DatePeriod($start, $interval, $end);
						
						 $this->db->order_by("id", "asc");
						 $leadstage = $this->db->get('tblleadsstatus')->result_array();
						
						echo "<table class='table border' border='1'>";
						echo '<tr>											

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
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead</strong></th>
							<th style="text-align:center;width:100px;" bgcolor="#f47b34"><strong>Lead Value</strong></th>
							
						  </tr>';
						  
						$region_id ='--Select--';
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
						
						$currmonth = date('m');
						//$rowspan = ($currmonth -4)+ 1;
						
						//=======================new
						foreach ($period as $dt) {
							$month = $dt->format("Y-m");
							
						/* $rowspan = ($currmonth -4)+ 1;
						for($m=$currmonth; $m>=4; $m--){
						
						$month = date('Y-m', mktime(0, 0, 0, $m, 1));
						/* foreach ($period as $dt) {
							$month = $month; */ 
							echo '<tr>';
							
							$identified_lead_total = $identified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id);
							$qualified_lead_total = $qualified_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id);
							$alignment_lead_total = $alignment_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id);
							$final_selection_lead_total = $final_selection_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id);
							$final_contract_lead_total = $final_contract_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id);
							$close_won_lead_total = $close_won_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id);
							$close_lost_lead_total = $close_lost_lead_total + $this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id);
							$total_no_lead_total = $total_no_lead_total + $this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region_id);
							
							
							$identified_lead_total_value = $identified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id);
							$qualified_lead_total_value = $qualified_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id);
							$alignment_lead_total_value = $alignment_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id);
							$final_selection_lead_total_value = $final_selection_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id);
							$final_contract_lead_total_value = $final_contract_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id);
							$close_won_lead_total_value = $close_won_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id);
							$close_lost_lead_total_value = $close_lost_lead_total_value + $this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id);
							$total_no_lead_total_value = $total_no_lead_total_value + $this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region_id);
							
							
							echo "<td ><strong>".date('M, Y', strtotime($month))."</strong></td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[0]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[1]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[2]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[3]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[4]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[5]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->no_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->value_of_leads_by_stage_month_staff($month,$leadstage[6]['id'],$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->total_no_of_leads_by_stage_month_staff($month,$staff_id,$region_id)."</td>";
							 echo "<td>".$this->leads_model->total_value_of_leads_by_stage_month_staff($month,$staff_id,$region_id)."</td>";
							 echo '</tr>';
						}
						
						echo '<tr>											
							<th style="text-align:center;" bgcolor="#56ff63"><strong>Total</strong></th>
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
						
						echo "</table>";
					 
					 } 
					 
					 
					 ?>
					 
					 
                  </div>
				
			   </div>
              
            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>
<script>
$(document).on('change', '#region_id', function (e) {
    var zone = $(this).val();
	//alert(zone);
	 $('#zone_name').val($("#region_id :selected").text());
	 $('#view_assigned').html("");
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/staff/getStaffByZone",
		  data: {'zone': zone},
		  dataType: "json",
		  success: function (data) {	
		     console.log(data);
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.staffid + ">" + obj.firstname+' '+ obj.lastname+' - '+obj.emp_code +"</option>";
				});
			  $('#view_assigned').append(div_data);
		  }
		});  
  });
  
  $('form').submit(function () {
		 $('select').removeAttr('disabled');
		
	});
</script>
</body>
</html>