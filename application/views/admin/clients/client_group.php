<div class="modal fade" id="customer_group_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('customer_group_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('customer_group_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/clients/group',array('id'=>'customer-group-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label"> <small class="req text-danger">* </small>Name</label><input type="text"  autocomplete="off" id="name" name="name" class="form-control">
					   </div>
						<ul class="dropdown-menu txtcountry" style="margin-left:15px;margin-right:0px;" role="menu" aria-labelledby="dropdownMenu"  id="DropdownCountry"></ul>
                        <?php echo form_hidden('id'); ?>
						<input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="addedby"/>
                    </div> 

					<div class="col-md-12">
                       <div class="form-group" app-field-wrapper="name"><label for="name" class="control-label"> <small class="req text-danger">* </small>Segment</label>
					   <select class="form-control" required name="segment" id="segment">
					   
					  
					    <?php 
								$this->db->select()->from('segment');
								
								$query = $this->db->get();
								$all_city = $query->result_array();		
							?>
                              <option value="">--Select--</option>  
							  <?php foreach($all_city as $status_loss) {?>
                              <option value="<?php echo $status_loss['name']; ?>"> <?php echo $status_loss['name'];?></option>
                              <?php } ?>
							    </select>
					   </div>
					
						
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load',function(){
       _validate_form($('#customer-group-modal'), {
        name: 'required'
    }, manage_customer_groups);

       $('#customer_group_modal').on('show.bs.modal', function(e) {
        var invoker = $(e.relatedTarget);
        var group_id = $(invoker).data('id');
        $('#customer_group_modal .add-title').removeClass('hide');
        $('#customer_group_modal .edit-title').addClass('hide');
        $('#customer_group_modal input[name="id"]').val('');
        $('#customer_group_modal input[name="name"]').val('');
		$('#customer_group_modal input[name="segment"]').val('');
        // is from the edit button
        if (typeof(group_id) !== 'undefined') {
            $('#customer_group_modal input[name="id"]').val(group_id);
            $('#customer_group_modal .add-title').addClass('hide');
            $('#customer_group_modal .edit-title').removeClass('hide');
            $('#customer_group_modal input[name="name"]').val($(invoker).parents('tr').find('td').eq(1).text());
			var selected = $(invoker).parents('tr').find('td').eq(2).text();
			$('#segment option').filter(function() { return $.trim($(this).text()) == selected; } ).attr('selected',true);
		}
    });
   });
    function manage_customer_groups(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                if($.fn.DataTable.isDataTable('.table-customer-groups')){
                    $('.table-customer-groups').DataTable().ajax.reload();
                }
                if($('body').hasClass('dynamic-create-groups') && typeof(response.id) != 'undefined') {
                    var groups = $('select[name="groups_in[]"]');
                    groups.prepend('<option value="'+response.id+'">'+response.name+'</option>');
                    groups.selectpicker('refresh');
					
                }
           alert(response.message);
			    location.reload(); 
            }
			
            $('#customer_group_modal').modal('hide');
        });
        return false;
    }

	
    

</script>

