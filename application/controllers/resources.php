<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
    
	public function index()
	{	
		//Heading
		$this->data['heading'] = 'Resources';
		
		//Retreive data from Model
		$this->data['results'] = $this->Resources_model->get_all_resources();	
	}
    
	public function insert()
	{
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('controller','controller','trim|required');
		$this->form_validation->set_rules('order','order','trim|integer|required');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Resources_model->insert($_POST))
				$this->utilities->flash('add','resources');
			else
				$this->utilities->flash('error','resources');
		}	
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = 'Create New Resource';
	}
    
	public function edit($id)
	{
		$this->data['resource'] = $this->Resources_model->get($id);

		if(!$this->data['resource'])
			$this->utilities->flash('void','resources');
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('title','title','trim|required');
			$this->form_validation->set_rules('controller','controller','trim|required');
			$this->form_validation->set_rules('order','order','trim|integer');
				
			if ($this->form_validation->run())
			{
				//print_r($_POST); die;
				if(!strlen($_POST['parent_id']))
					unset($_POST['parent_id']);

				if($this->Resources_model->update($_POST['id'],$_POST))
					$this->utilities->flash('add','resources');
				else
					$this->utilities->flash('error','resources');
			}
		}
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = 'Edit Resource';
	}
    
	public function delete($id)
	{
		if(!$this->Resources_model->get($id))
			$this->utilities->flash('void','resources');
			
		if($this->Resources_model->delete($id))
			$this->utilities->flash('delete','resources');
		else
			$this->utilities->flash('error','resources');
	}	
}