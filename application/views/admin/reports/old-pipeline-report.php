<?php 

init_head(); 

 function get_opportunity_sum($staff_id=''){
	$ci =& get_instance();
	$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")');
	
	return $query->row()->total_opportunity;
 }
 function get_project_total_amount_sum($staff_id=''){
	$ci =& get_instance();
	$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")');
	
	return $query->row()->project_total_amount;
 }
 function get_opportunity_sum_s($staff_id=''){
	$ci =& get_instance();
	$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")');
	
	return $query->row()->total_opportunity;
 }
 function get_project_total_amount_sum_s($staff_id=''){
	$ci =& get_instance();
	$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")');
	
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
				   <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('Pipe Line Report'); ?></h4>
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
                           <option value="1"><?php echo _l('last_month'); ?></option>
                           <option value="this_year"><?php echo _l('this_year'); ?></option>
                           <option value="last_year"><?php echo _l('last_year'); ?></option>
                           
                           <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                        </select>
                     </div>
                     <div id="date-range" class="hide mbot15">
                        <div class="row">
                           <div class="col-md-6">
                              <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
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
                        <label for="months-report"><?php echo _l('NSM'); ?></label><br />
                        <select class="selectpicker" id="nsm" name="nsm" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('--Select NSM--'); ?></option>
                           <?php
							$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=8');
								$result = $query->result_array();
								
								foreach($result as $res){?>
									<option <?php if($select_staff == $res['staffid']){ echo 'selected'; } ?>  value="<?php echo $res['staffid']?>"><?php echo $res['firstname']; ?><?php echo $res['lastname'] ;?></option>
									
									
									
									
								<?php
								}
						   ?>
                        </select>
                     </div>
					 
				  </div>
                
                 <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="1" id="checkAllDyNSM" /> Dy NSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllDyNSM">	
                          
                       </div>
                     </div>
					
				  </div>
				  <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="2" id="checkAllRSM" /> RSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllRSM">	
                         
                       </div>
                     </div>
					
				  </div>
                 <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="2" id="checkAllZSM" /> ZSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllZSM">	
                         
                       </div>
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
                        <label for="months-report"><input type="checkbox" value="4" id="checkAllSE" /> SE</label><br />
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
						
						<table class="collaptable table table-striped">
						  <tr style="text-align:center;">
							<th style="text-align:left;width:160px;">Name</th>
							<th style="text-align:center;">Opportunity Value(Lac)</th>
							<th style="text-align:center;">Order Value(Lac)</th>
							<th style="text-align:center;">If lost then lost Value</th>
							<th style="text-align:center;">Region</th>
							<th style="text-align:center;">Area</th>
							<th style="text-align:center;">Executive name</th>
							<th style="text-align:center;">Catg.</th>
							<th style="text-align:center;">Customer Name</th>
							<th style="text-align:center;width:200px">Product Description</th>
							<th style="text-align:center;">Status</th>
							<th style="text-align:center;">If lost then lost to</th>
						  </tr>
						 
						  <?php
							
							$chkrsm = $this->input->post('chkrsm');
							$total_record1 = sizeof($chkrsm);
							
							$chkasm = $this->input->post('chkasm');
							$total_record2 = sizeof($chkasm);
							
							$chkse = $this->input->post('chkse');
							$total_record3 = sizeof($chkse);
							
							$data = array();
			
							
							//print_r($this->input->post());
							
							$nsm = $this->input->post('nsm');
							
							$chkdynsm = $this->input->post('chkdynsm');
							/* $string_version = implode(',', $original_array)

							 */
							$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=6 AND reporting_manager IN ("'.$nsm.'") ORDER BY firstname ASC');
							
							$result = $query->result_array();
							$total_chkdynsm=1;
							foreach($result as $res){
								if(in_array($res['staffid'], $chkdynsm)){
									
						  ?>
							<tr data-id="<?php echo $total_chkdynsm; ?>" data-parent="">
								<td style="text-align: left !important;"><?php echo $res['firstname'].' '.$res['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res['staffid']); ?></td>
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
								$query1 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res['staffid'].'")');
							
								$result1 = $query1->result_array();
								$total_chkrsm=$total_chkdynsm;
								$totrsm = $total_chkdynsm + 1;
								foreach($result1 as $res1){
								if(in_array($res1['staffid'], $chkrsm)){
						   ?>
							   <tr data-id="<?php echo $totrsm; ?>" data-parent="<?php echo $total_chkrsm; ?>">
								<td style="text-align: left !important;"><?php echo $res1['firstname'].' '.$res1['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res1['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res1['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res1['staffid']); ?></td>
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
								$query2 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res1['staffid'].'")');
							
								$result2 = $query2->result_array();
								$total_chkasm=$totrsm;
								$totasm = $total_chkasm + 1;
								foreach($result2 as $res2){
								if(in_array($res2['staffid'], $chkasm)){
						   ?>
							   <tr data-id="<?php echo $totasm; ?>" data-parent="<?php echo $total_chkasm; ?>">
								<td style="text-align: left !important;"><?php echo $res2['firstname'].' '.$res2['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res2['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res2['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res2['staffid']); ?></td>
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
								$query3 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res2['staffid'].'")');
							
								$result3 = $query3->result_array();
								$total_chkse=$totasm;
								$totse = $totasm + 20;
								foreach($result3 as $res3){
								if(in_array($res3['staffid'], $chkse)){
						   ?>
							   <tr data-id="<?php echo $totse; ?>" data-parent="<?php echo $totasm; ?>">
								<td style="text-align: left !important;"><?php echo $res3['firstname'].' '.$res3['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_s($res3['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum_s($res3['staffid']); ?></td>
								<td><?php echo get_opportunity_sum_s($res3['staffid']); ?></td>
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
								$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res3['staffid'].'"');
							
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4){
								
						   ?>
							   <tr data-id="<?php echo $totse; ?>" data-parent="<?php echo $total_chkse; ?>">
								<td style="text-align: left !important;"></td>
								<td><?php echo $res4['opportunity']; ?></td>
								<td><?php if(isset($res4['project_total_amount'])){ echo $res4['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4['status']==7){ echo $res4['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4['city']); ?></td>
								<td><?php echo $this->leads_model->get_emp_name($res4['assigned']); ?></td>
								<td><?php echo $res4['customer_type']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4['customer_name']); ?></td>
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
							  </tr>
							  <?php
							
							} 
							?>
							<?php
								$total_chkse++;
								}
								
								$totse++;
							} 
							?>
							<?php
								$total_chkasm++;
								}
								
								$totasm++;
							} 
							?>
							<?php
								$total_chkrsm++;
								}
								
								$totrsm++;
							} 
							?>
							
							<?php 
							$total_chkdynsm++;
							
							
							} ?>
						<?php } ?>
						 
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



$("#checkAllDyNSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkdynsm").prop("checked",status);
	
});



