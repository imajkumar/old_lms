
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
	<form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/lead_requirment/add_lead_document_data/') ?>">
	<div class="panel_s accounting-template estimate">
		<div class="panel-body">
		<h4>Project Related Documents</h4>
			<div class="row hide">
			 <div class="col-md-6">
				<div class="f_client_id">
				 <div class="form-group select-placeholder">
					<label for="clientid" class="control-label"><?php echo "Select Lead"; ?></label>
					<input type="hidden" name="client_id"  value="<?php echo $_GET['id']; ?>">
					
				  </div>
				</div>
				
			 </div>
					<div class="col-sm-4">
                        <div class="form-group field-lead-lead_owner_id hidden">
                           <label class="control-label" for="lead-lead_owner_id">Lead Owner</label>
                           <select id="lead-lead_owner_id"  class="form-control" name="lead_owner">
                              <option value="<?php echo $this->session->staff_user_id; ?>"> <?php echo $this->session->staff_user_id; ?></option>
                           </select>
                           <div class="help-block"></div>
                        </div>
					</div>
					<div class="col-md-6 col-sm-4">
                        <div class="form-group field-lead-lead_owner_id">
                           <label class="control-label" for="lead-lead_owner_id">Lead Remark</label>
						   <textarea class="form-control" name="remark" id="remark"></textarea>
                           
                           <div class="help-block"></div>
                        </div>
                     </div>
					
					</div>
			
			<div class="row around10">   
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>1.</td>
								<td><input type="text" name='first_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>2.</td>
								<td><input type="text" name='second_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='second_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>3.</td>
								<td><input type="text" name='third_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='third_doc' id="doc1" >
								</td>
							</tr>
						</tbody></table>
				</div>
				<div class="col-md-6">
					<table class="table">
						<tbody><tr>
								<th style="width: 10px">#</th>
								<th><?php echo 'Title'; ?></th>
								<th><?php echo  'Documents'; ?></th>
							</tr>
							<tr>
								<td>4.</td>
								<td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fourth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td>5.</td>
								<td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>
								<td>
									<input class="filestyle form-control" type='file' name='fifth_doc' id="doc1" >
								</td>
							</tr>
							<tr>
								<td></td>
							</tr>
							
							
						</tbody></table>
				</div>
			</div>
           
			<div class="row">
      <div class="col-md-12  text-right">
	  <button type="submit" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit" onclick="location.href='<?php echo base_url();?>admin/leads'">
              <?php echo 'Cancel'; ?>
              </button>
            <button type="submit" id="save_record" class="btn-tr btn btn-info mleft10 estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
              </button>
            <div class="btn-bottom-pusher"></div>
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
