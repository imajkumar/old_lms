<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="lead-form">
      <form id="w0" class="form-vertical" action="<?php echo base_url('admin/leads/lead_add');?> " method="post" role="form">
         <input type="hidden" name="_csrf" value="">
         <div class="panel panel-info">
            <div class="panel-heading">
               <h3 class="panel-title">Lead Details</h3>
            </div>
            <div class="panel-body">
               <fieldset id="w1">
                  <div class="row">
				   <div class="col-sm-12 hide">
                        <div class="form-group field-lead-opportunity_amount">
						  <input type="radio" name="customer_namec"  id="customer_name" value="ncustomer">New Customer
						  <input type="radio" name="customer_namec" id="customer_namec" value="ecustomer" >Existing Customer
                           <div class="help-block"></div>
                        </div>
                     </div>
					
                     <div class="col-sm-8">
						<div class="form-group field-lead-lead_source_id col-sm-6">
						<div class="col-sm-10">
                           <label class="control-label" for="lead-lead_source_id">Customer Group</label>
                           <select class="form-control" id="customer_group" name="customer_group" required>
                    		<option value="">--Select Customer Group--</option>
							 <?php foreach($customer_groups as $customer_group) { ?>
                              <option value="<?php echo $customer_group['id']; ?>"> <?php echo $customer_group['name'];?></option>
                              <?php } ?>
							
                           </select>
						   </div>
						   <div class="col-sm-2">
						   <label class="control-label" for="lead-lead_source_id"><br><br><br></label>
						   <a href="#" data-toggle="modal" style="background: #008ece;
    color: white;
    padding: 12px 9px 7px 7px;
    line-height: 7;
    margin-left: -30px;
    border-radius: 3px 4px 4px 3px;" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>
						  </div>
						    <div class="help-block"></div>
                        </div>
                        <div class="form-group field-lead-lead_name required col-sm-6">
						<div class="form-group field-lead-lead_name required col-sm-10">
                           <label class="control-label" for="lead-lead_name">Customer</label>
						        
                           <input type="text" id="my_l_searcht" autocomplete="off" class="form-control hide" name="customer" maxlength="255" placeholder="Enter customer..." aria-required="true">
						   
						   <select id="my_l_searchd" class="form-control" required name="customer_existing">
                              <option value="">--Select Customer--</option>
                              
                           </select></div>
						   <div class="form-group col-sm-2">
						<a href="<?php echo base_url('admin/clients/client'); ?>" id="add_client_data" data-toggle="modal" style="background: #008ece;
    color: white;
    padding: 12px 9px 7px 7px;
    line-height: 7;
    margin-left: -30px;
    border-radius: 3px 4px 4px 3px;">ADD</a>
						   
						   </div>
						   
						   
						   
						   
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div>
					  <div class="col-sm-4">
                        <div class="form-group field-lead-lead_status_id required">
                           <label class="control-label" for="lead-lead_status_id">Lead Status</label>
                           <select id="lead-lead_status_id" required class="form-control" name="lead_status">
                              <option value="">--Lead Status--</option>
                              <?php foreach($all_status as $status) {?>
                              <option value="<?php echo $status['id']; ?>"> <?php echo $status['name'];?></option>
                              <?php } ?>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					  <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar">
                           <label class="control-label" for="lead-dellar">Dilar/Contractor</label>
                           <select  class="form-control" name="dillar_data" id="lead_dillar_data"  placeholder="">
                              <option value="">--Select--</option>
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar_name">
                           <label class="control-label" for="lead-dellar">Dilar</label>
						   
                            <input id="dilar" name="dilar" value="" data-role="tagsinput"  type="text">
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-sm-4">
                        <div class="form-group field-lead-dellar" id="lead-dellar_contractor">
                           <label class="control-label" for="lead-dellar">Contractor</label>
						      <input  name="contractor" value="" data-role="tagsinput"  type="text">
                       
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
                     <div class="col-sm-8">
					 
					 
					
                     <div class="col-sm-6">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Project Title</label>
						        
                           <input type="text"  id="project_title" autocomplete="off" class="form-control" name="lead_name" maxlength="255" placeholder="Enter Project Title..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
					<div class="col-sm-6">
                        <div class="form-group field-lead-lead_source_id">
                           <label class="control-label" for="lead-lead_source_id">Lead Source</label>
                           <select id="lead-lead_source_id" class="form-control" name="lead_source">
                              <option value="">--Lead Source--</option>
                              <?php foreach($sources as $lead_source) {?>
                              <option value="<?php echo $lead_source['id']; ?>"> <?php echo $lead_source['name'];?></option>
                              <?php } ?>
                           </select>
                           <div class="help-block"></div>
                        </div>
                     </div>
                     </div>

                     <div class="col-sm-4" >
                        <div class="form-group field-lead-lead_status_id" >
							<label class="control-label" for="lead-lead_status_id"> Remark </label>
                            <select  class="form-control" name="lead_status_losss" id="lead_status_losss">
						    
                              <option value="">--Select Remark --</option>  
							 
							</select>
							<input type="text"  autocomplete="off" class="form-control" name="lead_status_lo" id="lead_status_lo" maxlength="255" placeholder="Remark..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
              
                  <div class="row">
				  <div class="col-sm-4">
                        <div class="form-group field-lead-lead_description required">
                           <label class="control-label" for="lead-lead_description">Project Location</label>
                           
						   
						    <input type="text" id="project_location" autocomplete="off" class="form-control" name="project_location" maxlength="255" placeholder="Project Location.." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-lead_description required">
                           <label class="control-label" for="lead-lead_description">Lead Description</label>
                           <textarea id="lead-lead_description" class="form-control" name="lead_description" rows="5" placeholder="Enter Lead Description..." style="resize:none" aria-required="true"></textarea>
                           <div class="help-block"></div>
                        </div>
                     </div> 
                     <div class="col-sm-4 project_awarded_to"  >
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Lead Awarded to</label>
						        
                           <input type="text"  autocomplete="off" class="form-control" name="project_awarded_to" maxlength="255" placeholder="Lead Awarded to.." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					 <div class="col-sm-4 project_total_amount">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name">Total Amount(In Lacs)</label>
						        
                           <input type="text"  autocomplete="off" class="form-control" name="project_total_amount" maxlength="255" placeholder="Total Amount..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					 
					 <div class="col-sm-4">
                        <div class="form-group field-lead-lead_source_id">
                           <label class="control-label" for="lead-lead_source_id">Customer type</label>
                           <select id="lead_customer_type_id" class="form-control" name="customer_type" required>
                    		<option value="">--Select Customer Type--</option>
							 <?php foreach($customer_detail_type as $customer) {?>
                              <option value="<?php echo $customer['code']; ?>"> <?php echo $customer['name'];?></option>
                              <?php } ?>
							
                              
                           </select>
						    <input type="hidden" id="hidden_customer_type" class="form-control" name="customer_typehidden_">
                           <div class="help-block"></div>
                        </div>
                     </div>
					 
					 
					  <div class="col-sm-4">
                        <div class="form-group  required">
                           <label class="control-label" for="lead-lead_namet">Finalization Month (Expected)</label>
						        
                           <input type="date" required autocomplete="off" class="form-control" name="accepted_date" maxlength="255" placeholder="Enter Date..." aria-required="true">
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
                           <input type="text" required id="lead-opportunity_amount" class="form-control" name="opportunity_amount" maxlength="255" placeholder="Enter Opportunity Estimated Amount...">
                           <div class="help-block"></div>
                        </div>
                     </div>
					
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label" for="Competitor">Competitor 1</label>
						   <input type="text" id="competition" required class="form-control" name="competition" maxlength="255" placeholder="">
                          
                        </div>
                     </div><div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 2</label>
                           <input type="text" id="competition1" name="competition1" class="form-control">
                        </div>
                     </div>
					 <div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 3</label>
                           <input type="text"  name="competition2" class="form-control">
                        </div>
                     </div><div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Competitor 4</label>
                           <input type="text"  name="competition3" class="form-control">
                        </div>
                     </div><div class="col-sm-2">
                         <div class="form-group no-mbot">
                           <label  class="control-label">Other Competitor</label>
                           <input type="text"  name="competition4" class="form-control">
                        </div>
                     </div>
					 
                     <div class="col-sm-4">
                        <div class="form-group field-lead-lead_owner_id hidden">
                           <label class="control-label" for="lead-lead_owner_id">Lead Owner</label>
                           <select id="lead-lead_owner_id" class="form-control" name="lead_owner">
                              <option value="<?php echo $this->session->staff_user_id; ?>"> <?php echo $this->session->staff_user_id; ?></option>
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
				   <select id="lead_contact" class="form-control" required name="lead_contact">
					  <option value=""><?php echo '--Select--'; ?></option>
				   </select>
			   </div>
				  </div>
				  <hr>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-group field-lead-first_name">
                           <label class="control-label" for="lead-first_name">Name</label>
                           <input type="text"  id="lead-first_name" id="lead-last_name" class="form-control" readonly name="first_name" maxlength="255" placeholder="Enter Name...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-last_name">
                           <label class="control-label" for="lead-last_name">Position</label>
                           <input type="text" id="lead-last_name" class="form-control"readonly name="position" maxlength="255" placeholder="Enter Position...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-email required">
                           <label class="control-label" for="lead-email">Email</label>
                           <input type="text" id="lead-email" class="form-control" readonly name="email" maxlength="255" placeholder="Enter Email..." aria-required="true">
                           <div class="help-block"></div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-4">
                        <div class="form-group field-lead-phone">
                           <label class="control-label" for="lead-phone">Phone</label>
                           <input type="text" id="lead-phone" class="form-control" readonly name="phone" maxlength="255" placeholder="Enter Phone...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-mobile">
                           <label class="control-label" for="lead-mobile">Mobile</label>
                           <input type="text" id="lead-mobile" class="form-control" readonly name="mobile" placeholder="Enter Mobile...">
                           <div class="help-block"></div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group field-lead-do_not_call">
                           <label class="control-label" for="lead-do_not_call">Can be called?</label>
                           <select id="lead-do_not_call" class="form-control" name="can_be_called" placeholder="Can be called?">
                              <option value="">--Select--</option>
                              <option value="N">No</option>
                              <option value="Y">Yes</option>
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
                        <label class="control-label">Address </label>
                        <input type="text" id="lead_address" name="address"  class="form-control">
                     </div>
					
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group">
                        <label class="control-label">Zipcode</label>
                        <input type="text" name="zipcode" id="zipcode" class="form-control">
                     </div>
                  </div>
				    <div class="col-sm-4">
                     <div class="form-group required">
                        <label class="control-label">Country</label>
                        <input type="text" id="country_id" class="form-control" name="country_id" data-validation="required">
                         
                     </div>
                  </div>
               </div>
               <div class="row">
                
				   <div class="col-sm-4 hide">
				  <div class="form-group required">
                        <label for="direction"><?php echo _l('Region'); ?></label>
						 <select class="selectpicker" data-none-selected-text="<?php echo _l('system_default_string'); ?>" data-width="100%" name="region" id="region">
                          <option value="">Select Region</option>
                          
                           <?php 
						  
						   foreach($region as $region_data){
							   
						
							 ?>
							 
							 
							 <option value="<?php echo $region_data['id'] ?>"<?php
                                        if (set_value('region',$region_data['id']) == $member_list['region']) {
                                            echo "selected =selected";
                                        }
                                        ?>>
							 
						<?php echo ucfirst($region_data['region']); ?></option>
                           <?php } ?>
                        </select>
						
                     </div></div>
					
					
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo 'State'; ?></label>
                        <input type="text"  id="state_id" name="state_id" class="form-control" >
                          
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <div class="form-group required">
                        <label class="control-label">City</label>
                        <input type="text" id="city_id" class="form-control" name="city_id">
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
		 <div class="panel">
			<div class="panel-body">
				 <div class="col-md-10">
				 </div>
				 <div class="col-md-2">
					<button type="submit" name="submit" class="btn btn-primary lead_submit float-right">Create</button>
				 </div>
			 </div>
		 </div>
      </form>
   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/clients/client_group'); ?>
