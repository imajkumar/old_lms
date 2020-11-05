<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="lead-form">
      <form id="w0" class="form-vertical" action="<?php echo base_url('admin/leads/update/'.$posts->id);?> " method="post" role="form">
         <input type="hidden" name="_csrf" value="">
         <div class="panel panel-info">
            <div class="panel-heading">
               <h3 class="panel-title">Lead Details - <?php echo $get_company_detail->company; ?></h3>
            </div>
            <div class="panel-body">
               <fieldset id="w1">
                  <div class="row">
				   <div class="col-sm-4">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Customer Group</label>
						   
                           <input type="hidden" id="my_l_searcht" autocomplete="off" class="form-control " name="customer_group" maxlength="255" readonly placeholder="Enter customer Group..." aria-required="true" value="<?php echo $get_company_detail->customer_group; ?>">
                           <input type="text" id="my_l_searcht" autocomplete="off" class="form-control " name="customer_groupasasasas" maxlength="255" readonly placeholder="Enter customer Group..." aria-required="true" value="<?php echo $get_company_detail->group_name; ?>">
						   
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div> 
					 <div class="col-sm-4">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Customer</label>
						        
                           <input type="hidden" id="my_l_searchtkk" autocomplete="off" class="form-control " name="customer" maxlength="255" readonly placeholder="Enter customer..." aria-required="true" value="<?php echo $get_company_detail->customerid; ?>">
                           <input type="text" id="my_l_searcht" autocomplete="off" class="form-control " name="customerzsdsdsd" maxlength="255" readonly placeholder="Enter customer..." aria-required="true" value="<?php echo $get_company_detail->company_name; ?>">
						   
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div>
				    <div class="col-sm-4">
                        <div class="form-group field-lead-lead_status_id required">
                           <label class="control-label" for="lead-lead_status_id">Lead Status</label>
                           <select id="lead-lead_status_id" class="form-control" name="lead_status">
                              <option value="">--Lead Status--</option>
                              <?php foreach($all_status as $status) {
									if($posts->status <= $status['id']){
								  ?>
                              <option value="<?php echo $status['id']; ?>" <?php if($get_company_detail->status == $status['id']){ echo 'selected'; } ?>> <?php echo $status['name'];?></option>
                              <?php } } ?>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
				  <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar">
                           <label class="control-label" for="lead-dellar">Dilar/Contractor</label>
                           <select  class="form-control" name="dillar_data" id="lead_dillar_data"  placeholder="">
                              <option value="">--Select--</option>
                              <option value="<?php echo $posts->dillar_data; ?>" <?php if($posts->dillar_data== 0){echo 'selected';}?>>No</option>
                              <option value="<?php echo $posts->dillar_data; ?>" <?php if($posts->dillar_data== 1){echo 'selected';}?>>Yes</option>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
					  <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar_name">
                           <label class="control-label" for="lead-dellar">Dilar</label>
						   
                            <input id="dilar" name="dilar" value="<?php echo $posts->dilar; ?>" data-role="tagsinput"  type="text">
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar_contractor">
                           <label class="control-label" for="lead-dellar">Contractor</label>
						      <input  name="contractor" value="<?php echo $posts->contractor; ?>" data-role="tagsinput"  type="text">
                       
                           <div class="help-block"></div>
                        </div>
                     </div>
				  
                     <div class="col-sm-8">
						 <div class="col-sm-6">
							<div class="form-group field-lead-lead_name required">
							   <label class="control-label" for="lead-lead_name">Project Title</label>
							   <?php
							  if(is_admin()){
								   ?>
								   <input type="text" id="lead-lead_name" class="form-control" value="<?php echo $posts->company; ?>" name="lead_name" maxlength="255" placeholder="Enter Project Title..." aria-required="true">
								   <?php
							   }
							   else{
								   ?>
								   <input type="text" readonly id="lead-lead_name" class="form-control" value="<?php echo $posts->company; ?>" name="lead_name" maxlength="255" placeholder="Enter Lead Title..." aria-required="true">
								   <?php
							   }
							  ?> 
							   
							   <div class="help-block"></div>
							</div>
						 </div>
						 <div class="col-sm-6">
							<div class="form-group field-lead-lead_source_id">
							   <label class="control-label" for="lead-lead_source_id">Lead Source</label>
							 <?php 
							   if(is_admin()){
								   ?>
							   
							   
							   <select id="lead-lead_source_id"  class="form-control" name="lead_source">
								  <option value="">--Lead Source--</option>
								  <?php foreach($sources as $lead_source) {?>
								  <option <?php if($posts->source == $lead_source['id']){ echo 'selected'; } ?> value="<?php echo $lead_source['id']; ?>"> <?php echo $lead_source['name'];?></option>
								  <?php } ?>
							   </select>
							   
							   <?php
							   }
							   else{
								   ?>
								   <select id="lead-lead_source_id"  disabled class="form-control" name="lead_source">
								  <option value="">--Lead Source--</option>
								  <?php foreach($sources as $lead_source) {?>
								  <option <?php if($posts->source == $lead_source['id']){ echo 'selected'; } ?> value="<?php echo $lead_source['id']; ?>"> <?php echo $lead_source['name'];?></option>
								  <?php } ?>
							   </select>
								   <?php
							   }
							   
							   ?>
							   
							   <div class="help-block"></div>
							</div>
						 </div>						 
                     </div> 
					<div class="col-sm-4">
                        <div class="form-group field-lead-lead_status_id" id="lead_loss">
                           <label class="control-label" for="lead-lead_name">Interaction stage</label>
                           <select class="form-control " name="lead_status_losss" id="lead_status_losss">
						    <?php 
								$this->db->select()->from('status_loss');
								$this->db->where('id', $posts->status_closed_won);
								$query = $this->db->get();
								$all_city = $query->result_array();	
						
							?>
                              <option value="">--Select--</option>  
							  <?php foreach($all_city as $status_loss) {?>
                              <option value="<?php echo $status_loss['id']; ?>" <?php if($posts->status_closed_won == $status_loss['id']){ echo 'selected'; } ?>> <?php echo $status_loss['name'];?></option>
                              <?php } ?>
							    </select>
								
						
								<input type="text"  autocomplete="off" class="form-control " name="lead_status_lo" id="lead_status_lo" maxlength="255" value="<?php echo $posts->status_lost; ?>" placeholder="Status Reason...">
                     
						  <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
               </fieldset>
               <fieldset id="w2">
                  <div class="row">
				   <div class="col-sm-4">
                        <div class="form-group field-lead-lead_description required">
                           <label class="control-label" for="lead-lead_description">Project Location</label>
                           
						   
						    <input type="text" id="project_location" autocomplete="off" class="form-control" name="project_location" maxlength="255" value="<?php echo $posts->project_location; ?>" placeholder="Project Location.." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-lead_description required">
                           <label class="control-label" for="lead-lead_description">Lead Description</label>
						    <?php
							    if(is_admin()){
								   ?>
							    <textarea id="lead-lead_description" class="form-control" name="lead_description" rows="5" placeholder="Enter Lead Description..." style="resize:none" aria-required="true"><?php echo $posts->description; ?></textarea>
						   
						   <?php
							   }
							   else{
								   ?>
								    <textarea id="lead-lead_description"  readonly class="form-control" name="lead_description" rows="5" placeholder="Enter Lead Description..." style="resize:none" aria-required="true"><?php echo $posts->description; ?></textarea>
								   <?php
							   }
							   ?>
                          
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					 
					 
					 <div class="col-sm-4" id="project_awarded_to">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Lead Awarded to</label>
						        
                           <input type="text"  autocomplete="off" value="<?php echo $posts->project_awarded_to; ?>" class="form-control" name="project_awarded_to" maxlength="255" placeholder="Enter Lead Title..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					 <div class="col-sm-4" id="project_total_amount">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Total Amount(In Lacs)</label>
						        
                           <input type="text"  autocomplete="off" class="form-control"  value="<?php echo $posts->project_total_amount; ?>"  name="project_total_amount" id="project_total_amount" maxlength="255" placeholder="Enter Lead Title..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     
					 
					 <div class="col-sm-4">
                        <div class="form-group field-lead-lead_source_id">
                           <label class="control-label" for="lead-lead_source_id">Customer type</label>
						    <select id="lead_customer_type_id" class="form-control" name="customer_typegfdf" disabled>
                              <option value="">Select Customer Type</option>  
							  <?php foreach($customer_detail_type as $customer) { ?>
                              <option value="<?php echo $customer['code']; ?>" <?php if($posts->customer_type == $customer['code']){ echo 'selected'; } ?>> <?php echo $customer['name'];?></option>
                              <?php } ?>
						    </select>
							 <input type="hidden" id="my_l_searcht" autocomplete="off" class="form-control " name="customer_type" maxlength="255" readonly placeholder="Enter customer..." aria-required="true" value="<?php echo $get_company_detail->customer_type; ?>">
						  
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-sm-4">
                        <div class="form-group  required">
                           <label class="control-label" for="lead-lead_namet">Finalization Month (Expected)</label>
						        
        <input type="date"  class="form-control" value="<?php echo $posts->accepacted_date;  ?>" name="accepted_date" maxlength="255" placeholder="Enter Date...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
               </fieldset>
               <fieldset id="w3">
                  <div class="row">
                     <div class="col-sm-2">
                        <div class="form-group field-lead-opportunity_amount">
                           <label class="control-label" for="lead-opportunity_amount">Opportunity Amount(In Lacs)</label>
                           <input type="text" value="<?php echo $posts->opportunity; ?>" id="lead-opportunity_amount" class="form-control" name="opportunity_amount" maxlength="255" placeholder="Enter Opportunity Estimated Amount..." readonly>
                           <div class="help-block"></div>
                        </div>
                     </div>
					
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 1</label>
                           <input type="text"  name="competition" class="form-control"  value="<?php echo $posts->competition; ?>">
                        </div>
                     </div>
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 2</label>
                           <input type="text"  name="competition1" class="form-control"  value="<?php echo $posts->competition1; ?>">
                        </div>
                     </div>
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 3</label>
                           <input type="text"  name="competition2" class="form-control"  value="<?php echo $posts->competition2; ?>">
                        </div>
                     </div>
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 4</label>
                           <input type="text"  name="competition3" class="form-control"  value="<?php echo $posts->competition3; ?>">
                        </div>
                     </div>
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Other Competitor</label>
                           <input type="text"  name="competition4" class="form-control"  value="<?php echo $posts->competition4; ?>">
                        </div>
                     </div>
					
					 					 
                     <div class="col-sm-4">
                        <div class="form-group field-lead-lead_owner_id hidden">
                           <label class="control-label" for="lead-lead_owner_id">Lead Owner</label>
                           <select id="lead-lead_owner_id" class="form-control" name="lead_owner">
                              <option value="<?php echo $posts->assigned; ?>"> <?php echo $this->session->staff_user_id; ?></option>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
               </fieldset>
            </div>
         </div>
         <div class="panel panel-info">
            <div class="panel-heading">
               <h3 class="panel-title">Lead Contact Details</h3>
            </div>
            <div class="panel-body">
               <fieldset id="w4">
			   <div class="row">
					<div class="col-md-4">
					<label class="control-label" for="lead-first_name">Select Contact</label>
				   <select id="lead_contact" class="form-control" required  name="lead_contact">
				   <option>select contact</option>
				  <?php 
					$this->db->select()->from('tblcontacts');
					$this->db->where('userid', $get_company_detail->customerid);
					$query = $this->db->get();
					$get_contact_type = $query->result_array();
				  
				     foreach($get_contact_type as $get_contact) {
						 ?>
                              <option  value ="<?php echo $get_contact['id']; ?>" <?php if($get_company_detail->lead_contact == $get_contact['id']){ echo 'selected';  }?>> <?php echo $get_contact['firstname']; ?>
							  </option>
                              <?php 
							  } 
							?>
				     
				   
				   </select>
			   </div>
				  </div>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-group field-lead-first_name">
                           <label class="control-label" for="lead-first_name">Name</label>
                           <input type="text" readonly id="lead-first_name"  value="<?php echo $posts->name; ?>" class="form-control" name="first_name" maxlength="255" placeholder="Enter Name...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-last_name">
                           <label class="control-label" for="lead-last_name">Position</label>
                           <input type="text" readonly id="lead-position" value="<?php echo $posts->title; ?>" class="form-control" name="position" maxlength="255" placeholder="Enter Position...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-email required">
                           <label class="control-label" for="lead-email">Email</label>
                           <input type="text" readonly id="lead-email" value="<?php echo $posts->email; ?>" class="form-control" name="email" maxlength="255" placeholder="Enter Email..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-group field-lead-phone">
                           <label class="control-label" for="lead-phone">Phone</label>
                           <input type="text" readonly id="lead-phone" value="<?php echo $posts->phonenumber; ?>" class="form-control" name="phone" maxlength="255" placeholder="Enter Phone...">
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-sm-4">
                        <div class="form-group field-lead-mobile">
                           <label class="control-label" for="lead-mobile">Mobile</label>
                           <input type="text" readonly id="lead-mobile" value="<?php echo $posts->mobile_number; ?>" class="form-control" name="mobile" maxlength="255" placeholder="Enter Mobile...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                   
                     <div class="col-sm-4">
                        <div class="form-group field-lead-do_not_call">
                           <label class="control-label" for="lead-do_not_call">Can be called?</label>
                           <select id="lead-do_not_call" class="form-control" name="can_be_called" placeholder="Can be called?">
                              <option value="">--Select--</option>
                              <option <?php if($posts->can_be_called == 'N'){ echo 'selected'; } ?> value="N">No</option>
                              <option <?php if($posts->can_be_called == 'Y'){ echo 'selected'; } ?> value="Y">Yes</option>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
               </fieldset>
            </div>
         </div>
         <div class="panel panel-info hide">
            <div class="panel-heading">
               <h3 class="panel-title">Address Details</h3>
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label class="control-label">Address</label>
                        <input type="text" name="address" value="<?php echo $posts->address; ?>" class="form-control">
                     </div>
                  </div>
               
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label class="control-label">Zipcode</label>
                        <input type="text" name="zipcode" value="<?php echo $posts->zip; ?>" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-4">
                     <div class="form-group required">
                        <label class="control-label">Country</label>
                        <select id="country_id" class="form-control" name="country_id" data-validation="required">
                           <option value="">--Select--</option>
                           <?php foreach($all_country as $country) { if($country->id== 104){?>
                           <option <?php if($posts->country == $country->id){ echo 'selected'; } ?>  value="<?php echo $country->id ; ?>"> <?php echo $country->country;?></option>
                           <?php }} ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo 'State'; ?></label>
                        <select  id="state_id" name="state_id" class="form-control" >
						
						  <option value="">--Select--</option>
                           <?php foreach($all_state as $state) {?>
                           <option <?php if($posts->state == $state['id']){ echo 'selected'; } ?>  value="<?php echo $state['id'] ; ?>"> <?php echo $state['state'];?></option>
                           <?php } ?>
						
                           
                        </select>
                        <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group required">
						<?php 
						    $this->db->select()->from('tbl_city');
							$this->db->where('state_id', $posts->state);
							$query = $this->db->get();
							$all_city = $query->result_array();		
						?>
					 
                        <label class="control-label">City</label>
                        <select  id="city_id" class="form-control" name="city_id">
                           <option value="">--Select--</option>
						   <?php

							foreach($all_city as $city) {?>
                           <option <?php if($posts->city == $city['id']){ echo 'selected'; } ?>  value="<?php echo $city['id'] ; ?>"> <?php echo $city['city'];?></option>
						<?php } ?>
						   
                        </select>
                     </div>
                  </div>
               </div>
            </div>
         </div>
		<?php if($get_company_detail->status==6 || $get_company_detail->status==7){ ?> 
			<button type="submit" name="cancel" class="btn btn-primary lead_submit pull-right"  onclick="location.href='<?php echo base_url();?>admin/leads'" style="margin: 0 11px 3px 3px;">Back</button>
	<?php }else { ?>
		<button type="submit" name="submit" class="btn btn-primary lead_submit  pull-right">Update</button> 
		<button type="submit" name="cancel" class="btn btn-primary lead_submit pull-right"  onclick="location.href='<?php echo base_url();?>admin/leads'" style="margin: 0 11px 3px 3px;">Cancel</button>
	<?php } ?>
	<br><br>
      </form>
   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>

 <script type="text/javascript">  
