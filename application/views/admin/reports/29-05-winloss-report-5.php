<?php 

init_head(); 

function get_opportunity_sum_s_l($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
	}
	
	return $query->row()->total_opportunity;
 }


 function get_opportunity_sum($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7) ');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	
	return $query->row()->total_opportunity;
 }
 function get_opportunity_sum_loss($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status=7');
	}
	
	return $query->row()->total_opportunity;
 }
 
 
 function get_project_total_amount_sum($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7) ');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	return $query->row()->project_total_amount;
 }
 function get_opportunity_sum_s($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7) ');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	return $query->row()->total_opportunity;
 }
 function get_project_total_amount_sum_s($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7) ');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	return $query->row()->project_total_amount;
 }
	
?>
<style>
.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>
<div id="wrapper">
   <div class="content">
   <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
			        <div class="row">
                  <div class="col-md-8">
				   <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('Winloss Report'); ?></h4>
				  </div>
                 
                 
               </div>
                 <div class="row" style="margin-top:50px;">
                 <form method="post" id="form1">
				   <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><?php echo _l('Filter').' '._l('period_datepicker'); ?></label><br />
                        <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                           <option value="this_month"><?php echo _l('this_month'); ?></option>
                           <option value="last_month"><?php echo _l('last_month'); ?></option>
                           <option value="this_year"><?php echo _l('this_year'); ?></option>
                           <option value="last_year"><?php echo _l('last_year'); ?></option>
                           <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                        </select>
                     </div>
                     <div id="date-range" class="hide mbot15">
                        <div class="row">
                           <div class="col-md-12">
                              <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" disabled="disabled" id="report-to" name="report-to">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  	 
					<div class="form-group" id="report-time11">
                        <label for="months-report"><?php echo _l('ZSM'); ?></label><br />
                        <select class="selectpicker" id="zsm" name="zsm" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('--Select ZSM--'); ?></option>
                           <?php
							$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where staffid="'.$this->session->staff_user_id.'"');
								$result = $query->result_array();
								
								foreach($result as $res){
									echo  '<option value="'.$res['staffid'].'">'.$res['firstname'].' '.$res['lastname'].'</option>';
								}
						   ?>
                        </select>
                     </div>
				  </div>
              
                <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="3" id="checkAllASM" /> ASM</label><br />
							<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllASM">
							
							</div>
                     </div>
					
				  </div>
                <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="1" id="checkAllSE" /> SE</label><br />
							<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllSE">
							
						</div>
                     </div>
					
				  </div>
				<div class="col-md-1">
						<div class="form-group" id="report-time">
						<br><br><br>
							<button type="submit" id="filter" class="btn btn-info pull-left display-block btnadd" >SHOW</button>
						 </div>
						
				  </div>
                </form>
				
                 </div>
               
					<div class="row">
						
						<table class="collaptable table table-bordered">
						  <tr style="text-align:center;background: #ff9201;">
							<th style="text-align:left;width:160px;">Name</th>
							<th style="text-align:center;">Opportunity Value(Lac)</th>
							<th style="text-align:center;">Order Value(Lac)</th>
							<th style="text-align:center;">If lost then lost Value</th>
							<th style="text-align:center;">Region</th>
							<th style="text-align:center;">Area</th>
							<th style="text-align:center;">Lead ID</th>
							<th style="text-align:center;">Lead Date</th>
							<th style="text-align:center;">Customer Name</th>
							<th style="text-align:center;">Catg.</th>
							<th style="text-align:center;width:200px">Product Description</th>
							<th style="text-align:center;">Status</th>
							<th style="text-align:center;">If lost then lost to</th>
							<th style="text-align:center;"><?php echo _l('Remarks'); ?></th>
						  </tr>
						 
						  <?php
							$get_opportunity_sums = 0;	
							$chkzsm = $this->input->post('zsm');
							
							$chkasm = $this->input->post('chkasm');
							$total_record2 = sizeof($chkasm);
							
							$chkse = $this->input->post('chkse');
							$total_record3 = sizeof($chkse);
							
							$data = array();
			
							/* $string_version = implode(',', $original_array)

							 */
							
								$chkzsm_id = $res_zsm['staffid'];
								$query_asm = $this->db->query("SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=3 AND reporting_manager IN ('". $chkzsm ."') ");
							
								$result_asm = $query_asm->result_array();
								foreach($result_asm as $res_asm){
									$get_opportunity_sum +=get_opportunity_sum($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
                                    $get_project_total_amount_sum += get_project_total_amount_sum($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
                                    $get_opportunity_sum_loss += get_opportunity_sum_loss($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
									
									
									
									
								if(in_array($res_asm['staffid'], $chkasm)){
						   ?>
							   <tr style="display: table-row;background: #fddede;font-size: 14px;" data-id="<?php echo $res_asm['staffid']; ?>" data-parent="<?php echo $chkzsm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_asm['firstname'].' '.$res_asm['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_project_total_amount_sum($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_opportunity_sum_loss($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							
							<?php
								$chkasm_id = $res_asm['staffid'];
								$query_se = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=1 AND reporting_manager ='.$chkasm_id.'');
							
								$result_se = $query_se->result_array();
								$total_chkse=$total_chkasm;
								$totse = $totasm + 20;
								foreach($result_se as $res_se){
								if(in_array($res_se['staffid'], $chkse)){
						   ?>
							   <tr data-id="<?php echo $res_se['staffid']; ?>" data-parent="<?php echo $chkasm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_se['firstname'].' '.$res_se['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_s($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_project_total_amount_sum_s($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_opportunity_sum_s_l($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							  
							  <?php
								$filter = $this->input->post('months-report');
								$report_from = $this->input->post('report-from');
								$report_to = $this->input->post('report-to');
								
								if($filter=='this_month'){
									$month = date('Y-m');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND status IN(6,7)');
								}
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4){
								
								$get_opportunity_sum_s = $get_opportunity_sum_s + $res4['opportunity'];
								$get_project_total_amount_sum_s = $get_project_total_amount_sum_s + $res4['project_total_amount'];
								if($res4['status']==7)
								{ 
									$get_opportunity_sums = $get_opportunity_sums + $res4['opportunity'];
								}
						   ?>
							   <tr data-id="<?php echo $res_se['staffid']; ?>" data-parent="<?php echo $chkasm_id; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4['opportunity']; ?></td>
								<td><?php if(isset($res4['project_total_amount'])){ echo $res4['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4['status']==7){ echo $res4['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4['city']); ?></td>
								<td><?php echo $res4['id']; ?></td>
								<td><?php echo $res4['dateadded']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4['customer_name']); ?></td>
								<td><?php echo $res4['customer_type']; ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4['id']);
										$string = '';
										foreach ($CAT as $value) 
										{
											if (!empty($string)) 
											{ 
												$string .= ', '; 
											}
											$string .= $value['cat_name'];
										}
										echo $string;
									  ?>
								</td>
								<td><?php echo $this->leads_model->get_status_name($res4['status']); ?></td>
								<td><?php echo $res4['project_awarded_to']; ?></td>
								<td><?php
								 if($res4['status_closed_won'] !=''){ 
									echo $this->leads_model->get_status_won_loss($res4['status_closed_won']);
								  }			  
								  
								  ?></td> 
							  </tr>
							  <?php
							
								} 
							
							
							}
							
							} 
							
								}
								
							} 
								
							?>
							
							<tr style="text-align:center;background: #ff9201;">
								<td style="text-align: left !important;width:220px;">Total</td>
								<td><?php echo $get_opportunity_sum; ?></td>
								<td><?php echo $get_project_total_amount_sum; ?></td>
								<td><?php echo $get_opportunity_sum_loss; ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
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

<?php $this->load->view('admin/reports/includes/sales_js'); ?>

<script>
$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();


$("#checkAllASM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkasm").prop("checked",status);
	
});



