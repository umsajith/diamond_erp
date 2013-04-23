<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_extra extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Payroll_extra_model','pre');
	}
    
	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Додатоци';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('variable','- Работник -');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'=>'Работник',
			'payroll_extra_cat_fk'=>'Категорија',
			'amount'=>'Износ',
			'for_date'=>'Датум',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'employee_fk' => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense' => 0,
			'is_contribution' => 0
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('employee','payroll_extra_cat_fk','amount','for_date','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->pre->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("payroll_extra/index/{$query_id}/{$sort_by}/{$sort_order}");
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
			'employee_fk' => $this->input->post('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->post('payroll_extra_cat_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll_extra/index/{$query_id}");
	}
	
	public function expenses($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Трошоци';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('variable','- Работник -');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'=>'Работник',
			'payroll_extra_cat_fk'=>'Категорија',
			'amount'=>'Износ',
			'for_date'=>'Датум',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'employee_fk' => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense' => 1,
			'is_contribution' => 0
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('employee','payroll_extra_cat_fk','amount','for_date','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->pre->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("payroll_extra/expenses/{$query_id}/{$sort_by}/{$sort_order}");
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
	
	public function search_expenses()
	{
		$query_array = array(
			'employee_fk' => $this->input->post('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->post('payroll_extra_cat_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll_extra/expenses/{$query_id}");
	}
	
	public function social_contributions($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Придонеси';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('variable','- Работник -');
		$this->data['categories'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat','- Категорија -');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'=>'Работник',
			'payroll_extra_cat_fk'=>'Категорија',
			'amount'=>'Износ',
			'for_date'=>'Датум',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'employee_fk' => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense' => 0,
			'is_contribution' => 1
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('employee','payroll_extra_cat_fk','amount','for_date','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->pre->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("payroll_extra/social_contributions/{$query_id}/{$sort_by}/{$sort_order}");
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
	
	public function search_social_cont()
	{
		$query_array = array(
			'employee_fk' => $this->input->post('employee_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll_extra/social_contributions/{$query_id}");
	}
    
	public function insert_bonus()
	{
		//Heading
		$this->data['heading'] = 'Внес на Додатоци';
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date','date','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pre->insert($_POST))
				$this->utilities->flash('add','payroll_extra');
			else
				$this->utilities->flash('error','payroll_extra');		
		}	

		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('all','- Работник -');
		$this->data['categories'] = $this->pre->dropdown('bonuses');
	}
	
	public function insert_expense()
	{
		//Heading
		$this->data['heading'] = 'Внес на Трошоци';
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date','month','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Expenses are expressed in Negative numbers
			$_POST['amount'] = $_POST['amount'] * -1;
			
			if($this->pre->insert($_POST))
				$this->utilities->flash('add','payroll_extra/expenses');
			else
				$this->utilities->flash('error','payroll_extra/expenses');	
		}	
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('all','- Работник -');
		$this->data['categories'] = $this->pre->dropdown('expenses');	
	}
	
	public function insert_social_contribution()
	{
		//Heading
		$this->data['heading'] = 'Внес на Придонеси';
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('amount','amount','trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date','month','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Pridonesi(Social Contribution) are in Payroll Extras Category ID = 7
			$_POST['payroll_extra_cat_fk'] = 7;	
			
			if($this->pre->insert($_POST))
				$this->utilities->flash('add','payroll_extra/social_contributions');
			else
				$this->utilities->flash('error','payroll_extra/social_contributions');
		}	
		
		$this->data['employees'] = $this->utilities->get_employees('all','- Работник -');
	}
    
	public function edit($id)
	{
		//Heading
		$this->data['heading'] = 'Корекција на Додатоци/Трошоци';
		
		//Retreives ONE product from the database
		$this->data['payroll_extra'] = $this->pre->select_single($id);
		if(!$this->data['payroll_extra']) show_404();
		
		if($this->data['payroll_extra']->locked == 1)
			$this->utilities->flash('deny','payroll_extra');
		
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('employee_fk','employee','trim|required');
			$this->form_validation->set_rules('payroll_extra_cat_fk','category','trim|required');
			$this->form_validation->set_rules('amount','amount','trim|greater_than[0]|required');
			$this->form_validation->set_rules('for_date','month','trim|required');
			$this->form_validation->set_rules('description','description','trim');
				
			if ($this->form_validation->run())
			{		
				// TODO: Move LOGIN to Model
				//Retrevies the type of expense/extra from the Payroll Extras Category definition
				// 1 - Expense , 0 - Non expense
				$sign = $this->pre->check_type($_POST['payroll_extra_cat_fk']);

				if($sign->is_expense == 1)
				{
					$_POST['amount'] = $_POST['amount'] * -1;
					$redirect = 'expenses';
				}
				else
				{
					$redirect = 'index';
				}	
				
				if($this->pre->update($_POST['id'],$_POST))
					$this->utilities->flash('update',"payroll_extra/{$redirect}");
				else
					$this->utilities->flash('error',"payroll_extra/{$redirect}");

			}
		}

		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees('all','- Работник -');
		$this->data['categories'] = 
		$this->utilities->get_dropdown('id', 'name','exp_cd_payroll_extra_cat','- Категорија -');
	}
	
	public function view($id)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->pre->select_single($id);
		if(!$this->data['master']) show_404();
		
		//Heading
		$this->data['heading'] = $this->data['master']->name;
	}
    
	public function delete($id)
	{
		if($this->pre->delete($id))
			$this->utilities->flash('delete',$_SERVER['HTTP_REFERER']);
		else
			$this->utilities->flash('error',$_SERVER['HTTP_REFERER']);
	}	
}