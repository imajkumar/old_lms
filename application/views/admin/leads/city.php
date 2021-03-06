<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="#" onclick="new_status(); return false;" class="btn btn-info pull-left display-block">
								<?php echo 'Add New City'; ?>
							</a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading"/>
						<?php if(count($city) > 0){ ?>
						<table class="table dt-table scroll-responsive">
							<thead>
							
							<th><?php echo 'Country'; ?></th>
							<th><?php echo 'State'; ?></th>
								<th><?php echo 'City'; ?></th>
						<th><?php echo _l('options'); ?></th>
							</thead>
							<tbody>
								<?php foreach($city as $status){ ?>
								<tr>
									<td><?php echo $status['country_name']; ?></td>
									<td><?php echo $status['state_name']; ?></td>
									<td><?php echo $status['city']; ?></td>
									<td>
										<a href="#" onclick="edit_city(this,<?php echo $status['city_id']; ?>); return false" data-name="<?php echo $status['city']; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
										<!--<a href="<?php echo admin_url('leads/delete_city/'.$status['city_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>-->
									</td>
								</tr>
								<?php } ?>
								</tbody>
							</table>
							<?php } else { ?>
							<p class="no-margin"><?php echo _l('lead_statuses_not_found'); ?></p>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="city" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('leads/update_city')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo 'Edit City'; ?></span>
                    <span class="add-title"><?php echo 'City Add'; ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','City Name'); ?>
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

	<?php include_once(APPPATH.'views/admin/leads/add_city.php'); ?>
	<?php init_tail(); ?>
</body>
</html>

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
   
   function edit_city(invoker,id){
    	var name = $(invoker).data('name');
    	$('#additional').append(hidden_input('id',id));
    	$('#city input[name="name"]').val(name);
    	$('#city').modal('show');
    	$('.add-title').addClass('hide');
    }
  </script>

