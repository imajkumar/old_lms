<?php init_head(); ?>

<div id="wrapper" style="margin: 0px 0px 0px 45px; min-height: 954px;">

   <div class="content">

      <div class="row">

         <div class="col-md-12">





<div class="panel_s">

<div class="panel-body">

 <?php if(isset($lead)){

             echo form_hidden('leadid',$leads->id);

         } ?>





<table id="exampletbl" class="table dt-table scroll-responsive">

<h3>Lead Name : <?php echo $leads->description; ?></h3>

        <thead>

		 <tr>

                <th>Category</th> 

				<th>Wattage</th>

                <th>Item</th>

                <th>New Item Description</th> <th>Item Warranty</th>

                <th>Qty</th>

                <th>Rate</th> <th>Total Amount</th>

                <th>Document Required</th>

				<th>Proposed Item</th>

				<th id="item_details" style="width:170px" class="hide" align="left">Item Details</th>

                <th>Added On</th>

				<th>Reason</th>

				<th>Status</th>	

				

            </tr>

        </thead>

		

      <tbody>

	 

		<input type="hidden" name="lead_id" id="leadid" value="<?php echo $leads->id; ?>" />

		

		<?php

		

			$this->db->where('lead_requirment_id',$leads->id);

			$this->db->order_by('id', 'DESC');  //actual field name of id

			$query=$this->db->get('tbllead_requirment_detail');

			

			$record = $query->result();

			

			foreach($record as $rec){

			

			$itemrecord = array();

			if($rec->category_id != 0){

				$this->db->where('group_id',$rec->category_id);

				$this->db->order_by('id', 'DESC');  //actual field name of id

				$query=$this->db->get('tblitems');



				$itemrecord = $query->result();	

			}else{

				$this->db->order_by('id', 'DESC');  //actual field name of id

				$query=$this->db->get('tblitems');

				$itemrecord = $query->result();	

		

			}

		?>

		

		

            <tr>

                <td>

				<input type="hidden" name="tblitem_id[]" value="<?php echo $rec->id; ?>" />

				<?php 

				if($rec->category_id == 0){

					echo 'New Category';

				}else{

					echo $this->leads_model->get_group($rec->category_id); 

				}

				

				?></td>

				<td><?php

				if($rec->subcategory_id == 0){

					echo 'New Wattage';

				}else{

					echo $this->leads_model->get_subcategory($rec->subcategory_id); 

				}



				 ?></td>

                <td><?php 

				if($rec->item_id == 0){

					$item_name= $rec->item_description;
					echo 'New Item';

				}else{

					$item_name = $this->leads_model->get_item($rec->item_id); 
					echo $this->leads_model->get_item($rec->item_id); 
				}
				
				 ?></td> 

			

                <td><?php echo $rec->item_description; ?></td> 
				<td><?php echo $rec->warranty; ?></td>

                <td><?php echo $rec->quantity; ?></td>

                <td><?php echo $rec->rate; ?></td>

				

				

				<td><?php



				$total_amount = $rec->quantity*$rec->rate;



				echo $total_amount; ?></td>

                <td><?php echo $rec->document; ?></td>

                <td>

				

				<?php 

					if($rec->proposed_item_id=='0' || $rec->proposed_item_id==''){ 
						$pitem_name = 'No Item'; 

					}else{ 

						$this->db->where('id', $rec->proposed_item_id);

						$pitem_name = $this->db->get('tblitems')->row()->description; 

					} 
					echo $pitem_name;

				 ?>

				</td>

				

				<td><?php echo $rec->addedon; ?></td>

				<td>

				<?php

				if($rec->status==1)

				{

				 echo $rec->reason; 

				}

				

				 ?>

				 

				 </td>

				<td>

				

				<input type="hidden" name="category_id" id="category_id" value="<?php echo $rec->category_id; ?>" />
				<input type="hidden" name="wattage" id="wattage" value="<?php echo $rec->subcategory_id; ?>" />

				<input type="hidden" name="itemid" id="item_id" value="<?php echo $rec->id; ?>" />

				<input type="hidden" name="status" value="<?php echo $rec->status; ?>" />
				<input type="hidden" name="title" value="<?php echo $rec->item_description; ?>" />
				
					<?php if($rec->status==0){ ?>

					 <i data="<?php echo $rec->id;?>" title="<?php echo $rec->category_id; ?>" wattage="<?php echo $rec->subcategory_id; ?>" item="<?php echo $item_name; ?>"  class="status_checks btn

						  <?php echo ($rec->status)?

						  'btn-success': 'btn-danger'?>"><?php echo ($rec->status)? 'Item Inactive' : 'Mark Inactive'?>

					</i>

					<?php }else{ ?>

							<?php echo ($rec->status)? 'Item Inactive' : 'Mark Inactive'?>

					<?php }						?>

				</form>

					

			    </td>

				

            </tr>

			

		<?php

			}

		?>

	

	

		

        </tbody>

        



   </table>

	<div class="col-md-12">

	<hr/>

				 <a href="<?php echo base_url().'admin/leads/index/'.$leads->id; ?>" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit pull-right">

					  <?php echo _l('Back to Lead'); ?>

					  </a>

			  </div>

	</div>

	</div>

	</div>

	</div>

	</div>

	</div>

	

 <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>

<script type="text/javascript">

	$(document).on('click','.status_checks',function(){

	  var txt;

	  var person = prompt("Are you sure to Active/Inactive:", "");

	  var current_element = $(this);

	  var lead_id = $('#leadid').val();

	  var category_id = $(current_element).attr('title');
	  var wattage = $(current_element).attr('wattage');

	  var item_id = $(current_element).attr('data');
	  var item_name = $(current_element).attr('item');

	  var status = ($(this).hasClass("btn-success")) ? '0' : '1';

      var input = (status=='0')? 'Inactive' : 'Active';
	 
	 if (person != null) 

	  {

         var current_element = $(this);

		 url = "<?php echo base_url('admin/lead_requirment/update_requirment_status/');?>"; 

        $.ajax({

          type:"POST",

          url: url,

          data: {lead_id:lead_id,item_id:item_id,status:status,reason:person,category_id:category_id,wattage:wattage,item_name:item_name},

		

          success: function(data)

          {  

			console.log(data);

            location.reload();

          }

        });

		 txt = "" + person + ""; 

      }   

	else{

	  alert("Please enter reason!");	  

	}	 



    });

</script>

	

	

	