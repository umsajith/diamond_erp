<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('invoices/Invoices_model','inv');	
	}
    
	public function index()
	{

	}
    
	public function insert()
	{
		
	}
    
	public function edit($id = false)
	{
		
	}
    
	public function delete($id = false)
	{
		
	}	
}