$(document).ready(function () {
     $('#project_awarded_to').hide();
     $('#project_total_amount').hide();
	  $('#lead-dellar').hide();
	 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
	 
	 <?php if($get_company_detail->status==6){ ?>
		$('#project_total_amount').show();
	 <?php }else if($get_company_detail->status==7){ ?>
		$('#project_awarded_to').show();
	 <?php } else if($get_company_detail->status==4||$get_company_detail->status==5||$get_company_detail->status==6||$get_company_detail->status==7){ ?>
		 $('#lead-dellar').show();
	 
	 <?php } ?>
	 <?php if($posts->dillar_data == 1){
		 ?>
		 $('#lead-dellar_name').show();
	 $('#lead-dellar_contractor').show();
		 
		 
	<?php	 
	 } else{
		 ?>
		 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
		 
		<?php 
	 }?>
	 
	 $('#lead-lead_status_id').change(function(){
		  if( $('#lead-lead_status_id').val() ==7) {
			$('#project_awarded_to').show();
			$('#lead-dellar').show();
				$("#lead-dellar").prop("required", true);
			$("#project_location").prop("required", true);
			$("#project_title").prop("required", true);
			$('#project_total_amount').hide();
			
        } else if( $('#lead-lead_status_id').val() ==6){
           $('#project_awarded_to').hide();
		   $('#project_total_amount').show();$('#lead-dellar').show();
		 	$("#lead-dellar").prop("required", true);
			$("#project_location").prop("required", true);
			$("#project_title").prop("required", true);
		   // $("#project_total_amount").prop("required", false);
        }	

else if( $('#lead-lead_status_id').val() ==4 ||$('#lead-lead_status_id').val() ==5) {
			$('#lead-dellar').show();
			$('.project_awarded_to').hide();
			$("#lead-dellar").prop("required", true);
			$("#project_location").prop("required", true);
			$("#project_title").prop("required", true);
			$('.project_total_amount').hide();
		
			
        }
		
	 else{
			$('#project_awarded_to').hide();
			$('#project_total_amount').hide();
			 $('#lead-dellar').hide();
	 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
	 	$("#lead-dellar").prop("required", false);
			$("#project_location").prop("required", false);
			$("#project_title").prop("required", false);
			
		}	
	 
    });}); 
