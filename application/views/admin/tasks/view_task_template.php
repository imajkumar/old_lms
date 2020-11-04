<div class="modal-header task-single-header" data-task-single-id="<?php echo $task->id; ?>" data-status="<?php echo $task->status; ?>">
   <?php if($this->input->get('opened_from_lead_id')){ ?>
   <a href="#" onclick="init_lead(<?php echo $this->input->get('opened_from_lead_id'); ?>); return false;" class="back-to-from-task" data-placement="left" data-toggle="tooltip" data-title="<?php echo _l('back_to_lead'); ?>">
      <i class="fa fa-tty" aria-hidden="true"></i>
   </a>
   <?php } ?>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title">Task Details</h4>
     
   
</div>
<div class="modal-body">
   <div class="row">
      <div class="col-md-8">
         <?php if(!empty($task->rel_id)){
			 $this->db->where('id', $task->rel_id);
			 $lead_name = $this->db->get('tblleads')->row()->description;
			 
			$this->db->where('id', $task->rel_id);
			$assigned = $this->db->get('tblleads')->row()->assigned;
							
			 
			$this->db->where('id', $task->rel_id);
			$customer_id = $this->db->get('tblleads')->row()->customer_name;
			
			$this->db->where('userid', $customer_id);
			$customer_name = $this->db->get('tblclients')->row()->company;
		?>		
           
		  <div class="task-single-related-wrapper"><h4 class="bold font-medium mbot15"><a href="<?php echo base_url().'admin/leads/index/'.$task->rel_id; ?>"><?php echo '#'.$task->rel_id.' '.$customer_name.' ( '.$lead_name.')   -   '. get_staff_full_name($assigned);  ?></a></h4></div>
		  <?php } ?>
		  <hr>
		  <div class="clearfix"></div>
		  <h4>Description</h4>
		  <p><?php echo $task->name; ?></p>
		  <div class="clearfix"></div>
		  <?php if($task->status != 5 && ($task->current_user_is_assigned || is_admin() || $task->current_user_is_creator)){ ?>
		  <p class="no-margin pull-left hide" style="<?php echo 'margin-'.(is_rtl() ? 'left' : 'right').':5px !important'; ?>">
			 <a href="#" class="btn btn-info" id="task-single-mark-complete-btn" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="mark_complete(<?php echo $task->id; ?>); return false;" data-toggle="tooltip" title="<?php echo _l('task_single_mark_as_complete'); ?>">
				<i class="fa fa-check"></i>
			 </a>
		  </p>
		  <?php } else if($task->status == 5 && ($task->current_user_is_assigned || is_admin() || $task->current_user_is_creator)){ ?>
		  <p class="no-margin pull-left hide" style="<?php echo 'margin-'.(is_rtl() ? 'left' : 'right').':5px !important'; ?>">
			 <a href="#" class="btn btn-default" id="task-single-unmark-complete-btn" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="unmark_complete(<?php echo $task->id; ?>); return false;" data-toggle="tooltip" title="<?php echo _l('task_unmark_as_complete'); ?>">
				<i class="fa fa-check"></i>
			 </a>
		  </p>
		  <?php } ?>
			 
		
	   <a href="#" id="taskCommentSlide" onclick="slideToggle('.tasks-comments'); return false;" >
		  <h4 class="mbot20 font-medium"><?php echo _l('task_comments'); ?></h4>
	   </a>
	   <div class="tasks-comments inline-block full-width simple-editor">
		  <textarea name="comment" placeholder="<?php echo _l('task_single_add_new_comment'); ?>" id="task_comment" rows="3" class="form-control ays-ignore"></textarea>
		  <button type="button" class="btn btn-info mtop30 pull-right" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" onclick="add_task_comment('<?php echo $task->id; ?>');">
			 <?php echo _l('task_single_add_new_comment'); ?>
		  </button>
		  <div class="clearfix"></div>
		  <?php if(count($task->comments) > 0){echo '<hr />';} ?>
		  <div id="task-comments" class="mtop10">
			 <?php
			 $comments = '';
			 $len = count($task->comments);
			 $i = 0;
			 foreach ($task->comments as $comment) {
			   $comments .= '<div id="comment_'.$comment['id'].'" data-commentid="' . $comment['id'] . '" data-task-attachment-id="'.$comment['file_id'].'" class="tc-content task-comment'.(strtotime($comment['dateadded']) >= strtotime('-16 hours') ? ' highlight-bg' : '').'">';
			   $comments .= '<a data-task-comment-href-id="'.$comment['id'].'" href="'.admin_url('tasks/view/'.$task->id).'#comment_'.$comment['id'].'" class="task-date-as-comment-id"><small><span class="text-has-action inline-block mbot5" data-toggle="tooltip" data-title="'._dt($comment['dateadded']).'">' . time_ago($comment['dateadded']) . '</span></small></a>';
			   if($comment['staffid'] != 0){
				$comments .= '<a href="' . admin_url('profile/' . $comment['staffid']) . '" target="_blank">' . staff_profile_image($comment['staffid'], array(
				 'staff-profile-image-small',
				 'media-object img-circle pull-left mright10'
			  )) . '</a>';
			 } elseif($comment['contact_id'] != 0) {
				$comments .= '<img src="'.contact_profile_image_url($comment['contact_id']).'" class="client-profile-image-small media-object img-circle pull-left mright10">';
			 }
			 if ($comment['staffid'] == get_staff_user_id() || is_admin()) {
				$comment_added = strtotime($comment['dateadded']);
				$minus_1_hour = strtotime('-1 hours');
				if(get_option('client_staff_add_edit_delete_task_comments_first_hour') == 0 || (get_option('client_staff_add_edit_delete_task_comments_first_hour') == 1 && $comment_added >= $minus_1_hour) || is_admin()){
				  $comments .= '<span class="pull-right"><a href="#" onclick="remove_task_comment(' . $comment['id'] . '); return false;"><i class="fa fa-times text-danger"></i></span></a>';
				  $comments .= '<span class="pull-right mright5"><a href="#" onclick="edit_task_comment(' . $comment['id'] . '); return false;"><i class="fa fa-pencil-square-o"></i></span></a>';
			   }
			}
			$comments .= '<div class="media-body">';
			if($comment['staffid'] != 0){
			  $comments .= '<a href="' . admin_url('profile/' . $comment['staffid']) . '" target="_blank">' . $comment['staff_full_name'] . '</a> <br />';
		   } elseif($comment['contact_id'] != 0) {
			  $comments .= '<span class="label label-info mtop5 mbot5 inline-block">'._l('is_customer_indicator').'</span><br /><a href="' . admin_url('clients/client/'.get_user_id_by_contact_id($comment['contact_id']) .'?contactid='.$comment['contact_id'] ) . '" class="pull-left" target="_blank">' . get_contact_full_name($comment['contact_id']) . '</a> <br />';
		   }
		   $comments .= '<div data-edit-comment="'.$comment['id'].'" class="hide edit-task-comment"><textarea rows="5" id="task_comment_'.$comment['id'].'" class="ays-ignore">'.$comment['content'].'</textarea>
		   <div class="clearfix mtop20"></div>
		   <button type="button" class="btn btn-info pull-right" onclick="save_edited_comment('.$comment['id'].','.$task->id.')">'._l('submit').'</button>
		   <button type="button" class="btn btn-default pull-right mright5" onclick="cancel_edit_comment('.$comment['id'].')">'._l('cancel').'</button>
		   </div>';
		   if($comment['file_id'] != 0){
			$comment['content'] = str_replace('[task_attachment]',$attachments_data[$comment['file_id']],$comment['content']);
							   // Replace lightbox to prevent loading the image twice
			$comment['content'] = str_replace('data-lightbox="task-attachment"','data-lightbox="task-attachment-comment"',$comment['content']);
		 }
		 $comments .= '<div class="comment-content mtop10">'.app_happy_text(check_for_links($comment['content'])) . '</div>';
		 $comments .= '</div>';
		 if ($i >= 0 && $i != $len - 1) {
			$comments .= '<hr class="task-info-separator" />';
		 }
		 $comments .= '</div>';
		 $i++;
	  }
	  echo $comments;
	  ?>
	</div>
	</div>

