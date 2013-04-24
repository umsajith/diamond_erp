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
		$this->form_validation->set_rules('value','','required|numeric');

		if (($_POST['value'] < 0) OR (!$this->form_validation->run()))
		{
			$this->output->set_status_header(500,'Внесете валидна вредност');
		}
		else
		{
			if(!$this->cod->update($_POST['pk'],[$_POST['name']=>$_POST['value']]))
				$this->output->set_status_header(500);
		}
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