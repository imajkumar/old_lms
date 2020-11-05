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
      <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/lead_requirment/lead_carry_forward_data/') ?>">
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Lead Carry Forward</h4>
                  </div>
                  <div class="row">
                     <div class="col-md-3">
						<h5>Filter From:</h5>
						<div class="col-md-6">
							<select class="selectpicker" name="from-months" required data-width="100%" data-none-selected-text="Select Month" tabindex="-98">
							   <option value="">Select Month</option>
							   <option <?php if($this->input->post('from-months')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('from-months')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('from-months')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('from-months')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('from-months')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('from-months')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('from-months')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('from-months')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('from-months')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('from-months')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('from-months')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('from-months')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							</select>
						
						</div>
						<div class="col-md-6">
							<select class="selectpicker" name="from-years" required data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
							   <option value="">Nothing selected</option>
							  
								<option <?php if($this->input->post('from-years')=='2019'){ echo 'selected'; }else if($this->input->post('from-years')=='') { echo 'selected'; } ?> value="2019">2019</option>
								<option <?php if($this->input->post('from-years')=='2020'){ echo 'selected'; } ?>  value="2020">2020</option>
								<option <?php if($this->input->post('from-years')=='2021'){ echo 'selected'; } ?>  value="2021">2021</option>
								<option <?php if($this->input->post('from-years')=='2022'){ echo 'selected'; } ?>  value="2022">2022</option>
								<option <?php if($this->input->post('from-years')=='2023'){ echo 'selected'; } ?>  value="2023">2023</option>
								<option <?php if($this->input->post('from-years')=='2024'){ echo 'selected'; } ?>  value="2024">2024</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<h5>Filter To:</h5>
						<div class="col-md-6">
							<select class="selectpicker" name="to-months" required data-width="100%" data-none-selected-text="Select Month" tabindex="-98">
							   <option value="">Select Month</option>
							   <option <?php if($this->input->post('to-months')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('to-months')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('to-months')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('to-months')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('to-months')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('to-months')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('to-months')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('to-months')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('to-months')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('to-months')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('to-months')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('to-months')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							</select>
						
						</div>
						<div class="col-md-6">
							<select class="selectpicker" name="to-years" required data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
							   <option value="">Nothing selected</option>
							   
								<option <?php if($this->input->post('to-years')=='2019'){ echo 'selected'; }else if($this->input->post('to-years')=='') { echo 'selected'; } ?> value="2019">2019</option>
								<option <?php if($this->input->post('to-years')=='2020'){ echo 'selected'; } ?>  value="2020">2020</option>
								<option <?php if($this->input->post('to-years')=='2021'){ echo 'selected'; } ?>  value="2021">2021</option>
								<option <?php if($this->input->post('to-years')=='2022'){ echo 'selected'; } ?>  value="2022">2022</option>
								<option <?php if($this->input->post('to-years')=='2023'){ echo 'selected'; } ?>  value="2023">2023</option>
								<option <?php if($this->input->post('to-years')=='2024'){ echo 'selected'; } ?>  value="2024">2024</option>
							</select>
						</div>
					</div>
					
					 <?php 
							
							$arruser = "reporting_to LIKE '%".get_staff_user_id()."%' AND is_not_staff = 0";
							
							$this->db->order_by('firstname');
						    $this->db->select()->from('tblstaff');
							if(get_staff_role() < 9)
							$this->db->where($arruser);
						
							$query = $this->db->get();
							$staff = $query->result_array();
							
							$selected = array();
							  if($this->input->post('view_assigned')){
								array_push($selected,$this->input->post('view_assigned'));
							  }
							 
						 ?>
						  <div class="col-md-2">
						 <h5>Staff</h5>
						   <?php if(get_staff_role() == 1 ){ ?>
						   <select id="view_assigned" name="view_assigned" class="form-control">
								<option value="<?php echo get_staff_user_id(); ?>"><?php echo get_staff_full_name(); ?></option>
						   </select>
						   
						   <?php }else{  ?>
							
						  <select id="view_assigned" name="view_assigned" class="form-control <?php if(get_staff_role() == 1 ){ echo 'hide'; } ?>">
							<option value="">--Select--</option>
							<?php foreach ($staff as $staffd) { 
							    $arr_user = "reportingto LIKE '%".$staffd["staffid"]."%' OR assigned = '".$staffd["staffid"]."'";
								$this->db->select()->from('tblleads');
								$this->db->where($arr_user);
								$query = $this->db->get();
								$ifzsmleadvalue = $query->num_rows();
								if($ifzsmleadvalue > 0)
								{
							?>
								<option <?php if($staffd["staffid"]==$this->input->post('view_assigned')){ echo 'selected'; } ?> value="<?php echo $staffd["staffid"]; ?>"><?php echo $staffd["firstname"].' '.$staffd["lastname"].' - '.$staffd['emp_code']; ?></option>
							<?php } } ?>
						   </select>
						   <?php } ?>
						  </div>
						 
                     <div class="col-sm-2">
                        <div class="form-group">
                           <label><br></label><br>
                           <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo 'Show Data'; ?></button>
                        </div>
                     </div>
      </form>
      </div>
      <?php
         if (isset($resultlist)) {
             ?>
      <div class="col-md-12">
      <table class="table dt-table scroll-responsive">
      <thead>
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
      <th><?php echo 'Month'; ?></th>
      <th><?php echo 'Year'; ?></th>
      </tr>
      </thead>
      <tbody>
      <?php
         foreach($resultlist as $data_l)
         {
         	
         
         ?>
      <tr>
      <td>
      <?php echo $this->leads_model->get_emp_name($data_l['staff_id']); ?>
      </td>
      <td>
      <?php echo $data_l['lead_id']; ?>
      </td>
      <td>
      <?php echo $data_l['created_date']; ?>
      </td>	
      <td><?php echo $this->leads_model->customer_group_byname($data_l['customer_name']); ?>
      </td>
      <td>	<?php echo $this->leads_model->get_customer_name($data_l['customer_group']); ?>
      </td>
      <td>
      <?php
         echo $this->leads_model->get_lead_description($data_l['lead_id']);
         
         ?>
      </td>
      <td>
      <?php echo $data_l['won_date']; ?>
      </td>
      <td><?php echo $data_l['won_amount']; ?></td>
      <td>
      <?php echo $data_l['executed_amount']; ?>
      </td>
      <td>
      <?php echo $data_l['month']; ?>
      </td><td>
      <?php echo $data_l['year']; ?>
      </td>
      </tr>
      <?php $i++; 
         } ?>
      </tbody>
      </table>
      </div>
      <?php } 
         ?>
      </div>
      </div>
      </div>
   </div>
</div>
</div>
<?php init_tail(); ?>
<script>
   $(document).ready(function(){
       $(".expenses").each(function() {
   
         $(this).keyup(function(){
               sum($(this).parents("tr"));
         });
   	  var carry_total = $(parent).find(".carry_total").val();
   	  if(carry_total < 0){
   		alert('Amount is greater than Won Amount');
   		return false;
   	}
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