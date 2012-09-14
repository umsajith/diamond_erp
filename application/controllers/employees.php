<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Employees_model');	
		$this->load->model('hr/Emp_tasks_model');		
	}
	
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Вработени';
		
		// Generating dropdown menu's
		$this->data['possitions'] = $this->utilities->get_dropdown('id', 'position','exp_cd_positions','- Работно Место -');	
		$this->data['ugroups'] = $this->utilities->get_dropdown('id', 'name','exp_cd_user_groups','- Корисничка Група -',false);
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('employees/index');
		$config['total_rows'] = count($this->Employees_model->select($_POST));
		$config['per_page'] = 30;
		
		$this->pagination->initialize($config);
		$this->data['pagination'] = $this->pagination->create_links();

		//AA - Present the Products from the database
		$this->data['results'] = $this->Employees_model->select($_POST, $config['per_page'],$offset);
	}
	
	function insert()
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
			if( $this->Employees_model->insert($_POST))
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
	
	function edit($id = false)
	{
		//Retreives ONE product from the database
		$this->data['employee'] = $this->Employees_model->select_single($id);
		
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
				if($this->Employees_model->update($_POST['id'],$_POST))
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

		$this->data['assigned_tasks'] = $this->Emp_tasks_model->select($id);

		//Heading
		$this->data['heading'] = 'Корекција на Работник';
	}
	
	function view($id = false)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Employees_model->select_single($id);
		
		if(!$this->data['master']) 
			$this->utilities->flash('void','employees');

		//Heading
		$this->data['heading'] = 'Работник';
	}
	
	function delete($id = false)
	{
		$this->data['master'] = $this->Employees_model->select_single($id);
		
		if(!$this->data['master']) 
			$this->utilities->flash('void','employees');
			
		if($this->Employees_model->delete($id))
			$this->utilities->flash('delete','employees');
		else
			$this->utilities->flash('error','employees');			
	}
}