$('#my_l_searcht').change(function(){
 
	   $("#lead_customer_type_id").prop("disabled",true);
   
});

	<?php if($get_company_detail->status =='6' || $get_company_detail->status =='7') { ?> 
		$('#lead_status_losss').show();
		$('#lead_status_lo').hide();
	<?php }else{ ?>
		$('#lead_status_losss').hide();
		$('#lead_status_lo').show();
	<?php } ?>
   
	$(document).on('change', '#lead-lead_status_id', function (e) {
		 
        $('#lead_status_losss').html("");
		var status_loss = $(this).val();
		
		if(status_loss==6 || status_loss==7){
			$('#lead_status_losss').show();
			$('#lead_status_lo').hide();
		}else{
			$('#lead_status_losss').hide();
			$('#lead_status_lo').show();
		}
      
		
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';
    
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getReasonByStatus",
            data: {'status_loss': status_loss},
            dataType: "json",
            success: function (data) {
			
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
   
                });
				
                $('#lead_status_losss').append(div_data);
				
               
            }
        });
    });
	$(document).on('change', '#lead_dillar_data', function (e) {
		
		
		if( $('#lead_dillar_data').val() ==1)
		{
		$('#lead-dellar_name').show();		
		$('#lead-dellar_contractor').show();
		}
		else{
			
			$('#lead-dellar_name').hide();		
		$('#lead-dellar_contractor').hide();
		}
		
	});
	
