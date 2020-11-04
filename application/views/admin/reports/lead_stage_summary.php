<?php init_head();

?>
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
   .table>tbody>tr>td, .table>tfoot>tr>td {
    padding: 6px 1px 1px 1px;
    text-align: center;
    border: 1px solid !important;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border: 1px solid #000000 !important;
}

</style>
<div id="wrapper">
   <div class="content">

         <div class="panel_s accounting-template estimate">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     <h4>Stage Summary Reports</h4>
                  </div>
				  <div class="col-md-12">
						<form method="post">
					<div class="col-md-2">
						<h5>Select Region:</h5>
							<select class="selectpicker" name="from-region" id="form-filter-zone" required data-width="100%" data-none-selected-text="Select region" tabindex="-98">
							   <option value="">Select Region</option>
							   <option <?php if($this->input->post('from-region')=='all_region'){ echo 'selected'; } ?>  value="all_region">All Region</option>
								<option <?php if($this->input->post('from-region')=='pan_india'){ echo 'selected'; } ?>  value="pan_india">Regions</option>

							</select>

					</div>
					<div class="col-md-2 leads-filter-column <?php if($this->input->post('from-region') !='pan_india'){ echo 'hidden'; } ?>" id="zone-div">
					  <h5>Zone:</h5>
						 <?php
						 $regions = $this->db->get('tblregion')->result_array();
						  $regionids = implode(',',$this->input->post('region_id'));
						  $explode_id = array_map('intval', explode(',', $regionids));

						 ?>
						<select <?php if(get_staff_role() == 1 || get_staff_role() == 3 || get_staff_role() == 5){ echo 'disabled'; } ?> required name="region_id[]" multiple id="region_id" class="form-control selectpicker" >
							<option value="--Select--">--Select--</option>

							<?php
							$sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
							$region_staff = $this->db->query($sql)->row()->region;

							$arruser = "id IN(".$region_staff.")";
							$this->db->select()->from('tblregion');
							if(get_staff_role() < 9 || !is_admin())
							$this->db->where($arruser);
							$query = $this->db->get();
							$region = $query->result_array();
							foreach ($region as $regiond) {

							?>
							<?php $selected = in_array($regiond['id'],$explode_id) ? " selected " : null; ?>
							<option <?php echo $selected; ?> value="<?php echo $regiond['id'] ?>"><?php echo $regiond['region'] ?></option>
							<?php } ?>
						</select>
					  </div>
					<div class="col-md-4">
						<h5>Select MTD:</h5>
						<div class="col-md-6">
							<select class="selectpicker" name="from-months" required data-width="100%" data-none-selected-text="Select Month" tabindex="-98">
							   <option value="">Select Month</option>
							   <option <?php if($this->input->post('from-months')=='01'){ echo 'selected'; } ?>  value="01">January</option>
								<option <?php if($this->input->post('from-months')=='02'){ echo 'selected'; } ?>  value="02">February</option>
								<option <?php if($this->input->post('from-months')=='03'){ echo 'selected'; } ?>  value="03">March</option>
								<option <?php if($this->input->post('from-months')=='04'){ echo 'selected'; } ?>  value="04">April</option>
								<option <?php if($this->input->post('from-months')=='05'){ echo 'selected'; } ?>  value="05">May</option>
								<option <?php if($this->input->post('from-months')=='06'){ echo 'selected'; } ?>  value="06">June</option>
								<option <?php if($this->input->post('from-months')=='07'){ echo 'selected'; } ?>  value="07">July</option>
								<option <?php if($this->input->post('from-months')=='08'){ echo 'selected'; } ?>  value="08">August</option>
								<option <?php if($this->input->post('from-months')=='09'){ echo 'selected'; } ?>  value="09">September</option>
								<option <?php if($this->input->post('from-months')=='10'){ echo 'selected'; } ?>  value="10">October</option>
								<option <?php if($this->input->post('from-months')=='11'){ echo 'selected'; } ?>  value="11">November</option>
								<option <?php if($this->input->post('from-months')=='12'){ echo 'selected'; } ?>  value="12">December</option>
							</select>

						</div>
						<div class="col-md-6">
							<select class="selectpicker" name="from-years" required data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
							   <option value="">Nothing selected</option>

								<option <?php if($this->input->post('from-years')=='2019'){ echo 'selected'; }else if($this->input->post('from-years')=='') { echo 'selected'; } ?> value="2019">2019</option>
								<option <?php if($this->input->post('from-years')=='2020'){ echo 'selected'; } ?>  value="2020">2020</option>
								<option <?php if($this->input->post('from-years')=='2021'){ echo 'selected'; } ?>  value="2021">2021</option>
								<option <?php if($this->input->post('from-years')=='2022'){ echo 'selected'; } ?>  value="2022">2022</option>
								<option <?php if($this->input->post('from-years')=='2023'){ echo 'selected'; } ?>  value="2023">2023</option>
								<option <?php if($this->input->post('from-years')=='2024'){ echo 'selected'; } ?>  value="2024">2024</option>
							</select>
						</div>
					</div>

					<div class="col-md-2 text-right">
						<br>
						<br>
						<button class="btn btn-success" type="submit">Apply</button>
					</div>

					</form>

				  </div>
				  <div class="col-md-12">
					 <br>

					 <?php
						$regionids = implode(',',$this->input->post('region_id'));
						if($this->input->post('from-months') !=''){
							$filteryear = $this->input->post('from-years');
							$filtermonth = $this->input->post('from-months');
							$curr_month = $filteryear.'-'.$filtermonth;
						}else{
							$curr_month = date('Y-m');
						}

						//echo $curr_month;

						if ( date('m') > 3 ) {
							$year = date('Y') + 1;
						}else {
							$year = date('Y');
						}
						$fromyear = ($year-1);
						$toyear = $year;

						$fromyearmonth = $fromyear.'-04';
						$toyearmonth = $year.'-03';
						$this->db->order_by("id", "asc");

						$leadstage = $this->db->get('tblleadsstatus')->result_array();
			?>
					<table class="table border <?php if($this->input->post('from-region')=='pan_india'){ echo 'hidden'; } ?>" border="1" style="font-size:13px;text-align:center;font-weight: bold;"><tbody>
						<tr align="center">
									<th colspan="20" style="background:#f58a4c">All Regions</th>
								</tr>
						 <tr>
							<th style="text-align:center;width:250px;" bgcolor="#f4b084" rowspan="2" colspan="2" width="150"><strong>Stages</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Total</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Identified</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Qualified</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Alignment &amp; Selection</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Selection</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Contract Signed</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Won</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Lost</strong></th>
							<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Open</strong></th>
						  </tr>
			  <tr>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
				<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>

			 </tr>
		<?php
		$sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
		$region_staff = $this->db->query($sql)->row()->region;
		if($this->input->post('from-region')=='pan_india'){
			$arruser = "id IN(".$regionids.")";
		}else{
			$arruser = "id IN(".$region_staff.")";
		}

		$this->db->select()->from('tblregion');
		if(get_staff_role() < 9 || !is_admin())
		$this->db->where($arruser);

		$query = $this->db->get();
		$regions = $query->result_array();

		$mtd_total_lead = 0; $mtd_total_lead_value = 0; $mtd_identified_lead = 0; $mtd_identified_lead_value = 0;
		$mtd_qualified_lead = 0; $mtd_qualified_lead_value = 0;	$mtd_alignment_lead = 0; $mtd_alignment_lead_value = 0;
		$mtd_finalselection_lead = 0; $mtd_finalselection_lead_value = 0; $mtd_finalcontract_lead = 0;
		$mtd_finalcontract_lead_value = 0;	$mtd_closewon_lead = 0;	$mtd_closewon_lead_value = 0;	$mtd_closeloss_lead = 0;
		$mtd_closeloss_lead_value = 0;

		$mtd_stage_total_lead = 0;	$mtd_stage_total_lead_value = 0;	$mtd_stage_identified_lead = 0;			$mtd_stage_identified_lead_value = 0;	$mtd_stage_qualified_lead = 0;	$mtd_stage_qualified_lead_value = 0;		$mtd_stage_alignment_lead = 0;	$mtd_stage_alignment_lead_value = 0;	$mtd_stage_finalselection_lead = 0;
		$mtd_stage_finalselection_lead_value = 0; $mtd_stage_finalcontract_lead = 0; $mtd_stage_finalcontract_lead_value = 0;
		$mtd_stage_closewon_lead = 0; $mtd_stage_closewon_lead_value = 0; $mtd_stage_closeloss_lead = 0;
		$mtd_stage_closeloss_lead_value = 0;

		$itd_total_lead = 0; $itd_total_lead_value = 0; $itd_closewon_lead = 0; $itd_closewon_lead_value = 0;$itd_closeloss_lead = 0; $itd_closeloss_lead_value = 0;


		foreach ($regions as $region) {

			$mtd_total_lead = $mtd_total_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region['id']);
			$mtd_total_lead_value = $mtd_total_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region['id']);

			$itd_total_lead = $itd_total_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region['id']);
			$itd_total_lead_value = $itd_total_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region['id']);

			$itd_closewon_lead = $itd_closewon_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region['id']);
			$itd_closewon_lead_value = $itd_closewon_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region['id']);
			$itd_closeloss_lead = $itd_closeloss_lead + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region['id']);
			$itd_closeloss_lead_value = $itd_closeloss_lead_value + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region['id']);


			$mtd_stage_identified_lead = $mtd_stage_identified_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region['id']);
			$mtd_stage_identified_lead_value = $mtd_stage_identified_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region['id']);
			$mtd_stage_qualified_lead = $mtd_stage_qualified_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region['id']);
			$mtd_stage_qualified_lead_value = $mtd_stage_qualified_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region['id']);
			$mtd_stage_alignment_lead = $mtd_stage_alignment_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region['id']);
			$mtd_stage_alignment_lead_value = $mtd_stage_alignment_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region['id']);
			$mtd_finalselection_lead = $mtd_finalselection_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region['id']);
			$mtd_finalselection_lead_value = $mtd_finalselection_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region['id']);
			$mtd_finalcontract_lead = $mtd_finalcontract_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region['id']);
			$mtd_finalcontract_lead_value = $mtd_finalcontract_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region['id']);
			$mtd_closewon_lead = $mtd_closewon_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region['id']);
			$mtd_closewon_lead_value = $mtd_closewon_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region['id']);
			$mtd_closeloss_lead = $mtd_closeloss_lead + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region['id']);
			$mtd_closeloss_lead_value = $mtd_closeloss_lead_value + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region['id']);


		}


		?>
			<tr bgcolor="#c6e0b4">
						<th rowspan="3" style="text-align:center;width:120px;">Value</th>
						<th style="text-align:center;width:160px;" ><strong>MTD(<?php echo date('M-y', strtotime($curr_month)); ?>)</strong></th>

						<th style="text-align:center;" ><strong>H<?php echo $mtd_total_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_total_lead_value; ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $mtd_stage_identified_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_stage_identified_lead_value; ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $mtd_stage_qualified_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_stage_qualified_lead_value; ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $mtd_stage_alignment_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_stage_alignment_lead_value; ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $mtd_finalselection_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_finalselection_lead_value; ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $mtd_finalcontract_lead; ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $mtd_finalcontract_lead_value; ?></strong></th>



						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_closewon_lead; ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_closewon_lead_value; ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_closeloss_lead; ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_closeloss_lead_value; ?></strong></th>

						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_total_lead - ($mtd_closewon_lead + $mtd_closeloss_lead); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $mtd_total_lead_value -($mtd_closewon_lead_value + $mtd_closeloss_lead_value); ?></strong></th>


					 </tr>
					<tr bgcolor="#c6e0b4">
						<th style="text-align:center;width:160px;" ><strong>YTD(<?php echo date('y', strtotime($fromyearmonth)); ?>-<?php echo date('y', strtotime($toyearmonth)); ?>)</strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',''); ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],''); ?></strong></th>

						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') - ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'') + $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],'')); ?></strong></th>

						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') - ( $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],'') + $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],'')); ?></strong></th>

					 </tr>
					<tr bgcolor="#c6e0b4">
						<th style="text-align:center;width:160px" ><strong>ITD(May-19)</strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff('',''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff('',''); ?></strong></th>

						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],''); ?></strong></th>
						<th style="text-align:center;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],''); ?></strong></th>
						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],''); ?></strong></th>

						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $itd_total_lead - ( $itd_closewon_lead + $itd_closeloss_lead); ?></strong></th>

						<th style="text-align:center;background:#9ccc7c;" ><strong><?php echo $itd_total_lead_value - ($itd_closewon_lead_value + $itd_closeloss_lead_value); ?></strong></th>

					 </tr>
					 <tr align="center">
						<th colspan="18" style="padding-top:5px;"></th>
					</tr>
					 <tr bgcolor="#ffe699" style="font-style: italic;">
						<th rowspan="3">Stage Wise %</th>
						<td ><strong>MTD(<?php echo date('M-y', strtotime($curr_month)); ?>) </strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<?php
							$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);

							$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100)
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $mtd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $mtd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>YTD (<?php echo date('y', strtotime($fromyearmonth)); ?>-<?php echo date('y', strtotime($toyearmonth)); ?>)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>
						<?php
							$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);

							$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $ytd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $ytd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>ITD (May-19)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('','')/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<?php
							$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 );

							$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100);
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $itd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $itd_pipline_value),0) ; ?> %</td>

					</tr>
		</table>

		<?php
		$sql =  'SELECT region FROM tblstaff WHERE staffid="'.get_staff_user_id().'"';
		$region_staff = $this->db->query($sql)->row()->region;
		if($this->input->post('from-region')=='pan_india'){
			$arruser = "id IN(".$regionids.")";
		}else{
			$arruser = "id IN(".$region_staff.")";
		}
		$this->db->select()->from('tblregion');
		if(get_staff_role() < 9 || !is_admin())
		$this->db->where($arruser);
		$query = $this->db->get();
		$regions = $query->result_array();
		//$regions = $this->db->get('tblregion')->result_array();
		foreach ($regions as $region) {

		?>
		<table class="table border <?php if($this->input->post('from-region') != 'pan_india'){ echo 'hidden'; } ?>" border="1" style="font-size:13px;text-align:center;font-weight: bold;">
		<?php
			echo '<tbody>';
			echo '<tr align="center">';
						if($region['region']=='North'){
			echo '<th colspan="20" bgcolor="#00ffff">'.$region['region'].'</th>';
							}else if($region['region']=='East'){
			echo '<th colspan="20" bgcolor="#ff6699">'.$region['region'].'</th>';
							}else if($region['region']=='South'){
			echo '<th colspan="20" bgcolor="#cc66ff">'.$region['region'].'</th>';
							}else if($region['region']=='West'){
			echo '<th colspan="20" bgcolor="#66ffcc">'.$region['region'].'</th>';
							}
			echo '</tr>';
		?>
			<tr>
					<th style="text-align:center;width:250px;" bgcolor="#f4b084" rowspan="2" colspan="2" width="150"><strong>Stages</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Total</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Identified</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Qualified</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Alignment &amp; Selection</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Selection</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Final Contract Signed</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Won</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Closed Lost</strong></th>
					<th style="text-align:center;" bgcolor="#f4b084" colspan="2"><strong>Open</strong></th>
				  </tr>
				  <tr>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead</strong></th>
					<th style="text-align:center;width:100px" bgcolor="#f4b084"><strong>Lead Value</strong></th>
				  </tr>
		<tr bgcolor="#bdd7ee">
						<th rowspan="3">Value</th>
						<td ><strong>MTD(<?php echo date('M-y', strtotime($curr_month)); ?>) </strong></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) - ( $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) + $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) ); ?></td>



					</tr>

					<tr bgcolor="#bdd7ee">
						<td><strong>YTD (<?php echo date('y', strtotime($fromyearmonth)); ?>-<?php echo date('y', strtotime($toyearmonth)); ?>)</strong></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ( $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) - ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) + $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])); ?></td>


					</tr>


					<tr bgcolor="#bdd7ee">
						<td><strong>ITD (May-19)</strong></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]); ?></td>

						<td style="text-align:center;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]); ?></td>
						<td style="text-align:center;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]); ?></td>

						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])  - ( $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])); ?></td>
						<td style="text-align:center;background:#7cc1ff;"><?php echo $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) - ( $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) + $this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])); ?></td>

					</tr>
					<tr align="center">
						<th colspan="18" style="padding-top:5px;"></th>
					</tr>

					<tr bgcolor="#ffe699" style="font-style: italic;">
						<th rowspan="3">Stage Wise %</th>
						<td ><strong>MTD(<?php echo date('M-y', strtotime($curr_month)); ?>) </strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','') / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100),0) ; ?> %</td>

						<?php
							$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100);

							$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"])*100)
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $mtd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $mtd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>YTD (<?php echo date('y', strtotime($fromyearmonth)); ?>-<?php echo date('y', strtotime($toyearmonth)); ?>)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100),0) ; ?> %</td>

						<?php
							$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);

							$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"])*100);
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $ytd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $ytd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#ffe699" style="font-style: italic;">
						<td ><strong>ITD (May-19)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('','')/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('','') / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"]) *100),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100),0) ; ?> %</td>

						<?php
							$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])*100 );

							$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"])*100);
						?>
						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $itd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#ffd34e;"><?php echo  round((100 - $itd_pipline_value),0) ; ?> %</td>
					</tr>

					<tr align="center">
						<th colspan="18" style="padding-top:5px;"></th>
					</tr>

					<tr bgcolor="#f1dcdc">
						<th rowspan="3">Region Share</th>
						<td ><strong>MTD(<?php echo date('M-y', strtotime($curr_month)); ?>) </strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'',$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[0]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[1]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[2]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[3]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[4]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100),0) ; ?> %</td>

						<?php
							$mtd_pipline_no = ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 ) + ($this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"])/$this->leads_model->mtd_no_of_leads_by_stage_month_staff($curr_month,'','')*100 );

							$mtd_pipline_value = ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[5]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100) + ($this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,$leadstage[6]['id'],$region["id"]) / $this->leads_model->mtd_value_of_leads_by_stage_month_staff($curr_month,'','')*100);
						?>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $mtd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $mtd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#f1dcdc">
						<td ><strong>YTD (<?php echo date('y', strtotime($fromyearmonth)); ?>-<?php echo date('y', strtotime($toyearmonth)); ?>)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','') *100),0) ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'',$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[0]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[1]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[2]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[3]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[4]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100),0) ; ?> %</td>

						<?php
							$ytd_pipline_no = ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 ) + ($this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"])/$this->leads_model->ytd_no_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100 );

							$ytd_pipline_value = ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[5]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100) + ($this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,$leadstage[6]['id'],$region["id"]) / $this->leads_model->ytd_value_of_leads_by_stage_month_staff($fromyearmonth,$toyearmonth,'','')*100);
						?>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $ytd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $ytd_pipline_value),0) ; ?> %</td>

					</tr>
					<tr bgcolor="#f1dcdc">
						<td ><strong>ITD (May-19)</strong></td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff('',$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff('',$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','') *100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[0]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[1]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) ; ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[2]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0 ); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0); ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[3]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0); ?> %</td>

						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','') *100),0)   ; ?> %</td>
						<td style="text-align:center;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[4]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ),0)   ; ?> %</td>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round(($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100),0) ; ?> %</td>

						<?php
							$itd_pipline_no = ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 ) + ($this->leads_model->itd_no_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"])/$this->leads_model->itd_no_of_leads_by_stage_month_staff('','')*100 );

							$itd_pipline_value = ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[5]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100) + ($this->leads_model->itd_value_of_leads_by_stage_month_staff($leadstage[6]['id'],$region["id"]) / $this->leads_model->itd_value_of_leads_by_stage_month_staff('','')*100);
						?>
						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $itd_pipline_no),0)   ; ?> %</td>

						<td style="text-align:center;background:#dec3c3;"><?php echo  round((100 - $itd_pipline_value),0) ; ?> %</td>

					</tr>
				</tbody>
		</table>
		<?php

		    }
		 ?>


                  </div>

			   </div>

            </div>
         </div>
   </div>
