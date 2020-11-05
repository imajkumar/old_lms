<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Region_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
	
	
	 public function add_region($data)
    {
		
		
        $this->db->insert('tblregion', $data);
        return true;
    }
	 
	 public function get_region(){
		 $this->db->select('tblregion.*,b.state');
        $this->db->from('tblregion');
        $this->db->join('state b', 'b.id=tblregion.state');
     
        $listregion = $this->db->get();
        return $listregion->result_array(); 
		 
	 }
	 
	 
	 public function get($id = null) {
        $this->db->select()->from('tblregion');
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
	  
	 
	 public function add_region_status($data)
    {
  
        $this->db->insert('tblregion', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New  region added [Region ID: ' . $insert_id . ', Name: ' . $data['region'] . ']');

            return $insert_id;
        }

        return false;
    }
	 
	
	 public function update_region($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblregion', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Region Updated [RegionID: ' . $id . ', Name: ' . $data['region'] . ']');

            return true;
        }

        return false;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}