<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="#" onclick="new_status(); return false;" class="btn btn-info pull-left display-block">
								<?php echo 'Add New Status'; ?>
							</a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php if(count($customer) > 0){ ?>
						<table class="table dt-table scroll-responsive">
							<thead>
								<th><?php echo 'Status'; ?></th>
								<th><?php echo 'Status Category'; ?></th>
								<th><?php echo _l('options'); ?></th>
							</thead>
							<tbody>
								<?php foreach($customer as $status){ ?>
								<tr>
									<td><a href="#" onclick="edit_status(this,<?php echo $status['id']; ?>);return false;" data-color="<?php echo $status['color']; ?>" data-name="<?php echo $status['name']; ?>" data-code="<?php echo $status['code']; ?>"><?php echo $status['name']; ?></a><br />
										<span class="text-muted hide">
											<?php echo _l('leads_table_total',total_rows('tblleads',array('customer_type'=>$status['code']))); ?></span>
										</td>
										
										<td>
											<?php echo $this->leads_model->get_status_name($status['status_id']); ?>
										</td>
										
										<td>
											<a href="#" onclick="edit_status(this,<?php echo $status['id']; ?>);return false;" data-color="<?php echo $status['color']; ?>" data-name="<?php echo $status['name']; ?>" data-order="<?php echo $status['code']; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
											<?php											
												if($status['status'] == 1){
											?>
												<a href="<?php echo admin_url('leads/loss_status_inactive/'.$status['id']); ?>" class="btn btn-danger"><i class="fa fa-ban"></i></a>
											<?php }else{ ?>
												<a href="<?php echo admin_url('leads/loss_status_active/'.$status['id']); ?>" class="btn btn-danger"><i class="fa fa-check"></i></a>
											
											<?php } ?>
											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<?php } else { ?>
							<p class="no-margin"><?php echo _l('lead_statuses_not_found'); ?></p>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once(APPPATH.'views/admin/leads/status_loss.php'); ?>
	<?php init_tail(); ?>
</body>
</html>
