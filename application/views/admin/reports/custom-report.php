<?php init_head(); 

/* function bd_nice_number($n) {
	// first strip any formatting;
	$n = (0+str_replace(",","",$n));
	
	// is this a number?
	if(!is_numeric($n)) return false;
	
	// now filter it;
	if($n>100000) return round(($n/100000),1).'';
	else if($n>1000) return round(($n/1000),1).'';
	
	return number_format($n);
} */

function bd_nice_number($n) {
	// first strip any formatting; 
	$n = (0+str_replace(",","",$n));
	
	// is this a number?
	if(!is_numeric($n)) return false;
	
	// now filter it;
	if($n) return round(($n),1).'';
	else if($n) return round(($n),1).'';
	
	return number_format($n);
}
			
			
			
?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row">
                  <div class="col-md-12">
				   <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('Report'); ?></h4>
				  </div>
                  <div class="col-md-2 border-right">
                     <br>
                      <p>
                        <a href="#" class="font-medium" onclick="init_report(this,'pipeline-report'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('Order Pipeline'); ?></a>
                      </p>
                    
                     
                      
                  </div>
                 <div class="col-md-2 border-right">
                     <br>
                     
                      <p>
                        <a href="#" class="font-medium" onclick="init_report(this,'winloss-report'); return false;"><i class="fa fa-caret-down" aria-hidden="true"></i> <?php echo _l('Win Loss'); ?></a>
                      </p>
                      
                  </div>
                 
                 <div class="col-md-4 hide">
                  <?php if(isset($currencies)){ ?>
                  <div id="currency" class="form-group hide">
                     <label for="currency"><i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo _l('report_sales_base_currency_select_explanation'); ?>"></i> <?php echo _l('currency'); ?></label><br />
                     <select class="selectpicker" name="currency" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <?php foreach($currencies as $currency){
                           $selected = '';
                           if($currency['isdefault'] == 1){
                              $selected = 'selected';
                           }
                           ?>
                           <option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>><?php echo $currency['name']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <?php } ?>
                     <div id="income-years" class="hide mbot15">
                        <label for="payments_years"><?php echo _l('year'); ?></label><br />
                        <select class="selectpicker" name="payments_years" data-width="100%" onchange="total_income_bar_report();" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php foreach($payments_years as $year) { ?>
                           <option value="<?php echo $year['year']; ?>"<?php if($year['year'] == date('Y')){echo 'selected';} ?>>
                              <?php echo $year['year']; ?>
                           </option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="form-group hide" id="report-time">
                        <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
                        <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                           <option value="this_month"><?php echo _l('this_month'); ?></option>
                           <option value="1"><?php echo _l('last_month'); ?></option>
                           <option value="this_year"><?php echo _l('this_year'); ?></option>
                           <option value="last_year"><?php echo _l('last_year'); ?></option>
                           <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
                           <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
                           <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
                           <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                        </select>
                     </div>
                     <div id="date-range" class="hide mbot15">
                        <div class="row">
                           <div class="col-md-6">
                              <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" disabled="disabled" id="report-to" name="report-to">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
				   <div id="report" class="hide">
					   <hr class="hr-panel-heading" />
					   <h4 class="no-mtop"><?php echo _l('reports_sales_generated_report'); ?></h4>
					   <hr class="hr-panel-heading" />
					   <?php $this->load->view('admin/reports/includes/winloss'); ?>
					   <?php $this->load->view('admin/reports/includes/pipeline'); ?>	
				   </div>
              </div>
			</div>
		</div>
	</div>
</div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/reports/includes/sales_js'); ?>
</body>
</html>
