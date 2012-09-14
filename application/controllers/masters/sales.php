<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{	
		redirect('orders');
	}
	
}