<?php init_head(); ?>
<div id="wrapper">
<div class="modal-content">
   
     
            <div class="modal-header">
               
                <h4 class="modal-title" id="myModalLabel">
                
                    <span class="add-title">Edit New Item</span>
                </h4>
            </div>
            <form action="<?php echo base_url('admin/invoice_items/update_record/'.$get_items['id']) ?>" method="post" >
            

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       
											<div class="col-md-6">	   
                        <div class="form-group">
						
						    <label for="description" class="control-label"> 
						    <small class="req text-danger">* </small>Cat. Ref.</label>
			 <input type="text"  name="description" class="form-control" value="<?php echo $get_items['description']; ?>">
						</div> 
						<div class="form-group" app-field-wrapper="long_description">
						<label for="long_description" class="control-label">Long Description</label><textarea id="long_description" name="long_description" class="form-control" rows="4"><?php echo $get_items['long_description']; ?></textarea>
						</div>
						<div class="form-group">
                        <label for="rate" class="control-label"> <small class="req text-danger">* </small>
                            Rate - INR <small>(Base Currency)</small></label>
                            <input type="number" id="rate" name="rate" class="form-control" value="<?php echo $get_items['rate']; ?>">
                        </div></div>
                                              
                       
                   
               
				<div class="col-md-6">
                
				
				<div class="form-group" app-field-wrapper="itme_code">
				<label for="itme_code" class="control-label">Item code</label>
				<input type="text" id="itme_code" name="itme_code" class="form-control" value="<?php echo $get_items['itme_code']; ?>">
				</div>               

				<div class="form-group" app-field-wrapper="unit"><label for="unit" class="control-label">Unit</label><input type="text" id="unit" name="unit" class="form-control" value="<?php echo $get_items['unit']; ?>">
				</div> 
				
              <div class="form-group" app-field-wrapper="itme_code"><label for="itme_code" class="control-label">Item Group</label>
			  <select class="form-control" name="item_group" id="group_id">
			  
			  <option>Select Group</option>			 

<?php 
foreach($items_groups as $items)
{

?>
			  <option <?php if($get_items['group_id'] == $items['id']){ echo 'selected'; } ?>  value="<?php echo $items['id']; ?>"><?php echo $items['name']; ?></option>
			  
			  <?php 
			  
}
?>
			  
			  </select>
			

			</div>
			  <div class="form-group" app-field-wrapper="itme_code"><label for="itme_code" class="control-label">Wattage</label>
			 <?php 
						    $this->db->select()->from('tblitems_sub_groups');
							$this->db->group_by('name');
							$this->db->where('group_id', $get_items['group_id']);
							$query = $this->db->get();
							$all_city = $query->result_array();
		
							?>
					 
                       
                        <select  id="subgroup_id" class="form-control" name="subgroup_id">
                           <option value="">Select Wattage</option>
						   <?php

							foreach($all_city as $city) {?>
                           <option <?php if($get_items['subgroup_id'] == $city['id']){ echo 'selected'; } ?>  value="<?php echo $city['id'] ; ?>"> <?php echo $city['name'];?></option>
						  
<?php } ?>
						   
			 </select>
			  </div>
               				
				
				 
            </div></div>
  </div>     
   <div class="modal-footer">
        <button type="button" class="btn btn-default hide">Close</button>
        <button type="submit" class="btn btn-info">Save</button>
            </div>
			
			</form>


  
</div>
<?php init_tail(); ?>

 <script type="text/javascript">  

$('#my_l_searcht').change(function(){
 
	   $("#lead_customer_type_id").prop("disabled",true);
   
});
   

	
</script>  





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


</body>
</html>