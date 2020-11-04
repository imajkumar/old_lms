<?php
defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('leads','','delete');
$custom_fields = get_table_custom_fields('leads');

$aColumns     = array(
    '1',
    'tblleads.id as id',
    'tblleads.name as name',
    'tblleads.reportingto as reportingto',
    'tblleads.company as leaddesc','tblcustomer_type.name as customer_type',
    'tblleads.email as email',
    'tblleads.phonenumber as phonenumber','opportunity',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tbltags_in JOIN tbltags ON tbltags_in.tag_id = tbltags.id WHERE rel_id = tblleads.id and rel_type="lead" ORDER by tag_order ASC) as tags',
    'CONCAT(firstname, \' \', lastname) as assigned_name',
    'tblleadsstatus.name as status_name',
    'tblclients.company as companyname',
    'tblleadssources.name as source_name',
    'tblregion.region as region_name', 'tblcustomersgroups.name as group_name',
    'lastcontact',
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
$filter = false;

if ($this->ci->input->post('custom_view')) {
    $filter = $this->ci->input->post('custom_view');
    if ($filter == 'lost') {
        array_push($where, 'AND lost = 1');
    } elseif ($filter == 'junk') {
        array_push($where, 'AND junk = 1');
    } elseif ($filter == 'not_assigned') {
        array_push($where, 'AND assigned = 0');
    } elseif ($filter == 'contacted_today') {
        array_push($where, 'AND lastcontact LIKE "'.date('Y-m-d').'%"');
    } elseif ($filter == 'created_today') {
        array_push($where, 'AND dateadded LIKE "'.date('Y-m-d').'%"');
    } elseif ($filter == 'public') {
        array_push($where, 'AND is_public = 1');
    }
}

/* print_r($this->ci->input->post()); */

if (!$filter || ($filter && $filter != 'lost' && $filter != 'junk')) {
   // array_push($where, 'AND lost = 0 AND junk = 0');
}

if (has_permission('leads','','view') && $this->ci->input->post('assigned')) {
    array_push($where, 'AND assigned =' . $this->ci->input->post('assigned'));
}

if ($this->ci->input->post('status')
    && count($this->ci->input->post('status')) > 0
    && ($filter != 'lost' && $filter != 'junk')) {
    array_push($where, 'AND status IN ('.implode(',',$this->ci->input->post('status')).')');
}

if ($this->ci->input->post('source')) {
    array_push($where, 'AND source =' . $this->ci->input->post('source'));
}
if ($this->ci->input->post('region')) {
    array_push($where, 'AND region =' . $this->ci->input->post('region'));
}

if (!has_permission('leads','','view')) {
    array_push($where, 'AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
}
if(get_staff_role() == 7 || get_staff_role() == 4) 
{
	array_push($where, 'AND tblleads.state IN('. get_staff_state_id() .')');
}
if(get_staff_role() > 1 && (get_staff_role() != 4 || get_staff_role() != 7)) 
{
	//array_push($where, 'AND tblleads.state IN('. get_staff_state_id() .')');
	
	array_push($where, 'AND tblleads.reportingto LIKE "%'.get_staff_user_id().'%"');
}

$aColumns = do_action('leads_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblleads.junk',
    'tblleads.lost',
    'tblcustomer_type.color',
    'tblleads.assigned',
    'tblleads.addedfrom as addedfrom',
    'tblleads.zip'
));

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();

    /* $row[] = '<div class="checkbox"><input type="checkbox" value="'.$aRow['id'].'"><label></label></div>'; */

    $row[] =  '<a href="'.admin_url('leads/index/'.$aRow['id']).'" onclick="init_lead('.$aRow['id'].');return false;">'. $aRow['id'] . '</a>';
$row[] = $aRow['group_name'];$row[] = $aRow['companyname'];
    $row[] = '<a href="'.admin_url('leads/index/'.$aRow['id']).'" onclick="init_lead('.$aRow['id'].');return false;">'. $aRow['leaddesc'] . '</a>';

    

    $row[] = $aRow['opportunity'];
   
    $assignedOutput = '';
    if ($aRow['assigned'] != 0) {

        $full_name = $aRow['assigned_name'];

        $assignedOutput = '<a data-toggle="tooltip" data-title="'.$full_name.'" href="'.admin_url('profile/'.$aRow['assigned']).'">'.$full_name. '</a>';

        // For exporting
        $assignedOutput .= '<span class="hide">'.$full_name.'</span>';
    }

    $row[] = $assignedOutput;

    if ($aRow['status_name'] == null) {
        if ($aRow['lost'] == 1) {
            $statusOutput = '<span class="label label-danger inline-block">' . _l('lead_lost') . '</span>';
        } elseif ($aRow['junk'] == 1) {
            $statusOutput = '<span class="label label-warning inline-block">' . _l('lead_junk') . '</span>';
        }
    } else {
        $statusOutput = '<span class="inline-block label'.(!$this->ci->input->post('status') ? ' pointer lead-status' : '').' label-' . (empty($aRow['color']) ? 'default': '') . '" style="color:' . $aRow['color'] . ';border:1px solid ' . $aRow['color'] . '">' . $aRow['status_name'] . '</span>';
    }

    $row[] = $statusOutput;

    $row[] = $aRow['source_name']; 

	
	$customerOutput = '<span class="label label-danger inline-block">' . $aRow['customer_type'] . '</span>';
	
    $row[] = $customerOutput;

    /* $row[] = '<span data-toggle="tooltip" data-title="'._dt($aRow['dateadded']).'" class="text-has-action">'.time_ago($aRow['dateadded']).'</span>';
 */
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

    $options .= '<a class="btn btn-primary btn-icon" href="'.admin_url('leads/index/'.$aRow['id']).'" onclick="init_lead('.$aRow['id'].');return false;"><i  class="fa fa-eye"></i></a>';
    $options .= '<a class="btn btn-default btn-icon" href="'.admin_url('leads/manage/'.$aRow['id']).'"><i class="fa fa-pencil-square-o"></i></a>';
    if ($aRow['addedfrom'] == get_staff_user_id() || $has_permission_delete) {
        $options .= icon_btn('leads/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    }

    $row[] = $options;
    $row['DT_RowId'] = 'lead_'.$aRow['id'];

    if ($aRow['assigned'] == get_staff_user_id()) {
        $row['DT_RowClass'] = 'alert-info';
    }

    $output['aaData'][] = $row;
}
