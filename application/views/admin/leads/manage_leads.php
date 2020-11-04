<?php init_head(); 
$has_permission_create = has_permission('leads','','create');
?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
          <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
				  <?php if($has_permission_create){ ?>
                  <a href="<?php echo base_url('admin/leads/lead_add') ?>"  class="btn btn-default btn-icon pull-left display-block">
                      <?php echo _l('new_lead'); ?>
                  </a>
				  <?php
				   } 
				  ?>
				
                  <div class="row">
                     <div class="col-md-5">
                        <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-title="<?php echo _l('Lead Filter'); ?>" data-placement="bottom" onclick="slideToggle('.leads-overview'); return false;"><i class="fa fa-filter"></i> Lead Filter</a>
                        <a href="<?php echo admin_url('leads/switch_kanban/'.$switch_kanban); ?>" class="btn btn-default mleft10 hidden-xs">
                        <?php if($switch_kanban == 1){ echo _l('leads_switch_to_kanban');}else{echo _l('switch_to_list_view');}; ?>
                        </a>
                     </div>
                     <div class="col-md-4 col-xs-12 pull-right leads-search">
                        <?php if($this->session->userdata('leads_kanban_view') == 'true') { ?>
                        <div data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('search_by_tags'); ?>">
                        <?php echo render_input('search','','','search',array('data-name'=>'search','onkeyup'=>'leads_kanban();','placeholder'=>_l('leads_search')),array(),'no-margin') ?>
                        </div>
                        <?php } ?>
                        <?php echo form_hidden('sort_type'); ?>
                        <?php echo form_hidden('sort',(get_option('default_leads_kanban_sort') != '' ? get_option('default_leads_kanban_sort_type') : '')); ?>
                     </div>
                  </div>
                  <div class="clearfix"></div>

                  <div class="row leads-overview hide display-inline">
                        <hr class="hr-panel-heading" />
                     <div id="original">
						 <div class="col-md-12">
							<h4 class="no-margin"><?php echo _l('leads_summary'); ?></h4>
						 </div>
						 <table >
						 <tr >
						
						<?php
					      
						$total_value = 0;
						$numStatuses = count($statuses);
                        $whereNoViewPermission ='';
                        foreach($statuses as $status){ ?>
						<td width="150px">
							 <div class="border-right text-center">
								<?php
									if(get_staff_role() > 8 || is_admin() ){
										$whereNoViewPermission = 'status="'.$status['id'].'"';
										$this->db->where($whereNoViewPermission);
									}else if(get_staff_role() == 1 ){
										$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.assigned='.get_staff_user_id().'';
										$this->db->where($whereNoViewPermission);
									}else if (get_staff_role()  == 7 || get_staff_role() == 4) {
										$whereNoViewPermission = 'status="'.$status['id'].'" AND tblleads.state IN('. trim(get_staff_state_id(),",") .')';
										$this->db->where($whereNoViewPermission);
									 }
									 else if(get_staff_role() <= 8) 
									{
										$whereNoViewPermission = 'status="'.$status['id'].'" AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned='.get_staff_user_id().')';
										$this->db->where($whereNoViewPermission);
									}
									
									$query = $this->db->select_sum('opportunity', 'Amount');
									$query = $this->db->where($whereNoViewPermission);
									$query = $this->db->get('tblleads');
									$result = $query->result();

								   // $total = $this->db->count_all_results('tblleads');
									$total = $result[0]->Amount;
									$total_value = $total_value + $total;
								   ?>
								<h3 class="bold"><?php echo $total; ?></h3>
								<span style="color:<?php echo $status['color']; ?>"><?php echo $status['name']; ?></span>
							 </div>
							 </td>
						<?php } ?>
					 
					<td width="150px">
						<div class="border-right text-center">
                        
                        <h3 class="bold"><?php echo $total_value; ?></h3>
                        <span style="color:<?php echo '#fb8c00'; ?>"><?php echo 'Total'; ?></span>
                     </div>
					 </td>
					
					 </tr>
					
					 </table>
					 
                    </div>
                    <div id="filterdata">
					
					</div>
					 <div class="col-md-12">
                           <div class="row">
                              <div class="col-md-12">
							  <hr>
                                 <p class="bold"><?php echo _l('filter_by'); ?></p>
                              </div>
							  <div class="col-md-2 leads-filter-column">
							    <label>Customer Group</label>
								
                                 <?php
								
                                   echo render_select('view_customer_group',$customer_groups,array('id','name'),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('Customer Group')),array(),'no-mbot');
                                    ?>
                              </div>
                              
                              <div class="col-md-2 leads-filter-column">
							     <label>Status</label>
                                 <?php
                                 $selected = array();
                                 if($this->input->get('status')) {
                                  $selected[] = $this->input->get('status');
                                 } else {
                                  foreach($statuses as $key => $status) {
                                    /* if($status['isdefault'] == 0) { */
									if(get_staff_role() == 4) 
									{
										if($status['id'] > 2 && $status['id'] < 6 ) 
										{
											$selected[] = $status['id'];
										}
									}else if(get_staff_role() == 7){
										if($status['id'] > 2 && $status['id'] < 6 ) 
										{
											$selected[] = $status['id'];
										}
									}else{
										$selected[] = $status['id'];
									}
                                   /*  } else { */
                                     /*  $statuses[$key]['option_attributes'] = array('data-subtext'=>_l('leads_converted_to_client'));
                                    } */
                                  }
                                 }
                                 echo '<div id="leads-filter-status">';
                                    echo render_select('view_status[]',$statuses,array('id','name'),'',$selected,array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false);
                                    echo '</div>';
                                    ?>
                              </div>
                              
							  <div class="col-md-2 leads-filter-column hide">
                                 <?php
								 
								
                                   // echo render_select('view_source',$sources,array('id','name'),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_source')),array(),'no-mbot');
                                    ?>
                              </div>
							  <div class="col-md-2 leads-filter-column">
								<label>Project Manager Approval</label>
                                 <select id="pm_approval_status" name="pm_approval_status[]" multiple class="selectpicker" data-width="100%" data-none-selected-text="PM Approval" data-live-search="true">
									 <option value=""></option>
									 <option <?php if(get_staff_role() == 4){ ?> selected <?php } ?> value="0">Pending</option>
									 <option value="1">Approved</option>
									 <option <?php if(get_staff_role() == 4){ ?> selected <?php } ?> value="2">Partial Approved</option>
								</select>
                              </div>
							  <div class="col-md-2 leads-filter-column">
								<label>Technical Approval</label>
                                 <select id="as_approval_status" multiple name="as_approval_status[]" class="selectpicker" data-width="100%" data-none-selected-text="Ass. Approval" data-live-search="true">
									 <option value=""></option>
									 <option <?php if(get_staff_role() == 7){ ?> selected <?php } ?> value="0">Pending</option>
									 <option value="1">Approved</option>
									 <option <?php if(get_staff_role() == 7){ ?> selected <?php } ?> value="2">Partial Approved</option>
								</select>
                              </div>
							  <div class="col-md-2 leads-filter-column <?php if(get_staff_role() == 1){ echo 'hide'; } ?>">
							     <label>Staff</label>
								 <select class="form-control selectpicker filtersummary" data-width="100%" data-none-selected-text="Staff" data-live-search="true"  name="view_assigned" id="view_assigned">
									<option value="">--Select User--</option>
									<?php foreach($staff as $staffs) {
										$query = $this->db->get_where('tblleads', array('assigned' => $staffs["staffid"]));
										$ifzsmleadvalue = $query->num_rows();
										if($ifzsmleadvalue > 0)
										{
										?>
											<option value="<?php echo $staffs['staffid']; ?>"> <?php echo $staffs['firstname'].' '.$staffs['lastname'].' - '.$staffs['emp_code'];?></option>
									<?php 
										} 
										
									} 
										
									?>
								</select>
                                
                              </div>
                              
							  <div class="col-md-2 leads-filter-column hide">
                                 <?php
								
                                    echo render_select('view_region',$regions,array('id','region'),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('region')),array(),'no-mbot');
                                    ?>
                              </div>
                              
							  <div class="col-md-2 leads-filter-column" id="report-time">
								<label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
								<select class="selectpicker filtersummary" name="months-report" id="months_report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
								   <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
								   <option value="this_month"><?php echo _l('this_month'); ?></option>
								   <option value="last_month"><?php echo _l('last_month'); ?></option>
								   <option value="this_year"><?php echo _l('this_year'); ?></option>
								   <option value="last_year"><?php echo _l('last_year'); ?></option>
								   <option value="report_sales_months_three_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
								   <option value="report_sales_months_six_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
								   <option value="report_sales_months_twelve_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
								   <option value="till_last_month"><?php echo 'Till Last Month'; ?></option> 
								   <!--<option value="custom"><?php echo _l('Custom'); ?></option> -->
								   
								</select>
							 </div>
							  <div id="date-range" class="hide mbot15 col-md-5 offset-md-7">
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
                  	 
                  	 
							  
                           </div>
                        </div>
                        <div class="clearfix"></div>
					 
                  </div>
               </div>
			   
			   
			   
			   
                  <hr class="hr-panel-heading" />
                  <div class="tab-content">
                     <?php
                        if($this->session->has_userdata('leads_kanban_view') && $this->session->userdata('leads_kanban_view') == 'true') { ?>
                     <div class="active kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
                        <div class="kanban-leads-sort">
                           <span class="bold"><?php echo _l('leads_sort_byfdg'); ?>: </span>
                           <a href="#" onclick="leads_kanban_sort('dateadded'); return false" class="dateadded">
                            <?php if(get_option('default_leads_kanban_sort') == 'dateadded'){echo '<i class="kanban-sort-icon fa fa-sort-amount-'.strtolower(get_option('default_leads_kanban_sort_type')).'"></i> ';} ?><?php echo _l('leads_sort_by_datecreated'); ?>
                            </a>
                           |
                           <a href="#" onclick="leads_kanban_sort('leadorder');return false;" class="leadorder">
                            <?php if(get_option('default_leads_kanban_sort') == 'leadorder'){echo '<i class="kanban-sort-icon fa fa-sort-amount-'.strtolower(get_option('default_leads_kanban_sort_type')).'"></i> ';} ?><?php echo _l('leads_sort_by_kanban_order'); ?>
                            </a>
                           |
                           <a href="#" onclick="leads_kanban_sort('lastcontact');return false;" class="lastcontact">
                            <?php if(get_option('default_leads_kanban_sort') == 'lastcontact'){echo '<i class="kanban-sort-icon fa fa-sort-amount-'.strtolower(get_option('default_leads_kanban_sort_type')).'"></i> ';} ?><?php echo _l('leads_sort_by_lastcontact'); ?>
                            </a>
                        </div>
                        <div class="row">
                           <div class="container-fluid leads-kan-ban">
                              <div id="kan-ban"></div>
                           </div>
                        </div>
                     </div>
                     <?php 
					 } 
					 else 
					 { 
?>
                     <div class="row" id="leads-table">
                        
                        <div class="col-md-12">
                           <!--<a href="#" data-toggle="modal" data-table=".table-leads" data-target="#leads_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>-->
                           <div class="modal fade bulk_actions" id="leads_bulk_actions" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                       <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                    </div>
                                    <div class="modal-body">
                                       <?php if(has_permission('leads','','delete')){ ?>
                                       <div class="checkbox checkbox-danger">
                                          <input type="checkbox" name="mass_delete" id="mass_delete">
                                          <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                       </div>
                                       <hr class="mass_delete_separator" />
                                       <?php } ?>
                                       <div id="bulk_change">
                                          <?php echo render_select('move_to_status_leads_bulk',$statuses,array('id','name'),'ticket_single_change_status'); ?>
                                          <?php
                                              echo render_select('move_to_source_leads_bulk',$sources,array('id','name'),'lead_source');
                                              echo render_datetime_input('leads_bulk_last_contact','leads_dt_last_contact');
                                              if(has_permission('leads','','edit')){
                                                echo render_select('assign_to_leads_bulk',$staff,array('staffid',array('firstname','lastname')),'leads_dt_assigned');
                                              }
                                             ?>
                                          <div class="form-group">
                                          <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                                          <input type="text" class="tagsinput" id="tags_bulk" name="tags_bulk" value="" data-role="tagsinput">
                                       </div>
                                       <hr />
                                       <div class="form-group no-mbot">
                                          <div class="radio radio-primary radio-inline">
                                              <input type="radio" name="leads_bulk_visibility" id="leads_bulk_public" value="public">
                                                 <label for="leads_bulk_public">
                                                   <?php echo _l('lead_public'); ?>
                                                </label>
                                          </div>
                                          <div class="radio radio-primary radio-inline">
                                              <input type="radio" name="leads_bulk_visibility" id="leads_bulk_private" value="private">
                                                 <label for="leads_bulk_private">
                                                   <?php echo _l('private'); ?>
                                                </label>
                                          </div>
                                       </div>
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                       <a href="#" class="btn btn-info" onclick="leads_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                    </div>
                                 </div>
                                 <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                           </div>
                           <!-- /.modal -->
                           <?php
                              $table_data = array();
                              $_table_data = array(
                               
								_l('id'),
								_l('Created Date'),									
								 _l('Customer Group'),
								 _l('Customer'),								 
                                _l('Cust. Type'), 
                                _l('Lead Title'),
                                _l('Opportunity(In Lacs)'),
                                _l('leads_dt_status'),
                                _l('Document Required By'),                              
                                _l('PM Approval'),                               
                                _l('TS Approval'),  										
								_l('Created By'),
																
                                array(
                                 'name'=>_l('Last Changed'),
                                 'th_attrs'=>array('class'=>'date-created')
                                 ));
								 
								
                              foreach($_table_data as $_t){
                               array_push($table_data,$_t);
                              }
                              /* $custom_fields = get_custom_fields('leads',array('show_on_table'=>1));
                              foreach($custom_fields as $field){
                               array_push($table_data,$field['name']);
                              } */
                              $table_data = do_action('leads_table_columns',$table_data);
                              $_op = _l('options');
                              array_push($table_data,$_op);
                              render_datatable($table_data,'leads'); ?>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   
   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Lead Created</h4>
		  </div>
		  <div class="modal-body">
			Do you want to add Item Requirement?
		  </div>
		  <div class="modal-footer">
			<a href="<?php echo base_url(); ?>admin/leads" class="btn btn-default">No</a>
			<a href='<?php echo base_url().'admin/lead_requirment/add_lead_requirement?id='.$_REQUEST['lead_id']; ?>' class="btn btn-primary">Yes</a>
		  </div>
		</div> 
	  </div>
	</div>


</div>
<?php include_once(APPPATH.'views/admin/leads/status.php'); ?>
<?php $this->load->view('admin/reports/includes/sales_js'); ?>
<?php init_tail(); ?>

<script>
   var c_leadid = '<?php echo $leadid; ?>';
</script>
<script>
   $(function(){
      leads_kanban();
   });
 
	var url_string = document.URL;

	var url = new URL(url_string);
	var c = url.searchParams.get("confirm");
	var id = url.searchParams.get("lead_id");
	console.log(url_string);
	if(c==1){
		$("#myModal").modal();
		
	}
	
	$(document).on('change', '.filtersummary', function (e) {
			var staff = $("#view_assigned").val();
			var months_report = $("#months_report").val();
			$('#filterdata').html("");
			$('#filterdata').append("");
			if(staff=='')
			{
				$('#filterdata').html("");
				$('#filterdata').append("");
				$('#original').removeClass("hide");
				$('#filterdata').addClass("hide");
			}
			else{
				$('#filterdata').html("");
				$('#filterdata').append("");
				$('#filterdata').removeClass("hide");
				$('#original').addClass("hide");
				var base_url = '<?php echo base_url() ?>';				
					$.ajax({
					  type: "GET",
					  url: base_url + "admin/leads/getFilterCountData",
					  data: {'staff_id': staff,'months_report': months_report},
					  dataType: "json",
					  success: function (data) {	
							$('#filterdata').html("");
						     $('#filterdata').append(data);							
						}
					}); 
			}
	});
   
</script>

<script>
$(document).ready(function(){
	   $("#item_details").hide();
	   $("#item_details_d").hide();
	   
	  
	   /*  $(document).on('change', '#view_assigned', function (e) {
		  var item = $(this).val();
		  alert(item);
		  
	   });  */
	   
	  
		   
  
   });
	
	$(document).on('change', '.selectpickerlr', function (e) {
		  var item = $(this).val();
		   var id = $(this).attr("id");
		  if(item.substring(0, 3)==='new'){
			$("#"+item).removeClass("hidden");
			$("#"+item).val("");
		  }else{
			 $("#new"+id).addClass("hidden"); 
			  $("#new"+id).val('');
		  }
	   });
	   
    $(function(){
       var LeadsServerParams = {
			"report_months": '[name="months-report"]',
			"report_from": '[name="report-from"]',
			"report_to": '[name="report-to"]',
		}
		
		table_leads = $('table.table-leads');
		
        var headers_leads = table_leads.find('th');
        initDataTable(table_leads, admin_url + 'leads/table', [headers_leads.length - 1, 0], [headers_leads.length - 1, 0], LeadsServerParams, [table_leads.find('th.date-created').index(), 'DESC']);
        
		$('#report-to').focusout(function(){
			
			table_leads.DataTable().ajax.reload()
				.columns.adjust()
				.responsive.recalc();
		});
       
		$('#report-from').focusout(function(){
			table_leads.DataTable().ajax.reload()
				.columns.adjust()
				.responsive.recalc();
		});
       
    });

	
</script>
</body>
</html>