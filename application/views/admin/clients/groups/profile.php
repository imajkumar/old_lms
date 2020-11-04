<h4 class="customer-profile-group-heading"><?php echo _l('client_add_edit_profile').' ('.$client->company.') - [Added By: '.get_staff_full_name($client->addedfrom) .']'?></h4>
<div class="inline-block new-contact-wrapper" style="float: right;margin: -54px 0px 0px 0px;border-bottom:none" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php if($disable_new_contacts){ ?> data-toggle="tooltip"<?php } ?>>
   <a href="#" onclick="contact(<?php echo $client->userid; ?>); return false;" class="btn btn-default btn-icon pull-left display-block<?php if($disable_new_contacts){echo ' disabled';} ?>"><?php echo _l('new_contact'); ?></a>
</div>
<div class="row">
   <form action="<?php echo base_url(); ?>admin/clients/client/<?php echo $client->userid; ?>" class="client-form" autocomplete="off"
 method="post" accept-charset="utf-8">
   
   <div class="additional"></div>
   <div class="col-md-12">
      <ul class="nav nav-tabs profile-tabs row customer-profile-tabs" role="tablist">
         <li role="presentation" class="<?php if(!$this->input->get('tab')){echo 'active';}; ?>">
            <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
            <?php echo _l( 'customer_profile_details'); ?>
            </a>
         </li>
         <?php
            $customer_custom_fields = false;
            if(total_rows('tblcustomfields',array('fieldto'=>'customers','active'=>1)) > 0 ){
                 $customer_custom_fields = true;
             ?>
         <li role="presentation" class="<?php if($this->input->get('tab') == 'custom_fields'){echo 'active';}; ?>">
            <a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">
            <?php echo do_action('customer_profile_tab_custom_fields_text',_l( 'custom_fields')); ?>
            </a>
         </li>
         <?php } ?>
         <li role="presentation" class="hide">
            <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
            <?php echo _l( 'billing_shipping'); ?>
            </a>
         </li>
         <?php do_action('after_customer_billing_and_shipping_tab',isset($client) ? $client : false); ?>
         <?php if(isset($client)){ ?>
         <li role="presentation<?php if($this->input->get('tab') && $this->input->get('tab') == 'contacts'){echo ' active';}; ?>">
            <a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">
            <?php if(is_empty_customer_company($client->userid)) {
               echo _l('contact');
               } else {
               echo _l( 'customer_contacts');
               }
               ?>
            </a>
         </li>
         <li role="presentation"  class="hide">
            <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
            <?php echo _l( 'customer_admins'); ?>
            </a>
         </li>
         <?php do_action('after_customer_admins_tab',$client); 
		 
?>
         <?php } ?>
         <li role="presentation hide">
            <a href="#home_tab_activity" aria-controls="home_tab_activity" role="tab" data-toggle="tab">
            <i class="fa fa-window-maximize menu-icon"></i><?php echo _l('home_latest_activity'); ?>
            </a>
         </li>
      </ul>
      <div class="tab-content">
         <?php do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
         <?php if($customer_custom_fields) { ?>
         <div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') == 'custom_fields'){echo ' active';}; ?>" id="custom_fields">
            <?php $rel_id=( isset($client) ? $client->userid : false); ?>
            <?php echo render_custom_fields( 'customers',$rel_id); ?>
         </div>
         <?php } ?>
         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            <div class="row">
               <div class="col-md-12<?php if(isset($client) && (!is_empty_customer_company($client->userid) && total_rows('tblcontacts',array('userid'=>$client->userid,'is_primary'=>1)) > 0)) { echo ''; } else {echo ' hide';} ?>" id="client-show-primary-contact-wrapper">
                  <div class="checkbox checkbox-info mbot20 no-mtop hide">
                     <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                     <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments').', '._l('credit_notes')); ?></label>
                  </div>
               </div>
               <div class="col-md-6">
                  <?php 
                     $s_attrs = array('data-none-selected-text'=>_l('Select Group'));
                     $s_attrs['required'] = 'required';
                              $selected = array();
							  if(isset($_GET['cid'])){
								array_push($selected,$_GET['cid']);
							  }
                              if(isset($customer_groups)){
                                foreach($customer_groups as $group){
									array_push($selected,$group['groupid']);
								}
                             }
                             if(is_admin()){
                               echo render_select_with_input_group('groups_in[]',$groups,array('id','name'),'Groups',$selected,'<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>',$s_attrs);
                               } else {
                                
								echo render_select_with_input_group('groups_in[]',$groups,array('id','name'),'Groups',$selected,'',$s_attrs);
                               }
                     
                     ?>
                  <?php $value=( isset($client) ? $client->address : ''); ?>
                  <?php $value=( isset($client) ? $client->address : ''); ?>
                  <?php echo render_input( 'address', 'client_address',$value); ?>
                  <div class="form-group">
                     <label for="exampleInputEmail1"><small class="req text-danger">* </small><?php echo 'State'; ?></label>
                   
  <select  id="state_id" name="state" required class="form-control" >
                        <option value=""><?php echo '--Select--'; ?></option>
                        <?php foreach($all_state as $state) {?>
                        <option <?php if($client->state == $state['id']){ echo 'selected'; } ?>  value="<?php echo $state['id'] ; ?>"> <?php echo $state['state'];?></option>
                        <?php } ?>
                     </select>
                     <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                  </div>
				  <?php $value=( isset($client) ? $client->zip : ''); ?>
                  <?php echo render_input( 'zip', 'client_postal_code',$value); ?>
                   <?php if(get_option('company_requires_vat_number_field') == 1){
                     $value=( isset($client) ? $client->vat : '');
                     echo render_input( 'vat', 'GSTN No.',$value);
                     } ?>
                  
                  <div class="form-group" app-field-wrapper="valid">
                     <label for="valid" class="control-label">Valid From</label>
					 <?php 
						if($client->valid_from !=''){
							$valid_from = $client->valid_from;
						}else{
							$valid_from = date('Y-m-d');
						}
					 
					 ?>
                     <input type="date" id="valid_from" name="valid_from" value="<?php echo $valid_from; ?>" class="form-control">
                  </div>
                  <?php if(!isset($client)){ ?>
                  <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
                  <?php }
                     $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                     $selected = '';
                     if(isset($client) && client_have_transactions($client->userid)){
                        $s_attrs['disabled'] = true;
                     }
                     
                     $s_attrs['disabled'] = true;
                     
                     foreach($currencies as $currency){
                        if(isset($client)){
                          if($currency['id'] == $client->default_currency){
                            $selected = $currency['id'];
                         }
                      }
                     }
                            // Do not remove the currency field from the customer profile!
                     echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
                  <?php if(get_option('disable_language') == 0){ ?>
                  <div class="form-group select-placeholder">
                     <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?>
                     </label>
                     <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('system_default_string'); ?></option>
                        <?php foreach(list_folders(APPPATH .'language') as $language){
                           $selected = '';
                           if(isset($client)){
                              if($client->default_language == $language){
                                 $selected = 'selected';
                              }
                           }
                           ?>
                        <option value="<?php echo $language; ?>" <?php echo $selected; ?>><?php echo ucfirst($language); ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <?php } ?>
                 
               </div>
               <div class="col-sm-6">
                  <div class="form-group" app-field-wrapper="company"><label for="company" class="control-label"> <small class="req text-danger">* </small>Customer</label>
				  
		
		 
			  <input type="text"  id="company" autocomplete="off" name="company" class="form-control" value="<?php echo $client->company; ?>"  value="" data-toggle="" aria-invalid="false" aria-expanded="true">
			  <input type="hidden"  id="addedfrom" autocomplete="off" name="addedfrom" class="form-control" value="<?php echo $client->addedfrom; ?>"  value="" data-toggle="" aria-invalid="false" aria-expanded="true">
			  
			
			
				
		
				   <ul class="dropdown-menu txtcompany" style="margin-left:15px;margin-right:0px;" role="menu" aria-labelledby="dropdownMenu"  id="Dropdowncompany"></ul>
				  </div>
				  
				 </div>
				 <div class="col-sm-6"> 
				  
				  <div class="form-group required">
                     <label class="control-label"><small class="req text-danger">* </small>Country</label>
                     <select id="country_id" class="form-control" name="country" required data-validation="required">
                        <option value="">--Select--</option>
                        <?php foreach($all_country as $country) { if($country->id== 104){ ?>
                        <option <?php if($client->country == $country->id){ echo 'selected'; } ?>   value="<?php echo $country->id ; ?>"> <?php echo $country->country;?></option>
                        <?php } } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  
               </div>
               <div class="col-sm-6">
                  <div class="form-group required">
                     <label class="control-label"><small class="req text-danger">* </small>City</label>
                     <?php 
                        $this->db->select()->from('tbl_city');
                        $this->db->where('state_id', $client->state);
                        $query = $this->db->get();
                        $all_city = $query->result_array();
                        
                        ?>
                     <select required id="city_id" class="form-control" name="city">
                        <option value="">--Select--</option>
                        <?php
                           foreach($all_city as $city) {?>
                        <option <?php if($client->city == $city['id']){ echo 'selected'; } ?>  value="<?php echo $city['id'] ; ?>"> <?php echo $city['city'];?></option>
                        <?php } ?>
                     </select>
                  </div>
				 <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                  <?php echo render_input( 'phonenumber', 'Customer Contact Number',$value); ?>
                   <?php if((isset($client) && empty($client->website)) || !isset($client)){
                     $value=( isset($client) ? $client->website : '');
                     echo render_input( 'website', 'client_website',$value);
                     } else { ?>
                  <div class="form-group">
                     <label for="website"><?php echo _l('client_website'); ?></label>
                     <div class="input-group">
                        <input type="text" name="website" id="website" value="<?php echo $client->website; ?>" class="form-control">
                        <div class="input-group-addon">
                           <span><a href="<?php echo maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                        </div>
                     </div>
                  </div>
                  <?php }
                     ?>
					 
					 
               </div>
               <div class="col-md-6">
                  <div class="form-group" app-field-wrapper="valid">
                     <label for="valid" class="control-label">Valid To</label>
					  <?php 
						if($client->valid_to !=''){
							$valid_to = $client->valid_to;
						}else{
							$today = date("Y-03-31"); // 2012-01-30
							$valid_to = date("Y-m-d", strtotime("$today +1 year"));
						}
					 
					 ?>
						<input type="date" id="valid_to" name="valid_to" value="<?php echo $valid_to; ?>" class="form-control">
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group field-lead-lead_source_id">
                     <label class="control-label" for="lead-lead_source_id"><small class="req text-danger">* </small>Customer type</label>
                     <select id="lead_customer_type_id" class="form-control" name="customer_type" required>
                        <option value="">Select Customer Type</option>
                        <?php foreach($customer_detail_type as $customer) {  ?>
                        <option <?php if($client->customer_type==$customer['code']){ echo 'selected'; } ?> value="<?php echo $customer['code']; ?>"> <?php echo $customer['name'];?></option>
                        <?php } ?>
                     </select>
                     <div class="help-block"></div>
                  </div>
                </div>
				<div class="col-sm-6  <?php if($client->userid==''){ echo 'hide'; } ?>">
                  <div class="form-group field-lead-lead_source_id">
                     <label class="control-label" for="lead-lead_source_id"><small class="req text-danger">* </small>Approved/Reject/Resubmit </label>
                     <select required id="approve" class="form-control" name="approve">
                        <option value="">Select Approval Type</option>
                        
                       <?php if($client->approve == '0'){ 
					    if (has_permission('customers', '', 'approval')) {
							?>
							<option <?php if($client->approve==1){ echo 'selected'; } ?> value="1">Approved</option>
							<option <?php if($client->approve==2){ echo 'selected'; } ?> value="2">Resubmit</option>
							<option <?php if($client->approve==3){ echo 'selected'; } ?> value="3">Reject</option>
							
						<?php
						}
						else{
							?>
							<option selected value="0">Unapproved</option>
							
							
						<?php	
							
						}	
							
					   ?>
					   
					   
					   
                       <?php }else{ ?>
					   <option selected value="0">Unapproved</option>
					   <?php } ?>
                     </select>
                     <div class="help-block"></div>
                  </div>
                </div>
			    <div class="col-md-6">
					<div class="form-group">
                     <label for="remark"><?php echo _l('Reason'); ?></label>
                     <div class="input-group">
					 <span><?php echo $client->remark; ?></span><br>
                        <textarea required style="margin: 0px; width: 514px; height: 57px;" name="remark" id="remark" class="form-control"></textarea>
                      
                     </div>
                  </div>
				</div>
			    <div class="col-md-12">
					 
					 <?php
						if((get_staff_role() > 1 || get_staff_role() == 0 ) || $client->approve==2 || $client->approve==0  || has_permission('customers', '', 'edit')) 
						{ 
					
						?> 
					   <button class="btn btn-default btn-icon pull-right display-block only-save customer-form-submiter" style="margin: 0 0 0 13px;">
						   <?php echo _l( 'submit'); ?>
						</button>
						
						<?php } ?>
						<?php if(!isset($client)){ ?>
						<button class="btn btn-default btn-icon pull-right display-block save-and-add-contact customer-form-submiter">
						   <?php echo _l( 'save_customer_and_add_contact'); ?>
						</button>
						<?php } ?>
					
				</div>
            </div>
         </div>
         <?php if(isset($client)){ ?>
         <div role="tabpanel" class="tab-pane<?php if($this->input->get('tab') && $this->input->get('tab') == 'contacts'){echo ' active';}; ?>" id="contacts">
            <?php if(has_permission('customers','','create') || is_customer_admin($client->userid)){
               $disable_new_contacts = false;
               if(is_empty_customer_company($client->userid) && total_rows('tblcontacts',array('userid'=>$client->userid)) == 1){
                  $disable_new_contacts = true;
               }
               ?>
            <?php } ?>
            <?php
               $table_data = array(_l('client_firstname'),_l('client_lastname'),_l('client_phonenumber'),_l('Mobile'),_l('contact_position'),_l('client_email'),_l('contact_active'),_l('Is Primary'));
               $custom_fields = get_custom_fields('contacts',array('show_on_table'=>1));
               foreach($custom_fields as $field){
                  array_push($table_data,$field['name']);
               }
               array_push($table_data,_l('options'));
               echo render_datatable($table_data,'contacts'); ?>
         </div>
         <div role="tabpanel" class="tab-pane" id="customer_admins">
            <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
            <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
            <?php } ?>
            <table class="table dt-table">
               <thead>
                  <tr>
                     <th><?php echo _l('staff_member'); ?></th>
                     <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <th><?php echo _l('options'); ?></th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($customer_admins as $c_admin){ ?>
                  <tr>
                     <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                        <?php echo staff_profile_image($c_admin['staff_id'], array(
                           'staff-profile-image-small',
                           'mright5'
                           ));
                           echo get_staff_full_name($c_admin['staff_id']); ?></a>
                     </td>
                     <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                     <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                     <td>
                        <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                     </td>
                     <?php } ?>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
         <?php } ?>
         <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-6">
                        <h4 class="no-mtop"><?php echo _l('billing_address'); ?> <a href="#" class="pull-right billing-same-as-customer"><small class="font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></a></h4>
                        <hr />
                        <?php $value=( isset($client) ? $client->billing_street : ''); ?>
                        <?php echo render_textarea( 'billing_street', 'billing_street',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_city : ''); ?>
                        <?php echo render_input( 'billing_city', 'billing_city',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_state : ''); ?>
                        <?php echo render_input( 'billing_state', 'billing_state',$value); ?>
                        <?php $value=( isset($client) ? $client->billing_zip : ''); ?>
                        <?php echo render_input( 'billing_zip', 'billing_zip',$value); ?>
                        <?php $selected=( isset($client) ? $client->billing_country : '' ); ?>
                        <?php echo render_select( 'billing_country',$countries,array( 'country_id',array( 'short_name')), 'billing_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                     </div>
                     <div class="col-md-6">
                        <h4 class="no-mtop">
                           <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_shipping_address_notice'); ?>"></i>
                           <?php echo _l('shipping_address'); ?> <a href="#" class="pull-right customer-copy-billing-address"><small class="font-medium-xs"><?php echo _l('customer_billing_copy'); ?></small></a>
                        </h4>
                        <hr />
                        <?php $value=( isset($client) ? $client->shipping_street : ''); ?>
                        <?php echo render_textarea( 'shipping_street', 'shipping_street',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                        <?php echo render_input( 'shipping_city', 'shipping_city',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_state : ''); ?>
                        <?php echo render_input( 'shipping_state', 'shipping_state',$value); ?>
                        <?php $value=( isset($client) ? $client->shipping_zip : ''); ?>
                        <?php echo render_input( 'shipping_zip', 'shipping_zip',$value); ?>
                        <?php $selected=( isset($client) ? $client->shipping_country : '' ); ?>
                        <?php echo render_select( 'shipping_country',$countries,array( 'country_id',array( 'short_name')), 'shipping_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                     </div>
                     <?php if(isset($client) &&
                        (total_rows('tblinvoices',array('clientid'=>$client->userid)) > 0 || total_rows('tblestimates',array('clientid'=>$client->userid)) > 0 || total_rows('tblcreditnotes',array('clientid'=>$client->userid)) > 0)){ ?>
                     <div class="col-md-12">
                        <div class="alert alert-warning">
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_all_other_transactions" id="update_all_other_transactions">
                              <label for="update_all_other_transactions">
                              <?php echo _l('customer_update_address_info_on_invoices'); ?><br />
                              </label>
                           </div>
                           <b><?php echo _l('customer_update_address_info_on_invoices_help'); ?></b>
                           <div class="checkbox checkbox-default">
                              <input type="checkbox" name="update_credit_notes" id="update_credit_notes">
                              <label for="update_credit_notes">
                              <?php echo _l('customer_profile_update_credit_notes'); ?><br />
                              </label>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
         <div role="tabpanel" class="tab-pane" id="home_tab_activity">
            <div class="clearfix"></div>
            <div class="activity-feed">
               <?php 
                  $activity_log = $this->misc_model->get_activity_log_client($client->userid);
                  
                  foreach($activity_log as $log){ ?>
               <div class="feed-item">
                  <div class="date">
                     <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($log['date']); ?>">
                     <?php echo $log['date']; ?>
                     </span>
                  </div>
                  <div class="text">
                     <?php echo $log['staffid']; ?><br />
                     <?php echo $log['description']; ?>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>
<div id="contact_data"></div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info  pull-right"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<?php $this->load->view('admin/clients/client_group'); ?>