<?php $this->load->view('admin/clients/client_js'); ?>


 <script type="text/javascript">  
 //$('#my_l_searchd').hide();
/* 
 $('#customer_namec').change(function(){
    if($(this).is(":checked")) {
		 $('#my_l_searchd').show();
		 $('#my_l_searcht').hide();
    } else {
       $('#my_l_searchd').hide();
	   $('#my_l_searcht').show();
    }
});
$('#customer_name').change(function(){
    if($(this).is(":checked")) {
      $('#my_l_searchd').hide();
	   $('#my_l_searcht').show();
	   $("#lead_customer_type_id").prop("disabled", false);
    } 
});
   
 */
$(document).on('change', '#my_l_searchd', function (e) {
       $('#lead_contact').html("");
        var my_l_searchdr = $(this).val(); 
		var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
		
		var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/customer_type_value_byname",
            data: {'customer_type': my_l_searchdr},
            dataType: "json",
            success: function (data) {
			
				$("#lead_customer_type_id option[value="+data[0].details.customer_type+"]").attr('selected', 'selected');
				$("#hidden_customer_type").val(data[0].details.customer_type);
				$("#lead_customer_type_id").prop("disabled", true);
				$("#lead_address").val(data[0].details.address);
				$("#zipcode").val(data[0].details.zip);
				$("#country_id").val(data[0].details.country);
				$("#state_id").val(data[0].details.state);
				$("#city_id").val(data[0].details.city);
				
				$.each(data[0].contact, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.firstname +"  "  + obj.lastname  + "</option>";
   
                });
				
                $('#lead_contact').append(div_data);
				
				
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
            url: base_url + "admin/leads/getlead_contact",
            data: {'id': lead_contact},
            dataType: "json",
            success: function (data) {
				
				$("#lead-first_name").val(data.firstname+" "+data.lastname);
				$("#lead-last_name").val(data.title);
				$("#lead-email").val(data.email);
				$("#lead-phone").val(data.phonenumber);
				$("#lead-mobile").val(data.mobilenumber);
   
            }
        });
    });

	$(document).on('change', '#customer_group', function (e) {
        $('#my_l_searchd').html("");
     
        var customer_group = $(this).val();
		
		var adminurl = "http://103.253.145.71/halonix-new/admin/clients/client?cid="+customer_group;
		$("#add_client_data").attr("href", adminurl);

		
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getCustomerByGroup",
            data: {'customer_group': customer_group},
            dataType: "json",
            success: function (data) {
			
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
   
                });
				
                $('#my_l_searchd').append(div_data);
               
            }
        });
    });
	
  
   