</div>
<div class="col-md-4 task-single-col-right">
  
	<h4 class="task-info-heading"><?php echo _l('task_info'); ?>
	<?php
	  if($task->recurring == 1){
		 echo '<span class="label label-info inline-block mleft5">'._l('recurring_task').'</span>';
	  }
	?>
	</h4>
	<div class="clearfix"></div>
	<h5 class="no-mtop task-info-created">
   <?php if(($task->assigned_to != 0 )){ ?>
   <small class="text-dark"><?php echo _l('task_created_by','<span class="text-dark">'.($task->is_added_from_contact == 0 ? get_staff_full_name($task->assigned_to) : get_contact_full_name($task->assigned_to)).'</span>'); ?> <i class="fa fa-clock-o" data-toggle="tooltip" data-title="<?php echo _l('task_created_at',_dt($task->dateadded)); ?>"></i></small>
   <br />
   <?php } else { ?>
   <small class="text-dark"><?php echo _l('task_created_at','<span class="text-dark">'._dt($task->dateadded).'</span>'); ?></small>
   <?php } ?>
</h5>
<hr class="task-info-separator" />
<?php if($task->status == 5){ ?>
<div class="task-info task-info-finished">
   <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa-check"></i>
      <?php echo _l('task_single_finished'); ?>: <span data-toggle="tooltip" data-title="<?php echo _dt($task->datefinished); ?>" data-placement="bottom" class="text-has-action"><?php echo time_ago($task->datefinished); ?></span>
   </h5>
</div>
<?php } ?>
<div class="task-info task-single-inline-wrap task-info-start-date">
   <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
      <?php echo _l('task_single_start_date'); ?>:
      <?php if(has_permission('tasks','','edit') && $task->status !=5) { ?>
      <input name="startdate" value="<?php echo _d($task->startdate); ?>" id="task-single-startdate" class="task-info-inline-input-edit datepicker pointer task-single-inline-field">
      <?php } else { ?>
      <?php echo _d($task->startdate); ?>
      <?php } ?>
   </h5>
