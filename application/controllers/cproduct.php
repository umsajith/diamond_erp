<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cproduct extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('products/Cproduct_model');
	}
    
	function index()
	{	
		//Heading
		$this->data['heading'] = 'Категории на Артикли';
	}
	
	function insert()
	{
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('pcname','product category name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Cproduct_model->insert($this->input->post('pcname')))
				echo 1;	
		}
		exit;
	}
	
	function edit()
	{
		$this->load->library('form_validation');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('pcname','product category name','trim|required');
				
		if ($this->form_validation->run())
		{
			if($this->Cproduct_model->update($_POST['id'],$_POST['pcname']))
				echo 1;
		}
		exit;
	}
	
	function delete()
	{
		if($this->Cproduct_model->delete($_POST['id']))
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
	
		$results = $this->Cproduct_model->select($options);
		$responce->total =$results['count'];
        $responce->page = $_POST['page'];
		$i = 0;
		foreach($results['results'] as $row) 
		{
			$responce->rows[$i]['id']=$row->id;
			$responce->rows[$i]['cell']=array($row->id,$row->pcname);
			$i++;
		}  

      	header('Content-Type: application/json',true);     
      	echo json_encode($responce);
      	exit;
	}	
}