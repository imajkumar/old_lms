<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns     = array(
    'tblitems.itme_code',
    'tblitems.description',
    'tblitems.long_description',
    'tblitems.rate',
    'tblitems.unit',
    'tblitems_groups.name',
	'tblitems_sub_groups.name as group_name',
	'tblitems.status'
    );
$sIndexColumn = "id";
$sTable       = 'tblitems';

$join             = array(
   
    'LEFT JOIN tblitems_groups ON tblitems_groups.id = tblitems.group_id', 
	'LEFT JOIN tblitems_sub_groups ON tblitems_sub_groups.id = tblitems.subgroup_id', 
	
    );
$additionalSelect = array(
    'tblitems.id',
   
    'tblitems.group_id','tblitems.subgroup_id',
    );

$custom_fields = get_custom_fields('items');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_'.$key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_'.$key.'.value as '.$selectAs);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_'.$key . ' ON tblitems.id = ctable_'.$key . '.relid AND ctable_'.$key . '.fieldto="items_pr" AND ctable_'.$key . '.fieldid='.$field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result           = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, array(), $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
		if ($aColumns[$i] == 'tblitems.status') {
			
			if($aRow['tblitems.status'] > 0){
				$_data = 'Inactive';
			}else{
				$_data = 'Active';
			}
            
        }
        /* if ($aColumns[$i] == 't1.taxrate as taxrate_1') {
            if (!$aRow['taxrate_1']) {
                $aRow['taxrate_1'] = 0;
            }
            $_data = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="'.$aRow['tax_id_1'].'">' . $aRow['taxrate_1'] . '%' . '</span>';
        } elseif ($aColumns[$i] == 't2.taxrate as taxrate_2') {
            if (!$aRow['taxrate_2']) {
                $aRow['taxrate_2'] = 0;
            }
            $_data = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="'.$aRow['tax_id_2'].'">' . $aRow['taxrate_2'] . '%' . '</span>';
        } elseif ($aColumns[$i] == 'description') {
            $_data = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="'.$aRow['id'].'">'.$_data.'</a>';
        } else {
            if(_startsWith($aColumns[$i],'ctable_') && is_date($_data)){
                $_data = _d($_data);
            }
        } */

        $row[] = $_data;
    }
    $options = '';
    if (has_permission('items', '', 'edit') || get_staff_role()==0) {
        $options .= icon_btn('invoice_items/update/' . $aRow['id'], 'pencil-square-o', 'btn-default');
    }
    if (has_permission('items', '', 'delete')) {
		if($aRow['tblitems.status'] > 0){
			 $options .= icon_btn('invoice_items/active/' . $aRow['id'], 'check', 'btn-default _delete');
		}else{
			 $options .= icon_btn('invoice_items/inactive/' . $aRow['id'], 'ban', 'btn-default _delete');
		}
       
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
