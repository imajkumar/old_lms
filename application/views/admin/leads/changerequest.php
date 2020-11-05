<?php init_head(); 

$has_permission_view = has_permission('lead_change_request','','edit');
$has_permission_approve = has_permission('lead_change_request','','approval');

?>
<style>
   .main{
   background: #fbfdff;
   }
   .panel-body.bottom-transaction {
   background: #fff !important; 
   }
   .panel_s .panel-body {
   border: 1px solid #fff !important;
   }
</style>
<div id="wrapper">
   <div class="content">
      
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               
			<div class="row">
			<div class="col-md-12">
                     <h4>Lead Change Request</h4>
                     <button  data-toggle="modal" data-target="#newEventModal" class="btn btn-info mright5 test pull-right display-block">
                     Add Change Request</button>
                  </div>                  
				<div class="col-md-12">
					<ul class="nav nav-tabs ">
					  <li class="active"><a href="#academic" data-toggle="tab" aria-expanded="true"> Pending</a></li>
					   <li><a href="#extra" data-toggle="tab" aria-expanded="false">Approved</a></li>
					   <li><a href="#rejected" data-toggle="tab" aria-expanded="false">Rejected</a></li>
					</ul>
					<div class="tab-content">
					  <div id="academic" class="tab-pane fade active in">
						  <div class="col-md-12">
							 <br>
							 <table class="table dt-table scroll-responsive">
								<thead style="background: #f47b34;color: #fff;">
								   <tr>
									  <th><?php echo 'Sr. No.'; ?></th>
									  <th><?php echo 'Lead ID'; ?></th>
									  <th><?php echo  'Lead Title'; ?></th>
									  <th><?php echo 'Customer'; ?></th>
									  <th><?php echo 'Remarks'; ?></th>
									  <th><?php echo 'Added By'; ?></th>
									  <th><?php echo 'Status'; ?></th>
									  <th><?php echo 'Request Date'; ?></th>
									  <?php
										if($has_permission_approve || $has_permission_view || is_admin()){
									  ?>
									  <th style="width:200px;"><?php echo 'Options'; ?></th>
									<?php } ?>
								   </tr>
								</thead>
								<tbody>
								   <?php
									  foreach($result_pending as $data_l)
									  {                              	
										$sql    = "SELECT reportingto FROM tblleads WHERE id='" . $data_l['leadid'] . "'";
										$reportingto = $this->db->query($sql)->row()->reportingto;
										
																
										$staff_reportingto = explode(',',$reportingto);
										if(in_array(get_staff_user_id(), $staff_reportingto) || $data_l['addedby'] == get_staff_user_id() || is_admin() || $has_permission_view){
									  ?>
								   <tr>
									  <td>
										 <?php echo $data_l['id']; ?>
									  </td>
									  <td><input type="hidden" name='lead_id' class="form-control" value="<?php echo $data_l['leadid']; ?>">
										 <?php echo $data_l['leadid']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_lead_description($data_l['leadid']); ?>
									  </td>
									  <td>
										 <?php 
												$customer =  $this->leads_model->get_lead_customer($data_l['leadid']); 
												echo $this->leads_model->get_customer_name($customer); 
												
										 ?>
									  </td>
									  <td>
										 <?php echo $data_l['remark']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_emp_name($data_l['addedby']); ?>
									  </td>
									  <!--<td>
										 <?php echo $this->leads_model->get_emp_name($data_l['assignedto']); ?>
									  </td>-->
									  <td>
										 <?php
											$this->db->select('tblstaff.firstname,tblstaff.lastname');
											$this->db->from('tblstaffpermissions');
											$this->db->join('tblstaff','tblstaff.staffid = tblstaffpermissions.staffid');
											$this->db->where(array('permissionid'=>'28','can_edit'=>'1'));
											$query = $this->db->get();
											$resulta = $query->result();
											if($data_l['status']=='Approved'){
												if(empty($resulta)){
													echo 'Pending at Admin';
												}else{
													echo 'Pending at '.$resulta[0]->firstname .' '. $resulta[0]->lastname;
												}
												
											}else if($data_l['status']=='Pending'){
												$this->db->select('tblstaff.firstname,tblstaff.lastname');
												$this->db->from('tblstaffpermissions');
												$this->db->join('tblstaff','tblstaff.staffid = tblstaffpermissions.staffid');
												$this->db->where(array('permissionid'=>'28','can_approval'=>'1'));
												$query = $this->db->get();
												$resulta = $query->result();
												echo 'Pending at '.$resulta[0]->firstname .' '. $resulta[0]->lastname;
											}else{
												echo $data_l['status'];
											}
											?>
									  </td>
									  <td>
										 <?php 
										 $timestamp1 = strtotime($data_l['created']);
										$date1 = date("d-m-Y", $timestamp1);
										 echo $date1; ?>
										 
									  </td>
									  <?php
										if($has_permission_approve || $has_permission_view || is_admin()){
									  ?>
									  <td>
										<?php 
											if($data_l['status']=='Updated'){
												
												echo '<a href="/" class="btn btn-default btn-icon" onclick="return false;">Status Updated</a>';
											}else if($data_l['status']=='Approved' ){
												if($has_permission_view){
										?>
											<a onclick="return confirm('Are you sure to update change request?')" class="btn btn-default btn-icon" href="<?php echo base_url('admin/leads/manage/').$data_l['leadid']; ?>"><i class="fa fa-pencil-square-o"></i> Update Status</a>
										<?php	
												}
											} else if($has_permission_approve && !(is_admin())){	
										?>
										 <a onclick="return confirm('Are you sure to approved status?')" class="btn btn-default btn-icon" style="text-transform: capitalize !important;" href="<?php echo base_url('admin/leads/updatechangerequest/').$data_l['id'].'/'.$data_l['addedby']; ?>"><i class="fa fa-pencil-square-o"></i> Approved</a>
										 <a onclick="myFunction(<?php echo $data_l['id']; ?>,<?php echo $data_l['leadid']; ?>)" style="text-transform: capitalize !important;" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i> Reject</a>
										 
										 <?php 
											}else{
												echo '<a href="/" class="btn-icon" onclick="return false;">Awaiting Approval</a>';
											}
										 ?>
									  </td>
									<?php } ?>
								   </tr>
								   <?php $i++; 
										}
									  } ?>
								</tbody>
							 </table>
						  </div>
					   
              		  </div>
					  <div id="extra" class="tab-pane">
						 <div class="col-md-12">
							 <br>
							 <table class="table dt-table scroll-responsive">
								<thead style="background: #f47b34;color: #fff;">
								   <tr>
									  <th><?php echo 'Sr. No.'; ?></th>
									  <th><?php echo 'Lead ID'; ?></th>
									  <th><?php echo  'Lead Title'; ?></th>
									  <th><?php echo  'Customer'; ?></th>
									  <th><?php echo 'Remarks'; ?></th>
									  <th><?php echo 'Added By'; ?></th>
									  <!--<th><?php echo 'Assigned To'; ?></th>-->
									  <th><?php echo 'Status'; ?></th>
									  <th><?php echo 'Request Date'; ?></th>
									  <?php
										if($has_permission_view || is_admin()){
									  ?>
									  <th><?php echo 'Options'; ?></th>
									<?php } ?>
								   </tr>
								</thead>
								<tbody>
								   <?php
									  foreach($result_completed as $data_l)
									  {                              	
										$sql    = "SELECT reportingto FROM tblleads WHERE id='" . $data_l['leadid'] . "'";
										$reportingto = $this->db->query($sql)->row()->reportingto;
										
																
										$staff_reportingto = explode(',',$reportingto);
										if(in_array(get_staff_user_id(), $staff_reportingto) || $data_l['addedby'] == get_staff_user_id() || is_admin() || $has_permission_view){
									  ?>
								   <tr>
									  <td>
										 <?php echo $data_l['id']; ?>
									  </td>
									  <td><input type="hidden" name='lead_id' class="form-control" value="<?php echo $data_l['leadid']; ?>">
										 <?php echo $data_l['leadid']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_lead_description($data_l['leadid']); ?>
									  </td>
									   <td>
										 <?php 
												$customer =  $this->leads_model->get_lead_customer($data_l['leadid']); 
												echo $this->leads_model->get_customer_name($customer); 
												
										 ?>
									  </td>
									  <td>
										 <?php echo $data_l['remark']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_emp_name($data_l['addedby']); ?>
									  </td>
									  <!--<td>
										 <?php echo $this->leads_model->get_emp_name($data_l['assignedto']); ?>
									  </td>-->
									  <td>
										 <?php
											echo $data_l['status'];
											?>
									  </td>
									  <td>
										 <?php 
										 $timestamp1 = strtotime($data_l['created']);
										$date1 = date("d-m-Y", $timestamp1);
										 echo $date1; ?>
										 
									  </td>
									  <?php
										if($has_permission_view || is_admin()){
									  ?>
									  <td>
										<?php 
											if($data_l['status']=='Updated'){
												
												echo '<a href="/" class="btn-icon" onclick="return false;">Status Updated</a>';
											}else if($data_l['status']=='Approved'){
										?>
											<a onclick="return confirm('Are you sure to update change request?')" class="btn btn-default btn-icon" style="text-transform: capitalize !important;" href="<?php echo base_url('admin/leads/manage/').$data_l['leadid']; ?>"><i class="fa fa-pencil-square-o"></i> Update Status</a>
										<?php	
										} else{	
										?>
										 <a onclick="return confirm('Are you sure to approved status?')" class="btn-icon" style="text-transform: capitalize !important;" href="<?php echo base_url('admin/leads/updatechangerequest/').$data_l['id']; ?>"><i class="fa fa-pencil-square-o"></i> Approved </a>
										 
										 <?php 
											}
										 ?>
									  </td>
									<?php } ?>
								   </tr>
								   <?php $i++; 
										}
									  } ?>
								</tbody>
							 </table>
						  </div>
					   
					  </div>
					  <div id="rejected" class="tab-pane">
						 <div class="col-md-12">
							 <br>
							 <table class="table dt-table scroll-responsive">
								<thead style="background: #f47b34;color: #fff;">
								   <tr>
									  <th><?php echo 'Sr. No.'; ?></th>
									  <th><?php echo 'Lead ID'; ?></th>
									  <th><?php echo  'Lead Title'; ?></th>
									  <th><?php echo  'Customer'; ?></th>
									  <th><?php echo 'Remarks'; ?></th>
									  <th><?php echo 'Added By'; ?></th>
									  <th><?php echo 'Reason'; ?></th>
									  <th><?php echo 'Status'; ?></th>
									  <th><?php echo 'Request Date'; ?></th>
									  
								   </tr>
								</thead>
								<tbody>
								   <?php
									  foreach($result_rejected as $data_l)
									  {                              	
										$sql    = "SELECT reportingto FROM tblleads WHERE id='" . $data_l['leadid'] . "'";
										$reportingto = $this->db->query($sql)->row()->reportingto;
										
																
										$staff_reportingto = explode(',',$reportingto);
										if(in_array(get_staff_user_id(), $staff_reportingto) || $data_l['addedby'] == get_staff_user_id() || is_admin() || $has_permission_view){
									  ?>
								   <tr>
									  <td>
										 <?php echo $data_l['id']; ?>
									  </td>
									  <td><input type="hidden" name='lead_id' class="form-control" value="<?php echo $data_l['leadid']; ?>">
										 <?php echo $data_l['leadid']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_lead_description($data_l['leadid']); ?>
									  </td>
									   <td>
										 <?php 
												$customer =  $this->leads_model->get_lead_customer($data_l['leadid']); 
												echo $this->leads_model->get_customer_name($customer); 
												
										 ?>
									  </td>
									  <td>
										 <?php echo $data_l['remark']; ?>
									  </td>
									  <td>
										 <?php echo $this->leads_model->get_emp_name($data_l['addedby']); ?>
									  </td>
									  
									  <td>
										 <?php
											echo $data_l['updateremark'];
											?>
									  </td>
									  <td>
										 <?php
											echo $data_l['status'];
											?>
									  </td>
									  <td>
										 <?php 
										 $timestamp1 = strtotime($data_l['created']);
										$date1 = date("d-m-Y", $timestamp1);
										 echo $date1; ?>
										 
									  </td>
									  
								   </tr>
								   <?php $i++; 
										}
									  } ?>
								</tbody>
							 </table>
						  </div>
					   
					  </div>
					  
					</div>
				</div>
			</div>
  
            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>

