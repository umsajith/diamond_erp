<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{		
		//Page Title Segment and Heading
		$this->data['heading'] = 'Работни Налози';
		
		//Generate dropdown menu data for Filters
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['tasks']     = $this->tsk->dropdown('id','taskname');


		//Columns which can be sorted by
		$this->data['columns'] = [	
			'datedue'           =>'Датум',
			'assigned_to'       =>'Работник',
			'task_fk'           =>'Работна Задача',
			'assigned_quantity' =>'Кол./Траење',
			'work_hours'        =>'Раб.Часови',
			'shift'             =>'Смена',
			'dateofentry'       =>'Внес'
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

	public function insert()
	{		
		if($_POST)
		{
			if($job_order_id = $this->jo->insert($_POST))
			{
				/*
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->get($_POST['task_fk']);
				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($job_order_id,$production->id,$_POST['assigned_quantity']);
				}
				
				air::flash('add','job_orders');
			}
			/**
			 * @todo Check if insert failed AND there are no validation errors,
			 * then trwo 500 intenral error message with redirect
			 */
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->emp->generateDropdown([
			'is_distributer'  => 0,
			'fixed_wage_only' => 0
		]);
				
		//Retreives the Last Inserted Job Order
		$this->data['last'] = $this->jo->get_last();

		//Heading
		$this->data['heading'] = 'Нов Работен Налог';
	}
	
	public function edit($id)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['job_order'] = $this->jo->select_single($id);

		if(!$this->data['job_order']) show_404();
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['job_order']->locked) air::flash('deny','job_orders');
		
		if($_POST)
		{
			if($this->jo->update($_POST['id'],$_POST))
			{
				// $found = $this->inv->get_many_by(['job_order_fk'=>$_POST['id']]);
				// if(!empty($found))
				// {
				// 	$ids = [];
				// 	foreach ($found as $row) 
				// 	{
				// 		array_push($ids,$row->id);
				// 	}
				// 	$this->inv->delete_many($ids);
				// }
				
				/*
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->get($_POST['task_fk']);

				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($_POST['id'],$production->id,$_POST['assigned_quantity']);
				}

				air::flash('update','job_orders');
			}
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->emp->generateDropdown([
			'is_distributer'  => 0,
			'fixed_wage_only' => 0
		]);
		
		//Heading
		$this->data['heading'] = 'Корекција на Работен Налог';
	}

	/**
	 * Completes and prepares Job Orders for Payroll Calculation
	 */
	public function ajxComplete()
	{	
		if($this->jo->update_many(json_decode($_POST['ids']),['is_completed'=>1],true))
			echo 1;
		exit;	
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Работен Налог';

		//Retreives data from MASTER Model //Gets the ID of the selected entry from the URL
		$this->data['master'] = $this->jo->select_single($id);

		if(!$this->data['master']) air::flash('void','job_orders');

		$this->data['details'] = $this->inv->select_use('job_order_fk',$this->data['master']->id);		
	}

	public function report()
	{
		$this->data['submited'] = 0;
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('datefrom','date from','trim|required');
			$this->form_validation->set_rules('dateto','date to','trim|required');
			$this->form_validation->set_rules('shift[]','shift','trim');
			
			if ($this->form_validation->run())
			{
				//Log the report
				$this->input->log_report($_POST);

				$this->data['results']  = $this->jo->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto']   = $_POST['dateto'];
				$this->data['submited'] = 1;

				if(empty($this->data['results']))
					$this->data['submited'] = 0;
			}			
		}
		
		//Dropdown Menus
		$this->data['employees'] = $this->emp->generateDropdown();
		$this->data['tasks']     = $this->tsk->dropdown('id','taskname');
		
		//Heading
		$this->data['heading'] = 'Рипорт на Производство';
	}
	
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
	
	public function delete($id)
	{
		$this->data['job_order'] = $this->jo->get($id);

		if(!$this->data['job_order']) air::flash('void','job_orders');
		/*
		 * Prevents from deleting locked Job Orders
		 */
		if($this->data['job_order']->locked) air::flash('deny','job_orders');
			
		if($this->jo->delete($id))
			air::flash('delete','job_orders');
		else
			air::flash('error','job_orders');
	}

	private function _inventory_use($job_order_id,$task_id,$quantity)
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