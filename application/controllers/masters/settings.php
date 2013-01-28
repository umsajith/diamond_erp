<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{	
		redirect('products');
	}
	
}