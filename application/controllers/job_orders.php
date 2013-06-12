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

class Job_orders extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('production/joborders_model','jo');
		$this->load->model('procurement/inventory_model','inv');
		$this->load->model('hr/task_model','tsk');
		$this->load->model('hr/employees_model','emp');
    }
    /**
     * Display all job orders
     * @param  integer $query_id   
     * @param  string  $sort_by    
     * @param  string  $sort_order 
     * @param  integer $offset
     */
	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{		
		//Page Heading
		$this->data['heading'] = uif::lng('app.job_jobs');
		
		//Generate dropdown menu data for Filters
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['tasks']     = $this->tsk->dropdown('id','taskname');


		//Columns which can be sorted by
		$this->data['columns'] = [	
			'datedue'           => uif::lng('attr.date'),
			'assigned_to'       => uif::lng('attr.employee'),
			'task_fk'           => uif::lng('attr.task'),
			'assigned_quantity' => uif::lng('attr.quantity'),
			'work_hours'        => uif::lng('attr.work_hours'),
			'shift'             => uif::lng('attr.shift'),
			'dateofentry'       => uif::lng('attr.doe')
		];

		$this->input->load_query($query_id);
		
		$query_array = [
			'task_fk'     => $this->input->get('task_fk'),
			'assigned_to' => $this->input->get('assigned_to'),
			'shift'       => $this->input->get('shift')
		];
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = ['datedue','task_fk','assigned_to','assigned_quantity',
								'work_hours','shift','dateofentry'];
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->jo->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("job_orders/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
				
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}
	
	public function search()
	{
		$query_array = array(
			'task_fk'     => $this->input->post('task_fk'),
			'assigned_to' => $this->input->post('assigned_to'),
			'shift'       => $this->input->post('shift')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("job_orders/index/{$query_id}");
	}
	/**
	 * Create new job order
	 */
	public function insert()
	{	
		//Defining Validation Rules
		$this->form_validation->set_rules('datedue',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('assigned_to',uif::lng('attr.employee'),'trim|required');
		$this->form_validation->set_rules('task_fk',uif::lng('attr.task'),'trim|required');
		$this->form_validation->set_rules('assigned_quantity',uif::lng('attr.quantity'),'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('work_hours',uif::lng('attr.work_hours'),'numeric|greater_than[0]');
		$this->form_validation->set_rules('defect_quantity',uif::lng('attr.spill'),'numeric|greater_than[0]');
		$this->form_validation->set_rules('shift','','trim');
		$this->form_validation->set_rules('ext_doc','','trim');
		$this->form_validation->set_rules('is_completed','','trim');
		$this->form_validation->set_rules('description','','trim');
		
		if($this->form_validation->run())
		{	
			if($job_order_id = $this->jo->insert($_POST))
			{
				/**
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->get($_POST['task_fk']);
				/**
				 * If Job Order is production and has assigned BOM,
				 * calculated and deducts from Inventory accordingly
				 */
				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($job_order_id,$production->id,$_POST['assigned_quantity']);
				}
				
				air::flash('add','job_orders');
			}
			air::flash('error','job_orders');
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->emp->generateDropdown([
			'is_distributer'  => 0,
			'fixed_wage_only' => 0
		]);
				
		//Get last inserted Job Order if present
		if($hasLastJobOrder = $this->jo->get_last())
		{
			$this->data['last'] = $hasLastJobOrder;
		}

		//Heading
		$this->data['heading'] = uif::lng('app.job_new');
	}
	/**
	 * Edit job order
	 * @param  integer $id job order id
	 */
	public function edit($id)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['job_order'] = $this->jo->select_single($id);

		if(!$this->data['job_order']) air::flash('void');
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['job_order']->locked) air::flash('deny','job_orders');

		//Defining Validation Rules
		$this->form_validation->set_rules('datedue',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('assigned_to',uif::lng('attr.employee'),'trim|required');
		$this->form_validation->set_rules('task_fk',uif::lng('attr.task'),'trim|required');
		$this->form_validation->set_rules('assigned_quantity',uif::lng('attr.quantity'),'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('work_hours',uif::lng('attr.work_hours'),'numeric|greater_than[0]');
		$this->form_validation->set_rules('defect_quantity',uif::lng('attr.spill'),'numeric|greater_than[0]');
		$this->form_validation->set_rules('shift','','trim');
		$this->form_validation->set_rules('ext_doc','','trim');
		$this->form_validation->set_rules('is_completed','','trim');
		$this->form_validation->set_rules('description','','trim');
		
		if($this->form_validation->run())
		{
			if($this->jo->update($_POST['id'],$_POST))
			{
				/**
				 * Delete all inventory entries for this Job Order
				 */
				$this->inv->delete_by(['job_order_fk'=>$_POST['id']]);
				/**
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->get($_POST['task_fk']);
				/**
				 * If Job Order is production and has assigned BOM,
				 * calculated and deducts from Inventory accordingly
				 */
				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($_POST['id'],$production->id,$_POST['assigned_quantity']);
				}
				air::flash('update','job_orders');
			}
			air::flash('error','job_orders');
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->emp->generateDropdown([
			'is_distributer'  => 0,
			'fixed_wage_only' => 0
		]);
		
		//Heading
		$this->data['heading'] = uif::lng('app.job_edit');
	}
	/**
	 * Display single job order
	 * @param  integer $id 
	 */
	public function view($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.job_job');

		//Retreives data from MASTER Model //Gets the ID of the selected entry from the URL
		$this->data['master'] = $this->jo->select_single($id);

		if(!$this->data['master']) air::flash('void');

		$this->data['details'] = $this->inv->select_use('job_order_fk',$this->data['master']->id);		
	}

	/**
	 * Completes and prepares Job Orders for Payroll Calculation
	 */
	public function ajxComplete()
	{	
		if($this->jo->update_many(json_decode($_POST['ids']),['is_completed'=>1]))
			echo 1;
		exit;
	}
	/**
	 * Displays report of production
	 */
	public function report()
	{
		$this->data['submitted'] = 0;
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('datefrom',uif::lng('attr.date_from'),'trim|required');
			$this->form_validation->set_rules('dateto',uif::lng('attr.date_to'),'trim|required');
			$this->form_validation->set_rules('shift[]','','trim');
			
			if ($this->form_validation->run())
			{
				//Log the report
				$this->input->log_report($_POST);

				$this->data['results']  = $this->jo->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto']   = $_POST['dateto'];
				$this->data['submitted'] = 1;

				if(empty($this->data['results']))
					$this->data['submitted'] = 0;
			}			
		}
		
		//Dropdown Menus
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['tasks']     = $this->tsk->dropdown('id','taskname');
		
		//Heading
		$this->data['heading'] = uif::lng('app.job_production_report');
	}
	/**
	 * Creates PDF production report
	 * @return PDF file
	 */
	public function report_pdf()
	{
		if(!$_POST) show_404();

		$this->load->helper('dompdf');
		$this->load->helper('file');
		
		$report_data['results']  = $this->jo->report($_POST);
		$report_data['datefrom'] = $_POST['datefrom'];
		$report_data['dateto']   = $_POST['dateto'];
		
		if(strlen($_POST['assigned_to']))
		{
			$report_data['employee'] = $this->emp->get($_POST['assigned_to']);	
		}
		if(strlen($_POST['task_fk']))
		{
			$report_data['task'] = $this->tsk->get($_POST['task_fk']);	
		}
		// if(strlen($_POST['shift']))
		// {
		// 	$report_data['shift'] = $_POST['shift'];	
		// }
		
		if($report_data['results'])
		{
			$html = $this->load->view('job_orders/report_pdf',$report_data, true);
		
			$file_name = random_string();
			
			header("Content-type: application/pdf");
			header("Content-Disposition: attachment; filename='{$file_name}'");
			
			mkpdf($html,$file_name);
		}
		exit;
	}
	/**
	 * Delete job order
	 * @param  integer $id
	 */
	public function delete($id)
	{
		$this->data['job_order'] = $this->jo->get($id);

		if(!$this->data['job_order']) air::flash('void');
		/*
		 * Prevents from deleting locked Job Orders
		 */
		if($this->data['job_order']->locked) air::flash('deny');
			
		if($this->jo->delete($id))
			air::flash('delete','job_orders');
		else
			air::flash('error','job_orders');
	}
	//////////////////////
	// PRIVATE METHODS //
	//////////////////////
	/**
	 * Inserts inventory deduction according to Bill of Materials
	 * for specific job order task
	 * @param  integer $job_order_id
	 * @param  integer $task_id     
	 * @param  decimal $quantity    
	 */
	private function _inventory_use($job_order_id = 0,$task_id = 0,$quantity = 0.0)
	{
		//Loading Models
		$this->load->model('production/bomdetails_model','bomd');
		$this->load->model('production/boms_model','bom');
		
		$taskId = $this->tsk->get($task_id);

		$bomId = $this->bom->get($taskId->bom_fk);

		/*
		 * Retreive all components for specific Bill of Materials (bom_id) 
		 */
		$bom_components = $this->bomd->get_many_by(['bom_fk'=>$bomId->id]);
		/**
		 * MOVE THE FOLLOWING PART TO BOM-DETAILS MODEL
		 */
		foreach ($bom_components as $component)
		{
			$options = [
				'prodname_fk'  => $component->prodname_fk,
				'job_order_fk' => $job_order_id,
				'quantity'     => (($component->quantity * $quantity) * -1),
				'type'         => '0',
				'is_use'       => 1
			];

			unset($_POST);
				
			$this->inv->insert($options);
		}		
	}
}