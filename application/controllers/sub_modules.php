<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_Modules extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Sub Modules';
		
		//Retreive data from Model
		$this->data['results'] = $this->Sub_modules_model->select();
	}
    
	function insert()
	{
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('module_id','module','trim|required');
		$this->form_validation->set_rules('controller','controller','trim|required');
		$this->form_validation->set_rules('method','method','trim');
		$this->form_validation->set_rules('permalink','permalink','trim');
		$this->form_validation->set_rules('order','order','trim|integer');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			$success = $this->Sub_modules_model->insert($_POST);
			
			if($success)
			{
				$this->utilities->flash('add','sub_modules',false);
				echo $success;
				exit;
			}
			else
			{
				$this->utilities->flash('error','sub_modules',false);
				exit;
			}
		}	
		
		$this->data['modules'] = $this->utilities->get_dropdown('id','title','exp_cd_modules');

		//Heading
		$this->data['heading'] = 'Create New Sub-Module';
	}
    
	function edit($id)
	{
		$this->data['module'] = $this->Sub_modules_model->select_single($id);
		if(!$this->data['module'])
			$this->utilities->flash('void','sub_modules');
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('title','title','trim|required');
			$this->form_validation->set_rules('module_id','module','trim|required');
			$this->form_validation->set_rules('controller','controller','trim|required');
			$this->form_validation->set_rules('method','method','trim');
			$this->form_validation->set_rules('permalink','permalink','trim');
			$this->form_validation->set_rules('order','order','trim|integer');
				
			if ($this->form_validation->run())
			{
				if($this->Sub_modules_model->update($id,$_POST))
					$this->utilities->flash('add','sub_modules');
				else
					$this->utilities->flash('error','sub_modules');
			}
		}
		
		$this->data['modules'] = $this->utilities->get_dropdown('id','title','exp_cd_modules');

		//Heading
		$this->data['heading'] = 'Edit Sub-Module';
	}
    
	function delete($id)
	{
		$this->data['module'] = $this->Sub_modules_model->select_single($_POST['id']);
		if(!$this->data['module'])
			$this->utilities->flash('void','sub_modules');
			
		if($this->Sub_modules_model->delete($_POST['id']))
		{
			$this->utilities->flash('delete','sub_modules',false);
			echo 1;
			exit;
		}
		else
		{
			$this->utilities->flash('error','sub_modules',false);
			exit;
		}
	}	
}