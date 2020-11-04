<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$aColumns = array('name','segment');

$sIndexColumn = "id";
$sTable = 'tblcustomersgroups';

$join = array();


$result = data_tables_init($aColumns,$sIndexColumn,$sTable,array(),array(),array('id'));
$output = $result['output'];
$rResult = $result['rResult'];

foreach ( $rResult as $aRow )
{
    $row = array();
	$row[] = $aRow['id'];
	$row[] = $aRow['name'];
    $row[] = $aRow['segment'];


	$options = icon_btn('#','pencil-square-o','btn-default',array('data-toggle'=>'modal','data-target'=>'#customer_group_modal','data-id'=>$aRow['id'])); 
	$options .= icon_btn('clients/delete_group/'.$aRow['id'],'remove','btn-danger _delete');
    $row[]  = $options;

    $output['aaData'][] = $row;
}
