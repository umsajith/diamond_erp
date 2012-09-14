<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procurement extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{	
		redirect('inventory');
	}
}