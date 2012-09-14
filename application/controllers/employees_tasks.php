<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees_tasks extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		if(!$this->session->userdata('logged_in'))
			redirect('login');
		$this->load->model('hr/Emp_tasks_model');
	}
    
	function index()
	{	
			
	}
    
	function insert()
	{
		if($_POST)
		{
			//Load Validation Library
			$this->load->library('form_validation');
	
			//Defining Validation Rules
			$this->form_validation->set_rules('employee_fk','employee','trim|required');
			$this->form_validation->set_rules('task_fk','tasks','trim|required');
			
			if ($this->form_validation->run())
			{
				//Successful insertion
				if( $this->Emp_tasks_model->insert($_POST))
					echo 1;
				else
					exit;
			}
		}
	}
    
	function delete()
	{	
		if($this->Emp_tasks_model->delete($_POST['id']))
			echo 1;
		else
			exit;
	}
	
	function dropdown()
	{	
		$emp_id = json_decode($_GET['emp_id']);
		
		$data = $this->Emp_tasks_model->dropdown($emp_id);
		
		header('Content-Type: application/json',true); 
		echo json_encode($data);
	}
}