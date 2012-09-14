<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Distribution extends MY_Controller {
	
	protected $limit = 25;
	
	function __construct()
	{
		parent::__construct();
			
		//Load Models
		$this->load->model('distribution/Warehouse_model');
		$this->load->model('products/Products_model');
	}
	
	public function index()
	{
		//Heading
		$this->data['heading'] = 'Магацин: Готови Производи';
		
		$this->data['results'] = $this->Warehouse_model->levels();
		
	}
	
	public function insert_inbound()
	{
		/*
		 * Inserts entries into the
		 * finished Goods warehouse
		 * eg. Storing finished goods
		 */
		//Load form validation library
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('ext_doc','external document','trim');
		$this->form_validation->set_rules('note','comments','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Inserts into databse and reports outcome
			if($warehouse_id = $this->Warehouse_model->insert($_POST))
			{
				$this->_inventory_use($warehouse_id, $_POST['prodname_fk'], $_POST['quantity']);
				$this->utilities->flash('add','distribution/inbounds');
			}		
			else
				$this->utilities->flash('error','distribution/inbounds');
		}

		//Heading
		$this->data['heading'] = 'Влез во Магацин';
	}
	
	public function insert_outbound()
	{
		/*
		 * Inserts outgoings into
		 * finished Goods warehouse
		 * eg. Distributor reservations,direct sales,deduction etc.
		 */
		//Load formvalidation library
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('distributor_fk','distributor','numeric|trim|required');
		$this->form_validation->set_rules('ext_doc','external document','trim');
		$this->form_validation->set_rules('note','comments','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			/*
			 * Sets is_out flag to 1, making
			 * this entry's quantity negative
			 * in the model side
			 */
			$_POST['is_out'] = 1;
			
			//Inserts into databse and reports outcome
			if($this->Warehouse_model->insert($_POST))
				$this->utilities->flash('add','distribution/outbounds');
			else
				$this->utilities->flash('error','distribution/outbounds');
		}

		//Heading
		$this->data['heading'] = 'Излез од Магацин';
		
		$this->data['distributors'] = $this->utilities->get_distributors();
	}
	
	public function edit($id = false, $redirect = false)
	{
		/*
		 * Edits inbounds/outbound entry 
		 * into the warehouse, and then redirects
		 * if set, or defaults
		 */
		$this->data['result'] = $this->Warehouse_model->select_single($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','distribution');
		
		
		//Heading
		if($this->data['result']->is_out == 0)
		{
			$this->data['heading'] = 'Корекција на Приемница';
			$redirect = 'inbounds';
		}
		else
		{
			$this->data['heading'] = 'Корекција на Испратница';
			$this->data['distributors'] = $this->utilities->get_distributors();
			$redirect = 'outbounds';
		}
		//Load form validation library
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('note','comments','trim');
		$this->form_validation->set_rules('ext_doc','external document','trim');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Inserts into databse and reports outcome
			if($this->Warehouse_model->update($id,$_POST))
			{
				/*
				 * If an inbound entry has been modified,
				 * and the qty has changed, recalculate all
				 * inventory deductions again for the new quantity
				 * according to the Bill of Materials
				 */
				if(($this->data['result']->is_out == 0) && $redirect == 'inbounds')
					$this->_inventory_use($id, $_POST['prodname_fk'], $_POST['quantity']);
				
				$this->utilities->flash('add','distribution/'.$redirect);
			}
			else
				$this->utilities->flash('error','distribution/'.$redirect);
		}	
	}
	
	public function digg($id = false,$offset=null)
	{
		//Heading
		$this->data['heading'] = 'Картица';

		/*
		 * If $id is not supplied, or does not exist
		 * redirect to this controllers index
		 */	
		$temp = $this->Warehouse_model->select_item($id,$this->limit,$offset);
		if(!$temp)
			$this->utilities->flash('void','distribution');
				
		//Retreive data from Model
		$this->data['product'] = $this->Products_model->select_single($id);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("distribution/digg/$id");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 4;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
	}
	
	public function view($id = false, $type = false)
	{
		/*
		 * Retreives and displayes only
		 * SINGLE inbound/outbound entry as view
		 * 
		 * Type defines whether view to display
		 * Inbound or Outbound
		 */
		$this->data['master'] = $this->Warehouse_model->select_single($id);
		
		if(!$this->data['master'])
			$this->utilities->flash('void','distribution');
		
		/*
		 * If this is an Inbound warehouse movement,
		 * details will be present, and contain all
		 * raw material deductions from Inventory
		 */
		if($this->data['master']->is_out == 0)
		{
			$this->load->model('procurement/Inventory_model');
			$this->data['details'] = $this->Inventory_model->select_use($this->data['master']->id);
			$this->data['heading'] = 'Приемница';
		}
		else
			$this->data['heading'] = 'Испратница';
	}
	
	public function inbounds($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		/*
		 * Retreives all inbound entires
		 * into the warehouse
		 */
			
		//Heading
		$this->data['heading'] = 'Влез во Магацин';
		
		$this->data['products'] = $this->utilities->get_products('salable',false,true,'- Артикл -');
		
		//Limit Per Page
		$limit = 25;
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateoforigin'=>'Датум',
			'prodname_fk'=>'Производ',
			'quantity'=>'Влез',
			'qty_current'=>'Старо Салдо',
			'qty_new'=>'Ново Салдо',
			'dateofentry'=>'Внес'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk')
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateoforigin','prodname_fk','quantity','qty_current',
								'qty_new','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->Warehouse_model->select_all_inbound($query_array, $sort_by, $sort_order, $limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("distribution/inbounds/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $limit;
		$config['uri_segment'] = 6;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function in_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("distribution/inbounds/$query_id");
	}
	
	public function outbounds($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		/*
		 * Retreives all outbound entires
		 * into the warehouse
		 */
		
		//Heading
		$this->data['heading'] = 'Излез од Магацин';
		
		$this->data['products'] = $this->utilities->get_products('salable',false,true,'- Артикл -');
		$this->data['distributors'] = $this->utilities->get_distributors();
		
		//Limit Per Page
		$limit = 25;
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateoforigin'=>'Датум',
			'prodname_fk'=>'Производ',
			'quantity'=>'Излез',
			'qty_current'=>'Старо Салдо',
			'qty_new'=>'Ново Салдо',
			'distributor_fk'=>'Дистрибутер',
			'ext_doc'=>'Документ',
			'dateofentry'=>'Внес'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'distributor_fk' => $this->input->get('distributor_fk')
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateoforigin','prodname_fk','quantity','qty_current',
								'qty_new','distributor_fk','ext_doc','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->Warehouse_model->select_all_outbound($query_array, $sort_by, $sort_order, $limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("distribution/outbounds/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $limit;
		$config['uri_segment'] = 6;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function out_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'distributor_fk' => $this->input->post('distributor_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("distribution/outbounds/$query_id");
	}
	
	public function delete($id = false)
	{
		/*
		 * Deletes the passed ID,
		 * and redirects based on the type
		 * of warehouse entry: 
		 *  1. is_out = 0, inbound entry
		 *  2. is_out = 1, outbound entry
		 */
		$this->data['result'] = $this->Warehouse_model->select_single($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','distribution');
			
		if($this->data['result']->is_out == 0)
			$redirect = 'inbounds';
		else
			$redirect = 'outbounds';
			
		if($this->Warehouse_model->delete($id))
			$this->utilities->flash('delete','distribution/'.$redirect);
		else
			$this->utilities->flash('error','distribution/'.$redirect);	
	}
	

	private function _inventory_use($warehouse_id,$product_id,$quantity)
	{
		//Loading Models
		$this->load->model('production/Bomdetails_model');
		$this->load->model('production/Boms_model');
		$this->load->model('procurement/Inventory_model');
		
		$bom_id = $this->Boms_model->select_by_product($product_id);
		
		if(!$bom_id)
			return false;

		$results = $this->Inventory_model->has_deducation($warehouse_id);
		
		if($results)
		{
			foreach ($results as $row )
				$this->Inventory_model->delete($row['id']);
		}

		/*
		 * Retreive all components for specific Bill of Materials (bom_id) 
		 */
		$bom_components = $this->Bomdetails_model->select(array('id'=>$bom_id));
							
		foreach ($bom_components as $component)
		{
			$options = array(
				'prodname_fk'=> $component->prodname_fk,
				'warehouse_fk'=> $warehouse_id,
				'quantity' => (($component->quantity * $quantity) * -1),
				'received_by' => $this->session->userdata('userid'),
				'type' => '0',
				'is_use' => 1
			);

			unset($_POST);
				
			$this->Inventory_model->insert($options);
		}		
	}
}