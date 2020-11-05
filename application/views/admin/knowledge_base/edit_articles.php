<?php init_head(); ?>
<div id="wrapper">
<div class="modal-content">
  <div class="modal-header">
                
                <h4 class="modal-title" id="myModalLabel">
               
                    <span class="add-title">Edit Knowledge base</span>
                </h4>
            </div>
			<div class="row">
			<div class="col-md-3"></div> <div class="col-md-6">
            <form action="<?php echo base_url('admin/knowledge_base/update_record/'.$get_knowledge['articleid']) ?>" method="post">
            
<div class="panel-body">
            <div class="modal-body">
              <div class="form-group">
						
						    <label for="title" class="control-label"> 
						    <small class="req text-danger">* </small>Title</label>
			 <input type="text"  name="title" class="form-control" value="<?php echo $get_knowledge['subject']; ?>">
						</div> 
						<div class="form-group" app-field-wrapper="long_description">
						<label for="long_description" class="control-label">Document</label>
						<input type="file" id="profile_image" name="profile_image" class="form-control" value="<?php echo $get_knowledge['image']; ?>">
						</div>
						<div class="form-group">
						<p class="bold">Knowledge Base description</p>
                         <?php $contents = ''; if(isset($get_knowledge['description'] )){$contents = $get_knowledge['description'];} ?>
 <?php echo render_textarea('description','',$contents,array(),array(),'','tinymce'); ?>
                        </div></div>
                                              
                       
                   
               
				
  
  </div>     
   <div class="modal-footer">
        <button type="button" class="btn btn-default hide">Close</button>
        <button type="submit" class="btn btn-info">Save</button>
            </div></div>
			
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