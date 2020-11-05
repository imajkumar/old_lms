<?php 

init_head();  


//-------------------- DyNSM -------------------------------//

 function get_lost_amount_sum_dynsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status=7');
	}
	$own = $query->row()->total_opportunity;
	$staff = $query_staff->row()->total_opportunity;
	return $own + $staff;
 }

 function get_order_amount_sum_dynsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status = 6');
	}
	$own = $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }
 
 function get_opportunity_sum_dynsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	$own =  $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }


//-------------------- RSM -------------------------------//

 function get_lost_amount_sum_rsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status=7');
	}
	$own = $query->row()->total_opportunity;
	$staff = $query_staff->row()->total_opportunity;
	return $own + $staff;
 }

 function get_order_amount_sum_rsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status = 6');
	}
	$own = $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }
 
 function get_opportunity_sum_rsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	$own =  $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }


//-------------------- ZSM -------------------------------//

 function get_lost_amount_sum_zsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status=7');
	}
	$own = $query->row()->total_opportunity;
	$staff = $query_staff->row()->total_opportunity;
	return $own + $staff;
 }

 function get_order_amount_sum_zsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status = 6');
	}
	$own = $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }
 
 function get_opportunity_sum_zsm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	$own =  $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }


//-------------------- ASM -------------------------------//

 function get_lost_amount_sum_asm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status=7');
	}
	$own = $query->row()->total_opportunity;
	$staff = $query_staff->row()->total_opportunity;
	return $own + $staff;
 }

 function get_order_amount_sum_asm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status = 6');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status = 6');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status = 6');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status = 6');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status = 6');
	}
	$own = $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }
 
 function get_opportunity_sum_asm($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		
		
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
		$query_staff = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as project_total_amount FROM tblleads where reportingto LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	$own =  $query->row()->project_total_amount;
	$staff = $query_staff->row()->project_total_amount;
	return $own+$staff;
 }

 //-------------------- SE -------------------------------//
 function get_project_total_amount_sum_s($staff_id='',$filter='',$report_from='',$report_to=''){
	$ci =& get_instance();
	
	
	if($filter=='this_month'){
		$month = date('Y-m');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)'); 
	}else if($filter=='last_month'){
		$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)'); 
	}else if($filter=='this_year'){
		$year = date('Y');
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")   AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)'); 
	}else if($filter=='last_year'){
		$year = date('Y', strtotime(date('Y')." -1 year"));
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%")   AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)'); 
	}else if($filter=='custom'){
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
	}else{
		$query = $ci->db->query('SELECT COALESCE(SUM(project_total_amount),0) as project_total_amount FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
	}
	return $query->row()->project_total_amount;
 }
	
 function get_opportunity_sum_Sales($staff_id='',$filter='',$report_from='',$report_to=''){
		$ci =& get_instance();
		if($filter=='this_month'){
			$month = date('Y-m');
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)'); 
		}else if($filter=='last_month'){
			$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)'); 
		}else if($filter=='this_year'){
			$year = date('Y');
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")   AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)'); 
		}else if($filter=='last_year'){
			$year = date('Y', strtotime(date('Y')." -1 year"));
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")   AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)'); 
		}else if($filter=='custom'){
			
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND ( dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status IN(6,7)');
		}else{
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status IN(6,7)');
		}
		return $query->row()->total_opportunity;
	 }	
		
 function get_opportunity_sum_Sales_loss($staff_id='',$filter='',$report_from='',$report_to=''){
		$ci =& get_instance();
		if($filter=='this_month'){
			$month = date('Y-m');
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		}else if($filter=='last_month'){
			$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$month.'%") AND status=7');
		}else if($filter=='this_year'){
			$year = date('Y');
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%")  AND dateadded LIKE ("'.$year.'%") AND status=7');
		}else if($filter=='last_year'){
			$year = date('Y', strtotime(date('Y')." -1 year"));
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND dateadded LIKE ("'.$year.'%") AND status=7');
		}else if($filter=='custom'){
			
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND ( dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" ) AND status=7');
		}else{
			$query = $ci->db->query('SELECT COALESCE(SUM(opportunity),0) as total_opportunity FROM tblleads where assigned LIKE ("%'.$staff_id.'%") AND status=7');
		}
		
		return $query->row()->total_opportunity;
	 }
 

