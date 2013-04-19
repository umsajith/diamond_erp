<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		//Load Models
		$this->load->model('products/products_model','prod');	
	}

	public function index($query_id = 0,$sort_by = 'prodname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Артикли';

		// Generating dropdown menu's
		$this->data['warehouses'] = $this->utilities->get_dropdown('id', 'wname','exp_cd_warehouses','- Магацин -');
		$this->data['types'] = $this->utilities->get_dropdown('id', 'ptname','exp_cd_product_type','- Тип -'); 
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'prodname'=>'Назив',
			'ptname_fk'=>'Тип',
			'pcname_fk'=>'Категорија',
			'wname_fk'=>'Магацин',
			'base_unit'=>'Осн.ЕМ',
			'alert_quantity'=>'Мин.Кол.',
			'retail_price'=>'МПЦ',
			'whole_price1'=>'ГПЦ1',
			'commision'=>'Рабат',
			'tax_rate_fk'=>'ДДВ'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'ptname_fk' => $this->input->get('ptname_fk'),
			'pcname_fk' => $this->input->get('pcname_fk'),
			'wname_fk' => $this->input->get('wname_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('prodname','ptname_fk','pcname_fk','wname_fk',
								'base_unit','alert_quantity','retail_price','whole_price1',
								'whole_price2','commision','tax_rate_fk');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'prodname';
		
		//Retreive data from Model
		$temp = $this->prod->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("products/index/$query_id/$sort_by/$sort_order");
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
	
	public function search()
	{
		$query_array = array(
			'ptname_fk' => $this->input->post('ptname_fk'),
			'pcname_fk' => $this->input->post('pcname_fk'),
			'wname_fk' => $this->input->post('wname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("products/index/$query_id");
	}
	
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname','name','trim|required');
		$this->form_validation->set_rules('ptname_fk','product type','trim|required');
		$this->form_validation->set_rules('pcname_fk','product category','trim|required');
		$this->form_validation->set_rules('wname_fk','warehouse','trim|required');
		$this->form_validation->set_rules('uname_fk','unit of measure','trim|required');
		$this->form_validation->set_rules('tax_rate_fk','tax rate','trim|required');
		$this->form_validation->set_rules('code','code','trim');
		$this->form_validation->set_rules('base_unit','base unit','trim|numeric');
		$this->form_validation->set_rules('retail_price','retail price','trim|numeric');
		$this->form_validation->set_rules('description','description','trim');
		$this->form_validation->set_rules('alert_quantity','alert quantity','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('salable','salable','trim|numeric');
		$this->form_validation->set_rules('purchasable','purchasable','trim|numeric');
		$this->form_validation->set_rules('stockable','stockable','trim|numeric');
		
		//Check if form has passed validation
		if ($this->form_validation->run())
		{
			//Successful validation insets into the DB
			if($this->prod->insert($_POST))
				$this->utilities->flash('add','products');
			else
				$this->utilities->flash('error','products');
		}
		
		// Generating dropdown menu's
		$this->data['warehouses'] = $this->utilities->get_dropdown('id', 'wname','exp_cd_warehouses','- Магацин -');
		$this->data['product_types'] = $this->utilities->get_dropdown('id', 'ptname','exp_cd_product_type','- Тип -'); 
		$this->data['product_cates'] = 
			$this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		$this->data['uoms'] = $this->utilities->get_dropdown('id', 'uname','exp_cd_uom','- EM -');
		$this->data['tax_rates'] = $this->utilities->get_dropdown('id', 'rate','exp_cd_tax_rates','- ДДВ -');  	  		

		//Heading
		$this->data['heading'] = 'Внес на Артикл';
	}
	
	public function edit($id)
	{
		//Retreives ONE product from the database
		$this->data['product'] = $this->prod->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['product'])
			$this->utilities->flash('void','products');
		
		//Proccesses the form with the new updated data
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('prodname','name','trim|required');
			$this->form_validation->set_rules('ptname_fk','product type','trim|required');
			$this->form_validation->set_rules('pcname_fk','product category','trim|required');
			$this->form_validation->set_rules('wname_fk','warehouse','trim|required');
			$this->form_validation->set_rules('uname_fk','unit of measure','trim|required');
			$this->form_validation->set_rules('tax_rate_fk','tax rate','trim|required');
			$this->form_validation->set_rules('code','code','trim');
			$this->form_validation->set_rules('base_unit','base unit','trim|numeric');
			$this->form_validation->set_rules('retail_price','retail price','trim|numeric');
			$this->form_validation->set_rules('description','description','trim');
			$this->form_validation->set_rules('alert_quantity','alert quantity','trim|numeric');
			$this->form_validation->set_rules('commision','commision','trim|numeric');
				
			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				//Successful validation insets into the DB
				if($this->prod->update($id,$_POST))
					$this->utilities->flash('update','products');
				else
					$this->utilities->flash('error','products');	
			}	
		}
		
		// Generating dropdown menu's
		$this->data['warehouses'] = $this->utilities->get_dropdown('id', 'wname','exp_cd_warehouses','- Магацин -');
		$this->data['product_types'] = $this->utilities->get_dropdown('id', 'ptname','exp_cd_product_type','- Тип -'); 
		$this->data['product_cates'] = 
			$this->utilities->get_dropdown('id', 'pcname','exp_cd_product_category','- Категорија -');
		$this->data['uoms'] = $this->utilities->get_dropdown('id', 'uname','exp_cd_uom','- EM -');
		$this->data['tax_rates'] = $this->utilities->get_dropdown('id', 'rate','exp_cd_tax_rates','- ДДВ -');  	  		

		//Heading
		$this->data['heading'] = 'Корекција на Артикл';
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Артикл';

		//Retreives data from MASTER Model
		$this->data['master'] = $this->prod->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','products');
	}
	
	public function delete($id)
	{
		if($this->prod->delete($id))
			$this->utilities->flash('delete','products');
		else
			$this->utilities->flash('error','products');
	}

	public function dropdown($type)
	{
		$this->data = $this->prod->get_products($type);
		header('Content-Type: application/json',true);     
		echo json_encode($this->data);
		exit;
	}
}