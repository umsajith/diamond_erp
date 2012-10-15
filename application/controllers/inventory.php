<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('procurement/inventory_model','inv');
		$this->load->model('partners/partners_model','par');
		$this->load->model('products/products_model','prod');
    }
	
	public function index()
	{	
		//Heading
		$this->data['heading'] = 'Магацин: Сировини';
		
		//Retreive data from Model
		$this->data['results'] = $this->inv->levels();
	}
	
	public function purchase_orders($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Нарачки';
		
		//Generate dropdown menu data
		$this->data['products'] = $this->utilities->get_products('purchasable',false,true,'- Артикл -');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateoforder'=>'Нарачано',
			'prodname_fk'=>'Артикл',
			'qty_current'=>'Лагер',
			'quantity'=>'Количина',
			'partner_fk'=>'Добавувач',
			'assigned_to'=>'Задолжение',
			'purchase_method'=>'Начин',
			'po_status'=>'Статус',
			'dateofentry'=>'Внес'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'pcname_fk' => $this->input->get('pcname_fk'),
			'partner_fk' => '',
			'type' => 'po'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateoforder','prodname_fk','partner_fk','quantity','qty_current',
								'assigned_to','purchase_method','po_status','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("inventory/purchase_orders/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
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
	
	public function po_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'pcname_fk' => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/purchase_orders/$query_id");
	}
	
	public function goods_receipts($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Приемници';
				
		//Generate dropdown menu data
		$this->data['products'] = $this->utilities->get_products('purchasable',false,true,'- Артикл -');
		$this->data['vendors'] = $this->par->dropdown('vendors');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'datereceived'=>'Примено',
			'prodname_fk'=>'Артикл',
			'partner_fk'=>'Добавувач',
			'quantity'=>'Количина',
			'purchase_method'=>'Начин',
			'price'=>'Цена(без ДДВ)',	
			'dateoforder'=>'Нарачано',
			'dateofentry'=>'Внес'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'partner_fk' => $this->input->get('partner_fk'),
			'pcname_fk' => $this->input->get('pcname_fk'),
			'type' => 'gr'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('datereceived','prodname_fk','partner_fk','quantity',
								'price','purchase_method','dateoforder','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("inventory/goods_receipts/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
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
	
	public function gr_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'partner_fk' => $this->input->post('partner_fk'),
			'pcname_fk' => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/goods_receipts/$query_id");
	}
	
	public function adjustments($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Порамнување';
		
		//Generate dropdown menu data
		$this->data['products'] = $this->utilities->get_products('purchasable',true,true,'- Артикл -');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (
			'dateofentry'=>'Внес',	
			'prodname_fk'=>'Артикл',
			'pcname_fk'=>'Категорија',
			'quantity'=>'Количина'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'pcname_fk' => $this->input->get('pcname_fk'),
			'partner_fk' => '',
			'type' => 'adj'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('prodname_fk','pcname_fk','quantity','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("inventory/adjustments/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
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
	
	public function adj_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'pcname_fk' => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/adjustments/$query_id");
	}
	
	public function digg($id, $offset = null)
	{
		//Heading
		$this->data['heading'] = 'Картица';
		/*
		 * If $id is not supplied, or does not exist
		 * redirect to this controllers index
		 */		
		$temp = $this->inv->select_item($id,$this->limit,$offset);
		if(!$temp)
			$this->utilities->flash('void','inventory');
				
		//Retreive data from Model
		$this->data['product'] = $this->prod->select_single($id);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("inventory/digg/$id");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 4;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
	}
	
	function insert_gr()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('partner_fk','vendor','trim|required');
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('purchase_method','purchase method','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('dateofexpiration','date of expiration','trim');
		$this->form_validation->set_rules('comments','comments','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Inserts a Goods Receipt
			$_POST['type'] = 'gr';
				
			//Inserts into databse and reports outcome
			if($this->inv->insert($_POST))
				$this->utilities->flash('add','goods_receipts');
			else
				$this->utilities->flash('error','goods_receipts');
		}
		//Load Partner model for Dropdown creation
		$this->data['partners'] = $this->par->dropdown('vendors');

		//Heading
		$this->data['heading'] = 'Внес на Приемница';
	}
	
	function insert_po()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('dateoforder','date of order','trim');
		$this->form_validation->set_rules('comments','comments','trim');

		//Check if form has been submited
		if ($this->form_validation->run())
		{			
			//Inserts a Purchase Order
			$_POST['type'] = 'po';
			$_POST['po_status'] = 'pending';
			
			//Sets the Partner to NULL
			$_POST['partner_fk'] = null;

			//Sets the Date of Order  to NOW
			$_POST['dateoforder'] = mdate("%Y-%m-%d",now());
			
			//Successful validation
			if($this->inv->insert($_POST))
				$this->utilities->flash('add','purchase_orders');
			else	
				$this->utilities->flash('error','purchase_orders');
		}

		//Heading
		$this->data['heading'] = 'Внес на Нарачка';
	}
	
	function insert_adj()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('comments','comment','trim|required');
		$this->form_validation->set_rules('is_use','is use','trim');

		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Inserts an adjustment
			$_POST['type'] = 'adj';
			
			//Sets the Partner to NULL
			$_POST['partner_fk'] = null;
			
			//If is_use is checkd, reverts the sign of quantity
			if(isset($_POST['is_use'])&&($_POST['is_use'])==1)
				$_POST['quantity'] = $_POST['quantity']* (-1);
			
			//Successful validation
			if($this->inv->insert($_POST))
				$this->utilities->flash('add','adjustments');
			else
				$this->utilities->flash('error','adjustments');
		}

		//Heading
		$this->data['heading'] = 'Внес на Порамнување';
	}
	
	function edit($page,$id)
	{
		
		if(!in_array($page, array('po','gr','adj')))
			$page = 'po';
			
		$this->data['page'] = $page;		
		
		if($page == 'po')
		{
			$heading = 'Нарачка';
			$redirect = 'purchase_orders';
			
			$this->data['employees'] = $this->utilities->get_employees();
		}	
		if($page == 'gr')
		{
			$heading = 'Приемница';
			$redirect = 'goods_receipts';

			$this->form_validation->set_rules('partner_fk','vendor','trim|required');
			$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		}
		
		//Retreives ONE product from the database
		$this->data['goods_receipt'] = $this->inv->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['goods_receipt'])
			$this->utilities->flash('void',$redirect);
					
		//Defining Validation Rules	
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('dateoforder','date of order','trim');
		$this->form_validation->set_rules('dateofexpiration','date of expiration','trim');
		$this->form_validation->set_rules('comments','comment','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->inv->update($id,$_POST))
				$this->utilities->flash('update',$redirect);
			else	
				$this->utilities->flash('error',$redirect);
		}
		
		//Heading
		$this->data['heading'] = 'Корекција на ' . $heading;
		
		$this->data['vendors'] = $this->par->dropdown('vendors');
	}
	
	//AJAX - Marks the Purchase Order into Good Receipts and adds to inventory
	function receive_po() 
	{
		$data['ids'] = json_decode($_POST['ids']);
		
		if($this->inv->receive_po($data))
			echo 1;
			
		exit;
	}
	
	function view($page, $id)
	{		
		if(!in_array($page, array('po','gr','adj')))
			$page = 'po';
			
		if($page == 'po')
		{
			$heading = 'Нарачка';
			$redirect = 'purchase_orders';
			$this->content_view = 'inventory/purchase_order';
		}
		if($page == 'gr')
		{
			$heading = 'Приемница';
			$redirect = 'goods_receipts';
			$this->content_view = 'inventory/goods_receipt';
		}
		if($page == 'adj')
		{
			$heading = 'Порамнување';
			$redirect = 'adjustments';
			$this->content_view = 'inventory/adjustment';
		}
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->inv->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void',$redirect);

		//Heading
		$this->data['heading'] = $heading;
	}
	
	function delete($page = 'po',$id)
	{
		if(!in_array($page, array('po','gr','adj')))
			$page = 'po';	

		if($page == 'po')
			$redirect = 'purchase_orders';
		if($page == 'gr')
			$redirect = 'goods_receipts';
		if($page == 'adj')
			$redirect = 'adjustments';
			
		//Retreives data from MASTER Model
		$this->data['master'] = $this->inv->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void',$redirect);
			
		if($this->inv->delete($id))
			$this->utilities->flash('delete',$redirect);
		else
			$this->utilities->flash('error',$redirect);
	}	
}