</div>
<div class="task-info task-info-due-date task-single-inline-wrap<?php if(!$task->duedate && !has_permission('edit','','tasks')){echo ' hide';} ?>"<?php if(!$task->duedate){ echo ' style="opacity:0.5;"';} ?>>
   <h5><i class="fa fa-calendar-check-o task-info-icon fa-fw fa-lg pull-left"></i>
      <?php echo _l('task_single_due_date'); ?>:
      <?php if(has_permission('tasks','','edit') && $task->status !=5) { ?>
      <input name="duedate" value="<?php echo _d($task->duedate); ?>" id="task-single-duedate" class="task-info-inline-input-edit datepicker pointer task-single-inline-field">
      <?php } else { ?>
      <?php echo _d($task->duedate); ?>
      <?php } ?>
   </h5>
</div>
<div class="task-info task-info-priority">
   <h5>
      <i class="fa task-info-icon fa-fw fa-lg pull-left fa-bolt"></i>
      <?php echo _l('task_single_priority'); ?>:
      <?php if(has_permission('tasks','','edit') && $task->status != 5) { ?>
      <span class="task-single-menu task-menu-priority">
         <span class="trigger pointer manual-popover text-has-action" style="color:<?php echo task_priority_color($task->priority); ?>;">
            <?php echo task_priority($task->priority); ?>
         </span>
         <span class="content-menu hide">
            <ul>
               <?php
               foreach(get_tasks_priorities() as $priority){ ?>
               <?php if($task->priority != $priority['id']){ ?>
               <li>
                  <a href="#" onclick="task_change_priority(<?php echo $priority['id']; ?>,<?php echo $task->id; ?>); return false;">
                     <?php echo $priority['name']; ?>
                  </a>
               </li>
               <?php } ?>
               <?php } ?>
            </ul>
         </span>
      </span>
      <?php } else { ?>
      <span style="color:<?php echo task_priority_color($task->priority); ?>;">
         <?php echo task_priority($task->priority); ?>
      </span>
      <?php } ?>
   </h5>
