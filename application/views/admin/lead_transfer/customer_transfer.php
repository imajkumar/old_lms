<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
          <div class="panel_s">
               <div class="panel-body">
			    <div class="col-md-12">
				  <div class="col-md-6">
						<h4>Customer Transfer</h4>
				   </div>
				   <div class="col-md-6">
						<a href="<?php echo base_url(); ?>/admin/Lead_tansfer" class="btn btn-info pull-right display-block" >Lead Transfer</a>
				   </div>
				 </div>
                  <div class="_buttons">
				  <hr class="hr-panel-heading" />
                  <div class="tab-content">
                    
                     <div class="row" id="leads-table">
                        <div class="col-md-12">
						
                           <div class="row">
                              <div class="col-md-12">
                                 <p class="bold"><?php echo _l('Get Staff Customer'); ?></p>
                              </div>
							
							<form action="<?php echo base_url('admin/Customer_transfer/'.$posts->assigned); ?>" class="region-form"  method="post"> 
                              <div class="col-md-3 leads-filter-column">
							  
							  <select class="form-control selectpicker" name="lead_manager" data-width="100%" data-none-selected-text="Select Staff" data-live-search="true" id="lead_manager">
						    
                              <option value="">Select Staff</option>  
							 <?php foreach($staff as $staff_name) {
							 
								 ?>
								<option <?php if($select_staff == $staff_name['staffid']){ echo 'selected'; } ?> value="<?php echo $staff_name['staffid']; ?>"> <?php echo $staff_name['emp_code'].' - '.$staff_name['firstname'];?> <?php echo $staff_name['lastname'].' - '. $this->staff_model->get_role_data($staff_name['role']); ?></option>
                              <?php } ?>
							    </select>
							  
                              </div>
                            <div class="col-md-2 leads-filter-column">
							   <div class="_buttons">
				  
								  <input type="submit" value="<?php echo 'Fetch Customer'; ?>" class="btn mright5 btn-info pull-left display-block" class="checkbox mass_select_all_wrap">
								</div>    
                           </div>
						   
							</form>
						   
						    <form action="<?php echo base_url('admin/Customer_transfer/lead_transfer_done'); ?>" class="region-form"  method="post"> 
						   <div class="col-md-2 leads-filter-column">
							   <div class="_buttons">
									Select Transfer To
								</div>    
                           </div>
						   <div class="col-md-3 leads-filter-column">
							  
							<select class="form-control selectpicker" data-width="100%" data-none-selected-text="Transfer To" data-live-search="true" name="lead_managerto" id="lead_manager_to">
						    
                              <option value="">Select Staff</option>  
							 <?php foreach($staff as $staff_name) { ?>
                              <option value="<?php echo $staff_name['staffid']; ?>" <?php if($staff_name['staffid'] ==$posts->assigned){ echo 'selected'; } ?>> <?php echo $staff_name['emp_code'].' - '.$staff_name['firstname'];?> <?php echo $staff_name['lastname'].' - '. $this->staff_model->get_role_data($staff_name['role']);?></option>
                              <?php } ?>
							</select>
							  
                            </div>
                            
						   
						   
                           </div>
						
						   
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="col-md-12">
                       <?php if(isset($list_leads_data)) { ?>    
                      <table class="table" id="example" role="grid">
					  
					  
					  
					  <thead>
						<tr role="row">
						  <th class="sorting_disabled  text-center" rowspan="1" colspan="1" aria-label="#">
							<input type="checkbox" class="checkbox mass_select_all_wrap" id="ckbCheckAll">
						  </th>
						  <th class="sorting_disabled  text-center" rowspan="1" colspan="1" aria-label="#">Customer ID</th>
						  <th class="sorting_disabled text-center" rowspan="1" colspan="1" aria-label="#">Created Date</th>					  
						  <th class="sorting_disabled text-center" rowspan="1" colspan="1" aria-label="#">Customer</th>
						  <th class="sorting_disabled text-center" rowspan="1" colspan="1" aria-label="#">Contact No.</th>
						  <th class="sorting_disabled text-center" rowspan="1" colspan="1" aria-label="#">Transfer To</th>
						 
						</tr>
					  </thead>
					  
						 <tbody>
						 <?php 
						$i=0;
						 foreach($list_leads_data as $transfer)
						 
						 {
							 ?>
						 <tr>
						 <td><input class="checkboxall"  name="lead_id[]"  type="checkbox" value="<?php echo $transfer['userid']; ?>"></td> 
						 <td><?php echo $transfer['userid']; ?></td> 
						 <td><?php echo $transfer['datetime']; ?></td> 
						
						 <td><?php echo $transfer['company']; ?></td>
						 <td><?php echo $transfer['phonenumber']; ?></td>
						 <td>
							<select class="form-control col-md-3" name="lead_manager_to[]" id="leadmanagerto_<?php echo $i; ?>" >
						    
                              <option value="">Select</option>  
							 <?php foreach($staff as $staff_name) { ?>
                              <option value="<?php echo $staff_name['staffid']; ?>" <?php if($staff_name['staffid'] ==$posts->assigned){ echo 'selected'; } ?>> <?php echo $staff_name['emp_code'].' - '.$staff_name['firstname'];?> <?php echo $staff_name['lastname'].' - '. $this->staff_model->get_role_data($staff_name['role']);?></option>
                              <?php } ?>
							    </select>
						 </td>
						  <td></td>
						 </tr>
						  <?php $i++; } ?>
						 
						 </tbody>
				
						 
						 </table>
						 <div class="col-md-2 leads-filter-column">
							   <div class="_buttons">
				  
								  <input type="submit" value="<?php echo 'Transfer Customer'; ?>" class="btn mright5 btn-info pull-left display-block" class="checkbox mass_select_all_wrap">
								</div>    
                           </div>
					   <?php } ?>
                        </div>
                     </div>
                   </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   
  

</div>
<?php init_tail(); ?>

<script>
$(document).ready(function() {
   $("#ckbCheckAll").click(function () {
    $(".checkboxall").prop('checked', $(this).prop('checked'));
});

} );
$(document).ready(function() {
   var table = $('#example').DataTable({
      'ajax': 'https://api.myjson.com/bins/1us28',
      'columnDefs': [
         {
            'targets': '',
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
      'order': [[1, 'asc']]
   });
});
$('.leadmanagerto').each(function () {
		var tranto = $('.leadmanagerto').val();
		
		console.log(tranto);
	
	});
	 


 $(document).ready(function()
 {
       $("#lead_manager_to").change(function()
       {
		   var trans = $(this).val();
		   var i=0;
		  $('select[id^="leadmanagerto"]').each(function () {
				var tranto = this.id;
				$("#leadmanagerto_"+i).val(trans);
				
				/* if( tranto == trans)
				 {
					$('.leadmanagerto').attr("selected","selected");
				  } */
				i = i + 1;
			});
			

       });
     });
	 
</script>
</body>
</html>
