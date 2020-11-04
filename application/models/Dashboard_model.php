<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard_model extends CRM_Model
{
    private $is_admin;

    public function __construct()
    {
        parent::__construct();
        $this->is_admin = is_admin();
    }

    /**
     * @return array
     * Used in home dashboard page
     * Return all upcoming events this week
     */
    public function get_upcoming_events()
    {
        $this->db->where('(start BETWEEN "' . date('Y-m-d', strtotime('monday this week')) . '" AND "' . date('Y-m-d', strtotime('sunday this week')) . '")');
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');
        $this->db->order_by('start', 'desc');
        $this->db->limit(6);

        return $this->db->get('tblevents')->result_array();
    }

    /**
     * @param  integer (optional) Limit upcoming events
     * @return integer
     * Used in home dashboard page
     * Return total upcoming events next week
     */
    public function get_upcoming_events_next_week()
    {
        $monday_this_week = date('Y-m-d', strtotime('monday next week'));
        $sunday_this_week = date('Y-m-d', strtotime('sunday next week'));
        $this->db->where('(start BETWEEN "' . $monday_this_week . '" AND "' . $sunday_this_week . '")');
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');

        return $this->db->count_all_results('tblevents');
    }

    /**
     * @param  mixed
     * @return array
     * Used in home dashboard page, currency passed from javascript (undefined or integer)
     * Displays weekly payment statistics (chart)
     */
    public function get_weekly_payments_statistics($currency)
    {
        $all_payments                 = array();
        $has_permission_payments_view = has_permission('payments', '', 'view');
        $this->db->select('amount,tblinvoicepaymentrecords.date');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
        $this->db->where('CAST(tblinvoicepaymentrecords.date as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(tblinvoicepaymentrecords.date as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '"');
        $this->db->where('tblinvoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }

        if (!$has_permission_payments_view) {
            $this->db->where('invoiceid IN (SELECT id FROM tblinvoices WHERE addedfrom=' . get_staff_user_id() . ')');
        }

        // Current week
        $all_payments[] = $this->db->get()->result_array();
        $this->db->select('amount,tblinvoicepaymentrecords.date');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid');
        $this->db->where('CAST(tblinvoicepaymentrecords.date as DATE) >= "' . date('Y-m-d', strtotime('monday last week', strtotime('last sunday'))) . '" AND CAST(tblinvoicepaymentrecords.date as DATE) <= "' . date('Y-m-d', strtotime('sunday last week', strtotime('last sunday'))) . '"');

        $this->db->where('tblinvoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }
        // Last Week
        $all_payments[] = $this->db->get()->result_array();

        $chart = array(
            'labels' => get_weekdays(),
            'datasets' => array(
                array(
                    'label' => _l('this_week_payments'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => "#84c529",
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => array(
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ),
                ),
                array(
                    'label' => _l('last_week_payments'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor' => "#c53da9",
                    'borderWidth' => 1,
                    'tension' => false,
                    'data' => array(
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ),
                ),
            ),
        );


        for ($i = 0; $i < count($all_payments); $i++) {
            foreach ($all_payments[$i] as $payment) {
                $payment_day = date('l', strtotime($payment['date']));
                $x           = 0;
                foreach (get_weekdays_original() as $day) {
                    if ($payment_day == $day) {
                        $chart['datasets'][$i]['data'][$x] += $payment['amount'];
                    }
                    $x++;
                }
            }
        }

        return $chart;
    }

    public function projects_status_stats()
    {
        $this->load->model('projects_model');
        $statuses = $this->projects_model->get_project_statuses();
        $colors   = get_system_favourite_colors();

        $chart = array(
            'labels' => array(),
            'datasets' => array(),
        );

        $_data                         = array();
        $_data['data']                 = array();
        $_data['backgroundColor']      = array();
        $_data['hoverBackgroundColor'] = array();
        $_data['statusLink'] = array();


        $has_permission = has_permission('projects', '', 'view');
        $sql = '';
        foreach ($statuses as $status) {
            $sql .= ' SELECT COUNT(*) as total';
            $sql .= ' FROM tblprojects';
            $sql .= ' WHERE status='.$status['id'];
            if (!$has_permission) {
                $sql .= ' AND id IN (SELECT project_id FROM tblprojectmembers WHERE staff_id=' . get_staff_user_id() . ')';
            }
            $sql .= ' UNION ALL ';
            $sql = trim($sql);
        }

        $result = array();
        if ($sql != '') {
            // Remove the last UNION ALL
            $sql = substr($sql, 0, -10);
            $result = $this->db->query($sql)->result();
        }

        foreach ($statuses as $key => $status) {
			
			/* $value_total = $result[$key]->total .' - '.$result[$key]->total_opportunity; */
            array_push($_data['statusLink'], admin_url('projects?status='.$status['id']));
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'], $result[$key]->total);
        }

        $chart['datasets'][]           = $_data;
        $chart['datasets'][0]['label'] = _l('home_stats_by_project_status');

        return $chart;
    }

    public function leads_status_stats()
    {
        $this->load->model('leads_model');

        $statuses = $this->leads_model->get_status();
        $colors   = get_system_favourite_colors();

        $chart    = array(
            'labels' => array(),
            'datasets' => array(),
        );

        $_data                         = array();
        $_data['data']                 = array();
        $_data['backgroundColor']      = array();
        $_data['hoverBackgroundColor'] = array();
        $_data['statusLink'] = array();

        $has_permission_view = has_permission('leads', '', 'view');
        $sql = '';

        foreach ($statuses as $status) {
            $sql .= ' SELECT COUNT(*) as total, SUM(opportunity) as total_opportunity';
            $sql .= ' FROM tblleads';
            $sql .= ' WHERE status='.$status['id'];
            if (!$has_permission_view) {
                $sql .= ' AND (addedfrom = ' . get_staff_user_id() . ' OR assigned = ' . get_staff_user_id() . ')';
            }
            $sql .= ' UNION ALL ';
            $sql = trim($sql);
        }

        $result = array();
        if ($sql != '') {
            // Remove the last UNION ALL
            $sql = substr($sql, 0, -10);
            $result = $this->db->query($sql)->result();
        }

        foreach ($statuses as $key => $status) {
            if ($status['color'] == '') {
                $status['color'] = '#737373';
            }
				 $value_total = $result[$key]->total .' - '.$result[$key]->total_opportunity; 
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            array_push($_data['statusLink'], admin_url('leads?status='.$status['id']));
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'],  $result[$key]->total_opportunity);
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

  
    public function customer_status_stats()
    {
        $this->load->model('leads_model');

        $customer = $this->leads_model->get_customer_type();
		
        $colors   = get_system_favourite_colors();

        $chart    = array(
            'labels' => array(),
            'datasets' => array(),
        );

        $_data                         = array();
        $_data['data']                 = array();
        $_data['backgroundColor']      = array();
        $_data['hoverBackgroundColor'] = array();
        $_data['statusLink'] = array();

        $has_permission_view = has_permission('leads', '', 'view');
        $sql = "";

        foreach ($customer as $customer_lead) {
			
            $sql .= " SELECT COUNT(*) as total, SUM(opportunity) as total_opportunity";
            $sql .= " FROM tblleads";
            $sql .= " WHERE customer_type='".$customer_lead['code']."'";
            if (!$has_permission_view) {
                $sql .= " AND (addedfrom = " . get_staff_user_id() . " OR assigned = " . get_staff_user_id() . ")";
            }
            $sql .= " UNION ALL ";
            $sql = trim($sql);
        }
 
        $result = array();
        if ($sql != '') {
            // Remove the last UNION ALL
            $sql = substr($sql, 0, -10);
			
            $result = $this->db->query($sql)->result();
        }
		

        foreach ($customer as $key => $customer_lead) {
            if ($customer_lead['color'] == '') {
                $customer_lead['color'] = '#737373';
            }

            array_push($chart['labels'], $customer_lead['name']);
            array_push($_data['backgroundColor'], $customer_lead['color']);
            array_push($_data['statusLink'], admin_url('leads?customer_lead='.$customer_lead['code']));
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($customer_lead['color'], -20));
            array_push($_data['data'], $result[$key]->total_opportunity);
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

	
	public function get_staff_role_id($staff_id){
		$this->db->where('staffid', $staff_id);
        return $this->db->get('tblstaff')->row()->role;
	}
	public function get_staff_state_byid($staff_id){
		$this->db->where('staffid', $staff_id);
        return $this->db->get('tblstaff')->row()->state;
	}
    /**
     * Display total tickets awaiting reply by department (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_department()
    {
        $this->load->model('departments_model');
        $departments = $this->departments_model->get();
        $colors      = get_system_favourite_colors();
        $chart       = array(
            'labels' => array(),
            'datasets' => array(),
        );

        $_data                         = array();
        $_data['data']                 = array();
        $_data['backgroundColor']      = array();
        $_data['hoverBackgroundColor'] = array();

        $i = 0;
        foreach ($departments as $department) {
            if (!$this->is_admin) {
                if (get_option('staff_access_only_assigned_departments') == 1) {
                    $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                    $departments_ids      = array();
                    if (count($staff_deparments_ids) == 0) {
                        $departments = $this->departments_model->get();
                        foreach ($departments as $department) {
                            array_push($departments_ids, $department['departmentid']);
                        }
                    } else {
                        $departments_ids = $staff_deparments_ids;
                    }
                    if (count($departments_ids) > 0) {
                        $this->db->where('department IN (SELECT departmentid FROM tblstaffdepartments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                    }
                }
            }
            $this->db->where_in('status', array(
                1,
                2,
                4,
            ));

            $this->db->where('department', $department['departmentid']);
            $total = $this->db->count_all_results('tbltickets');

            if ($total > 0) {
                $color = '#333';
                if (isset($colors[$i])) {
                    $color = $colors[$i];
                }
                array_push($chart['labels'], $department['name']);
                array_push($_data['backgroundColor'], $color);
                array_push($_data['hoverBackgroundColor'], adjust_color_brightness($color, -20));
                array_push($_data['data'], $total);
            }
            $i++;
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    /**
     * Display total tickets awaiting reply by status (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_status()
    {
        $this->load->model('tickets_model');
        $statuses             = $this->tickets_model->get_ticket_status();
        $_statuses_with_reply = array(
            1,
            2,
            4,
        );

        $chart = array(
            'labels' => array(),
            'datasets' => array(),
        );

        $_data                         = array();
        $_data['data']                 = array();
        $_data['backgroundColor']      = array();
        $_data['hoverBackgroundColor'] = array();
        $_data['statusLink'] = array();

        foreach ($statuses as $status) {
            if (in_array($status['ticketstatusid'], $_statuses_with_reply)) {
                if (!$this->is_admin) {
                    if (get_option('staff_access_only_assigned_departments') == 1) {
                        $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                        $departments_ids      = array();
                        if (count($staff_deparments_ids) == 0) {
                            $departments = $this->departments_model->get();
                            foreach ($departments as $department) {
                                array_push($departments_ids, $department['departmentid']);
                            }
                        } else {
                            $departments_ids = $staff_deparments_ids;
                        }
                        if (count($departments_ids) > 0) {
                            $this->db->where('department IN (SELECT departmentid FROM tblstaffdepartments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                        }
                    }
                }

                $this->db->where('status', $status['ticketstatusid']);
                $total = $this->db->count_all_results('tbltickets');
                if ($total > 0) {
                    array_push($chart['labels'], ticket_status_translate($status['ticketstatusid']));
                    array_push($_data['statusLink'], admin_url('tickets/index/'.$status['ticketstatusid']));
                    array_push($_data['backgroundColor'], $status['statuscolor']);
                    array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['statuscolor'], -20));
                    array_push($_data['data'], $total);
                }
            }
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }
	
	
	//========================= Dashboard Filter -------------------//
	
	
	
	public function get_lead_status($lead_status='',$staff_id='',$report_months='',$from_date='',$to_date='')
    {
        $this->load->model('leads_model');
		
		$staff_state_id = $this->get_staff_state_byid($staff_id);
		$role = $this->get_staff_role_id($staff_id);
			
		$sql = "";
		$sql .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$lead_status."'";
		
		if($staff_id != '' && $report_months != '' ) 
		{
			if ($role == 1) {
				
				$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if($role == 7 || $role == 4) 
			{
				$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$lead_status."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		
		}
		else if($staff_id != '' && $report_months == '' ) 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				
				$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				
			}
			else if($role == 7 || $role == 4) 
			{
				$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )';
				
			}
			
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$lead_status."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		
		}
		else if($staff_id == '' && $report_months != '' )
		{
			if (get_staff_role() > 8 || is_admin()) {
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$lead_status."'";
				
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		}
		else
		{
			if (is_admin()) {
				$sql .=  '';
			}
			else if (get_staff_role() > 8) {
				$sql .=  ' AND tblleads.is_public = 1';
			}
			else if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )';
			}
			
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$lead_status."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		}
		
		$total_staff = $this->db->query($sql)->row()->total;
		
        return $total_staff + $total_own;
        
    }

	
	public function get_customer_type($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
			$this->db->order_by('name', 'asc');
            return $this->db->get('tblcustomer_type')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get('tblcustomer_type')->result_array();
    }
	public function get_status($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            
            return $this->db->get('tblleadsstatus')->row();
        }
        $this->db->order_by('id', 'asc');
        
        return $this->db->get('tblleadsstatus')->result_array();
    }
	
	public function get_status_displayorder($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadsstatus')->row();
        }
        $this->db->order_by('displayorder', 'asc');

        return $this->db->get('tblleadsstatus')->result_array();
    }
	
	
	  
	 public function get_lead_no_status($status, $staff_id = '', $report_months = '',$from_date='',$to_date='')
    {
        if ($staff_id != '' && $report_months != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } else if ($role == 7 || $role == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } 
		else if ($staff_id != '' && $report_months == '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                
            } else if ($role == 7 || $role == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ')');
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )');
                
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                    
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                    
                }
                $total_own = $query1->num_rows();
            }
        } 
		else if ($staff_id == '' && $report_months != '') {
            
            if (get_staff_role() > 8 || is_admin()) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } 
			else if (get_staff_role() == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } 
			else if (get_staff_role() == 7 || get_staff_role() == 4 ) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } 
			else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else {
			if (is_admin()) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"');
			}else if (get_staff_role() > 8) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND tblleads.is_public = 1');
			}
			else if (get_staff_role() == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ')');
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.get_staff_user_id().',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.get_staff_user_id().',%")  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                }
                $total_own = $query1->num_rows();
            }
        }
        
        $total_staff = $query->num_rows();
        return $total_staff + $total_own;
    }
 
 
  public function get_lead_customer_type($customer_type='',$staff_id='',$report_months='',$from_date='',$to_date='')
    {
        $this->load->model('leads_model');
		
		$staff_state_id = $this->get_staff_state_byid($staff_id);
		$role = $this->get_staff_role_id($staff_id);
		
			$sql = "";
			
            $sql .= "SELECT SUM(opportunity) as total FROM tblleads WHERE customer_type='".$customer_type."'";
           
        if($staff_id != '' && $report_months != '' ) 
		{
			if ($role == 1) {
				
				$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if($role == 7 || $role == 4) 
			{
				$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				
				$sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%"';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE customer_type='".$customer_type."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		
		}
		else if($staff_id != '' && $report_months == '' ) 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				
				$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				
			}
			else if($role == 7 || $role == 4) 
			{
				$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				
				$sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%"';
				
			}
			
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE customer_type='".$customer_type."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		
		}
		else if($staff_id == '' && $report_months != '' )
		{
			if (get_staff_role() > 8) {
				
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND tblleads.reportingto LIKE "%'.get_staff_user_id().'%"';
				if ($report_months =='this_month') {
					$month = date('Y-m');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='last_month') {
					$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
				}else if($report_months =='this_year') {
					$year = date('Y');
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='last_year') {
					$year = date('Y', strtotime(date('Y')." -1 year"));
					$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
				}else if($report_months =='report_sales_months_three_months') {
					$report_from = date('Y-m-01', strtotime("-2 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_six_months') {
					$report_from = date('Y-m-01', strtotime("-5 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='report_sales_months_twelve_months') {
					$report_from = date('Y-m-01', strtotime("-11 MONTH"));
					$report_to= date('Y-m-d');
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
				}
			}
			
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE customer_type='".$customer_type."'";
				
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					if ($report_months =='this_month') {
						$month = date('Y-m');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='last_month') {
						$month = date('Y-m' , strtotime(date('Y-m')." -1 month"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$month.'%")';
					}else if($report_months =='this_year') {
						$year = date('Y');
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='last_year') {
						$year = date('Y', strtotime(date('Y')." -1 year"));
						$sql .=   ' AND tblleads.dateassigned LIKE ("'.$year.'%")';
					}else if($report_months =='report_sales_months_three_months') {
						$report_from = date('Y-m-01', strtotime("-2 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_six_months') {
						$report_from = date('Y-m-01', strtotime("-5 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (tblleads.dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='report_sales_months_twelve_months') {
						$report_from = date('Y-m-01', strtotime("-11 MONTH"));
						$report_to= date('Y-m-d');
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
						$sql .=   ' AND (dateassigned BETWEEN "'.$report_from.' 00:00:00" AND "'.$report_to.' 23:59:59" )';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		}
		else
		{
			if (is_admin()) {
				$sql .=  '';
			}else if (get_staff_role() > 8) {
				$sql .=  ' AND tblleads.is_public =1';
			}
			else if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND tblleads.reportingto LIKE "%'.get_staff_user_id().'%"';
			}
			
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= "SELECT SUM(opportunity) as total FROM tblleads WHERE customer_type='".$customer_type."'";
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
		}
		
		$total_staff = $this->db->query($sql)->row()->total;
		
        return $total_staff + $total_own;
        
    }
    
	public function get_lead_no_custtype($cust, $staff_id = '', $report_months = '',$from_date='',$to_date='')
    {
        if ($staff_id != '' && $report_months != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } 
			else if ($role == 7 || $role == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } 
		
		else if ($staff_id != '' && $report_months == '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                
            } else if ($role == 7 || $role == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ')');
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )');
                
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                    
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                    
                }
                $total_own = $query1->num_rows();
            }
        } 
		else if ($staff_id == '' && $report_months != '') {
            if (get_staff_role() > 8 || is_admin()) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            else if (get_staff_role() == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } 
			else if (get_staff_role() == 7 || get_staff_role() == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateassigned LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateassigned LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else {
            if (is_admin()) {
				$query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"');
			}else if (get_staff_role() > 8) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"');
            }else if (get_staff_role() == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ')');
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.get_staff_user_id().',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.get_staff_user_id().',%")  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                }
                $total_own = $query1->num_rows();
            }
        }
		
        $total_staff = $query->num_rows();
      
        
        return $total_staff + $total_own;
        
    }
    
	
	
	
}
