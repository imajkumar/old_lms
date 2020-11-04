<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Invoice_items_model extends CRM_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
	 
	  public function list_category($id = null) {
        $this->db->select()->from('tblitems');
	
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('i');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }
	 
	 
	 
	 
    public function get($id)
    {
        $columns = $this->db->list_fields('tblitems');
        $rateCurrencyColumns = '';
        foreach($columns as $column){
            if(strpos($column,'rate_currency_') !== FALSE){
                $rateCurrencyColumns .= $column.',';
            }
        }
        $this->db->select($rateCurrencyColumns.'tblitems.id as itemid,rate,itme_code,subgroup_id,depot_code,
            t1.taxrate as taxrate,t1.id as taxid,t1.name as taxname,
            t2.taxrate as taxrate_2,t2.id as taxid_2,t2.name as taxname_2,
            description,long_description,group_id,tblitems_groups.name as group_name,unit');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes t1', 't1.id = tblitems.tax', 'left');
        $this->db->join('tbltaxes t2', 't2.id = tblitems.tax2', 'left');
        $this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems.group_id', 'left');
        $this->db->order_by('description', 'asc');
        if (is_numeric($id)) {
            $this->db->where('tblitems.id', $id);

            return $this->db->get()->result_array();
        }

        return $this->db->get()->result_array();
    }
	
	public function getstock($id)
    {
		
        $columns = $this->db->list_fields('tblitems');
        $rateCurrencyColumns = '';
        foreach($columns as $column){
            if(strpos($column,'rate_currency_') !== FALSE){
                $rateCurrencyColumns .= $column.',';
            }
        }
        $this->db->select($rateCurrencyColumns.'tblitems.id as itemid,rate,stock,depot_code,itme_code,subgroup_id,depot_code,
            t1.taxrate as taxrate,t1.id as taxid,t1.name as taxname,
            t2.taxrate as taxrate_2,t2.id as taxid_2,t2.name as taxname_2,
            description,long_description,group_id,tblitems_groups.name as group_name,unit');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes t1', 't1.id = tblitems.tax', 'left');
        $this->db->join('tbltaxes t2', 't2.id = tblitems.tax2', 'left');
        $this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems.group_id', 'left');
        $this->db->order_by('description', 'asc');
        /* if (!is_admin()) {
			$this->load->model('staff_model');
			$user_id = get_staff_user_id();
			
			$result = $this->staff_model->get_staff_data($user_id);
			
			$str_arr = explode (",", $result['depot_code']);
			
			$this->db->select()->from('depo_master');
			
			$this->db->where_in('depo_master.id', $str_arr);
			
			$resultcode = $this->db->get()->result_array();
			$deptcodeid = array();
			while($resultcode as $deptcode)
			{
				$deptcodeid = 
			}
			echo $resultcode[0]['depcode'];
			print_r($resultcode);
			exit;
			echo $depot_codeids = $resultcode['depcode'];
			
			exit;
			
			
			
			$this->db->where_in('tblitems.depot_code', $id);

            return $this->db->get()->result_array();
        } */

        return $this->db->get()->result_array();
    }
	
	

    public function get_grouped()
    {
        $items = array();
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get('tblitems_groups')->result_array();

        array_unshift($groups, array(
            'id' => 0,
            'name' => ''
        ));

        foreach ($groups as $group) {
            $this->db->select('*,tblitems_groups.name as group_name,tblitems.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get('tblitems')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = array();
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }

    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        if ($data['tax'] == '') {
            unset($data['tax']);
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            unset($data['tax2']);
        }

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $columns = $this->db->list_fields('tblitems');
        $this->load->dbforge();

        foreach($data as $column => $itemData){
            if(!in_array($column,$columns) && strpos($column,'rate_currency_') !== FALSE){
                $field = array(
                        $column => array(
                            'type' =>'decimal(15,'.get_decimal_places().')',
                            'null'=>true,
                        )
                );
                $this->dbforge->add_column('tblitems', $field);
            }
        }

        $this->db->insert('tblitems', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields, true);
            }
            logActivity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['tax']) && $data['tax'] == '') {
            $data['tax'] = NULL;
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
             $data['tax2'] = NULL;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $columns = $this->db->list_fields('tblitems');
        $this->load->dbforge();

        foreach($data as $column => $itemData){
            if(!in_array($column,$columns) && strpos($column,'rate_currency_') !== FALSE){
                $field = array(
                        $column => array(
                            'type' =>'decimal(15,'.get_decimal_places().')',
                            'null'=>true,
                        )
                );
                $this->dbforge->add_column('tblitems', $field);
            }
        }

        $affectedRows = 0;
        $this->db->where('id', $itemid);
        $this->db->update('tblitems', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
            $affectedRows++;
        }

        if(isset($custom_fields)) {
            if(handle_custom_fields_post($itemid, $custom_fields, true)) {
                $affectedRows++;
            }
        }
        return $affectedRows > 0 ? true : false;
    }

    public function search($q){

        $this->db->select('rate, id, description as name, long_description as subtext');
        $this->db->like('description',$q);
        $this->db->or_like('long_description',$q);

        $items = $this->db->get('tblitems')->result_array();

        foreach($items as $key=>$item){
            $items[$key]['subtext'] = strip_tags(mb_substr($item['subtext'],0,200)).'...';
            $items[$key]['name'] = '('._format_number($item['rate']).') ' . $item['name'];
        }

        return $items;
    }

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
	 
	 public function edit_item($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblitems', $data);
       

        return true;
    } 
	public function inactive($id)
    {
		$data = array('status' => '1');
        $this->db->where('id', $id);
        $this->db->update('tblitems', $data);
        return true;
    } 
	public function active($id)
    {
		$data = array('status' => '0');
        $this->db->where('id', $id);
        $this->db->update('tblitems', $data);
        return true;
    } 
	public function inactive_group($id)
    {
		$data = array('group_status' => '1');
        $this->db->where('id', $id);
        $this->db->update('tblitems_groups', $data);
        return true;
    } 
	 
	public function inactive_sub_group($id)
    {
		$data = array('sub_group_status' => '1');
        $this->db->where('id', $id);
        $this->db->update('tblitems_sub_groups', $data);
        return true;
    } 
	 
	 
	 
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblitems');
        if ($this->db->affected_rows() > 0) {

            $this->db->where('relid',$id);
            $this->db->where('fieldto','items_pr');
            $this->db->delete('tblcustomfieldsvalues');

            logActivity('Invoice Item Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_groups()
    {
        $this->db->select()->from('tblitems_groups');
		$this->db->group_by('name');
		$this->db->order_by("name", "asc");
		$query = $this->db->get();
		$result = $query->result_array();
		//$result = $this->db->get('tblitems_groups')->result_array();

        return $result;
    }   
	public function get_sub_groups()
    {
        
		$this->db->select('tblitems_sub_groups.id,tblitems_sub_groups.name,tblitems_sub_groups.sub_group_status,tblitems_sub_groups.group_id,tblitems_groups.name as group_name');
        $this->db->from('tblitems_sub_groups');
		$this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems_sub_groups.group_id', 'left');
		$this->db->order_by('tblitems_sub_groups.name', 'asc');
        return $this->db->get()->result_array();
    }  

	public function get_groups_document()
    {
        $this->db->order_by('name','asc');

        return $this->db->get('document_required')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert('tblitems_groups', $data);
        logActivity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }
 public function add_sub_group($data)
    {
        $this->db->insert('tblitems_sub_groups', $data);
        logActivity('Sub Items Group Created [Name: ' . $data['name'] . ','.'Group: ' . $data['group_id'] . ']');

        return $this->db->insert_id();
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblitems_groups', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }  public function edit_sub_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblitems_sub_groups', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Sub Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get('tblitems_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update('tblitems', array(
                'group_id' => 0
            ));

            $this->db->where('id', $id);
            $this->db->delete('tblitems_groups');

            logActivity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
	  public function delete_sub_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get('tblitems_sub_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update('tblitems', array(
                'group_id' => 0
            ));

            $this->db->where('id', $id);
            $this->db->delete('tblitems_sub_groups');

            logActivity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
	
	public function getitemBycat($group_id) {
        $this->db->select()->from('tblitems');
		$this->db->where('group_id', $group_id);
        $query = $this->db->get();
		return $query->result_array();
       
    }
	public function wattage_data($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('tblitems_sub_groups')->row()->name;
		
    }
	public function depo_master_data($id='')
    {
        $this->db->where('id', $id);

        return $this->db->get('depo_master')->row()->description;
		
    }
}
