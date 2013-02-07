<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Employees_model');
		$this->load->model('hr/payroll_model','pr');

		$this->load->helper('date');
	}
	
	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Преглед на Плати';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'=>'Работник',
			'for_month'=>'Месец',
			'date_from'=>'Од',
			'date_to'=>'До',
			'acc_wage'=>'Учинок',
			'social_cont'=>'Придонеси',
			'comp_mobile_sub'=>'Тел.Суб',
			'bonuses'=>'Бонуси',
			'gross_wage'=>'Бруто',
			'fixed_wage'=>'Фиксна Плата',
			'expenses'=>'Трошоци',
			'paid_wage'=>'Доплата',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'employee_fk' => $this->input->get('employee_fk'),
			'for_month' => $this->input->get('for_month')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('employee','for_month','date_from','date_to',
								'acc_wage','social_cont','comp_mobile_sub','bonuses',
								'gross_wage','fixed_wage','expenses','paid_wage','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->pr->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("payroll/index/$query_id/$sort_by/$sort_order");
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
			'for_month' => $this->input->post('for_month')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll/index/$query_id");
	}
	
	public function insert()
	{
		if(!$_POST)
			redirect('payroll');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk','employee','trim|required');
		$this->form_validation->set_rules('date_from','date from','trim|required');
		$this->form_validation->set_rules('date_to','date to','trim|required');
		$this->form_validation->set_rules('for_month','for month','trim|required');
		
		$this->form_validation->set_rules('acc_wage','accumulated wage','trim|required|numeric');
		$this->form_validation->set_rules('social_cont','social contribution','trim|required|numeric');
		$this->form_validation->set_rules('comp_mobile_sub','company mobile subsidy','trim|required|numeric');
		$this->form_validation->set_rules('bonuses','bonuses','trim|required|numeric');
		$this->form_validation->set_rules('gross_wage','gross_wage','trim|required|numeric');
		
		$this->form_validation->set_rules('fixed_wage','fixed wage','trim|required|numeric');
		$this->form_validation->set_rules('expenses','expenses','trim|required|numeric');
		$this->form_validation->set_rules('paid_wage','paid wage','trim|required|numeric');
		
		$this->form_validation->set_rules('fixed_wage_only','fixed wage only','trim|required|numeric');
		$this->form_validation->set_rules('is_distributer','distributer','trim|required|numeric');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			/*
			 * Creates a payroll entry and
			 * takes the ID inserted stored in the
			 * POST var. for further use
			 */
			$_POST['payroll_fk'] = $this->pr->insert($_POST);
			
			if($_POST['payroll_fk'])
			{
				$this->utilities->flash('add','',false);
				$this->output->set_content_type('application/json');
				echo json_encode(array('redirect'=>site_url('payroll/view/'.$_POST['payroll_fk'])));
			}
			
			exit;	
		}
	}
	
	public function view($id)
	{		
		//Loading Models
		$this->load->model('production/joborders_model','jo');
		$this->load->model('hr/Payroll_extra_model');
		$this->load->model('hr/Task_model');
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/cod_model','cod');

		//Retreives data from MASTER Model - Payroll info
		$this->data['master'] = $this->pr->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['master'])
			$this->utilities->flash('void','payroll');

		if($this->data['master']->is_distributer == 1)
		{
			/*
			 * Retreives all the customer Orders which
			 * this distributor has distributed them,by
			 * payroll id
			 */
			$ids = $this->co->get_by_payroll($this->data['master']->id);
			if($ids)
				$this->data['distribution'] = $this->cod->total_distributed($ids);
		}

		//Shows the basis for the Wage calculation (Job Orders)
		$this->data['results'] = $this->jo->select_by_payroll($id);
								
		//Retrevies all Payroll extras where Is_expense = 0
		$this->data['extras_plus'] = $this->Payroll_extra_model->select_by_payroll($id,0);
						
		//Retrevies all Payroll extras where Is_expense = 1
		$this->data['extras_minus'] = $this->Payroll_extra_model->select_by_payroll($id,1);
		
		//Heading
		$this->data['heading'] = 'Плата';
	}
	
	public function payroll_pdf($id)
	{
		$this->load->helper('dompdf');
		$this->load->helper('file');
		
		//Loading Models
		$this->load->model('hr/Payroll_extra_model');
			
		//Retreives data from MASTER Model - Payroll info
		$this->data['master'] = $this->pr->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['master'])
			$this->utilities->flash('void','payroll');
		
		//Display
		$html = $this->load->view('payroll/payroll_pdf',$this->data, true);
		
		$file_name = $this->data['master']->lname.'_'.$this->data['master']->fname.$this->data['master']->for_month.$this->data['master']->year.$id;
		
		pdf_create($html,$file_name);	
	}
	
	public function calculate()
	{	
		/*
		 * By default, function opens
		 * wage calculation before submission,
		 * hence, its not submited
		 */
		$this->data['submited'] = 0;
		
		/*
		 * Calculates the payroll based on
		 * supplied POST variables:
		 * -Employee ID
		 * -Date From - Date To
		 * -Month
		 */
		if($_POST)
		{
			//Loading Models
			$this->load->model('production/Joborders_model');
			$this->load->model('orders/Co_model');
			$this->load->model('orders/Cod_model');
			$this->load->model('hr/Payroll_extra_model');
			$this->load->model('hr/Task_model');
			$this->load->model('hr/Employees_model');
			
			/*
			 *  Checks if there is any Payroll for this specific
			 *  employee, in specified dates
			 */
		
			//Defining Validation Rules
			$this->form_validation->set_rules('employee','first name','trim|required');
			$this->form_validation->set_rules('datefrom','date from','trim|required');
			$this->form_validation->set_rules('dateto','date to','trim|required');
			$this->form_validation->set_rules('for_month','for month','trim|required');
			
			
			if($this->form_validation->run())
			{
				/*
				 * Saves the passed calculation variables
				 * (employee,dates,month) back to view for use
				 */			
				$this->data['datefrom'] = $_POST['datefrom'];	
				$this->data['dateto'] = $_POST['dateto'];
				$this->data['for_month'] = $_POST['for_month'];	
				$this->data['submited'] = 1;
				
				$this->data['employee_master'] = $this->Employees_model->select_single($_POST['employee']);

				if(!$this->data['employee_master'])
					$this->utilities->flash('void','payroll/calculate');

				$this->data['employee'] = $this->data['employee_master']->id;
				
				/*
				 * Looks for Job Orders assigned to
				 * this employee, if employee is not marked
				 * as Fixed Wage Only 
				 */
				$this->data['fixed_wage_only'] = $this->data['employee_master']->fixed_wage_only;
				$this->data['acc_wage'] = 0;
				$this->data['is_distributer'] = $this->data['employee_master']->is_distributer;
				
				/*
				 * Calcuation for Job Orders Employees
				 */
				if(!$this->data['fixed_wage_only'] AND !$this->data['is_distributer'])
				{
					$this->data['job_orders'] = $this->Joborders_model->payroll(array(
								'assigned_to' => $this->data['employee'],
								'datefrom' => $this->data['datefrom'],
								'dateto' => $this->data['dateto'],
								'for_month' => $this->data['for_month']
								));
					/*
					 * Calculates total accumulated wage by
					 * going through Job Orders assigned,
					 * and calculating with standard rate per unit.
					 */
					foreach ($this->data['job_orders'] as $row)
						$this->data['acc_wage'] += round($row->rate_per_unit * $row->final_quantity,2);
				}
				
				/*
				 * Calcuation for Distributors
				 */
				if($this->data['is_distributer'])
				{
					/*
					 * Retreives all the customer Orders which
					 * this distributor has distributed them, and get
					 * the total distribution for that period
					 */
					$ids = $this->Co_model->get_by_distributor($this->data['employee'],$this->data['datefrom'],$this->data['dateto']);
					$this->data['distribution'] = $this->Cod_model->total_distributed($ids);
					/*
					 * Calculates total accumulated wage by
					 * going through customer orders Orderder assigned,
					 * and calculating the total qty by commision per unit.
					 */
					foreach ($this->data['distribution'] as $row)
						$this->data['acc_wage'] += round($row->quantity * $row->commision,2);	
				}
				
				/*
				 * If employee uses company mobile contract,
				 * and has subsidy by the company, it is retrevied
				 * here.
				 */
				$this->data['comp_mobile_sub'] = $this->data['employee_master']->comp_mobile_sub;
				
				/*
				 * If employee has fixed wage
				 * it is retrevied here.
				 */
				$this->data['fixed_wage'] = $this->data['employee_master']->fixed_wage;

				/*
				 * Finds the social contribution for employee
				 * from the payroll extras table, by looking
				 * at the months
				 */
				//$this->data['social_cont'] = $this->data['employee_master']->social_cont;
				$this->data['social_cont'] = $this->Payroll_extra_model->get_soc_contr($this->data['employee'],$this->data['for_month']);
				
				/*
				 * Retrevies all payroll bonuses
				 */
				$this->data['extras_plus'] = $this->Payroll_extra_model->calc_extras(array(
								'employee_fk' => $this->data['employee'],
								'for_month' => $this->data['for_month'],	
								),0);
					$this->data['bonuses'] = 0;
					foreach ($this->data['extras_plus'] as $item)
						$this->data['bonuses'] += $item->amount;	

				/*
				 * GROSS WAGE CALCULATIONS
				 */
				$this->data['gross_wage'] = 0;
				/*
				 * To calculate gross wage, adds:
				 * 1.If employee on fixed wage only, add fixed wage,
				 *   else adds accumulated wage (based on Job Orders)
				 * 2.Social Contribution for that month
				 * 3.Company Mobile subsidy
				 * 4.Other Bonuses
				 */
				$this->data['gross_wage'] += $this->data['acc_wage'];
				
				/*
				 * If employee has fixed wage only, add fixed wage into
				 * gross calculation
				 */
				if($this->data['fixed_wage_only'] == 1)
					$this->data['gross_wage'] += $this->data['fixed_wage'];
					
				$this->data['gross_wage'] += $this->data['social_cont'];
				$this->data['gross_wage'] += $this->data['comp_mobile_sub'];
				$this->data['gross_wage'] += $this->data['bonuses'];
				
				/*
				 * Retrevies all payroll expenses
				 */
				$this->data['extras_minus'] = $this->Payroll_extra_model->calc_extras(array(
								'employee_fk' => $this->data['employee'],
								'for_month' => $this->data['for_month'],	
								),1);
					$this->data['expenses'] = 0;
					foreach ($this->data['extras_minus'] as $item)
						$this->data['expenses'] -= $item->amount;
					/*
					 * Expenses are expressed in negative numbers
					 */
					$this->data['expenses'] = $this->data['expenses'] * -1;
						
				/*
				 * GROSS EXPENSES CALCULATIONS
				 */
				$this->data['gross_exp'] = 0;
				$this->data['gross_exp'] -= $this->data['fixed_wage'];
				$this->data['gross_exp'] += $this->data['expenses'];
				$this->data['gross_exp'] -= $this->data['social_cont'];
						
				/*
				 * NET WAGE CALCUATIONS
				 */
				$this->data['paid_wage'] = 0;
				$this->data['paid_wage'] += $this->data['gross_wage'];
				$this->data['paid_wage'] += $this->data['gross_exp'];		
			}		
		}

		//Heading
		$this->data['heading'] = 'Калкулација на Плата';
		
		$this->data['employees'] = $this->utilities->get_employees();
	}

	public function report()
	{
		$this->data['submited'] = 0;
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('date_from','date from','trim|required');
			$this->form_validation->set_rules('date_to','date to','trim|required');
			
			if ($this->form_validation->run())
			{
				//Log the report
				$this->input->log_report($_POST);
				
				$this->data['results'] = $this->pr->report($_POST);
				$this->data['date_from'] = $_POST['date_from'];
				$this->data['date_to'] = $_POST['date_to'];
				$this->data['submited'] = 1;

				if(empty($this->data['results']))
					$this->data['submited'] = 0;
			}			
		}
		
		//Dropdown Menus
		$this->data['employees'] = $this->utilities->get_employees();
		
		//Heading
		$this->data['heading'] = 'Рипорт на Плати';
	}

	public function report_pdf()
	{	
		if($_POST)
		{
			$this->load->helper('dompdf');
			$this->load->helper('file');
			
			$report_data['results'] = $this->pr->report($_POST);
			$report_data['date_from'] = $_POST['date_from'];
			$report_data['date_to'] = $_POST['date_to'];
			
			$this->load->model('hr/task_model','tsk');
			$this->load->model('hr/employees_model','emp');

			if(strlen($_POST['employee_fk']))
			{
				$report_data['employee'] = $this->emp->select_single($_POST['employee_fk']);	
			}
			
			if($report_data['results'])
			{
				$html = $this->load->view('payroll/report_pdf',$report_data, true);
			
				$file_name = random_string();
				
				header("Content-type: application/pdf");
				header("Content-Disposition: attachment; filename='{$file_name}'");
				
				pdf_create($html,$file_name);
				exit;
			}
			else
				exit;
		}
	}

	/**
	 * Deletes payroll entry.
	 * @param  integer $id 
	 * @return redirects with success or error message.
	 */
	public function delete($id)
	{
		if(!$this->pr->select_single($id))
			$this->utilities->flash('void','payroll');
			
		if($this->pr->delete($id))
			$this->utilities->flash('delete','payroll');
		else
			$this->utilities->flash('error','payroll');
	}
}