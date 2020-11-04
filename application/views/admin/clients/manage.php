<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_filters _hidden_inputs hidden">
                    <?php
                    echo form_hidden('my_customers');
                    foreach($groups as $group){
                       echo form_hidden('customer_group_'.$group['id']);
                   }
                   foreach($contract_types as $type){
                       echo form_hidden('contract_type_'.$type['id']);
                   }
                   foreach($invoice_statuses as $status){
                       echo form_hidden('invoices_'.$status);
                   }
                   foreach($estimate_statuses as $status){
                       echo form_hidden('estimates_'.$status);
                   }
                   foreach($project_statuses as $status){
                    echo form_hidden('projects_'.$status['id']);
                }
                foreach($proposal_statuses as $status){
                    echo form_hidden('proposals_'.$status);
                }
                foreach($customer_admins as $cadmin){
                    echo form_hidden('responsible_admin_'.$cadmin['staff_id']);
                }
                foreach($countries as $country){
                    echo form_hidden('country_'.$country['country_id']);
                }
                ?>
            </div>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="_buttons">
                        <?php 
						
						if (has_permission('customers','','create')) { ?>
                        <a href="<?php echo admin_url('clients/client'); ?>" class="btn btn-default btn-icon pull-left display-block">
                            <?php echo _l('new_client'); ?></a>
							
							<?php
								if(get_staff_role() == 0 ) 
								{
							?>
                            <a href="<?php echo admin_url('clients/import'); ?>" class="btn btn-default btn-icon pull-left display-block">
                                <?php echo _l('import_customers'); ?></a>
                                <?php } ?>
                                <?php } ?>
								<?php
									if(is_admin() || get_staff_role() == 8 ) 
									{
								?>
                                <a href="<?php echo admin_url('clients/all_contacts'); ?>" class="btn btn-default btn-icon pull-left display-block">
                                    <?php echo _l('customer_contacts'); ?></a>
								<?php } ?>
                                    <div class="visible-xs">
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="btn-group pull-right btn-with-tooltip-group _filter_data hide" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                                            <li class="active"><a href="#" data-cview="all" onclick="dt_custom_view('','.table-clients',''); return false;"><?php echo _l('customers_sort_all'); ?></a>
                                            </li>
                                             <li class="divider"></li>
                                             <li>
                                                  <a href="#" data-cview="my_customers" onclick="dt_custom_view('my_customers','.table-clients','my_customers'); return false;">
                                                           <?php echo _l('customers_assigned_to_me'); ?>
                                                        </a>
                                             </li>
                                            <li class="divider"></li>
                                            <?php if(count($groups) > 0){ ?>
                                            <li class="dropdown-submenu pull-left groups">
                                                <a href="#" tabindex="-1"><?php echo _l('customer_groups'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($groups as $group){ ?>
                                                    <li><a href="#" data-cview="customer_group_<?php echo $group['id']; ?>" onclick="dt_custom_view('customer_group_<?php echo $group['id']; ?>','.table-clients','customer_group_<?php echo $group['id']; ?>'); return false;"><?php echo $group['name']; ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <?php } ?>
                                            <?php if(count($countries) > 1){ ?>
                                            <li class="dropdown-submenu pull-left countries">
                                                <a href="#" tabindex="-1"><?php echo _l('clients_country'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($countries as $country){ ?>
                                                    <li><a href="#" data-cview="country_<?php echo $country['country_id']; ?>" onclick="dt_custom_view('country_<?php echo $country['country_id']; ?>','.table-clients','country_<?php echo $country['country_id']; ?>'); return false;"><?php echo $country['short_name']; ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <?php } ?>
                                            <li class="dropdown-submenu pull-left invoice">
                                                <a href="#" tabindex="-1"><?php echo _l('invoices'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($invoice_statuses as $status){ ?>
                                                    <li>
                                                        <a href="#" data-cview="invoices_<?php echo $status; ?>" onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-clients','invoices_<?php echo $status; ?>'); return false;"><?php echo _l('customer_have_invoices_by',format_invoice_status($status,'',false)); ?></a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left estimate">
                                                <a href="#" tabindex="-1"><?php echo _l('estimates'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($estimate_statuses as $status){ ?>
                                                    <li>
                                                        <a href="#" data-cview="estimates_<?php echo $status; ?>" onclick="dt_custom_view('estimates_<?php echo $status; ?>','.table-clients','estimates_<?php echo $status; ?>'); return false;">
                                                            <?php echo _l('customer_have_estimates_by',format_estimate_status($status,'',false)); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left project">
                                                <a href="#" tabindex="-1"><?php echo _l('projects'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($project_statuses as $status){ ?>
                                                    <li>
                                                        <a href="#" data-cview="projects_<?php echo $status['id']; ?>" onclick="dt_custom_view('projects_<?php echo $status['id']; ?>','.table-clients','projects_<?php echo $status['id']; ?>'); return false;">
                                                            <?php echo _l('customer_have_projects_by',$status['name']); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left proposal">
                                                <a href="#" tabindex="-1"><?php echo _l('proposals'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($proposal_statuses as $status){ ?>
                                                    <li>
                                                        <a href="#" data-cview="proposals_<?php echo $status; ?>" onclick="dt_custom_view('proposals_<?php echo $status; ?>','.table-clients','proposals_<?php echo $status; ?>'); return false;">
                                                            <?php echo _l('customer_have_proposals_by',format_proposal_status($status,'',false)); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <div class="clearfix"></div>
                                            <?php if(count($contract_types) > 0) { ?>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left contract_types">
                                                <a href="#" tabindex="-1"><?php echo _l('contract_types'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($contract_types as $type){ ?>
                                                    <li>
                                                        <a href="#" data-cview="contract_type_<?php echo $type['id']; ?>" onclick="dt_custom_view('contract_type_<?php echo $type['id']; ?>','.table-clients','contract_type_<?php echo $type['id']; ?>'); return false;">
                                                            <?php echo _l('customer_have_contracts_by_type',$type['name']); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                            <?php if(count($customer_admins) > 0 && (has_permission('customers','','create') || has_permission('customers','','edit'))){ ?>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left responsible_admin">
                                                <a href="#" tabindex="-1"><?php echo _l('responsible_admin'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($customer_admins as $cadmin){ ?>
                                                    <li>
                                                        <a href="#" data-cview="responsible_admin_<?php echo $cadmin['staff_id']; ?>" onclick="dt_custom_view('responsible_admin_<?php echo $cadmin['staff_id']; ?>','.table-clients','responsible_admin_<?php echo $cadmin['staff_id']; ?>'); return false;">
                                                            <?php echo get_staff_full_name($cadmin['staff_id']); ?>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php if(has_permission('customers','','view') || have_assigned_customers()) {
                                    $where_summary = '';
                                    if(!has_permission('customers','','view')){
										
										if(get_staff_role() > 1 ) 
										{
											$where_summary = ' AND userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id='.get_staff_user_id().')';
										}else{
											$where_summary = ' AND addedfrom IN ('.get_staff_user_id().')';
										}
                                    }
                                    ?>
                                    
                                    <div class="row mbot15 hide">
									<hr class="hr-panel-heading" />
                                        <div class="col-md-12">
                                            <h4 class="no-margin"><?php echo _l('customers_summary'); ?></h4>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblclients',($where_summary != '' ? substr($where_summary,5) : '')); ?></h3>
                                            <span class="text-dark"><?php echo _l('customers_summary_total'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblclients','active=1'.$where_summary); ?></h3>
                                            <span class="text-success"><?php echo _l('active_customers'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblclients','active=0'.$where_summary); ?></h3>
                                            <span class="text-danger"><?php echo _l('inactive_active_customers'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblcontacts','active=1'.$where_summary); ?></h3>
                                            <span class="text-info"><?php echo _l('customers_summary_active'); ?></span>
                                        </div>
                                        <div class="col-md-2  col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblcontacts','active=0'.$where_summary); ?></h3>
                                            <span class="text-danger"><?php echo _l('customers_summary_inactive'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6">
                                            <h3 class="bold"><?php echo total_rows('tblcontacts','last_login LIKE "'.date('Y-m-d').'%"'.$where_summary); ?></h3>
                                            <span class="text-muted">
                                                <?php
                                                $contactsTemplate = '';
                                                if(count($contacts_logged_in_today)> 0){
                                                   foreach($contacts_logged_in_today as $contact){
                                                    $url = admin_url('clients/client/'.$contact['userid'].'?contactid='.$contact['id']);
                                                    $fullName = $contact['firstname'] . ' ' . $contact['lastname'];
                                                    $dateLoggedIn = _dt($contact['last_login']);
                                                    $html = "<a href='$url' target='_blank'>$fullName</a><br /><small>$dateLoggedIn</small><br />";
                                                    $contactsTemplate .= htmlspecialchars('<p class="mbot5">'.$html.'</p>');
                                                }
                                                ?>
                                                <?php } ?>
                                                <span<?php if($contactsTemplate != ''){ ?> class="pointer text-has-action" data-toggle="popover" data-title="<?php echo _l('customers_summary_logged_in_today'); ?>" data-html="true" data-content="<?php echo $contactsTemplate; ?>" data-placement="bottom" <?php } ?>><?php echo _l('customers_summary_logged_in_today'); ?></span>
                                            </span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <hr class="hr-panel-heading" />
                                       
                                        <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                              <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                           </div>
                                           <div class="modal-body">
                                              <?php if(has_permission('customers','','delete')){ ?>
                                              <div class="checkbox checkbox-danger">
                                                <input type="checkbox" name="mass_delete" id="mass_delete">
                                                <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                            </div>
                                            <hr class="mass_delete_separator" />
                                            <?php } ?>
                                            <div id="bulk_change">
                                               <?php echo render_select('move_to_groups_customers_bulk[]',$groups,array('id','name'),'customer_groups','', array('multiple'=>true),array(),'','',false); ?>
                                               <p class="text-danger"><?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                                           </div>
                                       </div>
                                       <div class="modal-footer">
                                           <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                           <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                       </div>
                                   </div><!-- /.modal-content -->
                               </div><!-- /.modal-dialog -->
                           </div><!-- /.modal -->
						<!---     Filter started ------------------------------------------>						   
                        <div class="row">
							<div class="col-md-2 <?php if(get_staff_role() == 1){ echo 'hide'; } ?>">
								<div class="col-md-12 leads-filter-column">
							     	 <select class="form-control selectpicker" data-width="100%" data-none-selected-text="Staff" data-live-search="true"  name="view_assigned" id="view_assigned">
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
							</div>
                           <div class="col-md-8">
							   <div class="checkbox col-md-2">
									<?php
										$numrecord = $this->clients_model->count_active_client('1');
										//print_r($numrecord);
										
									?>
									
									<input type="checkbox" id="activerec" name="active">
									<label for="exclude_inactive"><?php echo _l('Active'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								
								<div class="checkbox col-md-2">
								<br>
								<?php
										$numrecord = $this->clients_model->count_active_client('0');
										
									?>
									<input type="checkbox" id="inactive" name="inactive">
									<label for="exclude_inactive"><?php echo _l('Inactive'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								
								<div class="checkbox col-md-2">
								<br>
								<?php
										$numrecord = $this->clients_model->count_client('1');
										
									?>
									<input type="checkbox" id="approved" name="approved">
									<label for="exclude_inactive"><?php echo _l('Approved'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								<div class="checkbox col-md-2">
								<br>
								<?php
										$numrecord = $this->clients_model->count_client('0');
										
									?>
									<input type="checkbox" checked id="unapproved" name="unapproved">
									<label for="exclude_inactive"><?php echo _l('Unapproved'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								<div class="checkbox col-md-2">
								<br>
								<?php
										$numrecord = $this->clients_model->count_client('2');
										
									?>
									<input type="checkbox" id="resubmit" name="resubmit">
									<label for="exclude_inactive"><?php echo _l('Resubmitted'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								<div class="checkbox col-md-2">
								<br>
								<?php
										$numrecord = $this->clients_model->count_client('3');
										
									?>
									<input type="checkbox" id="reject" name="reject">
									<label for="exclude_inactive"><?php echo _l('Rejected'); ?> <strong> (<?php echo $numrecord->num_of_record; ?>)</strong></label>
								</div>
								
							</div>
							
							  <div class="col-md-2" id="report-time">
								<select class="selectpicker" id="months-report" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
								   <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
								   <option value="this_month"><?php echo _l('this_month'); ?></option>
								   <option value="last_month"><?php echo _l('last_month'); ?></option>
								   <option value="this_year"><?php echo _l('this_year'); ?></option>
								   <option value="last_year"><?php echo _l('last_year'); ?></option>
								   <option value="report_sales_months_three_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
								   <option value="report_sales_months_six_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
								   <option value="report_sales_months_twelve_months" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
								</select>
								<br/><br/>
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
							
							
							
                           <div class="clearfix mtop20"></div>
                           <?php
                           $table_data = array();
                           $_table_data = array(
                           /*  '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>', */
                             _l('id'),
                            _l('customer_groups'), 
                            _l('Customer'),
                            _l('Cust No.'),
                            _l('contact_primary'),
                            _l('Mobile'),
                            _l('company_primary_email'),
                            _l('customer_active'),
                            _l('Created By'),							
                           _l('Date Created'),							
                            _l('App. Status'),							
                            );

                           foreach($_table_data as $_t){
                            array_push($table_data,$_t);
                        }

                        $custom_fields = get_custom_fields('customers',array('show_on_table'=>1));
                        foreach($custom_fields as $field){
                            array_push($table_data,$field['name']);
                        }

                        $table_data = do_action('customers_table_columns',$table_data);

                        $_op = _l('options');

                        array_push($table_data, $_op);
                        render_datatable($table_data,'clients');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        var CustomersServerParams = {};
        $.each($('._hidden_inputs._filters input'),function(){
           CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
       });
        CustomersServerParams['activerec'] = '[name="activerec"]:checked';
        CustomersServerParams['inactive'] = '[name="inactive"]:checked';
        CustomersServerParams['approved'] = '[name="approved"]:checked';
        CustomersServerParams['unapproved'] = '[name="unapproved"]:checked';
        CustomersServerParams['resubmit'] = '[name="resubmit"]:checked';
        CustomersServerParams['reject'] = '[name="reject"]:checked';
		CustomersServerParams['assigned'] = '[name="view_assigned"]';
		CustomersServerParams['report_months'] = '[name="months-report"]';
        CustomersServerParams['report_from'] = '[name="report-from"]';
        CustomersServerParams['report_to'] = '[name="report-to"]';

        var headers_clients = $('.table-clients').find('th');
        var not_sortable_clients = (headers_clients.length - 1);
        var tAPI = initDataTable('.table-clients', admin_url+'clients/table', [not_sortable_clients,0], [not_sortable_clients,0], CustomersServerParams,<?php echo do_action('customers_table_default_order',json_encode(array(1,'DESC'))); ?>);
        
		$('#activerec').on('change',function(){
			console.log('clicked active');
            tAPI.ajax.reload();
        });
		$('input[name="inactive"]').on('change',function(){
			console.log('clicked inactive');
            tAPI.ajax.reload();
        });
		$('input[name="approved"]').on('change',function(){
			console.log('clicked approved');
            tAPI.ajax.reload();
        });
		$('input[name="unapproved"]').on('change',function(){
			tAPI.ajax.reload();
        });
		
		$('input[name="reject"]').on('change',function(){
            tAPI.ajax.reload();
        });
		
		$('input[name="resubmit"]').on('change',function(){
            tAPI.ajax.reload();
        });
		$('#view_assigned').on('change',function(){
            tAPI.ajax.reload();
        });
		$('#months-report').on('change',function(){
            tAPI.ajax.reload();
        });
		$('#report-to').focusout(function(){
			tAPI.ajax.reload();
		});
       
		$('#report-from').focusout(function(){
			tAPI.ajax.reload();
		});
		
		
    });
    function customers_bulk_action(event) {
        var r = confirm(appLang.confirm_action_prompt);
        if (r == false) {
            return false;
        } else {
            var mass_delete = $('#mass_delete').prop('checked');
            var ids = [];
            var data = {};
            if(mass_delete == false || typeof(mass_delete) == 'undefined'){
                data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                if (data.groups.length == 0) {
                    data.groups = 'remove_all';
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $('.table-clients').find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function(){
              $.post(admin_url + 'clients/bulk_action', data).done(function() {
               window.location.reload();
           });
          },50);
        }
    }


</script>
</body>
</html>
