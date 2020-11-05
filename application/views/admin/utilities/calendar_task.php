<div class="row">
				<!--<form action="<?php echo base_url(); ?>admin/utilities/calendar_task" id="calendar-task-form" method="post" accept-charset="utf-8">-->
				<?php 
					
					$customer_groups = $this->clients_model->get_groups();
				?>
				<?php 
						  $condtn = array('reporting_to LIKE' => '%'.get_staff_user_id().'%',
									'active' => '1');
						    $this->db->select()->from('tblstaff');
							$this->db->where($condtn);
							$query = $this->db->get();
							$staffs = $query->result_array();
							
						 ?>
						<div class="form-group col-md-4">
						<label class="control-label" for="lead-lead_source_id">User *</label>
						<select class="form-control" name="view_assigned" id="view_assigned">
						<option value="">--Select User--</option>
							<?php foreach($staffs as $staff) {
								$query = $this->db->get_where('tblleads', array('assigned' => $staff["staffid"]));
								$ifzsmleadvalue = $query->num_rows();
								if($ifzsmleadvalue > 0)
								{
								?>
                        <option value="<?php echo $staff['staffid']; ?>"> <?php echo $staff['firstname'].' '.$staff['lastname'];?></option>
							<?php 
								} 
								
								} 
								
							?>
							</select>
						  </div>
				<div class="form-group required col-sm-4">
                     <label class="control-label" for="lead-lead_source_id">Customer Group *</label>
                     <select class="form-control" name="customer_group" id="customer_group1" >
                       <option value="">--Select Customer Group--</option>
                     </select>
               
				</div>
              
                  <div class="form-group required col-sm-4">
                     <label class="control-label" for="lead-lead_name">Customer *</label>
                    
                     <select id="customer1" name="customer" class="form-control" >
                        <option value="">--Select Customer--</option>
                     </select>
                  </div>
                 
              
				 <div class="form-group col-sm-4" app-field-wrapper="description">
				 <label for="description" class="control-label">Lead *</label>
				  <select class="form-control" required id="rel_id1" name="rel_id1">
							<option value="">--Select Lead--</option>
						   
						 </select>
				  <input type="hidden" id="rel_type1" name="rel_type1" class="form-control" value="lead">
				 </div>
				</div>
				<hr>
				<div class="form-group" app-field-wrapper="description">
					<label for="description" class="control-label">Description</label>
					<textarea id="description" name="description" required class="form-control" rows="5"></textarea>
				</div>
				<div class="form-group" app-field-wrapper="start">
					<label for="start" class="control-label"><small class="req text-danger">* </small>Task Date</label>
					<div class="input-group date">
						<input type="text" id="start" name="start" class="form-control datetimepicker" value="" aria-invalid="false">
						<div class="input-group-addon">
							<i class="fa fa-calendar calendar-icon"></i>
						</div>
					</div>
				</div>
				
				<div class="clearfix mtop15"></div>
				
			<hr />
			<?php echo form_hidden('calendar_data_type','calendar_task'); ?>
			<div class="clearfix"></div>
			
			<input  value="<?php echo _l('submit'); ?>" type="submit" id="calendar_submit" name="calendar_task" class="btn btn-info pull-right">
				
		<!--	</form> -->
				