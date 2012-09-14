<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Employees_model');
		$this->load->model('hr/Payroll_model');	

		$this->load->helper('date');
	}
	
	public function index()
	{	
		//Heading
		$this->data['heading'] = 'Преглед на Плати';
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->utilities->get_employees();
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('payroll/index');
		$config['total_rows'] = count($this->Payroll_model->select($_POST));
		$config['per_page'] = 100;
		
		$this->pagination->initialize($config);
		$this->data['pagination'] = $this->pagination->create_links();

		//AA - Present the Products from the database
		$this->data['results'] = $this->Payroll_model->select($_POST, $config['per_page'],$offset);
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
			$_POST['payroll_fk'] = $this->Payroll_model->insert($_POST);
			
			if($_POST['payroll_fk'])
			{
				$this->utilities->flash('add','',false);
				$this->output->set_content_type('application/json');
				echo json_encode(array('redirect'=>site_url('payroll/view/'.$_POST['payroll_fk'])));
			}
			
			exit;	
		}
	}
	
	public function view($id = false)
	{		
		//Loading Models
		$this->load->model('production/Joborders_model');
		$this->load->model('hr/Payroll_extra_model');
		$this->load->model('hr/Task_model');

		//Retreives data from MASTER Model - Payroll info
		$this->data['master'] = $this->Payroll_model->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['master'])
			$this->utilities->flash('void','payroll');

		//Shows the basis for the Wage calculation (Job Orders)
		$this->data['results'] = $this->Joborders_model->select_by_payroll($id);
								
		//Retrevies all Payroll extras where Is_expense = 0
		$this->data['extras_plus'] = $this->Payroll_extra_model->select_by_payroll($id,0);
						
		//Retrevies all Payroll extras where Is_expense = 1
		$this->data['extras_minus'] = $this->Payroll_extra_model->select_by_payroll($id,1);
		
		//Heading
		$this->data['heading'] = 'Плата';
	}
	
	public function payroll_pdf($id = false)
	{
		$this->load->helper('dompdf');
		$this->load->helper('file');
		
		//Loading Models
		$this->load->model('hr/Payroll_extra_model');
			
		//Retreives data from MASTER Model - Payroll info
		$this->data['master'] = $this->Payroll_model->select_single($id);
		
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
				if(!$this->data['fixed_wage_only'] && !$this->data['is_distributer'])
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
	
	public function delete($id = false)
	{
		if(!$this->Payroll_model->select_single($id))
			$this->utilities->flash('void','payroll');
			
		if($this->Payroll_model->delete($id))
			$this->utilities->flash('delete','payroll');
		else
			$this->utilities->flash('error','payroll');
	}
}