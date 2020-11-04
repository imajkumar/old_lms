<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Invoice_items extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
    }

    /* List all available items */
    public function index()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
			$this->load->model('currencies_model');
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
		$data['get_sub_groups'] = $this->invoice_items_model->get_sub_groups();
		
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['title'] = _l('invoice_items');
		
        $this->load->view('admin/invoice_items/manage', $data);
    } 
	public function item_stock()
    {
        /* if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        } */
		
		$this->load->model('currencies_model');
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->getstock();
		
        $this->load->view('admin/invoice_items/manage_item_stock', $data);
    }
    public function table(){
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        $this->app->get_table_data('invoice_items');
    }
    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item' => $this->invoice_items_model->get($id)
                    ));
                } else {
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message
                    ));
                }
            }
			redirect('admin/invoice_items');
        }
    }

    public function add_group()
    {
        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->invoice_items_model->add_group($this->input->post());
            set_alert('success', _l('added_successfully', _l('item_group')));
        }
    }  


	public function add_sub_group()
    {
        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->invoice_items_model->add_sub_group($this->input->post());
            set_alert('success', _l('added_successfully', 'Sub item group'));
        }
		
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_group')));
        }
    }
	public function update_sub_group($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_sub_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', 'Sub item group'));
        }
    }

    public function delete_group($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
	public function inactive_group($id)
    {
        if (has_permission('items', '', 'delete')) {			
            if ($this->invoice_items_model->inactive_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }

	public function inactive_sub_group($id)
    {
        if (has_permission('items', '', 'delete')) {			
            if ($this->invoice_items_model->inactive_sub_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?sub_groups_modal=true'));
    }

	
	public function delete_sub_group($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_sub_group($id)) {
                set_alert('success', _l('deleted', 'Sub item group'));
            }
        }
        redirect(admin_url('invoice_items?sub_groups_modal=true'));
    }

    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }
	public function inactive($id)
    {
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->inactive($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('updated', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }
	public function active($id)
    {
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->active($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('updated', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }

    public function search(){
        if($this->input->post() && $this->input->is_ajax_request()){
            echo json_encode($this->invoice_items_model->search($this->input->post('q')));
        }
    }

    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item                   = $this->invoice_items_model->get($id);
            $item->long_description = nl2br($item->long_description);
            $item->custom_fields_html = render_custom_fields('items',$id,array(),array('items_pr'=>true));
            $item->custom_fields = array();

            $cf = get_custom_fields('items');

            foreach($cf as $custom_field) {
                $val = get_custom_field_value($id,$custom_field['id'],'items_pr');
                if($custom_field['type'] == 'textarea') {
                    $val = clear_textarea_breaks($val);
                }
                $custom_field['value'] = $val;
                $item->custom_fields[] = $custom_field;
            }

            echo json_encode($item);
        }
    }
	
	
	 function update($id)
    {
		$data['id']=$id;
		 $data['items_groups'] = $this->invoice_items_model->get_groups();
		 $data['get_items'] = $this->invoice_items_model->list_category($id);
		 
 
	  
		   
		  $this->load->view('admin/invoice_items/item_edit', $data); 
	  
    }
		
	 function update_record($id)
    {
		
		
		 
 $data_array = array (
 
            'description' => $this->input->post('description'),  
			'long_description' => $this->input->post('long_description'),
			'rate' => $this->input->post('rate'),
			'itme_code' => $this->input->post('itme_code'), 
			'group_id' => $this->input->post('item_group'), 
			'subgroup_id' => $this->input->post('subgroup_id'), 
			'unit' => $this->input->post('unit'), 
			 );
			
     $this->invoice_items_model->edit_item($data_array,$id);
	  
		   
		 redirect('admin/invoice_items');
	  
    }
	
	
	public function import()
    {
        if (!has_permission('items', '', 'create')) {
            access_denied('items');
        }
     
        $total_imported = 0;
        if ($this->input->post()) {

            // Used when checking existing company to merge contact
            $contactFields = $this->db->list_fields('tblitems');

            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $newFilePath = TEMP_FOLDER . $_FILES['file_csv']['name'];
                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 777);
                    }
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $import_result = true;
                        $fd            = fopen($newFilePath, 'r');
                        $rows          = array();
                        while ($row = fgetcsv($fd)) {
                            $rows[] = $row;
                        }

                        $data['total_rows_post'] = count($rows);
                        fclose($fd);
                        if (count($rows) <= 1) {
                            set_alert('warning', 'Not enought rows for importing');
                            redirect(admin_url('admin/invoice_items'));
                        }
                        unset($rows[0]);
                        if ($this->input->post('simulate')) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }
                       

                        foreach ($rows as $row) {
                            // do for db fields
                            $insert    = array();
                            $duplicate = false;
                            for ($i = 0; $i < count($db_fields); $i++) {
                                if (!isset($row[$i])) {
                                    continue;
                                }
                                if ($db_fields[$i] == 'name') {
                                    $name_exists = total_rows('tblitems', array(
                                        'name' => $row[$i],
                                    ));
                                    // don't insert duplicate emails
                                    if ($name_exists > 0) {
                                        $duplicate = true;
                                    }
                                }
                                
                                if($row[$i] === 'NULL' || $row[$i] === 'null') {
                                    $row[$i] = '';
                                }
                                $insert[$db_fields[$i]] = $row[$i];
                            }


                            if ($duplicate == true) {
                                continue;
                            }
                            if (count($insert) > 0) {
                                $total_imported++;
                                
                                if (!$this->input->post('simulate')) {
                                    
                                    foreach ($insert as $key =>$val) {
                                        $insert[$key] = trim($val);
                                    }


                                    $clientid   = $this->invoice_items_model->add($insert, true);
                                   
                                }
								
                            }
                            $_row_simulate++;
                            if ($this->input->post('simulate') && $_row_simulate >= 100) {
                                break;
                            }
                        }
                        unlink($newFilePath);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
        }
        if (isset($import_result)) {
            set_alert('success', _l('import_total_imported', $total_imported));
        }
       
        $this->load->view('admin/invoice_items/import_group', $data);
    }

	
	
	
	
}
