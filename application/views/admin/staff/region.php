<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="row">
	   
	   <div class="col-md-12" >
		  <div class="panel_s">
			 <div class="panel-body">
				<ul class="nav nav-tabs" role="tablist">
				   <li role="presentation" class="active">
					  <a href="tab_staff_profile" aria-controls="tab_staff_profile" role="tab" data-toggle="tab">
					  Assign State to Region </a>
				   </li>
				   <div class="buttons" style="margin: 8px 4px 4px 3px;">
					  <a href="<?php echo base_url('admin/leads/sources_region'); ?>"  class="btn btn-info pull-right display-block">
					  Add Region</a>
				   </div>
				</ul>
				<div class="tab-content">
				   <form action="<?php echo base_url('admin/region/'); ?>" class="region-form" enctype="multipart/form-data" method="post">
					  <div class="form-group col-md-4" app-field-wrapper="firstname">
						 <label for="firstname" class="control-label"> <small class="req text-danger">* </small>Select Region</label> 
						 <select class="selectpicker" data-none-selected-text="<?php echo _l('system_default_string'); ?>" data-width="100%" name="region" id="region">
							<?php 
							   foreach($region as $region_data){
							   ?>
							<option <?php echo $checked; ?> value="<?php echo $region_data['id']; ?>" ><?php echo ucfirst($region_data['region']); ?></option>
							<?php }
							   ?>
						 </select>
					  </div>
					  <div class="form-group col-md-4">
						 <label for="State"><small class="req text-danger">* </small><?php echo 'Select State'; ?></label>
						 <select  name="state[]" multiple data-live-search="true" id="staff" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" >
							<option value=""><?php echo _l('system_default_string'); ?></option>
							<?php 
							   foreach($stateList as $state){
								$checked = '';
									if(isset($member)){
									 foreach ($state_list as $state_lst) {
									  if($state_lst['id'] == $state['id']){
									   $checked = ' selected';
									 }
									}
									}
													?>
							<option <?php echo $checked; ?> value="<?php echo $state['id']; ?>"><?php echo ucfirst($state['state']); ?></option>
							<?php } ?>
						 </select>
					  </div>
					  <div class="form-group  col-md-2">
						<br>
						 <button type="submit" class="btn1 btn-info" style="width: 75px;height: 34px;"><?php echo _l('Assign'); ?></button>
					  </div>
				   </form>
				</div>
				<hr>
				<table class="table dt-table scroll-responsive" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
				   <thead>
					  <tr>
						 <th>Region</th>
						 <th>State name</th>
					  </tr>
				   </thead>
				   
				   <tbody>
				   <?php
					  foreach($lists_region_data as $region_view){?>
					  <tr>
					  <td><?php echo $region_view['region_id']; ?></td>
					  <td>
						 <?php
							$this->db->select('state');
							   $this->db->where('id', $region_view['state_id']);
							   $query = $this->db->get('tbl_state');
							   $row = $query->row();
							echo $row->state;
							 ?>
					  </td>
					  </tr>
					   <?php
					  }
					  
					  ?>
				   </tbody>
				  
				</table>
			 </div>
		  </div>
	   </div>
	</div>
</div>
</div>
<div class="modal fade" id="status" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('region/add_region/'.$get_region['id'])); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="edit-title"><?php echo 'Edit Region'; ?></span>
               <span class="add-title"><?php echo 'Add Region'; ?></span>
            </h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="additional"></div>
                  <?php echo render_input('name', 'region'); ?>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
</body>
</html>
<!-- /.modal -->
<script>
   window.addEventListener('load', function () {
     _validate_form($("body").find('#leads-status-form'), {
         name: 'required'
     }, manage_leads_statuses);
     $('#status').on("hidden.bs.modal", function (event) {
         $('#additional').html('');
         $('#status input[name="name"]').val('');
         $('#status input[name="color"]').val('');
         $('#status input[name="code"]').val('');
         $('.add-title').removeClass('hide');
         $('.edit-title').removeClass('hide');
         $('#status input[name="statusorder"]').val($('table tbody tr').length + 1);
     });
   });
   
   // Create lead new status
   function new_status() {
     $('#status').modal('show');
     $('.edit-title').addClass('hide');
   }
   
   // Edit status function which init the data to the modal
   function edit_status(invoker, id) {
     $('#additional').append(hidden_input('id', id));
     $('#status input[name="name"]').val($(invoker).data('name'));
     $('#status .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
     $('#status input[name="code"]').val($(invoker).data('code'));
     $('#status').modal('show');
     $('.add-title').addClass('hide');
   }
   
   // Form handler function for leads status
   function manage_leads_statuses(form) {
     var data = $(form).serialize();
     var url = form.action;
     $.post(url, data).done(function (response) {
         window.location.reload();
     });
     return false;
   }
   
   
   
</script>