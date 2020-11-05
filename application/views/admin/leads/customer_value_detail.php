<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons hide">
							<a href="#" onclick="new_status(); return false;" class="btn btn-info pull-left display-block">
								<?php echo 'Add New Customer'; ?>
							</a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php if(count($customer) > 0){ ?>
						<table class="table dt-table scroll-responsive">
							<thead>
								<th><?php echo _l('leads_customer_table_name'); ?></th>
								<th><?php echo 'Customer Code'; ?></th>
								<th><?php echo _l('options'); ?></th>
							</thead>
							<tbody>
								<?php foreach($customer as $status){ ?>
								<tr>
									<td><?php echo $status['customer_name']; ?>
										</td>
										<td><?php echo $status['customer_type_code']; ?>
										</td>
										<td>
				<a href="<?php echo base_url('admin/leads/update_customer_value/'.$status['id']) ?>"  class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
											
										
											
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
	
	<?php init_tail(); ?>
</body>
</html>
