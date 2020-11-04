<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leads_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
	 
	 
	 
	 public function update_lead_requirment_status($id,$status,$reason){
	
		$data = array(
			'status'=>$status,
			'reason'=>$reason,
	  );
	  $this->db->where('id', $id);
	  $this->db->update('tbllead_requirment_detail',$data);
	}
	 
	  public function list_leads_data_region($id = null) {
        $this->db->select()->from('tbllead_requirment_detail');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }
    } 
	 
	 function update_user_comment($status,$u_id)
{
if($status == 1)
{
$query="UPDATE tbllead_requirment_detail SET 'status' ='1' WHERE id=".$u_id;
mysql_query($query);
}
else
{
$query="UPDATE tbllead_requirment_detail SET 'status' ='0' WHERE id=".$u_id;
mysql_query($query);
}
}
	  public function list_leads_data($id = null) {
        $this->db->select()->from('tblleads');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    } 

	 public function lead_carry_won_data_show($frommonth = '', $fromyear = '',$tomonth = '', $toyear = '',$staff_id='') {
        
		$this->db->select('*')->from('tbl_carry_leadwon_report');
		
		$report_from = $fromyear.'-'.$frommonth.'-01';
		$report_to = $toyear.'-'.$tomonth.'-31';
		
		
        if ($staff_id !='') {
			$arruser = 'staff_id = "'.$staff_id.'" AND (created_date BETWEEN "' . $report_from . '" AND "' . $report_to . '" )';
		
            $this->db->where($arruser);
        }else{
			$arruser = 'created_date BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59"';
            $this->db->where($arruser);
		}
     
        $this->db->order_by('id');
		$query = $this->db->get();
        return $query->result_array();
    }
	
    public function lead_won_carry($id = null) {
	   
	   
	   if (get_staff_role() == 1) {
				$query = $this->db->query("SELECT tbl_carry_leadwon.id,tbl_carry_leadwon.lead_id,tbl_carry_leadwon.staff_id,tbl_carry_leadwon.repoting_to,tbl_carry_leadwon.close_won,tbl_carry_leadwon.last_executed,tbl_carry_leadwon.state,tbl_carry_leadwon.executed,tbl_carry_leadwon.carry_forward,tbl_carry_leadwon.status,tbl_carry_leadwon.month,tbl_carry_leadwon.year,tbl_carry_leadwon.customer_name,tbl_carry_leadwon.created,tbl_carry_leadwon.last_changed,tbl_carry_leadwon.customer_group  FROM tbl_carry_leadwon WHERE staff_id ='" . get_staff_user_id() . "'");
			}
     else{
			
			 if(get_staff_role() == 0){
	$query = $this->db->query('SELECT * FROM tbl_carry_leadwon');
		}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$query = $this->db->query("SELECT * FROM tbl_carry_leadwon");
				
			}
			else if(get_staff_role() != 4 || get_staff_role() != 7) 
			{
				$query = $this->db->query('SELECT tbl_carry_leadwon.id,tbl_carry_leadwon.lead_id,tbl_carry_leadwon.staff_id,tbl_carry_leadwon.repoting_to,tbl_carry_leadwon.close_won,tbl_carry_leadwon.last_executed,tbl_carry_leadwon.state,tbl_carry_leadwon.executed,tbl_carry_leadwon.carry_forward,tbl_carry_leadwon.status,tbl_carry_leadwon.month,tbl_carry_leadwon.year,tbl_carry_leadwon.customer_name,tbl_carry_leadwon.created,tbl_carry_leadwon.last_changed,tbl_carry_leadwon.customer_group FROM tbl_carry_leadwon WHERE repoting_to LIKE "%'.get_staff_user_id().'%" AND id = "'.$id.'"');
			
			}
		} 
		
		
            return $query->result_array();
        
    } 
	public function get_result_carry_leadwon($id){
		  $this->db->select()->from('tbl_carry_leadwon_report');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
		
		
		
		
		
		
	}
	public function lead_won($id = null) {
        $this->db->select()->from('tbl_carry_leadwon_report');
        
        if ($id != null) {
           $this->db->where('staff_id', get_staff_user_id());
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->result_array();
        } else {
            return $query->result_array();
        }
    } 
	
	 public function getlist_leads_data_ghui($id = null) {
       $this->db->select('*,tblleads.name as lead_name, tblleads.customer_name,tblleatblleadsds.customer_type,tblleads.customer_group,tblleads.city as city_name,tblleads.lead_contact , tblleads.id,tblleads.assigned,tblleadsstatus.name as status_name,tblleadssources.name as source_name,tbl_state.state as state_name,tblregion.region as region_name,tblclients.company as company_name,tblclients.userid,tblclients.company as company_name,tbl_city.city,tblcontacts.firstname,tblstaff.staffid,tblcustomersgroups.name as group_name,tblcustomer_type.name as customer_name');
        $this->db->join('tblleadsstatus', 'tblleadsstatus.id=tblleads.status', 'left');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left'); 
		$this->db->join('tblclients', 'tblclients.userid=tblleads.customer_name', 'left');
		$this->db->join('tbl_state', 'tbl_state.id=tblleads.state', 'left'); 
		$this->db->join('tbl_city', 'tbl_city.id=tblleads.city', 'left'); 
		$this->db->join('tblregion', 'tblregion.id=tblleads.region', 'left'); 
		$this->db->join('tblcustomersgroups', 'tblcustomersgroups.id=tblleads.customer_group', 'left'); 
		$this->db->join('tblcustomer_type', 'tblcustomer_type.code=tblleads.customer_type', 'left'); 
		$this->db->join('tblcontacts', 'tblcontacts.userid=tblleads.lead_contact', 'left');
		$this->db->join('tblstaff', 'tblstaff.staffid=tblleads.assigned', 'left'); 
        if ($id != null) {
            $this->db->where('assigned', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    } 
	
	
	public function list_clients_data_detail($id = null) {
        $this->db->select()->from('tblclients');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    } 

	public function list_leads_data_city($id = null) {
        $this->db->select()->from('tbl_city');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }  
	
	public function customer_type_value($id = null) {

		
        $this->db->select()->from('customer_type_value');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('customer_name','alphabetical' ,'ASC');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
	public function document_required_value($id = null) {
        $this->db->select()->from('document_required');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
	public function document_required_status_inactive($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('document_required', $data);
        return true;
    } 
	public function document_required_status_active($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('document_required', $data);
        return true;
    } 
	
	
	public function customer_type_value_byname($customer_name='')
    {
		$sql .= "SELECT customer_type FROM tblclients WHERE userid='".$customer_name."'";
        return $this->db->query($sql)->row()->customer_type;
  
    }
	public function customer_group_byname($customer_group='')
    {
		$sql .= "SELECT name FROM tblcustomersgroups WHERE id='".$customer_group."'";
        return $this->db->query($sql)->row()->name;
  
    }
	public function get_customer_name($customer_name='')
    {
		$sql .= "SELECT company FROM tblclients WHERE userid='".$customer_name."'";
        return $this->db->query($sql)->row()->company;
  
    }
	
	public function get_status_per($id='')
    {
		$sql = "SELECT weighted FROM tblleadsstatus WHERE name='".$id."'";
        return $this->db->query($sql)->row()->weighted;
  
    }
	public function get_region_name($state_id='')
    {
		$sql = "SELECT region FROM tblregion_state WHERE state_id='".$state_id."'";
        $region = $this->db->query($sql)->row()->region;
		
		$sql = "SELECT region FROM tblregion WHERE id='".$region."'";
        return $this->db->query($sql)->row()->region;
	}
	
	public function get_product_description($lead_id='')
    {
		/* $sql = "SELECT id FROM tbllead_requirment WHERE lead_id='".$lead_id."'";
        return $id = $this->db->query($sql)->row()->id; */
		
		$sql = "SELECT DISTINCT tblitems_groups.name as cat_name FROM tbllead_requirment_detail LEFT JOIN tblitems_groups ON tblitems_groups.id = tbllead_requirment_detail.category_id WHERE tbllead_requirment_detail.lead_requirment_id='".$lead_id."'";
		
        return $this->db->query($sql)->result_array();  
		
		
		/* $this->db->select('tblitems_groups.name as cat_name,tbllead_requirment.lead_id,tbllead_requirment_detail.category_id,tblitems_groups.id as group_id');
        $this->db->join('tblitems_groups', 'tblitems_groups.id=tbllead_requirment_detail.category_id', 'left');
        $this->db->join('tbllead_requirment', 'tbllead_requirment.id=tbllead_requirment_detail.lead_requirment_id', 'left'); 
		 $this->db->where('tbllead_requirment.lead_id', $lead_id);
		
        $query = $this->db->get();
       
		
		return $query->result_array(); */
		
    }
	
	
	
	 public function list_customer_type($id = null) {
        $this->db->select()->from('tblcustomer_type');
        if ($id != null) {
            $this->db->where('lead_id', $id);
        } else {
            $this->db->order_by('lead_id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
	 
	 public function lookup($keyword){ 
        $this->db->select('company')->from('tblleads');
		$this->db->distinct();
        $this->db->like('company',$keyword,'after'); 
     
        $query = $this->db->get();     
        return $query->result(); 
    }
	 
    public function get_lead($id = '', $where = array())
    {
        $this->db->select('tblleads.company as company,tblleads.name, tblleads.id');
        $this->db->distinct();
		$ignore = array(0, 1);
		$this->db->where_not_in('status', $ignore);
        
        return $this->db->get('tblleads')->result_array();
    }

	public function get($id = '', $where = array())
    {
        $this->db->select('*,tblleads.name as lead_name, tblleads.id,tblleadsstatus.name as status_name,tblleadssources.name as source_name,tbl_state.state as state_name,tblleads.customer_name,tbl_city.city as city_name,tbl_country.country as country_name,tblregion.region as region_name,tblcustomersgroups.name as group_name,');
        $this->db->join('tblleadsstatus', 'tblleadsstatus.id=tblleads.status', 'left');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left');
		$this->db->join('tbl_state', 'tbl_state.id=tblleads.state', 'left'); 
		$this->db->join('tbl_city', 'tbl_city.id=tblleads.city', 'left'); 
		$this->db->join('tblcustomersgroups', 'tblcustomersgroups.id=tblleads.customer_name', 'left');
		$this->db->join('tbl_country', 'tbl_country.id=tblleads.country', 'left'); 
		$this->db->join('tblregion', 'tblregion.id=tblleads.region', 'left'); 
	

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('tblleads.id', $id);
            $lead = $this->db->get('tblleads')->row();
            if ($lead) {
                if ($lead->from_form_id != 0) {
                    $lead->form_data = $this->get_form(array(
                        'id' => $lead->from_form_id,
                    ));
                }
                $lead->attachments = $this->get_lead_attachments($id);
            }

            return $lead;
        }

        return $this->db->get('tblleads')->result_array();
    }
	public function contact_details(){
		
		$this->db->select('tblstaff.staffid,tblstaff.firstname,staff.lastname,tblleads.name as lead_name, tblleads.customer_name,tblleads.customer_type,tblleads.customer_group,tblleads.city as city_name,tblleads.lead_contact , tblleads.id,tblleads.assigned,tblleadsstatus.name as status_name,tblleadssources.name as source_name,tbl_state.state as state_name,tblregion.region as region_name,tblclients.company as company_name,tblclients.userid,tblclients.company as company_name,tbl_city.city,tblcontacts.firstname,tblcustomersgroups.name as group_name,tblcustomer_type.name as customer_name')->from('tblstaff');
            $this->db->join('tblstaff', 'tblleads.assigned = tblstaff.staffid');

            $query = $this->db->get();
            return $query->result_array();
		
		
		
	}
	
	public function get_company_detail($id = '', $where = array())
    {
        $this->db->select('*,tblleads.name as lead_name,tblleads.status as lead_status, tblleads.customer_name as customerid,tblleads.customer_type,tblleads.customer_group,tblleads.city as city_name,tblleads.lead_contact , tblleads.id,tblleads.assigned,tblleadsstatus.name as status_name,tblleadssources.name as source_name,tbl_state.state as state_name,tblregion.region as region_name,tblclients.company as company_name,tblclients.userid,tblclients.company as company_name,tbl_city.city,tblcontacts.firstname,tblstaff.staffid,tblcustomersgroups.name as group_name,tblcustomer_type.name as customer_name');
        $this->db->join('tblleadsstatus', 'tblleadsstatus.id=tblleads.status', 'left');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left'); 
		$this->db->join('tblclients', 'tblclients.userid=tblleads.customer_name', 'left');
		$this->db->join('tbl_state', 'tbl_state.id=tblleads.state', 'left'); 
		$this->db->join('tbl_city', 'tbl_city.id=tblleads.city', 'left'); 
		$this->db->join('tblregion', 'tblregion.id=tblleads.region', 'left'); 
		$this->db->join('tblcustomersgroups', 'tblcustomersgroups.id=tblleads.customer_group', 'left'); 
		$this->db->join('tblcustomer_type', 'tblcustomer_type.code=tblleads.customer_type', 'left'); 
		$this->db->join('tblcontacts', 'tblcontacts.userid=tblleads.lead_contact', 'left');
		$this->db->join('tblstaff', 'tblstaff.staffid=tblleads.assigned', 'left'); 
	

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('tblleads.id', $id);
            $lead = $this->db->get('tblleads')->row();
            if ($lead) {
                if ($lead->from_form_id != 0) {
                    $lead->form_data = $this->get_form(array(
                        'id' => $lead->from_form_id,
                    ));
                }
                $lead->attachments = $this->get_lead_attachments($id);
            }

            return $lead;
        }

        return $this->db->get('tblleads')->result_array();
    }
	public function getlist_leads_data($id = '')
    {
		
        $this->db->select(
		'tblcustomersgroups.name as group_name,
		tblleads.name as lead_name,
		tblleads.city as city_name,
		tblleads.lead_contact , 
		tblleads.id,
		tblleads.dateadded,
		tblleads.opportunity,
		tblleads.description,
		tblleads.assigned,
		tbl_state.state as state_name,
		tblclients.company as company_name,
		tblclients.userid,tblclients.company as company_name,
		tblcustomer_type.name as customer_name');
        $this->db->join('tblclients', 'tblclients.userid=tblleads.customer_name', 'left');
		$this->db->join('tbl_state', 'tbl_state.id=tblleads.state', 'left'); 
		$this->db->join('tbl_city', 'tbl_city.id=tblleads.city', 'left'); 
		$this->db->join('tblcustomersgroups', 'tblcustomersgroups.id=tblleads.customer_group', 'left'); 
		$this->db->join('tblcustomer_type', 'tblcustomer_type.code=tblleads.customer_type', 'left'); 
		
		$this->db->where('tblleads.assigned', $id);
		return $this->db->get('tblleads')->result_array();
           
        
    }
	
	
	
public function get_tbl_city($id)
    {
        $this->db->select('tbl_city.id as city_id,tbl_city.city,tbl_country.country as country_name, tbl_country.id,tbl_state.id,tbl_state.state as state_name');
		$this->db->join('tbl_country', 'tbl_country.id=tbl_city.country_id', 'left');
    
		$this->db->join('tbl_state', 'tbl_state.id=tbl_city.state_id', 'left'); 

        if (is_numeric($id)) {
            $this->db->where('tbl_city.id', $id);
            $lead = $this->db->get('tbl_city')->row();
            if ($lead) {
                if ($lead->from_form_id != 0) {
                    $lead->form_data = $this->get_form(array(
                        'id' => $lead->from_form_id,
                    ));
                }
               
            }

            return $lead;
        }

        return $this->db->get('tbl_city')->result_array();
    }

    public function do_kanban_query($status, $search = '', $page = 1, $sort = array(), $count = false)
    {
        $limit                         = get_option('leads_kanban_limit');
        $default_leads_kanban_sort      = get_option('default_leads_kanban_sort');
        $default_leads_kanban_sort_type = get_option('default_leads_kanban_sort_type');
        $has_permission_view = has_permission('leads', '', 'view');

        $this->db->select('tblleads.name as lead_name,tblleadssources.name as source_name,tblleads.id as id,tblleads.assigned,tblleads.email,tblleads.phonenumber,tblleads.company,tblleads.dateadded,tblleads.status,tblleads.lastcontact,(SELECT COUNT(*) FROM tblclients WHERE leadid=tblleads.id) as is_lead_client, (SELECT COUNT(id) FROM tblfiles WHERE rel_id=tblleads.id AND rel_type="lead") as total_files, (SELECT COUNT(id) FROM tblnotes WHERE rel_id=tblleads.id AND rel_type="lead") as total_notes,(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tbltags_in JOIN tbltags ON tbltags_in.tag_id = tbltags.id WHERE rel_id = tblleads.id and rel_type="lead" ORDER by tag_order ASC) as tags');
        $this->db->from('tblleads');
        $this->db->join('tblleadssources', 'tblleadssources.id=tblleads.source', 'left');
        $this->db->join('tblstaff', 'tblstaff.staffid=tblleads.assigned', 'left');
        $this->db->where('status', $status);
        if (!$has_permission_view) {
            $this->db->where('(assigned = ' . get_staff_user_id() . ' OR addedfrom=' . get_staff_user_id() . ' OR is_public=1)');
        }
        if ($search != '') {
            if (!_startsWith($search, '#')) {
                $this->db->where('(tblleads.name LIKE "%' . $search . '%" OR tblleadssources.name LIKE "%' . $search . '%" OR tblleads.email LIKE "%' . $search . '%" OR tblleads.phonenumber LIKE "%' . $search . '%" OR tblleads.company LIKE "%' . $search . '%" OR CONCAT(tblstaff.firstname, \' \', tblstaff.lastname) LIKE "%' . $search . '%")');
            } else {
                $this->db->where('tblleads.id IN
                (SELECT rel_id FROM tbltags_in WHERE tag_id IN
                (SELECT id FROM tbltags WHERE name="' . strafter($search, '#') . '")
                AND tbltags_in.rel_type=\'lead\' GROUP BY rel_id HAVING COUNT(tag_id) = 1)
                ');
            }
        }

        if (isset($sort['sort_by']) && $sort['sort_by'] && isset($sort['sort']) && $sort['sort']) {
            $this->db->order_by($sort['sort_by'], $sort['sort']);
        } else {
            $this->db->order_by($default_leads_kanban_sort, $default_leads_kanban_sort_type);
        }

        if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * $limit);
                $this->db->limit($limit, $position);
            } else {
                $this->db->limit($limit);
            }
        }

        if ($count == false) {
            return $this->db->get()->result_array();
        } else {
            return $this->db->count_all_results();
        }
    }

    /**
     * Add new lead to database
     * @param mixed $data lead data
     * @return mixed false || leadid
     */
    public function add($data)
    {
        if (isset($data['custom_contact_date']) || isset($data['custom_contact_date'])) {
            if (isset($data['contacted_today'])) {
                $data['lastcontact'] = date('Y-m-d H:i:s');
                unset($data['contacted_today']);
            } else {
                $data['lastcontact'] = to_sql_date($data['custom_contact_date'], true);
            }
        }

        if (isset($data['is_public']) && ($data['is_public'] == 1 || $data['is_public'] === 'on')) {
            $data['is_public'] = 1;
        } else {
            $data['is_public'] = 0;
        }

        if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
            $data['country'] = 0;
        }

        if (isset($data['custom_contact_date'])) {
            unset($data['custom_contact_date']);
        }

        $data['description'] = nl2br($data['description']);
        $data['dateadded']   = date('Y-m-d H:i:s');
        $data['addedfrom']   = get_staff_user_id();

        $data                = do_action('before_lead_added', $data);

        $tags = '';
        if (isset($data['tags'])) {
            $tags  = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);
        $data['state'] = $this->get_staff_state_byid(get_staff_user_id());
		
        $this->db->insert('tblleads', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Lead Added [Name: ' . $data['name'] . ']');
            $this->log_lead_activity($insert_id, 'not_lead_activity_created');

            handle_tags_save($tags, $insert_id, 'lead');

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            $this->lead_assigned_member_notification($insert_id, $data['assigned']);
            do_action('lead_created', $insert_id);

            return $insert_id;
        }

        return false;
    }

    public function lead_assigned_member_notification($lead_id, $assigned, $integration = false)
    {
        if ((!empty($assigned) && $assigned != 0)) {
            if ($integration == false) {
                if ($assigned == get_staff_user_id()) {
                    return false;
                }
            }

            $name = $this->db->select('name')->from('tblleads')->where('id', $lead_id)->get()->row()->name;

            $notification_data = array(
                'description' => ($integration == false) ? 'not_assigned_lead_to_you' : 'not_lead_assigned_from_form',
                'touserid' => $assigned,
                'link' => '#leadid=' . $lead_id,
                'additional_data' => ($integration == false ? serialize(array(
                    $name,
                )) : serialize(array())),
            );

            if ($integration != false) {
                $notification_data['fromcompany'] = 1;
            }

            if (add_notification($notification_data)) {
                pusher_trigger_notification(array($assigned));
            }

            $this->db->where('staffid', $assigned);
            $email = $this->db->get('tblstaff')->row()->email;

            $this->load->model('emails_model');
            $merge_fields = array();
            $merge_fields = array_merge($merge_fields, get_lead_merge_fields($lead_id));
            $this->emails_model->send_email_template('new-lead-assigned', $email, $merge_fields);

            $this->db->where('id', $lead_id);
            $this->db->update('tblleads', array(
                'dateassigned' => date('Y-m-d'),
            ));

            $not_additional_data = array(
                get_staff_full_name(),
                '<a href="' . admin_url('profile/' . $assigned) . '" target="_blank">' . get_staff_full_name($assigned) . '</a>',
            );

            if ($integration == true) {
                unset($not_additional_data[0]);
                array_values(($not_additional_data));
            }

            $not_additional_data = serialize($not_additional_data);

            $not_desc = ($integration == false ? 'not_lead_activity_assigned_to' : 'not_lead_activity_assigned_from_form');
            $this->log_lead_activity($lead_id, $not_desc, $integration, $not_additional_data);
        }
    }

    /**
     * Update lead
     * @param  array $data lead data
     * @param  mixed $id   leadid
     * @return boolean
     */
    public function update($data, $id)
    {
        $current_lead_data = $this->get($id);
        $current_status    = $this->get_status($current_lead_data->status);
        if ($current_status) {
            $current_status_id = $current_status->id;
            $current_status    = $current_status->name;
        } else {
            if ($current_lead_data->junk == 1) {
                $current_status = _l('lead_junk');
            } elseif ($current_lead_data->lost == 1) {
                $current_status = _l('lead_lost');
            } else {
                $current_status = '';
            }
            $current_status_id = 0;
        }

        $affectedRows = 0;
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        if (!defined('API')) {
            if (isset($data['is_public'])) {
                $data['is_public'] = 1;
            } else {
                $data['is_public'] = 0;
            }

            if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
                $data['country'] = 0;
            }

            $data['description'] = nl2br($data['description']);
        }

        if (isset($data['lastcontact']) && $data['lastcontact'] == '' || isset($data['lastcontact']) && $data['lastcontact'] == null) {
            $data['lastcontact'] = null;
        } elseif (isset($data['lastcontact'])) {
            $data['lastcontact'] = to_sql_date($data['lastcontact'], true);
        }

        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'lead')) {
                $affectedRows++;
            }
            unset($data['tags']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);

        $this->db->where('id', $id);
        $this->db->update('tblleads', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if (isset($data['status']) && $current_status_id != $data['status']) {
                $this->db->where('id', $id);
                $this->db->update('tblleads', array(
                    'last_status_change' => date('Y-m-d H:i:s'),
                ));
                $new_status_name = $this->get_status($data['status'])->name;
                $this->log_lead_activity($id, 'not_lead_activity_status_updated', false, serialize(array(
                    get_staff_full_name(),
                    $current_status,
                    $new_status_name,
                )));

                do_action('lead_status_changed', array('lead_id'=>$id, 'old_status'=>$current_status_id, 'new_status'=>$data['status']));
            }

            if (($current_lead_data->junk == 1 || $current_lead_data->lost == 1) && $data['status'] != 0) {
                $this->db->where('id', $id);
                $this->db->update('tblleads', array(
                    'junk' => 0,
                    'lost' => 0,
                ));
            }

            if (isset($data['assigned'])) {
                if ($current_lead_data->assigned != $data['assigned'] && (!empty($data['assigned']) && $data['assigned'] != 0)) {
                    $this->lead_assigned_member_notification($id, $data['assigned']);
                }
            }
            logActivity('Lead Updated [Name: ' . $data['name'] . ']');

            return true;
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Delete lead from database and all connections
     * @param  mixed $id leadid
     * @return boolean
     */
    public function delete($id)
    {
        $affectedRows = 0;

        do_action('before_lead_deleted', $id);

        $this->db->where('id', $id);
        $this->db->delete('tblleads');
        if ($this->db->affected_rows() > 0) {
            logActivity('Lead Deleted [Deleted by: ' . get_staff_full_name() . ', LeadID: ' . $id . ']');

            $attachments = $this->get_lead_attachments($id);
            foreach ($attachments as $attachment) {
                $this->delete_lead_attachment($attachment['id']);
            }

            // Delete the custom field values
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'leads');
            $this->db->delete('tblcustomfieldsvalues');

            $this->db->where('leadid', $id);
            $this->db->delete('tblleadactivitylog');

            $this->db->where('leadid', $id);
            $this->db->delete('tblleadsemailintegrationemails');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $this->db->delete('tblnotes');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete('tblreminders');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete('tbltags_in');

            $this->load->model('proposals_model');
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $proposals = $this->db->get('tblproposals')->result_array();

            foreach ($proposals as $proposal) {
                $this->proposals_model->delete($proposal['id']);
            }

            // Get related tasks
            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get('tblstafftasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }

            $affectedRows++;
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Mark lead as lost
     * @param  mixed $id lead id
     * @return boolean
     */
    public function mark_as_lost($id)
    {
        $this->db->select('status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', array(
            'lost' => 1,
            'status' => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status' => $last_lead_status,
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_lost');
            logActivity('Lead Marked as Lost [LeadID: ' . $id . ']');
            do_action('lead_marked_as_lost', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as lost
     * @param  mixed $id leadid
     * @return boolean
     */
    public function unmark_as_lost($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', array(
            'lost' => 0,
            'status' => $last_lead_status,
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_lost');
            logActivity('Lead Unmarked as Lost [LeadID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Mark lead as junk
     * @param  mixed $id lead id
     * @return boolean
     */
    public function mark_as_junk($id)
    {
        $this->db->select('status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', array(
            'junk' => 1,
            'status' => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status' => $last_lead_status,
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_junk');
            logActivity('Lead Marked as Junk [LeadID: ' . $id . ']');
            do_action('lead_marked_as_junk', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as junk
     * @param  mixed $id leadid
     * @return boolean
     */
    public function unmark_as_junk($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from('tblleads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update('tblleads', array(
            'junk' => 0,
            'status' => $last_lead_status,
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_junk');
            logActivity('Lead Unmarked as Junk [LeadID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Get lead attachments
     * @since Version 1.0.4
     * @param  mixed $id lead id
     * @return array
     */
    public function get_lead_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'lead');
        $this->db->order_by('dateadded', 'DESC');

        return $this->db->get('tblfiles')->result_array();
    }

    public function add_attachment_to_database($lead_id, $attachment, $external = false, $form_activity = false)
    {
        $this->misc_model->add_attachment_to_database($lead_id, 'lead', $attachment, $external);

        if ($form_activity == false) {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_added_attachment');
        } else {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_log_attachment', true, serialize(array(
                $form_activity,
            )));
        }

        // No notification when attachment is imported from web to lead form
        if ($form_activity == false) {
            $lead         = $this->get($lead_id);
            $not_user_ids = array();
            if ($lead->addedfrom != get_staff_user_id()) {
                array_push($not_user_ids, $lead->addedfrom);
            }
            if ($lead->assigned != get_staff_user_id() && $lead->assigned != 0) {
                array_push($not_user_ids, $lead->assigned);
            }
            $notifiedUsers = array();
            foreach ($not_user_ids as $uid) {
                $notified = add_notification(array(
                    'description' => 'not_lead_added_attachment',
                    'touserid' => $uid,
                    'link' => '#leadid=' . $lead_id,
                    'additional_data' => serialize(array(
                        $lead->name,
                    )),
                ));
                if ($notified) {
                    array_push($notifiedUsers, $uid);
                }
            }
            pusher_trigger_notification($notifiedUsers);
        }
    }

    /**
     * Delete lead attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_lead_attachment($id)
    {
        $attachment = $this->get_lead_attachments('', $id);
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('lead') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Lead Attachment Deleted [LeadID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('lead') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('lead') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('lead') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    // Sources

    /**
     * Get leads sources
     * @param  mixed $id Optional - Source ID
     * @return mixed object if id passed else array
     */
    public function get_source($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadssources')->row();
        }

        return $this->db->get('tblleadssources')->result_array();
    }
	public function source_status_inactive($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('tblleadssources', $data);
        return true;
    } 
	public function source_status_active($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('tblleadssources', $data);
        return true;
    } 
	
	
	public function get_contact_type($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblcontacts')->row();
        }

        return $this->db->get('tblcontacts')->result_array();
    }

    /**
     * Add new lead source
     * @param mixed $data source data
     */
    public function add_source($data)
    {
        $this->db->insert('tblleadssources', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Leads Source Added [SourceID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }

        return $insert_id;
    }  

	public function add_region($data)
    {
        $this->db->insert('tblregion', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Region Added [SourceID: ' . $insert_id . ', Name: ' . $data['region'] . ']');
        }

        return $insert_id;
    }

    /**
     * Update lead source
     * @param  mixed $data source data
     * @param  mixed $id   source id
     * @return boolean
     */
    public function update_source($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblleadssources', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Source Updated [SourceID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    } public function update_region($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblregion', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Source Updated [SourceID: ' . $id . ', Name: ' . $data['region'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead source from database
     * @param  mixed $id source id
     * @return mixed
     */
    public function delete_source($id)
    {
        $current = $this->get_source($id);
        // Check if is already using in table
        if (is_reference_in_table('source', 'tblleads', $id) || is_reference_in_table('lead_source', 'tblleadsintegration', $id)) {
            return array(
                'referenced' => true,
            );
        }
        $this->db->where('id', $id);
        $this->db->delete('tblleadssources');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_source') == $id) {
                update_option('leads_default_source', '');
            }
            logActivity('Leads Source Deleted [LeadID: ' . $id . ']');

            return true;
        }

        return false;
    }

    // Statuses

    /**
     * Get lead statuses
     * @param  mixed $id status id
     * @return mixed      object if id passed else array
     */
    /* public function get_status($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblleadsstatus')->row();
        }
        $this->db->order_by('id', 'asc');

        return $this->db->get('tblleadsstatus')->result_array();
    } */
	public function get_status($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('tblleadsstatus.id', $id);
            
            return $this->db->get('tblleadsstatus')->row();
        }
        $this->db->order_by('tblleadsstatus.id', 'asc');
        
        return $this->db->get('tblleadsstatus')->result_array();
    }
	public function get_depo_master($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('depo_master')->row();
        }
        $this->db->order_by('depcode', 'asc');

        return $this->db->get('depo_master')->result_array();
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
	
	 public function get_staff($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('tblstaff')->row();
        }
        $this->db->order_by('statusorder', 'asc');

        return $this->db->get('tblleadsstatus')->result_array();
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
	 public function get_segment($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
			$this->db->order_by('name', 'asc');
            return $this->db->get('segment')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get('segment')->result_array();
    }
	
	
	
	public function get_loss_status($id = '', $where = array())
    {
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get('status_loss')->row();
        }
      
        return $this->db->get('status_loss')->result_array();
    }
	
    /**
     * Add new lead status
     * @param array $data lead status data
     */
    public function add_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = do_action('default_lead_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows('tblleadsstatus') + 1;
        }

        $this->db->insert('tblleadsstatus', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Leads Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }
  public function add_customer($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = do_action('default_lead_customer_color', '#757575');
        }

        if (!isset($data['code'])) {
            $data['code'] = total_rows('tblcustomer_type') + 1;
        }

        $this->db->insert('tblcustomer_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Customer Added [CustomerID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    } 
	
	public function add_segment($data)
    {
      
        $this->db->insert('segment', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New segment Added [CustomerID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }
	
	public function add_depo_master($data)
    {
      
        $this->db->insert('depo_master', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Depo Master Added [CustomerID: ' . $insert_id . ', Name: ' . $data['description'] . ',Code: '.$data['depcode'].']');

            return $insert_id;
        }

        return false;
    }
 public function add_document_required($data)
    {

        $this->db->insert('document_required', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Customer Added [DocumentID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

	public function add_loss_status($data)
    {
      
        $this->db->insert('status_loss', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New status region added [status ID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }
	
	 public function city_add($data){
    $this->db->insert('tbl_city',$data);
}
	
   public function update_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblleadsstatus', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    } 
	
	public function update_customer($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblcustomer_type', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads Customer Updated [CustomerID: ' . $id . ', Name: ' . $data['name'] . ',Code:'.$data['code'].']');

            return true;
        }

        return false;
    }
	
	public function update_segment($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('segment', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Segment Updated [CustomerID: ' . $id . ', Name: ' . $data['name'] . ',Code:'.$data['code'].']');

            return true;
        }

        return false;
    }
	public function update_depo_master($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('depo_master', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Depo Master Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');
            return true;
        }

        return false;
    }
	
	public function update_document_required($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('document_required', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Document Updated [DocumentID: ' . $id . ', Name: ' . $data['name'] . ',Code:'.$data['code'].']');

            return true;
        }

        return false;
    }

    public function update_loss_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('status_loss', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Leads status region Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }
	
	public function update_city($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_city', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('City Updated [CityID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead status from database
     * @param  mixed $id status id
     * @return boolean
     */
    public function delete_status($id)
    {
        $current = $this->get_status($id);
        // Check if is already using in table
        if (is_reference_in_table('status', 'tblleads', $id) || is_reference_in_table('lead_status', 'tblleadsintegration', $id)) {
            return array(
                'referenced' => true,
            );
        }

        $this->db->where('id', $id);
        $this->db->delete('tblleadsstatus');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_status') == $id) {
                update_option('leads_default_status', '');
            }
            logActivity('Leads Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    } 
	
	public function customer_status_inactive($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('tblcustomer_type', $data);
        return true;
    } 
	public function customer_status_active($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('tblcustomer_type', $data);
        return true;
    } 
	public function segment_status_inactive($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('segment', $data);
        return true;
    } 
	public function segment_status_active($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('segment', $data);
        return true;
    } 
	
	
	public function delete_customer($id)
    {        
		$this->db->where('id', $id);
		$this->db->delete('tblcustomer_type');

    }
	
	public function delete_segment($id)
    {
        
	$this->db->where('id', $id);
	$this->db->delete('segment');

    }
	
	public function delete_depo_master($id)
    {
        
$this->db->where('id', $id);
$this->db->delete('depo_master');

    }
	
	 public function get_lead_no_status($status, $staff_id = '', $report_months = '',$from_date='',$to_date='')
    {
        if ($staff_id != '' && $report_months != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } else if ($role == 7 || $role == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )  AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else if ($staff_id != '' && $report_months == '') {
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
        } else if ($staff_id == '' && $report_months != '') {
            
            if (get_staff_role() == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else {
            if (get_staff_role() == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND tblleads.state IN(' . get_staff_state_id() . ')');
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
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
        ;
        
        return $total_staff + $total_own;
        
    }
    
	public function get_staff_role_id($staff_id){
		$this->db->where('staffid', $staff_id);
        return $this->db->get('tblstaff')->row()->role;
	}
	public function get_staff_state_byid($staff_id){
		$this->db->where('staffid', $staff_id);
        return $this->db->get('tblstaff')->row()->state;
	}
	/* public function get_lead_no_custtype($cust)
    {
		if (get_staff_role() == 1) {
			$query = $this->db->query('SELECT id FROM tblleads where customer_type="'.$cust.'" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
		}
		else if(get_staff_role() == 7 || get_staff_role() == 4) 
		{
			$query = $this->db->query('SELECT id FROM tblleads where customer_type="'.$cust.'" AND tblleads.state IN('. get_staff_state_id() .')');
		}
		else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
		{
			$query = $this->db->query('SELECT id FROM tblleads where customer_type="'.$cust.'" AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) ');
		}
       return $query->num_rows();

    }
	 */
	 public function get_lead_no_custtype($cust, $staff_id = '', $report_months = '',$from_date='',$to_date='')
    {
        if ($staff_id != '' && $report_months != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } else if ($role == 7 || $role == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)  AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else if ($staff_id != '' && $report_months == '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                
            } else if ($role == 7 || $role == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . $staff_state_id . ')');
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" )');
                
            }
            
            if ($role != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                    
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
                    
                }
                $total_own = $query1->num_rows();
            }
        } else if ($staff_id == '' && $report_months != '') {
            
            if (get_staff_role() == 1) {
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $month . '%")');
                    
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND tblleads.dateadded LIKE ("' . $year . '%")');
                    
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                } else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ') AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    
                }
                
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                
                if ($report_months == 'this_month') {
                    $month = date('Y-m');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $month . '%")');
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND tblleads.dateadded LIKE ("' . $year . '%")');
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                    $report_to   = date('Y-m-d');
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
                    $query       = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staff_id.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staff_id.',%" ) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                }
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                } else {
                    
                    if ($report_months == 'this_month') {
                        $month  = date('Y-m');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'last_month') {
                        $month  = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $month . '%")');
                    } else if ($report_months == 'this_year') {
                        $year   = date('Y');
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'last_year') {
                        $year   = date('Y', strtotime(date('Y') . " -1 year"));
                        $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND tblleads.dateadded LIKE ("' . $year . '%")');
                    } else if ($report_months == 'report_sales_months_three_months') {
                        $report_from = date('Y-m-01', strtotime("-2 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_six_months') {
                        $report_from = date('Y-m-01', strtotime("-5 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (tblleads.dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if ($report_months == 'report_sales_months_twelve_months') {
                        $report_from = date('Y-m-01', strtotime("-11 MONTH"));
                        $report_to   = date('Y-m-d');
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    } else if($report_months =='custom') {
						$report_from = $from_date;
						$report_to= $to_date;
                        $query1      = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1) AND (dateadded BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" )');
                    }
                }
                $total_own = $query1->num_rows();
            }
        } else {
            if (get_staff_role() == 1) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
            } else if (get_staff_role() == 7 || get_staff_role() == 4) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND tblleads.state IN(' . get_staff_state_id() . ')');
            } else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
                $query = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
            }
            
            if (get_staff_role() != 1) {
                
                if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where customer_type="' . $cust . '"  AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
                }
                $total_own = $query1->num_rows();
            }
        }
        
        
        $total_staff = $query->num_rows();
      
        
        return $total_staff + $total_own;
        
    }
    
	public function delete_loss_status($id)
    {
        
		$this->db->where('id', $id);
		$this->db->delete('status_loss');

    }
	
	public function loss_status_inactive($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('status_loss', $data);
        return true;
    } 
	public function loss_status_active($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('status_loss', $data);
        return true;
    } 

    /**
     * Update canban lead status when drag and drop
     * @param  array $data lead data
     * @return boolean
     */
    public function update_lead_status($data)
    {
        $this->db->select('status');
        $this->db->where('id', $data['leadid']);
        $_old = $this->db->get('tblleads')->row();

        $old_status = '';

        if ($_old) {
            $old_status = $this->get_status($_old->status);
            if ($old_status) {
                $old_status = $old_status->name;
            }
        }

        $affectedRows   = 0;
        $current_status = $this->get_status($data['status'])->name;

        $this->db->where('id', $data['leadid']);
        $this->db->update('tblleads', array(
            'status' => $data['status'],
        ));

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if ($current_status != $old_status && $old_status != '') {
                $_log_message    = 'not_lead_activity_status_updated';
                $additional_data = serialize(array(
                    get_staff_full_name(),
                    $old_status,
                    $current_status,
                ));

                do_action('lead_status_changed', array('lead_id'=>$data['leadid'], 'old_status'=>$old_status, 'new_status'=>$current_status));
            }
            $this->db->where('id', $data['leadid']);
            $this->db->update('tblleads', array(
                'last_status_change' => date('Y-m-d H:i:s'),
            ));
        }
        if (isset($data['order'])) {
            foreach ($data['order'] as $order_data) {
                $this->db->where('id', $order_data[0]);
                $this->db->update('tblleads', array(
                    'leadorder' => $order_data[1],
                ));
            }
        }
        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }
            $this->log_lead_activity($data['leadid'], $_log_message, false, $additional_data);

            return true;
        }

        return false;
    }

    /* Ajax */

    /**
     * All lead activity by staff
     * @param  mixed $id lead id
     * @return array
     */
	 
	 public function get_country()
	{
	  $query = $this->db->query("SELECT * from 	tbl_country order by added_at desc");
  	  return $query->result();
	}
	public function get_state()
	{
		$query = $this->db->query("SELECT * from tbl_state order by state asc");
  	  return $query->result();
	}
	public function get_state_by_id()
	{
		$query = $this->db->query("SELECT * from tbl_state order by state asc");
  	  return $query->result();
	}
	
	
	public function get_lead_type()
	{
	  $query = $this->db->query("SELECT * from tbl_lead_type");
  	  return $query->result();
	}
	
	 public function getcountryBystate($country_id) {
        $this->db->select()->from('tbl_state');
		$this->db->where('country_id', $country_id);
        $query = $this->db->get();
		return $query->result_array();
       
    } 
	public function getdynsm_id($nsm_id) {
		
        $query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_manager IN ("'. $nsm_id .'") AND role=6 ORDER BY staffid ASC');
		return $result = $query->result_array();
       
    } 
	
	public function getrsm_id($dynsm_id='') {
		$dynsm_id = rtrim($dynsm_id,',');

        $query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_manager IN ('.$dynsm_id.') AND role=2');
		return $result = $query->result_array(); 
		
		/* $explode = explode(',',$dynsm_id);
		$ids  = array_unique($explode);
		
		foreach($ids as $single){
			
			$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_to LIKE ("%,'.$single.'%") AND role=2');
			return $query->result_array();
		} 
		 */
       
    } 
	
	public function getzsm_id($rsm_id='') {
		/* $explode = explode(',',$rsm_id);
		$ids  = array_unique($explode);
		
		foreach($ids as $single){
			
			$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_to LIKE ("%,'.$single.'%") AND role=5');
			return $query->result_array();
		} */
		
		$rsm_id = rtrim($rsm_id,',');
		 
		$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_manager IN ('.$rsm_id.') AND role=5');
		return $result = $query->result_array(); 
		
       
    } 
	
	public function getasm_id($zsm_id='') {
		
	   /*  $explode = explode(',',$rsm_id);
		$ids  = array_unique($explode);
		
		foreach($ids as $single){
			
			$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_to LIKE ("%,'.$single.'%") AND role=3');
			return $query->result_array();
		}  */
		
		$zsm_id = rtrim($zsm_id,',');
      $query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_manager IN ('.$zsm_id.') AND role=3');
		return $result = $query->result_array();
       
    } 
	
	public function getse_id($asm_id='') {
		/* $explode = explode(',',$asm_id);
		$ids  = array_unique($explode);
		
		foreach($ids as $single){
			
			$query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_to LIKE ("%,'.$single.'%") AND role=1');
			return $query->result_array();
		}  */
		$asm_id = rtrim($asm_id,',');
       $query = $this->db->query('SELECT staffid,emp_code,firstname,lastname,reporting_manager,reporting_to FROM tblstaff where reporting_manager IN ('.$asm_id.') AND role=1');
		return $result = $query->result_array(); 
       
    } 
	
	
	
	public function getinvoiceBysubcategory($group_id) {
        $this->db->select()->from('tblitems_sub_groups');
		$this->db->where('group_id', $group_id);
        $query = $this->db->get();
		return $query->result_array();
       
    }
	  public function getstateBycity($state_id) {
        $this->db->select()->from('tbl_city');
		$this->db->where('state_id', $state_id);
        $query = $this->db->get();
		return $query->result_array();
       
    }
	 
	 
	public function getEmailID($field,$table,$column,$keyword) {
       $this->db->select($field);
		$this->db->from($table);
		$this->db->like($column, $keyword);
		return $this->db->get()->result_array();
       
    }
	 
	 public function getEmailIDReportingTo($field,$table,$column,$keyword) {
       $this->db->select($field);
	   $this->db->from($table);	   
	   $this->db->where('active', '1');
	   $this->db->where_in($column, $keyword);
	   return $this->db->get()->result_array();
    }
	
	 
    public function get_lead_activity_log($id)
    {
        $sorting = do_action('lead_activity_log_default_sort', 'desc');

        $this->db->where('leadid', $id);
        $this->db->order_by('date', $sorting);

        return $this->db->get('tblleadactivitylog')->result_array();
    }

    public function staff_can_access_lead($id, $staff_id = '')
    {
        $staff_id = $staff_id == '' ? get_staff_user_id() : $staff_id;

        if (has_permission('leads', $staff_id, 'view')) {
            return true;
        }

        if (total_rows('tblleads', 'id="'.$id.'" AND (assigned='.$staff_id.' OR is_public=1 OR addedfrom='.$staff_id.')') > 0) {
            return true;
        }

        return false;
    }
public function getcontanct_details($staff_id) {
        $this->db->select()->from('tblstaff');
		$this->db->where('staffid', $staff_id);
        $query = $this->db->get();
		return $query->result_array();
       
    }
    /**
     * Add lead activity from staff
     * @param  mixed  $id          lead id
     * @param  string  $description activity description
     */
    public function log_lead_activity($id, $description, $integration = false, $additional_data = '')
    {
        $log = array(
            'date' => date('Y-m-d H:i:s'),
            'description' => '<a href="#lead_reminders"  aria-controls="lead_reminders" role="tab" data-toggle="tab" class="lead_reminders">'. $description .'</a>',
            'leadid' => $id,
            'staffid' => get_staff_user_id(),
            'additional_data' => $additional_data,
            'full_name' => get_staff_full_name(get_staff_user_id()),
        );
        if ($integration == true) {
            $log['staffid']   = 0;
            $log['full_name'] = '[CRON]';
        }

        $this->db->insert('tblleadactivitylog', $log);

        return $this->db->insert_id();
    }

    /**
     * Get email integration config
     * @return object
     */
    public function get_email_integration()
    {
        $this->db->where('id', 1);

        return $this->db->get('tblleadsintegration')->row();
    }

    /**
     * Get lead imported email activity
     * @param  mixed $id leadid
     * @return array
     */
    public function get_mail_activity($id)
    {
        $this->db->where('leadid', $id);
        $this->db->order_by('dateadded', 'asc');

        return $this->db->get('tblleadsemailintegrationemails')->result_array();
    }

    /**
     * Update email integration config
     * @param  mixed $data All $_POST data
     * @return boolean
     */
    public function update_email_integration($data)
    {
        $this->db->where('id', 1);
        $original_settings = $this->db->get('tblleadsintegration')->row();

        $data['create_task_if_customer'] = isset($data['create_task_if_customer']) ? 1 : 0;
        $data['active'] = isset($data['active']) ? 1 : 0;
        $data['delete_after_import'] = isset($data['delete_after_import']) ? 1 : 0;
        $data['notify_lead_imported'] = isset($data['notify_lead_imported']) ? 1 : 0;
        $data['only_loop_on_unseen_emails'] = isset($data['only_loop_on_unseen_emails']) ? 1 : 0;
        $data['notify_lead_contact_more_times'] = isset($data['notify_lead_contact_more_times']) ? 1 : 0;
        $data['mark_public'] = isset($data['mark_public']) ? 1 : 0;
        $data['responsible'] = !isset($data['responsible']) ? 0 : $data['responsible'];

        if ($data['notify_lead_contact_more_times'] != 0 || $data['notify_lead_imported'] != 0) {
            if (isset($data['notify_type']) && $data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize(array());
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize(array());
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize(array());
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        // Check if not empty $data['password']
        // Get original
        // Decrypt original
        // Compare with $data['password']
        // If equal unset
        // If not encrypt and save
        if (!empty($data['password'])) {
            $or_decrypted = $this->encryption->decrypt($original_settings->password);
            if ($or_decrypted == $data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = $this->encryption->encrypt($data['password']);
            }
        }

        $this->db->where('id', 1);
        $this->db->update('tblleadsintegration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function change_status_color($data)
    {
        $this->db->where('id', $data['status_id']);
        $this->db->update('tblleadsstatus', array(
            'color' => $data['color'],
        ));
    }

    public function update_status_order($data)
    {
        foreach ($data['order'] as $status) {
            $this->db->where('id', $status[0]);
            $this->db->update('tblleadsstatus', array(
                'statusorder' => $status[1],
            ));
        }
    }

    public function get_form($where)
    {
        $this->db->where($where);

        return $this->db->get('tblwebtolead')->row();
    }

    public function add_form($data)
    {
        $data                       = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $data['form_key']           = app_generate_hash();

        if (isset($data['create_task_on_duplicate'])) {
            $data['create_task_on_duplicate'] = 1;
        } else {
            $data['create_task_on_duplicate'] = 0;
        }

        if (isset($data['mark_public'])) {
            $data['mark_public'] = 1;
        } else {
            $data['mark_public'] = 0;
        }

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $data['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert('tblwebtolead', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Web to Lead Form Added [' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_form($id, $data)
    {
        $data                       = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);

        if (isset($data['create_task_on_duplicate'])) {
            $data['create_task_on_duplicate'] = 1;
        } else {
            $data['create_task_on_duplicate'] = 0;
        }

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        if (isset($data['mark_public'])) {
            $data['mark_public'] = 1;
        } else {
            $data['mark_public'] = 0;
        }

        $this->db->where('id', $id);
        $this->db->update('tblwebtolead', $data);

        return ($this->db->affected_rows() > 0 ? true : false);
    }

    public function delete_form($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblwebtolead');

        $this->db->where('from_form_id', $id);
        $this->db->update('tblleads', array(
            'from_form_id' => 0,
        ));

        if ($this->db->affected_rows() > 0) {
            logActivity('Lead Form Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    private function _do_lead_web_to_form_responsibles($data)
    {
        if (isset($data['notify_lead_imported'])) {
            $data['notify_lead_imported'] = 1;
        } else {
            $data['notify_lead_imported'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_lead_imported'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize(array());
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize(array());
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize(array());
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }
	public function all_employees()
	{
	  $query = $this->db->query("SELECT * from tblemployees");
  	  return $query->result();
	}
	public function get_lead_source()
	{
	  $query = $this->db->query("SELECT * from lead_source");
  	  return $query->result();
	}
	
	
	public function add_lead(){
$data = array(
		'lead_title' => $this->input->post('lead_name'),
		
		'lead_description' => $this->input->post('lead_description'),
		'lead_type' => $this->input->post('lead_type'),
		'lead_owner' => $this->input->post('lead_owner'),
		'lead_status' => $this->input->post('lead_status'),
		'date' => date('Y-m-d H:i:s'),
		'lead_source' => $this->input->post('lead_source'),
		'opportunity' => $this->input->post('opportunity_amount'),);
$this->db->insert('lead_detail', $data);
$insert_id = $this->db->insert_id();

$adddata=array('first_name' => $this->input->post('first_name'),
		'last_name' => $this->input->post('last_name'),
		'email' => $this->input->post('email'),
		'phone' => $this->input->post('phone'),
		'fax' => $this->input->post('fax'),
		'lead_id'=>$insert_id,
		'date' => date('Y-m-d H:i:s'),
		'mobile' => $this->input->post('mobile'),);


$addaddress=array(	'address_1' => $this->input->post('address_1'),
		'address_2' => $this->input->post('address_2'),
		'zipcode' => $this->input->post('zipcode'),
		'country' => $this->input->post('country_id'),
		'state' => $this->input->post('state_id'),
		'lead_id'=>$insert_id,
		'date' => date('Y-m-d H:i:s'),
		'city' => $this->input->post('city_id'),
		
		
		);


$this->db->insert('tbl_lead_contact', $adddata);

$this->db->insert('tbl_lead_address', $addaddress);

		
	}
	
		public function get_leade_detail()
	{
	  $query = $this->db->query("SELECT * from lead_detail");
  	  return $query->result();
	}
	
	
		public function get_status_lead_byfg()
	{
	  $query = $this->db->query("SELECT * from tbl_status");
  	  return $query->result();
	}
	
	public function result_getall(){

    $this->db->select('lead_detail.id as leadid,lead_detail.lead_title as lead_title,lead_detail.date as lead_date,lead_detail.lead_status as lead_status,lead_detail.lead_type as lead_type,lead_detail.lead_owner as lead_owner,tbl_lead_contact.first_name,tbl_lead_contact.last_name,tbl_lead_contact.phone,	tbl_lead_contact.mobile,tbl_lead_contact.email,tbl_lead_address.address_1,tbl_lead_address.address_2,tbl_lead_address.zipcode,tbl_lead_address.country,tbl_lead_address.state,tbl_lead_address.city');
    $this->db->from('lead_detail');
    $this->db->join('tbl_lead_contact', ' tbl_lead_contact.lead_id=lead_detail.id'); 
    $this->db->join('tbl_lead_address', ' tbl_lead_address.lead_id=lead_detail.id'); 
	$this->db->where('tbl_lead_contact.is_primary','1');
	$this->db->where('tbl_lead_address.is_primary','1');
    $query = $this->db->get();
	
    return $query->result();

    }
		public function tbl_lead_address(){
	$this->db->select('tbl_lead_address.*, b.country,c.state');
	$this->db->from('tbl_lead_address');
	
	$this->db->join('tbl_country b', 'b.id=tbl_lead_address.country');
$this->db->join('tbl_state c', 'c.id=tbl_lead_address.state');


$tbl_list = $this->db->get();
return 	$tbl_list->result_array();
	
}




public function get_status_byid($id)
	{
	   $this->db->select('status');
		$this->db->from('tbl_status');
		$this->db->where('id',$id);
		$reault_array = $this->db->get()->result_array();
		return $reault_array[0]['status'];
	}
	public function get_lead_type_byid($id)
	{
	   $this->db->select('type');
		$this->db->from('tbl_lead_type');
		$this->db->where('id',$id);
		$reault_array = $this->db->get()->result_array();
		return $reault_array[0]['type'];
	}
	public function read_user_info($id) {
	
		$condition = "user_id =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('tblemployees');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
		
	}
		public function get_lead_source_byid($id)
	{
	   $this->db->select('name');
		$this->db->from('lead_source');
		$this->db->where('id',$id);
		$reault_array = $this->db->get()->result_array();
		return $reault_array[0]['name'];
	}
	
	  public function update_leads($id,$data) {
        
            $this->db->where('id', $id);
            $this->db->update('tblleads', $data);
       
    }
	
	  public function update_customer_value($id,$data) {
        
            $this->db->where('id', $id);
            $this->db->update('customer_type_value', $data);
       
    }
	
	public function update_leads_lead_requirment($id,$data) {
        
            $this->db->where('id', $id);
            $this->db->update('tbllead_requirment', $data);
       
    }
	
	
	
	public function get_item($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems')->row()->description;
    }
	
	public function get_item_state($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblleads')->row()->state;
    }
		public function get_subcategory($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems_sub_groups')->row()->name;
    }
	
	
	public function get_group($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems_groups')->row()->name;
    }
	public function get_status_name($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblleadsstatus')->row()->name;
    }
	public function get_status_loss($id='')
    {
        $this->db->where('id', $id);

        $name = $this->db->get('status_loss')->row()->name;
       
		
		return $name;
    }
	public function get_status_won_loss($id='')
    {
        $this->db->where('id', $id);

        $name = $this->db->get('status_loss')->row()->name;
       
		
		return $name;
    }
	
	public function get_emp_name($id='')
    {
        $this->db->where('staffid', $id);
        $firstname = $this->db->get('tblstaff')->row()->firstname;
		$this->db->where('staffid', $id);
        $lastname = $this->db->get('tblstaff')->row()->lastname;
		
		return $firstname.' '.$lastname;
    }
	public function get_emp_email($id='')
    {
        $this->db->where('staffid', $id);
        $email = $this->db->get('tblstaff')->row()->email;
		return $email;
    }
	
	public function get_tblclients($id='')
    {
        $this->db->where('userid', $id);

        return $this->db->get('tblclients')->row()->company;
		
    }
	
	public function get_lead_description($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblleads')->row()->company;
		
    }
	public function get_lead_customer($id = '')
    {
        $this->db->where('id', $id);
        
        return $this->db->get('tblleads')->row()->customer_name;
        
    }
	public function get_reporting_manager($id='')
    {
        $this->db->where('staffid', $id);

        return $this->db->get('tblstaff')->row()->reporting_manager;
		
    }
	public function get_reporting_to($id='')
    {
        $this->db->where('staffid', $id);

        return $this->db->get('tblstaff')->row()->reporting_to;
		
    }
	public function get_city_name($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tbl_city')->row()->city;
		
    }
	public function get_state_name($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tbl_state')->row()->state;
		
    }
	
	public function get_dateadded($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblleads')->row()->dateadded;
		
    }
	public function get_financialdate($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblleads')->row()->accepacted_date;
		
    }
	public function lead_requirment_category($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems_groups')->row()->name;
		
    }	
	public function lead_requirment_wattage($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems_sub_groups')->row()->name;
		
    }
	public function lead_requirment_items($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems')->row()->description;
		
    }
	
	public function get_opportunity_sum($staffid='')
    {
        $query = $this->db->query('SELECT SUM(opportunity) as total_opportunity FROM tblleads where ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.$staffid.',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.$staffid.',%" )');
		
		return $query->total_opportunity;

    }
	
	public function no_of_leads_bymonth($month,$staffid)
	{
		$query = $this->db->query("SELECT id FROM tblleads where assigned='".$staffid."' AND dateadded LIKE '".$month."%'");
		return $query->num_rows();

	}
	public function no_of_leads_bydate($date,$staffid)
	{
		$query = $this->db->query("SELECT id FROM tblleads where assigned='".$staffid."' AND dateadded LIKE '".$date."%'");
		return $query->num_rows();
	}
    
    
    public function value_of_leads_bymonth($month,$staffid)
	{
		$whereArray = array(
                "assigned" => $staffid,
                "dateadded LIKE" => $month.'%',
        );
		
		$this->db->select_sum('opportunity');
		$result = $this->db->get_where('tblleads',$whereArray)->row();  
		if($result->opportunity=='')
			$opportunity = 0;
		else
			$opportunity = $result->opportunity;
		return $opportunity;
	}
	public function value_of_leads_bydate($date,$staffid)
	{
		$whereArray = array(
                "assigned" => $staffid,
                "dateadded LIKE" => $date.'%',
        );
		
		$this->db->select_sum('opportunity');
		$result = $this->db->get_where('tblleads',$whereArray)->row();  
		if($result->opportunity=='')
			$opportunity = 0;
		else
			$opportunity = $result->opportunity;
		return $opportunity;

	}
	
	public function no_of_leads_by_stage_month($month,$status)
	{
		$query = $this->db->query("SELECT id FROM tblleads where status='".$status."' AND dateassigned LIKE '".$month."%'");
		return $query->num_rows();

	}
	public function no_of_leads_by_stage_date($date,$status)
	{
		$query = $this->db->query("SELECT id FROM tblleads where status='".$status."' AND dateassigned LIKE '".$date."%'");
		return $query->num_rows();
	}
    
    
    public function value_of_leads_by_stage_month($month,$status)
	{
		$whereArray = array(
                "status" => $status,
                "dateassigned LIKE" => $month.'%',
        );
		
		$this->db->select_sum('opportunity');
		$result = $this->db->get_where('tblleads',$whereArray)->row();  
		if($result->opportunity=='')
			$opportunity = 0;
		else
			$opportunity = $result->opportunity;
		return $opportunity;
	}
	public function value_of_leads_by_stage_date($date,$status)
	{
		$whereArray = array(
                "status" => $status,
                "dateassigned LIKE" => $date.'%',
        );
		
		$this->db->select_sum('opportunity');
		$result = $this->db->get_where('tblleads',$whereArray)->row();  
		if($result->opportunity=='')
			$opportunity = 0;
		else
			$opportunity = $result->opportunity;
		return $opportunity;

	}
    
     
   
    public function getleadchangerequest()
    {
		if (get_staff_role() == 1) {
			$this->db->where('addedby', get_staff_user_id());
		}
        return $this->db->get('tblleadchangerequest')->result_array();
    }
    public function getleadchangerequest_pending()
    {
		
		if (get_staff_role() == 1) {
			$where = "addedby='".get_staff_user_id()."' AND (status='Pending' OR status='Approved')";
			$this->db->where($where);
		}else if (get_staff_role() == 8) {
			$where = "status='Pending'";
			$this->db->where($where);
		}else{
			$where = "status='Pending' OR status='Approved'";
			$this->db->where($where);
		}
		$this->db->order_by("id", "DESC");
				
        return $this->db->get('tblleadchangerequest')->result_array();
    }
	public function getleadchangerequest_completed()
    {		
		if (get_staff_role() == 1) {
			$arraycond = array('addedby' => get_staff_user_id(),'status' => 'Updated');
			$this->db->where($arraycond);
		}else if (get_staff_role() == 8) {
			$where = "status='Updated' OR status='Approved'";
			$this->db->where($where);
		}else{
			$arraycond = array('status' => 'Updated');
			$this->db->where($arraycond);
		}
		$this->db->order_by("id", "DESC");
				
        return $this->db->get('tblleadchangerequest')->result_array();
    }
	public function getleadchangerequest_rejected()
    {		
		if (get_staff_role() == 1) {
			$arraycond = array('addedby' => get_staff_user_id(),'status' => 'Rejected');
			$this->db->where($arraycond);
		}else{
			$arraycond = array('status' => 'Rejected');
			$this->db->where($arraycond);
		}
		$this->db->order_by("id", "DESC");
				
        return $this->db->get('tblleadchangerequest')->result_array();
    }
	
    public function list_leads_data_staff($id = null)
    {
		$this->db->order_by("id", "DESC");
		
        $this->db->select()->from('tblleads');
		$this->db->where('assigned', get_staff_user_id());
        $query = $this->db->get();
        
        return $query->result_array();
        
    }

	//---------------------------- Stage Report ---------------------//
	
	/* public function no_of_leads_by_stage_month_staff($month,$status,$staff_id='',$zone_name='')
	{
		$total_own =0;
		
		if ($staff_id != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND  (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND  (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
				}
                
            } else if ($role == 7 || $role == 4) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND  tblleads.state IN(' . $staff_state_id . ') AND region="'.$zone_name.'"');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND  tblleads.state IN(' . $staff_state_id . ')');
                }
            } else if ($role != 0 || $role != 4 || $role != 7) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . $staff_id . '%" AND region="'.$zone_name.'"');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . $staff_id . '%"');
                }
            }
            
            if ($role != 1) {
                
				if ($staff_id != '') {
						if($zone_name != '--Select--'){
							$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"');
						}else{
							$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
						}
                } else {
					if($zone_name != '--Select--'){
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND region="'.$zone_name.'"');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
					}
                }
                $total_own = $query1->num_rows();
            }
        }
		else if($staff_id == '' && $zone_name != '--Select--'){
			
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND region="'.$zone_name.'"');
		} else {
			if (get_staff_role() == 1) {				
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
			} else if (get_staff_role() == 7 || get_staff_role() == 4) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND tblleads.state IN(' . get_staff_state_id() . ')');
			} else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . get_staff_user_id() . '%"  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
			}
			
			if (get_staff_role() != 1) {
				if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
                }
				
				
				$total_own = $query1->num_rows();
			}
		}
		 $total_rec =  $query->num_rows();
		 
		 return $total_own + $total_rec;

	}
	public function total_no_of_leads_by_stage_month_staff($month,$staff_id='',$zone_name='')
	{
		if ($staff_id != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND  (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND  (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                }
            } else if ($role == 7 || $role == 4) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND  tblleads.state IN(' . $staff_state_id . ') AND region="'.$zone_name.'" ');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND  tblleads.state IN(' . $staff_state_id . ')');
				}
                
            } else if ($role != 0 || $role != 4 || $role != 7) {
				if($zone_name != '--Select--'){
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . $staff_id . '%" AND region="'.$zone_name.'" ');
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . $staff_id . '%"');
                }
            }
            if ($role != 1) {
                
                if ($staff_id != '') {
					if($zone_name != '--Select--'){
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)  AND region="'.$zone_name.'"');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                    }
                } else {
					if($zone_name != '--Select--'){
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND region="'.$zone_name.'" ');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
                    }
                }
                $total_own = $query1->num_rows();
            }
			$total_rec =  $query->num_rows();
        }else if($staff_id == '' && $zone_name != '--Select--'){
			
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND region="'.$zone_name.'"');
		}else {
			if (get_staff_role() == 1) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
			} else if (get_staff_role() == 7 || get_staff_role() == 4) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.state IN(' . get_staff_state_id() . ')');
			} else if (get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.reportingto LIKE "%' . get_staff_user_id() . '%"  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
			}
			
			if (get_staff_role() != 1) {
				if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
                }
				
				
				$total_own = $query1->num_rows();
			}
		}
		 $total_rec =  $query->num_rows();
		 
		 return $total_own + $total_rec;
	}
	
	public function value_of_leads_by_stage_month_staff($month,$status,$staff_id='',$zone_name='')
	{
		$sql = "";
		$sql .= "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%'";
		if($staff_id != '') 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				if ($zone_name != '--Select--') {
                    $sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"';
                } else {
					$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
				}
			}
			else if($role == 7 || $role == 4) 
			{
				if ($zone_name != '--Select--') {
                    $sql .=  ' AND tblleads.state IN('. $staff_state_id .') AND region="'.$zone_name.'"';
                } else {
					$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				}
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				if ($zone_name != '--Select--') {
                    $sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%" AND region="'.$zone_name.'"';
                } else {
					$sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%"';
				}
			}
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= 'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
				
				if($staff_id != '') 
				{
					if ($zone_name != '--Select--') {
						$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"';
					} else {
						$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
					}
				}
				else{
					if ($zone_name != '--Select--') {
						$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND region="'.$zone_name.'"';
					} else {
						$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($staff_id == '' && $zone_name != '--Select--'){
			$sql .=  ' AND region="'.$zone_name.'"';
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else{
			if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) ';
			}
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= 'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
					
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
					
				}
							
				$total_own = $this->db->query($sql1)->row()->total;
			}
			$total_staff = $this->db->query($sql)->row()->total;
		}	
		return $total_staff + $total_own;
	
	}
	public function total_value_of_leads_by_stage_month_staff($month,$staff_id='',$zone_name='')
	{
		$sql = "";
		$sql .= "SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE '".$month."%'";
		if($staff_id != '') 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				if ($zone_name != '--Select--') {
					$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)  AND region="'.$zone_name.'"';
                } else {
					$sql .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
				}
				
			}
			else if($role == 7 || $role == 4) 
			{
				if ($zone_name != '--Select--') {
					$sql .=  ' AND tblleads.state IN('. $staff_state_id .') AND region="'.$zone_name.'"';
                } else {
					$sql .= ' AND tblleads.state IN('. $staff_state_id .')';
				}
			}
			else if($role != 0 ||  $role != 4 || $role != 7) 
			{
				if ($zone_name != '--Select--') {
					$sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%" AND region="'.$zone_name.'"';
                } else {
					$sql .=  ' AND tblleads.reportingto LIKE "%'.$staff_id.'%"';
				}
			}
			
			if ($role != 1) {
				$sql1 ='';
				$sql1 .= 'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"';
				
				if($staff_id != '') 
				{
					if ($zone_name != '--Select--') {
						$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1) AND region="'.$zone_name.'"';
					} else {
						$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
					}
				}
				else{
					if ($zone_name != '--Select--') {
						$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)  AND region="'.$zone_name.'"';
					} else {
						$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
					}
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
			$total_staff = $this->db->query($sql)->row()->total;
		}else if($staff_id == '' && $zone_name != '--Select--'){
			$sql .=  ' AND region="'.$zone_name.'"';
			$total_staff = $this->db->query($sql)->row()->total;
		}else{
			if (get_staff_role() == 1) {
				$sql .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= ' AND tblleads.state IN('. get_staff_state_id() .')';
			}
			else if(get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql .=  ' AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) ';
			}
			
			if (get_staff_role() != 1) {
				$sql1 ='';
				$sql1 .= 'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"';
				
				if($staff_id != '') 
				{
					$sql1 .=  ' AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ' OR is_public = 1)';
					
				}
				else{
					$sql1 .=  ' AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)';
					
				}
							
				$total_own = $this->db->query($sql1)->row()->total;
			}
			$total_staff = $this->db->query($sql)->row()->total;
		}	
		return $total_staff + $total_own;
	
	}
	 */
	
	public function no_of_leads_by_stage_month_staff($month,$status,$staff_id='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if ($staff_id != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
				
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
				$total_rec =  $query->num_rows();
			}
			else if ($role != 1) {
				if($zone_name != '--Select--'){
					$where = "region LIKE '%".$zone_name."' AND ( CONCAT(',', reporting_to, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".$staff_id.",%' ) AND is_not_staff = 0";
					$this->db->select('staffid');
					$this->db->where($where);		
					$userarray = $this->db->get('tblstaff')->result_array();
					foreach($userarray as $row)  
					{
						$ids[] = $row['staffid']; 
					} 
					$assigned = implode(", ", $ids);
					
					if($assigned !=''){	
						$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND assigned IN('.$assigned.')');
						$total_rec =  $query->num_rows();
					}	
				
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.$staff_id.',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.$staff_id.',%")');
					$total_rec =  $query->num_rows();
                }
            }            
            if ($role != 1) {
                
				if ($staff_id != '') {
					if($zone_name != '--Select--'){
						$this->db->where('id', $zone_name);
						$zone_name = $this->db->get('tblregion')->row()->region;
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ') AND region="'.$zone_name.'"');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
					}
                } else {
					if($zone_name != '--Select--'){
						$this->db->where('id', $zone_name);
						$zone_name = $this->db->get('tblregion')->row()->region;
						
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND region="'.$zone_name.'"');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
					}
                }
                $total_own = $query1->num_rows();
            }
        }
		else if($staff_id == '' && $zone_name != '--Select--'){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
		} 
		else {
			if (get_staff_role() > 8 || is_admin()) {				
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%"');
				$total_rec =  $query->num_rows();
			}else if (get_staff_role() == 1) {				
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
				$total_rec =  $query->num_rows();
			} 
			else if (get_staff_role() < 9 || get_staff_role() != 0 ||  get_staff_role() != 4 || get_staff_role() != 7) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.get_staff_user_id().',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.get_staff_user_id().',%") OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
				$total_rec =  $query->num_rows();
			}
			else if (get_staff_role() == 7 || get_staff_role() == 4) {
				$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" tblleads.state IN(' . get_staff_state_id() . ')');
				$total_rec =  $query->num_rows();
			} 
			if (get_staff_role() != 1) {
				if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                }
				
				
				$total_own = $query1->num_rows();
			}
		}
		
		
		 return $total_own + $total_rec;

	}
	public function total_no_of_leads_by_stage_month_staff($month,$staff_id='',$zone_name='')
	{
		$total_rec = 0;
		$total_own = 0;
		if ($staff_id != '') {
            $staff_state_id = $this->get_staff_state_byid($staff_id);
            $role           = $this->get_staff_role_id($staff_id);
            if ($role == 1) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND  (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
				$total_rec =  $query->num_rows();
            }
			else if ($role != 1) {
				if($zone_name != '--Select--'){
					$where = "region LIKE '%".$zone_name."' AND ( CONCAT(',', reporting_to, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".$staff_id.",%' ) AND is_not_staff = 0";
					$this->db->select('staffid');
					$this->db->where($where);		
					$userarray = $this->db->get('tblstaff')->result_array();
					foreach($userarray as $row)  
					{
						$ids[] = $row['staffid']; 
					} 
					$assigned = implode(", ", $ids);
					if($assigned !=''){						
						$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.assigned IN(' . $assigned . ')');
						$total_rec =  $query->num_rows();
					}
					
				}else{
					$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.$staff_id.',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.$staff_id.',%")');
					$total_rec =  $query->num_rows();
                }
            }
            if ($role != 1) {
                
                if ($staff_id != '') {
					if($zone_name != '--Select--'){
						$this->db->where('id', $zone_name);
						$zone_name = $this->db->get('tblregion')->row()->region;
						
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')  AND region="'.$zone_name.'"');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                    }
                } else {
					if($zone_name != '--Select--'){
						$this->db->where('id', $zone_name);
						$zone_name = $this->db->get('tblregion')->row()->region;
						
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')  AND region="'.$zone_name.'" ');
					}else{
						$query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                    }
                }
                $total_own = $query1->num_rows();
            }
			
        }
		else if($staff_id == '' && $zone_name != '--Select--'){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND assigned IN('.$assigned.')');
			$total_rec =  $query->num_rows();
		}
		else {
			
			if (get_staff_role() > 8 || is_admin()) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" ');
			}
			else if (get_staff_role() == 1) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
			} else if (get_staff_role() == 7 || get_staff_role() == 4) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND tblleads.state IN(' . get_staff_state_id() . ')');
			} else if (get_staff_role() < 9 || get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) {
				$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%"  OR tblleads.assigned ='. get_staff_user_id() .')  OR tblleads.addedfrom IN(' . get_staff_user_id() . ')');
			}
			
			if (get_staff_role() != 1) {
				if ($staff_id != '') {
                    $query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')');
                } else {
                    $query1 = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')');
                }
				
				
				$total_own = $query1->num_rows();
			}
			$total_rec =  $query->num_rows();
		}
		 
		
		 return $total_own + $total_rec;
	}
	
	public function value_of_leads_by_stage_month_staff($month,$status,$staff_id='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($staff_id != '') 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				$sql = "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND tblleads.assigned ='". $staff_id . "'";
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if($role != 1) 
			{
				if ($zone_name != '--Select--') {
					if(is_array ($zone_name)){
						$region_ids = implode(',',$zone_name);
						$where = "region IN(".$region_ids.") AND ( CONCAT(',', reporting_to, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".$staff_id.",%' ) AND is_not_staff = 0";
					}else{
						$where = "region IN(".$zone_name.") AND ( CONCAT(',', reporting_to, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".$staff_id.",%' ) AND is_not_staff = 0";
					}
					$this->db->select('staffid');
					$this->db->where($where);		
					$userarray = $this->db->get('tblstaff')->result_array();
					foreach($userarray as $row)  
					{
						$ids[] = $row['staffid']; 
					} 
					$assigned = implode(", ", $ids);
					if($assigned !=''){
						$sql =  "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND tblleads.assigned IN(" . $assigned . ")";
						$total_staff = $this->db->query($sql)->row()->total;
					}
                } else {
					
					$sql =  "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND ( CONCAT(',', reportingto, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reportingto, ',')  LIKE '%,".$staff_id.",%' )";
					$total_staff = $this->db->query($sql)->row()->total;
				}
				
				
			}
			if ($role != 1) {
				
				if($staff_id != '') 
				{
					$sql1 =  'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id .')';
				}
				else{
					$sql1 =  'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
			
		}
		else if($staff_id == '' && $zone_name != '--Select--'){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT SUM(opportunity) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else{
			if (get_staff_role() > 8 || is_admin()) {
				$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if (get_staff_role() == 1) {
				$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql .= 'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%" AND tblleads.state IN('. get_staff_state_id() .')';
				$total_staff = $this->db->query($sql)->row()->total;
			}else if(get_staff_role() < 9 || get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) ';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			
			if (get_staff_role() != 1) {
				$sql1 = 'SELECT SUM(opportunity) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
				
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
		 
		return $total_staff + $total_own;
	
	}
	
	public function total_value_of_leads_by_stage_month_staff($month,$staff_id='',$zone_name='')
	{
		$total_staff = 0; 
		$total_own=0;
		if($staff_id != '') 
		{
			$staff_state_id = $this->get_staff_state_byid($staff_id);
			$role = $this->get_staff_role_id($staff_id);
			if ($role == 1) {
				$sql = 'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND tblleads.assigned ='.$staff_id.'';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if($role != 1) 
			{
				if ($zone_name != '--Select--') {
					$where = "region LIKE '%".$zone_name."' AND ( CONCAT(',', reporting_to, ',')  LIKE '%, ".$staff_id.",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".$staff_id.",%' )";
					$this->db->select('staffid');
					$this->db->where($where);		
					$userarray = $this->db->get('tblstaff')->result_array();
					foreach($userarray as $row)  
					{
						$ids[] = $row['staffid']; 
					} 
					$assigned = implode(", ", $ids);
					if($assigned !=''){
						$sql = 'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND tblleads.assigned IN(' . $assigned . ')';
						$total_staff = $this->db->query($sql)->row()->total;
					}
                } else {
					 $sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND ( CONCAT(",", reportingto, ",")  LIKE "%, '.$staff_id.',%" OR CONCAT(",", reportingto, ",")  LIKE "%,'.$staff_id.',%")';
					$total_staff = $this->db->query($sql)->row()->total;
				}
				
			}
			if ($role != 1) {
				if($staff_id != '') 
				{
					$sql1 =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
				}
				else{
					$sql1 =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
				}
				$total_own = $this->db->query($sql1)->row()->total;
			}
			
		}
		else if($staff_id == '' && $zone_name != '--Select--'){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND assigned IN('.$assigned.')';
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else{
			
			if (get_staff_role() > 8 || is_admin()) {
				$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if (get_staff_role() == 1) {
				$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND tblleads.assigned ='. get_staff_user_id() . '';
				$totalstaff = $this->db->query($sql)->row()->total;
				
			}
			else if(get_staff_role() == 7 || get_staff_role() == 4) 
			{
				$sql = 'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"  AND tblleads.state IN('. get_staff_state_id() .')';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			else if(get_staff_role() < 9 || get_staff_role() != 0 || get_staff_role() != 4 || get_staff_role() != 7) 
			{
				
				$sql =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"  AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" ) ';
				$total_staff = $this->db->query($sql)->row()->total;
			}
			
			if (get_staff_role() != 1 || get_staff_role() != 0 || get_staff_role() < 9) {
				
				if($staff_id != '') 
				{
					$sql1 = 'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . $staff_id . ' OR tblleads.addedfrom = ' . $staff_id . ')';
					$total_own = $this->db->query($sql1)->row()->total;
				}
				else{
					$sql1 =  'SELECT SUM(opportunity) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%" AND (tblleads.assigned =' . get_staff_user_id() . ' OR tblleads.addedfrom = ' . get_staff_user_id() . ')';
					$total_own = $this->db->query($sql1)->row()->total;
				}
				$total_staff = $this->db->query($sql)->row()->total;			
				
			}
			
		}	
		if (get_staff_role() == 1) {
			return $total_staff;
		}else{
			return $total_staff + $total_own;
			
		}
	}
	
	//------------------------------ MTD / YTD / ITD Report -------------------------//
	
	public function mtd_no_of_leads_by_stage_month_staff($month,$status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
			
		}else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%"');
			$total_rec =  $query->num_rows();
		
		}
		else {
				
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%"');
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function mtd_value_of_leads_by_stage_month_staff($month,$status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE '".$month."%' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	public function itd_no_of_leads_by_stage_month_staff($status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
			
		}else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"');
			$total_rec =  $query->num_rows();
		
		}
		else {
			if(get_staff_role()==2 || get_staff_role()==3 || get_staff_role()==5 || get_staff_role()==6 || get_staff_role()==8){
				$query = $this->db->query('SELECT id FROM tblleads where ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )');
				
			}else{
				$query = $this->db->query('SELECT id FROM tblleads');
				
			}	
			
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function itd_value_of_leads_by_stage_month_staff($status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status="'.$status.'"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			if(get_staff_role()==2 || get_staff_role()==3 || get_staff_role()==5 || get_staff_role()==6 || get_staff_role()==8){
				$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads where ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )';
				
			}else{
				$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads';
			}
			
			
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	public function ytd_no_of_leads_by_stage_month_staff($fromyearmonth, $toyearmonth, $status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND assigned IN ('.$assigned.') AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where assigned IN ('.$assigned.') AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
			
		}
		else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
		
		}
		else {
			if(get_staff_role()==2 || get_staff_role()==3 || get_staff_role()==5 || get_staff_role()==6 || get_staff_role()==8){	
			$query = $this->db->query('SELECT id FROM tblleads where (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" ) AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )');
			}else{
			$query = $this->db->query('SELECT id FROM tblleads where (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
				
			}
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function ytd_value_of_leads_by_stage_month_staff($fromyearmonth, $toyearmonth,$status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND assigned IN(".$assigned.") AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE assigned IN(".$assigned.")  AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."'  AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			if(get_staff_role()==2 || get_staff_role()==3 || get_staff_role()==5 || get_staff_role()==6 || get_staff_role()==8){	
				$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads where (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59") AND ( CONCAT(",", tblleads.reportingto, ",")  LIKE "%, '.get_staff_user_id().',%"  OR CONCAT(",", tblleads.reportingto, ",")  LIKE "%,'.get_staff_user_id().',%" )';
			}else{
				$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads where (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59') ";
			}
			
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	
	//------------------------------ MTD / YTD / ITD Report -------------------------//
	
	/* public function mtd_no_of_leads_by_stage_month_staff($month,$status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
			
		}else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND dateassigned LIKE "'.$month.'%"');
			$total_rec =  $query->num_rows();
		
		}
		else {
				
			$query = $this->db->query('SELECT id FROM tblleads where dateassigned LIKE "'.$month.'%"');
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function mtd_value_of_leads_by_stage_month_staff($month,$status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND dateassigned LIKE '".$month."%' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE '".$month."%' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status="'.$status.'" AND dateassigned LIKE "'.$month.'%"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE dateassigned LIKE "'.$month.'%"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	public function itd_no_of_leads_by_stage_month_staff($status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where assigned IN ('.$assigned.')');
			$total_rec =  $query->num_rows();
			
		}else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '"');
			$total_rec =  $query->num_rows();
		
		}
		else {
				
			$query = $this->db->query('SELECT id FROM tblleads');
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function itd_value_of_leads_by_stage_month_staff($status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE assigned IN(".$assigned.")";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status="'.$status.'"';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			
			$sql =  'SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads';
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	public function ytd_no_of_leads_by_stage_month_staff($fromyearmonth, $toyearmonth, $status='',$zone_name='')
	{
		$total_own =0;
		$total_rec =0;
		
		if($zone_name != '' && $status != ''){
			
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND assigned IN ('.$assigned.') AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
		} 
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$query = $this->db->query('SELECT id FROM tblleads where assigned IN ('.$assigned.') AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
			
		}else if($status != '') {
				
			$query = $this->db->query('SELECT id FROM tblleads where status="' . $status . '" AND (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
		
		}
		else {
				
			$query = $this->db->query('SELECT id FROM tblleads where (dateassigned BETWEEN "' . $fromyearmonth . '-01 00:00:00" AND "' . $toyearmonth . '-30 23:59:59" )');
			$total_rec =  $query->num_rows();
		
		}
		
		
		 return $total_rec;

	}
	
	public function ytd_value_of_leads_by_stage_month_staff($fromyearmonth, $toyearmonth,$status='',$zone_name='')
	{
		$total_staff = 0;
		$total_own=0;
		
		if($zone_name != '' && $status != ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."' AND assigned IN(".$assigned.") AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		else if($zone_name != '' && $status == ''){
			$where = "region LIKE '%".$zone_name."'";
			$this->db->select('staffid');
			$this->db->where($where);		
			$userarray = $this->db->get('tblstaff')->result_array();
			
			foreach($userarray as $row)  
			{
				$ids[] = $row['staffid']; 
			} 
			$assigned = implode(", ", $ids);
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE assigned IN(".$assigned.")  AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
		}
		elseif($status != ''){
			
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads WHERE status='".$status."'  AND (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59')";
			$total_staff = $this->db->query($sql)->row()->total;
			
		}else{
			
			$sql =  "SELECT COALESCE(SUM(opportunity),0) as total FROM tblleads where (dateassigned BETWEEN '" . $fromyearmonth . "-01 00:00:00' AND '" . $toyearmonth . "-30 23:59:59') ";
			$total_staff = $this->db->query($sql)->row()->total;
			
		}	
		 
		return $total_staff;
	
	}
	
	 */
	//------------------------ Win / loss ---------------------//
	
	
	public function winloss_month_zone($report_months='',$from_date='',$to_date='',$zone_name='',$winloss='',$topRecord='',$staff_id='')
	{
		if($staff_id ==''){
			if(get_staff_role()==2 || get_staff_role()==3 || get_staff_role()==5 || get_staff_role()==6){
				
				 $where_region = "((CONCAT(',', reporting_to, ',')  LIKE '%, ".get_staff_user_id().",%'  OR CONCAT(',', reporting_to, ',')  LIKE '%,".get_staff_user_id().",%') OR staffid=".get_staff_user_id().") AND is_not_staff = 0";
				$this->db->select('staffid');
				$this->db->where($where_region);		
				$userarray = $this->db->get('tblstaff')->result_array();
				foreach($userarray as $row)  
				{
					$ids[] = $row['staffid']; 
				}
				
			}else{
				$where_region = "region IN(".$zone_name.")";
				$this->db->select('staffid');
				$this->db->where($where_region);		
				$userarray = $this->db->get('tblstaff')->result_array();
				foreach($userarray as $row)  
				{
					$ids[] = $row['staffid']; 
				}
			}
			$assigned = implode(", ", $ids);	
		}else{
		   $assigned = $staff_id;
		}
		 
		
		if($winloss == '7,6'){
			
			$where_win = '';
			$where_loss = '';
			
				if ($report_months == 'this_month') {
                    $month = date('Y-m');
					
					$where_win = 'dateassigned LIKE ("' . $month . '%") AND status IN(7) AND assigned IN('.$assigned.')';
					$where_loss = 'dateassigned LIKE ("' . $month . '%") AND status IN(6) AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $where_win = 'dateassigned LIKE ("' . $month . '%") AND status IN(7) AND assigned IN('.$assigned.')';
					$where_loss = 'dateassigned LIKE ("' . $month . '%") AND status IN(6) AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $where_win = 'dateassigned LIKE ("' . $year . '%") AND status IN(7) AND assigned IN('.$assigned.')';
					$where_loss = 'dateassigned LIKE ("' . $year . '%") AND status IN(6) AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $where_win = 'dateassigned LIKE ("' . $year . '%") AND status IN(7) AND assigned IN('.$assigned.')';
					$where_loss = 'dateassigned LIKE ("' . $year . '%") AND status IN(6) AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime(" -3 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where_win = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(7) AND assigned IN('.$assigned.')';
                    $where_loss = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(6) AND assigned IN('.$assigned.')';
				
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime(" -6 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where_win = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(7) AND assigned IN('.$assigned.')';
                    $where_loss = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(6) AND assigned IN('.$assigned.')';
				
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime(" -12 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where_win = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(7) AND assigned IN('.$assigned.')';
                    $where_loss = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(6) AND assigned IN('.$assigned.')';
				
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$where_win = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(7) AND assigned IN('.$assigned.')';
                    $where_loss = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(6) AND assigned IN('.$assigned.')';
				
                }else{
					$report_from = date('Y-m-01', strtotime(" -3 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
					$where_win = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(7) AND assigned IN('.$assigned.')';
                    $where_loss = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN(6) AND assigned IN('.$assigned.')';
				
				}
				
			    						
				 $this->db->select('id,dateassigned,customer_name,assigned,opportunity,project_total_amount,competition,competition1,competition2,competition3,competition4,status,lost_status,status_lost,status_closed_won');
				$this->db->from("tblleads");
				$this->db->where($where_win);
				$this->db->order_by("opportunity",'DESC');
				if($topRecord !='All')
					$this->db->limit($topRecord);
				$lead_details_win = $this->db->get()->result_array(); 
				
				
				$this->db->select('id,dateassigned,customer_name,assigned,opportunity,project_total_amount,competition,competition1,competition2,competition3,competition4,status,lost_status,status_lost,status_closed_won');
				$this->db->from("tblleads");
				$this->db->where($where_loss);
				$this->db->order_by("opportunity",'DESC');
				if($topRecord !='All'){
					$this->db->limit($topRecord);
				}
				$lead_details_loss = $this->db->get()->result_array();
				
				$lead_details = array_merge($lead_details_loss,$lead_details_win);
				
					//$where = 'dateassigned LIKE "%'.$month.'%" AND  status IN('.$winloss.') AND assigned IN('.$assigned.')';
		}
		else{
			   if ($report_months == 'this_month') {
                    $month = date('Y-m');
					
					$where = 'dateassigned LIKE ("' . $month . '%") AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'last_month') {
                    $month = date('Y-m', strtotime(date('Y-m') . " -1 month"));
                    $where = 'dateassigned LIKE ("' . $month . '%") AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'this_year') {
                    $year  = date('Y');
                    $where = 'dateassigned LIKE ("' . $year . '%") AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'last_year') {
                    $year  = date('Y', strtotime(date('Y') . " -1 year"));
                    $where = 'dateassigned LIKE ("' . $year . '%") AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
					
                } else if ($report_months == 'report_sales_months_three_months') {
                    $report_from = date('Y-m-01', strtotime(" -3 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
                  
                } else if ($report_months == 'report_sales_months_six_months') {
                    $report_from = date('Y-m-01', strtotime(" -6 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
                    
                } else if ($report_months == 'report_sales_months_twelve_months') {
                    $report_from = date('Y-m-01', strtotime(" -12 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
                    $where = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
                   
                }else if($report_months =='custom') {
					$report_from = $from_date;
					$report_to= $to_date;
					$where = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
                   
                }else{
					$report_from = date('Y-m-01', strtotime(" -3 MONTH"));
                    $report_to   = date('Y-m-t',strtotime(" -1 MONTH"));
					$where = '(dateassigned BETWEEN "' . $report_from . ' 00:00:00" AND "' . $report_to . ' 23:59:59" ) AND status IN('.$winloss.') AND assigned IN('.$assigned.')';
                  
				}
				 
				//echo $where;
				
				$this->db->select('id,dateassigned, customer_name,assigned,opportunity,project_total_amount,competition,competition1,competition2,competition3,competition4,status,lost_status,status_lost,status_closed_won');
				$this->db->where($where);
				$lead_details = $this->db->order_by('opportunity' , 'desc')->limit($topRecord)->get('tblleads')->result_array(); 
				
		}
		
		 return $lead_details;

	}
	
	public function lead_source_byname($id='')
    {
		$sql .= "SELECT name FROM tblleadssources WHERE id='".$id."'";
        return $this->db->query($sql)->row()->name;
  
    }
	
	public function lead_status_byname($id='')
    {
		$sql .= "SELECT name FROM tblleadsstatus WHERE id='".$id."'";
        return $this->db->query($sql)->row()->name;
  
    }
	
}
