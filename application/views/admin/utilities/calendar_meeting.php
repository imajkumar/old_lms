
				<?php 
				$Hidden = explode(',','4,7,9,10,11,12,13');
				if (!(in_array(get_staff_role(), $Hidden))) {
				
					$customer_group1 = array();
		
					$this->db->select('customer_group');
					$this->db->from('tblleads');
					if(get_staff_role() == 1 ){
						$where = "assigned LIKE '%".get_staff_user_id()."%'";				
					}else{
						$where = "reportingto LIKE '%".get_staff_user_id()."%'  OR assigned IN(". get_staff_user_id() .")";				
					}
					$this->db->where($where);
					$customer_group = $this->db->get()->result_array();
					
					foreach($customer_group as $cg){
						array_push($customer_group1,$cg['customer_group']);
					}
					$customer_group_unique = array_unique($customer_group1);
					$cust_group_id = implode(',', $customer_group_unique);
			
					$qry = "SELECT * FROM `tblcustomersgroups` WHERE `id` IN(".$cust_group_id.")";
					$customer_groups =  $this->db->query($qry)->result_array(); 
				}
				?>
				
                 <div class="form-group required col-sm-6">
                     <label class="control-label" for="lead-lead_source_id">Customer Group *</label>
                     <select class="form-control selectpicker" name="customer_group" id="customer_group" data-width="100%" data-none-selected-text="Customer Group" data-live-search="true" >
                        <option value="">--Select Customer Group--</option>
                        <?php foreach($customer_groups as $customer_group) { ?>
                        <option value="<?php echo $customer_group['id']; ?>"> <?php echo $customer_group['name'];?></option>
                        <?php } ?>
                     </select>
               
				</div>
              
                  <div class="form-group required col-sm-6">
                     <label class="control-label" for="lead-lead_name">Customer *</label>
                    
                     <select id="my_l_searchd" name="customer" class="form-control" >
                        <option value="">--Select Customer--</option>
                     </select>
                  </div>
                 
              
				 <div class="form-group col-sm-6" app-field-wrapper="description">
				 <label for="description" class="control-label">Lead *</label>
				  <select class="form-control" id="rel_id" name="rel_id">
							<option value="">--Select Lead--</option>
						   
						 </select>
				  
				 </div>
				<div class="form-group col-sm-6">
					<label class="control-label"> Assigned To</label>
						<select class="form-control" id="assigned_to" name="assigned_to">
							<option value="">--Select Technical--</option>
						   
						</select>					
					
				</div>
				<hr>
				<?php echo render_textarea('description','Description','',array('rows'=>5)); ?>
				<div class="form-group" app-field-wrapper="start">
					<label for="start" class="control-label"><small class="req text-danger">* </small>Meeting/Followup Date</label>
					
					<div class="input-group date">
						<input type="text" id="start" data-date-min-date="<?php echo date('Y-m-d'); ?>" name="start" class="form-control datetimepicker" value="" aria-invalid="false">
						<div class="input-group-addon">
							<i class="fa fa-calendar calendar-icon"></i>
						</div>
					</div>
				</div>
				<?php echo form_hidden('staff',get_staff_user_id()); ?>
				<?php echo form_hidden('rel_type','lead'); ?>
				<?php echo form_hidden('calendar_data_type','calendar_meeting'); ?>
			  
				<div class="clearfix mtop15"></div>
				
			<hr />
			
			<div class="clearfix"></div>
						
			<div class="form-group">
                <div class="col-md-4">
                  <input type="radio" name="reminder_status" id="reminder_closed" value="Closed">
                  <label for="notify_by_email"><?php echo 'Closed'; ?></label>
                </div>
				<div class="col-md-4">
                  <input type="radio" name="reminder_status" id="reminder_postponed" value="Postponed">
                  <label for="notify_by_email"><?php echo 'Postponed'; ?></label>
                </div>
			  </div>
			
			<div class="checkbox checkbox-primary hide">
			  <input type="checkbox" name="public" id="public">
			  <label for="public"><?php echo _l('utility_calendar_new_event_make_public'); ?></label>
			</div>
			<input  value="<?php echo _l('submit'); ?>" type="submit" name="calendar_meeting" id="calendar_submit" class="btn btn-info pull-right">
				
			<!--	<?php echo form_close(); ?>-->