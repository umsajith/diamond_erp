<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_extra extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Payroll_extra_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Додатоци';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat');
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('payroll_extra/index');
		$config['total_rows'] = count($this->Payroll_extra_model->select($_POST,0));
		$config['per_page'] = 20;
		
		$this->pagination->initialize($config);
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		//Retreive data from Model
		$this->data['results'] = $this->Payroll_extra_model->select($_POST,0,$config['per_page'],$offset);
	}
	
	function expenses()
	{	
		//Heading
		$this->data['heading'] = 'Трошоци';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat');
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('payroll_extra/expenses');
		$config['total_rows'] = count($this->Payroll_extra_model->select($_POST,1));
		$config['per_page'] = 20;
		
		$this->pagination->initialize($config);
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		//Retreive data from Model
		$this->data['results'] = $this->Payroll_extra_model->select($_POST,1, $config['per_page'],$offset);
	}
	
	function social_contributions()
	{	
		//Heading
		$this->data['heading'] = 'Придонеси';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat');
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('payroll_extra/social_contributions');
		$config['total_rows'] = count($this->Payroll_extra_model->select($_POST,3));
		$config['per_page'] = 20;
		
		$this->pagination->initialize($config);
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		//Retreive data from Model
		$this->data['results'] = $this->Payroll_extra_model->select($_POST,3, $config['per_page'],$offset);
	}
    
	function insert_bonus()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_month','month','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->Payroll_extra_model->insert($_POST))
				$this->utilities->flash('add','payroll_extra');
			else
				$this->utilities->flash('error','payroll_extra');		
		}	

		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['categories'] = $this->Payroll_extra_model->dropdown('bonuses');

		//Heading
		$this->data['heading'] = 'Внес на Додатоци';
	}
	
	function insert_expense()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_month','month','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Expenses are expressed in Negative numbers
			$_POST['amount'] = $_POST['amount'] * -1;
			
			if($this->Payroll_extra_model->insert($_POST))
				$this->utilities->flash('add','payroll_extra/expenses');
			else
				$this->utilities->flash('error','payroll_extra/expenses');	
		}	
		
		// Generating dropdown menu's
		$this->data['employees'] =$this->utilities->get_employees();
		$this->data['categories'] = $this->Payroll_extra_model->dropdown('expenses');

		//Heading
		$this->data['heading'] = 'Внес на Трошоци';
	}
	
	function insert_social_contribution()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_month','month','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Pridonesi(Social Contribution) are in Payroll Extras Category ID = 7
			$_POST['payroll_extra_cat_fk'] = 7;	
			
			if($this->Payroll_extra_model->insert($_POST))
				$this->utilities->flash('add','payroll_extra/social_contributions');
			else
				$this->utilities->flash('error','payroll_extra/social_contributions');
		}	
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();

		//Heading
		$this->data['heading'] = 'Внес на Придонеси';
	}
    
	function edit($id)
	{
		//Retreives ONE product from the database
		$this->data['payroll_extra'] = $this->Payroll_extra_model->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['payroll_extra']) redirect('payroll_extra');
		
		if(isset($_POST['submit']))
		{
			//Unsets the POST ubmit, so I doesnt get inserted into the db
			unset($_POST['submit']);
			
			$this->load->library('form_validation');
	
			//Defining Validation Rules
			$this->form_validation->set_rules('employee_fk','employee','trim|required');
			$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
			$this->form_validation->set_rules('amount','amount','trim|required');
			$this->form_validation->set_rules('for_month','month','trim|required');
			$this->form_validation->set_rules('description','description','trim');
				
			if ($this->form_validation->run())
				{
					//Adds what Id to be updated
					$_POST['id'] = $id;
					
					//Retrevies the type of expense/extra from the Payroll Extras Category definition
					// 1 - Expense , 0 - Non expense
					$sign = $this->Payroll_extra_model->check_type($_POST['payroll_extra_cat_fk']);
					
					// If sign is 1, makes the amount an expense;hence, deducts
					if($sign->is_expense == 1 && $_POST['amount'] >= 0)
						$_POST['amount'] = $_POST['amount'] * -1;
					
					if($this->Payroll_extra_model->update($_POST))
						$this->utilities->flash('update',"payroll_extra/view/$id");
					else
						$this->utilities->flash('error',"payroll_extra/view/$id");

				}
		}

		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat','- Категорија -');

		//Heading
		$this->data['heading'] = 'Корекција на Додатоци/Трошоци';
	}
	
	function view($id)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Payroll_extra_model->select_single($id);
		
		//Heading
		//$this->data['heading'] = 'Додаток/Трошок/Придонес';
		$this->data['heading'] = $this->data['master']->name;
	}
    
	function delete($id = false)
	{
		//Takes the ID (third segment) of the URL, delets the corresponding db entry
		if($this->Payroll_extra_model->delete($id))
			$this->utilities->flash('delete',$_SERVER['HTTP_REFERER']);
		else
			$this->utilities->flash('error',$_SERVER['HTTP_REFERER']);
	}	
}