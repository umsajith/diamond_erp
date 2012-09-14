<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Positions extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/Positions_model');
	}
	
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Работни Места';
		
		//Retreive data from Model
		$this->data['results'] = $this->Positions_model->select();
	}
	
	function insert()
	{
		//Load formvalidation library
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('position','position name','trim|required');
		$this->form_validation->set_rules('dept_fk','department','required');
		$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
		$this->form_validation->set_rules('bonus','bonus','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('requirements','requirements','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->Positions_model->insert($_POST))
				$this->utilities->flash('add','positions');
			else
				$this->utilities->flash('error','positions');	
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = $this->utilities->get_dropdown('id', 'department','exp_cd_departments','- Сектори -');

		//Heading
		$this->data['heading'] = 'Внес на Работно Место';
	}
	
	function edit($id = false)
	{
		//Retreives ONE product from the database
		$this->data['position'] = $this->Positions_model->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['position']) redirect('position');
		
		if($_POST)
		{	
			//Load formvalidation library
			$this->load->library('form_validation');
			
			//Defining Validation Rules
			$this->form_validation->set_rules('position','position name','trim|required');
			$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
			$this->form_validation->set_rules('bonus','bonus','trim|numeric');
			$this->form_validation->set_rules('commision','commision','trim|numeric');
			$this->form_validation->set_rules('requirements','requirements','trim');
			$this->form_validation->set_rules('description','description','trim');
			
			//Check if form has been submited
			if ($this->form_validation->run())
			{
				//Successful validation
				if($this->Positions_model->update($id,$_POST))
					$this->utilities->flash('update','positions');
				else
					$this->utilities->flash('error','positions');
			}
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = $this->utilities->get_dropdown('id', 'department','exp_cd_departments','- Сектори -');

		//Heading
		$this->data['heading'] = 'Корекција на Работно Место';
	}
	
	function view($id = false)
	{
		//Gets the ID of the selected entry from the URL
		$this->data['master'] = $this->Positions_model->select_single($id);
		
		//Page Title
		$this->data['title'] = 'Работно Место';
		
		//Heading
		$this->data['heading'] = 'Работно Место';
	}

	function delete($id = false)
	{
		if($success = $this->Possitions_model->delete($id))
			$this->utilities->flash('delete','positions');
		else
			$this->utilities->flash('error','positions');
	}
}