?>
<style>
.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>
<div id="wrapper">
   <div class="content">
   <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
			        <div class="row">
                  <div class="col-md-8">
				   <h4 class="no-margin font-medium"><i class="fa fa-area-chart" aria-hidden="true"></i> <?php echo _l('Winloss Report'); ?></h4>
				  </div>
                 
                 
               </div>
                 <div class="row" style="margin-top:50px;">
                 <form method="post" id="form1">
				   <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><?php echo _l('Filter').' '._l('period_datepicker'); ?></label><br />
                        <select class="selectpicker" name="months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                           <option value="this_month"><?php echo _l('this_month'); ?></option>
                           <option value="last_month"><?php echo _l('last_month'); ?></option>
                           <option value="this_year"><?php echo _l('this_year'); ?></option>
                           <option value="last_year"><?php echo _l('last_year'); ?></option>
                          <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                        </select>
                     </div>
                     <div id="date-range" class="hide mbot15">
                        <div class="row">
                           <div class="col-md-12">
                              <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                              <div class="input-group date">
                                 <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                 <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12">
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
                  	 
					<div class="form-group" id="report-time11">
                        <label for="months-report"><?php echo _l('NSM'); ?></label><br />
                        <select class="selectpicker" id="nsm" name="nsm" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value=""><?php echo _l('--Select NSM--'); ?></option>
                           <?php
							$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=8');
								$result = $query->result_array();
								
								foreach($result as $res){
									echo  '<option value="'.$res['staffid'].'">'.$res['firstname'].' '.$res['lastname'].'</option>';
								}
						   ?>
                        </select>
                     </div>
					 
				  </div>
                
                 <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="6" id="checkAllDyNSM" /> Dy NSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllDyNSM">	
                          
                       </div>
                     </div>
					
				  </div>
                 <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="2" id="checkAllRSM" /> RSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllRSM">	
                          
                       </div>
                     </div>
					
				  </div>
				  <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="5" id="checkAllZSM" /> ZSM</label><br />
						<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllZSM">	
                          
                       </div>
                     </div>
					
				  </div>
				  
                <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="3" id="checkAllASM" /> ASM</label><br />
							<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllASM">
							
							</div>
                     </div>
					
				  </div>
                <div class="col-md-2">
					<div class="form-group" id="report-time">
                        <label for="months-report"><input type="checkbox" value="1" id="checkAllSE" /> SE</label><br />
							<div style="max-height:145px;min-height:145px;overflow:scroll;border:1px solid #808080;" id="AllSE">
							
						</div>
                     </div>
					
				  </div>
				<div class="col-md-1">
						<div class="form-group" id="report-time">
						<br><br><br>
							<button type="submit" id="filter" class="btn btn-info pull-left display-block btnadd" >SHOW</button>
						 </div>
						
				  </div>
                </form>
				
                 </div>
               
					<div class="row">
						
						<table class="collaptable table table-bordered">
						  <tr style="text-align:center;background: #ff9201;">
							<th style="text-align:left;width:160px;">Name</th>
							<th style="text-align:center;">Opportunity Value(Lac)</th>
							<th style="text-align:center;">Order Value(Lac)</th>
							<th style="text-align:center;">If lost then lost Value</th>
							<th style="text-align:center;">Region</th>
							<th style="text-align:center;">Area</th>
							<th style="text-align:center;">Lead ID</th>
							<th style="text-align:center;">Lead Date</th>
							<th style="text-align:center;">Customer Name</th>
							<th style="text-align:center;">Catg.</th>
							<th style="text-align:center;width:200px">Product Description</th>
							<th style="text-align:center;">Status</th>
							<th style="text-align:center;">If lost then lost to</th>
							<th style="text-align:center;"><?php echo _l('Remarks'); ?></th>
						  </tr>
						 
						  <?php
							
							
							$chkrsm = $this->input->post('chkrsm');
							$total_record0 = sizeof($chkrsm);
							
							$chkzsm = $this->input->post('chkzsm');
							$total_record1 = sizeof($chkzsm);
							
							
							
							$chkasm = $this->input->post('chkasm');
							$total_record2 = sizeof($chkasm);
							
							$chkse = $this->input->post('chkse');
							$total_record3 = sizeof($chkse);
							
							$data = array();
			
							
							
							$nsm = $this->input->post('nsm');
							
							$chkdynsm = $this->input->post('chkdynsm');
							/* $string_version = implode(',', $original_array)

							 */
							$query = $this->db->query("SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=6 AND reporting_manager IN ('". $nsm ."') ORDER BY staffid ASC");
							
							$result = $query->result_array();
							$total_chkdynsm=1;
							foreach($result as $res){
								
								if(in_array($res['staffid'], $chkdynsm)){
									
									$get_lost_amount_sum_dynsm +=get_lost_amount_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
                                    
									$get_order_amount_sum_dynsm += get_order_amount_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
                                    
									$get_opportunity_sum_dynsm += get_opportunity_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to'));
									
						  ?>
							<tr style="display: table-row;background: #d7b9f5; font-size: 14px;" data-id="<?php echo $res['staffid']; ?>" data-parent="">
								<td style="text-align: left !important;width:220px;"><?php echo $res['firstname'].' '.$res['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_order_amount_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_lost_amount_sum_dynsm($res['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						   </tr>
						   <?php
								$chkdynsm_id = $res['staffid'];
								$query_rsm= $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=2 AND reporting_manager = '.$chkdynsm_id.'');
							
								$result_rsm = $query_rsm->result_array();
								$total_chkrsm=$total_chkdynsm;
								$totrsm = $total_chkdynsm + 3;
								foreach($result_rsm as $res_rsm){
								if(in_array($res_rsm['staffid'], $chkrsm)){
						   ?>
							   <tr style="display: table-row;background: #99dde8; font-size: 14px;" data-id="<?php echo $res_rsm['staffid']; ?>" data-parent="<?php echo $chkdynsm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_rsm['firstname'].' '.$res_rsm['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_rsm($res_rsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_order_amount_sum_rsm($res_rsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_lost_amount_sum_rsm($res_rsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							<?php
								$chkrsm_id = $res_rsm['staffid'];
								$query_zsm = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=5 AND reporting_manager ='.$chkrsm_id.'');
							
								$result_zsm = $query_zsm->result_array();
								$total_chkzsm=$totrsm;
								$totzsm = $total_chkzsm + 15;
								foreach($result_zsm as $res_zsm){
								if(in_array($res_zsm['staffid'], $chkzsm)){
						   ?>
							   <tr style="display: table-row;background: #ff9d9df5; font-size: 14px;" data-id="<?php echo $res_zsm['staffid']; ?>" data-parent="<?php echo $chkrsm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_zsm['firstname'].' '.$res_zsm['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_zsm($res_zsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_order_amount_sum_zsm($res_zsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_lost_amount_sum_zsm($res_zsm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							<?php
								$filter = $this->input->post('months-report');
								$report_from = $this->input->post('report-from');
								$report_to = $this->input->post('report-to');
								
								if($filter=='this_month'){
									$month = date('Y-m');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )  AND status IN(6,7)');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_zsm['staffid'].'" AND status IN(6,7)');
								}
								$result4res_zsm = $query4->result_array();
								
								foreach($result4res_zsm as $res4res_zsm){
								
						   ?>
							   <tr style="display: table-row; font-size: 14px;" data-id="<?php echo $res_zsm['staffid']+500; ?>" data-parent="<?php echo $res_zsm['staffid']; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4res_zsm['opportunity']; ?></td>
								<td><?php if(isset($res4res_zsm['project_total_amount'])){ echo $res4res_zsm['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4res_zsm['status']==7){ echo $res4res_zsm['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4res_zsm['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4res_zsm['city']); ?></td>
								<td><?php echo $res4res_zsm['id']; ?></td>
								<td><?php echo $res4res_zsm['dateadded']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4res_zsm['customer_name']); ?></td>
								<td><?php echo $res4res_zsm['customer_type']; ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4res_zsm['id']);
										$string = '';
										foreach ($CAT as $value) 
										{
											if (!empty($string)) 
											{ 
												$string .= ', '; 
											}
											$string .= $value['cat_name'];
										}
										echo $string;
									  ?>
								</td>
								<td><?php echo $this->leads_model->get_status_name($res4res_zsm['status']); ?></td>
								<td><?php echo $res4res_zsm['project_awarded_to']; ?></td>
								<td><?php
								 if($res4res_zsm['status_closed_won'] !=''){ 
									echo $this->leads_model->get_status_won_loss($res4res_zsm['status_closed_won']);
								  }			  
								  
								  ?></td> 
							  </tr>
							<?php
								}
								$chkzsm_id = $res_zsm['staffid'];
								$query_asm = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=3 AND reporting_manager = '.$chkzsm_id.'');
							
								$result_asm = $query_asm->result_array();
								$total_chkasm=$totzsm;
								$totasm = $total_chkasm + 10;
								foreach($result_asm as $res_asm){
								if(in_array($res_asm['staffid'], $chkasm)){
						   ?>
							   <tr style="display: table-row;background: #fddede;font-size: 14px;" data-id="<?php echo $res_asm['staffid']; ?>" data-parent="<?php echo $chkzsm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_asm['firstname'].' '.$res_asm['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_asm($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_order_amount_sum_asm($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_lost_amount_sum_asm($res_asm['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							<?php
								$filter = $this->input->post('months-report');
								$report_from = $this->input->post('report-from');
								$report_to = $this->input->post('report-to');
								
								if($filter=='this_month'){
									$month = date('Y-m');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND  dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )  AND status IN(6,7)');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_asm['staffid'].'" AND status IN(6,7)');
								}
								$result4res_asm = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4res_asm as $res4res_asm){
								
								
						   ?>
							   <tr data-id="<?php echo $res_asm['staffid']+500; ?>" data-parent="<?php echo $res_asm['staffid']; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4res_asm['opportunity']; ?></td>
								<td><?php if(isset($res4res_asm['project_total_amount'])){ echo $res4res_asm['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4res_asm['status']==7){ echo $res4res_asm['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4res_asm['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4res_asm['city']); ?></td>
								<td><?php echo $res4res_asm['id']; ?></td>
								<td><?php echo $res4res_asm['dateadded']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4res_asm['customer_name']); ?></td>
								<td><?php echo $res4res_asm['customer_type']; ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4res_asm['id']);
										$string = '';
										foreach ($CAT as $value) 
										{
											if (!empty($string)) 
											{ 
												$string .= ', '; 
											}
											$string .= $value['cat_name'];
										}
										echo $string;
									  ?>
								</td>
								<td><?php echo $this->leads_model->get_status_name($res4res_asm['status']); ?></td>
								<td><?php echo $res4res_asm['project_awarded_to']; ?></td>
								<td><?php
								 if($res4res_asm['status_closed_won'] !=''){ 
									echo $this->leads_model->get_status_won_loss($res4res_asm['status_closed_won']);
								  }			  
								  
								  ?></td> 
							  </tr>
							<?php
								}
								$chkasm_id = $res_asm['staffid'];
								$query_se = $this->db->query('SELECT staffid,emp_code,firstname,lastname FROM tblstaff where role=1 AND reporting_manager ='.$chkasm_id.'');
							
								$result_se = $query_se->result_array();
								$total_chkse=$total_chkasm;
								$totse = $totasm + 20;
								foreach($result_se as $res_se){
								if(in_array($res_se['staffid'], $chkse)){
						   ?>
							   <tr data-id="<?php echo $res_se['staffid']; ?>" data-parent="<?php echo $chkasm_id; ?>">
								<td style="text-align: left !important;width:220px;"><?php echo $res_se['firstname'].' '.$res_se['lastname']; ?></td>
								<td><?php echo get_opportunity_sum_Sales($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_project_total_amount_sum_s($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td><?php echo get_opportunity_sum_Sales_loss($res_se['staffid'],$this->input->post('months-report'),$this->input->post('report-from'),$this->input->post('report-to')); ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
							  
							  <?php
								$filter = $this->input->post('months-report');
								$report_from = $this->input->post('report-from');
								$report_to = $this->input->post('report-to');
								
								if($filter=='this_month'){
									$month = date('Y-m');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='last_month'){
									$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$month.'%") AND status IN(6,7)');
								}else if($filter=='this_year'){
									$year = date('Y');
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='last_year'){
									$year = date('Y', strtotime(date('Y')." -1 year"));
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND dateadded LIKE ("'.$year.'%") AND status IN(6,7)');
								}else if($filter=='custom'){
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'" AND (dateadded BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )  AND status IN(6,7)');
								}else{
									$query4 = $this->db->query('SELECT * FROM tblleads where assigned = "'.$res_se['staffid'].'"  AND status IN(6,7)');
								}
								$result4 = $query4->result_array();
								$total_chkse=$totasm;
								$totse = 200;
								foreach($result4 as $res4){
								
								$get_opportunity_sum_s = $get_opportunity_sum_s + $res4['opportunity'];
								$get_project_total_amount_sum_s = $get_project_total_amount_sum_s + $res4['project_total_amount'];
								if($res4['status']==7)
								{ 
									$get_opportunity_sums = $get_opportunity_sums + $res4['opportunity'];
								}
						   ?>
							   <tr data-id="<?php echo $res_se['staffid']; ?>" data-parent="<?php echo $chkasm_id; ?>">
								<td style="text-align: left !important;width:220px;"></td>
								<td><?php echo $res4['opportunity']; ?></td>
								<td><?php if(isset($res4['project_total_amount'])){ echo $res4['project_total_amount']; }else{ echo '0';}; ?></td>
								<td><?php if($res4['status']==7){ echo $res4['opportunity']; }else{ echo '0'; } ?></td>
								<td><?php echo $res4['region']; ?></td>
								<td><?php echo $this->leads_model->get_city_name($res4['city']); ?></td>
								<td><?php echo $res4['id']; ?></td>
								<td><?php echo $res4['dateadded']; ?></td>
								<td><?php echo $this->leads_model->get_customer_name($res4['customer_name']); ?></td>
								<td><?php echo $res4['customer_type']; ?></td>
								<td><?php 
									
										$CAT = $this->leads_model->get_product_description($res4['id']);
										$string = '';
										foreach ($CAT as $value) 
										{
											if (!empty($string)) 
											{ 
												$string .= ', '; 
											}
											$string .= $value['cat_name'];
										}
										echo $string;
									  ?>
								</td>
								<td><?php echo $this->leads_model->get_status_name($res4['status']); ?></td>
								<td><?php echo $res4['project_awarded_to']; ?></td>
								<td><?php
								 if($res4['status_closed_won'] !=''){ 
									echo $this->leads_model->get_status_won_loss($res4['status_closed_won']);
								  }			  
								   
								  ?></td> 
							  </tr>
							  <?php
							
							} 
							?>
							<?php
								
								}
							
							} 
							?>
							<?php
							}
							
							} 
							?>
							<?php
								
								}
								
								
							} 
							?>
							<?php
								
								}
							
							} 
							?>
							
							<?php 
							
							} ?>
						<?php } ?>
						
						<tr style="text-align:center;background: #ff9201;">
								<td style="text-align: left !important;width:220px;">Total</td>
								<td><?php echo $get_opportunity_sum_dynsm; ?></td>
								<td><?php echo $get_order_amount_sum_dynsm; ?></td>
                               <td><?php echo $get_lost_amount_sum_dynsm; ?></td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							  </tr>
						 
						</table>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>