</div>
</div>

<?php init_tail(); ?>
<script>
$(document).on('change', '#region_id', function (e) {
    var zone = $(this).val();
	//alert(zone);
	 $('#zone_name').val($("#region_id :selected").text());
	 $('#view_assigned').html("");
   	var base_url = '<?php echo base_url() ?>';
    var div_data = '<option value=""><?php echo '-- Select --'; ?></option>';
   	    $.ajax({
		  type: "GET",
		  url: base_url + "admin/staff/getStaffByZone",
		  data: {'zone': zone},
		  dataType: "json",
		  success: function (data) {
		     console.log(data);
			  $.each(data, function (i, obj){
				  div_data += "<option value=" + obj.staffid + ">" + obj.firstname+' '+ obj.lastname+' - '+obj.emp_code +"</option>";
				});
			  $('#view_assigned').append(div_data);
		  }
		});
  });

  $('form').submit(function () {
		 $('select').removeAttr('disabled');

	});
</script>
<script type="text/javascript">
   $(document).ready(function () {
  //$("#region_id").prop("disabled", true);

   $('#form-filter-zone').change(function(){

   	   if($('#form-filter-zone').val() =='all_region') {
			$("#region_id").prop("required", false);
			//$("#region_id").prop("disabled", true);
			$("#zone-div").addClass('hidden');
	   }else if($('#form-filter-zone').val() =='pan_india'){
			$("#region_id").prop("required", true);
			//$("#region_id").prop("disabled", false);
			$("#zone-div").removeClass('hidden');
	   }else{
			$("#region_id").prop("required", false);
			//$("#region_id").prop("disabled", true);
			$("#zone-div").addClass('hidden');
	   }

   });

   });
   </script>
</body>
</html>
