    <div id="winloss-report" class="hide">
      <?php
		//print_r($pipeline);
	  ?>
	
         <table class="table dt-table scroll-responsive">
            <thead>
               <tr>
                  <th><?php echo _l('Region'); ?></th>
                  <th><?php echo _l('Area'); ?></th>
                  <th><?php echo _l('Executive name'); ?></th>
                  <th><?php echo _l('Catg.'); ?></th>
                  <th><?php echo _l('Customer Name'); ?></th>
                  <th><?php echo _l('Product Description'); ?></th>
                  <th><?php echo _l('Status'); ?></th>
                  <th><?php echo _l('If lost then lost to'); ?></th>
                  <th><?php echo _l('Opportunity Value(Lac)'); ?></th>
                  <th><?php echo _l('Order Value(Lac)'); ?></th>
                  <th><?php echo _l('Remarks'); ?></th>
               </tr>
            </thead>
            <tbody>
				<?php 
			
			
			
			foreach($pipeline as $pipeline_data)
			{
				
				$competition = '';
				
				if($pipeline_data['competition'] !='')
					$competition .= $pipeline_data['competition'];
				if($pipeline_data['competition1'] !='')
					$competition .= ', '.$pipeline_data['competition1'];
				if($pipeline_data['competition2'] !='')
					$competition .= ', '.$pipeline_data['competition2'];
				if($pipeline_data['competition3'] !='')
					$competition .= ', '.$pipeline_data['competition3'];
				if($pipeline_data['competition4'] !='')
					$competition .= ', '.$pipeline_data['competition4'];
				
			?>
           
				<tr>
                  <td><?php echo $this->leads_model->get_region_name($pipeline_data['state_id']); ?></td>
				  <td><?php echo $pipeline_data['state_name']; ?></td>
				  <td><?php echo $this->leads_model->get_emp_name($pipeline_data['assigned']); ?></td>
				  <td><?php echo $pipeline_data['customer_type']; ?></td>
				  <td><?php echo $this->leads_model->get_customer_name($pipeline_data['customer_name']); ?></td>
				  <td><?php 
					$CAT = $this->leads_model->get_product_description($pipeline_data['id']);
					$string = '';
					foreach ($CAT as $value) 
					{
						if (!empty($string)) 
						{ 
							$string .= ', '; 
						}
						$string .= $value['cat_name'];
					}
					echo $string;
				  ?></td>
				  <td><?php echo $pipeline_data['status_name']; ?></td>
				  <td><?php echo $pipeline_data['project_awarded_to']; ?></td>
	
				<td><?php 
				 $opportunity_total = format_money($pipeline_data['project_total_amount'], ($pipeline_data['currency'] != 0 ? $this->ci->currencies_model->get_currency_symbol($pipeline_data['currency']) : $baseCurrencySymbol));
				echo $opportunity_total; ?></td>
				   <td>
				  <?php

 $opportunity = format_money($pipeline_data['opportunity'], ($pipeline_data['currency'] != 0 ? $this->ci->currencies_model->get_currency_symbol($pipeline_data['currency']) : $baseCurrencySymbol));
    

				  echo  $opportunity; ?></td>
				 <td>
				 <?php
				 if($pipeline_data['status_name']=='Closed Lost' || $pipeline_data['status_name']== 'Closed Won')
{ 	echo $this->leads_model->get_status_loss($pipeline_data['status_lost']);
				  }
	else{
		echo $pipeline_data['status_lost'];
	}			  
				  
				  ?></td>
				
               </tr>
			
            <?php
			}
			?>
				
			</tbody>
            
         </table>
   </div>
 
