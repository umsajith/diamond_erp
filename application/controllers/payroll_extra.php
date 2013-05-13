<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *   Diamond ERP - Complete ERP for SMBs
 *   
 *   @author Marko Aleksic <psybaron@gmail.com>
 *   @copyright Copyright (C) 2013  Marko Aleksic
 *   @link https://github.com/psybaron/diamond_erp
 *   @license http://opensource.org/licenses/GPL-3.0
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

class Payroll_extra extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/payroll_extra_model','pre');
		$this->load->model('hr/payrollcategory_model','prc');
		$this->load->model('hr/employees_model','emp');
	}
    
	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.paye_exts');
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['categories'] = $this->prc->dropdown('id', 'name');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'employee'             => uif::lng('attr.employee'),
			'payroll_extra_cat_fk' => uif::lng('attr.category'),
			'amount'               => uif::lng('attr.amount'),
			'for_date'             => uif::lng('attr.date'),
			'dateofentry'          => uif::lng('attr.doe')
		);

		$this->input->load_query($query_id);
		
		$query_array = [
			'employee_fk'          => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense'           => 0,
			'is_contribution'      => 0
		];
		
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
		$this->data['pagination'] = 
		paginate("payroll_extra/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links();
		
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}
	
	public function search()
	{
		$query_array = array(
			'employee_fk'          => $this->input->post('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->post('payroll_extra_cat_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll_extra/index/{$query_id}");
	}
	
	public function expenses($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.paye_exps');
		
		// Generating dropdown menu's
		$this->data['employees']  = $this->emp->generateDropdown();
		$this->data['categories'] = $this->prc->dropdown('id', 'name');
		
		//Columns which can be sorted by
		$this->data['columns'] = [	
			'employee'             => uif::lng('attr.employee'),
			'payroll_extra_cat_fk' => uif::lng('attr.category'),
			'amount'               => uif::lng('attr.amount'),
			'for_date'             => uif::lng('attr.date'),
			'dateofentry'          => uif::lng('attr.doe')
		];

		$this->input->load_query($query_id);
		
		$query_array = [
			'employee_fk'          => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense'           => 1,
			'is_contribution'      => 0
		];
		
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
		$this->data['pagination'] = 
		paginate("payroll_extra/expenses/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links();
		
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}
	
	public function search_expenses()
	{
		$query_array = array(
			'employee_fk'          => $this->input->post('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->post('payroll_extra_cat_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("payroll_extra/expenses/{$query_id}");
	}
	
	public function social_contributions($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.paye_conts');
		
		// Generating dropdown menu's
		$this->data['employees'] = $this->emp->generateDropdown();
		
		//Columns which can be sorted by
		$this->data['columns'] = [	
			'employee'             => uif::lng('attr.employee'),
			'payroll_extra_cat_fk' => uif::lng('attr.category'),
			'amount'               => uif::lng('attr.amount'),
			'for_date'             => uif::lng('attr.date'),
			'dateofentry'          => uif::lng('attr.doe')
		];

		$this->input->load_query($query_id);
		
		$query_array = [
			'employee_fk'          => $this->input->get('employee_fk'),
			'payroll_extra_cat_fk' => $this->input->get('payroll_extra_cat_fk'),
			'is_expense'           => 0,
			'is_contribution'      => 1
		];
		
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
		$this->data['pagination'] = 
		paginate("payroll_extra/social_contributions/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links();
		
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
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
		$this->data['heading'] = uif::lng('app.paye_ext_new');

		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk',uif::lng('attr.employee'),'trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk',uif::lng('attr.category'),'trim|required');
		$this->form_validation->set_rules('amount',uif::lng('attr.amount'),'trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('description','','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pre->insert($_POST))
				air::flash('add','payroll_extra');
			else
				air::flash('error','payroll_extra');		
		}	

		// Generating dropdown menu's
		$this->data['employees']  = $this->emp->generateDropdown();
		$this->data['categories'] = $this->pre->dropdown('bonuses');
	}
	
	public function insert_expense()
	{
		//Heading
		$this->data['heading'] = uif::lng('app.paye_exp_new');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk',uif::lng('attr.employee'),'trim|required');
		$this->form_validation->set_rules('payroll_extra_cat_fk',uif::lng('attr.category'),'trim|required');
		$this->form_validation->set_rules('amount',uif::lng('attr.amount'),'trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('description','','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Expenses are expressed in Negative numbers
			$_POST['amount'] = $_POST['amount'] * -1;
			
			if($this->pre->insert($_POST))
				air::flash('add','payroll_extra/expenses');
			else
				air::flash('error','payroll_extra/expenses');	
		}	
		
		// Generating dropdown menu's
		$this->data['employees']  = $this->emp->generateDropdown();
		$this->data['categories'] = $this->pre->dropdown('expenses');	
	}
	
	public function insert_social_contribution()
	{
		//Heading
		$this->data['heading'] = uif::lng('app.paye_cont_new');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('employee_fk',uif::lng('attr.employee'),'trim|required');
		$this->form_validation->set_rules('amount',uif::lng('attr.amount'),'trim|required|greater_than[0]');
		$this->form_validation->set_rules('for_date',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('description','','trim');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Pridonesi(Social Contribution) are in Payroll Extras Category ID = 7
			$_POST['payroll_extra_cat_fk'] = 7;	
			
			if($this->pre->insert($_POST))
				air::flash('add','payroll_extra/social_contributions');
			else
				air::flash('error','payroll_extra/social_contributions');
		}	
		
		$this->data['employees'] = $this->emp->generateDropdown();
	}
    
	public function edit($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.paye_edit');
		
		//Retreives ONE product from the database
		$this->data['payroll_extra'] = $this->pre->select_single($id);
		if(!$this->data['payroll_extra']) show_404();
		
		if($this->data['payroll_extra']->locked == 1)
			air::flash('deny','payroll_extra');
		
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('employee_fk',uif::lng('attr.employee'),'trim|required');
			$this->form_validation->set_rules('payroll_extra_cat_fk',uif::lng('attr.category'),'trim|required');
			$this->form_validation->set_rules('amount',uif::lng('attr.amount'),'trim|required|greater_than[0]');
			$this->form_validation->set_rules('for_date',uif::lng('attr.date'),'trim|required');
			$this->form_validation->set_rules('description','','trim');
				
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
					air::flash('update',"payroll_extra/{$redirect}");
				else
					air::flash('error',"payroll_extra/{$redirect}");

			}
		}

		// Generating dropdown menu's
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['categories'] = $this->prc->dropdown('id', 'name');
	}
	
	public function view($id)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->pre->select_single($id);

		if(!$this->data['master']) air::flash('void');
		
		//Heading
		$this->data['heading'] = $this->data['master']->name;
	}
    
	public function delete($id)
	{
		if($this->pre->delete($id))
			air::flash('delete',$_SERVER['HTTP_REFERER']);
		else
			air::flash('error',$_SERVER['HTTP_REFERER']);
	}	
}