</script>  





<script>
   $(function() {
   
       $('select[name="role"]').on('change', function() {
           var roleid = $(this).val();
           init_roles_permissions(roleid, true);
       });
   
       $('input[name="administrator"]').on('change', function() {
           var checked = $(this).prop('checked');
           var isNotStaffMember = $('.is-not-staff');
           if (checked == true) {
               isNotStaffMember.addClass('hide');
               $('.roles').find('input').prop('disabled', true).prop('checked', false);
           } else {
               isNotStaffMember.removeClass('hide');
               isNotStaffMember.find('input').prop('checked', false);
               $('.roles').find('input').prop('disabled', false);
           }
       });
   
       $('#is_not_staff').on('change', function() {
           var checked = $(this).prop('checked');
           var row_permission_leads = $('tr[data-name="leads"]');
           if (checked == true) {
               row_permission_leads.addClass('hide');
               row_permission_leads.find('input').prop('checked', false);
           } else {
               row_permission_leads.removeClass('hide');
           }
       });
   
       init_roles_permissions();
   
       _validate_form($('.staff-form'), {
           firstname: 'required',
           lastname: 'required',
           username: 'required',
           password: {
               required: {
                   depends: function(element) {
                       return ($('input[name="isedit"]').length == 0) ? true : false
                   }
               }
           },
           email: {
               required: true,
               email: true,
               remote: {
                   url: site_url + "admin/misc/staff_email_exists",
                   type: 'post',
                   data: {
                       email: function() {
                           return $('input[name="email"]').val();
                       },
                       memberid: function() {
                           return $('input[name="memberid"]').val();
                       }
                   }
               }
           }
       });
   });
   
