<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Task_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Работни Задачи';
		
		//Retreive data from Model
		$this->data['results'] = $this->Task_model->select();
	}
    
	function insert()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('taskname','task name','trim|required');
		$this->form_validation->set_rules('rate_per_unit','unit rate','trim|required|numeric');
		$this->form_validation->set_rules('rate_per_unit_bonus','unit rate bonus','trim|numeric');
		$this->form_validation->set_rules('base_unit','base unit','trim|required|numeric');
		$this->form_validation->set_rules('uname_fk','UOM','trim|required|numeric');
		$this->form_validation->set_rules('description','description','trim|xss_clean');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->Task_model->insert($_POST))
				$this->utilities->flash('add','tasks');
			else
				$this->utilities->flash('error','tasks');
		}	

		// Generating dropdown menu's
		$this->data['uoms'] = $this->utilities->get_dropdown('id', 'uname','exp_cd_uom','- EM -');
		$this->data['boms'] = $this->utilities->get_boms();

		//Heading
		$this->data['heading'] = 'Внес на Работна Задача';
	}
    
	function edit($id = false)
	{
		//Retreives ONE product from the database
		$this->data['task'] = $this->Task_model->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['task']) redirect('tasks');
		
		if($_POST)
		{		
			$this->load->library('form_validation');
	
			//Defining Validation Rules
			$this->form_validation->set_rules('taskname','task name','trim|required');
			$this->form_validation->set_rules('rate_per_unit','unit rate','trim|required|numeric');
			$this->form_validation->set_rules('rate_per_unit_bonus','unit rate bonus','trim|numeric');
			$this->form_validation->set_rules('base_unit','base unit','trim|required|numeric');
			$this->form_validation->set_rules('description','description','trim|xss_clean');
				
			if ($this->form_validation->run())
				{
					//Successful validation
					if($this->Task_model->update($id,$_POST))
						$this->utilities->flash('update','tasks');
					else
						$this->utilities->flash('error','tasks');
				}
		}

		// Generating dropdown menu's
		$this->data['uoms'] = $this->utilities->get_dropdown('id', 'uname','exp_cd_uom','- EM -');
		$this->data['boms'] = $this->utilities->get_boms();

		//Heading
		$this->data['heading'] = 'Корекција на Работна Задача';
	}
	
	function view($id = false)
	{	
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Task_model->select_single($id);

		//Heading
		$this->data['heading'] = 'Работна Задача';
	}
    
	function delete($id = false)
	{
		//Takes the ID (third segment) of the URL, delets the corresponding db entry
		if($this->Task_model->delete($id))
		{
			$this->session->set_flashdata('flash','Record successfuly deleted!');
			redirect('tasks');
		}
		else
		{
			$this->session->set_flashdata('flash','Database error');
			redirect('tasks');
		}
	}
	
	function dropdown()
	{
		$this->data = $this->Task_model->dropdown();
		
		header('Content-Type: application/json',true); 
		echo json_encode($this->data);
	}	
}