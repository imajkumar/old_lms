<?php init_head(); ?>
<div id="wrapper">
<div class="content">
<div class="lead-form">
<form id="lead_add_form" class="form-vertical"  enctype="multipart/form-data"  action="<?php echo base_url('admin/leads/lead_add');?> " method="post" role="form">
   <input type="hidden" name="_csrf" value="">
   <div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title">Lead Details</h3>
   </div>
   <div class="panel-body">
      <fieldset id="w1">
         <div class="row">
            <div class="col-sm-12">
               <div class="panel panel-info">
                  <div class="panel-heading">
                     <h3 class="panel-title">Customer</h3>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="col-sm-10">
                     <label class="control-label" for="lead-lead_source_id">Customer Group *</label>
                     <select class="form-control selectpicker" id="customer_group" data-width="100%" data-none-selected-text="Customer Group" data-live-search="true" name="customer_group" required>
                        <option value="">--Select Customer Group--</option>
                        <?php foreach($customer_groups as $customer_group) { ?>
                        <option value="<?php echo $customer_group['id']; ?>"> <?php echo $customer_group['name'];?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <?php if(is_admin()){ ?>
                  <div class="col-sm-2">
                     <label class="control-label" for="lead-lead_source_id"><br><br><br></label>
                     <a href="#" data-toggle="modal" style="background: #008ece;
                        color: white;
                        padding: 12px 9px 7px 7px;
                        line-height: 7;
                        margin-left: -30px;
                        border-radius: 3px 4px 4px 3px;" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>
                  </div>
                  <?php } ?>
               </div>
               <div class="col-sm-4">
                  <div class="form-group field-lead-lead_name required col-sm-10">
                     <label class="control-label" for="lead-lead_name">Customer *</label>
                     <input type="text" id="my_l_searcht" autocomplete="off" class="form-control hide" name="customer" maxlength="255" placeholder="Enter customer..." aria-required="true">
                     <select id="my_l_searchd" class="form-control" required name="customer_existing" >
                        <option value="">--Select Customer--</option>
                     </select>
                  </div>
                  <div class="form-group col-sm-2">
                     <a href="<?php echo base_url('admin/clients/client'); ?>" id="add_client_data" data-toggle="modal" style="background: #008ece;
                        color: white;
                        padding: 12px 9px 7px 7px;
                        line-height: 7;
                        margin-left: -30px;
                        border-radius: 3px 4px 4px 3px;">ADD</a>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group field-lead-lead_source_id">
                     <label class="control-label" for="lead-lead_source_id">Customer Type *</label>
                     <select id="lead_customer_type_id" class="form-control" name="customer_type" required>
                        <option value="">--Select Customer Type--</option>
                        <?php foreach($customer_detail_type as $customer) {?>
                        <option value="<?php echo $customer['code']; ?>"> <?php echo $customer['name'];?></option>
                        <?php } ?>
                     </select>
                     <input type="hidden" id="hidden_customer_type" class="form-control" name="customer_typehidden_">
                  </div>
               </div>
            </div>
      </fieldset>

	  <div class="panel panel-info">
   <div class="panel-heading">
      <h3 class="panel-title">Project /Lead</h3>
   </div>
   <div class="panel-body">
      <fieldset>
         <div class="col-sm-12">
            <div class="col-sm-2">
               <div class="form-group field-lead-lead_name required">
                  <label class="control-label" for="lead-lead_name">Project Title *</label>
                  <textarea id="project_title" class="form-control" name="lead_name" rows="3" placeholder="Enter Project Title..." style="resize:none"></textarea>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-3">
               <div class="form-group field-lead-lead_description required">
                  <label class="control-label" for="lead-lead_description">Lead Description *</label>
                  <textarea id="lead-lead_description" class="form-control" name="lead_description" rows="3" placeholder="Enter Lead Description..." style="resize:none" aria-required="true"></textarea>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group field-lead-lead_source_id">
                  <label class="control-label" for="lead-lead_source_id">Lead Source *</label>
                  <select id="lead-lead_source_id" required class="form-control" name="lead_source">
                     <option value="">--Lead Source--</option>
                     <?php foreach($sources as $lead_source) {?>
                     <option value="<?php echo $lead_source['id']; ?>"> <?php echo $lead_source['name'];?></option>
                     <?php } ?>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-2">
               <label class="control-label" for="lead-lead_namet">Finalization Month (Expected) *</label>
               <input type="date" required autocomplete="off" class="form-control" name="accepted_date" min="<?php echo date('Y-m-d'); ?>" onkeydown="return false" maxlength="255" placeholder="Enter Date..." aria-required="true">
            </div>
            <div class="col-sm-3">
               <label class="control-label" for="lead-lead_description">Project Location *</label>
               <input type="text" id="project_location" data-role="tagsinput" autocomplete="off"  name="project_location" maxlength="255" class="form-control" placeholder="Used Project Location for multiple.." aria-required="true">
            </div>
         </div>
         <div class="col-sm-12">
            <div class="col-sm-3">
               <label class="control-label" for="lead-opportunity_amount">Opportunity Amount(In Lacs) *</label>
               <input type="number" min="1" required id="lead-opportunity_amount" class="form-control" name="opportunity_amount" maxlength="255" placeholder="Enter Opportunity Estimated Amount...">
            </div>
            <div class="col-sm-2">
               <label  class="control-label" for="Competitor">Competitor 1 *</label>
               <input type="text" id="competition" required class="form-control" name="competition" maxlength="255" placeholder="">
            </div>
            <div class="col-sm-2">  <label  class="control-label">Competitor 2</label>
               <input type="text" id="competition1" name="competition1" class="form-control">
            </div>
            <div class="col-sm-2">
               <label  class="control-label">Competitor 3</label>
               <input type="text"  name="competition2" class="form-control">
            </div>
            <div class="col-sm-3">
               <label  class="control-label">Other Competitor</label>
               <input type="text"  name="competition4" class="form-control">
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
         <div class="col-sm-12">
            <br><br>
            <div class="col-sm-4">
               <div class="form-group field-lead-lead_status_id required">
                  <label class="control-label" for="lead-lead_status_id">Lead Status *</label>
                  <select id="lead-lead_status_id" required class="form-control" name="lead_status">
                     <option value="">--Lead Status--</option>
                     <?php foreach($all_status as $status) {
							if($status['id'] == 1 || $status['id'] == 2 || $status['id'] == 3 || $status['id'] == 4 || $status['id'] == 5){
                         ?>
							<option value="<?php echo $status['id']; ?>"><?php echo $status['name'];?></option>
						<?php }
						} ?>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-4">
               <div class="form-group field-lead-lead_status_id" >
                  <label class="control-label" for="lead-lead_status_id"> Lead Status Remarks *</label>
                  <select  class="form-control" name="lead_status_losss" id="lead_status_losss">
                     <option value="">--Select Lead Status Remarks --</option>
                  </select>
                  <input type="text" required autocomplete="off" class="form-control" name="lead_status_lo" id="lead_status_lo" maxlength="255" placeholder="Lead Status Remarks..." aria-required="true">
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-4" id="lead-dellar">
               <div class="form-group field-lead-dellar">
                  <label class="control-label" for="lead-dellar">If Dealer & Contractor Involved</label>
                  <select class="form-control" name="dillar_data" id="lead_dillar_data"  placeholder="">
                     <option value="">--Select--</option>
                     <option value="0">No</option>
                     <option value="1">Dealer Only</option>
                     <option value="2">Contractor Only</option>
                     <option value="3">Both</option>
                  </select>
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-4" id="lead-dellar_name">
               <div class="form-group field-lead-dellar">
                  <label class="control-label" for="lead-dellar">Dealer Name </label>
                  <input id="dilar" name="dilar" value="" data-role="tagsinput"  type="text">
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-4" id="lead-dellar_contractor">
               <div class="form-group field-lead-dellar">
                  <label class="control-label" for="lead-dellar">Contrator Name</label>
                  <input  name="contractor" id="contractor" value="" data-role="tagsinput"  type="text">
                  <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-4 project_awarded_to">
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
         </div>
			  </fieldset>
		   </div>
		</div>

<!--  Lead Contact -->


			<div class="panel panel-info">
			   <div class="panel-heading">
				  <h3 class="panel-title">Lead Contact Details</h3>
			   </div>
			   <div class="panel-body">
				  <div class="row">
					 <div class="col-md-4">
						<label class="control-label" for="lead-first_name">Select Contact *</label>
						<select id="lead_contact" required class="form-control" required name="lead_contact">
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
			   </div>
			</div>

<!--  Lead Requirnment -->
		<div class="panel panel-info">
			   <div class="panel-heading">
				  <h3 class="panel-title">Lead Requirnment Details</h3>
			   </div>
			   <div class="panel-body">

			<div class="row around10">
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>1.</td>
								<td><input type="text" name='first_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>2.</td>
								<td><input type="text" name='second_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='second_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>3.</td>
								<td><input type="text" name='third_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='third_doc' id="doc1" >
								</td>
							</tr>
						</tbody></table>
				</div>
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>4.</td>
								<td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fourth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>5.</td>
								<td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fifth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>6.</td>
								<td><h4>Document Required By</h4></td>
								<td width="100px">

									<input type="date" name='document_due_date' id='document_due_date' min="<?php echo date('Y-m-d'); ?>"  onkeydown="return false" class="form-control">
								</td>

							</tr>


						</tbody></table>
				</div>
			</div>

			<div class="row">

				<div class="table-responsive s_table">

					<table class="table estimate-items-table items table-main-estimate-edit tablep">
					 <thead>

					 <tr>
						   <th></th>
						   <th style="width:170px" align="center"><?php echo 'Category *'; ?></th>
						   <th style="width:150px" align="center"><?php echo 'Wattage *'; ?></th>
						   <th style="width:170px" align="center"><?php echo 'Item *'; ?></th>
						   <th id="item_details" style="width:170px" align="center">
						   <?php echo 'Item Details'; ?></th>
						    <th style="width: 129px;" align="center"><?php echo 'Item Warranty *'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Qty *'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Req. Price *'; ?></th>
						   <th style="width:140px" align="center"><?php echo 'Document Required '; ?></th>
						   <?php
								if(is_admin())
								{
							?>
						   <th style="width:170px" align="center"><?php echo 'Proposed Item'; ?></th>
						   <th style="width:170px" id="propsed_item_details" align="center"><?php echo 'Proposed Item Details'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Proposed Price'; ?></th>
						   <?php
							   }

						   ?>
						   <th align="center"><i class="fa fa-cog"></i></th>
						</tr>


						<tr>
						   <td style="width:50px">
						   </td>
						   <td style="width:190px">
								<select class="form-control selectpicker" data-live-search="true" id="item_cat">
									<option value="">Select Category</option>
										<?php foreach($items_groups as $_items){ ?>
										  <option value="<?php echo $_items['id']; ?>"><?php echo $_items['name']; ?></option>
									   <?php } ?>

								</select>
						   </td> <td style="width:100px">
								<select class="form-control" id="sub_item_cat">
									<option value="">Select Wattage</option>

								</select>
						   </td>
						   <td style="width:320px">
								<select class="form-control" id="item">
									<option value="">Select Item</option>

								</select>
						   </td>
						   <td id="item_details_d"  style="width:150px">
							  <input type="text" class="form-control" name="item_description" id="item_description" placeholder="<?php echo _l('Item Details'); ?>">

						   </td>
						   <td >
							 <select id="warranty" class="form-control">
								<option>--Select Warranty--</option>
								<option>0 years</option>
								<option value="1">1 years</option>
								<option value="2">2 years</option>
								<option value="3">3 years</option>
								<option value="4">4 years</option>
								<option value="5">5 years</option>
								<option value="6">6 years</option>
								<option value="7">7 years</option>
							  </select>
						   </td>

						  <td style="width:80px">
							  <input style='width:90px;' type="number" min="0" value="1" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">

						   </td>

						   <td style="width:100px">
							  <input type="number" style='width:90px;' id="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">

						   </td>
						   <td style="width:190px">
								<select class="form-control selectpicker"  multiple data-live-search="false"  id="document">
									<option value="">Select Document</option>
									<?php foreach($get_groups_document as $document){ ?>
										  <option value="<?php echo $document['id']; ?>"><?php echo $document['name']; ?></option>
									   <?php } ?>


								</select>
						   </td>
					<?php
						if(is_admin())
						{
					?>
						   <td style="width:320px">
								<select id="proposed_item" class="form-control">
								   <option value=""></option>
								   <?php foreach($items as $group_id=>$_items){ ?>
									 <?php foreach($_items as $item){ ?>
									  <option value="<?php echo $item['id']; ?>"><?php echo $item['description']; ?> </option>
									  <?php } ?>

								   <?php } ?>
								</select>
						   </td>

						    <td id="item_details_du"  style="width:150px">
							  <input type="text" class="form-control" name="item_description_propsed"  id='item_description_propsed' placeholder="<?php echo 'Proposed Item Details'; ?>">

						   </td>

						   <td style="width:100px">
							  <input type="number" id="proposed_rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
					<?php
						 }

					?>
						   <td style="width:50px">

							<a href="javascript:void(0);"  class="add-row btn btn-success create-new-field">
								Add
								<span class="hidden-xs"></span>
							</a>
						   </td>
						</tr>

					 </thead>
					 <tbody>

					 </tbody>
				  </table>

				  <button type="button" class="delete-row">Delete Row</button>
			  </div>
			  <div class="table-responsive col-md-12">
					<table id="datarecord" class="table tabled">
					<tbody>

					 </tbody>
				  </table>
				</div>

			</div>



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
								 <?php echo ucfirst($region_data['region']); ?>
							  </option>
							  <?php } ?>
						   </select>
						</div>
					 </div>
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
				  <div class="col-md-2 text-right">
					 <button type="submit" id="save_record" name="submit" class="btn btn-primary lead_submit float-right">Save</button>
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
	var item_requirnment_exist=0;



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
	   var errormsg = $(this).find("option:selected").text();

   		  if( $('#lead-lead_status_id').val() ==7) {
			  $("#save_record").show()
				$('.project_awarded_to').show();
				$('#lead-dellar').show();
				$("#lead-dellar").prop("required", true);
				$("#project_location").prop("required", true);
				$("#project_title").prop("required", true);
				$("#lead_status_losss").prop("required", true);
				$('.project_total_amount').hide();
				$("#lead_status_lo").prop("required", false);

           } else if( $('#lead-lead_status_id').val() ==6){
				$('.project_awarded_to').hide();
				$('.project_total_amount').show();$('#lead-dellar').show();
				$("#lead-dellar").prop("required", true);
				$("#project_location").prop("required", true);
				$("#project_title").prop("required", true);
				$("#lead_status_losss").prop("required", true);
				$("#lead_status_lo").prop("required", false);
			   // $("#project_total_amount").prop("required", false);
           }else if( $('#lead-lead_status_id').val() ==4 || $('#lead-lead_status_id').val() ==5) {
			 ////alert('Lead cannot be saved in '+errormsg+' without item requirement');
			$('#lead-dellar').show();
			$('.project_awarded_to').hide();
			$("#lead-dellar").prop("required", true);
			$("#project_location").prop("required", true);
			$("#project_title").prop("required", true);

			$("#lead-lead_description").prop("required", true);

			$("#lead-lead_source_id").prop("required", true);
			$("#lead_status_lo").prop("required", true);

			$('.project_total_amount').hide();
			$("#lead_status_losss").prop("required", false);

   }else if($('#lead-lead_status_id').val() ==3){
	   ////alert('Lead cannot be saved in '+errormsg+' without item requirement');
	  ////$("#save_record").prop("disabled", true);
   	$('.project_awarded_to').hide();
   	$('.project_total_amount').hide();
   	$('#lead-dellar').hide();
   	$('#lead-dellar_name').hide();
   	$('#lead-dellar_contractor').hide();
   	$("#lead-dellar").prop("required", false);
   	$("#project_location").prop("required", false);

   	$("#project_title").prop("required", false);
   	$("#project_title").prop("readonly", false);

   	$("#lead-lead_description").prop("required", false);
   	$("#lead-lead_description").prop("readonly", false);
   	$("#lead_status_losss").prop("required", false);
   	$("#lead-lead_source_id").prop("required", false);
   	$("#lead-lead_source_id").prop("disabled", false);
   }else{
	$("#save_record").prop("disabled", false);
	$('.project_awarded_to').hide();
   	$('.project_total_amount').hide();
   	$('#lead-dellar').hide();
   	$('#lead-dellar_name').hide();
   	$('#lead-dellar_contractor').hide();
   	$("#lead-dellar").prop("required", false);
   	$("#project_location").prop("required", false);

   	$("#project_title").prop("required", false);
   	$("#project_title").prop("readonly", false);

   	$("#lead-lead_description").prop("required", false);
   	$("#lead-lead_description").prop("readonly", false);
   	$("#lead_status_losss").prop("required", false);
   	$("#lead-lead_source_id").prop("required", false);
   	$("#lead-lead_source_id").prop("disabled", false);
   }

   });

   });

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
   if($('#lead_dillar_data').val() ==1){
   $('#lead-dellar_name').show();
   $('#lead-dellar_contractor').hide();
   $("#dilar").prop("required", true);
   $("#contractor").prop("required", false);

   		}else if($('#lead_dillar_data').val() ==2){
   $('#lead-dellar_contractor').show();
   $('#lead-dellar_name').hide();

   $("#dilar").prop("required", false);
   $("#contractor").prop("required", true);

   }else if($('#lead_dillar_data').val() ==3){
   $('#lead-dellar_name').show();
   $('#lead-dellar_contractor').show();

   $("#dilar").prop("required", true);
   $("#contractor").prop("required", true);
   }
   		else{
   			$("#dilar").prop("required", false);
   $("#contractor").prop("required", false);

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

   $( "#lead-opportunity_amount" ).focusout(function() {
       var value = $(this).val();
    if(value < 0){
   alert('Opportunity amount must be grater than 0!');
   return false;
    }
   });
   $(document).ready(function() {
        $("#lead-opportunity_amount").keyup(function() {
            if ($(this).val() < 1) {
                alert('Opportunity amount must be grater than 0!');
   	$("#lead-opportunity_amount").focus();
            }

        });
    });
</script>


   <script type="text/javascript">
    $(document).ready(function(){


		$("#item_details").hide();
		$("#item_details_d").hide();
		$("#propsed_item_details").hide();
		$("#item_details_du").hide();
		//$("#save_record").hide();


        $(".add-row").click(function(){
			//$("#save_record").prop("disabled", false);
			$("#document_due_date").prop("required", true);
			var item_cat_val = $('#item_cat').find('option:selected').val();
            var item_cat_text = $('#item_cat').find('option:selected').text();
			var item_sub_cat_val = $('#sub_item_cat').find('option:selected').val();
            var item_sub_cat_text = $('#sub_item_cat').find('option:selected').text();

			var item_val = $("#item :selected").val();
            var item_text = $("#item :selected").text();

            var quantity = $("#quantity").val();
			var warranty = $('#warranty').find('option:selected').val();
            var item_description = $("#item_description").val();

			var item_description_propsed = $("#item_description_propsed").val();


            var rate = $("#rate").val();
            //var document = $('#document').val();
           // var document = $('#document').find('option:selected').text();
			var document = $('#document option:selected').toArray().map(item => item.text).join();


            var proposed_item_val = $('#proposed_item').find('option:selected').val();
            var proposed_item_text = $('#proposed_item').find('option:selected').text();

            var proposed_rate = $("#proposed_rate").val();



			<?php if(is_admin()){ ?>
            var markup = "<tr class='main'><td><input type='checkbox' name='record'></td><td style='width:180px'><span>"+item_cat_text+"</span><input  class='form-control' type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td><td style='width:180px'><span>"+item_sub_cat_text+"</span><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td><td style='width:180px'><span>"+item_text+"</span><input type='hidden' name='item[]' value="+ item_val +"></td><td style='width:280px;'><input type='text'  id='item_desc' name='item_description[]' value="+ item_description +"></td><td style='width:60px;'><span>"+warranty+"</span><input type='hidden' name='warranty[]' value="+ warranty +"></td><td><input style='width:80px;' type='number' name='quantity[]' value="+ quantity +"></td><td style='width:80px'><input type='number' style='width:80px;' name='rate[]' value="+ rate +"></td><td style='width:120px'><input type='text' name='document[]' value="+ document +"></td><td><span>"+proposed_item_text+"</span><input type='hidden' name='proposed_item[]' value="+ proposed_item_val +"></td><td style='width:180px;display:none;'><input type='text' name='item_description_propsed[]'  class='form-control' value="+ item_description_propsed +"></td><td style='width:80px'><input type='number' placeholder='Proposed Rate' class='form-control' name='proposed_rate[]' style='width:80px;' value="+ proposed_rate +"></td></tr>";
			<?php } else { ?>
			if(item_description == ''){
			var markup = "<tr class='main'>"+
					"<td><input type='checkbox' name='record'></td>"+
					"<td>Category<br> <b><span>"+item_cat_text+"</span></b><input type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td>"+
					"<td style='width:180px'>Wattage<br><b><span>"+item_sub_cat_text+"</span></b><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td>"+
					"<td>Item<br><b><span>"+item_text+"</span></b><input type='hidden' name='item[]' value="+ item_val +"><input id='item_desc' class='form-control' type='hidden' name='item_description[]' value='"+ item_description +"' /></td>"+
					"<td style='width:60px;'>Warranty<br><span>"+warranty+"</span><input  class='form-control' type='hidden' name='warranty[]' value="+ warranty +"></td>"+
					"<td>Quantity<br><b><span>"+ quantity +"</span></b><input  class='form-control' type='hidden' style='width:80px;' name='quantity[]' value="+ quantity +"></td>"+
					"<td>Rate<br><b><span>"+ rate +"</span></b><input class='form-control' type='hidden' name='rate[]' style='width:80px;' value="+ rate +"></td>"+
					"<td>Document<br><b><span>"+ document +"</span></b><input  class='form-control' type='hidden' name='document[]' value='"+ document +"'></td>"+
					"</tr>";
		}else{
			var markup = "<tr class='main'><td ><input type='checkbox' name='record'></td><td>Category<br><b><span>"+item_cat_text+"</span></b><input type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td><td style='width:180px'>Wattage<br><b><span>"+item_sub_cat_text+"</span></b><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td><td>Item<br><b><span>"+item_text+"</span></b><input type='hidden' name='item[]' value="+ item_val +"></td><td id='itemdesc'>Description<br><b><span>"+item_description+"</span></b><input id='item_desc' class='form-control' type='hidden' name='item_description[]' value='"+ item_description +"' /></td><td>Warranty<br><span>"+warranty+"</span><input  class='form-control' type='hidden' name='warranty[]' value="+ warranty +"></td><td>Quantity<br><b><span>"+ quantity +"</span></b><input  class='form-control' type='hidden' style='width:80px;' name='quantity[]' value="+ quantity +"></td><td>Rate<br><b><span>"+ rate +"</span></b><input  class='form-control' type='hidden' name='rate[]' style='width:80px;' value="+ rate +"></td><td>Document<br><b><span>"+ document +"</span></b><input  class='form-control' type='hidden' name='document[]' value='"+ document +"'></td></tr>";
		}
			<?php }?>

				if(item_cat_text == 'Select' || item_cat_text == 'Select Category'){
					alert('Please select Category');
					return false;
				}else if(item_sub_cat_text == 'Select' || item_sub_cat_text == '--Select Item--'){
					alert('Please select Wattage');
					return false;
				}else if(item_text == 'Select' || item_text == '--Select Item--'){
					alert('Please select Item');
					return false;
				}else if(rate == ''){
					alert('Please input rate');
					return false;
				}else if(warranty == ''){
					alert('Please input warranty');
					return false;
				}else if(quantity == ''){
					alert('Please add quantity');
					return false;
				}else{
				  $(".tablep tbody").append(markup);
				  $("#item_details").hide();
		          $("#item_details_d").hide();
				  if($("#item_desc").val()==''){
						$("#itemdesc").hide();
					}
				  $('#item_cat').val(0);
					$('#warranty').val();
					$('#item_description').val();
					// $('#sub_item_cat').val(0);
					$('#item').val(0);
					$('#proposed_item').val(0);
					$('#quantity').val('');
					$('#rate').val('');
					$('#proposed_rate').val('');

					$("#item_cat").val('default');
					$("#item_cat").selectpicker("refresh");
					$("#warranty").val('');
					$("#warranty").selectpicker("refresh");	$("#item_description").val('');
					$("#item_description").selectpicker("refresh");

					$("#document").val('default');
					$("#document").selectpicker("refresh");

					$("#item").val('default');
					//$("#item").selectpicker("refresh");

					$("#sub_item_cat").val('default');
					//$("#sub_item_cat").selectpicker("refresh");

				  return true;
				}




		    $('#item_cat').val(0);
			$('#warranty').val();
			$('#item_description').val();
			// $('#sub_item_cat').val(0);
			$('#item').val(0);
			$('#proposed_item').val(0);
			$('#quantity').val('');
			$('#rate').val('');
			$('#proposed_rate').val('');

		    $("#item_cat").val('default');
			$("#item_cat").selectpicker("refresh");
			$("#warranty").val('');
			$("#warranty").selectpicker("refresh");	$("#item_description").val('');
			$("#item_description").selectpicker("refresh");

			$("#document").val('default');
			$("#document").selectpicker("refresh");

			$("#item").val('default');
			//$("#item").selectpicker("refresh");

			$("#sub_item_cat").val('default');
			//$("#sub_item_cat").selectpicker("refresh");


        });

        // Find and remove selected table rows
        $(".delete-row").click(function(){
			$(".tablep tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
			 var item_requirnmentexist =0;
			$(".tablep tbody").find('input[name="record"]').each(function(){

				item_requirnmentexist++;
            });
			if(item_requirnmentexist == 0){
				//$("#save_record").prop("disabled", true);
				$("#document_due_date").prop("required", false);
			}

        });



    });
 </script>


	<script>




 $(document).on('change', '#item_cat', function (e) {
        $('#sub_item_cat').html("");
	    var item_cat = $(this).val();
   		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "admin/lead_requirment/getItemBysubCatID",
            data: {'item_cat': item_cat},
            dataType: "json",
            success: function (data) {
   				console.log(data);
                $.each(data, function (i, obj)
                {

                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";

                });
				div_data += "<option value='0'>New Wattage</option>";
                $('#sub_item_cat').append(div_data);

            }
        });


		/* var item = $("#item option:selected").text();
		var sub_item_cat = $("#sub_item_cat option:selected").text();
        var item_cat = $("#item_cat option:selected").text();

		if(item_cat=='New Category' || sub_item_cat=='New Wattage' || item=='New Item'){
			$("#item_details").show();
			$("#item_details_d").show();

		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();

		} */
    });



  $(document).on('change', '#sub_item_cat', function (e) {
        $('#item').html("");
        $('#proposed_item').html("");

	    var sub_item_cat = $(this).val();

        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';

        $.ajax({
            type: "GET",
            url: base_url + "admin/lead_requirment/getItemByCatID",
            data: {'subgroup_id': sub_item_cat},
            dataType: "json",
            success: function (data) {

				//console.log(data);
                $.each(data, function (i, obj)
                {

                    div_data += "<option value=" + obj.id + ">" + obj.description + "</option>";

                });
				div_data += "<option value='0'>New Item</option>";
                $('#item').append(div_data);
                $('#proposed_item').append(div_data);
            }
        });

		 var item = $("#item option:selected").text();
		var item_description = $(this).text();
        var item_cat = $("#item_cat option:selected").text();

		if(item!=''){


			$("#item_details").show();
			$("#item_details_d").show();

		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();

		}

    });


   $(document).on('change', '#item', function (e) {

        var item = $("#item option:selected").text();
		var sub_item_cat = $("#sub_item_cat option:selected").text();
        var item_cat = $("#item_cat option:selected").text();

		if(item_cat=='New Category' || sub_item_cat=='New Wattage' || item=='New Item'){
			$("#item_details").show();
			$("#item_details_d").show();

		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();

		}

    });


	$(document).on('change', '#proposed_item', function (e) {

        var proposed_item = $("#proposed_item option:selected").text();
		if(proposed_item=='New Item'){
			$("#propsed_item_details").show();
		$("#item_details_du").show();

		}else{
			$("#propsed_item_details").hide();
		$("#item_details_du").hide();

		}

    });

   $( "#lead_add_form" ).submit(function( event ) {
	   var item_requirnment_exist=0;
	   $(".tablep tbody").find('input[name="record"]').each(function(){
            item_requirnment_exist++;
       });
		var lead_status = $('#lead-lead_status_id').find("option:selected").val();
		var errormsg = $('#lead-lead_status_id').find("option:selected").text();
   		if(lead_status > 2 && item_requirnment_exist == 0){
			alert('Lead cannot be saved in '+errormsg+' without item requirement');
			return false;
		}else {
			$(this).find(':input[type=submit]').prop('disabled', true);
			return true;
		}
	});

	</script>




<script src="<?php echo base_url(); ?>assets/plugins/tagsinput.js"></script>
</body>
</html>