</div>

<hr class="task-info-separator" />
<div class="clearfix"></div>
<?php if($task->current_user_is_assigned){
   foreach($task->assignees as $assignee){
     if($assignee['assigneeid'] == get_staff_user_id() && get_staff_user_id() != $assignee['assigned_from'] && $assignee['assigned_from'] != 0 || $assignee['is_assigned_from_contact'] == 1){
      if($assignee['is_assigned_from_contact'] == 0){
        echo '<p class="text-muted task-assigned-from">'._l('task_assigned_from','<a href="'.admin_url('profile/'.$assignee['assigned_from']).'" target="_blank">'.get_staff_full_name($assignee['assigned_from'])).'</a></p>';
     } else {
      echo '<p class="text-muted task-assigned-from task-assigned-from-contact">'._l('task_assigned_from',get_contact_full_name($assignee['assigned_from'])).'<br /><span class="label inline-block mtop5 label-info">'._l('is_customer_indicator').'</span></p>';
   }
   break;
}
}
} ?>
<h4 class="task-info-heading font-normal font-medium-xs"><i class="fa fa-user-o" aria-hidden="true"></i> <?php echo _l('task_single_assignees'); ?></h4>
<?php if(has_permission('tasks','','edit') || has_permission('tasks','','create')){ ?>
<div class="simple-bootstrap-select hidden">
   <select data-width="100%" <?php if($task->rel_type=='project'){ ?> data-live-search-placeholder="<?php echo _l('search_project_members'); ?>" <?php } ?> data-task-id="<?php echo $task->id; ?>" id="add_task_assignees" class="text-muted task-action-select selectpicker" name="select-assignees" data-live-search="true" title='<?php echo _l('task_single_assignees_select_title'); ?>' data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
      <?php
      $options = '';
      foreach ($staff as $assignee) {
        if (!in_array($assignee['staffid'],$task->assignees_ids)) {
          if ($task->rel_type == 'project'
            && total_rows('tblprojectmembers', array('project_id' => $task->rel_id,'staff_id' => $assignee['staffid'])) == 0) {
           continue;
     }
     $options .= '<option value="' . $assignee['staffid'] . '">' . $assignee['full_name'] . '</option>';
  }
}
echo $options;
?>
</select>
</div>
<?php } ?>
<div class="task_users_wrapper">
   <?php
   $_assignees = '';
   foreach ($task->assignees as $assignee) {
    $_remove_assigne = '';
    if(has_permission('tasks','','edit') || has_permission('tasks','','create')){
      //$_remove_assigne = ' <a href="#" class="remove-task-user text-danger" onclick="remove_assignee(' . $assignee['id'] . ',' . $task->id . '); return false;"><i class="fa fa-remove"></i></a>';
   }
   $_assignees .= '
   <div class="task-user"  data-toggle="tooltip" data-title="'.$assignee['full_name'].'">'.$assignee['full_name'].'</a>  </div>';
}
if ($_assignees == '') {
   $_assignees = '<div class="text-danger display-block">'._l('task_no_assignees').'</div>';
}
echo $_assignees;
?>
</div>
<hr class="task-info-separator" />
<?php echo form_open_multipart('admin/tasks/upload_file',array('id'=>'task-attachment','class'=>'dropzone')); ?>
<?php echo form_close(); ?>
<?php if(get_option('dropbox_app_key') != ''){ ?>
<div class="text-center mtop10">
   <div id="dropbox-chooser-task"></div>
</div>
<?php } ?>

