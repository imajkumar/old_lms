
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
	<form method="post" action="<?php echo base_url('admin/lead_requirment/edit_lead_requirement_data/'.$leads_list['id']) ?>">
	<div class="panel_s accounting-template estimate">
		<div class="panel-body">
			<div class="row">
			 <div class="col-md-6">
				<div class="f_client_id">
				 <div class="form-group select-placeholder">
					<label for="clientid" class="control-label"><?php echo "Select Lead"; ?></label>
					<select name="client_id" data-live-search="true" data-width="100%" class="selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
						<option value="">Select Lead</option>
							<?php foreach($Leads as $_items){ ?>
					
							
							
							  <option value="<?php echo $_items['id']; ?>"<?php
                                        if (set_value('client_id', $_items['id']) == $leads_list['lead_id']) {
                                            echo "selected =selected";
                                        }
                                        ?>><?php echo $_items['company']; ?></option>
						   <?php } ?>
					</select>
				  </div>
				</div>
				
			 </div>
			  <div class="col-sm-4">
                        <div class="form-group field-lead-lead_owner_id hidden">
                           <label class="control-label" for="lead-lead_owner_id">Lead Owner</label>
                           <select id="lead-lead_owner_id" class="form-control" name="lead_owner">
                              <option value="<?php echo $leads_list['lead_owner']; ?>"> <?php echo $this->session->staff_user_id; ?></option>
                           </select>
                           <div class="help-block"></div>
                        </div></div>
			 <div class="col-md-6 col-sm-4">
                        <div class="form-group field-lead-lead_owner_id">
                           <label class="control-label" for="lead-lead_owner_id">Lead Remark</label>
						   <textarea class="form-control" name="remark" id="remark"><?php echo $leads_list['remark']; ?></textarea>
                           
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-md-6 col-sm-4">
                        <div class="form-group field-lead-lead_owner_id">
                        <label class="control-label" for="lead-lead_owner_id">Title</label>
                                                                       
                                                    <input type="text" name='first_title' class="form-control" placeholder="" value="<?php echo $leads_list['title1']; ?>"> <br>   
													<input type="text" name='second_title' value="<?php echo $leads_list['title2']; ?>" class="form-control" placeholder="">  <br> 
													<input type="text" name='third_title' value="<?php echo $leads_list['title3']; ?>" class="form-control" placeholder=""><br>  
													<input type="text" name='fourth_title' value="<?php echo $leads_list['title4']; ?>" class="form-control" placeholder=""> <br> 
													<input type="text" name='fifth_title' value="<?php echo $leads_list['title5']; ?>" class="form-control" placeholder="">                        
                                                                                   
                                                                               
                                                                           
                           
                           <div class="help-block"></div>
                        </div>
                     </div>
					 <div class="col-md-6 col-sm-4">
                        <div class="form-group field-lead-lead_owner_id">
                        <label class="control-label" for="lead-lead_owner_id">Document</label>
                                                                       
                                                   <input class="filestyle form-control" type='file' name='first_doc' id="doc1" > <br> 
												   <input class="filestyle form-control" type='file' name='second_doc' id="doc1" > <br>
												   <input class="filestyle form-control" type='file' name='third_doc' id="doc1" > <br> 
												   <input class="filestyle form-control" type='file' name='fourth_doc' id="doc1" >  <br>
												   <input class="filestyle form-control" type='file' name='fifth_doc' id="doc1" >                  
                                                     
                           <div class="help-block"></div>
                        </div>
                     </div>
					</div>
				
		
	   
			<div class="row">
				<div class="table-responsive s_table">
				
					<table class="table estimate-items-table items table-main-estimate-edit">
					 <thead>
					   <?php if(has_permission('estimates','','create')){ ?>
					 <tr>
						   <th></th>
						   <th  align="left"><?php echo 'Category'; ?></th>
						   <th align="left"><?php echo 'Item'; ?></th>
						   <th  class="left" align="right"><?php echo 'Qty'; ?></th>
						   <th align="left"><?php echo 'Required Price'; ?></th>
						   <th  align="left"><?php echo 'Document Required '; ?></th>
						   <th align="left"><?php echo 'Proposed Item'; ?></th>
						   <th  align="left"><?php echo 'Proposed Price'; ?></th>
						   <th align="center"><i class="fa fa-cog"></i></th>
						</tr>
						<?php 
					   }
					   else{
						   ?>
						   <tr>
						   <th></th>
						   <th  align="left"><?php echo 'Category'; ?></th>
						   <th align="left"><?php echo 'Item'; ?></th>
						   <th  class="left" align="right"><?php echo 'Qty'; ?></th>
						   <th align="left"><?php echo 'Required Price'; ?></th>
						   <th  align="left"><?php echo 'Document Required '; ?></th>
						  
						   <th align="center"><i class="fa fa-cog"></i></th>
						</tr> 
						   
						   
						   
						   
					  <?php
					  }
					   ?>
						
						 <?php if(has_permission('estimates','','create')){ ?>
						
						<tr>
						   <td>
						   </td>
						   <td>
								<select class="form-control" id="item_cat">
									<option value="">Select Category</option>
										<?php foreach($items_groups as $_items){ ?>
										  <option value="<?php echo $_items['id']; ?>"><?php echo $_items['name']; ?></option>
									   <?php } ?>
								</select>
						   </td>
						   <td>
								<select class="form-control" id="item">
									<option value="">--Select Item--</option>
										
								</select>
						   </td>
						   <td>
							  <input type="number" min="0" value="1" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
							 
						   </td>
						   <td>
							  <input type="number" id="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						   <td>
								<select class="form-control" id="document">
									<option value="">Select Document</option>
									<option value="TDS">TDS</option>
									<option value="LM79">LM79</option>
									<option value="LM80">LM80</option>
								</select>                     
						   </td>
						   <td>
								<select id="proposed_item" class="form-control" data-show-subtext="true" data-live-search="true">
								   <option value=""></option>
								   <?php foreach($items as $group_id=>$_items){ ?>
									 <?php foreach($_items as $item){ ?>
									  <option value="<?php echo $item['id']; ?>"><?php echo $item['description']; ?> - <?php echo $_items[0]['group_name']; ?></option>
									  <?php } ?>
								   
								   <?php } ?>
								</select>
						   </td>
						   <td>
							  <input type="number" id="proposed_rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						  
						   <td>
							
							<a href="javascript:void(0);"  class="add-row btn btn-success create-new-field">
								<i class="fa fa-plus"></i>
								<span class="hidden-xs"> ADD </span>
							</a>
						   </td>
						</tr>
						
						<?php 
						 }

