

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
	<h5>* Note: Please upload max 50mb file size at a time</h5>
	<form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/lead_requirment/lead_requirnment_file') ?>">
	<div class="panel_s accounting-template estimate">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Item Category'; ?></th>
								<th><?php echo 'Item'; ?></th><th><?php echo 'Wattage'; ?></th><th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<?php
								$leadid = '';
								$i=1;
								foreach($lead_doc_data as $data_l){
								$leadid = $data_l['lead_id'];
							?>
								<tr>
									<td><?php echo $i; ?><input type="hidden" name='doc_id[]' class="form-control" value="<?php echo $data_l['id']; ?>"><input type="hidden" name='lead_id' class="form-control" value="<?php echo $data_l['lead_id']; ?>"></td>
									<td>
									<?php 
									
									if($data_l['category_id']==0){
										echo  $this->db->get_where('tbllead_requirment_detail', array('lead_requirment_id' => $data_l['lead_id']))->row()->item_description;
									}else{
									
									echo  $this->db->get_where('tblitems_groups', array('id' => $data_l['category_id']))->row()->name; 
									
									}
									?>
									
									</td>
									<td><input readonly type="text"  value="<?php echo $data_l['title']; ?>" name='first_title[]' class="form-control"></td>
									
									<td>
									<?php 
									
									
									
									echo  $this->db->get_where('tblitems_sub_groups', array('id' => $data_l['wattage']))->row()->name; 
									
									
									?>
									</td>
									<td><?php echo $data_l['wattage_title']; ?></td>
									<td>
										<input class="filestyle form-control" type='file' name='first_doc_<?php echo $i; ?>' id="doc1" >
									</td>
								</tr>
							<?php $i++; 
							} ?>
							<input type="hidden" name='total_doc_id' class="form-control" value="<?php echo $i-1; ?>">
						</tbody>
						</table>
				</div>
			
			  <div class="col-md-12">
			  <a href="<?php echo base_url().'admin/leads/index/'.$leadid; ?>" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
					  <?php echo _l('Back to Lead'); ?>
					  </a>
			  </div>
			  <?php if(!empty($lead_doc_data)){ ?>
				 <button type="submit" onclick="return confirm('Do you agreed to submit.. if you submit its approved from your side!');" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit pull-right">
					  <?php echo _l('Upload'); ?>
					  </button>
			  <?php } ?>
			  </div>
		   </div>
		</div>
	</div>
	</form>
</div>
</div>

<?php init_tail(); ?>
	
</body>
</html>
