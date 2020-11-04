<ul class="nav nav-tabs" role="tablist">
   <li role="presentation" class="active">		<a href="#email_config" aria-controls="email_config" role="tab" data-toggle="tab"><?php echo 'LMS Daily Status Reports'; ?></a>	</li>
   <li role="presentation">		<a href="#email_queue" aria-controls="email_queue" role="tab" data-toggle="tab"><?php echo 'Stages Wise Report'; ?></a>	</li>
   <li role="presentation">		<a href="#email_summary" aria-controls="email_summary" role="tab" data-toggle="tab"><?php echo 'Stage Summary Report'; ?></a>	</li>
   <li role="presentation">		<a href="#email_winloss" aria-controls="email_winloss" role="tab" data-toggle="tab"><?php echo 'Win/Loss Report'; ?></a>	</li>
</ul>
<div class="tab-content">
   <div role="tabpanel" class="tab-pane active" id="email_config">
      <a href="<?php echo admin_url('misc/run_cron_lmsdaily'); ?>" class="btn btn-info pull-right"><?php echo 'Mail Send'; ?></a><br>
      <hr>
      <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->		<?php echo render_input('settings[daily_status_reports_to]','Email To',get_option('daily_status_reports_to')); ?>		<?php echo render_input('settings[daily_status_reports_cc]','Email CC',get_option('daily_status_reports_cc')); ?>			
   </div>
   <div role="tabpanel" class="tab-pane" id="email_queue">
      <a href="<?php echo admin_url('misc/run_cron_stage'); ?>" class="btn btn-info pull-right"><?php echo 'Mail Send'; ?></a><br>
      <hr>
      <?php 		//echo get_option('stages_wise_daily_reports_to');	?>		<!--<?php render_yes_no_option('email_stages_wise_daily_reports_staff','email_stages_wise_daily_reports_staff','If yes than stages wise daily reports also send to the staff member'); ?>-->		<?php echo render_input('settings[stages_wise_daily_reports_to]','Email To',get_option('stages_wise_daily_reports_to')); ?>		<?php echo render_input('settings[stages_wise_daily_reports_cc]','Email CC',get_option('stages_wise_daily_reports_cc')); ?>					
   </div>
   <div role="tabpanel" class="tab-pane" id="email_summary">
      <a href="<?php echo admin_url('misc/run_cron_stagesummary'); ?>" class="btn btn-info pull-right"><?php echo 'Mail Send'; ?></a><br>
      <hr>
      <?php echo render_input('settings[stages_summary_daily_reports_to]','Email To',get_option('stages_summary_daily_reports_to')); ?>		<?php echo render_input('settings[stages_summary_daily_reports_cc]','Email CC',get_option('stages_summary_daily_reports_cc')); ?>					
   </div>
   <div role="tabpanel" class="tab-pane" id="email_winloss">
      <a href="<?php echo admin_url('misc/run_cron_winloss'); ?>" class="btn btn-info pull-right"><?php echo 'Mail Send'; ?></a><br>
      <hr>
      <?php echo render_input('settings[winloss_daily_reports_to]','Email To',get_option('winloss_daily_reports_to')); ?>		<?php echo render_input('settings[winloss_daily_reports_cc]','Email CC',get_option('winloss_daily_reports_cc')); ?>					
   </div>
</div>