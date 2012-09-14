<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departments extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('hr/Department_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Сектори';	
	}
    
	function insert()
	{
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('department','department name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Department_model->insert($this->input->post('department')))
			{
				echo 1;
				exit;
			}
			else
				exit;
		}		
	}
	
	function edit()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('department','product category name','trim|required');
				
		if ($this->form_validation->run())
		{
			if($this->Department_model->update($_POST['id'],$_POST['department']))
			{
				echo 1;
				exit;
			}
			else
				exit;	
		}
	}
	
	function delete()
	{
		if($this->Department_model->delete($_POST['id']))
		{
			echo 1;
			exit;
		}
		else
			exit;
	}
	
	public function grid()
	{
		$options = array(
				'sortname' => $_POST['sortname'],
				'sortorder' => $_POST['sortorder'],
				'qtype' => $_POST['qtype'],
				'query' => $_POST['query'],
				'limit' => $_POST['rp'],
				'offset' => ($_POST['page']-1)*$_POST['rp']
		);
	
		$results = $this->Department_model->select($options);
		$responce->total =$results['count'];
		$responce->page = $_POST['page'];
		$i = 0;
		foreach($results['results'] as $row)
		{
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell']=array($row->id,$row->department);
			$i++;
		}
	
		header('Content-Type: application/json',true);
		echo json_encode($responce);
		exit;
	}
}