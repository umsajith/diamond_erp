<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_list extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/col_model','col');
		$this->load->model('orders/cod_model','cod');
	}

	public function index($query_id = 0,$sort_by = 'date', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = "Извештаи за Продажба";

		//Dropdown Menus
		$this->data['distributors'] = $this->utilities->get_distributors();

		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'date'=>'Датум',
			'distributor_id'=>'Дистрибутер',
			'ext_doc'=>'Документ',
			'code'=>'Код',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = [
			'distributor_id' => $this->input->get('distributor_id'),
			'q' => $this->input->get('q')
		];

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = ['date','distributor_id','ext_doc',
								'dateofentry','code'];
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'date';

		//Retreive data from Model
		$temp = $this->col->select($query_array, $sort_by, $sort_order, $this->limit, $offset);

		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];

		$this->data['pagination'] = 
		paginate("orders_list/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}

	public function search()
	{
		//(strlen($_POST['q'])) ? $_POST['distributor_id'] = '' : '';
		//(strlen($_POST['distributor_id'])) ? $_POST['q'] = '' : '';

		$query_array = [
			'distributor_id' => $this->input->post('distributor_id'),
			'q' => $this->input->post('q')
		];	
		$query_id = $this->input->save_query($query_array);
		redirect("orders_list/index/{$query_id}");
	}

	public function insert()
	{
		$this->data['heading'] = "Внес на Извештај";
		
		//Check if form has been submited
		if ($_POST)
		{
			if($order_id = $this->col->insert($_POST))
				$this->utilities->flash('add',"orders_list/view/{$order_id}");
		}

		//Dropdown Menus
		$this->data['distributors'] = $this->utilities->get_distributors();
	}

	public function edit($id)
	{
		//Heading
		$this->data['heading'] = "Корекција на Извештај";

		$this->data['master'] = $this->col->select_one($id);
		
		if(!$this->data['master']) $this->utilities->flash('void','orders_list');

		/*
		 * Prevents from editing locked record
		 */
		if($this->data['master']->locked) $this->utilities->flash('deny','orders_list');
		
		//Check if form has been submited
		if ($_POST)
		{
			if($this->col->update($_POST['id'],$_POST))
				$this->utilities->flash('update','orders_list');
		}

		//Dropdown Menus
		$this->data['distributors'] = $this->utilities->get_distributors();
	}

	public function view($id)
	{
		//Heading
		$this->data['heading'] = "Преглед на Извештај";

		$this->data['master'] = $this->col->select_one($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders_list');

		$this->data['results'] = $this->co->select_by_order_list($id);

		$this->data['products'] = $this->utilities->get_products('salable',false,true,'- Артикл -');
		$this->data['pmodes'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payment_modes','- Плаќање -');
	}

	public function delete($id)
	{
		$this->data['master'] = $this->col->select_one($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders_list');

		if($this->col->delete($id))
			$this->utilities->flash('delete','orders_list');
		else
			$this->utilities->flash('error','orders_list');
	}

	public function ajxLock()
	{
		if($this->col->update_many(json_decode($_POST['ids']), ['locked'=>1],true))
			echo 1;
		exit;	
	}

	public function ajxUnlock()
	{
		if($this->col->update_many(json_decode($_POST['ids']), ['locked'=>0],true))
			echo 1;
		exit;	
	}
}