<?php init_tail(); ?>

<?php $this->load->view('admin/reports/includes/sales_js'); ?>

<script>
$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();



$("#checkAllDyNSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkdynsm").prop("checked",status);
	
});



$("#checkAllRSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkrsm").prop("checked",status);
	
});

$("#checkAllZSM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkzsm").prop("checked",status);
	
});

$("#checkAllASM").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkasm").prop("checked",status);
	
});



$("#checkAllSE").change(function(){
	var status = $(this).is(":checked") ? true : false;
	$(".chkse").prop("checked",status);
	
});

$(document).on('change', '#nsm', function (e) {
		$('#AllDyNSM').html("");
		
        var nsm_id = $(this).val();
   		var base_url = '<?php echo base_url() ?>';
        var div_data = '';
        var string_dynsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getBydynsm_id",
            data: {'nsm': nsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<input type='checkbox' name='chkdynsm[]' class='chkdynsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";					
					string_dynsm += obj.staffid+',';
                });
				get_rsm(string_dynsm);
				console.log(string_dynsm);
				 $('#AllDyNSM').append(div_data);
				 $("#checkAllDyNSM").prop("checked",true).trigger("change");
            }
        });
	
        $("#checkAllDyNSM").prop("checked",true).trigger("change");
       
    });

	$(document).on('change', '.chkdynsm', function() {
		 var ids=''; 
		$('input[name="chkdynsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		
		get_rsm(ids);
	});
	
	 //------------fetch RSM ------------//
	function get_rsm(nsm_id)
	{
		$('#AllRSM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_rsm = '';
		var string_rsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByrsm_id",
            data: {'dynsm_id': nsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_rsm += "<input type='checkbox' name='chkrsm[]' class='chkrsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_rsm += obj.staffid+',';
                });
				console.log(string_rsm);
				get_zsm(string_rsm)
				$('#AllRSM').append(div_data_rsm);
				$("#checkAllRSM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkrsm', function() {
		 var ids=''; 
		$('input[name="chkrsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		console.log(ids);
		get_zsm(ids);
	});
	
	 //------------fetch ZSM ------------//
	function get_zsm(rsm_id)
	{
		$('#AllZSM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_rsm = '';
		var string_zsm = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByzsm_id",
            data: {'rsm_id': rsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_rsm += "<input type='checkbox' name='chkzsm[]' class='chkzsm' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_zsm += obj.staffid+',';
                });
				console.log(string_zsm);
				get_asm(string_zsm);
				$('#AllZSM').append(div_data_rsm);
				$("#checkAllZSM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkzsm', function() {
		 var ids=''; 
		$('input[name="chkzsm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		
		get_asm(ids);
	});
	 
	function get_asm(rsm_id)
	{
		$('#AllASM').html("");
		
		var base_url = '<?php echo base_url() ?>';
		//------------fetch ASM ------------//
		var div_data_asm = '';
		var string_asm = '';
		
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByasm_id",
            data: {'list_rsm': rsm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_asm += "<input type='checkbox' class='chkasm' name='chkasm[]' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_asm += obj.staffid+',';
                });
				console.log(string_asm);
				get_se(string_asm);
                $('#AllASM').append(div_data_asm);
				$("#checkAllASM").prop("checked",true).trigger("change");
            }
        });
	}

	$(document).on('change', '.chkasm', function() {
		 var ids=''; 
		$('input[name="chkasm[]"]:checked').each(function(){
			ids += $(this).val()+',';
		});
		console.log(ids);
		get_se(ids);
	});
	
	 //------------fetch SE ------------//
	function get_se(asm_id)
	{
		$('#AllSE').html("");
		
		var base_url = '<?php echo base_url() ?>';
		var div_data_se = '';
		var string_se = '';
		$.ajax({
            type: "GET",
            url: base_url + "admin/leads/getByse_id",
            data: {'list_asm': asm_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data_se += "<input type='checkbox' class='chkse' name='chkse[]' value=" + obj.staffid + ">" + obj.firstname +' '+ obj.lastname + "<br>";
					string_se += obj.staffid+',';
                });
				console.log(string_se);
                $('#AllSE').append(div_data_se);
				$("#checkAllSE").prop("checked",true).trigger("change");
            }
        });
		
	}

	 $(function () {
		 $('.btnadd').click(function () {
			var item_chkdynsm = '';
			 $('input[name="chkdynsm[]"]:checked').each(function(){
				item_chkdynsm += $(this).val()+',';
			});
			item_chkdynsm = item_chkdynsm.slice(0, -1);
			
			var item_rsm  = '';
			 $('input[name="chkrsm[]"]:checked').each(function(){
				item_rsm += $(this).val()+',';
			});
			item_rsm = item_rsm.slice(0, -1);
			
			var item_asm = '';
			 $('input[name="chkasm[]"]:checked').each(function(){
				item_asm += $(this).val()+',';
			});
			item_asm = item_asm.slice(0, -1);
			
			var item_se = '';
			 $('input[name="chkse[]"]:checked').each(function(){
				item_se += $(this).val()+',';
			});
			item_se = item_se.slice(0, -1);
			
			 console.log( 'item_se: '+ item_se +' item_asm:'+ item_asm +' item_rsm:'+ item_rsm +' item_chkdynsm:'+item_chkdynsm );
			 
			 /* $.ajax({
				 url: 'process.php',
				 type: 'post',
				 data: $("#form1").serialize(),
				 success: function (data) {}
			 }); */
		 });
	 });
	
	
	</script>
	
	
	

</body>
</html>
