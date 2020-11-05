

<div id="wrapper">
	<div class="content">
	<div class="col-md-12 small-table-right-col">
  <div id="estimate" class="">
<input type="hidden" name="_attachment_sale_id" value="2">

<input type="hidden" name="_attachment_sale_type" value="estimate">
<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
        <h3>Details</h3>
		 <div class="row">
            
			
			
			<div class="col-md-9">
              
               <div class="pull-right _buttons">
                                    <a href="" class="btn btn-default btn-with-tooltip hide" data-toggle="tooltip" title="Edit Estimate" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                      
                  <div class="btn-group">
                  
				  </div>
                                                      <div class="btn-group pull-right mleft5">
                   

				 </div>
                                                   </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <hr class="hr-panel-heading">
        <div role="tabpanel" class="tab-pane ptop10 active" id="tab_estimate">
               <div id="estimate-preview">
			   
                  <div class="row">
				  
                      

				   <div class="col-sm-6 text-right">
                     																												</div>
																																			 																													
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                           <table class="table items estimate-items-preview">
                              <thead>
                                 <tr>
								 <th align="left">#</th>
                                    <th align="left">Category</th>
                                    <th class="description">Item</th>
                                     <th align="">Qty</th>
                                    <th>Required Price</th><th>Document Required</th>
                                    <th>Proposed Item</th>
                                                                        <th>Proposed Price</th>
                                 </tr>
                              </thead>
                              <tbody class="ui-sortable">
							  <?php 
							  foreach($detail_data as $detail ){
							  $i++
							  
							  ?>
                                 <tr class="sortable" data-item-id="2">
								<td><?php echo $i ?></td>
								 <td><?php echo $detail['name']; ?></td> 
								 
								 <td><?php echo $detail['description']; ?></td>
								 <td><?php echo $detail['quantity']; ?></td>	 
								 <td><?php echo $detail['rate']; ?></td>
								 <td><?php echo $detail['document']; ?></td>
								 <td><?php echo $detail['description']; ?></td>	
								 <td><?php echo $detail['proposed_item_qty']; ?></td>
								 
								 
								 
								 
								 
								 </tr> 
							  <?php } ?>								 </tbody>
                           </table>
                        </div>
                     </div>
                     
                                                                                 </div>
               </div>
            </div>
	  
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
