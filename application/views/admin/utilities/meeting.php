   <div class="modal fade task-modal-single in" id="meeting-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: block;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content data">
		<div class="modal-header task-single-header" data-task-single-id="7" data-status="1">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		   <h4 class="modal-title">Meeting/Followup Details</h4>		   
		</div>
        <div class="modal-body">
			<?php echo form_open('admin/misc/update_reminder/'.$meeting->id.'/'.$meeting->rel_type,array('id'=>'form-reminder-lead')); ?>
				   <div class="row">
					  <div class="col-md-8">
						 <?php 
						 
							if(!empty($meeting->rel_id)){
							 
							 $this->db->where('id', $meeting->rel_id);
							 $assigned = $this->db->get('tblleads')->row()->assigned;
							
							$this->db->where('id', $meeting->rel_id);
							 $lead_name = $this->db->get('tblleads')->row()->description;
							 
							$this->db->where('id', $meeting->rel_id);
							$customer_id = $this->db->get('tblleads')->row()->customer_name;
							
							$this->db->where('userid', $customer_id);
							$customer_name = $this->db->get('tblclients')->row()->company;
						?>		
						   
						  <div class="task-single-related-wrapper"><h4 class="bold font-medium mbot15"><a href="<?php echo base_url().'admin/leads/index/'.$meeting->rel_id; ?>"><?php echo '#'.$meeting->rel_id.' '. $customer_name.' ('.$lead_name.')   -   '. get_staff_full_name($assigned);  ?></a></h4></div>
						  <?php } ?>
						  <hr>
						  <div class="clearfix"></div>
						  <div class="form-group" app-field-wrapper="description"><label for="description" class="control-label">Description</label><textarea <?php if(get_staff_role() == '7'){ echo 'readonly'; } ?> id="description" name="description" class="form-control" rows="4"><?php echo $meeting->description; ?></textarea></div>
						  <p></p>
						  <?php if($meeting->assigned_to){ ?>
						  <div class="clearfix"></div>
						  <div class="form-group" app-field-wrapper="technical_comment"><label for="technical_comment" class="control-label">Technical Comment</label><textarea <?php if(get_staff_role() != '7'){ echo 'readonly'; } ?> id="technical_comment" name="technical_comment" class="form-control" rows="4"><?php echo $meeting->technical_comment; ?></textarea></div>
						  <p></p>
						  <?php } ?>
						  <div class="clearfix"></div>
						  <?php if($meeting->status != 5 && ($meeting->current_user_is_assigned || is_admin() || $meeting->current_user_is_creator)){ ?>
						  <p class="no-margin pull-left hide" style="<?php echo 'margin-'.(is_rtl() ? 'left' : 'right').':5px !important'; ?>">
							 <a href="#" class="btn btn-info" id="task-single-mark-complete-btn" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="mark_complete(<?php echo $meeting->id; ?>); return false;" data-toggle="tooltip" title="<?php echo _l('task_single_mark_as_complete'); ?>">
								<i class="fa fa-check"></i>
							 </a>
						  </p>
						  <?php } else if($meeting->status == 5 && ($meeting->current_user_is_assigned || is_admin() || $meeting->current_user_is_creator)){ ?>
						  <p class="no-margin pull-left hide" style="<?php echo 'margin-'.(is_rtl() ? 'left' : 'right').':5px !important'; ?>">
							 <a href="#" class="btn btn-default" id="task-single-unmark-complete-btn" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="unmark_complete(<?php echo $meeting->id; ?>); return false;" data-toggle="tooltip" title="<?php echo _l('task_unmark_as_complete'); ?>">
								<i class="fa fa-check"></i>
							 </a>
						  </p>
						  <?php } ?>
							
						
							
					  
				</div>
				<div class="col-md-4 task-single-col-right">
				  
					<h4 class="task-info-heading"><?php echo 'Meeting/Followup Info'; ?></h4>
					<div class="clearfix"></div>
					<h5 class="no-mtop task-info-created">
				   <?php if(($meeting->creator != 0 )){ ?>
				   <small class="text-dark"><?php echo _l('task_created_by','<span class="text-dark">'.(get_staff_full_name($meeting->creator)).'</span>'); ?> <i class="fa fa-clock-o" data-toggle="tooltip" data-title="<?php echo _l('task_created_at',_dt($meeting->createddate)); ?>"></i></small>
				   <br />
				  
				   <?php } else { ?>
				   <small class="text-dark"><?php echo _l('task_created_at','<span class="text-dark">'._dt($meeting->createddate).'</span>'); ?></small>
				   <?php } ?>
				</h5>
				<hr class="task-info-separator" />
				<div class="task-info task-status task-info-status">
				   <h5>
					  <i class="fa fa-<?php if($meeting->reminder_status == 'Completed'){echo 'star';} else if($meeting->reminder_status == 'Postpond'){echo 'star-o';} else {echo 'star-half-o';} ?> pull-left task-info-icon fa-fw fa-lg"></i><?php echo _l('task_status'); ?>:
					  <?php if($meeting->creator || $meeting->staff) { ?>
					  <span class="task-single-menu task-menu-status">
						 <span class="<?php if($meeting->creator) { ?>trigger<?php } ?> pointer manual-popover text-has-action">
							<?php echo $meeting->reminder_status; 
							
							?>
						 </span>
						 
					  </span>
					  <?php }  ?>
				   </h5>
				</div>

						<div class="task-info task-single-inline-wrap task-info-start-date">
						   <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
								<?php echo 'Meeting Date'; ?>:<br>
								<div class="input-group date">
									<input type="text" id="date_meeting" name="date" class="form-control datetimepicker" data-width="100%" data-date-min-date="<?php echo date('Y-m-d'); ?>" disabled value="<?php echo $meeting->date; ?>">
									<div class="input-group-addon">
										<i class="fa fa-calendar calendar-icon"></i>
									</div>
								</div>
							  
						   </h5>
						</div>

						<hr class="task-info-separator" />
						<div class="clearfix"></div>

						<div class="task_users_wrapper">
						   <?php
						   
						   
						if ($meeting->assigned_to == '') {
						   echo '<div class="text-danger display-block">'._l('task_no_assignees').'</div>';
						}else{
							echo '<h4 class="task-info-heading font-normal font-medium-xs"><i class="fa fa-user-o" aria-hidden="true"></i> Assignees</h4>
						   <div class="task-user"  data-toggle="tooltip">'.get_staff_full_name($meeting->assigned_to).'</div>';
						}
						?>
						</div>
						<hr class="task-info-separator" />
						<div class="clearfix"></div>
				<!--------------------- Closed ------------------>
						<div class="col-md-12 task-single-inline-wrap" id="foobar">
								<div class="radio col-md-6">
								  <input type="radio" class='rg' <?php if($meeting->reminder_status =='Closed' || get_staff_role() == '7'){ echo 'checked'; }  ?> name="reminder_status" id="reminder_closed_meeting" value="Closed">
								  <label for="notify_by_email"><?php echo 'Closed'; ?></label>
								</div>
							 
							  <?php if(get_staff_role() != '7'){ ?>
								<div class="radio col-md-6" style="margin-top: 10px;">
								  <input type="radio" class='rg' <?php if($meeting->reminder_status =='Closed'){ echo 'disabled'; }  ?>  name="reminder_status" id="reminder_postponed_meeting" value="Postponed">
								  <label for="notify_by_email"><?php echo 'Postponed'; ?></label>
								</div>
							  <?php } ?>
						 </div>
						 <?php echo form_hidden('rel_id',$meeting->rel_id); ?>
						 <?php echo form_hidden('assigned_to',$meeting->assigned_to); ?>
						 <?php echo form_hidden('staff',$meeting->staff); ?>
						 <?php echo form_hidden('rel_type',$meeting->rel_type); ?>
						<?php if($meeting->reminder_status !='Closed' && ($meeting->creator == get_staff_user_id() || $meeting->assigned_to == get_staff_user_id())){ ?>	  
						 <button type="submit" id="submit" class="btn btn-info">Save</button>
						<?php } ?>
				</div>
			</div>
			</form>	
		</div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	
	$(function () {
    $('#foobar input[type=radio]').change(function(){
      if($(this).val() =='Postponed'){
		  $("#date_meeting").attr("disabled", false); 
			$("#date_meeting").removeAttr('disabled');
	  }else{
		  $("#date_meeting").attr("disabled", true); 
	  }
      
      })
})
$('.datetimepicker').datetimepicker({
    format:'Y-m-d H:i',
    step:15
});
  /* $(document).ready(function(){
		$('#reminder_postponed_meeting').click(function(){
			$("#date_meeting").attr("disabled", false); 
			$("#date_meeting").removeAttr('disabled');
		});
		$('#reminder_closed_meeting').click(function(){
			$("#date_meeting").attr("disabled", true); 
			//$("#date").removeAttr('disabled');
		});
	}); */

	/* $(document).ready(function(){
		$("#submit").click(function(){
			<?php 
			    $this->db->where('id', $meeting->rel_id);
				$status = $this->db->get('tblleads')->row()->status;
				$istechnical = $meeting->assigned_to;
			?>
			var stat = <?php echo $status; ?>;
			var istechnical = <?php echo $istechnical; ?>;
			if(istechnical != '' && stat < 3)
			{
				alert("Please change lead stage to Alignment & Selection");
				return false;
			}
				
			
		});
	}); */
</script>