$("#checkAllZSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkzsm").prop("checked",status);
	
});

$("#checkAllRSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkrsm").prop("checked",status);
	
});

$("#checkAllASM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkasm").prop("checked",status);
	
});



$("#checkAllSE").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkse").prop("checked",status);
	
});

$(document).on('change', '#nsm', function (e) {
		$('#AllDyNSM').html("");
		
        var nsm_id = $(this).val();
   		var base_url = '<?php echo base_url() ?>';
        var div_data = '';
        var string_dynsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBydynsm_id",
            data: {'nsm': nsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<input type='checkbox' name='chkdynsm[]' class='chkdynsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";					
					string_dynsm += obj.reporting_to+',';
                });
				string_dynsm += nsm_id;
				console.log(string_dynsm);
				get_zsm(string_dynsm);
				
				 $('#AllDyNSM').append(div_data);
				 $("#checkAllDyNSM").prop("checked",true).trigger("change");
            }
        });
	
        $("#checkAllDyNSM").prop("checked",true).trigger("change");
       
    });

	$(document).on('change', '.chkdynsm', function() {
		 var ids=''; 
		$('input[name="chkdynsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		
		get_rsm(ids);
	});
	
	 //------------fetch RSM ------------//
	function get_rsm(dynsm_id)
	{
		$('#AllRSM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_rsm = '';
		var string_asm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByrsm_id",
            data: {'dynsm_id': dynsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_rsm += "<input type='checkbox' name='chkrsm[]' class='chkrsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_asm += obj.reporting_to+',';
                });
				
				get_asm(string_asm);
				$('#AllRSM').append(div_data_rsm);
				$("#checkAllRSM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkrsm', function() {
		 var ids=''; 
		$('input[name="chkrsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		
		get_zsm(ids);
	});
	
	
	 //------------fetch ZSM ------------//
	function get_zsm(rsm_id)
	{
		$('#AllZSM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_rsm = '';
		var string_rsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByzsm_id",
            data: {'rsm_id': rsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_rsm += "<input type='checkbox' name='chkzsm[]' class='chkzsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_rsm += obj.reporting_to+',';
                });
				string_rsm += rsm_id;
				get_rsm(string_rsm);
				$('#AllZSM').append(div_data_rsm);
				$("#checkAllZSM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkzsm', function() {
		 var ids=''; 
		$('input[name="chkzsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		
		get_asm(ids);
	});
	 
	//------------fetch ASM ------------//
	function get_asm(rsm_id)
	{
		$('#AllASM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		
		var div_data_asm = '';
		var string_asm = '';
		
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByasm_id",
            data: {'list_rsm': rsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_asm += "<input type='checkbox' class='chkasm' name='chkasm[]' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_asm += obj.reporting_to+',';
                });
				
				get_se(string_asm);
                $('#AllASM').append(div_data_asm);
				$("#checkAllASM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkasm', function() {
		 var ids=''; 
		$('input[name="chkasm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
	
		get_se(ids);
	});
	
	 //------------fetch SE ------------//
	function get_se(asm_id)
	{
		$('#AllSE').html("");
   
		var base_url = '<?php echo base_url() ?>';
		var div_data_se = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByse_id",
            data: {'list_asm': asm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_se += "<input type='checkbox' class='chkse' name='chkse[]' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					
                });
				
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
