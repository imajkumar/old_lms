<div class="modal fade" id="depot" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('leads/depo_master_add'), array('id'=>'leads-depot-form')); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="edit-title"><?php echo _l('Edit Depot Mater'); ?></span>
               <span class="add-title"><?php echo _l('New Depot Master'); ?></span>
            </h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="additional"></div>
                  <?php echo render_input('depcode', 'Depotcode'); ?>
                  <?php echo render_input('description', 'Description'); ?>
                  
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
<!-- /.modal -->
<script>
  window.addEventListener('load', function () {
    _validate_form($("body").find('#leads-depot-form'), {
        name: 'required'
    }, manage_leads_depotes);
    $('#depot').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
             
        $('#depot input[name="depcode"]').val('');
        $('#depot input[name="description"]').val('');
     
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        
    });
});

// Create lead new depot
function new_depot() {
    $('#depot').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit depot function which init the data to the modal
function edit_depot(invoker, id) {
    $('#additional').append(hidden_input('id', id));
	  $('#depot input[name="depcode"]').val($(invoker).data('depcode'));
    $('#depot input[name="description"]').val($(invoker).data('name'));
   
   

    $('#depot').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for leads depot
function manage_leads_depotes(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}
</script>
