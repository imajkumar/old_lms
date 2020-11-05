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
      <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/lead_requirment/lead_carry_forward/') ?>">
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Lead Carry Forward</h4>
                     <a href="<?php echo base_url('admin/lead_requirment/lead_carry_forward_data/') ?>" class="btn btn-info mright5 test pull-right display-block">
                     Show Report Data</a>
                  </div>
                  <div class="col-md-4">
                     <b>Select Year</b><br>
                     <select class="form-control" name="year">
                        <option value="">Select Year</option>
                        <?php 
                           $now = new \DateTime('now');
                             $year = $now->format('Y');
                           ?>
                        <option value="2019" <?php if($year == 2019){ echo 'selected'; } ?>>2019</option>
                        <option value="2020" <?php if($year == 2020){ echo 'selected'; } ?>>2020</option>
                        <option value="2021" <?php if($year == 2021){ echo 'selected'; } ?>>2021</option>
                        <option value="2022" <?php if($year == 2022){ echo 'selected'; } ?>>2022</option>
                        <option value="2023" <?php if($year == 2023){ echo 'selected'; } ?>>2023</option>
                        <option value="2024" <?php if($year == 2024){ echo 'selected'; } ?>>2024</option>
                        <option value="2025" <?php if($year == 2025){ echo 'selected'; } ?>>2025</option>
                        <option value="2026" <?php if($year == 2026){ echo 'selected'; } ?>>2026</option>
                        <option value="2027" <?php if($year == 2027){ echo 'selected'; } ?>>2027</option>
                        <option value="2028" <?php if($year == 2028){ echo 'selected'; } ?>>2028</option>
                        <option value="2029" <?php if($year == 2029){ echo 'selected'; } ?>>2029</option>
                     </select>
                  </div>
                  <div class="col-md-4">
                     <b>Select Month </b><br>
					 
                     <select name="month" class="form-control">
                        <option value="">Select Month</option>
                        <option <?php if($this->input->post('month')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('month')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('month')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('month')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('month')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('month')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('month')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('month')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('month')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('month')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('month')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('month')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							
                     </select>
                  </div>
                  <div class="col-md-12">
                     <br>
                     <table class="table dt-table scroll-responsive">
                        <thead style="background: #f47b34;color: #fff;">
                           <tr>
                              <th><?php echo 'Created By'; ?></th>
                              <th><?php echo 'Lead ID'; ?></th>
                              <th><?php echo  'Creation Date'; ?></th>
                              <th><?php echo 'Customer Group '; ?></th>
                              <th><?php echo 'Customer Name'; ?></th>
                              <th><?php echo 'Lead Description '; ?></th>
                              <th><?php echo ' Won Date '; ?></th>
                              <th><?php echo 'Won Amount '; ?></th>
                              <th><?php echo 'Total Executed Value'; ?></th>
                              <th><?php echo 'Last Month Executed Value'; ?></th>
                              <th><?php echo ' Carry Forward'; ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              foreach($lead_carry as $data_l)
                              {
                              	if($data_l['close_won'] != $data_l['last_executed']){
                              
                              ?>
                           <tr>
                              <td><input type="hidden" name='doc_id[]' class="form-control" value="<?php echo $data_l['id']; ?>"><input type="hidden" name='lead_id[]' class="form-control" value="<?php echo $data_l['lead_id']; ?>" style="width: 68px;">
                                 <?php echo $this->leads_model->get_emp_name($data_l['staff_id']); ?>
                              </td>
                              <td>
                                 <?php echo $data_l['lead_id']; ?>
                              </td>
                              <td>
                                 <?php echo $data_l['last_changed']; ?>
                              </td>
                              <td>
                                 <?php echo $this->leads_model->customer_group_byname($data_l['customer_name']); ?>
                                 <input  type="hidden"  value="<?php echo $data_l['customer_name']; ?>" name='customer_name[]' class="form-control customer_name" readonly style="width: 68px;">
                              </td>
                              <td>
                                 <?php echo $this->leads_model->get_customer_name($data_l['customer_group']); ?>
                                 <input  type="hidden"  value="<?php echo $data_l['customer_group']; ?>" name='customer_group[]' class="form-control customer_group" readonly style="width: 68px;">
                              </td>
                              <td>
                                 <?php
                                    echo $this->leads_model->get_lead_description($data_l['lead_id']);
                                    ?>
                              </td>
                              <td>
                                 <?php echo $data_l['created']; ?>
                                 <input  type="hidden"  value="<?php echo $data_l['created']; ?>" name='won_date[]' class="form-control won_date" readonly style="width: 68px;">
                              </td>
                              <td><input  type="text"  value="<?php echo $data_l['close_won']; ?>" name='close_won[]' class="form-control close_won" readonly style="width:68px;"></td>
                              <td>
                                 <input  type="text" readonly value="<?php echo $data_l['last_executed']; ?>" name='last_executed[]' class="form-control last_executed expenses" style="width:68px;">
                              </td>
                              <td>
                                 <?php
                                    if($data_l['month'] == $data_l['executed']){
                                    	?>
                                 <input  type="text" readonly value="<?php echo $data_l['executed']; ?>" name='executed[]' class="form-control executed expenses" style="width: 68px;">
								 
                                 <?php
                                    }
                                    else{
                                    	?>
										<div class="col-md-12">
										 <input  type="text"  value="<?php echo $data_l['executed']; ?>" name='executed[]' class="form-control executed expenses" style="width: 68px;"><br>
										 <span style="color:red" class="error"></span>
										</div>
                                 <?php	
                                    }
                                    
                                    ?>
                              </td>
                              <td>
                                 <input  type="text" readonly value="<?php echo $data_l['carry_forward']; ?>" name='carry_total[]' style="width: 68px;" class="form-control carry_total">
                              </td>
                           </tr>
                           <?php $i++; 
                              }
                              } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="col-md-12">
                  <button type="submit"  class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
                  <?php echo _l('update'); ?>
                  </button>
               </div>
            </div>
         </div>
   </div>
   </form>
</div>
</div>
<?php init_tail(); ?>
<script>
 
   $(document).ready(function(){
       $(".expenses").each(function() {   
         $(this).keyup(function(){
               sum($(this).parents("tr"));			  
         });
		 
		 $(this).focusout(function(){
				var carry_total = $(this).parents("tr").find(".carry_total").val();
				if(carry_total < 0){
					$(this).focus();
					$(this).parents("tr").find(".error").text("Amount is greater than Won Amount");
					$(this).css("background-color", "red");
					return false;
				}else{
					$(this).css("background-color", "white");
					$(this).parents("tr").find(".error").text("");
				}
				
			 });
			 
		 
       });
   });
   function sum(parent){
       var sum = 0;
       $(parent).find(".expenses").each(function(){
           if(!isNaN(this.value) && this.value.length!=0) {
               sum += parseFloat(this.value);
           }
		   
		   
       });
       var close_won = $(parent).find(".close_won").val();
		
		
		$(parent).find(".carry_total").val(close_won - sum.toFixed(2));
		
   }
</script>
</body>
</html>