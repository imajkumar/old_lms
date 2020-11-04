<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
$hasPermissionDelete = has_permission('customers', '', 'delete');
$hasPermissionApproved = has_permission('customers', '', 'approval');

$custom_fields = get_table_custom_fields('customers');

$aColumns = array(
    '1',
    'tblclients.userid as userid',  'tblclients.company as company_name', 
    'tblclients.datetime as datetime',
    'CONCAT(tblcontacts.firstname, " ", tblcontacts.lastname) as contact_fullname',
    'CONCAT(tblstaff.firstname, " ", tblstaff.lastname) as assign_fullname',
	'tblcontacts.email as emailid','addedfrom','reportingto',
    'tblcontacts.phonenumber as phonenumber',
	'tblclients.remark as remark',
    'tblcontacts.mobilenumber as mobilenumber',
    'tblclients.active','tblclients.approve',
    '(SELECT GROUP_CONCAT(name ORDER BY name DESC) FROM tblcustomersgroups LEFT JOIN tblcustomergroups_in ON tblcustomergroups_in.groupid = tblcustomersgroups.id WHERE customer_id = tblclients.userid) as groups'
    );

$sIndexColumn = "userid";

$sTable       = 'tblclients';
$where   = array();
// Add blank where all filter can be stored
$filter  = array();