$("#checkAllSE").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkse").prop("checked",status);
	
});

$(document).on('change', '#zsm', function (e) {
		$('#AllASM').html("");
		
        var rsm_id = $(this).val();
   		var base_url = '<?php echo base_url() ?>';
        var div_data = '';
        var string_dynsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByasm_id",
            data: {'list_rsm': rsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<input type='checkbox' name='chkasm[]' class='chkasm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";					
					string_dynsm += obj.staffid+',';
                });
				get_se(string_dynsm);
				console.log(string_dynsm);
				 $('#AllASM').append(div_data);
				 $("#checkAllASM").prop("checked",true).trigger("change");
            }
        });
	
        $("#checkAllASM").prop("checked",true).trigger("change");
       
    });
	
	
	$(document).on('change', '.chkasm', function() {
		 var ids=''; 
		$('input[name="chkasm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		console.log(ids);
		get_se(ids);
	});
	
	 //------------fetch SE ------------//
	function get_se(asm_id)
	{
		$('#AllSE').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_se = '';
		var string_se = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByse_id",
            data: {'list_asm': asm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_se += "<input type='checkbox' class='chkse' name='chkse[]' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_se += obj.staffid+',';
                });
				console.log(string_se);
                $('#AllSE').append(div_data_se);
				$("#checkAllSE").prop("checked",true).trigger("change");
            }
        });
		
	}

	 $(function () {
		 $('.btnadd').click(function () {
			var item_chkdynsm = '';
			 $('input[name="chkdynsm[]"]:checked').each(function(){
				item_chkdynsm += $(this).val()+',';
			});
			item_chkdynsm = item_chkdynsm.slice(0, -1);
			
			var item_rsm  = '';
			 $('input[name="chkrsm[]"]:checked').each(function(){
				item_rsm += $(this).val()+',';
			});
			item_rsm = item_rsm.slice(0, -1);
			
			var item_asm = '';
			 $('input[name="chkasm[]"]:checked').each(function(){
				item_asm += $(this).val()+',';
			});
			item_asm = item_asm.slice(0, -1);
			
			var item_se = '';
			 $('input[name="chkse[]"]:checked').each(function(){
				item_se += $(this).val()+',';
			});
			item_se = item_se.slice(0, -1);
			
			 console.log( 'item_se: '+ item_se +' item_asm:'+ item_asm +' item_rsm:'+ item_rsm +' item_chkdynsm:'+item_chkdynsm );
			 
			 /* $.ajax({
				 url: 'process.php',
				 type: 'post',
				 data: $("#form1").serialize(),
				 success: function (data) {}
			 }); */
		 });
	 });
	
	
	</script>
	
	
	

</body>
</html>
