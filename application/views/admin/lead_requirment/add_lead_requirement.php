

<?php init_head(); ?>
<style>
.main{
	background: #fbfdff;
}

.panel-body.bottom-transaction {
     background: #fff !important; 
}
.panel_s .panel-body {
	 border: 1px solid #fff !important;
}
</style>
<div id="wrapper">
	<div class="content">
	<form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/lead_requirment/add_lead_requirement_data/') ?>">
	<div class="panel_s accounting-template estimate">
		<div class="panel-body">
		<h4>Project Related Documents</h4>
			<div class="row hide">
			 <div class="col-md-6">
				<div class="f_client_id">
				 <div class="form-group select-placeholder">
					<label for="clientid" class="control-label"><?php echo "Select Lead"; ?></label>
					<input type="hidden" name="client_id"  value="<?php echo $_GET['id']; ?>">
					<?php
						if(isset($_GET['status'])){
							echo '<input type="hidden" name="lead_status"  value="'.$_GET["status"].'">';
						}
					?>
				  </div>
				</div>
				
			 </div>
					<div class="col-sm-4">
                        <div class="form-group field-lead-lead_owner_id hidden">
                           <label class="control-label" for="lead-lead_owner_id">Lead Owner</label>
                           <select id="lead-lead_owner_id"  class="form-control" name="lead_owner">
                              <option value="<?php echo $this->session->staff_user_id; ?>"> <?php echo $this->session->staff_user_id; ?></option>
                           </select>
                           <div class="help-block"></div>
                        </div>
					</div>
					<div class="col-md-6 col-sm-4">
                        <div class="form-group field-lead-lead_owner_id">
                           <label class="control-label" for="lead-lead_owner_id">Lead Remark</label>
						   <textarea class="form-control" name="remark" id="remark"></textarea>
                           
                           <div class="help-block"></div>
                        </div>
                     </div>
					
					</div>
			
			<div class="row around10">   
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>1.</td>
								<td><input type="text" name='first_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>2.</td>
								<td><input type="text" name='second_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='second_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>3.</td>
								<td><input type="text" name='third_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='third_doc' id="doc1" >
								</td>
							</tr>
						</tbody></table>
				</div>
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>4.</td>
								<td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fourth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>5.</td>
								<td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fifth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>6.</td>
								<td><h4>Document Required By</h4></td>
								<td width="100px">
									<?php 
										$this->db->where('id', $_GET['id']);
										$due_date = $this->db->get('tblleads')->row()->document_due_date;
									?>
									<input type="date" min="<?php echo date('Y-m-d'); ?>"  onkeydown="return false"  required value="<?php echo $due_date; ?>" name='document_due_date' class="form-control" placeholder="">
								</td>
							</tr>
							
							
						</tbody></table>
				</div>
			</div>
                                                        	
			<div class="row">
				
				<div class="table-responsive s_table">
				
					<table class="table estimate-items-table items table-main-estimate-edit tablep">
					 <thead>
					   
					 <tr>
						   <th></th>
						   <th style="width:170px" align="center"><?php echo 'Category *'; ?></th>
						   <th style="width:150px" align="center"><?php echo 'Wattage *'; ?></th>
						   <th style="width:170px" align="center"><?php echo 'Item *'; ?></th>
						   <th id="item_details" style="width:170px" align="center">
						   <?php echo 'Item Details'; ?></th>
						    <th style="width: 129px;" align="center"><?php echo 'Item Warranty *'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Qty *'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Req. Price *'; ?></th>
						   <th style="width:140px" align="center"><?php echo 'Document Required '; ?></th>
						   <?php
								if(is_admin())
								{
							?>
						   <th style="width:170px" align="center"><?php echo 'Proposed Item'; ?></th> 
						   <th style="width:170px" id="propsed_item_details" align="center"><?php echo 'Proposed Item Details'; ?></th>
						   <th style="width:80px" align="center"><?php echo 'Proposed Price'; ?></th>
						   <?php 
							   }
							  
						   ?>
						   <th align="center"><i class="fa fa-cog"></i></th>
						</tr>
						
						
						<tr>
						   <td style="width:50px">
						   </td>
						   <td style="width:190px">
								<select class="form-control selectpicker" data-live-search="true" id="item_cat">
									<option value="">Select Category</option>
										<?php foreach($items_groups as $_items){
											if($_items['group_status'] != 1){
											?>
										  <option value="<?php echo $_items['id']; ?>"><?php echo $_items['name']; ?></option>
										<?php }
										} ?>
									   
								</select>
						   </td> <td style="width:100px">
								<select class="form-control" id="sub_item_cat">
									<option value="">Select Wattage</option>
										
								</select>
						   </td>
						   <td style="width:320px">
								<select class="form-control" id="item">
									<option value="">Select Item</option>
										
								</select>
						   </td>
						   <td id="item_details_d"  style="width:150px">
							  <input type="text" class="form-control" name="item_description" id="item_description" placeholder="<?php echo _l('Item Details'); ?>">
							 
						   </td>
						   <td >
							<select class="form-control" id="warranty">
								<option>--Select Warranty--</option>
								<option>0 years</option>
								<option value="1">1 years</option>
								<option value="2">2 years</option>
								<option value="3">3 years</option>
								<option value="4">4 years</option>
								<option value="5">5 years</option>
								<option value="6">6 years</option>
								<option value="7">7 years</option>
								
							</select>
							 
						   </td>

						  <td style="width:80px">
							  <input style='width:90px;' type="number" min="0" value="1" class="form-control" id="quantity" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
							 
						   </td>
						   
						   <td style="width:100px">
							  <input type="number" style='width:90px;' id="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
						   <td style="width:190px">
								<select class="form-control selectpicker"  multiple data-live-search="false"  id="document">
									<option value="">Select Document</option>
									<?php foreach($get_groups_document as $document){ ?>
										  <option value="<?php echo $document['id']; ?>"><?php echo $document['name']; ?></option>
									   <?php } ?>
									
									
								</select>                     
						   </td>
					<?php
						if(is_admin())
						{
					?>
						   <td style="width:320px">
								<select id="proposed_item" class="form-control">
								   <option value=""></option>
								   <?php foreach($items as $group_id=>$_items){ ?>
									 <?php foreach($_items as $item){ ?>
									  <option value="<?php echo $item['id']; ?>"><?php echo $item['description']; ?> </option>
									  <?php } ?>
								   
								   <?php } ?>
								</select>
						   </td>
						   
						    <td id="item_details_du"  style="width:150px">
							  <input type="text" class="form-control" name="item_description_propsed"  id='item_description_propsed' placeholder="<?php echo 'Proposed Item Details'; ?>">
							 
						   </td>
						   
						   <td style="width:100px">
							  <input type="number" id="proposed_rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
						   </td>
					<?php 
						 }
						 
					?>
						   <td style="width:50px">
							
							<a href="javascript:void(0);"  class="add-row btn btn-success create-new-field">
								Add
								<span class="hidden-xs"></span>
							</a>
						   </td>
						</tr>
					   
					 </thead>
					 <tbody>
						
					 </tbody>
				  </table>
				  
				  <button type="button" class="delete-row">Delete Row</button>
			  </div>
			  <div class="table-responsive col-md-12">
					<table id="datarecord" class="table tabled">
					<tbody>
						
					 </tbody>
				  </table>
				</div>
				
			</div>
			
			<div class="row">
      <div class="col-md-12  text-right">
	  <button type="submit" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit" onclick="location.href='<?php echo base_url();?>admin/leads'">
              <?php echo 'Cancel'; ?>
              </button>
            <button type="submit" id="save_record" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
              </button>
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
		
		
		$("#item_details").hide();
		$("#item_details_d").hide();
		$("#propsed_item_details").hide();
		$("#item_details_du").hide();
		$("#save_record").hide();
		
		
        $(".add-row").click(function(){
			$("#save_record").show();
            var item_cat_val = $('#item_cat').find('option:selected').val();
            var item_cat_text = $('#item_cat').find('option:selected').text(); 
			var item_sub_cat_val = $('#sub_item_cat').find('option:selected').val();
            var item_sub_cat_text = $('#sub_item_cat').find('option:selected').text();
            
			var item_val = $("#item :selected").val();
            var item_text = $("#item :selected").text();
			
            var quantity = $("#quantity").val(); 
			var warranty = $('#warranty').find('option:selected').val();
            var item_description = $("#item_description").val(); 
			
			var item_description_propsed = $("#item_description_propsed").val();
			
			
            var rate = $("#rate").val();
            //var document = $('#document').val();
           // var document = $('#document').find('option:selected').text();
			var document = $('#document option:selected').toArray().map(item => item.text).join();

            var proposed_item_val = $('#proposed_item').find('option:selected').val();
            var proposed_item_text = $('#proposed_item').find('option:selected').text();
			
            var proposed_rate = $("#proposed_rate").val();
			
			
			
			<?php if(is_admin()){ ?>
            var markup = "<tr class='main'><td><input type='checkbox' name='record'></td><td style='width:180px'><span>"+item_cat_text+"</span><input  class='form-control' type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td><td style='width:180px'><span>"+item_sub_cat_text+"</span><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td><td style='width:180px'><span>"+item_text+"</span><input type='hidden' name='item[]' value="+ item_val +"></td><td style='width:280px;'><input type='text'  id='item_desc' name='item_description[]' value="+ item_description +"></td><td style='width:60px;'><span>"+warranty+"</span><input type='hidden' name='warranty[]' value="+ warranty +"></td><td><input style='width:80px;' type='number' name='quantity[]' value="+ quantity +"></td><td style='width:80px'><input type='number' style='width:80px;' name='rate[]' value="+ rate +"></td><td style='width:120px'><input type='text' name='document[]' value="+ document +"></td><td><span>"+proposed_item_text+"</span><input type='hidden' name='proposed_item[]' value="+ proposed_item_val +"></td><td style='width:180px;display:none;'><input type='text' name='item_description_propsed[]'  class='form-control' value="+ item_description_propsed +"></td><td style='width:80px'><input type='number' placeholder='Proposed Rate' class='form-control' name='proposed_rate[]' style='width:80px;' value="+ proposed_rate +"></td></tr>";
			<?php } else { ?>
			if(item_description == ''){
			var markup = "<tr class='main'>"+
					"<td><input type='checkbox' name='record'></td>"+
					"<td>Category<br> <b><span>"+item_cat_text+"</span></b><input type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td>"+
					"<td style='width:180px'>Wattage<br><b><span>"+item_sub_cat_text+"</span></b><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td>"+
					"<td>Item<br><b><span>"+item_text+"</span></b><input type='hidden' name='item[]' value="+ item_val +"><input id='item_desc' class='form-control' type='hidden' name='item_description[]' value='"+ item_description +"' /></td>"+
					"<td style='width:60px;'>Warranty<br><span>"+warranty+"</span><input  class='form-control' type='hidden' name='warranty[]' value="+ warranty +"></td>"+
					"<td>Quantity<br><b><span>"+ quantity +"</span></b><input  class='form-control' type='hidden' style='width:80px;' name='quantity[]' value="+ quantity +"></td>"+
					"<td>Rate<br><b><span>"+ rate +"</span></b><input class='form-control' type='hidden' name='rate[]' style='width:80px;' value="+ rate +"></td>"+
					"<td>Document<br><b><span>"+ document +"</span></b><input  class='form-control' type='hidden' name='document[]' value='"+ document +"'></td>"+
					"</tr>";
		}else{
			var markup = "<tr class='main'><td ><input type='checkbox' name='record'></td><td>Category<br><b><span>"+item_cat_text+"</span></b><input type='hidden' required name='item_cat[]' value="+ item_cat_val +"></td><td style='width:180px'>Wattage<br><b><span>"+item_sub_cat_text+"</span></b><input type='hidden' name='item_sub_cat[]' value="+ item_sub_cat_val +"></td><td>Item<br><b><span>"+item_text+"</span></b><input type='hidden' name='item[]' value="+ item_val +"></td><td id='itemdesc'>Description<br><b><span>"+item_description+"</span></b><input id='item_desc' class='form-control' type='hidden' name='item_description[]' value='"+ item_description +"' /></td><td>Warranty<br><span>"+warranty+"</span><input  class='form-control' type='hidden' name='warranty[]' value="+ warranty +"></td><td>Quantity<br><b><span>"+ quantity +"</span></b><input  class='form-control' type='hidden' style='width:80px;' name='quantity[]' value="+ quantity +"></td><td>Rate<br><b><span>"+ rate +"</span></b><input  class='form-control' type='hidden' name='rate[]' style='width:80px;' value="+ rate +"></td><td>Document<br><b><span>"+ document +"</span></b><input  class='form-control' type='hidden' name='document[]' value='"+ document +"'></td></tr>";
		}
			<?php }?>
			
				if(item_cat_text == 'Select' || item_cat_text == 'Select Category'){
					alert('Please select Category');
					return false;
				}else if(item_sub_cat_text == 'Select' || item_sub_cat_text == '--Select Item--'){
					alert('Please select Wattage');
					return false;
				}else if(item_text == 'Select' || item_text == '--Select Item--'){
					alert('Please select Item');
					return false;
				}else if(rate == ''){
					alert('Please input rate');
					return false;
				}else if(warranty == ''){
					alert('Please input warranty');
					return false;
				}else if(quantity == ''){
					alert('Please add quantity');
					return false;
				}else{
				  $(".tablep tbody").append(markup);
				  $("#item_details").hide();
		          $("#item_details_d").hide();
				  if($("#item_desc").val()==''){
						$("#itemdesc").hide();
					}
				  $('#item_cat').val(0);  
					$('#warranty').val();
					$('#item_description').val();
					// $('#sub_item_cat').val(0);
					$('#item').val(0);
					$('#proposed_item').val(0);
					$('#quantity').val('');
					$('#rate').val('');
					$('#proposed_rate').val('');
				   
					$("#item_cat").val('default');
					$("#item_cat").selectpicker("refresh");
					$("#warranty").val('');
					$("#warranty").selectpicker("refresh");	$("#item_description").val('');
					$("#item_description").selectpicker("refresh");
					
					$("#document").val('default');
					$("#document").selectpicker("refresh");
					
					$("#item").val('default');
					//$("#item").selectpicker("refresh");
					
					$("#sub_item_cat").val('default');
					//$("#sub_item_cat").selectpicker("refresh");
			
				  return true;
				}
				
				
			
			
			
		    $('#item_cat').val(0);  
			$('#warranty').val();
			$('#item_description').val();
			// $('#sub_item_cat').val(0);
			$('#item').val(0);
			$('#proposed_item').val(0);
			$('#quantity').val('');
			$('#rate').val('');
			$('#proposed_rate').val('');
           
		    $("#item_cat").val('default');
			$("#item_cat").selectpicker("refresh");
			$("#warranty").val('');
			$("#warranty").selectpicker("refresh");
			$("#item_description").val('');
			$("#item_description").selectpicker("refresh");
			
			$("#document").val('default');
			$("#document").selectpicker("refresh");
			
			$("#item").val('default');
			//$("#item").selectpicker("refresh");
			
			$("#sub_item_cat").val('default');
			//$("#sub_item_cat").selectpicker("refresh");
			
			
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $(".tablep tbody").find('input[name="record"]').each(function(){
            	if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
			
			var i=0;
            $(".tablep tbody").find('input[name="record"]').each(function(){            	
				i++;
            });
			if(i == 0){
				$("#save_record").hide();
			}
			
			
        });
    });    
 </script>
	
	
	<script>
	
		


 $(document).on('change', '#item_cat', function (e) {
        $('#sub_item_cat').html("");
	    var item_cat = $(this).val();
   		var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "admin/lead_requirment/getItemBysubCatID",
            data: {'item_cat': item_cat},
            dataType: "json",
            success: function (data) {
   				console.log(data);
                $.each(data, function (i, obj)
                {
					
                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
   
                });
				div_data += "<option value='0'>New Wattage</option>";
                $('#sub_item_cat').append(div_data);
               
            }
        });
		
		
		/* var item = $("#item option:selected").text();
		var sub_item_cat = $("#sub_item_cat option:selected").text();
        var item_cat = $("#item_cat option:selected").text();
		
		if(item_cat=='New Category' || sub_item_cat=='New Wattage' || item=='New Item'){
			$("#item_details").show();
			$("#item_details_d").show();
		
		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();
		
		} */
    });
  


  $(document).on('change', '#sub_item_cat', function (e) {
        $('#item').html("");
        $('#proposed_item').html("");
       
	    var sub_item_cat = $(this).val();
		
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo 'Select'; ?></option>';
   
        $.ajax({
            type: "GET",
            url: base_url + "admin/lead_requirment/getItemByCatID",
            data: {'subgroup_id': sub_item_cat},
            dataType: "json",
            success: function (data) {
   
				//console.log(data);
                $.each(data, function (i, obj)
                {
   
                    div_data += "<option value=" + obj.id + ">" + obj.description + "</option>";
   
                });
				div_data += "<option value='0'>New Item</option>";
                $('#item').append(div_data);
                $('#proposed_item').append(div_data);
            }
        });
		
		 var item = $("#item option:selected").text();
		var item_description = $(this).text();
        var item_cat = $("#item_cat option:selected").text();
		
		if(item!=''){
			
			
			$("#item_details").show();
			$("#item_details_d").show();
		
		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();
		
		} 
		
    });
   
   
   $(document).on('change', '#item', function (e) {
        
        var item = $("#item option:selected").text();
		var sub_item_cat = $("#sub_item_cat option:selected").text();
        var item_cat = $("#item_cat option:selected").text();
		
		if(item_cat=='New Category' || sub_item_cat=='New Wattage' || item=='New Item'){
			$("#item_details").show();
			$("#item_details_d").show();
		
		}else{
			$("#item_details").hide();
			$("#item_details_d").hide();
		
		}
   
    });
	
	
	$(document).on('change', '#proposed_item', function (e) {
        
        var proposed_item = $("#proposed_item option:selected").text();
		if(proposed_item=='New Item'){
			$("#propsed_item_details").show();
		$("#item_details_du").show();
		
		}else{
			$("#propsed_item_details").hide();
		$("#item_details_du").hide();
		
		}
   
    });
   
	</script>
		
		
</body>
</html>
