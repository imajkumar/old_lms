<div class="modal fade _event" id="newEventModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Meeting / Task</h4>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
			<ul class="nav nav-tabs ">
			  <li class="active"><a href="#academic" data-toggle="tab" aria-expanded="true"> Meeting/Follow</a></li>
			 <?php	if(get_staff_role() != 1) { ?> 
			  <li class=""><a href="#extra" data-toggle="tab" aria-expanded="false"> Task/Assign</a></li>
			 <?php } ?>
			</ul>
			<div class="tab-content">
			  <div id="academic" class="tab-pane fade active in">
				<form role="form" method="post" id="academic_dtls">
				  <?php $this->load->view('admin/utilities/calendar_meeting'); ?>
				</form>
			  </div>
			  <?php if(get_staff_role() != 1) { ?>
			  <div id="extra" class="tab-pane">
				<form role="form" method="post"  id="extra_dtls">
				  <?php $this->load->view('admin/utilities/calendar_task'); ?>
				</form>
			  </div>
			  <?php } ?>
			</div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    
  </div>
 
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 