</script>

<script type="text/javascript">

$('form').submit(function () {

    var total = $('#project_total_amount').val();
    var opportunity = $('#lead-opportunity_amount').val();

    // Check if empty of not
   /*  if (opportunity  < total) {
        alert('Total amount should be less then or equals to opportunity amount');
        return false;
    } */
});


   

	
	
</script>




<script>
/*  
	$(document ).ready(function() {
		$('#state_id').html("");
		var country_id = $('#country_id').val();
		
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'select'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBystate",
            data: {'country_id': country_id},
            dataType: "json",
            success: function (data) {
   
   
                $.each(data, function (i, obj)
                {
					
                    div_data += "<option value=" + obj.id + ">" + obj.state + "</option>";
   
                });
                $('#state_id').append(div_data);
            }
        });
		
	}); */
	
   $(document).on('change', '#country_id', function (e) {
        $('#state_id').html("");
        var country_id = $(this).val();
   
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'select'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBystate",
            data: {'country_id': country_id},
            dataType: "json",
            success: function (data) {
   
   
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.state + "</option>";
   
                });
                $('#state_id').append(div_data);
            }
        });
    });
   
      $(document).on('change', '#state_id', function (e) {
        $('#city_id').html("");
   
        var state_id = $(this).val();
   
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'select'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBycity",
            data: {'state_id': state_id},
            dataType: "json",
            success: function (data) {
   
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.city + "</option>";
                });
                $('#city_id').append(div_data);
            }
        });
    });
   
   $(document).on('change', '#lead_contact', function (e) {
		$("#lead-first_name").val("");
		$("#lead-last_name").val("");
		$("#lead-email").val("");
		$("#lead-phone").val("");
		$("#lead-mobile").val("");
				
       var lead_contact = $(this).val();
   
		var base_url = '<?php echo base_url() ?>';
       
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getlead_contact_edit",
            data: {'lead_contact': lead_contact},
            dataType: "json",
            success: function (data) {
				$("#lead-first_name").val(data.firstname+" "+data.lastname);
				$("#lead-position").val(data.title);
				$("#lead-email").val(data.email);
				$("#lead-phone").val(data.phonenumber);
				$("#lead-mobile").val(data.mobilenumber);
   
            }
        });
    });

   
</script>
<script src="<?php echo base_url(); ?>assets/plugins/tagsinput.js"></script>


</body>
</html>