 <?php  echo $this->session->userdata(); ?>
  
  <div class="modal fade modal-reminder reminder-modal-<?php echo $name; ?>-<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <?php echo form_open('admin/misc/add_reminder/'.$id . '/'.$name,array('id'=>'form-reminder-'.$name)); ?>
        <div class="modal-header">
          <button type="button" class="close close-reminder-modal" data-rel-id="<?php echo $id; ?>" data-rel-type="<?php echo $name; ?>" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Add follow up'; ?>" data-placement="bottom"></i> <?php echo 'Meeting / Follow Up'; ?></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
			<?php 
			    $this->db->where('id', $id);
				$region = $this->db->get('tblleads')->row()->region;
				
				$this->db->where('id', $id);
				$status = $this->db->get('tblleads')->row()->status;
				
				$this->db->where('region', $region);
				$region_id = $this->db->get('tblregion')->row()->id;
				
				$this->db->select('staffid,firstname,lastname');
				$this->db->from('tblstaff');
				$where = "role='7' AND region LIKE '%".$region_id."%'";				
				$this->db->where($where);
				$assignto = $this->db->get()->result_array();
				
			?>
              <?php echo form_hidden('rel_id',$id); ?>
              <?php echo form_hidden('rel_type',$name); ?>
			  <div class="col-md-6">
              <?php echo render_datetime_input('date','Meeting/Followup Date','',array('data-width'=>'100%','data-date-min-date'=>_d(date('Y-m-d')))); ?>
			  </div>
			<div class="form-group">
				<label class="control-label"> Assigned To</label>
				<div class="input-group col-md-6">
					<?php echo render_select('assigned_to',$assignto,array('staffid',array('firstname','lastname')),'','',array('data-width'=>'100%','data-none-selected-text'=>'Select User'),array(),'no-mbot'); ?>					
				</div>
			</div>
			  <?php echo form_hidden('staff',get_staff_user_id()); ?>
              <?php echo render_textarea('description','reminder_description'); ?>
			  <hr />
			  <div class="form-group">
                <div class="radio col-md-4">
                  <input type="radio" name="reminder_status" id="reminder_closed" value="Closed">
                  <label for="notify_by_email"><?php echo 'Closed'; ?></label>
                </div>
			  </div>
			  <div class="form-group">
				<div class="radio col-md-4">
                  <input type="radio" name="reminder_status" id="reminder_postponed" value="Postponed">
                  <label for="notify_by_email"><?php echo 'Postponed'; ?></label>
                </div>
              </div>
			 
              <?php if(total_rows('tblemailtemplates',array('slug'=>'reminder-email-staff','active'=>0)) == 0) { ?>
              <div class="form-group hide">
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" checked name="notify_by_email" id="notify_by_email">
                  <label for="notify_by_email"><?php echo _l('reminder_notify_me_by_email'); ?></label>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close-reminder-modal" data-rel-id="<?php echo $id; ?>" data-rel-type="<?php echo $name; ?>"><?php echo _l('close'); ?></button>
          <button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
<script>

   $(document).ready(function(){
		$('#reminder_postponed').click(function(){
			$("#date").attr("disabled", false); 
			$("#date").removeAttr('disabled');
		});
		$('#reminder_closed').click(function(){
			$("#date").attr("disabled", true); 
			//$("#date").removeAttr('disabled');
		});
	});
	
	$(document).ready(function(){
		$("#submit").click(function(){
			<?php 
			    $this->db->where('id', $id);
				$status = $this->db->get('tblleads')->row()->status;
			?>
			var stat = <?php echo $status; ?>;
			var istechnical = $('#assigned_to').find(":selected").text();
			if(istechnical != '' && stat < 3)
			{
				alert("Please change lead stage to Alignment & Selection");
				return false;
			}
				
			
		});
	});
</script>