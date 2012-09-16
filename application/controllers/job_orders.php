<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Job_orders extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('production/Joborders_model');
		$this->load->model('procurement/Inventory_model');
    }
	
	function index($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{		
		//Page Title Segment and Heading
		$this->data['heading'] = 'Работни Налози';
		
		//Generate dropdown menu data for Filters
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['tasks'] = $this->utilities->get_dropdown('id','taskname','exp_cd_tasks','- Работна Задача -');
		
		//Limit Per Page
		$limit = 25;
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'datedue'=>'Датум',
			'task_fk'=>'Работна Задача',
			'assigned_to'=>'Работник',
			'assigned_quantity'=>'Кол./Траење',
			'work_hours'=>'Раб.Часови',
			'shift'=>'Смена',
			'final_quantity'=>'Резализ. Кол.',
			'dateofentry'=>'Внес'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'task_fk' => $this->input->get('task_fk'),
			'assigned_to' => $this->input->get('assigned_to'),
			'shift' => $this->input->get('shift'),
			'job_order_status' => $this->input->get('job_order_status')	
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('datedue','task_fk','assigned_to','assigned_quantity',
								'work_hours','shift','final_quantity','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->Joborders_model->select($query_array, $sort_by, $sort_order, $limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("job_orders/index/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $limit;
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
	
	function search()
	{
		$query_array = array(
			'task_fk' => $this->input->post('task_fk'),
			'assigned_to' => $this->input->post('assigned_to'),
			'shift' => $this->input->post('shift'),
			'job_order_status' => $this->input->post('job_order_status')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("job_orders/index/$query_id");
	}

	function insert()
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
			if($this->Joborders_model->insert($_POST))
				$this->utilities->flash('add','job_orders');
			else
				$this->utilities->flash('error','job_orders');
		}
		
		//Generate dropdown menu data
		$this->data['employees'] = $this->utilities->get_employees('variable');
				
		//Retreives the Last Inserted Job Order
		$this->data['last'] = $this->Joborders_model->get_last();

		//Heading
		$this->data['heading'] = 'Внес на Работен Налог';
	}
	
	function edit($id = false)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['job_order'] = $this->Joborders_model->select_single($id);
		if(!$this->data['job_order'])
			$this->utilities->flash('void','job_orders');
				
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['job_order']->locked == 1)
			$this->utilities->flash('deny','job_orders');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('assigned_to','employee','trim|required');
		$this->form_validation->set_rules('job_order_status','job order status','trim|required');
		$this->form_validation->set_rules('shift','shift','trim');
		$this->form_validation->set_rules('work hours','shift','trim|greater_than[0]');
		$this->form_validation->set_rules('prodname_fk','product','trim');
		$this->form_validation->set_rules('task_fk','task','trim|required');
		$this->form_validation->set_rules('assigned_quantity','assigned quantity','greater_than[0]|required');
		$this->form_validation->set_rules('final_quantity','final quantity','greater_than[0]');
		$this->form_validation->set_rules('datedue','due date','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->Joborders_model->update($_POST['id'],$_POST))
			{
				$this->load->model('hr/Task_model');
				
				//Checks if this Task has BOM and is Production
				$production = $this->Task_model->find_bom($_POST['task_fk']);
				
				if($production)
					$this->inventory_use($_POST['id'],$production->bom_fk,$_POST['final_quantity']);

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
	
	/*AJAX - Changes the Job Order status
	function set_status()
	{
		if($this->Joborders_model->update($_POST['id'],$_POST))
		{
			echo json_encode(array('success'=>true,'message'=>'Статусот е успешно ажуриран'));
			exit;
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>'Грешка при ажурирање'));
			exit;
		}
	}
	*/
	//AJAX - Completes Job Orders, and sets Final Qty if not set to Default Qty
	function complete()
	{	
		$this->data['ids'] = json_decode($_POST['ids']);

		foreach($this->data['ids'] as &$id)
		{
			//Checks if there is Final Qty entered already
			$has = $this->Joborders_model->has_fqty($id);
			
			if(!$has->final_quantity && !$has->is_completed)
			{
				//Get the Default Assigned Quatntity
				$qty = $this->Joborders_model->get_qty($id);
				
				$this->load->model('hr/Task_model');
				//Get the Task Id
				$task_id = $this->Joborders_model->get_task($id);

				//Get Bom ID if exists
				$production = $this->Task_model->find_bom($task_id->task_fk);
	
				//Saves BOM_FK and unsets it from POST
				if($production)
				{
					$this->inventory_use($id,$production->bom_fk,$qty->assigned_quantity);
				}
				
				/*
				 * If Job Order is successfully completed,
				 * sets the outcome of the action to TRUE
				 */
				if($this->Joborders_model->complete($id,$qty->assigned_quantity))
					$success = true;
			}	
		}
		if($success)
			echo 1;
			
		exit;	
	}
	
	function view($id = false)
	{
		//Retreives data from MASTER Model //Gets the ID of the selected entry from the URL
		$this->data['master'] = $this->Joborders_model->select_single($id);
		
		if(!$this->data['master'])
			$this->utilities->flash('void','job_orders');

		//Heading
		$this->data['heading'] = 'Работен Налог';
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
				$this->data['results'] = $this->Joborders_model->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto'] = $_POST['dateto'];
				$this->data['submited'] = 1;	
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
			//Runs model functions and retreives results
			
			//Passes the results
			
		}
		
		//Dropdown Menus
		$this->data['employees'] = $this->utilities->get_employees();
		$this->data['tasks'] = $this->utilities->get_dropdown('id','taskname','exp_cd_tasks','- Работна Задача -');
		
		//Heading
		$this->data['heading'] = 'Извештај на Производство';
	}
	
	function delete($id = false)
	{
		$this->data['job_order'] = $this->Joborders_model->select_single($id);
		if(!$this->data['job_order'])
			$this->utilities->flash('void','job_orders');
		/*
		 * Prevents from deleting locked Job Orders
		 */
		if($this->data['job_order']->locked == 1)
			$this->utilities->flash('deny','job_orders');
			
		if($this->Joborders_model->delete($id))
			$this->utilities->flash('delete','job_orders');
		else
			$this->utilities->flash('error','job_orders');
	}	
}