<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uom extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('uom/Uom_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Единици Мерки';
	}
	
	function insert()
	{
		$this->load->library('form_validation');

		//Defining Validation Rules
		$this->form_validation->set_rules('uname','','trim|required');
		$this->form_validation->set_rules('description','','trim');
		$this->form_validation->set_rules('oper','','trim|required');
		
		if ($this->form_validation->run())
		{
			unset($_POST['oper']);
			if($this->Uom_model->insert($_POST))
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
		$this->form_validation->set_rules('uname','','trim|required');
		$this->form_validation->set_rules('description','','trim');
		$this->form_validation->set_rules('oper','','trim|required');
				
		if ($this->form_validation->run())
		{
			unset($_POST['oper']);

			if($this->Uom_model->update($_POST))
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
		if($this->Uom_model->delete($_POST['id']))
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
	
		$results = $this->Uom_model->select($options);
		$responce->total =$results['count'];
		$responce->page = $_POST['page'];
		$i = 0;
		foreach($results['results'] as $row)
		{
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell']=array($row->id,$row->uname,$row->description);
			$i++;
		}
	
		header('Content-Type: application/json',true);
		echo json_encode($responce);
		exit;
	}
}