<div class="row">
						<div class="col-md-4">
							<ul id="tree1">
							<?php
								$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager FROM tblstaff where reporting_manager IN ("'. get_staff_user_id() .'")');
								$result = $query->result_array();
								
								foreach($result as $res){
									
									$query_chield = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager FROM tblstaff where reporting_manager IN ("'. $res['staffid'] .'")');
									$ischield = $query_chield->num_rows();
									
									if($ischield){
										$result_chield = $query_chield->result_array();
									
							?>
									<li>
									
									<a href="#"><?php echo $res['firstname'].' '.$res['lastname']; ?></a>
									<ul>
										<?php
											foreach($result_chield as $res_chield){
												$query_sub_chield = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager FROM tblstaff where reporting_manager IN ("'. $res_chield['staffid'] .'")');
												
												$is_sub_chield = $query_sub_chield->num_rows();
									
												if($is_sub_chield){
												$result_sub_chield = $query_sub_chield->result_array();
												
										?>
										
										<li><?php echo $res_chield['firstname'].' '.$res_chield['lastname']; ?>
											<ul>
												<li>Reports
													<ul>
														<li>Report1</li>
														<li>Report2</li>
														<li>Report3</li>
													</ul>
												</li>
												<li>Employee Maint.</li>
											</ul>
										</li>
										<?php }else{ ?>
										<li><?php echo $res_chield['firstname'].' '.$res_chield['lastname']; ?></li>
										<?php } 
											}
										?>
									</ul>
								</li>
								
								<?php }else{ ?>
									<li><?php echo $res['firstname'].' '.$res['lastname']; ?></li>
								<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
				