<hr class="task-info-separator" />
<div class="task-info task-status task-info-status">
   <h4>
      <i class="fa fa-<?php if($task->status == 5){echo 'star';} else if($task->status == 1){echo 'star-o';} else {echo 'star-half-o';} ?> pull-left task-info-icon fa-fw fa-lg"></i><?php echo 'Update '._l('task_status'); ?>:
      <?php if($task->current_user_is_assigned || $task->current_user_is_creator || has_permission('tasks','','edit')) { ?>
      <span class="task-single-menu task-menu-status">
         <span class="<?php if($task->current_user_is_assigned) { ?>trigger<?php } ?> pointer manual-popover text-has-action">
            <?php echo format_task_status($task->status,true); 
			
			?>
         </span>
         <span class="content-menu hide">
            <ul>
               <?php
               $task_single_mark_as_statuses = do_action('task_single_mark_as_statuses',$task_statuses);
               foreach($task_single_mark_as_statuses as $status){ ?>
               <?php if($task->status != $status['id']){ ?>
               <li>
                  <a href="#" onclick="task_mark_as(<?php echo $status['id']; ?>,<?php echo $task->id; ?>); return false;">
                     <?php echo _l('task_mark_as',$status['name']); ?>
                  </a>
               </li>
               <?php } ?>
               <?php } ?>
            </ul>
         </span>
      </span>
      <?php } else { ?>
      <?php echo format_task_status($task->status,true); ?>
      <?php } ?>
   </h4>
</div>

</div>
</div>
</div>
<script>
var commonTaskPopoverMenuOptions = {
   html: true,
   placement: 'bottom',
   trigger: 'click',
   template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>',
};

var taskPopoverMenus = [{
      selector: '.task-menu-options',
      title: "<?php echo _l('actions'); ?>",
   },
   {
      selector: '.task-menu-status',
      title: "<?php echo _l('task_status'); ?>",
   },
   {
      selector: '.task-menu-priority',
      title: "<?php echo _l('task_single_priority'); ?>",
   },
   {
      selector: '.task-menu-milestones',
      title: "<?php echo _l('task_milestone'); ?>",
   },
];

for (var i = 0; i < taskPopoverMenus.length; i++) {
   var taskMenuContent = $('body').find(taskPopoverMenus[i].selector + ' .content-menu').html();
   $(taskPopoverMenus[i].selector + ' .trigger').popover($.extend({}, commonTaskPopoverMenuOptions, {
      title: taskPopoverMenus[i].title,
      content: taskMenuContent
   }));
}

if (typeof (Dropbox) != 'undefined') {
   document.getElementById("dropbox-chooser-task").appendChild(Dropbox.createChooseButton({
      success: function (files) {
         $.post(admin_url + 'tasks/add_external_attachment', {
            files: files,
            task_id: '<?php echo $task->id; ?>',
            external: 'dropbox'
         }).done(function () {
            init_task_modal('<?php echo $task->id; ?>');
         });
      },
      linkType: "preview",
      extensions: app_allowed_files.split(','),
   }));
}

init_selectpicker();
init_datepicker();
init_lightbox();

tinyMCE.remove('#task_view_description');

if (typeof (taskAttachmentDropzone) != 'undefined') {
   taskAttachmentDropzone.destroy();
}

taskAttachmentDropzone = new Dropzone("#task-attachment", $.extend({}, _dropzone_defaults(), {
   uploadMultiple: true,
   parallelUploads: 20,
   maxFiles: 20,
   paramName: 'file',
   sending: function (file, xhr, formData) {
      formData.append("taskid", '<?php echo $task->id; ?>');
   },
   success: function (files, response) {
      response = JSON.parse(response);
      if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
         _task_append_html(response.taskHtml);
      }
   }
}));
</script>
