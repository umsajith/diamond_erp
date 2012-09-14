<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boms extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('production/Boms_model');
		$this->load->model('production/Bomdetails_model');
	}
	
	public function index()
	{			
		//Heading
		$this->data['heading'] = 'Нормативи';
		
		//Retreive data from Model
		$this->data['results'] = $this->Boms_model->select();
	}
	
	public function insert()
	{
		//Load formvalidation library
		$this->load->library('form_validation');

		//Defining Validation Rules
		$this->form_validation->set_rules('name','name','trim|required');
		$this->form_validation->set_rules('quantity','quantity','trim|required');
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('uname_fk','uom','trim|required');
		$this->form_validation->set_rules('conversion','conversion','trim|required');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//echo "<pre>";
			//print_r($_POST); die();
			
			$id = $this->Boms_model->insert($_POST);
			
			if($id)
				$this->utilities->flash('add','boms/edit/'.$id);
			else
				$this->utilities->flash('error','boms');
		/*
			$master = array(
							'name'=>$_POST['name'],
							'quantity'=>$_POST['quantity']);
			$bom_fk = $this->Boms_model->insert($master);
			
			if($bom_fk)
			{
				//Decode the JSON object int Ass.array and loop through detail records
				foreach (json_decode($_POST['components'],TRUE) as $detail)
				{
					//Inserts all Detail records into the database
					$this->Bomdetails_model->insert(array(
							'bom_fk'=>$bom_fk,
							'prodname_fk'=>$detail['id'],
							'quantity'=>$detail['quantity']));	
				}
				$this->session->set_flashdata('flash','Record successfuly added');
				echo json_encode(array('success'=>TRUE));
				exit;
			}
			else
			{
				$this->session->set_flashdata('flash','Database error');
				echo json_encode(array('success'=>FALSE));
				exit;	
			}	
		*/
		}

		//Heading
		$this->data['heading'] = 'Внес на Норматив';
		
		$this->data['uoms'] = $this->utilities->get_dropdown('id', 'uname','exp_cd_uom');
	}
	
	public function edit($id = false)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Boms_model->select_single($id);
		
		//If there is nothing, redirects
		if(!$this->data['master']) 
			$this->utilities->flash('void','boms');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->Bomdetails_model->select(array('id'=>$id));
		
		/*
		if(isset($_POST['submit']))
		{
			//Unsets the POST submit, so I doesnt get inserted into the db
			unset($_POST['submit']);
			
			//Load Validation Library
			$this->load->library('form_validation');
		
			//Defining Validation Rules
			$this->form_validation->set_rules('prodname_fk','product','trim|required');
			$this->form_validation->set_rules('quantity','quantity','trim|required');
			$this->form_validation->set_rules('description','description','trim');
			
			
			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				if($this->Boms_model->update($id,$_POST))
					$this->utilities->flash('add','boms');
				else
					$this->utilities->flash('error','boms');
			}
		}
		*/
		//Heading
		$this->data['heading'] = "Корекција на Норматив";
		
		$this->data['products'] = $this->utilities->get_products('purchasable');
	}
	
	//AJAX - Adds New Product in Bom Details
	public function add_product()
	{
		$data['bom_fk'] = json_decode($_POST['bom_fk']);
		$data['prodname_fk'] = json_decode($_POST['prodname_fk']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->Bomdetails_model->insert($data))
		{
			echo 1;
			exit;
		}
		else
			exit;
		
	}
	
	//AJAX - Edits the Quantity of Products from a Bom
	public function edit_qty()
	{
		$data['id'] = json_decode($_POST['id']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->Bomdetails_model->update($data))
		{
			echo json_encode($data['quantity']);
			exit;
		}
		else
			exit;	
	}
	
	//AJAX - Removes Products from a Bom
	public function remove_product()
	{
		if($this->Bomdetails_model->delete(json_decode($_POST['id'])))
		{
			echo 1;
			exit;
		}
		else
			exit;	
	}
	
	public function view($id = false)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Boms_model->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','boms');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->Bomdetails_model->select(array('id'=>$id));

		//Heading
		$this->data['heading'] = 'Норматив';
	}
	
	public function delete($id = false)
	{
		if(!$this->Boms_model->select_single($id))
			$this->utilities->flash('void','boms');
		
		if($this->Boms_model->delete($id))
				$this->utilities->flash('delete','boms');
			else
				$this->utilities->flash('error','boms');
	}
}