else{
	?>
	
	

	
	<tr>
						   <td>
						   </td>
						   <td>
								<select class="form-control" id="item_cat">
									<option value="">Select Category</option>
										<?php foreach($items_groups as $_items){ ?>
										  <option value="<?php echo $_items['id']; ?>"><?php echo $_items['name']; ?></option>
									   <?php } ?>
								</select>
						   </td>
						   <td>
								<select class="form-control" id="item">
									<option value="">--Select Item--</option>
										
								</select>
						   </td>
						   <td>
							  <input type="number" min="0" value="1" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
							 
						   </td>
						   <td>
							  <input type="number" id="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						   <td>
								<select class="form-control" id="document">
									<option value="">Select Document</option>
									<option value="TDS">TDS</option>
									<option value="LM79">LM79</option>
									<option value="LM80">LM80</option>
								</select>                     
						   </td>
						   
						  
						   <td>
							
							<a href="javascript:void(0);"  class="add-row btn btn-success create-new-field">
								<i class="fa fa-plus"></i>
								<span class="hidden-xs"> ADD </span>
							</a>
						   </td>
						</tr>
						
	
	<?php
}

						 
						 ?>
					



					<?php 
					 
					 foreach($leads_data as $leads_edit_data)
					 {
						if(has_permission('estimates','','create')){ ?> 
						 
				
					 
					 
					 <tr class='main'>
					 
					  <td>
						   </td>
					 <td>
					 
					 <select class="form-control" id="item_cat" name='item_cat[]'>
									<option value="">Select Category</option>
									<?php foreach($items_groups as $_items){ ?>
										
										   <option value="<?php echo $_items['id']; ?>"<?php
                                        if (set_value('item_cat', $_items['id']) == $leads_edit_data['category_id']) {
                                            echo "selected =selected";
                                        }
                                        ?>><?php echo $_items['name']; ?></option>
						   <?php } ?>
								
								</select></td>
								<td>
								
								
								<select class="form-control" id="item">
									<option value="<?php echo $_items['id']; ?>"><?php echo $leads_edit_data['description']; ?></option>
										
								</select>
						   </td>
								 <td>
							  <input type="number" min="0" value="<?php echo $leads_edit_data['quantity']; ?>" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
							 
						   </td>
						   
						    <td>
							  <input type="number" id="rate"  value="<?php echo $leads_data[0]['rate']; ?>" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						    <td>
								<select class="form-control" id="document">
									<option value="">Select Document</option>
	<option <?php if ($leads_data[0]['document'] == 'TDS' ) echo 'selected' ; ?> value="TDS">TDS</option>
	<option <?php if ($leads_data[0]['document'] == 'LM79' ) echo 'selected' ; ?> value="LM79">LM79</option>
	<option <?php if ($leads_data[0]['document'] == 'LM80' ) echo 'selected' ; ?> value="LM80">LM80</option>
	
								</select>                     
						   </td>
						   <td>
								<select id="proposed_item" class="form-control" data-show-subtext="true" data-live-search="true">
								   <option value=""></option>
								   <?php foreach($items as $group_id=>$_items){ ?>
									 <?php foreach($_items as $item){ ?>
									  <option value="<?php echo $item['id']; ?>"<?php
                                        if (set_value('proposed_item', $item['id']) == $leads_edit_data['proposed_item_id']) {
                                            echo "selected =selected";
                                        }
                                        ?>><?php echo $item['description']; ?> - <?php echo $item[0]['group_name']; ?></option>
						 
									  
									  
									  <?php } ?>
								   
								   <?php } ?>
								</select>
						   </td>
						   <td>
							  <input type="number" id="proposed_rate" value="<?php echo $leads_data[0]['proposed_item_qty']; ?>" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						   
						   
						  
						   
					</tr> 




					<?php } 
					
					 else{?>
						 
						 <tr class='main'>
					 
					  <td>
						   </td>
					 <td>
					 
					 <select class="form-control" id="item_cat" name='item_cat[]'>
									<option value="">Select Category</option>
									<?php foreach($items_groups as $_items){ ?>
										
										   <option value="<?php echo $_items['id']; ?>"<?php
                                        if (set_value('item_cat', $_items['id']) == $leads_edit_data['category_id']) {
                                            echo "selected =selected";
                                        }
                                        ?>><?php echo $_items['name']; ?></option>
						   <?php } ?>
								
								</select></td>
								<td>
								
								
								<select class="form-control" id="item">
									<option value="<?php echo $_items['id']; ?>"><?php echo $leads_edit_data['description']; ?></option>
										
								</select>
						   </td>
								 <td>
							  <input type="number" min="0" value="<?php echo $leads_edit_data['quantity']; ?>" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
							 
						   </td>
						   
						    <td>
							  <input type="number" id="rate"  value="<?php echo $leads_data[0]['rate']; ?>" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						    <td>
								<select class="form-control" id="document">
									<option value="">Select Document</option>
	<option <?php if ($leads_data[0]['document'] == 'TDS' ) echo 'selected' ; ?> value="TDS">TDS</option>
	<option <?php if ($leads_data[0]['document'] == 'LM79' ) echo 'selected' ; ?> value="LM79">LM79</option>
	<option <?php if ($leads_data[0]['document'] == 'LM80' ) echo 'selected' ; ?> value="LM80">LM80</option>
	
								</select>                     
						   </td>
							   
					</tr> 


 
						 
						 
					<?php	 
					 }
					 }
					?>
					 </thead>
					



					<tbody>
						
					 </tbody>
				  </table>
				  <button type="button" class="delete-row">Delete Row</button>
			  </div>
	   
			</div>
			
			<div class="row">
      <div class="col-md-12">
         <div class="panel-body bottom-transaction">
           
            <div class="btn-bottom-toolbar text-right">
              
              <button type="submit" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
              </button>
            </div>
         </div>
           <div class="btn-bottom-pusher"></div>
      </div>
   </div>
		</div>
	</div>
	</form>
