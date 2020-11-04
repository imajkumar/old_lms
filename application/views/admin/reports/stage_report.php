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
   tr, td, th{
   border: 1px solid #000 !important;
   border-top: 1px solid #000 !important;
   }
   .table>thead>tr>th{
	   border-top: 1px solid #000!important;
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
					<div class="col-md-2">
						<h5>Select Region:</h5>
							<select class="selectpicker" name="from-region" id="form-filter-zone" required data-width="100%" data-none-selected-text="Select region" tabindex="-98">
							   <option value="">Select Region</option>
							   <option <?php if($this->input->post('from-region')=='all_region'){ echo 'selected'; } ?>  value="all_region">All Region</option>
								<option <?php if($this->input->post('from-region')=='pan_india'){ echo 'selected'; } ?>  value="pan_india">Regions</option>
								
							</select>
						
					</div>
					<div class="col-md-2 leads-filter-column <?php if($this->input->post('from-region') !='pan_india'){ echo 'hidden'; } ?>" id="zone-div">
					  <h5>Zone:</h5>
						 <?php 
						  $regions = $this->db->get('tblregion')->result_array();
						  if($this->input->post('region_id')){
							  $regionids = implode(',',$this->input->post('region_id'));
							  $explode_id = array_map('intval', explode(',', $regionids));
						  }
						 ?>
						<select <?php if(get_staff_role() == 1 || get_staff_role() == 3 || get_staff_role() == 5){ echo 'disabled'; } ?> required name="region_id[]" multiple id="region_id" class="form-control selectpicker" >
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
							<?php $selected = in_array($regiond['id'],$explode_id) ? " selected " : null; ?>
							<option <?php echo $selected; ?> value="<?php echo $regiond['id'] ?>"><?php echo $regiond['region'] ?></option>
							<?php } ?>
						</select>
					  </div>
					<div class="col-md-2 leads-filter-column <?php if($this->input->post('from-region') !='pan_india'){ echo 'hidden'; } ?>" id="staff-div">
						  <h5>Staff:</h5>
						   <input type="hidden" name="zone_name" id="zone_name" value="--Select--" />
						   <?php if(get_staff_role() == 1 ){ ?>
						   <select id="view_assigned" name="view_assigned" class="form-control">
								<option value="<?php echo get_staff_user_id(); ?>"><?php echo get_staff_full_name(); ?></option>
						   </select>
						   
						   <?php }else{ 
							
							/* echo $this->input->post('region_id');*/
								
								if($this->input->post('region_id')){
									$regionids = implode(',',$this->input->post('region_id'));
									$this->db->select()->from('tblstaff');
									$condt = "region IN(".$regionids.") AND active = 1";
									$this->db->where($condt);
									$query = $this->db->get();
									$staff = $query->result_array();
								} 
						   ?>
							
						  <select id="view_assigned" name="view_assigned" class="form-control <?php if(get_staff_role() == 1 ){ echo 'hide'; } ?>">
							<option value="">--Select--</option>
							<?php 
							
								foreach ($staff as $staffd) { 
							    
								$arr_user = "reportingto LIKE '%".$staffd["staffid"]."%' OR assigned = '".$staffd["staffid"]."'";
								$this->db->select()->from('tblleads');
								$this->db->where($arr_user);
								$query = $this->db->get();
								$ifzsmleadvalue = $query->num_rows();
								
								
								if($ifzsmleadvalue > 0)
								{
							?>
								<option <?php if($staffd["staffid"]==$this->input->post('view_assigned')){ echo 'selected'; } ?> value="<?php echo $staffd["staffid"]; ?>"><?php echo $staffd["firstname"].' '.$staffd["lastname"].' - '.$staffd['emp_code']; ?></option>
							<?php 
								} 
							} 
							?>
						   </select>
						   <?php } ?>
						  </div>
					<div class="col-md-4">
						<h5>Select MTD:</h5>
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
					
					<div class="col-md-2 text-right">
						<br>
						<br>
						<button class="btn btn-success" type="submit">Apply</button>
					</div>
						
					</form>
                    
				  </div>
				  
				  <div class="col-md-12">
                     <br>
                     <table class="table <?php if(($this->input->post('from-region')=='pan_india') || ($this->input->post('view_assigned') != '')){ echo 'hidden'; } ?>"  style="text-align:center;">
                        
                        <tbody>
						<?php 
						   
						   $sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
							$region_staff = $this->db->query($sql)->row()->region;
							if($this->input->post('from-region')=='pan_india'){ 
								$arruser = "id IN(".$regionids.")";
							}else{
								$arruser = "id IN(".$region_staff.")";
								
							}
							$this->db->select()->from('tblregion');
							if(get_staff_role() < 9 || !is_admin())
								$this->db->where($arruser);						
							$query = $this->db->get();
							$regions = $query->result_array();
							
						?>
						
                           <?php
							$tbl_data ='<br>';	
							$tbl_data .='<tr align="center">';
										
							$tbl_data .='<th colspan="18" bgcolor="#f58a4c">All Regions</th>';				
											
							$tbl_data .='</tr>';
							
							echo $tbl_data;
							if($this->input->post('from-months') !=''){
								$filteryear = $this->input->post('from-years');
								$filtermonth = $this->input->post('from-months');
								
								$curr = $filteryear.'-'.$filtermonth;
								$thisMonth = date('Y-m', strtotime("$curr -1 month"));	
								$lastMonth = date('Y-m', strtotime("$curr -2 month"));	
								$last2Month = date('Y-m', strtotime("$curr -3 month"));	 
								
								 if($this->input->post('view_assigned') !=''){
									$staff_id = $this->input->post('view_assigned');
								 }else{
									 $staff_id = get_staff_user_id();
								
								 }
								$region_id ='--Select--';
							}else{
								
								$thisMonth = date('Y-m', strtotime("-1 month"));	
								$lastMonth = date('Y-m', strtotime("-2 month"));	
								$last2Month = date('Y-m', strtotime("-3 month"));	 
								
								$region_id ='--Select--';
								$staff_id = get_staff_user_id();
							}
							if($this->input->post('from-months') !=''){
						?>
						<tr>
							<th width="200px" style="text-align:center;" bgcolor="#f4b084" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($last2Month)); ?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($lastMonth)) ;?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($thisMonth)) ;?></strong></th>
							
						  </tr>
						<?php }else{ ?>
						<tr>
							<th width="200px" style="text-align:center;" bgcolor="#f4b084" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($last2Month)); ?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($lastMonth)) ;?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($thisMonth)) ;?></strong></th>
							
						  </tr>
						<?php } ?>
						 <tr bgcolor="#f4b084">
							<td width='100px'>Lead(Nos.)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead(Nos.)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead(Nos.)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
						  </tr>	
							
					<?php 
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
							
							//$region_id ='--Select--';
							
							//echo $thisMonth.' '.''.$region_id;
							$this->db->order_by("id", "asc");
							$leadstage = $this->db->get('tblleadsstatus')->result_array();
                              foreach($leadstage as $data_l)
                              {
                              	$leadsTotal = 0;
                              	$leadsAmountTotal = 0;
			
								$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id);
								$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id);
								$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id);				
								
								$leadsTotal = $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'','') + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id) + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id);
								
								$leadsTotal_of_total = $leadsTotal_of_total + $leadsTotal;
								
								$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id);
								$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id);
								$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id);			
								
								$leadsAmountTotal = $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id);
								
								$leadsvalue_of_total = $leadsvalue_of_total + $leadsAmountTotal;
								
                              ?>
								
								<tr >
									<td style="text-align:left;"><?php echo $data_l['name']; ?></td>
									
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id) ?></td>
								
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region_id)) * 100 ,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region_id)) * 100 ,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region_id) * $data_l["weighted"]) / 100 ,0); ?></td>
								<?php } ?>
								
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id); ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region_id)) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region_id)) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region_id) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id);?></td>
									
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id) ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region_id)) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region_id)) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region_id) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
				
								  </tr>
						   <?php $i++; 
                           
                              }  
						   ?>
							<tr align="center">
								<th bgcolor="#f58a4c">Total</th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthno_of_leadsTotal; ?></th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
							</tr>
					</table>
					
					<?php
						   
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
													   
						    $sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
							$region_staff = $this->db->query($sql)->row()->region;
							if($this->input->post('from-region')=='pan_india'){ 
								$arruser = "id IN(".$regionids.")";
							}else{
								$arruser = "id IN(".$region_staff.")";
								
							}
							$this->db->select()->from('tblregion');
							if(get_staff_role() < 9 || !is_admin())
								$this->db->where($arruser);						
							$query = $this->db->get();
							$regions = $query->result_array();
							
							foreach ($regions as $region) {
					?>
			<table class="table <?php if(($this->input->post('from-region') != 'pan_india') || ($this->input->post('view_assigned') != '')){ echo 'hidden'; } ?>" style="text-align:center;">
					<tbody>
						<?php
							
							$tbl_data ='<tr align="center">';
										if($region['region']=='North'){
							$tbl_data .='<th colspan="18" bgcolor="#00ffff">'.$region['region'].'</th>';				
											}else if($region['region']=='East'){
							$tbl_data .='<th colspan="18" bgcolor="#ff6699">'.$region['region'].'</th>';					
											}else if($region['region']=='South'){
							$tbl_data .='<th colspan="18" bgcolor="#cc66ff">'.$region['region'].'</th>';					
											}else if($region['region']=='West'){
							$tbl_data .='<th colspan="18" bgcolor="#66ffcc">'.$region['region'].'</th>';					
											}
							$tbl_data .='</tr>';
			
							echo $tbl_data;
							if($this->input->post('from-months') !=''){
								$filteryear = $this->input->post('from-years');
								$filtermonth = $this->input->post('from-months');
								
								$curr = $filteryear.'-'.$filtermonth;
								$thisMonth = date('Y-m', strtotime("$curr -1 month"));	
								$lastMonth = date('Y-m', strtotime("$curr -2 month"));	
								$last2Month = date('Y-m', strtotime("$curr -3 month"));	 
								
								 if($this->input->post('view_assigned') !=''){
									$staff_id = $this->input->post('view_assigned');
								 }else{
									 $staff_id = get_staff_user_id();
								
								 }
								
							}else{
								
								$thisMonth = date('Y-m', strtotime("-1 month"));	
								$lastMonth = date('Y-m', strtotime("-2 month"));	
								$last2Month = date('Y-m', strtotime("-3 month"));	 
								
								$region_id ='--Select--';
								$staff_id = get_staff_user_id();
							}
						?>
						<tr>
							<th width="200px" style="text-align:center;" bgcolor="#f4b084" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($last2Month)); ?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($lastMonth)) ;?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($thisMonth)) ;?></strong></th>
							
						  </tr>
						 <tr bgcolor="#f4b084">
							<td width='100px'>Lead</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
						  </tr>	
							
					<?php 
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
							
							
							
							$this->db->order_by("id", "asc");
							$leadstage = $this->db->get('tblleadsstatus')->result_array();
                              foreach($leadstage as $data_l)
                              {
                              	$leadsTotal = 0;
                              	$leadsAmountTotal = 0;
								
								/* $thisMonth = date('Y-m', strtotime("-1 month"));	
								$lastMonth = date('Y-m', strtotime("-2 month"));	
								$last2Month = date('Y-m', strtotime("-3 month"));	
								$lastdate = date('Y-m-d', strtotime("-1 days")); */
			
								$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']);
								$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']);
								$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']);				
								
								$leadsTotal = $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']) + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']) + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']);
								
								$leadsTotal_of_total = $leadsTotal_of_total + $leadsTotal;
								
								$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']);
								$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']);
								$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']);			
								
								$leadsAmountTotal = $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']);
								
								$leadsvalue_of_total = $leadsvalue_of_total + $leadsAmountTotal;
								
                              ?>
								
								<tr>
									<td style="text-align:left;"><?php echo $data_l['name']; ?></td>
									
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']) ?></td>
								
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region['id'])) * 100 ,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7','',$region['id'])) * 100 ,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],'',$region['id']) * $data_l["weighted"]) / 100 ,0); ?></td>
								<?php } ?>
								
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']); ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region['id'])) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7','',$region['id'])) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],'',$region['id']) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']);?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']) ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region['id'])) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region['id']) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6','',$region['id']) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7','',$region['id'])) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],'',$region['id']) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
				
								  </tr>
						   <?php $i++; 
                              
                              } 
						?>
							<tr align="center">
								<th bgcolor="#f58a4c">Total</th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthno_of_leadsTotal; ?></th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
							</tr>
						 </tbody>
				</table>
				<?php 
					}  
					 ?>
							 
                <table class="table <?php if($this->input->post('view_assigned') == ''){ echo 'hidden'; } ?>"  style="text-align:center;">
                        
                        <tbody>
						
                           <?php
							$tbl_data ='<br>';	
							$tbl_data .='<tr align="center">';
										
							$tbl_data .='<th colspan="18" bgcolor="#f58a4c">All Regions</th>';				
											
							$tbl_data .='</tr>';
			
							echo $tbl_data;
							if($this->input->post('from-months') !=''){
								$filteryear = $this->input->post('from-years');
								$filtermonth = $this->input->post('from-months');
								
								$curr = $filteryear.'-'.$filtermonth;
								$thisMonth = date('Y-m', strtotime("$curr -1 month"));	
								$lastMonth = date('Y-m', strtotime("$curr -2 month"));	
								$last2Month = date('Y-m', strtotime("$curr -3 month"));	 
								
								 if($this->input->post('view_assigned') !=''){
									$staff_id = $this->input->post('view_assigned');
								 }else{
									 $staff_id = get_staff_user_id();
								
								 }
								
							}else{
								
								$thisMonth = date('Y-m', strtotime("-1 month"));	
								$lastMonth = date('Y-m', strtotime("-2 month"));	
								$last2Month = date('Y-m', strtotime("-3 month"));	 
								
								$region_id ='--Select--';
								$staff_id = get_staff_user_id();
							}
							
						?>
						<tr>
							<th width="200px" style="text-align:center;" bgcolor="#f4b084" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($last2Month)); ?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($lastMonth)) ;?></strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="3"><strong><?php echo date("M-Y", strtotime($thisMonth)) ;?></strong></th>
							
						  </tr>
						 <tr bgcolor="#f4b084">
							<td width='100px'>Lead(Nos)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead(Nos)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
							<td width='100px'>Lead(Nos)</td>
							<td width='100px'>Lead Value(In Lac)</td>
							<td width='100px'>Risk Weighted(In Lac)</td>
						  </tr>	
							
					<?php 
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
							
							//$region_id ='--Select--';
							
							
							$this->db->order_by("id", "asc");
							$leadstage = $this->db->get('tblleadsstatus')->result_array();
                              foreach($leadstage as $data_l)
                              {
                              	$leadsTotal = 0;
                              	$leadsAmountTotal = 0;
								$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id);
								$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id);
								$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id);				
								
								$leadsTotal = $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id) + $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id) + $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id);
								
								$leadsTotal_of_total = $leadsTotal_of_total + $leadsTotal;
								
								$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id);
								$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id);
								$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id);			
								
								$leadsAmountTotal = $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id);
								
								$leadsvalue_of_total = $leadsvalue_of_total + $leadsAmountTotal;
								
                              ?>
								
								<tr >
									<td style="text-align:left;"><?php echo $data_l['name']; ?></td>
									
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id) ?></td>
								
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7',$staff_id,$region_id)) * 100 ,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($last2Month,'7',$staff_id,$region_id)) * 100 ,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($last2Month,$data_l["id"],$staff_id,$region_id) * $data_l["weighted"]) / 100 ,0); ?></td>
								<?php } ?>
								
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id); ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id); ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7',$staff_id,$region_id)) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,'7',$staff_id,$region_id)) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($lastMonth,$data_l["id"],$staff_id,$region_id) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
									
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id); ?></td>
									
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id) ?></td>
									
								<?php if($data_l["id"] == 6){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7',$staff_id,$region_id)) * 100,0); ?>%</td>
								<?php } else if($data_l["id"] == 7){ ?>
									<td><?php echo round($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7',$staff_id,$region_id) / ($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'6',$staff_id,$region_id) + $this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,'7',$staff_id,$region_id)) * 100,0); ?>%</td>
								<?php } else{ ?>
									<td><?php echo round(($this->leads_model->value_of_leads_by_stage_month_staff($thisMonth,$data_l["id"],$staff_id,$region_id) * $data_l["weighted"]) / 100,0); ?></td>
								<?php } ?>
								
				
								  </tr>
						   <?php $i++; 
                              
                              } 
						   ?>
						   <tr align="center">
								<th bgcolor="#f58a4c">Total</th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthno_of_leadsTotal; ?></th>			
								<th align="center" bgcolor="#f58a4c"><?php echo $last2Monthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $lastMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthno_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c"><?php echo $thisMonthvalue_of_leadsTotal; ?></th>			
								<th align="center"  bgcolor="#f58a4c">-</th>			
							</tr>
					</table>       
                  </div>
               
			   </div>
              
            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>
