<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<a href="#" onclick="new_depot(); return false;" class="btn btn-info pull-left display-block">
								<?php echo 'Add New Depot Master'; ?>
							</a>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
					
						<table class="table dt-table scroll-responsive">
							<thead>
								<th><?php echo 'Dept Code'; ?></th>
								<th><?php echo 'Description'; ?></th>
								<th><?php echo _l('options'); ?></th>
							</thead>
							<tbody>
								<?php foreach($depo_master as $depot){ ?>
								<tr>
									<td><a href="#" data-name="<?php echo $depot['depcode']; ?>"><?php echo $depot['depcode']; ?></a><br />
										
										</td>
										<td><a href="#" data-name="<?php echo $depot['description']; ?>"><?php echo $depot['description']; ?></a><br />
										
										</td>
									
										<td>
											
											<a href="<?php echo admin_url('leads/delete_depo_master/'.$depot['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
											
										</td>
										
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once(APPPATH.'views/admin/leads/depo_master.php'); ?>
	<?php init_tail(); ?>
</body>
</html>
