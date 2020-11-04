<?php init_head(); ?>
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
     text-transform: capitalize;

   }
   .table>thead>tr>th{
	   border-top: 1px solid #000!important;
   }
   p{
	     text-transform: uppercase;
		 font-size:11px;
		 margin: 0 0 1px !important;
   }
</style>
<div id="wrapper">
   <div class="content">
      
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Win/Loss Reports</h4>
                    
                  </div>  
					<div class="col-md-12">
				  <form method="post">
					<div class="col-md-2">
						<h5>Select Region:</h5>
							<select class="selectpicker" name="from-region" id="form-filter-zone" required data-width="100%" data-none-selected-text="Select region">
							   <option value="">Select Region</option>
							   <option <?php if($this->input->post('from-region')=='all_region' || $this->input->post('from-region')==''){ echo 'selected'; } ?>  value="all_region">All Region</option>
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
						<select  required name="region_id" id="region_id" class="form-control" >
							<option value="1,2,3,4">--Select--</option>
							
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
							<option <?php if($this->input->post('region_id')==$regiond['id']){ echo 'selected'; } ?> value="<?php echo $regiond['id'] ?>"><?php echo $regiond['region'] ?></option>
							<?php } ?>
						</select>
					  </div> 
					<div class="col-md-2 leads-filter-column" id="report-time1">

								<h5><?php echo _l('period_datepicker'); ?>:</h5>

								<select class="selectpicker" id="months-report" name="report_months" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

								   <option <?php if($this->input->post('report_months')==''){ echo 'selected'; } ?> value=""><?php echo _l('report_sales_months_all_time'); ?></option>

								   <option <?php if($this->input->post('report_months')=='this_month'){ echo 'selected'; } ?> value="this_month"><?php echo _l('this_month'); ?></option>
									
								   <option <?php if($this->input->post('report_months')=='last_month'){ echo 'selected'; } ?> value="last_month"><?php echo _l('last_month'); ?></option>

								   <option <?php if($this->input->post('report_months')=='this_year'){ echo 'selected'; } ?> value="this_year"><?php echo _l('this_year'); ?></option>

								   <option <?php if($this->input->post('report_months')=='last_year'){ echo 'selected'; } ?> value="last_year"><?php echo _l('last_year'); ?></option>

								   <option <?php if($this->input->post('report_months')=='report_sales_months_three_months' || $this->input->post('report_months')==''){ echo 'selected'; } ?> value="report_sales_months_three_months" data-subtext="<?php echo _d(date('01-m-Y', strtotime("-3 MONTH"))); ?> - <?php echo _d(date('t-m-Y', strtotime("-1 MONTH"))); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>

								   <option <?php if($this->input->post('report_months')=='report_sales_months_six_months'){ echo 'selected'; } ?> value="report_sales_months_six_months" data-subtext="<?php echo _d(date('01-m-Y', strtotime("-6 MONTH"))); ?> - <?php echo _d(date('t-m-Y',strtotime("-1 MONTH"))); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>

								   <option <?php if($this->input->post('report_months')=='report_sales_months_twelve_months'){ echo 'selected'; } ?> value="report_sales_months_twelve_months" data-subtext="<?php echo _d(date('01-m-Y', strtotime("-12 MONTH"))); ?> - <?php echo _d(date('t-m-Y',strtotime("-1 MONTH"))); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>

								   <option value="custom"><?php echo _l('Custom'); ?></option> 

								   

								</select>
								
								<div id="date-range" class="hide mbot15 col-md-12 offset-md-7">

								<div class="row">

								   <div class="col-md-12">

									  <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>

									  <div class="input-group date">

										 <input type="text" class="form-control datepicker" id="report_from" name="report_from">

										 <div class="input-group-addon">

											<i class="fa fa-calendar calendar-icon"></i>

										 </div>

									  </div>

								   </div>

								   <div class="col-md-12">

									  <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>

									  <div class="input-group date">

										 <input type="text" class="form-control datepicker" id="report_to" name="report_to">

										 <div class="input-group-addon">

											<i class="fa fa-calendar calendar-icon"></i>

										 </div>

									  </div>

								   </div>

								</div>

							 </div>

					</div>
							
					<div class="col-md-1"  style="width: 130px;">
						<h5>Win/Loss:</h5>
						
							<select class="selectpicker" name="from-stage" data-width="100%" data-none-selected-text="Select Win/Loss" tabindex="-98">
							   <option value="7,6">Win/Loss</option>
							   <option <?php if($this->input->post('from-stage')=='6'){ echo 'selected'; } ?>  value="6">Win</option>
								<option <?php if($this->input->post('from-stage')=='7'){ echo 'selected'; } ?>  value="7">Loss</option>
								
							</select>
					
					</div>
					<div class="col-md-2" style="width: 150px;">
						<h5>Top Record:</h5>
						
							<select class="selectpicker" name="from-top" data-width="100%" data-none-selected-text="Select Top Record" tabindex="-98">
							   <option value="">--Select--</option>
							   <option <?php if($this->input->post('from-top')=='10' || $this->input->post('from-top')==''){ echo 'selected'; } ?>  value="10">Top 10</option>
							   <option <?php if($this->input->post('from-top')=='20'){ echo 'selected'; } ?>  value="20">Top 20</option>
							   <option <?php if($this->input->post('from-top')=='All'){ echo 'selected'; } ?>  value="All">All</option>
								
							</select>
					
					</div>
					
					<div class="col-md-2">
						<h5>Staff:</h5>
						  <?php 
						  
							 if($this->input->post('region_id')){
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
								
								
								 /* if(get_staff_role() == 2 || get_staff_role() == 5 || get_staff_role() == 6 || get_staff_role() == 8){
								 $arr_user = '( CONCAT(",", reportingto, ",") LIKE "%, '.$staffd["staffid"].',%"  OR CONCAT(",", reportingto, ",")  LIKE "%,'.$staffd["staffid"].',%" ) OR assigned = "'.$staffd["staffid"].'"';
									$this->db->where($arr_user);
								}  */
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
			   
					<div class="col-md-1 text-right">
						<br>
						<br>
						<button class="btn btn-success" type="submit">Apply</button>
					</div>
						
					</form>
                    
				  </div>
				  
				  <div class="col-md-12">
                     <br>
					 <?php 
					 $table_winloss = '';
                    $table_winloss .='<table class="table "  style="text-align:center;">
                        
                        <tbody>';
						
						   						
							if($this->input->post('from-top') !=''){
								$topRecord = $this->input->post('from-top');
							}else{
								$topRecord = '10';
							}
							
							if($this->input->post('from-stage') !=''){
								$winloss = $this->input->post('from-stage');
							}else{
								$winloss = '7,6';
							}
							$report_months  = $this->input->post('report_months');
							$from_date  = $this->input->post('report_from');
							$to_date  = $this->input->post('report_to');
							$view_assigned  = $this->input->post('view_assigned');
							
							$regionid = $this->input->post('region_id');
							$sql =  'SELECT region,firstname,lastname FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
							$region_staff = $this->db->query($sql)->row()->region;
							$_staff_name = $this->db->query($sql)->row()->firstname.' '.$this->db->query($sql)->row()->lastname;
							
							echo $report_months.' - '.$from_date.' - '.$to_date.' - '.$regionid.' - '.$winloss.' - '.$topRecord.' - '.$view_assigned;
							exit;
							if($this->input->post('from-region')=='pan_india'){
							
								$lead_details = $this->leads_model->winloss_month_zone($report_months,$from_date,$to_date,$regionid,$winloss,$topRecord,$view_assigned);
							}else{
								$lead_details = $this->leads_model->winloss_month_zone($report_months,$from_date,$to_date,$region_staff,$winloss,$topRecord,$view_assigned);
							}
							
					
				$table_winloss .='<br>
						<tr align="center">
							<th colspan="8" bgcolor="#f58a4c">All Regions</th>
						</tr>
						 <tr bgcolor="#f4b084">
							<td width="120px">Lead Id | Date</td>
							<td width="100px">Remark</td>
							<td width="">Client Name</td>
							<td width="120px">Opportunity Value<br>(In Lac)</td>
							<td width="120px">Order Value<br>(In Lac)</td>
							<td width="">Staff</td>
							<td width="">Competitor</td>
							<td width="240px">Reason</td>
						  </tr>';
							
					
							
							$opportunity_total = 0;
							$ordervalue_total = 0;
							foreach($lead_details as $res4)
                            {
                              	$competition = '';
				
								if($res4['competition'] !='')
									$competition .= $res4['competition'];
								if($res4['competition1'] !='')
									$competition .= ', '.$res4['competition1'];
								if($res4['competition2'] !='')
									$competition .= ', '.$res4['competition2'];
								if($res4['competition3'] !='')
									$competition .= ', '.$res4['competition3'];
								if($res4['competition4'] !='')
									$competition .= ', '.$res4['competition4'];
								$opportunity_total = $opportunity_total + $res4['opportunity'];
								$ordervalue_total = $ordervalue_total + $res4['project_total_amount'];
                    
							if($res4["status"]==7){
								$table_winloss .='<tr bgcolor="#ffe699">';
							}else{
								$table_winloss .='<tr bgcolor="#c6e0b4">';								
							}
							$timestamp1 = strtotime($res4["dateassigned"]);
							$date1 = date("d-m-Y", $timestamp1);
					$table_winloss .='<td><p>'.$res4["id"].' | '. $date1.'</p></td>
									<td><p>'.$this->leads_model->get_status_name($res4["status"]).'</p></td>
									<td><p>'.$this->leads_model->get_customer_name($res4["customer_name"]).'</p></td>
									<td><p>'.$res4["opportunity"].'</p></td>';
									if($res4["project_total_amount"] != ""){
					$table_winloss .='<td><p>'.$res4["project_total_amount"] .'</p></td>';
									}else{
					$table_winloss .='<td><p>'.$res4["project_total_amount"] .'</p></td>';
									}
					$table_winloss .='<td><p>'. get_staff_full_name($res4["assigned"]) .'</p></td>';
					$table_winloss .='<td><p>'. $competition .'</p></td>';
					
					if($res4["status_closed_won"] !=""){ 
					
					$table_winloss .='<td><p>'. $this->leads_model->get_status_won_loss($res4["status_closed_won"]).'</p></td>'; 
					}else{
						$table_winloss .='<td><p>No remark added</p></td>';
					}						
					$table_winloss .='</tr>';
						       $i++; 
                              
                              } 
						   
						   $table_winloss .='<tr bgcolor="#f4b084">
									<td colspan="3"><p><strong>Total</strong></p></td>
									<td><p><strong>'.$opportunity_total.'</strong></p></td>
									<td><p><strong>'. $ordervalue_total.'</strong></p></td>
									<td colspan="3"><p></p></td>
									
								  </tr>
					</table>';
					
					echo $table_winloss;
					?>
					
                  </div>
				
			   </div>
              
            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>

<script type="text/javascript">
	   
	var date_range = $('#date-range');
    var report_from = $('#report_from');
	var report_to = $('#report_to');
 
    $('#report_from').change( function() {
     var val = $(this).val();
	 var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         
       }
     } else {
       report_to.attr('disabled', true);
     } 
   });
   
   $(document).ready(function () {

 
	$('#months-report').change( function() {
     var val = $(this).val();
	 
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       $('#date-range').addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!$('#date-range').hasClass('hide')) {
         $('#date-range').removeClass('fadeIn').addClass('hide');
       }
     } 
    
   });
	   
	
  //$("#region_id").prop("disabled", true);
   $('#form-filter-zone').change(function(){
	    
   	   if($('#form-filter-zone').val() =='all_region') {
			$("#region_id").prop("required", false);
			//$("#region_id").prop("disabled", true);
			$("#view_assigned").find('option:selected').removeAttr('selected');
			$("#region_id").find('option:selected').removeAttr('selected');
			
			$("#zone-div").addClass('hidden');
	   }else if($('#form-filter-zone').val() =='pan_india'){
			$("#region_id").prop("required", true);
			//$("#region_id").prop("disabled", false);
			$("#zone-div").removeClass('hidden');
	   }else{
			$("#region_id").prop("required", false);
			//$("#region_id").prop("disabled", true);
			$("#zone-div").addClass('hidden');
	   }	
   	 
   });
    
   }); 
   </script>
   <script>
$(document).on('change', '#region_id', function (e) {
    var zone = $(this).val();
	//alert(zone);
	 //$('#zone_name').val($("#region_id :selected").text());
	 $('#view_assigned').html("");
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/staff/getStaffByZoneLead",
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