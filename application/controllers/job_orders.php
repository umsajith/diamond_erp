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
    }
	
	public function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{		
		//Page Title Segment and Heading
		$this->data['heading'] = 'Работни Налози';
		
		//Generate dropdown menu data for Filters
		$this->data['employees'] = $this->utilities->get_employees('variable');
		$this->data['tasks'] = $this->utilities->get_dropdown('id','taskname','exp_cd_tasks','- Работна Задача -');


		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'datedue'=>'Датум',
			'assigned_to'=>'Работник',
			'task_fk'=>'Работна Задача',
			'assigned_quantity'=>'Количина/Траење',
			'work_hours'=>'Раб.Часови',
			'shift'=>'Смена',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'task_fk' => $this->input->get('task_fk'),
			'assigned_to' => $this->input->get('assigned_to'),
			'shift' => $this->input->get('shift')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('datedue','task_fk','assigned_to','assigned_quantity',
								'work_hours','shift','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->jo->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("job_orders/index/$query_id/$sort_by/$sort_order");
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
			'task_fk' => $this->input->post('task_fk'),
			'assigned_to' => $this->input->post('assigned_to'),
			'shift' => $this->input->post('shift')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("job_orders/index/$query_id");
	}

	public function insert()
	{		
		//Defining Validation Rules
		$this->form_validation->set_rules('assigned_to','employee','trim|required');
		$this->form_validation->set_rules('assigned_by','assigned by','trim');
		$this->form_validation->set_rules('work hours','shift','trim|greater_than[0]');
		$this->form_validation->set_rules('shift','shift','trim');
		$this->form_validation->set_rules('prodname_fk','product','trim');
		$this->form_validation->set_rules('task_fk','task','trim|required');
		$this->form_validation->set_rules('assigned_quantity','assigned quantity','greater_than[0]|required');
		$this->form_validation->set_rules('datedue','due date','trim|required');
		$this->form_validation->set_rules('description','description','trim');

		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Successful validation insets into the DB
			if($job_order_id = $this->jo->insert($_POST))
			{
				/*
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->select_single($_POST['task_fk']);
				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($job_order_id,$production->id,$_POST['assigned_quantity']);
				}
				
				$this->utilities->flash('add','job_orders');
			}
			else
				$this->utilities->flash('error','job_orders');
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->utilities->get_employees('variable');
				
		//Retreives the Last Inserted Job Order
		$this->data['last'] = $this->jo->get_last();

		//Heading
		$this->data['heading'] = 'Внес на Работен Налог';
	}
	
	public function edit($id)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['job_order'] = $this->jo->select_single($id);
		if(!$this->data['job_order'])
			$this->utilities->flash('void','job_orders');
				
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['job_order']->locked == 1)
			$this->utilities->flash('deny','job_orders');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('assigned_to','employee','trim|required');
		$this->form_validation->set_rules('shift','shift','trim');
		$this->form_validation->set_rules('work hours','shift','trim|greater_than[0]');
		$this->form_validation->set_rules('task_fk','task','trim|required');
		$this->form_validation->set_rules('assigned_quantity','assigned quantity','greater_than[0]|required');
		$this->form_validation->set_rules('datedue','due date','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->jo->update($_POST['id'],$_POST))
			{
				$found = $this->inv->get_many_by(array('job_order_fk'=>$_POST['id']));
				if(!empty($found))
				{
					$ids = array();
					foreach ($found as $row) 
					{
						array_push($ids,$row->id);
					}
					//print_r($ids); die;
					$this->inv->delete_many($ids);
				}
				
				/*
				 * Check if this task is production and has
				 * assigned BOM
				 */
				$production = $this->tsk->get($_POST['task_fk']);
				if($production->is_production AND !is_null($production->bom_fk))
				{
					$this->_inventory_use($_POST['id'],$production->id,$_POST['assigned_quantity']);
				}

				$this->utilities->flash('update','job_orders');
			}
			else
				$this->utilities->flash('error','job_orders');
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->utilities->get_employees('variable');

		//Heading
		$this->data['heading'] = 'Корекција на Работен Налог';
	}

	//AJAX - Completes Job Orders, and sets Final Qty if not set to Default Qty
	public function ajxComplete()
	{	
		$this->data['ids'] = json_decode($_POST['ids']);

		foreach($this->data['ids'] as &$id)
		{
			if(!$this->jo->complete($id))
				$error = true;
			else
				$error = false;
		}	

		if(!$error)
			echo 1;			
		
		exit;	
	}
	
	public function view($id = false)
	{
		//Heading
		$this->data['heading'] = 'Работен Налог';

		//Retreives data from MASTER Model //Gets the ID of the selected entry from the URL
		$this->data['master'] = $this->jo->select_single($id);
		$this->data['details'] = $this->inv->select_use('job_order_fk',$this->data['master']->id);
		
		if(!$this->data['master'])
			$this->utilities->flash('void','job_orders');

		
	}

	public function report()
	{
		$this->data['submited'] = 0;
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('datefrom','date from','trim|required');
			$this->form_validation->set_rules('dateto','date to','trim|required');
			
			if ($this->form_validation->run())
			{
				$this->data['results'] = $this->jo->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto'] = $_POST['dateto'];
				$this->data['submited'] = 1;

				if(empty($this->data['results']))
					$this->data['submited'] = 0;
			}		
			
			/*
			$data = '';
			$categories = '';

			
			foreach ($results as $row)
			{
				$data .= "'$row->sum',";
				$categories .= "'$row->taskname',";
			}

			
			$this->data['json_data'] = substr($data,0,-1);
			$this->data['categories'] = substr($categories,0,-1);
			*/
			/*
			$gdata = array();
			foreach($results as $one)
			{
				array_push($gdata, $one->avg);
			}
			
			$graph = $this->jpgraph->linechart($gdata, 'This is a Line Chart');
			
			$graph_temp_directory = 'temp';  // in the webroot (add directory to .htaccess exclude)
	        $graph_file_name = rand(1,3).time().'.jpg';    
	        
	        $graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
	                
	        $graph->Stroke(base_url().$graph_file_location);  // create the graph and write to file
	        
	        $this->data['graph'] = $graph_file_location;
			*/
			//Runs model public functions and retreives results
			
			//Passes the results
			
		}
		
		//Dropdown Menus
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['tasks'] = $this->utilities->get_dropdown('id','taskname','exp_cd_tasks','- Работна Задача -');
		
		//Heading
		$this->data['heading'] = 'Извештај на Производство';
	}

	public function report_pdf()
	{	
		if($_POST)
		{
			$this->load->helper('dompdf');
			$this->load->helper('file');
			
			$report_data['results'] = $this->jo->report($_POST);
			$report_data['datefrom'] = $_POST['datefrom'];
			$report_data['dateto'] = $_POST['dateto'];
			
			$this->load->model('hr/task_model','tsk');
			$this->load->model('hr/employees_model','emp');

			if(strlen($_POST['assigned_to']))
			{
				$report_data['employee'] = $this->emp->select_single($_POST['assigned_to']);	
			}
			if(strlen($_POST['task_fk']))
			{
				$report_data['task'] = $this->tsk->select_single($_POST['task_fk']);	
			}
			if(strlen($_POST['shift']))
			{
				$report_data['shift'] = $_POST['shift'];	
			}
			
			if($report_data['results'])
			{
				$html = $this->load->view('job_orders/report_pdf',$report_data, true);
			
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
	
	public function delete($id)
	{
		$this->data['job_order'] = $this->jo->get($id);
		if(!$this->data['job_order'])
			$this->utilities->flash('void','job_orders');
		/*
		 * Prevents from deleting locked Job Orders
		 */
		if($this->data['job_order']->locked == 1)
			$this->utilities->flash('deny','job_orders');
			
		if($this->jo->delete($id))
			$this->utilities->flash('delete','job_orders');
		else
			$this->utilities->flash('error','job_orders');
	}

	/**
	 *  MOVE WHOLE FUNCTION TO JOB_ORDERS MODEL
	 */
	private function _inventory_use($job_order_id,$task_id,$quantity)
	{
		//Loading Models
		$this->load->model('hr/task_model','tsk');
		$this->load->model('production/bomdetails_model','bomd');
		$this->load->model('production/boms_model','bom');
		
		if(!$bom_id = $this->tsk->find_bom($task_id))
			return false;

		$results = $this->inv->has_deducation($job_order_id);
		
		if($results)
		{
			foreach ($results as $row )
				$this->inv->delete($row['id']);
		}

		/*
		 * Retreive all components for specific Bill of Materials (bom_id) 
		 */
		$bom_components = $this->bomd->select_by_bom_id($bom_id);
							
		foreach ($bom_components as $component)
		{
			$options = array(
				'prodname_fk'=> $component->prodname_fk,
				'job_order_fk'=> $job_order_id,
				'quantity' => (($component->quantity * $quantity) * -1),
				'received_by' => $this->session->userdata('userid'),
				'type' => '0',
				'is_use' => 1
			);

			unset($_POST);
				
			$this->inv->insert($options);
		}		
	}
}