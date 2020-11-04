<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="lead-form">
      <form id="w0" class="form-vertical" action="<?php echo base_url('admin/leads/update_customer/'.$customer_type_value['id']);?> " method="post" role="form">
         <input type="hidden" name="_csrf" value="">
         <div class="panel panel-info">
          
            <div class="panel-body">
               <fieldset id="w1">
                  <div class="row">
				   <div class="col-sm-6">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name"><h4>Customer Name</h4></label>
						        
                           <input type="text"   class="form-control " name="customer" maxlength="255" placeholder="Enter customer..."  value="<?php echo $customer_type_value['customer_name']; ?>">
						   
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div> 
					 <div class="col-sm-6">
                        <div class="form-group field-lead-lead_name required">
                           <label class="control-label" for="lead-lead_name"><h4>Customer Type Code</h4></label>
						        
                 <input type="text"  autocomplete="off" class="form-control " name="customer_type_code" maxlength="255" placeholder="Enter customer..." aria-required="true" value="<?php echo $customer_type_value['customer_type_code']; ?>">
						   
						   
						   
                           <div class="help-block"></div>
                        </div>
                     </div>
				       
				 </div>
               </fieldset>
             
		   </div>
         </div>
         

		<button type="submit" name="submit" class="btn btn-primary lead_submit">Update</button>
      </form>
   </div>
   <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>



</body>
</html>