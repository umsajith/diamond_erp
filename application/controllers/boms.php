<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boms extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('production/boms_model','bom');
		$this->load->model('production/bomdetails_model','bomd');
	}
	
	public function index($sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{			
		//Heading
		$this->data['heading'] = 'Нормативи';
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'name'=>'Назив',
			'quantity'=>'Количина',
			'prodname'=>'Производ',
			'conversion' => 'Конверзија'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('name','quantity','prodname','conversion');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'name';
		
		//Retreive data from Model
		$temp = $this->bom->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("boms/index/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 5;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
	
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('name','name','trim|required');
		$this->form_validation->set_rules('quantity','quantity','trim|required');
		$this->form_validation->set_rules('prodname_fk','product','trim');
		$this->form_validation->set_rules('uname_fk','uom','trim|required');
		$this->form_validation->set_rules('conversion','conversion','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			$id = $this->bom->insert($_POST);
			
			if($id)
				$this->utilities->flash('add','boms/edit/'.$id);
			else
				$this->utilities->flash('error','boms');
		/*
			$master = array(
							'name'=>$_POST['name'],
							'quantity'=>$_POST['quantity']);
			$bom_fk = $this->bom->insert($master);
			
			if($bom_fk)
			{
				//Decode the JSON object int Ass.array and loop through detail records
				foreach (json_decode($_POST['components'],TRUE) as $detail)
				{
					//Inserts all Detail records into the database
					$this->bomd->insert(array(
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
	
	public function edit($id)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->bom->select_single($id);
		if(!$this->data['master']) 
			$this->utilities->flash('void','boms');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->bomd->select_by_bom_id($id);
		
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
				if($this->bom->update($id,$_POST))
					$this->utilities->flash('add','boms');
				else
					$this->utilities->flash('error','boms');
			}
		}
		*/
		//Heading
		$this->data['heading'] = "Корекција на Норматив";
		
		$this->data['products'] = $this->utilities->get_products('purchasable',false,true);
	}
	
	//AJAX - Adds New Product in Bom Details
	public function add_product()
	{
		$data['bom_fk'] = json_decode($_POST['bom_fk']);
		$data['prodname_fk'] = json_decode($_POST['prodname_fk']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->bomd->insert($data))
			echo 1;
		exit;
	}
	
	//AJAX - Edits the Quantity of Products from a Bom
	public function edit_qty()
	{
		$data['id'] = json_decode($_POST['id']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->bomd->update($data['id'],$data['quantity']))
			echo json_encode($data['quantity']);
		exit;	
	}
	
	//AJAX - Removes Products from a Bom
	public function remove_product()
	{
		if($this->bomd->delete(json_decode($_POST['id'])))
			echo 1;
		exit;
	}
	
	public function view($id = false)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->bom->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','boms');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->bomd->select_by_bom_id($id);

		//Heading
		$this->data['heading'] = 'Норматив';
	}
	
	public function delete($id = false)
	{
		if(!$this->bom->select_single($id))
			$this->utilities->flash('void','boms');
		
		if($this->bom->delete($id))
			$this->utilities->flash('delete','boms');
		else
			$this->utilities->flash('error','boms');
	}
}