<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Modules';
		
		//Retreive data from Model
		$this->data['results'] = $this->Modules_model->select();	
	}
    
	function insert()
	{
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('controller','controller','trim|required');
		$this->form_validation->set_rules('method','controller','trim');
		$this->form_validation->set_rules('permalink','controller','trim');
		$this->form_validation->set_rules('order','controller','trim|integer');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Modules_model->insert($_POST))
				$this->utilities->flash('add','modules');
			else
				$this->utilities->flash('error','modules');
		}	
		
		$this->data['parents'] = $this->Modules_model->dropdown();

		//Heading
		$this->data['heading'] = 'Create New Module';
	}
    
	function edit($id)
	{
		$this->data['module'] = $this->Modules_model->select_single($id);
		if(!$this->data['module'])
			$this->utilities->flash('void','modules');
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('title','title','trim|required');
			$this->form_validation->set_rules('controller','controller','trim|required');
			$this->form_validation->set_rules('method','controller','trim');
			$this->form_validation->set_rules('permalink','controller','trim');
			$this->form_validation->set_rules('order','controller','trim|integer');
				
			if ($this->form_validation->run())
			{
				if($this->Modules_model->update($id,$_POST))
					$this->utilities->flash('add','modules');
				else
					$this->utilities->flash('error','modules');
			}
		}
		
		$this->data['sub_modules'] = $this->Sub_modules_model->select(array('module_id'=>$id));

		//Heading
		$this->data['heading'] = 'Edit Module';
	}
    
	function delete($id)
	{
		$this->data['module'] = $this->Modules_model->select_single($id);
		if(!$this->data['module'])
			$this->utilities->flash('void','modules');
			
		if($this->Modules_model->delete($id))
			$this->utilities->flash('delete','modules');
		else
			$this->utilities->flash('error','modules');
	}	
}