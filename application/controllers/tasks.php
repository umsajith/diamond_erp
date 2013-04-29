<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/task_model','tsk');
		$this->load->model('production/boms_model','bom');
		$this->load->model('uom/uom_model','uom');
	}
    
	public function index($sort_by = 'taskname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Работни Задачи';

		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'taskname'            =>'Назив',
			'is_production'       =>'Производство',
			'base_unit'           =>'Основна Единица',
			'rate_per_unit'       =>'Цена/ЕМ',
			'rate_per_unit_bonus' =>'Цена/ЕМ Бонус'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('taskname','is_production','base_unit',
						'rate_per_unit','rate_per_unit_bonus');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'taskname';
		
		//Retreive data from Model
		$temp = $this->tsk->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("tasks/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
    
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('taskname','task name','trim|required');
		$this->form_validation->set_rules('rate_per_unit','unit rate','trim|required|numeric');
		$this->form_validation->set_rules('rate_per_unit_bonus','unit rate bonus','trim|numeric');
		$this->form_validation->set_rules('base_unit','base unit','trim|required|numeric');
		$this->form_validation->set_rules('uname_fk','UOM','trim|required|numeric');
		$this->form_validation->set_rules('description','description','trim|xss_clean');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->tsk->insert($_POST))
				$this->utilities->flash('add','tasks');
			else
				$this->utilities->flash('error','tasks');
		}	

		// Generating dropdown menu's
		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
		$this->data['boms'] = $this->bom->dropdown('id','name');

		//Heading
		$this->data['heading'] = 'Нова Работна Задача';
	}
    
	public function edit($id)
	{
		$this->data['task'] = $this->tsk->select_single($id);
		if(!$this->data['task'])
			$this->utilities->flash('void','tasks');

		//Defining Validation Rules
		$this->form_validation->set_rules('taskname','task name','trim|required');
		$this->form_validation->set_rules('rate_per_unit','unit rate','trim|required|numeric');
		$this->form_validation->set_rules('rate_per_unit_bonus','unit rate bonus','trim|numeric');
		$this->form_validation->set_rules('base_unit','base unit','trim|required|numeric');
		$this->form_validation->set_rules('description','description','trim|xss_clean');
			
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->tsk->update($id,$_POST))
				$this->utilities->flash('update','tasks');
			else
				$this->utilities->flash('error','tasks');
		}
		
		// Generating dropdown menu's
		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
		$this->data['boms'] = $this->bom->dropdown('id','name');

		//Heading
		$this->data['heading'] = 'Корекција на Работна Задача';
	}
	
	public function view($id)
	{
		$this->data['master'] = $this->tsk->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','tasks');

		//Heading
		$this->data['heading'] = 'Работна Задача';
	}
    
	public function delete($id)
	{
		if(!$this->tsk->select_single($id))
			$this->utilities->flash('void','tasks');

		if($this->tsk->delete($id))
			$this->utilities->flash('delete','tasks');
		else
			$this->utilities->flash('error','tasks');
	}

	public function ajxUOM()
	{
		if(!$_GET['task']) exit;
		header('Content-Type: application/json',true); 
		echo json_encode($this->tsk->getUOM($_GET['task']));
		exit;
	}
	
	public function dropdown()
	{
		header('Content-Type: application/json',true); 
		echo json_encode($this->tsk->dropdown());
		exit;
	}	
}