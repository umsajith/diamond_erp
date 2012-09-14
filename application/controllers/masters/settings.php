<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('admin') != 1)
			redirect('logout');
	}

	function index()
	{	
		redirect('products');
	}
	
}