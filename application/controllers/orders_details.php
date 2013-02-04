<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_details extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/cod_model','cod');
	}

	//AJAX - Adds New Product in Order Details
	public function add_product()
	{
		$data['order_fk'] = $_POST['order_fk'];
		$data['prodname_fk'] = $_POST['prodname_fk'];
		$data['quantity'] = $_POST['quantity'];

		if($this->cod->insert($data))
			echo 1;
			
		exit;		
	}
	
	//AJAX - Removes Products from an Order
	public function remove_product()
	{
		if($this->cod->delete(json_decode($_POST['id'])))
			echo json_encode(array('message'=>'Производот е успешно избришан'));
		
		exit;
	}
	
	//AJAX - Edits the Quantity of Products from an Order
	public function edit_qty()
	{
		$id = json_decode($_POST['id']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->cod->update($id,$data))
		{
			echo json_encode($data['quantity']);
			exit;
		}
		else
			exit;	
	}
	
	//AJAX - Edits the Returned Quantity of Products from an Order
	public function edit_ret_qty()
	{	
		$id = json_decode($_POST['id']);
		$data['returned_quantity'] = json_decode($_POST['returned_quantity']);
		
		if($this->cod->update($id,$data))
		{
			echo json_encode($data['returned_quantity']);
			exit;
		}
		else
			exit;		
	}
	
	public function delete($id = false)
	{
		$this->data['master'] = $this->co->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders');

		if($this->co->delete($id))
			$this->utilities->flash('delete','orders');
		else
			$this->utilities->flash('error','orders');
	}
}