<script type="text/javascript">
   $(document).ready(function () {
  //$("#region_id").prop("disabled", true);
   $('#form-filter-zone').change(function(){
	    
   	   if($('#form-filter-zone').val() =='all_region') {
			$("#region_id").prop("required", false);
			
			$("#region_id").val('default');
			$("#region_id").selectpicker("refresh");
			
			$("#view_assigned").find('option:selected').removeAttr('selected');
			$("#zone-div").addClass('hidden');
			$("#staff-div").addClass('hidden');
	   }else if($('#form-filter-zone').val() =='pan_india'){
			$("#region_id").prop("required", true);
			//$("#region_id").prop("disabled", false);
			$("#zone-div").removeClass('hidden');
			$("#staff-div").removeClass('hidden');
	   }else{
			$("#region_id").prop("required", false);
			//$("#region_id").prop("disabled", true);
			$("#zone-div").addClass('hidden');
			$("#staff-div").addClass('hidden');
	   }	
   	 
   });
    
   }); 
   
   
   $(document).on('change', '#region_id', function (e) {
        $('#view_assigned').html("");
        var region = $(this).val();
 
   var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value="">--Select--</option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/region/getByStaff_zone",
            data: {'region': region},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.staffid + ">" + obj.firstname +" "+obj.lastname +" - "+ obj.emp_code+ "</option>";
   
                });
                $('#view_assigned').append(div_data);
            }
        });
    });
	
   </script>
</body>
</html>