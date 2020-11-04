<script>
  $(document).on('change', '#group_id', function (e) {
        $('#subgroup_id').html("");
        var group_id = $(this).val();
  alert(group_id);
		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select Wattage'; ?></option>';
   
        $.ajax({
            type: "POST",
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



<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
           <?php do_action('before_items_page_content'); ?>
           <?php if(has_permission('items','','create')){ ?>
           <div class="_buttons">
            <a href="#" class="btn btn-default pull-left" data-toggle="modal" data-target="#sales_item_modal"><?php echo _l('new_invoice_item'); ?></a>
            <a href="#" class="btn btn-default pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo _l('item_groups'); ?></a>
			<a href="#" style="margin-left:10px" class="btn btn-default pull-left mleft6" data-toggle="modal" data-target="#subgroups"><?php echo 'Wattage'; ?></a>
			
			<a href="#" style="margin-left:10px" class="btn btn-default pull-left mleft6" data-toggle="modal" data-target="#bulkgroups"><?php echo 'Import Group'; ?></a>
			
			
		  </div>
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
          <?php } ?>
          <?php
          $table_data = array(
		    _l('Item Code'),
            _l('Cat. Ref.'),
            _l('invoice_item_long_description'),
            _l('invoice_items_list_rate'),
            _l('unit'),
            _l('item_group_name'),
			'Wattage',
			'Status');
            $cf = get_custom_fields('items');
            foreach($cf as $custom_field) {
                array_push($table_data,$custom_field['name']);
            }
            array_push($table_data,_l('options'));
            render_datatable($table_data,'invoice-items'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('admin/invoice_items/item'); ?>


<div class="modal fade" id="bulkgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
       <div class="col-md-4 mtop15">
                  <form action="<?php echo base_url(); ?>admin/invoice_items/import" id="import_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                  <div class="form-group" app-field-wrapper="file_csv">
					  <label for="file_csv" class="control-label"> 
					  <small class="req text-danger">* </small>Choose CSV File</label>
					  <input type="file" id="file_csv" name="file_csv" class="form-control" value="">
				  </div>                 
				   <div class="form-group">
                    <button type="submit" class="btn btn-info import btn-import-submit">Import</button>
                    
                  </div>
                  </form>
                </div>
        <hr />
        <?php } ?>
        
	</div>
  



  <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
        <div class="input-group">
          <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
          <span class="input-group-btn">
            <button class="btn btn-info p9" type="button" id="new-item-group-insert"><?php echo _l('new_item_group'); ?></button>
          </span>
        </div>
        <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('item_group_name'); ?></th>
                <th><?php echo 'Status'; ?></th>
                <th><?php echo _l('options'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_groups as $group){ ?>
              <tr data-group-row-id="<?php echo $group['id']; ?>">
                <td data-order="<?php echo $group['name']; ?>">
                  <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                  <div class="group_edit hide">
                   <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-info p7 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                    </span>
                  </div>
                </div>
              </td>
             <td data-order="<?php echo $group['group_status']; ?>">
                  <span class="group_name_plain_text"><?php 
					if($group['group_status'] > 0){
						echo 'Inactive';
					}else{
						echo 'Active';
					}
				  
				  ?></span>
                  <div class="group_edit hide">
                   <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-info p7 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                    </span>
                  </div>
                </div>
              </td>
             
              <td align="right">
                <?php if(has_permission('items','','edit')){ ?><button type="button" class="btn btn-default btn-icon edit-item-group"><i class="fa fa-pencil-square-o"></i></button><?php } ?>
                <?php if(has_permission('items','','delete')){ ?><a href="<?php echo admin_url('invoice_items/inactive_group/'.$group['id']); ?>" class="btn btn-danger btn-icon delete-item-group _delete"><i class="fa fa-ban"></i></a><?php } ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  



  <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    </div>
  </div>
</div>
</div>



<div class="modal fade" id="subgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo 'Wattage'; ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
		
		
		<?php echo render_select('get_group_id',$items_groups,array('id','name'),'item_group'); ?>
        <div class="input-group">
		Wattage
          <input type="text" name="sub_group_name" id="sub_group_name" class="form-control" placeholder="<?php echo 'wattage Name'; ?>">
		
          <span class="input-group-btn">
            <button class="btn btn-info p9" style="margin: 17px 0px 0px 0px;" type="button" id="new-item-sub-group-insert"><?php echo 'New Wattage'; ?></button>
          </span>
        </div>
        <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
               
				<th><?php echo 'Wattage Name'; ?></th>
				<th><?php echo 'Group Name'; ?></th>
				<th><?php echo 'Status'; ?></th>
                <th><?php echo _l('options'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($get_sub_groups as $group){ ?>
              <tr data-sub-group-row-id="<?php echo $group['id']; ?>">
			  
                <td data-order="<?php echo $group['name']; ?>">
                  <span class="sub_group_name_plain_text"><?php echo $group['name']; ?></span>
                  <div class="sub_group_edit hide">
                   <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-info p7 sub-update-item-group" type="button"><?php echo _l('submit'); ?></button>
                    </span>
                  </div>
                </div>
              </td>
			  <td data-order="<?php echo $group['group_id']; ?>">
                  <span class="sub_group_name_plain_text"><?php echo $group['group_name']; ?></span>
                  <div class="sub_group_edit hide">
                   <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-info p7 sub-update-item-group" type="button"><?php echo _l('submit'); ?></button>
                    </span>
                  </div>
                </div>
              </td>
              <td data-order="<?php echo $group['sub_group_status']; ?>">
                  <span class="sub_group_name_plain_text">
				  <?php 
				    if($group['sub_group_status'] > 0){
						echo 'Inactive';
					}else{
						echo  'Active';
					}
				 
				  ?>
				  </span>
                  <div class="sub_group_edit hide">
                   <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-info p7 sub-update-item-group" type="button"><?php echo _l('submit'); ?></button>
                    </span>
                  </div>
                </div>
              </td>
              <td align="right">
                <!--<?php if(has_permission('items','','edit')){ ?><button type="button" class="btn btn-default btn-icon edit-sub-item-group"><i class="fa fa-pencil-square-o"></i></button><?php } ?>
                -->
				<?php if(has_permission('items','','delete')){ ?><a href="<?php echo admin_url('invoice_items/inactive_sub_group/'.$group['id']); ?>" class="btn btn-danger btn-icon delete-item-group _delete"><i class="fa fa-ban"></i></a><?php } ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  



  <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    </div>
  </div>
</div>
</div>





<?php init_tail(); ?>




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
