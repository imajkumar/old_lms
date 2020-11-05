<?php init_head(); 

?>
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
      
         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Stage Wise Reports</h4>
                    
                  </div>                  
				  <div class="col-md-12">
                     <br>
                     <table class="table border">
                        <thead style="">
                           <tr>
							<th style="text-align:center;" rowspan="2"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#56ff63" colspan="4"><strong>Lead</strong></th>
							<th style="text-align:center;" bgcolor="#56FED4" colspan="4"><strong>Lead Value(In Lakhs)</strong></th>
							
						  </tr>
						  <tr>
								<td bgcolor="#56ff63"><strong><?php echo date('M', strtotime('-3 month')).'-'.date('Y'); ?></strong></td>
								<td bgcolor="#56ff63"><strong><?php echo date('M', strtotime('-2 month')).'-'.date('Y') ;?></strong></td>
								<td bgcolor="#56ff63"><strong><?php echo date('M', strtotime('-1 month')).'-'.date('Y') ;?></strong></td>
								<td bgcolor="#56FED4"><strong><?php echo date('M', strtotime('-3 month')).'-'.date('Y'); ?></strong></td>
								<td bgcolor="#56FED4"><strong><?php echo date('M', strtotime('-2 month')).'-'.date('Y') ;?></strong></td>
								<td bgcolor="#56FED4"><strong><?php echo date('M', strtotime('-1 month')).'-'.date('Y') ;?></strong></td>
								
							 </tr>
                        </thead>
                        <tbody>
							
                           <?php
						   
						    $last2Monthno_of_leadsTotal = 0;
							$lastMonthno_of_leadsTotal = 0;
							$thisMonthno_of_leadsTotal = 0;
							$lastdateno_of_leadsTotal = 0;
							
							$last2Monthvalue_of_leadsTotal = 0;
							$lastMonthvalue_of_leadsTotal = 0;
							$thisMonthvalue_of_leadsTotal = 0;
							$lastdatevalue_of_leadsTotal = 0;
							
							$leadsTotal_of_total = 0;
							$leadsvalue_of_total = 0;
							$this->db->order_by("id", "asc");
							$leadstage = $this->db->get('tblleadsstatus')->result_array();
                              foreach($leadstage as $data_l)
                              {
                              	$leadsTotal = 0;
                              	$leadsAmountTotal = 0;
								
								$thisMonth = date('Y-m', strtotime("-1 month"));	
								$lastMonth = date('Y-m', strtotime("-2 month"));	
								$last2Month = date('Y-m', strtotime("-3 month"));	
								$lastdate = date('Y-m-d', strtotime("-1 days"));
			
								$last2Monthno_of_leadsTotal = $last2Monthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]);
								$lastMonthno_of_leadsTotal = $lastMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]);
								$thisMonthno_of_leadsTotal = $thisMonthno_of_leadsTotal + $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]);				
								
								$leadsTotal = $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]) + $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]) + $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]);
								
								$leadsTotal_of_total = $leadsTotal_of_total + $leadsTotal;
								
								$last2Monthvalue_of_leadsTotal = $last2Monthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]);
								$lastMonthvalue_of_leadsTotal = $lastMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]);
								$thisMonthvalue_of_leadsTotal = $thisMonthvalue_of_leadsTotal + $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]);				
								
								$leadsAmountTotal = $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]) + $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]);
								
								$leadsvalue_of_total = $leadsvalue_of_total + $leadsAmountTotal;
								
                              ?>
								<tr>
									<td style="text-align:left;"><?php echo $data_l['name']; ?></td>
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month($last2Month,$data_l["id"]); ?></td>
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month($lastMonth,$data_l["id"]); ?></td>
									<td><?php echo $this->leads_model->no_of_leads_by_stage_month($thisMonth,$data_l["id"]); ?></td>
									
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month($last2Month,$data_l["id"]) ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month($lastMonth,$data_l["id"]) ?></td>
									<td><?php echo $this->leads_model->value_of_leads_by_stage_month($thisMonth,$data_l["id"]) ?></td>
									
								  </tr>
						   <?php $i++; 
                              
                              } ?>
							  <tr>
									<td style="text-align:left;"><strong><?php echo 'Total'; ?></strong></td>
									<td ><strong><?php echo $last2Monthno_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $lastMonthno_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $thisMonthno_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $leadsTotal_of_total; ?></strong></td>
									
									<td ><strong><?php echo $last2Monthvalue_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $lastMonthvalue_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $thisMonthvalue_of_leadsTotal; ?></strong></td>
									<td ><strong><?php echo $leadsvalue_of_total; ?></strong></td>
									
								  </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
              
            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>

</body>
</html>