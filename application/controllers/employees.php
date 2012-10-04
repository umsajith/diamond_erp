<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/employees_model','emp');	
		$this->load->model('hr/emp_tasks_model','empt');		
	}
	
	public function index($query_id = 0,$sort_by = 'employee', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Вработени';
		
		// Generating dropdown menu's
		$this->data['possitions'] = $this->utilities->get_dropdown('id', 'position','exp_cd_positions','- Работно Место -');	
		$this->data['ugroups'] = $this->utilities->get_dropdown('id', 'name','exp_cd_user_groups','- Корисничка Група -',false);
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'=>'Работник',
			'comp_mobile'=>'Мобилен',
			'position'=>'Работно Место',
			'department'=>'Сектор',
			'fixed_wage_only'=>'Само ФП',
			'is_manager'=>'Менаџер',
			'is_distributer'=>'Дистрибутер',
			'fixed_wage'=>'Фиксна Плата',
			'comp_mobile_sub'=>'Тел.Суб.',
			'status'=>'Статус'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'poss_fk' => $this->input->get('poss_fk'),
			'ugroup_fk' => $this->input->get('ugroup_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('employee','comp_mobile','position','department','fixed_wage_only',
								'is_manager','is_distributer','fixed_wage','comp_mobile_sub','status');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'employee';

		//Retreive data from Model
		$temp = $this->emp->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("employees/index/$query_id/$sort_by/$sort_order");
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
			'poss_fk' => $this->input->post('poss_fk'),
			'ugroup_fk' => $this->input->post('ugroup_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("employees/index/$query_id");
	}
	
	public function insert()
	{
		//Load Validation Library
		$this->load->library('form_validation');

		//Defining Validation Rules
		$this->form_validation->set_rules('fname','first name','trim|required');
		$this->form_validation->set_rules('lname','last name','trim|required');
		$this->form_validation->set_rules('code','code','trim|max_lenth[5]');
		$this->form_validation->set_rules('ssn','SSN','trim|required|exact_length[13]|numeric');
		$this->form_validation->set_rules('dateofbirth','date of birth','trim|required');
		$this->form_validation->set_rules('username','username','min_length[5]|max_lenth[15]');
		$this->form_validation->set_rules('password','password','min_length[6]');
		$this->form_validation->set_rules('email','email','trim|valid_email');
		$this->form_validation->set_rules('phone','phone','trim|numeric');
		$this->form_validation->set_rules('mobile','mobile','trim|numeric');
		$this->form_validation->set_rules('comp_mobile','company mobile','trim|numeric');
		$this->form_validation->set_rules('comp_mobile_sub','company mobile subvention','trim|numeric');
		$this->form_validation->set_rules('bank','bank','trim');
		$this->form_validation->set_rules('account_no','account number','trim|numeric');
		$this->form_validation->set_rules('fixed_wage','fixed wage','trim|numeric');
		$this->form_validation->set_rules('social_cont','social contribution','trim|numeric');
		$this->form_validation->set_rules('address','address','trim');
		$this->form_validation->set_rules('note','note','trim');
		$this->form_validation->set_rules('postcode_fk','city','trim|required');
		$this->form_validation->set_rules('poss_fk','possition','trim|required');
		$this->form_validation->set_rules('name_fk','user group','trim');
		$this->form_validation->set_rules('start_date','start date','trim');
		$this->form_validation->set_rules('stop_date','stop date','trim');
		
		//Check if form has passed validation
		if ($this->form_validation->run())
		{
			//Successful insertion
			if($this->emp->insert($_POST))
				$this->utilities->flash('delete','employees');
			else
				$this->utilities->flash('error','employees');
		}
		
		// Generating dropdown menu's
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();	
		$this->data['managers'] = $this->utilities->get_managers();
		$this->data['positions'] = $this->utilities->get_dropdown('id', 'position','exp_cd_positions','- Работно Место -');	
		$this->data['ugroups'] = $this->utilities->get_dropdown('id', 'name','exp_cd_user_groups','- Корисничка Група -',false);	
			
		//Heading
		$this->data['heading'] = 'Внеси Нов Работник';
	}
	
	public function edit($id = false)
	{
		//Retreives ONE product from the database
		$this->data['employee'] = $this->emp->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['employee']) 
			$this->utilities->flash('void','employees');
		
		//If Submit has been posted (EDIT form Submitted), runs the code below
		if($_POST)
		{
			unset($_POST['task']);
				
			//Load Validation Library
			$this->load->library('form_validation');
		
			//Defining Validation Rules
			$this->form_validation->set_rules('fname','first name','trim|required');
			$this->form_validation->set_rules('lname','last name','trim|required');
			$this->form_validation->set_rules('code','code','trim|max_lenth[5]');
			$this->form_validation->set_rules('ssn','SSN','trim|required|exact_length[13]|numeric');
			$this->form_validation->set_rules('dateofbirth','date of birth','trim');
			$this->form_validation->set_rules('username','username','min_length[5]|max_lenth[15]');
			$this->form_validation->set_rules('password','password','min_length[6]');
			$this->form_validation->set_rules('email','email','trim|valid_email');
			$this->form_validation->set_rules('phone','phone','trim|numeric');
			$this->form_validation->set_rules('mobile','mobile','trim|numeric');
			$this->form_validation->set_rules('postcode_fk','city','trim|required');
			$this->form_validation->set_rules('comp_mobile','company mobile','trim|numeric');
			$this->form_validation->set_rules('comp_mobile_sub','company mobile subvention','trim|numeric');
			$this->form_validation->set_rules('fixed_wage','fixed wage','trim|numeric');
			$this->form_validation->set_rules('social_cont','social contribution','trim|numeric');
			$this->form_validation->set_rules('bank','bank','trim');
			$this->form_validation->set_rules('account_no','account number','trim|numeric');
			$this->form_validation->set_rules('start_date','start date','trim');
			$this->form_validation->set_rules('stop_date','stop date','trim');
			
			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				if($this->emp->update($_POST['id'],$_POST))
					$this->utilities->flash('update','employees');
				else
					$this->utilities->flash('error','employees');	
			}
			
		}
		// Generating dropdown menu's
		$this->data['tasks'] = $this->utilities->get_dropdown('id', 'taskname','exp_cd_tasks','- Работна Задача -');
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();	
		$this->data['managers'] = $this->utilities->get_managers();
		$this->data['positions'] = $this->utilities->get_dropdown('id', 'position','exp_cd_positions','- Работно Место -');	
		$this->data['ugroups'] = $this->utilities->get_dropdown('id', 'name','exp_cd_user_groups','- Корисничка Група -',false);

		$this->data['assigned_tasks'] = $this->empt->select($id);

		//Heading
		$this->data['heading'] = 'Корекција на Работник';
	}
	
	public function view($id = false)
	{
		//Heading
		$this->data['heading'] = 'Работник';
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->emp->select_single($id);
		
		if(!$this->data['master']) 
			$this->utilities->flash('void','employees');	
	}
	
	public function delete($id = false)
	{
		if(!$this->emp->get($id)) 
			$this->utilities->flash('void','employees');
			
		if($this->emp->delete($id))
			$this->utilities->flash('delete','employees');
		else
			$this->utilities->flash('error','employees');			
	}

	public function ajxAssignTask()
	{
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('task_fk','tasks','trim|required');			
		if ($this->form_validation->run())
		{
			if($this->empt->insert($_POST))
				echo 1;	
		}
		exit;
	}

	public function ajxDeleteTask()
	{
		if($this->empt->delete($_POST['id']))
			echo 1;	
		exit;
	}

	public function ajxGetTasks()
	{	
		$data = $this->empt->dropdown(json_decode($_GET['emp_id']));	
		header('Content-Type: application/json',true); 
		echo json_encode($data);
		exit;
	}
}