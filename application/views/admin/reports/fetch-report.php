<?php
							
							$chkrsm = $this->input->post('chkrsm');
							$total_record1 = sizeof($chkrsm);
							
							$chkasm = $this->input->post('chkasm');
							$total_record2 = sizeof($chkasm);
							
							$chkse = $this->input->post('chkse');
							$total_record3 = sizeof($chkse);
							
							$data = array();
			
							
							//print_r($this->input->post());
							
							$nsm = $this->input->post('nsm');
							
							$chkdynsm = $this->input->post('chkdynsm');
							/* $string_version = implode(',', $original_array)

							 */
							$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=6 AND reporting_manager IN ("'.$nsm.'") ORDER BY firstname ASC');
							
							$result = $query->result_array();
							$total_chkdynsm=1;
							foreach($result as $res){
								if(in_array($res['staffid'], $chkdynsm)){
									
						  ?>
							<tr data-id="<?php echo $total_chkdynsm; ?>" data-parent="">
								<td style="text-align: left !important;"><?php echo $res['firstname'].' '.$res['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res['staffid']); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						   </tr>
						   <?php
								$query1 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res['staffid'].'")');
							
								$result1 = $query1->result_array();
								$total_chkrsm=$total_chkdynsm;
								$totrsm = $total_chkdynsm + 1;
								foreach($result1 as $res1){
								if(in_array($res1['staffid'], $chkrsm)){
						   ?>
							   <tr data-id="<?php echo $totrsm; ?>" data-parent="<?php echo $total_chkrsm; ?>">
								<td style="text-align: left !important;"><?php echo $res1['firstname'].' '.$res1['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res1['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res1['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res1['staffid']); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							
							<?php
								$query2 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res1['staffid'].'")');
							
								$result2 = $query2->result_array();
								$total_chkasm=$totrsm;
								$totasm = $total_chkasm + 1;
								foreach($result2 as $res2){
								if(in_array($res2['staffid'], $chkasm)){
						   ?>
							   <tr data-id="<?php echo $totasm; ?>" data-parent="<?php echo $total_chkasm; ?>">
								<td style="text-align: left !important;"><?php echo $res2['firstname'].' '.$res2['lastname']; ?></td>
								<td><?php echo get_opportunity_sum($res2['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum($res2['staffid']); ?></td>
								<td><?php echo get_opportunity_sum($res2['staffid']); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							
							<?php
								$query3 = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where reporting_manager IN ("'.$res2['staffid'].'")');
							
								$result3 = $query3->result_array();
								$total_chkse=$totasm;
								$totse = $totasm + 20;
								foreach($result3 as $res3){
								if(in_array($res3['staffid'], $chkse)){
						   ?>
							   <tr data-id="<?php echo $totse; ?>" data-parent="<?php echo $totasm; ?>">
								<td style="text-align: left !important;"><?php echo $res3['firstname'].' '.$res3['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_s($res3['staffid']); ?></td>
								<td><?php echo get_project_total_amount_sum_s($res3['staffid']); ?></td>
								<td><?php echo get_opportunity_sum_s($res3['staffid']); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							  
							  <?php
								$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res3['staffid'].'"');
							
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4){
								
						   ?>
							   <tr data-id="<?php echo $totse; ?>" data-parent="<?php echo $total_chkse; ?>">
								<td style="text-align: left !important;"></td>
								<td><?php echo $res4['opportunity']; ?></td>
								<td><?php if(isset($res4['project_total_amount'])){ echo $res4['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4['status']==7){ echo $res4['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4['city']); ?></td>
								<td><?php echo $this->leads_model->get_emp_name($res4['assigned']); ?></td>
								<td><?php echo $res4['customer_type']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4['customer_name']); ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4['id']);
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
									  ?>
								</td>
								<td><?php echo $this->leads_model->get_status_name($res4['status']); ?></td>
								<td><?php echo $res4['project_awarded_to']; ?></td>
							  </tr>
							  <?php
							
							} 
							?>
							<?php
								$total_chkse++;
								}
								
								$totse++;
							} 
							?>
							<?php
								$total_chkasm++;
								}
								
								$totasm++;
							} 
							?>
							<?php
								$total_chkrsm++;
								}
								
								$totrsm++;
							} 
							?>
							
							<?php 
							$total_chkdynsm++;
							
							
							} ?>
						<?php } ?>
						 