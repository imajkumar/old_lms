<?php init_head(); ?>
<div id="wrapper">
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <h2>Leads</h2>
                    <ol class="breadcrumb">
                       <ul class="breadcrumb"><li><a href="/LiveSales/livecrm/web/index.php">Home</a></li>
<li class="active">Leads</li>
</ul>                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			<div class="panel panel-info">
    <div class="panel-heading">    <div class="pull-right">
        <div class="summary">Showing <b>1-9</b> of <b>9</b> items.</div>
    </div>
    <h3 class="panel-title">
        </h3><h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Leads </h3>
    
    <div class="clearfix"></div></div>
    <div class="kv-panel-before">    <div class="pull-right">
        <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
            
<div class="btn-group"><a id="w0-togdata-page" class="btn btn-default" href="=all" title="Show all data" data-pjax="true"><i class="glyphicon glyphicon-resize-full"></i> All</a></div>

        </div>    
    </div>
    <form action="" method="post" name="frm">
										<input type="hidden" name="_csrf" value="">
           <input type="hidden" name="multiple_del" value="true"><a class="btn btn-success  btn-sm" href="<?php echo base_url('admin/leads/lead_add'); ?>"></i> Add</a> 
    <div class="clearfix"></div></form></div>
	
	
	 <div class="table-responsive" data-pattern="priority-columns">
    <table class="table table-striped table-bordered dataTable" id="xin_table1" style="width:100%;">
  <thead>
        <tr>
         <th style="width:150px;"><?php echo 'Lead Title';?></th>
              <th><?php echo 'Lead Status';?></th>
			  
              <th><?php echo 'Lead Type';?></th>
			  <th><?php echo 'Date';?></th>
              <th><?php echo 'First Name';?></th>
              <th><?php echo 'Last Name';?></th>
              <th><?php echo 'Email';?></th>
              <th><?php echo 'Mobile';?></th>
              <th><?php echo 'Owner';?></th>
              
              <th><?php echo 'Action';?></th>
        </tr>
      </thead>


<tbody>
	<?php 
		foreach($result as $data_leads) {

	?>
	
	<tr>
		<td><?php echo $data_leads->lead_title; ?></td>
		<td><?php echo $this->leads_model->get_status_byid($data_leads->lead_status); ?></td>
		<td><?php 
		if($data->lead_type == 0){
			echo $this->leads_model->get_lead_type_byid($data_leads->lead_type);
			
		}
		else{
		echo $this->leads_model->get_lead_type_byid($data_leads->lead_type);
	
		}?></td>
		<td><?php echo $data_leads->lead_date; ?></td>
		<td><?php echo $data_leads->first_name; ?></td>
		<td><?php echo $data_leads->last_name; ?></td>
		<td><?php echo $data_leads->email; ?></td>
		<td><?php echo $data_leads->mobile; ?></td>
		<td>
			<?php 
				   $user = $this->leads_model->read_user_info($data_leads->lead_owner);
   				  if(!is_null($user)){
					  $fname = $user[0]->first_name.' '.$user[0]->last_name;
					 
				  } else {
					 $fname = '--';   
				  }
				  echo $fname;
			?>
		</td>
		
		<td class="sorting_1">
			<span data-toggle="tooltip" data-placement="top" title="" data-original-title="View Details">
				<a href="<?php echo base_url().'admin/leads/manage/'.$data_leads->leadid;?>">
					<button type="button" class="btn btn-secondary btn-sm m-b-0-0 waves-effect waves-light">
					<i class="fa fa-arrow-circle-right"></i></button></a>
			</span>
			
		</td>
	</tr>

	<?php } ?>

</tbody>








</table></div>
	
	
	
	
  


   <div class="kv-panel-after"><a class="btn btn-info  btn-sm" href="/LiveSales/livecrm/web/index.php?r=sales%2Flead%2Findex"><i class="glyphicon glyphicon-repeat"></i> Reset List</a></div>
    <div class="panel-footer">    <div class="kv-panel-pager">
        
    </div>
    
    <div class="clearfix"></div></div>
</div>
    <div class="clearfix"></div></div>
<?php init_tail(); ?>
<script>
   $(function() {

       $('select[name="role"]').on('change', function() {
           var roleid = $(this).val();
           init_roles_permissions(roleid, true);
       });

       $('input[name="administrator"]').on('change', function() {
           var checked = $(this).prop('checked');
           var isNotStaffMember = $('.is-not-staff');
           if (checked == true) {
               isNotStaffMember.addClass('hide');
               $('.roles').find('input').prop('disabled', true).prop('checked', false);
           } else {
               isNotStaffMember.removeClass('hide');
               isNotStaffMember.find('input').prop('checked', false);
               $('.roles').find('input').prop('disabled', false);
           }
       });

       $('#is_not_staff').on('change', function() {
           var checked = $(this).prop('checked');
           var row_permission_leads = $('tr[data-name="leads"]');
           if (checked == true) {
               row_permission_leads.addClass('hide');
               row_permission_leads.find('input').prop('checked', false);
           } else {
               row_permission_leads.removeClass('hide');
           }
       });

       init_roles_permissions();

       _validate_form($('.staff-form'), {
           firstname: 'required',
           lastname: 'required',
           username: 'required',
           password: {
               required: {
                   depends: function(element) {
                       return ($('input[name="isedit"]').length == 0) ? true : false
                   }
               }
           },
           email: {
               required: true,
               email: true,
               remote: {
                   url: site_url + "admin/misc/staff_email_exists",
                   type: 'post',
                   data: {
                       email: function() {
                           return $('input[name="email"]').val();
                       },
                       memberid: function() {
                           return $('input[name="memberid"]').val();
                       }
                   }
               }
           }
       });
   });

</script>
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
       
          $(document).on('change', '#state_id', function (e) {
            $('#city_id').html("");
			
            var state_id = $(this).val();
			
			var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo 'select'; ?></option>';
			
            $.ajax({
                type: "GET",
                url: base_url + "admin/leads/getBycity",
                data: {'state_id': state_id},
                dataType: "json",
                success: function (data) {
				
                    $.each(data, function (i, obj)
                    {
						
                        div_data += "<option value=" + obj.city + ">" + obj.city + "</option>";
                    });
                    $('#city_id').append(div_data);
                }
            });
        });
       
   
  
</script>
</body>
</html>
