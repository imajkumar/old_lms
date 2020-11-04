
<?php init_head(); ?>

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
		 <li role="presentation">
			<a href="#lead_requirment" class="lead_req" aria-controls="lead_requirment" role="tab" data-toggle="tab" >
				<?php echo 'Item Requirment'; ?>
            </a>
		 
         </li>
         <?php if(isset($lead)){ ?>
         <?php if(count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity){ ?>
         <li role="presentation">
            <a href="#tab_email_activity" aria-controls="tab_email_activity" role="tab" data-toggle="tab">
                <?php echo do_action('lead_email_activity_subject',_l('lead_email_activity')); ?>
            </a>
         </li>
         <?php } ?>
         <li role="presentation" class="hide">
            <a href="#tab_proposals_leads" onclick="initDataTable('.table-proposals-lead', admin_url + 'proposals/proposal_relations/' + <?php echo $lead->id; ?> + '/lead','undefined', 'undefined','undefined',[6,'DESC']);" aria-controls="tab_proposals_leads" role="tab" data-toggle="tab">
            <?php echo _l('proposals'); ?>
            </a>
         </li>
        
         <li role="presentation" class="hide">
            <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
            <?php echo _l('lead_attachments'); ?>
            </a>
         </li>
         <li role="presentation" >
            <a href="#lead_reminders"  class="leadreminders" onclick="initDataTable('.table-reminders-leads', admin_url + 'misc/get_reminders/' + <?php echo $lead->id; ?> + '/' + 'lead', [4], [4],undefined,[1,'ASC']);" aria-controls="lead_reminders" role="tab" data-toggle="tab">
            <?php echo _l('Follow up'); ?>
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
            <?php echo _l('tasks'); ?>
            </a>
         </li>
		 <li role="presentation " >
            <a href="#lead_meeting" aria-controls="lead_meeting" role="tab" data-toggle="tab">
            <?php echo _l('Remark'); ?>
            </a>
         </li>
		 
         <li role="presentation" class="active">
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
      <div role="tabpanel" class="tab-pane" id="tab_lead_profile">
         <?php $this->load->view('admin/leads/profile'); ?>
      </div>
      <?php if(isset($lead)){ ?>
      <?php if(count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity){ ?>
      <div role="tabpanel" class="tab-pane" id="tab_email_activity">
         <?php do_action('before_lead_email_activity',array('lead'=>$lead,'email_activity'=>$mail_activity)); ?>
         <?php foreach($mail_activity as $_mail_activity){ ?>
         <div class="lead-email-activity">
            <div class="media-left">
               <i class="fa fa-envelope"></i>
            </div>
            <div class="media-body">
               <h4 class="bold no-margin lead-mail-activity-subject">
                  <?php echo $_mail_activity['subject']; ?>
                  <br />
                  <small class="text-muted display-block mtop5 font-medium-xs"><?php echo _dt($_mail_activity['dateadded']); ?></small>
               </h4>
               <div class="lead-mail-activity-body">
                  <hr />
                  <?php echo $_mail_activity['body']; ?>
               </div>
               <hr />
            </div>
         </div>
         <div class="clearfix"></div>
         <?php } ?>
         <?php do_action('after_lead_email_activity',array('lead_id'=>$lead->id,'emails'=>$mail_activity)); ?>
      </div>
      <?php } ?>
      <div role="tabpanel" class="tab-pane active" id="lead_activity">
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
      
	  
	  <div role="tabpanel" class="tab-pane" id="lead_requirment">

		 <div class="col-md-9">
		 <?php
		 if(get_staff_role() == 7)
			{
		?>
         <a href="<?php echo admin_url('lead_requirment/lead_requirnment_file/'.$lead->id); ?>" class="btn btn-info pull-right new new-estimate-btn"><?php echo 'Upload Documents'; ?></a><br>
		<?php } ?>
         </div>
		<div class="col-md-3">
		<?php
		 if($lead->status == 7 || $lead->status == 6 ||$lead->assproject_manager_approval==1 ||$lead->project_manager_approval==1){
		 }else{
		 if(get_staff_role() == 4 || get_staff_role() == 1)
			{
		?>
		<a href="<?php echo admin_url('lead_requirment/add_lead_requirement?id='.$lead->id); ?>" class="btn btn-info pull-right new new-estimate-btn"><?php echo 'New Item Requirnment'; ?></a><br>
		<?php 
			} 
			
			} ?> 
		</div>
		<hr/>
		<form method="post" action="<?php echo base_url('admin/lead_requirment/update_proposed_item'.$item_id); ?>">
		
		<div class="col-md-6">
			<table class="table dt-table scroll-responsive">
				<h4>User Document</h4>
				<thead>
					<tr>
						<th>Title</th>
						<th>Document</th>
					</tr>
				</thead>
				<tbody>
				<?php
				
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
			<table id="exampletb2" class="table dt-table scroll-responsive">
				<h4>Technical Document</h4>
				<thead>
					<tr>
						<th>Category</th><th>Item</th><th>Wattage</th><th>Title</th>
						
						<th>Document</th>
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
				
					<tr>
						<td><?php echo  $this->db->get_where('tblitems_groups', array('id' => $data_l->category_id))->row()->name; ?></td>
						<td><?php echo $data_l->title; ?></td>
						
						<td><?php echo  $this->db->get_where('tblitems_sub_groups', array('id' => $data_l->wattage))->row()->name; ?></td>
						<td><?php echo $data_l->wattage_title; ?></td>
						<td>
						<?php if($data_l->document=='none') { ?>
						Document Pending
						<?php }else{ ?> 
						
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
		
         <table id="exampletbl" class="table dt-table scroll-responsive">
        <thead>
		 <tr>
                <th>Category</th> 
				<th>Wattage</th>
                <th>Item</th>
                <th>New Item Description</th> <th>Item Warranty</th>
                <th>Qty</th>
                <th>Rate</th> <th>Total Amount</th>
                <th>Document Required</th>
				<th>Proposed Item</th>
				<?php
					if(is_admin() || get_staff_role() > 1)
					{
				?>
               <th id="item_details" style="width:170px" class="hide" align="left"><?php echo 'Item Details'; ?></th>
                <th>Proposed Rate</th>
				<?php
					}
				?>
				<th>Added On</th>
				<th>Option</th>
				
            </tr>
        </thead>
		
        <tbody>
		<input type="hidden" name="lead_id" value="<?php echo $lead->id; ?>" />
		
		<?php
		
			$this->db->where('lead_requirment_id',$lead->id);
			$this->db->order_by('id', 'DESC');  //actual field name of id
			$query=$this->db->get('tbllead_requirment_detail');
			
			$record = $query->result();
			
			foreach($record as $rec){
			
			$itemrecord = array();
			if($rec->category_id != 0){
				$this->db->where('group_id',$rec->category_id);
				$this->db->order_by('id', 'DESC');  //actual field name of id
				$query=$this->db->get('tblitems');

				$itemrecord = $query->result();	
			}else{
				$this->db->order_by('id', 'DESC');  //actual field name of id
				$query=$this->db->get('tblitems');
				$itemrecord = $query->result();	
		
			}
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
				
				?></td>
				<td><?php
				if($rec->subcategory_id == 0){
					echo 'New Wattage';
				}else{
					echo $this->leads_model->get_subcategory($rec->subcategory_id); 
				}

				 ?></td>
                <td><?php 
				if($rec->item_id == 0){
					echo 'New Item';
				}else{
					echo $this->leads_model->get_item($rec->item_id); 
				}

				 ?></td> 
			
                <td><?php echo $rec->item_description; ?></td> <td><?php echo $rec->warranty; ?></td>
                <td><?php echo $rec->quantity; ?></td>
                <td><?php echo $rec->rate; ?></td>
				
				
				<td><?php

$total_amount = $rec->quantity*$rec->rate;

				echo $total_amount; ?></td>
                <td><?php echo $rec->document; ?></td>
                <td>
				<?php
					if(get_staff_role() > 1)
					{
				?>
				<select class="form-control"  name="item_proposed[]" id="item">
					<option value="">Select Item</option>
						<?php 
						foreach($itemrecord as $_items){ 
						
						?>
						 
						
						 
						
						  <option  value="<?php echo $_items->id; ?>"
						  <?php if($rec->proposed_item_id==''){
							$_items->id==$rec->item_id;
							 echo 'selected'; 
						 }
						 else{
							 $_items->id==$rec->proposed_item_id;
							 echo 'selected'; 
						 }
						  ?>><?php echo $_items->description; ?>-(<?php echo $_items->itme_code; ?>)</option>
						 
						 <?php 
						 
						  
					    } ?>
				</select>
				<?php } else { 
					if($rec->proposed_item_id=='0'){ 
						echo 'No Item'; 
					}else{ 
						$this->db->where('id', $rec->proposed_item_id);
						echo $this->db->get('tblitems')->row()->description; 
					} 
				} ?>
				</td>
				
					<td  class="hide">
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
				
				<input type="text" name="proposed_price[]" value="<?php echo $rec->rate; ?>" />
				<?php
				}
					else{
						?>
						
						
						<input type="text" name="proposed_price[]" value="<?php echo $rec->proposed_item_qty; ?>" />
					<?php
					}
					?>
				
				</td>
				<?php
					
					}
				?>
				<td><?php echo $rec->addedon; ?></td>	
				<td>
					
					<div class="btn-group btn-toggle"> 
					   <?php if($rec->status==1){ ?>
						<a id="btn_active_inactive" value="0" href="<?php echo base_url('admin/lead_requirment/lead_requirment_status/'.$lead->id); ?>" class="btn btn-default">Active</a>
					   <?php }else if($rec->status==0){ ?>
						<a id="btn_active_inactive" value="1" href="<?php echo base_url('admin/lead_requirment/lead_requirment_status/'.$lead->id); ?>" class="btn btn-primary active">Inactive</a>
						<?php } ?>
					</div>
					
			    </td>
            </tr>
			
		<?php
			}
		?>
		 
		
		<?php
		 if($lead->status == 7 || $lead->status == 6 ||$lead->assproject_manager_approval==1 ||$lead->project_manager_approval==1){
		 }else{
		 if(get_staff_role() == 4)
			{
		?>
		
		
		
			
			<tr>
				<td colspan="12"><input type="submit" onclick="return confirm('Do you agreed to submit.. if you submit its approved from your side!');"  value="UPDATE" class="btn btn-info pull-right  " name="submit" /></td>
			</tr>
		 <?php } } ?>
	
		
        </tbody>
        
    </table>
	 



	 </form>
      </div>
      <div role="tabpanel" class="tab-pane" id="tab_tasks_leads">
         <?php init_relation_tasks_table(array('data-new-rel-id'=>$lead->id,'data-new-rel-type'=>'lead')); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="lead_reminders">
        <?php
			if(get_staff_role() == 1)
			{
		?> 
		 <a href="#" data-toggle="modal" class="btn btn-info" data-target=".reminder-modal-lead-<?php echo $lead->id; ?>"><i class="fa fa-bell-o"></i> <?php echo _l('add follow up'); ?></a>
		 <?php
			}
		 ?>
         <hr />
         <?php render_datatable(array( _l( 'reminder_description'), _l( 'reminder_date'), _l( 'reminder_staff'), _l( 'reminder_is_notified'), _l( 'options'), ), 'reminders-leads'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="attachments">
         <?php echo form_open('admin/leads/add_lead_attachment',array('class'=>'dropzone mtop15 mbot15','id'=>'lead-attachment-upload')); ?>
         <?php echo form_close(); ?>
         <?php if(get_option('dropbox_app_key') != ''){ ?>
         <hr />
         <div class="text-center">
            <div id="dropbox-chooser-lead"></div>
         </div>
         <?php } ?>
         <?php if(count($lead->attachments) > 0) { ?>
         <div class="mtop20" id="lead_attachments">
            <?php $this->load->view('admin/leads/leads_attachments_template', array('attachments'=>$lead->attachments)); ?>
         </div>
         <?php } ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="lead_notes">
         <?php echo form_open(admin_url('leads/add_note/'.$lead->id),array('id'=>'lead-notes')); ?>
         <div class="row">
		 <div class="lead-select-date-contacted col-md-3">
            <?php echo render_datetime_input('custom_contact_date','Task Date','',array('data-date-end-date'=>date('2050-m-d'))); ?>
         </div>
         </div>
         <div class="row">
         <div class="form-group col-md-6">
		 <label for="contacted_indicator_yes"><?php echo _l('Description'); ?></label>
                <textarea id="lead_note_description" name="lead_note_description" class="form-control" rows="4"></textarea>
         </div>
         </div>
		 <div class="col-md-6">
		 <?php
					if(is_admin() || get_staff_role() == 4)
					{
				?>
         <button type="submit" class="btn btn-info pull-right"><?php echo _l('ADD TASK'); ?></button>
					<?php } ?>
		 </div>
         <div class="clearfix"></div>
         
         <div class="radio radio-primary hide">
            <input type="radio" name="contacted_indicator" id="contacted_indicator_yes" value="yes">
            <label for="contacted_indicator_yes"><?php echo _l('lead_add_edit_contacted_this_lead'); ?></label>
         </div>
         <div class="radio radio-primary hide">
            <input type="radio" name="contacted_indicator" id="contacted_indicator_no" value="no" checked>
            <label for="contacted_indicator_no"><?php echo _l('lead_not_contacted'); ?></label>
         </div>
         <?php echo form_close(); ?>
         <hr />
         <div class="panel_s mtop20 no-shadow">
            <?php
               $len = count($notes);
               $i = 0;
               foreach($notes as $note){ ?>
            <div class="media lead-note">
               <a href="<?php echo admin_url('profile/'.$note["addedfrom"]); ?>" target="_blank">
               <?php echo staff_profile_image($note['addedfrom'],array('staff-profile-image-small','pull-left mright10')); ?>
               </a>
               <div class="media-body">
                  <?php if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
                  <a href="#" class="pull-right text-danger" onclick="delete_lead_note(this,<?php echo $note['id']; ?>);return false;"><i class="fa fa fa-times"></i></a>
                  <a href="#" class="pull-right mright5" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                  <?php } ?>
                  <?php if(!empty($note['date_contacted'])){ ?>
                  <span data-toggle="tooltip" data-title="<?php echo _dt($note['date_contacted']); ?>">
                  <i class="fa fa-phone-square text-success font-medium valign" aria-hidden="true"></i>
                  </span>
                  <?php } ?>
                  <small><?php echo _l('lead_note_date_added',_dt($note['dateadded'])); ?></small>
                  <a href="<?php echo admin_url('profile/'.$note["addedfrom"]); ?>" target="_blank">
                     <h5 class="media-heading bold"><?php echo get_staff_full_name($note['addedfrom']); ?></h5>
                  </a>
                  <div data-note-description="<?php echo $note['id']; ?>" class="text-muted">
                     <?php echo app_happy_text($note['description']); ?>
                  </div>
                  <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide mtop15">
                     <?php echo render_textarea('note','',$note['description']); ?>
                     <div class="text-right">
                        <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                        <button type="button" class="btn btn-info" onclick="edit_note(<?php echo $note['id']; ?>);"><?php echo _l('update_note'); ?></button>
                     </div>
                  </div>
               </div>
               <?php if ($i >= 0 && $i != $len - 1) {
                  echo '<hr />';
                  }
                  ?>
            </div>
            <?php $i++; } ?>
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
 
	
 </script>
	
	