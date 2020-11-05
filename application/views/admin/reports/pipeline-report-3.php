<?php 

init_head(); 

//-------------------- Own -------------------------------//

 function get_opportunity_sum_own($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
		
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
		
		
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status NOT IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status NOT IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status NOT IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status NOT IN(6,7)');
	}
	$own =  $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }
 
//-------------------- Sales Executive ---------------------//

 
 function get_opportunity_sum_se($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status NOT IN(6,7)');
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
				   <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('Pipeline Report'); ?></h4>
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
                        <label for="months-report"><?php echo _l('ASM'); ?></label><br />
                        <select class="selectpicker" id="asm" name="asm" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('--Select ASM--'); ?></option>
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
							<th style="text-align:center;">Region</th>
							<th style="text-align:center;">Area</th>
							<th style="text-align:center;">Lead ID</th>
							<th style="text-align:center;">Lead Date</th>
							<th style="text-align:center;">Customer Name</th>
							<th style="text-align:center;">Catg.</th>
							<th style="text-align:center;width:200px">Product Description</th>
							<th style="text-align:center;">Status</th>
							<th style="text-align:center;">Finalization Month (Expected)</th>
							<th style="text-align:center;"><?php echo _l('Remarks'); ?></th>
						  </tr>
						 
						  <?php
							$get_opportunity_sums = 0;	
							$get_opportunity_sum = 0;
							$chkasm = $this->input->post('asm');
							
							$chkse = $this->input->post('chkse');
							
							$data = array();
			
							/* $string_version = implode(',', $original_array)

							 */
								$query_asm = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where staffid ="'.$chkasm.'"');
							
								$result_asm = $query_asm->result_array();
								foreach($result_asm as $res_asm){
									$get_opportunity_sum_own += get_opportunity_sum_own($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
								
						   ?>
							   <tr style="display: table-row;background: #fddede;font-size: 14px;" data-id="<?php echo $res_asm['staffid']; ?>" data-parent="">
								<td style="text-align: left !important;width:220px;"><?php echo $res_asm['firstname'].' '.$res_asm['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_own($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
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
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status NOT IN(6,7)');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND status NOT IN(6,7)');
								}
							
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4res_asm){
								
						   ?>
							   <tr data-id="<?php echo $res_asm['staffid']+550; ?>" data-parent="<?php echo $res_asm['staffid']; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4res_asm['opportunity']; ?></td>
								<td><?php echo $res4res_asm['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4res_asm['city']); ?></td>
								<td><?php echo $res4res_asm['id']; ?></td>
								<td><?php echo $res4res_asm['dateadded']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4res_asm['customer_name']); ?></td>
								<td><?php echo $res4res_asm['customer_type']; ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4res_asm['id']);
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
								<td><?php echo $this->leads_model->get_status_name($res4res_asm['status']); ?></td>
								<td><?php
									$timestamp = strtotime($res4res_asm['accepacted_date']);
									$date = date("d-m-Y", $timestamp);
									echo $date; ?>
							  </td>
								<td><?php
									echo $res4res_asm['status_lost'];
								 
								  ?></td> 
							  </tr>	  
						<?php	 
								}
								$query_se = $this->db->query("SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=1 AND reporting_manager IN ('". $res_asm['staffid'] ."') ");
							
								$result_se = $query_se->result_array();
								
								foreach($result_se as $res_se){
								
								if(in_array($res_se['staffid'], $chkse)){
									$get_opportunity_sum_se += get_opportunity_sum_se($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
						   ?>
							   <tr data-id="<?php echo $res_se['staffid']; ?>" data-parent="<?php echo $res_asm['staffid']; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_se['firstname'].' '.$res_se['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_se($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
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
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status NOT IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status NOT IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status NOT IN(6,7)');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND status NOT IN(6,7)');
								}
							
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4){
								
						   ?>
							   <tr data-id="9000" data-parent="<?php echo $res_se['staffid']; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4['opportunity']; ?></td>
								
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
								<td><?php
										$timestamp = strtotime($res4['accepacted_date']);
										$date = date("d-m-Y", $timestamp);
										echo $date; ?>
								  </td>
								<td><?php
								 if($res4['status_lost'] !=''){ 
									echo $res4['status_lost'];
								  }			  
								  
								  ?></td> 
							  </tr>
							  <?php
							
								} 
							
							
								}
							
							} 
							
							
								}	

						?>
							<tr style="text-align:center;background: #ff9201;">
								<td style="text-align: left !important;width:220px;">Total</td>
								<td><?php echo $get_opportunity_sum_own; ?></td>
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

$("#checkAllSE").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkse").prop("checked",status);
	
});

	$(document).on('change', '#asm', function (e) {
   		$('#AllSE').html("");		
		var asm_id = $(this).val();
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
       
    });
	
	
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