<div class="modal fade" role="dialog" id="newEventModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo 'Lead Change Request'; ?></h4>
      </div>
      <?php echo form_open('admin/leads/addchangerequest'); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
		
			 
			<div class="form-group" app-field-wrapper="description">
			 <label for="description" class="control-label">Lead *</label>
			 <select id="leadid" name="leadid" required class="selectpicker" data-width="100%" data-none-selected-text="Leads" data-live-search="true" tabindex="-98">
			 <option value=""></option>
			 <?php foreach ($lead_list as $lead){ ?>
				<option value="<?php echo $lead['id']; ?>">#<?php echo $lead['id']; ?> - <?php echo $lead['company']; ?></option>
			 <?php } ?>
			 </select>

			 </div>
            <?php echo render_textarea('remark','Changes Description','',array('rows'=>5)); ?>
            <div class="clearfix mtop15"></div>
          
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    <button type="submit" id="submitchange" class="btn btn-info"><?php echo _l('submit'); ?></button>
  </div>
  <?php echo form_close(); ?>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
function myFunction(leadid,lead_id) {
  var remark = prompt("Please enter remark");
  if (remark != null) {
	  
	  var base_url = '<?php echo base_url() ?>';
			  $.ajax({
				  type: "POST",
				  url: base_url + "admin/leads/rejectchangerequest",
				  data: {'leadid': leadid, 'remark': remark, 'lead_id': lead_id},
				  dataType: "json",
				  success: function (data1) {	
					  console.log(data1);
					  
					  location.reload();
					}
				});
		alert("Change request rejected");
		location.reload();		
  }
}

$( "#submitchange" ).click(function() {
	if($( "#leadid" ).val()===''){
		alert('Please select Lead');
	}else{
	 $("form").submit(); 
     $(this).attr("disabled", "disabled");
    }
});
</script>
</body>
</html>