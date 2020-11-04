         <div class="col-md-12">
          <div class="panel_s mbot10">
           <div class="panel-body _buttons">
            <?php $this->load->view('admin/lead_requirment/estimates_top_stats');
           ?>
           
           <a href="<?php echo admin_url('lead_requirment/add_lead_requirement'); ?>" class="btn btn-info pull-left new new-estimate-btn"><?php echo 'New Lead Requirement'; ?></a>
          
    </div>
  </div>
  <div class="row">
   <div class="col-md-12">
    <div class="panel_s">
     <div class="panel-body">
      <!-- if estimateid found in url -->
	  <div class="table-responsive mtop10">
				
					<table class="table">
					 <thead>
						<tr>
						   <th>#</th>
						   <th  align="left"><?php echo 'LR. ID'; ?></th>
						   <th align="left"><?php echo 'Company'; ?></th>
						   <th  class="left" ><?php echo 'Added By'; ?></th>
						   <th align="left"><?php echo 'Date'; ?></th>
						   <th align="left"><?php echo 'Remark'; ?></th>
						   <th align="center"><i class="fa fa-cog"></i></th>
						</tr>
						
					   
					 </thead>
					 <tbody>
					 <?php foreach($leads as $_items){ $i++; ?>
						<tr>
						   <td><?php echo $i ?></td>
						   <td><?php echo $_items['id'] ?></td>
						   <td><?php echo $_items['company'] ?></td>
						   <td><?php echo $_items['full_name'] ?></td>
						   <td><?php echo $_items['added_on'] ?></td> 
						   <td><?php echo $_items['remark'] ?></td>
						   <td align="center"><a href="<?php echo base_url('admin/lead_requirment/list_view/'.$_items['id']); ?>"  class="add-row btn btn-success">
								<span > View </span>
							</a> <a href="<?php echo base_url('admin/lead_requirment/edit_lead_requirement_data/'.$_items['id']); ?>"  class="add-row btn btn-success">
								<span ><i class="fa fa-pencil-square-o"></i></span>
							</a></td>
						 
						</tr>
					 <?php } ?>
					 </tbody>
				  </table>
				 
			  </div>
	   
    </div>
  </div>
</div>
</div>
</div>

