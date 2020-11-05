<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body" style="overflow-x: auto;">
						<div class="dt-loader"></div>
						<?php $this->load->view('admin/utilities/calendar_filters'); ?>
						<div id="calendar"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<script>
google_api = '<?php echo $google_api_key; ?>';
calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<script>
 $(document).on('change', '#customer_group', function (e) {
    $('#my_l_searchd').html("");
	$('#rel_id').html("");
	$('#assigned_to').html("");
    var customer_group = $(this).val();
   	var adminurl = "<?php echo base_url(); ?>/admin/clients/client?cid="+customer_group;
   	$("#add_client_data").attr("href", adminurl);
     	
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo "-- Select --"; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getCustomerByGroupCal",
		  data: {'customer_group': customer_group},
		  dataType: "json",
		  success: function (data) {	
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
				});
			  $('#my_l_searchd').append(div_data);
		  }
		});
  });
  
   $(document).on('change', '#my_l_searchd', function (e) {
    $('#rel_id').html("");
	$('#assigned_to').html("");
    var customer = $(this).val();
   	 	
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo "-- Select --"; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getLeadByCustomerCal",
		  data: {'customer': customer},
		  dataType: "json",
		  success: function (data) {	
			  $.each(data, function (i, obj){
				
				  div_data += "<option value=" + obj.id + ">#" + obj.id + " " + obj.company + "</option>";
				});
			  $('#rel_id').append(div_data);
		  }
		});
  });
 
  
  $('#rel_id').change(function(){
     var lead_id =  $(this).val(); 
     //populate the rate.
    $('#assigned_to').html("");
	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getTechnicalByLead",
		  data: {'lead_id': lead_id},
		  dataType: "json",
		  success: function (data) {	
				console.log(data);
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.staffid + ">" + obj.firstname +" "+ obj.lastname + "</option>";
				});
			  $('#assigned_to').append(div_data);
		  }
		});
	
});
   
   
//----------------------------- task -------------------------------//

  var user_id = '';
 
  $(document).on('change', '#view_assigned', function (e) {
    $('#customer_group1').html("");
    var user = $(this).val();
    user_id = $(this).val();
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo "-- Select --"; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getUserCustomerGroup",
		  data: {'user': user},
		  dataType: "json",
		  success: function (data) {	
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
				});
			  $('#customer_group1').append(div_data);
		  }
		});
  });
  
  $(document).on('change', '#customer_group1', function (e) {
    $('#customer1').html("");
    var customer_group = $(this).val();
    
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo "-- Select --"; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getCustomerByGroupUser",
		  data: {'user_id': user_id,'customer_group': customer_group},
		  dataType: "json",
		  success: function (data) {	
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.id + ">" +obj.id+" - "+ obj.name + "</option>";
				});
			  $('#customer1').append(div_data);
		  }
		});
  });
  
   $(document).on('change', '#customer1', function (e) {
    $('#rel_id1').html("");
    var customer = $(this).val();
   	 	
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/leads/getLeadByCustomerStaff",
		  data: {'customer': customer},
		  dataType: "json",
		  success: function (data) {	
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.id + ">" +obj.id+" - "+ obj.company + "</option>";
				});
			  $('#rel_id1').append(div_data);
		  }
		});
  });
     
	 
	$(document).ready(function(){
	   var start='';
	   var calendar_data_type='';
	   var activeTab = null;
	   $('a[data-toggle="tab"]').on('show.bs.tab', function (event) {
		 activeTab = $(event.target).attr("href");
	   });
	  $('form').submit(function(e){
		  
		  $("#calendar_submit").attr("disabled", "disabled");
		  var data = $(this).serialize();
		  
		  var dataObj = {};
		  var fields = $(this).serializeArray();
		  $.each(fields, function(i, field){
			   dataObj[field.name] = field.value;
		  });
		  console.log(dataObj);
		  var dateString = dataObj['start'];
		  var now = new Date();
		  var date = Date.parse(dateString+':00');
			if (date <= Date.parse(now)) {
				alert('Please enter the current or future date & time!');
				$("#calendar_submit").removeAttr("disabled");
				return false;
			}else{
			  var base_url = '<?php echo base_url() ?>';
			  $.ajax({
				  type: "POST",
				  url: base_url + "admin/utilities/calendar_form_data",
				  data: data,
				  dataType: "json",
				  success: function (data) {	
					  alert(data['message']);
					  location.reload();
					}
				});
				location.reload();
			}
	  });

	}); 
var default_date=  new Date(); // or your date

$('.datetimepicker').datetimepicker({
    format:'Y-m-d H:i',
    step:15,
	useCurrent: false,
	defaultDate: default_date

});
</script> 
</body>
</html>
