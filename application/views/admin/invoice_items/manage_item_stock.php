<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
           <?php do_action('before_items_page_content'); ?>
           <?php if(has_permission('items','','create')){ ?>
           <div class="_buttons hidden">
            <a href="#" class="btn btn-default pull-left" data-toggle="modal" data-target="#sales_item_modal"><?php echo _l('new_invoice_item'); ?></a>
            <a href="#" class="btn btn-default pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo _l('item_groups'); ?></a>
			<a href="#" style="margin-left:10px" class="btn btn-default pull-left mleft6" data-toggle="modal" data-target="#subgroups"><?php echo 'Wattage'; ?></a>
		  </div>
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
          <?php } ?>
         <table class="table dt-table  scroll-responsive">
							<thead>
							
								<th><?php echo 'Item Code'; ?></th>
								<th><?php echo 'Description'; ?></th>
								<th><?php echo 'Long Description'; ?></th>
								<th><?php echo 'Group Name'; ?></th>
								<th><?php echo 'Wattage'; ?></th>
								<th><?php echo 'Stock'; ?></th>
								<th><?php echo 'Depot description'; ?></th>
								
								
							</thead>
							<tbody>
							<?php
							foreach($items_groups as $items){
								?>
								<tr>
								<td><?php echo $items['itme_code'];  ?></td>
								<td><?php echo $items['description'];  ?></td>
								<td><?php echo $items['long_description'];  ?></td>
								<td><?php echo $items['group_name'];  ?></td>
								<td><?php echo 
								$this->invoice_items_model->wattage_data($items['subgroup_id']);  ?></td>
								<td><?php echo $items['stock'];  ?></td>
								<td><?php echo $items['depot_code'];  ?></td>
							
								</tr>
								
								
							<?php	
							}
							
							?>
							
							
							
							
							</tbody>
							</table>


		 </div>
        </div>
      </div>
    </div>
  </div>
</div>









<?php init_tail(); ?>
<script>
  $(document).on('change', '#group_id', function (e) {
        $('#subgroup_id').html("");
        var group_id = $(this).val();
  
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select Wattage'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBysubcategory",
            data: {'group_id': group_id},
            dataType: "json",
            success: function (data) {
            $.each(data, function (i, obj)
                {

                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
   
                });
                $('#subgroup_id').append(div_data);
            }
        });
    });
  

</script>




<script>
  $(function(){
    var not_sortable_items;
    not_sortable_items = [($('.table-invoice-items').find('th').length - 1)];
    initDataTable('.table-invoice-items', admin_url+'invoice_items/table', not_sortable_items, not_sortable_items,'undefined',[0,'ASC']);
    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#groups').modal('show'); 
		 
       },1000);
    }
	if(get_url_param('sub_groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#subgroups').modal('show');
       },1000);
    }

    $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
          $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
           window.location.href = admin_url+'invoice_items?groups_modal=true';
         });
      }
    });
	$('#new-item-sub-group-insert').on('click',function(){
      var sub_group_name = $('#sub_group_name').val(); 
	  var group_id = $('#get_group_id').val();
      if(sub_group_name != ''){
          $.post(admin_url+'invoice_items/add_sub_group',{name:sub_group_name,group_id:group_id}).done(function(){
           window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
         });
      } 
    });

    $('body').on('click','.edit-item-group',function(){
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });
	
  $('body').on('click','.edit-sub-item-group',function(){
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.sub_group_name_plain_text').toggleClass('hide');
      tr.find('.sub_group_edit').toggleClass('hide');
      tr.find('.sub_group_edit input').val(tr.find('.sub_group_name_plain_text').text());
    });

    $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
	$('body').on('click','.sub-update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-sub-group-row-id');
      name = tr.find('.sub_group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_sub_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
  });
</script>


</body>
</html>
