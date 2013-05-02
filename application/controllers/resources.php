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
		$this->form_validation->set_rules('visible','visible','integer');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Resources_model->insert($_POST))
				air::flash('add','resources');
			else
				air::flash('error','resources');
		}	
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = 'Create New Resource';
	}
    
	public function edit($id)
	{
		$this->data['resource'] = $this->Resources_model->get($id);

		if(!$this->data['resource'])
			air::flash('void','resources');
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('title','title','trim|required');
			$this->form_validation->set_rules('controller','controller','trim|required');
			$this->form_validation->set_rules('order','order','trim|integer');
			$this->form_validation->set_rules('visible','visible','integer');
				
			if ($this->form_validation->run())
			{
				if($this->Resources_model->update($_POST['id'],$_POST))
					air::flash('add','resources');
				else
					air::flash('error','resources');
			}
		}
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = 'Edit Resource';
	}
    
	public function delete($id)
	{
		if(!$this->Resources_model->get($id)) air::flash('void');
			
		if($this->Resources_model->delete($id))
			air::flash('delete','resources');
		else
			air::flash('error','resources');
	}	
}