
<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title">
      <?php 
	  
		
		$isurlset = end($this->uri->segments); 
		
		if(isset($lead)){
         echo '#'.$lead->id . ' - ' . $this->leads_model->get_customer_name($lead->customer_name) .'('.  $lead->company .')';
         } else {
         echo _l('add_new',_l('lead'));
         }
         ?>
   </h4>
</div>
<div class="modal-body">
   <?php
      if(isset($lead)){
           if($lead->lost == 1){
              echo '<div class="ribbon danger"><span>'._l('lead_lost').'</span></div>';
           } else if($lead->junk == 1){
              echo '<div class="ribbon warning"><span>'._l('lead_junk').'</span></div>';
           } else {
              if (total_rows('tblclients', array(
                'leadid' => $lead->id))) {
                echo '<div class="ribbon success"><span>'._l('lead_is_client').'</span></div>';
             }
          }
      }
      ?>
   <div class="row">
      <div class="col-md-12">
         <?php if(isset($lead)){
            echo form_hidden('leadid',$lead->id);
            } ?>
         <div class="top-lead-menu">
            <ul class="nav nav-tabs<?php if(!isset($lead)){echo ' lead-new';} ?>" role="tablist">
               <li role="presentation">
                  <a href="#tab_lead_profile" class="lead_pro" aria-controls="tab_lead_profile" role="tab" data-toggle="tab">
                  <?php echo _l('lead_profile'); ?>
                  </a>
               </li>
               <li role="presentation" class="<?php if($isurlset !='lead_reminders'){ echo 'active'; } ?> ">
                  <a href="#lead_requirment" class="lead_req" aria-controls="lead_requirment" role="tab" data-toggle="tab" >
                  <?php echo 'Item Requirment'; ?>
                  </a>
               </li>
               <?php if(isset($lead)){ ?>
               
               <li role="presentation" class="<?php if($isurlset =='lead_reminders'){ echo 'active'; } ?>">
                  <a href="#lead_reminders"  class="leadreminders" onclick="initDataTable('.table-reminders-leads', admin_url + 'misc/get_reminders/' + <?php echo $lead->id; ?> + '/' + 'lead', [4], [4],undefined,[1,'ASC']);" aria-controls="lead_reminders" role="tab" data-toggle="tab">
                  <?php echo _l('Meeting / Follow up'); ?>
                  <?php
                     $total_reminders = total_rows('tblreminders',
                        array(
                           'isnotified'=>0,
                           'staff'=>get_staff_user_id(),
                           'rel_type'=>'lead',
                           'rel_id'=>$lead->id
                           )
                        );
                     if($total_reminders > 0){
                        echo '<span class="badge">'.$total_reminders.'</span>';
                     }
                     ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#tab_tasks_leads" onclick="init_rel_tasks_table(<?php echo $lead->id; ?>,'lead','.table-rel-tasks-leads');" aria-controls="tab_tasks_leads" role="tab" data-toggle="tab">
                  <?php echo 'Task / Assign'; ?>
				  
				   <?php

                     $total_reminders = total_rows('tblstafftasks',
                        array(
                           'status !='=>5,
                           'addedfrom'=>get_staff_user_id(),
                           'rel_type'=>'lead',
                           'rel_id'=>$lead->id
                           )
                        );
                     if($total_reminders > 0){
                        echo '<span class="badge">'.$total_reminders.'</span>';
                     }
                     ?>
                  </a>
               </li>
               <li role="presentation " >
                  <a href="#lead_meeting" aria-controls="lead_meeting" role="tab" data-toggle="tab">
                  <?php echo _l('Remark'); ?>
                  </a>
               </li>
               <li role="presentation">
                  <a href="#lead_activity" aria-controls="lead_activity" role="tab" data-toggle="tab">
                  <?php echo _l('lead_add_edit_activity'); ?>
                  </a>
               </li>
               <?php } ?>
            </ul>
         </div>
         <!-- Tab panes -->
         <div class="tab-content">
            <!-- from leads modal -->
    <!-- Lead Profile -->        
			<div role="tabpanel" class="tab-pane" id="tab_lead_profile">
               <?php $this->load->view('admin/leads/profile'); ?>
            </div>
    <!-- from leads requirment -->        
			<?php if(isset($lead)){ ?>
            <div role="tabpanel" class="tab-pane <?php if($isurlset !='lead_reminders'){ echo 'active'; } ?>" id="lead_requirment">
               <div class="col-md-12 pull-left">
			   
					 <?php
                     if($lead->status == 7 || $lead->status == 6){
                     
                     }
                     else{
                     if(get_staff_role() == 4 || get_staff_role() == 1 || get_staff_role() == 3 || get_staff_role() == 5)
                     { 
                     ?>
                  <a href="<?php echo admin_url('lead_requirment/add_lead_document?id='.$lead->id); ?>" class="btn btn-info pull-left new new-estimate-btn"><?php echo 'Upload Document'; ?></a>
                  <?php 
                     } 
                     
                     }  ?> 
                  <?php
                      if(get_staff_role() == 7 && $lead->status < 6)
                     {
                     ?> 
                  <a href="<?php echo admin_url('lead_requirment/lead_requirnment_file/'.$lead->id); ?>" class="btn btn-info pull-right new new-estimate-btn"><?php echo 'Upload Documents'; ?></a><br>
                  <?php } ?>
               </div>
               <div class="col-md-6 pull-right">
                  <?php
                     if($lead->status == 7 || $lead->status == 6){
                     
                     }
                     else{
                     if(get_staff_role() == 4 || get_staff_role() == 1 || get_staff_role() == 3 || get_staff_role() == 5)
                     { 
                     ?>
                  <a href="<?php echo admin_url('lead_requirment/add_lead_requirement?id='.$lead->id); ?>" class="btn btn-info pull-right new new-estimate-btn"><?php echo 'New Item Requirnment'; ?></a><br>
                  <?php 
                     } 
                     
                     }  ?> 
               </div>
               <hr/>
               <form method="post" action="<?php echo base_url('admin/lead_requirment/update_proposed_item'.$item_id); ?>">
                  <div class="col-md-6">
                     <table class="table dt-table scroll-responsive table-bordered">
                        <h4>User Document</h4>
                        <thead>
                           <tr>
                              <th align="center">Title</th>
                              <th align="center">Document</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $this->db->where('id',$lead->id);
                              $this->db->order_by('id', 'DESC');  //actual field name of id
                              $query_document=$this->db->get('tblleads')->row_array();
                              
                              $this->db->where('lead_id',$lead->id);
                              $this->db->order_by('id', 'DESC');  //actual field name of id
                              $query=$this->db->get('tbllead_requirment_file');
                              
                              $document = $query->result();
                              
                              foreach($document as $title){
                              ?>
                           <tr>
                              <td><?php echo $title->title; ?></td>
                              <td><a href="<?php echo base_url('uploads/lead_documents/').$lead->id.'/'.$title->doc; ?>" target="_blank"> <?php echo $title->doc; ?></a></td>
                           </tr>
                           <?php
                              }
                              ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-6">
                     <table class="table dt-table scroll-responsive table-bordered">
                        <h4>Technical Document  -   Document Due Date(<?php 
						$timestamp2 = strtotime($query_document['document_due_date']);
									$date2 = date("d-m-Y", $timestamp2);
									if($date2 =='01-01-1970'){
										echo '-';
									}else{
										echo $date2; 
									} ?>				
						)</h4>
                        <thead>
                           <tr>
                              <th align="center">Category</th>
                              <th align="center">Item</th>
                              <th align="center">Wattage</th>
                              <th align="center">Title</th>
                              <th align="center">Document</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $this->db->where('lead_id',$lead->id);
                              $this->db->order_by('id', 'DESC');  
                              $query=$this->db->get('tbl_lead_required_doc');
                              
                              $document = $query->result();
                              
                              foreach($document as $data_l){
                              ?>
                           <tr <?php if($data_l->document=='Item Inactive'){ echo 'style="background: #f9eaea;"'; } ?>>
                              <td><?php echo  $this->db->get_where('tblitems_groups', array('id' => $data_l->category_id))->row()->name; ?></td>
                              <td><?php echo $data_l->title; ?></td>
                              <td><?php echo  $this->db->get_where('tblitems_sub_groups', array('id' => $data_l->wattage))->row()->name; ?></td>
                              <td><?php echo $data_l->wattage_title; ?></td>
                              <td>
                                 <?php if($data_l->document=='none') { ?>
                                 Document Pending
                                 <?php }else if($data_l->document=='Item Inactive'){ echo 'Item Inactive'; }else{ ?> 
                                 <a href="<?php echo base_url('uploads/lead_documents/').$lead->id.'/'.$data_l->document; ?>" target="_blank"> <?php echo $data_l->document; ?></a>
                                 <?php } ?>
                              </td>
                           </tr>
                           <?php
                              }
                              ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-12">
                     <input type="hidden" name="lead_id" value="<?php echo $lead->id; ?>" />
                     <style>th{
                        font-weight: bold;
                        }
                     </style>
                     <table class="table dt-table scroll-responsive table-bordered">
                        <thead>
                           <tr>
                              <th>Category</th>
                              <th>Wattage</th>
                              <th>Item</th>
                              <th>New Item <br>Description</th>
                              <th>Item <br>Warranty</th>
                              <th>Qty</th>
                              <th>Rate</th>
                              <th>Total <br> Amount</th>
                              <th>Document <br> Required</th>
                              <th style="width: 167px;">Proposed Item</th>
                              <?php
                                 if(is_admin() || get_staff_role() > 1)
                                 {
                                 ?>
                              <th id="item_details" style="width:100px" class="hide" align="left"><?php echo 'Item Details'; ?></th>
                              <th style="width:50px">Proposed <br> Rate</th>
                              <?php
                                 }
                                 ?>
                              <th style="width: 77px;">Added On</th>
                              <th>Status</th>
                              <th>Reason</th>
                              <?php
                                 if(is_admin() || get_staff_role() > 1){?>
                              <th>App.</th>
                              <?php 
                                 }
                                 ?>
                           </tr>
                        </thead>
                        <tbody>
					
                           <?php
                              $this->db->where('lead_requirment_id',$lead->id);
                              $this->db->order_by('id', 'DESC');  //actual field name of id
                              $query=$this->db->get('tbllead_requirment_detail');
                              
                              $record = $query->result();
                              $rowi = 0;
                              foreach($record as $rec){
                              
                              $itemrecord = array();
                              /* if($rec->category_id != 0){
                              	$this->db->where('group_id',$rec->category_id);
                              	$this->db->group_by('description');
                              	$this->db->order_by('description', 'ASC');  //actual field name of id
                              	$query=$this->db->get('tblitems');
                              
                              	$itemrecord = $query->result();	
                              }else{ */
                              	$this->db->order_by('description', 'ASC');  //actual field name of id
                              	$this->db->group_by('description');
                              	$query=$this->db->get('tblitems');
                              	$itemrecord = $query->result();	
                              
                              /* } */
                              ?>
                           <tr>
                              <td>
                                 <input type="hidden" name="tblitem_id[]" value="<?php echo $rec->id; ?>" />
                                 <?php 
                                    if($rec->category_id == 0){
                                    	echo 'New Category';
                                    }else{
                                    	echo $this->leads_model->get_group($rec->category_id); 
                                    }
                                    
                                    ?>
                              </td>
                              <td><?php
                                 if($rec->subcategory_id == 0){
                                 	echo 'New Wattage';
                                 }else{
                                 	echo $this->leads_model->get_subcategory($rec->subcategory_id); 
                                 }
                                 
                                 ?>
                              </td>
                              <td><?php 
                                 if($rec->item_id == 0){
                                 	echo 'New Item';
                                 }else{
                                 	echo $this->leads_model->get_item($rec->item_id); 
                                 }
                                 
                                 ?>
                              </td>
                              <td><?php echo $rec->item_description; ?></td>
                              <td><?php echo $rec->warranty; ?></td>
                              <td><?php echo $rec->quantity; ?></td>
                              <td><?php echo $rec->rate; ?></td>
                              <td><?php  $total_amount = $rec->quantity*$rec->rate; echo $total_amount; ?></td>
                              <td><?php echo $rec->document; ?></td>
                              <td>
                                 <?php
                                    if(get_staff_role() > 1)
                                    {
                                    	if($rec->item_id != 0){
                                    ?>
									<select class="form-control selectpicker selectpickerlr" data-live-search="true"  name="item_proposed[]" id="<?php echo $rowi; ?>">
                                    <option value="">Select Item</option>
                                    <?php 
                                       foreach($itemrecord as $_items){ 
                                    ?>
                                    <option  value="<?php echo $_items->id; ?>"
                                       <?php if($rec->proposed_item_id == ''){ if($_items->id==$rec->item_id){ echo 'selected'; 
									   }
                                          }
                                          else{
                                           if($_items->id==$rec->proposed_item_id){
                                           echo 'selected'; }
                                          }
                                           ?>><?php echo $_items->description; ?></option>
                                    <?php 
                                       } ?>
									   <option <?php if($rec->proposed_item_id !='' && !is_numeric($rec->proposed_item_id)){ echo 'selected'; } ?> value="new<?php echo $rowi; ?>">New Item</option>
                                 </select>
								 
								 <input type="text" class="form-control <?php if(is_numeric($rec->proposed_item_id) || $rec->proposed_item_id =='' ){ echo 'hidden'; } ?>" id="new<?php echo $rowi; ?>" name="item_proposed_text[]" placeholder="<?php echo _l('Item Details'); ?>" title="<?php echo $rec->proposed_item_id; ?>" value="<?php echo $rec->proposed_item_id; ?>">
		
                                 <?php } else { ?>
								 <input type="text" class="form-control hidden" name="item_proposed_text[]" placeholder="<?php echo _l('Item Details'); ?>" value="">
                                 <input type="text" class="form-control" name="item_proposed[]" placeholder="<?php echo _l('Item Details'); ?>" value="<?php echo $rec->proposed_item_id; ?>">
                                 <?php    }
                                    } 
                                    else { 
                                    if($rec->proposed_item_id=='0'){ 
                                    	echo 'No Item'; 
                                    }else if(!is_numeric($rec->proposed_item_id)){
                                    	echo $rec->proposed_item_id;
                                    }else{ 
                                    	$this->db->where('id', $rec->proposed_item_id);
                                    	echo $this->db->get('tblitems')->row()->description; 
                                    } 
                                    } ?>
                              </td>
                              <td class="hide">
                                 <input type="text" class="form-control" name="item_description[]" placeholder="<?php echo _l('Item Details'); ?>" id="item_details_d" value="<?php echo $rec->item_description; ?>">
                              </td>
                              <?php
                                 if(is_admin() || get_staff_role() > 1)
                                 {
                                 	
                                 ?>
                              <td>
                                 <?php
                                    if($rec->proposed_item_qty==''){
                                    	?>
                                 
                                 <input type="text" <?php if(is_admin() || get_staff_role() != 4){ ?> readonly <?php } ?>  style="width:50px" name="proposed_price[]" value="<?php echo $rec->rate; ?>" />
                                 <?php
                                    }
                                    	else{
                                    		?>
                                 
                                 <input type="text" style="width:50px" name="proposed_price[]" <?php if(is_admin() || get_staff_role() != 4){ ?> readonly <?php } ?> value="<?php echo $rec->proposed_item_qty; ?>" />
                                 <?php
                                    }
                                    ?>
                              </td>
                              <?php
                                 }
                                 ?>
                              <td>
							  <?php  
									$timestamp2 = strtotime($rec->addedon);
									$date2 = date("d-m-Y", $timestamp2);
									if($date2 =='01-01-1970'){
										echo '-';
									}else{
										echo $date2; 
									} 
								?>
								</td>
                              <td>
                                 <div class="btn-group btn-toggle"> 
                                    <?php if($rec->status==0){ ?>
                                    <a id="btn_active_inactive" value="0" href="<?php echo base_url('admin/lead_requirment/lead_requirment_status/'.$lead->id); ?>" class="btn btn-success">Active</a>
                                    <?php }else if($rec->status==1){ ?>
                                    <a id="btn_active_inactive" value="1" href="<?php echo base_url('admin/lead_requirment/lead_requirment_status/'.$lead->id); ?>" class="btn btn-danger active">Inactive</a>
                                    <?php } ?>
                                 </div>
                              </td>
                              <td><?php echo $rec->reason; ?></td>
                              <?php
                                 if(is_admin() || get_staff_role() > 1){ ?>
                              <td align="center">
                                 <input type="checkbox" name="is_approved[]" class="checkbox mass_select_all_wrap" <?php if($rec->is_approved==1){ ?> checked disabled <?php } ?>  value="<?php echo $rec->id; ?>">
                              </td>
                              <?php
                                 }
                                 ?>
                           </tr>
                           <?php
                              $rowi++;
                              }
                              ?>
                           <?php
                              /* if($lead->status == 7 || $lead->status == 6 ||$lead->assproject_manager_approval==1 ||$lead->project_manager_approval==1){ */
                              if($lead->status == 7 || $lead->status == 6 ){
                              }else{
                              if(get_staff_role() == 4 || get_staff_role() == 7)
                              {
                              ?>
                           <tr>
                              <td colspan="19"><input type="submit" id="toogle" onclick="return confirm('Do you agreed to submit.. if you submit its approved from your side!');"  value="UPDATE" class="btn btn-info pull-right  " name="submit" /></td>
                           </tr>
                           <?php } 
                              } ?>
                        </tbody>
                     </table>
                  </div>
               </form>
            </div>
    <!-- from leads Meeting/Followup -->          
			<div role="tabpanel" class="tab-pane <?php if($isurlset =='lead_reminders'){ echo 'active'; } ?>" id="lead_reminders">
               <?php
			      if(get_staff_user_id() == $lead->assigned)
                  {
                  ?> 
               <a href="#" data-toggle="modal" class="btn btn-info" data-target=".reminder-modal-lead-<?php echo $lead->id; ?>"><i class="fa fa-bell-o"></i> <?php echo _l('add follow up'); ?></a>
               <?php
                  }
                  ?>
               <hr />
               <?php render_datatable(
						array(
							'User Remark',
							'Metting Date',
							_l( 'reminder_staff'),
							  'Assigned',
							  'Technical Remark',
							_l( 'reminder_is_notified'),
							'Status',
								'Created Date',
							_l( 'options'), ), 'reminders-leads'); ?>
            </div>
    <!-- from leads Task -->         
		   <div role="tabpanel" class="tab-pane" id="tab_tasks_leads">
               <?php init_relation_tasks_table(array('data-new-rel-id'=>$lead->id,'data-new-rel-type'=>'lead')); ?>
            </div>
    <!-- from leads Remark -->         
            <div role="tabpanel" class="tab-pane" id="lead_meeting">
               <div class="panel_s no-shadow">
                  <div class="col-md-12">
                     <?php echo render_textarea('lead_activity_textarea','','',array('placeholder'=>_l('enter_activity')),array(),'mtop15'); ?>
                     <div class="text-right">
                        <button id="lead_enter_activity" class="btn btn-info"><?php echo _l('submit'); ?></button>
                     </div>
                  </div>
                  <div class="clearfix"></div>
               </div>
            </div>
            
			<div role="tabpanel" class="tab-pane" id="lead_activity">
               <div class="panel_s no-shadow">
                  <div class="activity-feed">
                     <?php
                        foreach($activity_log as $log){ ?>
                     <div class="feed-item">
                        <div class="date">
                           <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo time_ago($log['date']); ?>">
                           <?php echo _dt($log['date']); ?>
                           </span>
                        </div>
                        <div class="text">
                           <?php if($log['staffid'] != 0){ ?>
                           <a href="<?php echo admin_url('profile/'.$log["staffid"]); ?>">
                           <?php echo staff_profile_image($log['staffid'],array('staff-profile-xs-image pull-left mright5'));
                              ?>
                           </a>
                           <?php
                              }
                              $additional_data = '';
                              if(!empty($log['additional_data'])){
                               $additional_data = unserialize($log['additional_data']);
                              
                               echo ($log['staffid'] == 0) ? _l($log['description'],$additional_data) : $log['full_name'] .' - '._l($log['description'],$additional_data);
                              } else {
                                  echo $log['full_name'] . ' - ';
                                 if($log['custom_activity'] == 0){
                                    echo _l($log['description']);
                                 } else {
                                    echo _l($log['description'],'',false);
                                 }
                              }
                              ?>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
                  <div class="clearfix"></div>
               </div>
            </div>
            
			
			<?php } ?>
         </div>
      </div>
   </div>
</div>
<?php do_action('lead_modal_profile_bottom',(isset($lead) ? $lead->id : '')); ?>
<script type="text/javascript">
   $(document).ready(function(){
	
   $("#item_details").hide();
   $("#item_details_d").hide();
   $(document).on('change', '#item', function (e) {
      
      var item = $("#item option:selected").text();
   if(item=='New Item'){
   $("#item_details").show();
   $("#item_details_d").show();
   
   }else{
   $("#item_details").hide();
   $("#item_details_d").hide();
   
   }
   
   
   });
   });
   
   
   $(document).ready(function() {
   $(".leadreq").click(function () {
   $(".lead_req").trigger("click")    
   });
   
   $(".leadpro").click(function () {
   $(".lead_pro").trigger("click")    
   });
   
   $(".lead_reminders").click(function () {
   $(".leadreminders").trigger("click")    
   });
   });
   
   $("#btn_active_inactive").click(function () {
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
   }
   
   $( "form" ).on( "submit", function() { 
		$(this).find( 'input[type="checkbox"]' ).each(function () {
			$(this).removeAttr('disabled');
		});
   });
 
	
</script>