</script>  


<script type="text/javascript">
   $(document).ready(function () {
     $('.project_awarded_to').hide();
     $('.project_total_amount').hide(); 
	 $('#lead-dellar').hide();
	 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
	
	  $('#lead-lead_status_id').change(function(){
        if( $('#lead-lead_status_id').val() ==7) {
			$('.project_awarded_to').show();
			$("#lead_status_losss").prop("required", true);
			$("#lead_status_lo").prop("required", false);$("#project_location").prop("required", false);
			$('.project_total_amount').hide();
			$('#lead-dellar').show();$("#project_title").prop("required", false);
			 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
			
        }else if( $('#lead-lead_status_id').val() ==4 ||$('#lead-lead_status_id').val() ==5) {
			$('#lead-dellar').show();
			$('.project_awarded_to').hide();
			$("#lead-dellar").prop("required", true);$("#project_location").prop("required", true);
			$("#project_title").prop("required", true);
			$('.project_total_amount').hide();
		
			
        } else if( $('#lead-lead_status_id').val() ==6){
           $('.project_awarded_to').hide();
		   $('.project_total_amount').show();
		    $("#lead_status_losss").prop("required", true);
			$("#lead_status_lo").prop("required", false);$("#project_location").prop("required", false);
			$("#project_title").prop("required", false);
			$('#lead-dellar').show();
			 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
        }else{
			$('.project_awarded_to').hide();
			$('.project_total_amount').hide();$("#project_title").prop("required", false);
			$("#lead_status_lo").prop("required", true);
			$("#lead_status_losss").prop("required", false);$("#project_location").prop("required", false);
			$('#lead-dellar').hide();
			 $('#lead-dellar_name').hide();
	 $('#lead-dellar_contractor').hide();
		}	
  
	 
    });}); 
	
	$('#lead_status_lo').hide();
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
        var div_data = '<option value=""><?php echo '-- Select Remark --'; ?></option>';
    
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
<script>
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
   
   
   
</script>
<script src="<?php echo base_url(); ?>assets/plugins/tagsinput.js"></script>


</body>
</html>