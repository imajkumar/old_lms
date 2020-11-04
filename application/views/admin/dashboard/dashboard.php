<?php init_head(); 

/* function bd_nice_number($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
        
        // is this a number?
        if(!is_numeric($n)) return false;
        
        // now filter it;
        if($n>100000) return round(($n/100000),1).' Lac';
        else if($n>1000) return round(($n/1000),1).' K';
        
        return number_format($n);
    } */
	
	function bd_nice_number($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
        
        // is this a number?
        if(!is_numeric($n)) return false;
        
        // now filter it;
        if($n) return round(($n),1).' Lac';
        else if($n) return round(($n),1).' K';
        
        return number_format($n);
    }

?>
<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="screen-options-btn hide">
        <?php echo _l('dashboard_options'); ?>
    </div>
    <div class="content">
        <div class="row">

            <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); ?>

            <?php do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>
			
      <div class="col-md-6">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
			   <div class="row">
					<div class="col-md-12">
					<h5 class="padding-5"><?php echo _l('home_lead_overview').'(Number)'; ?></h5>
					</div>
					<form method="post">
					<div class="col-md-6 ">
						<div class="col-md-12 leads-filter-column" id="report-time">
								
								<select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
								   <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
								   <option <?php if($this->input->post('months-report')=='this_month'){ echo 'selected'; } ?> value="this_month"><?php echo _l('this_month'); ?></option>
								   <option <?php if($this->input->post('months-report')=='last_month'){ echo 'selected'; } ?> value="last_month"><?php echo _l('last_month'); ?></option>
								   <option <?php if($this->input->post('months-report')=='this_year'){ echo 'selected'; } ?>  value="this_year"><?php echo _l('this_year'); ?></option>
								   <option <?php if($this->input->post('months-report')=='last_year'){ echo 'selected'; } ?> value="last_year"><?php echo _l('last_year'); ?></option>
								   <option <?php if($this->input->post('months-report')=='report_sales_months_three_months'){ echo 'selected'; } ?>  value="report_sales_months_three_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
								   <option <?php if($this->input->post('months-report')=='report_sales_months_six_months'){ echo 'selected'; } ?> value="report_sales_months_six_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
								   <option <?php if($this->input->post('months-report')=='report_sales_months_twelve_months'){ echo 'selected'; } ?> value="report_sales_months_twelve_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
								   <option <?php if($this->input->post('months-report')=='custom'){ echo 'selected'; } ?> value="custom"><?php echo _l('Custom'); ?></option> 
								   
								</select>
							 </div>
							  
                  	 
					</div>
					<div class="col-md-6">
						
						  <?php 
							
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
							
							$selected = array();
							  if($this->input->post('view_assigned')){
								array_push($selected,$this->input->post('view_assigned'));
							  }
							 
						 ?>
						  <div class="col-md-8 leads-filter-column">
							<select class="form-control selectpicker" data-width="100%" data-none-selected-text="Staff" data-live-search="true"  name="view_assigned" id="view_assigned">
									<option value="">--Select User--</option>
									
								<?php foreach ($staff as $staffd) { 
								
								$this->db->select()->from('tblleads');
								/* if(get_staff_role() == 2 || get_staff_role() == 5 || get_staff_role() == 6 || get_staff_role() == 8){
								 $arr_user = '( CONCAT(",", reporting_to, ",") LIKE "%, '.$staffd["staffid"].',%"  OR CONCAT(",", reporting_to, ",")  LIKE "%,'.$staffd["staffid"].',%" ) OR assigned = "'.$staffd["staffid"].'"';
									$this->db->where($arr_user);
								} */
								$where = "assigned = '".$staffd["staffid"]."' OR reportingto LIKE '%".$staffd["staffid"]."%'";
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
						  
						  <div class="col-md-4 text-right">
								<button class="btn btn-success" type="submit"><?php echo _l('apply'); ?></button>
							</div>
					</div>
			   
					<div id="date-range" class="<?php if($this->input->post('months-report')=='custom'){ echo 'open'; }else { echo 'hide'; } ?> mbot15 col-md-12">
							<div class="col-md-6">
							  <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
							  <div class="input-group date">
								 <input type="text" value="<?php echo $this->input->post('report-from'); ?>" class="form-control datepicker" id="report-from" name="report-from">
								 <div class="input-group-addon">
									<i class="fa fa-calendar calendar-icon"></i>
								 </div>
							  </div>
						   </div>
						   <div class="col-md-6">
							  <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
							  <div class="input-group date">
								 <input type="text" value="<?php echo $this->input->post('report-to'); ?>" class="form-control datepicker" disabled="disabled" id="report-to" name="report-to">
								 <div class="input-group-addon">
									<i class="fa fa-calendar calendar-icon"></i>
								 </div>
							  </div>
						   </div>
					 </div>
			   </div>
				</form>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height:280px">
                  <?php 
				  
					$report_months = $this->input->post('months-report');
					$from_date = $this->input->post('report-from');
					$to_date = $this->input->post('report-to');
					$staff_id = $this->input->post('view_assigned');
					$lead_status = $this->dashboard_model->get_status_displayorder();
					
					$TOTAL_LEAD_ = 0;
					$TOTAL_SUM_ = 0;
					$TOTAL_SUM_RISK = 0;
					$TOTAL_SUM_WEIGHTED = 0;
					
						
					$i=0;
					
					foreach($lead_status as $_items){
					
					
						$total = $this->dashboard_model->get_lead_status($_items['id'],$staff_id,$report_months,$from_date,$to_date);
					
					if($total==null)
					{ 
						$totalamt = 0; 
					}
					else{
						$totalamt = $total;
					
						$TOTAL_LEAD_ = $TOTAL_LEAD_ + $this->dashboard_model->get_lead_no_status($_items['id'],$staff_id,$report_months,$from_date,$to_date);
					
						$TOTAL_SUM_ = $TOTAL_SUM_ + $total;
						$TOTAL_SUM_WEIGHTED = $TOTAL_SUM_WEIGHTED + ($totalamt * $_items['weighted'] ) / 100;
						
					}
				  ?>
					<div class="card-box noradius noborder col-md-6 text-center no-padding" style="color:#e6e6e6;height: 121px; background:<?php if($i==0){ ?> #3366cc <?php }elseif($i==1){ ?> #990099 <?php }elseif($i==2){ ?> #dc3912   <?php }elseif($i==3){ ?> #0099c6 <?php }elseif($i==4){ ?> rgb(255, 153, 0) <?php }elseif($i==5){ ?> rgb(221, 68, 119);  <?php }elseif($i==6){ ?> #109618  <?php } ?>">
					
						<h5 class="text-white text-uppercase"><?php echo $_items['id']; ?>. <?php echo $_items['name']; ?> </h5>
						<div class="col-md-6">
						<h5 class="text-white counter">Weighted: <?php echo bd_nice_number($totalamt); ?></h5>
						<h5 class="text-white counter">Risk Wtd.: <?php echo  bd_nice_number(($totalamt * $_items['weighted'] ) / 100); ?></h5>
						</div>
						<div class="col-md-6">
						<h5 class="text-white">No of Leads : 
						<?php 
						
							echo $this->dashboard_model->get_lead_no_status($_items['id'],$staff_id,$report_months,$from_date,$to_date); 
						
						?>
						</h5>
			
						<br>
						</div>
						
					</div>
				 
					<?php 
						$i++;
						$totalamt = 0;
					}
					
					?>
					
					<div class="card-box noradius noborder col-md-6 text-center no-padding" style="color:#e6e6e6;height: 121px; background: #9c4776;">
					
						<h5 class="text-white text-uppercase">Total Value</h5>
						<div class="col-md-6 text-center">
							<h5 class="text-white counter">Weighted: <?php echo bd_nice_number($TOTAL_SUM_); ?></h5>
							<h5 class="text-white counter">Risk Wtd. <?php echo  bd_nice_number($TOTAL_SUM_WEIGHTED); ?></h5>
						</div>
						<div class="col-md-6 text-center">
							<h5 class="text-white">No of Leads <?php echo $TOTAL_LEAD_; ?></h5>
					
						</div>
						
					</div>
					
               </div>
            </div>
         </div>
      </div>
	  
	  <div class="col-md-6">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
               <h5 class="padding-5"><?php echo _l('home_lead_overview').'(%)'; ?></h5>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height: 567px;">
                  <div id="pie_lead_status" style="height: 567px;">
				
				  
				  </div>

               </div>
            </div>
         </div>
      </div>
      
	</div>
	<div class="row" >  
	  <div class="col-md-6">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
               <h5 class="padding-5"><?php echo _l('home_lead_overview').' Customer Type (Number)'; ?></h5>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height:220px">
                  <?php 
					$customer = $this->dashboard_model->get_customer_type();
					$i=0;
					foreach($customer as $_items){
					
					$total = $this->dashboard_model->get_lead_customer_type($_items['code'],$staff_id,$report_months,$from_date,$to_date);
					if($total==null)
					{ 
						$totalamt = 0; 
					}
					else{
						$totalamt = $total;
					}
					
				  ?>
					<div class="card-box noradius noborder col-md-6 text-center" style="color:#e6e6e6;height: 85px; background:<?php if($i==0){ ?> #3366cc <?php }elseif($i==1){ ?> #dc3912 <?php }elseif($i==2){ ?> #ff9900 <?php }elseif($i==3){ ?> #109618 <?php }elseif($i==4){ ?> #990099 <?php }elseif($i==5){ ?> #0099c6 <?php } ?>">
						<h5 class="text-white text-uppercase"><?php echo $_items['name']; ?> </h5>
						
						
						<div class="col-md-6">
						<h5 class="text-white counter">Weighted: <?php echo bd_nice_number($totalamt); ?></h5>
						
						</div>
						<div class="col-md-6">
						<h5 class="text-white">No of Leads : <?php echo $this->dashboard_model->get_lead_no_custtype($_items['code'],$staff_id,$report_months,$from_date,$to_date); ?></h5>
			
						
						</div>
						
						
						
						
					</div>
				 
					<?php 
						$i++;
						$totalamt = 0;
					}
					?>
               </div>
            </div>
         </div>
      </div>
	  
	  <div class="col-md-6">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
               <h5 class="padding-5"><?php echo _l('home_lead_overview'). ' Customer Type (%)'; ?></h5>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height:220px">
                  <div id="piechart" style="height: 220px;"></div>

               </div>
            </div>
         </div>
      </div>
	  
	  <div class="col-md-6 hide">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
               <h5 class="padding-5"><?php echo _l('home_lead_overview'). '(Numbers)'; ?></h5>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height:280px">
                  
					<div id="columnchart_values" style=" height: 280px;"></div>

               </div>
            </div>
         </div>
      </div>
	  
	  
	  
	  
      
      <div class="col-md-6 hide">
         <div class="panel_s">
            <div class="panel-body padding-10">
               <div class="widget-dragger"></div>
               <h5 class="padding-5"><?php echo _l('home_lead_overview'). ' Customer Type (Numbers)'; ?></h5>
               <hr class="hr-panel-heading-dashboard">
               <div class="relative" style="height:280px">
                  <div id="columnchart_values1" style="height: 280px;"></div>

               </div>
            </div>
         </div>
      </div>
      
	  
	  
      <div class="col-md-12 ">
		<div class="clearfix"></div>
		  <div class="panel_s">
		   <div class="panel-body">
			<div class="widget-dragger"></div>
			<div class="dt-loader hide"></div>
			<?php $this->load->view('admin/utilities/calendar_filters'); ?>
			<div id="calendar"></div>
		  </div>
		</div>
		<div class="clearfix"></div>
	  </div>
   <!--
            <div class="col-md-12 mtop30" data-container="top-12">
                <?php render_dashboard_widgets('top-12'); ?>
            </div>

            <?php do_action('after_dashboard_top_container'); ?>

            <div class="col-md-6" data-container="middle-left-6">
                <?php render_dashboard_widgets('middle-left-6'); ?>
            </div>
            <div class="col-md-6" data-container="middle-right-6">
                <?php render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php do_action('after_dashboard_half_container'); ?>

            <div class="col-md-8" data-container="left-8">
                <?php render_dashboard_widgets('left-8'); ?>
            </div>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets('right-4'); ?>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-4" data-container="bottom-left-4">
                <?php render_dashboard_widgets('bottom-left-4'); ?>
            </div>
             <div class="col-md-4" data-container="bottom-middle-4">
                <?php render_dashboard_widgets('bottom-middle-4'); ?>
            </div>
            <div class="col-md-4" data-container="bottom-right-4">
                <?php render_dashboard_widgets('bottom-right-4'); ?>
            </div>
		-->
		
		
            <?php do_action('after_dashboard'); ?>
        </div>
    </div>
</div>
</div>
<script>
    
	google_api = '<?php echo $google_api_key; ?>';
    calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
	
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>

<script>
    
	   function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Customer Type'],
		  <?php 
			$customer = $this->dashboard_model->get_customer_type();
			foreach($customer as $_items){
			$total = $this->dashboard_model->get_lead_customer_type($_items['code'],$staff_id,$report_months,$from_date,$to_date);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
          ['<?php echo $_items['name']; ?>', <?php echo $totalamt; ?>],
          
			<?php 
			$totalamt = 0;
			} ?>
        ]);

        var options = {
          title: '',
		  sliceVisibilityThreshold:0
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
	
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart1);

	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

   
      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
		  <?php 
			$lead_status = $this->dashboard_model->get_status();
			foreach($lead_status as $_items){
			$total = $this->dashboard_model->get_lead_status($_items['id'],$staff_id,$report_months,$from_date,$to_date);
			if($total==null)
			{ 
				$totalamt = 0; 
			}
			else{
				$totalamt = $total;
			}
		  ?>
          ['<?php echo $_items['name']; ?>', <?php echo $totalamt; ?>],
          
			<?php 
			$totalamt = 0;
			} ?>
        ]);

        var options = {
          title: '',
		  sliceVisibilityThreshold:0
        };

        var chart1 = new google.visualization.PieChart(document.getElementById('pie_lead_status'));

        chart1.draw(data, options);
    }
	
	
</script>

<?php //$this->load->view('admin/dashboard/dashboard_js'); ?>
<?php $this->load->view('admin/reports/includes/sales_js'); ?>
</body>
</html>