$join = array(
'LEFT JOIN tblstaff ON tblstaff.staffid = tblclients.addedfrom',
    
'LEFT JOIN tblcontacts ON tblcontacts.userid=tblclients.userid AND tblcontacts.is_primary=1', 

);

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_'.$key);
    array_push($customFieldsColumns,$selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $key . ' ON tblclients.userid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}
// Filter by custom groups
$groups  = $this->ci->clients_model->get_groups();

$groupIds = array();
foreach ($groups as $group) {
    if ($this->ci->input->post('customer_group_' . $group['id'])) {
        array_push($groupIds, $group['id']);
    }
}
if (count($groupIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT customer_id FROM tblcustomergroups_in WHERE groupid IN (' . implode(', ', $groupIds) . '))');
}

$countries  = $this->ci->clients_model->get_clients_distinct_countries();
$countryIds = array();
foreach ($countries as $country) {
    if ($this->ci->input->post('country_' . $country['country_id'])) {
        array_push($countryIds, $country['country_id']);
    }
}
if (count($countryIds) > 0) {
    array_push($filter, 'AND country IN ('.implode(',',$countryIds).')');
}


$this->ci->load->model('invoices_model');
// Filter by invoices
$invoiceStatusIds = array();
foreach ($this->ci->invoices_model->get_statuses() as $status) {
    if ($this->ci->input->post('invoices_' . $status)) {
        array_push($invoiceStatusIds, $status);
    }
}
if (count($invoiceStatusIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT clientid FROM tblinvoices WHERE status IN (' . implode(', ', $invoiceStatusIds) . '))');
}

// Filter by estimates
$estimateStatusIds = array();
$this->ci->load->model('estimates_model');
foreach ($this->ci->estimates_model->get_statuses() as $status) {
    if ($this->ci->input->post('estimates_' . $status)) {
        array_push($estimateStatusIds, $status);
    }
}
if (count($estimateStatusIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT clientid FROM tblestimates WHERE status IN (' . implode(', ', $estimateStatusIds) . '))');
}

// Filter by projects
$projectStatusIds = array();
$this->ci->load->model('projects_model');
foreach ($this->ci->projects_model->get_project_statuses() as $status) {
    if ($this->ci->input->post('projects_' . $status['id'])) {
        array_push($projectStatusIds, $status['id']);
    }
}
if (count($projectStatusIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT clientid FROM tblprojects WHERE status IN (' . implode(', ', $projectStatusIds) . '))');
}

// Filter by proposals
$proposalStatusIds = array();
$this->ci->load->model('proposals_model');
foreach ($this->ci->proposals_model->get_statuses() as $status) {
    if ($this->ci->input->post('proposals_' . $status)) {
        array_push($proposalStatusIds, $status);
    }
}
if (count($proposalStatusIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT rel_id FROM tblproposals WHERE status IN (' . implode(', ', $proposalStatusIds) . ') AND rel_type="customer")');
}

// Filter by having contracts by type
$this->ci->load->model('contracts_model');
$contractTypesIds = array();
$contract_types  = $this->ci->contracts_model->get_contract_types();

foreach ($contract_types as $type) {
    if ($this->ci->input->post('contract_type_' . $type['id'])) {
        array_push($contractTypesIds, $type['id']);
    }
}
if (count($contractTypesIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT client FROM tblcontracts WHERE contract_type IN (' . implode(', ', $contractTypesIds) . '))');
}

// Filter by proposals
$customAdminIds = array();
foreach ($this->ci->clients_model->get_customers_admin_unique_ids() as $cadmin) {
    if ($this->ci->input->post('responsible_admin_' . $cadmin['staff_id'])) {
        array_push($customAdminIds, $cadmin['staff_id']);
    }
}

if (count($customAdminIds) > 0) {
    array_push($filter, 'AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id IN (' . implode(', ', $customAdminIds) . '))');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (!has_permission('customers', '', 'view')) {
    array_push($where, 'AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')');
}


if($this->ci->input->post('activerec')){
    array_push($where,'AND tblclients.active=1');
}
if($this->ci->input->post('inactive')){
    array_push($where,'AND tblclients.active=0');
}
if($this->ci->input->post('approved')){
    array_push($where,'AND tblclients.approve=1');
}
if($this->ci->input->post('unapproved')){
	
	array_push($where,'AND tblclients.approve=0');
	
}
if($this->ci->input->post('resubmit')){
    array_push($where,'AND tblclients.approve=2');
}
if($this->ci->input->post('reject')){
    array_push($where,'AND tblclients.approve=3');
}
if($this->ci->input->post('report_months')){
	if ($this->ci->input->post('report_months') =='this_month') {
		$month = date('Y-m');
		array_push($where, 'AND datetime LIKE ("'.$month.'%")');
	}else if($this->ci->input->post('report_months') =='last_month') {
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		array_push($where, 'AND datetime LIKE ("'.$month.'%")');
	}else if($this->ci->input->post('report_months') =='this_year') {
		$year = date('Y');
		array_push($where, 'AND datetime LIKE ("'.$year.'%")');
	}else if($this->ci->input->post('report_months') =='last_year') {
		$year = date('Y', strtotime(date('Y')." -1 year"));
		array_push($where, 'AND datetime LIKE ("'.$year.'%")');
	}else if($this->ci->input->post('report_months') =='report_sales_months_three_months') {
		$report_from = date('Y-m-01', strtotime("-2 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (datetime BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else if($this->ci->input->post('report_months') =='report_sales_months_six_months') {
		$report_from = date('Y-m-01', strtotime("-5 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (datetime BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}else if($this->ci->input->post('report_months') =='report_sales_months_twelve_months') {
		$report_from = date('Y-m-01', strtotime("-11 MONTH"));
		$report_to= date('Y-m-d');
		array_push($where, 'AND (datetime BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}
   
}
if ($this->ci->input->post('report_from')) {
	$report_from = $this->ci->input->post('report_from');
	$report_to= $this->ci->input->post('report_to');
	if($report_to != ''){
		array_push($where, 'AND (datetime BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
	}
}
if ($this->ci->input->post('report_to')) {
	$report_from = $this->ci->input->post('report_from');
	$report_to= $this->ci->input->post('report_to');
	array_push($where, 'AND (datetime BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )');
}

if ($this->ci->input->post('assigned')) {
    array_push($where, 'AND addedfrom =' . $this->ci->input->post('assigned'));
}

if (is_admin()) {
	//array_push($where, 'AND tblclients.addedfrom IN('. get_staff_user_id() .')');
}
else if (get_staff_role() == 1) {
	array_push($where, 'AND tblclients.addedfrom IN('. get_staff_user_id() .')');
}
else if(get_staff_role() == 7 || get_staff_role() == 4) 
{
	array_push($where, 'AND tblclients.state IN('. get_staff_state_id() .')');
}
else if((get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) && get_staff_role() <= 8) 
{
	array_push($where, 'AND (CONCAT(",", tblclients.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblclients.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblclients.addedfrom IN('. get_staff_user_id() .'))');
}else if(get_staff_role() > 8) 
{
	array_push($where, 'AND tblclients.state IN('. get_staff_state_id() .')');
}


	

if($this->ci->input->post('my_customers')){
    array_push($where,'AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id='.get_staff_user_id().')');
}

$aColumns = do_action('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}
//print_r($where);	
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblcontacts.id as contact_id',
    'tblclients.zip as zip'
));

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = array();

    // Bulk actions
  /*   $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['userid'] . '"><label></label></div>'; */
   $user_id ='';
    

    if ($aRow['userid']) {
        $userid = explode(',', $aRow['userid']);
        foreach ($userid as $user) {
            $user_id .= '<span class="label mleft5 inline-block customer-group-list pointer" style="color:#008ece;">' . $user . '</span>';
        }
    }
   $row[] = $user_id;
    // Customer groups parsing
    $groupsRow  = '';
    if ($aRow['groups']) {
        $groups = explode(',', $aRow['groups']);
        foreach ($groups as $group) {
            $groupsRow .= $group;
        }
    }

    $row[] = $groupsRow;
	
	 // Company
    $company = $aRow['company_name'];

    if ($company == '') {
        $company = _l('no_company_view_profile');
    }		
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '">' . $company . '</a>';

	// Primary contact phone
    $row[] = ($aRow['phonenumber'] ? '<a href="' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');

    // Primary contact
    $row[] = ($aRow['contact_id'] ? '<a href="' . admin_url('clients/client/' . $aRow['userid'] . '?contactid=' . $aRow['contact_id']) . '" target="_blank">' . $aRow['contact_fullname'] . '</a>' : '');

	
	// Primary contact phone
    $row[] = ($aRow['mobilenumber'] ? '<a href="tel:' . $aRow['mobilenumber'] . '">' . $aRow['mobilenumber'] . '</a>' : '');

	
    // Primary contact email
    $row[] = ($aRow['emailid'] ? '<a href="mailto:' . $aRow['emailid'] . '">' . $aRow['emailid'] . '</a>' : '');

	
	// Customer Approval Status
    $company_s = 'Unapproved';
    if ($aRow['tblclients.approve'] == '0') {
        $company_s = 'Unapproved';
    }else if($aRow['tblclients.approve'] == '1'){
		$company_s = 'Approved';
	}else if($aRow['tblclients.approve'] == '2'){
		$company_s = 'Resubmit';
	}else if($aRow['tblclients.approve'] == '3'){
		$company_s = 'Reject';
	}
	$row[] = $company_s;
	
	$assignedOutput = '';
    if ($aRow['addedfrom'] !='') {

      $full_name = $aRow['assign_fullname'];

        $assignedOutput = '<a data-toggle="tooltip" data-title="'.$full_name.'" href="'.admin_url('profile/'.$aRow['addedfrom']).'">'.$full_name. '</a>';

        // For exporting
        //$assignedOutput .= '<span class="hide">'.$full_name.'</span>';
    }

    $row[] = $assignedOutput;
	
	
	$timestamp = strtotime($aRow['datetime']);
	$date = date("d-m-Y", $timestamp);

     $row[] = '<span data-toggle="tooltip" data-title="'._dt($aRow['datetime']).'" class="text-has-action">'.$date.'</span>';
 
	if($aRow['tblclients.active'] == 0)
	{
		$remark = $aRow['remark'];
	}
	else
	{ 
		$remark = 'Click to inactive'; 
	}
    
	
    // Toggle active/inactive customer
    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . $remark . '">
       <input style="text-align:center" type="checkbox" data-switch-url="' . admin_url().'clients/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . ($aRow['tblclients.active'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
    </div>';

	
    // For exporting
    $toggleActive .= '<span class="hide">' . ($aRow['tblclients.active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';

    $row[] = $toggleActive;
	
    // Custom fields add values
    foreach($customFieldsColumns as $customFieldColumn){
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $hook = do_action('customers_table_row_data', array(
        'output' => $row,
        'row' => $aRow
    ));

    $row = $hook['output'];

    // Table options
   
	$options = '';
    // Show button delete if permission for delete exists
	$options .= '<a class="btn btn-primary btn-icon" href="'.admin_url('clients/client/'.$aRow['userid']).'" ><i  class="fa fa-eye"></i></a>';
	
    if ($hasPermissionDelete) {
		 $options .= icon_btn('clients/client/' . $aRow['userid'], 'pencil-square-o');
        //$options .= icon_btn('clients/delete/' . $aRow['userid'], 'remove', 'btn-danger _delete');
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}