</div>
</div>
<?php init_tail(); ?>

    <script type="text/javascript">
    $(document).ready(function(){
        $(".add-row").click(function(){
            var item_cat_val = $('#item_cat').find('option:selected').val();
            var item_cat_text = $('#item_cat').find('option:selected').text();
            
			var item_val = $("#item :selected").val();
            var item_text = $("#item :selected").text();
			
            var quantity = $("#quantity").val();
            var rate = $("#rate").val();
            var document = $('#document').find('option:selected').text();
			
            var proposed_item_val = $('#proposed_item').find('option:selected').val();
            var proposed_item_text = $('#proposed_item').find('option:selected').text();
			
            var proposed_rate = $("#proposed_rate").val();
			<?php if(has_permission('estimates','','create')){ ?>
            var markup = "<tr class='main'><td><input type='checkbox' name='record'></td><td><span>"+item_cat_text+"</span><input type='hidden' name='item_cat[]' value="+ item_cat_val +"></td><td><span>"+item_text+"</span><input type='hidden' name='item[]' value="+ item_val +"></td><td><input type='text' name='quantity[]' value="+ quantity +"></td><td><input type='text' name='rate[]' value="+ rate +"></td><td><input type='text' name='document[]' value="+ document +"></td><td><span>"+proposed_item_text+"</span><input type='hidden' name='proposed_item[]' value="+ proposed_item_val +"></td><td><input type='text' name='proposed_rate[]' value="+ proposed_rate +"></td><td></td></tr>";
			<?php }
			else{
			
			?> 
			var markup = "<tr class='main'><td><input type='checkbox' name='record'></td><td><span>"+item_cat_text+"</span><input type='hidden' name='item_cat[]' value="+ item_cat_val +"></td><td><span>"+item_text+"</span><input type='hidden' name='item[]' value="+ item_val +"></td><td><input type='text' name='quantity[]' value="+ quantity +"></td><td><input type='text' name='rate[]' value="+ rate +"></td><td><input type='text' name='document[]' value="+ document +"></td><td></td></tr>";
			<?php }?>
			
				
				 $("table tbody").append(markup);
				
				
			
			
           
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });
    });    
 </script>
	<script>
	
		
		$(document).on('change', '#item_cat', function (e) {
        $('#item').html("");
        $('#proposed_item').html("");
        var item_cat = $(this).val();
   
   var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/lead_requirment/getItemByCatID",
            data: {'item_cat': item_cat},
            dataType: "json",
            success: function (data) {
   
   
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.description + "</option>";
   
                });
                $('#item').append(div_data);
                $('#proposed_item').append(div_data);
            }
        });
    });
   
	</script>
		
		
</body>
</html>
