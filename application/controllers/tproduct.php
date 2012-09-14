<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tproduct extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('products/Tproduct_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Tипови на Артикли';
	}
	
	function insert()
	{
		$this->load->library('form_validation');

		//Defining Validation Rules
		$this->form_validation->set_rules('ptname','product type name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Tproduct_model->insert($this->input->post('ptname')))
				echo 1;
		}
		
		exit;
	}
	
	function edit()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('ptname','product type name','trim|required');
				
		if ($this->form_validation->run())
		{
			$this->Tproduct_model->update($_POST['id'],$_POST['ptname']);
				echo 1;
		}
		
		exit;
	}
	
	function delete()
	{
		if($this->Tproduct_model->delete($_POST['id']))
			echo 1;
			
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
	
		$results = $this->Tproduct_model->select($options);
		$responce->total =$results['count'];
        $responce->page = $_POST['page'];
		$i = 0;
		foreach($results['results'] as $row) 
		{
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell']=array($row->id,$row->ptname);
			$i++;
		}  

      	header('Content-Type: application/json',true);     
      	echo json_encode($responce);
      	exit;
	}
}