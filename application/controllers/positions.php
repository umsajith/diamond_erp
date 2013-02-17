<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Positions extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/positions_model','pos');
	}
	
	public function index($sort_by = 'position', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Работни Места';
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'position'=>'Назив',
			'department'=>'Сектор',
			'base_salary'=>'Основна Плата',
			'bonus'=>'Бонсу',
			'commision'=>'Провизија'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('position','department','base_salary',
						'bonus','commision');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'position';
		
		//Retreive data from Model
		$temp = $this->pos->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("positions/index/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 5;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
	
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('position','position name','trim|required');
		$this->form_validation->set_rules('dept_fk','department','required');
		$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
		$this->form_validation->set_rules('bonus','bonus','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('requirements','requirements','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pos->insert($_POST))
				$this->utilities->flash('add','positions');
			else
				$this->utilities->flash('error','positions');	
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = 
			$this->utilities->get_dropdown('id', 'department','exp_cd_departments','- Сектори -');

		//Heading
		$this->data['heading'] = 'Внес на Работно Место';
	}
	
	public function edit($id)
	{
		$this->data['position'] = $this->pos->select_single($id);
		if(!$this->data['position'])
			$this->utilities->flash('void','positions');

		//Defining Validation Rules
		$this->form_validation->set_rules('position','position name','trim|required');
		$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
		$this->form_validation->set_rules('bonus','bonus','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('requirements','requirements','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pos->update($_POST['id'],$_POST))
				$this->utilities->flash('update','positions');
			else
				$this->utilities->flash('error','positions');
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = 
			$this->utilities->get_dropdown('id', 'department','exp_cd_departments','- Сектори -');

		//Heading
		$this->data['heading'] = 'Корекција на Работно Место';
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Работно Место';

		$this->data['master'] = $this->pos->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','positions');
	}

	public function delete($id)
	{
		if(!$this->pos->get($id))
			$this->utilities->flash('void','positions');

		if($success = $this->pos->delete($id))
			$this->utilities->flash('delete','positions');
		else
			$this->utilities->flash('error','positions');
	}
}