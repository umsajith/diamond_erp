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
	public function ajxAddProduct()
	{
		//TODO ADD Validation
		$data['order_fk'] = $_POST['order_fk'];
		$data['prodname_fk'] = $_POST['prodname_fk'];
		$data['quantity'] = $_POST['quantity'];
		$data['returned_quantity'] = $_POST['returned_quantity'];

		if(!$this->cod->insert($data))
			$this->output->set_status_header(500);

		exit;		
	}
	
	//AJAX - Removes Products from an Order
	public function ajxRemoveProduct()
	{
		if(!$this->cod->delete($_POST['id']))
			$this->output->set_status_header(500);
		
		exit;
	}
	
	//AJAX - Edits the Quantity/Returned Qty of Products from an Order
	public function ajxEditQty()
	{
		//TODO ADD Validation

		if(!in_array($_POST['name'],['quantity','returned_quantity']))
		{
			$this->output->set_status_header(400);
			exit;
		}

		if(!$this->cod->update($_POST['pk'],[$_POST['name']=>$_POST['value']]))
			$this->output->set_status_header(500);	

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