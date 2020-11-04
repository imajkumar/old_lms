<?php if(!isset($column)){
  $column = 'col-md-5ths';
}
?>
<div class="staff_logged_time">
 <div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
   <div class="panel_s">
    <div class="panel-body">
     <h3 class="text-muted _total">
       <?php echo seconds_to_time_format($logged_time['total']); ?>
     </h3>
     <span class="staff_logged_time_text text-success"><?php echo _l('staff_stats_total_logged_time'); ?></span>
   </div>
 </div>
</div>
<div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
 <div class="panel_s">
  <div class="panel-body">
   <h3 class="text-muted _total">
     <?php echo seconds_to_time_format($logged_time['last_month']); ?>
   </h3>
   <span class="staff_logged_time_text text-default"><?php echo _l('staff_stats_last_month_total_logged_time'); ?></span>
 </div>
</div>
</div>
<div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
 <div class="panel_s">
  <div class="panel-body">
   <h3 class="text-muted _total">
    <?php echo seconds_to_time_format($logged_time['this_month']); ?>
  </h3>
  <span class="staff_logged_time_text text-success"><?php echo _l('staff_stats_this_month_total_logged_time'); ?></span>
</div>
</div>
</div>
<div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
 <div class="panel_s">
  <div class="panel-body">
   <h3 class="text-muted _total">
     <?php echo seconds_to_time_format($logged_time['last_week']); ?>
   </h3>
   <span class="staff_logged_time_text text-default"><?php echo _l('staff_stats_last_week_total_logged_time'); ?></span>
 </div>
</div>
</div>
<div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
 <div class="panel_s">
  <div class="panel-body">
   <h3 class="text-muted _total">
     <?php echo seconds_to_time_format($logged_time['this_week']); ?>
   </h3>
   <span class="staff_logged_time_text text-success"><?php echo _l('staff_stats_this_week_total_logged_time'); ?></span>
 </div>
</div>
</div>
</div>

<div class="staff_logged_time">
<?php
foreach($list_customer_type as $time_leads)

{
?>


 <div class="<?php echo $column; ?> col-sm-6 col-xs-12 total-column">
   <div class="panel_s">
    <div class="panel-body">
     <h3 class="text-muted _total">
      <?php

		$query = $this->db->query("SELECT sum(opportunity) as opportunity_total,count(id) AS num_of_record FROM tblleads where customer_type ='".$time_leads['code']."' ");
        echo $query->row()->opportunity_total." - ".$query->row()->num_of_record;
        
		 

	  //echo $this->db->get_where('tblleads', array('customer_type' => $time_leads['code']))->row()->opportunity; ?> 
     </h3>
     <span class="staff_logged_time_text text-success"><?php echo $time_leads['name']; ?></span>
   </div>
 </div>
</div>
<?php } ?>

</div>




<div class="clearfix"></div>
