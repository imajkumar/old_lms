<?php
defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('leads','','delete');
$custom_fields = get_table_custom_fields('leads');
$aColumns     = array(
    '1',
    'tblleads.id as id',
    'tblleads.name as name',
    'tblleads.reportingto as reportingto','tblleads.state as state',
    'tblleads.company as leaddesc',
    'tblleads.description as description',
	'tblcustomer_type.code as customer_type',
	'tblcustomer_type.name as customer_tname',
    'tblleads.email as email',
    'tblleads.status as lstatus',
    'tblleads.document_due_date as document_due_date',
	'tblleads.project_manager_approval as project_manager_approval',
	'tblleads.assproject_manager_approval as assproject_manager_approval',
    'tblleads.phonenumber as phonenumber','opportunity',
    'CONCAT(tblstaff.firstname, \' \', tblstaff.lastname) as assigned_name',
    'tblleadsstatus.name as status_name',
    'tblclients.company as companyname',
    'tblleadssources.name as source_name',
    'tblregion.region as region_name', 'tblcustomersgroups.name as group_name',
    'lastcontact',
    'dateassigned',
    'dateadded'
    );

$sIndexColumn = "id";
$sTable       = 'tblleads';

$join = array(
    'LEFT JOIN tblstaff ON tblstaff.staffid = tblleads.assigned',
    'LEFT JOIN tblleadsstatus ON tblleadsstatus.id = tblleads.status',
    'LEFT JOIN tblleadssources ON tblleadssources.id = tblleads.source',
	'LEFT JOIN tblclients ON tblclients.userid = tblleads.customer_name',
	'LEFT JOIN tblcustomer_type ON tblcustomer_type.code = tblleads.customer_type',
	'LEFT JOIN tblcustomersgroups ON tblcustomersgroups.id = tblleads.customer_group',
    'LEFT JOIN tblregion ON tblregion.id = tblleads.region',
);

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_'.$key);
    array_push($customFieldsColumns,$selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $key . ' ON tblleads.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where = array();
$order = array();
$filter = false;

if (get_staff_role() > 8) {
    array_push($where, 'AND tblleads.is_public = 1');
}
else if (get_staff_role() == 1) {
    array_push($where, 'AND tblleads.assigned =' . get_staff_user_id() . '');
}
else if(get_staff_role() == 2) 
{
	array_push($where, 'AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned ='. get_staff_user_id() .')');
}
else if(get_staff_role() == 5 || get_staff_role() == 3) 
{
	array_push($where, 'AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned ='. get_staff_user_id() .')');
}
else if(get_staff_role() == 6) 
{
	array_push($where, 'AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned ='. get_staff_user_id() .')');
}
else if(get_staff_role() == 8) 
{
	array_push($where, 'AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned ='. get_staff_user_id() .')');
}
else if(get_staff_role() == 7 || get_staff_role() == 4) 
{
	array_push($where, 'AND tblleads.state IN('. trim(get_staff_state_id(),",") .')');
}
if ($this->ci->input->post('assigned')) {
    array_push($where, 'AND assigned =' . $this->ci->input->post('assigned'));
}
if($this->ci->input->post('status') && count($this->ci->input->post('status')) > 0 ) {
	array_push($where, 'AND tblleads.status IN ('.implode(',',$this->ci->input->post('status')).')');
}
if ($this->ci->input->post('source')) {
    array_push($where, 'AND source =' . $this->ci->input->post('source'));
}
if ($this->ci->input->post('view_customer_group')) {
    array_push($where, 'AND customer_group =' . $this->ci->input->post('view_customer_group'));
}
if ($this->ci->input->post('pm_approval_status')) {
    array_push($where, 'AND project_manager_approval IN ('.implode(',',$this->ci->input->post('pm_approval_status')).')');
}
if ($this->ci->input->post('report_months')) {
	if ($this->ci->input->post('report_months') =='this_month') {
		$month = date('Y-m');
		array_push($where, 'AND dateadded LIKE ("'.$month.'%")');
	}else if($this->ci->input->post('report_months') =='last_month') {
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		array_push($where, 'AND dateadded LIKE ("'.$month.'%")');
	}else if($this->ci->input->post('report_months') =='this_year') {
		$year = date('Y');
		array_push($where, 'AND dateadded LIKE ("'.$year.'%")');
	}else if($this->ci->input->post('report_months') =='last_year') {
		$year = date('Y', strtotime(date('Y')." -1 year"));
		array_push($where, 'AND dateadded LIKE ("'.$year.'%")');
	}else if($this->ci->input->post('report_months') =='report_sales_months_three_months') {
		$report_from = date('Y-m-01', strtotime("-2 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else if($this->ci->input->post('report_months') =='report_sales_months_six_months') {
		$report_from = date('Y-m-01', strtotime("-5 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else if($this->ci->input->post('report_months') =='report_sales_months_twelve_months') {
		$report_from = date('Y-m-01', strtotime("-11 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else if($this->ci->input->post('report_months') =='till_last_month') {
		$report_from = date('2019-04-01');
		
		$report_to= date('Y-m-d', strtotime("last day of -1 month"));
		array_push($where, 'AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}
	
}if ($this->ci->input->post('report_to')) {
	$report_from = $this->ci->input->post('report_from');
	$report_to= $this->ci->input->post('report_to');
	array_push($where, 'AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
}

if ($this->ci->input->post('as_approval_status')) {
  array_push($where, 'AND assproject_manager_approval IN ('.implode(',',$this->ci->input->post('as_approval_status')).')');
}

if ($this->ci->input->post('region')) {
    array_push($where, 'AND region =' . $this->ci->input->post('region'));
}


$aColumns = do_action('leads_table_sql_columns', $aColumns);

 /*  print_r($where);exit;  */
  
// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblleads.assigned',
    'tblleads.addedfrom as addedfrom',
    'tblleads.dateassigned'
));

$output  = $result['output'];
$rResult = $result['rResult'];
$i = 0;



foreach ($rResult as $aRow) {
    $row = array();

   
	
    $row[] = '<span  data-toggle="tooltip" data-title="'.$aRow['id'].'">'.$aRow['id'].'</span>';
	
	$timestamp1 = strtotime($aRow['dateadded']);
	$date1 = date("d-m-Y", $timestamp1);
	
	
    $row[] = '<span  data-toggle="tooltip" data-title="'._dt($aRow['dateadded']).'">'.$date1.'</span>';
	$row[] = $aRow['group_name'];
	$row[] = $aRow['companyname'];
	$customerOutput = '<span data-toggle="tooltip" data-title="'.$aRow['customer_tname'].'">' . $aRow['customer_type'] . '</span>';
	
    $row[] = $customerOutput;
	
    $row[] = '<a href="'.admin_url('leads/index/'.$aRow['id']).'" onclick="init_lead('.$aRow['id'].');return false;"><span data-toggle="tooltip" data-title="'.$aRow['description'].'">'. $aRow['leaddesc'] . '</span></a>';

	$row[] = format_money($aRow['opportunity'], ($aRow['currency'] != 0 ? $this->ci->currencies_model->get_currency_symbol($aRow['currency']) : $baseCurrencySymbol));
    
	
    if ($aRow['status_name'] == null) {
        if ($aRow['lost'] == 1) {
            $statusOutput = '<span >' . _l('lead_lost') . '</span>';
        } elseif ($aRow['junk'] == 1) {
            $statusOutput = '<span>' . _l('lead_junk') . '</span>';
        }
    } else {
        $statusOutput = '<span class="'.(!$this->ci->input->post('status') ? ' pointer lead-status' : '').' label-' . (empty($aRow['color']) ? '': '') . '" style="color:' . $aRow['color'] . '; ' . $aRow['color'] . '">' . $aRow['status_name'] . '</span>';
    }

    $row[] = $statusOutput;

    $timestamp2 = strtotime($aRow['document_due_date']);
	$date2 = date("d-m-Y", $timestamp2);
	if($date2 =='01-01-1970'){
		$row[] = '-';
	}else{
		$row[] = $date2; 
	}
  
	
	
	$project_manager = '';
	if($aRow['lstatus'] < 3 )
	{
		$project_manager = '<span class="label label-default inline-block" style="color:#000;border:0px;">-</span>';
	}
	else if ($aRow['project_manager_approval'] == 0) {
            $project_manager = '<span class="label label-default inline-block" style="color:#000;border:0px;">' . _l('Pending') . '</span>';
    }else if ($aRow['project_manager_approval'] == 1) {
            $project_manager = '<span class="label label-default inline-block" style="color:#000;border:0px;">Approved</span>';
    }else{
            $project_manager = '<span class="label label-default inline-block" style="color:#000;border:0px;">' . _l('Partial Approved') . '</span>';
    }
	
	$row[] = $project_manager;
	 $assproject = '';
	if($aRow['lstatus'] < 3 )
	{
		$assproject = '<span class="label label-default inline-block" style="color:#000;border:0px;">-</span>';
	}
	else if ($aRow['assproject_manager_approval'] == 0) {
            $assproject = '<span class="label label-default inline-block" style="color:#000;border:0px;">' . _l('lead_pending') . '</span>';
    }else if ($aRow['assproject_manager_approval'] == 1) {
            $assproject = '<span class="label label-default inline-block" style="color:#000;border:0px;">Approved</span>';
    } else{
            $assproject = '<span class="label label-default inline-block" style="color:#000;border:0px;">' . _l('Partial Approved') . '</span>';
    }
	$row[] = $assproject;
	
	$assignedOutput = '';
    if ($aRow['assigned'] != 0) {
        $full_name = $aRow['assigned_name'];
        $assignedOutput = '<a data-toggle="tooltip" href="#" data-title="'.$full_name.'"  href="'.admin_url('profile/'.$aRow['assigned']).'" >'.$full_name. '</a>';
	}

    $row[] = $assignedOutput;

	
	$timestamp = strtotime($aRow['dateassigned']);
	$date = date("d-m-Y", $timestamp);
	$row[] = '<span data-toggle="tooltip" data-title="'._dt($aRow['dateassigned']).'">'.$date.'</span>';
 
     // Custom fields add values
    foreach($customFieldsColumns as $customFieldColumn){
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $hook_data = do_action('leads_table_row_data', array(
        'output' => $row,
        'row' => $aRow
    ));

    $row = $hook_data['output'];
    
    $options = '';

    $options .= '<a class="btn btn-default btn-icon" href="'.admin_url('leads/index/'.$aRow['id']).'" onclick="init_lead('.$aRow['id'].');return false;"><i  class="fa fa-eye" style="color"></i></a>';
    
	if($aRow['lstatus'] == 7 || $aRow['lstatus'] == 6)
	{
		$options .= '<a class="btn btn-default btn-icon" href="'.admin_url('leads/manage/'.$aRow['id']).'"><i class="fa fa-ban"></i></a>';
	}else if($aRow['assigned'] == get_staff_user_id()){
		
		$options .= '<a class="btn btn-default btn-icon" href="'.admin_url('leads/manage/'.$aRow['id']).'"><i class="fa fa-pencil-square-o"></i></a>';
	
	}
    if ($aRow['addedfrom'] == get_staff_user_id() || $has_permission_delete) {
       // $options .= icon_btn('leads/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    }

    $row[] = $options;
    $row['DT_RowId'] = 'lead_'.$aRow['id'];

    if ($aRow['assigned'] == get_staff_user_id()) {
        $row['DT_RowClass'] = 'alert-info';
    }


    $output['aaData'][] = $row;
	
}

