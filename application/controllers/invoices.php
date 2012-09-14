<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('invoices/Invoices_model');	
	}
    
	function index()
	{
		$this->load->model('partners/Partners_model');
		
		$this->data['heading'] = 'Фактури';

		$this->data['customers'] = $this->Partners_model->dropdown('customers');
		$this->data['distributors'] = $this->utilities->get_distributors();
		
		$this->data['results'] = $this->Invoices_model->select();
	}
    
	function insert()
	{
		
	}
    
	function edit($id = false)
	{
		
	}
    
	function delete($id = false)
	{
		
	}	
}