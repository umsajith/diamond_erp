<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Regional extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		//Authentication Check
		if(!($this->session->userdata('logged_in')))
			redirect('auth');
			
		//Load Models
		$this->load->model('regional/Regional_model');
		
		// Load Utility library
		$this->load->library('utilities');
	}
	function index()
	{
		
	}

	function city()
	{	
		//Heading
		$data['heading'] = 'Cities';
		
		//View
		$data['content']= 'regional/cities_view';
		
		//Module Navigation
		$data['modnav'] = 'includes/modnav/master_nav_view';
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('job_orders/index');
		$config['total_rows'] = count($this->Regional_model->city_select($_POST));
		$config['per_page'] = 15;
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//Retrevies Data from Model
		$data['results'] = $this->Regional_model->city_select($_POST,$config['per_page'],$offset);
		
		//Display
		$this->load->view('template',$data);
	}
	
	function postal_code()
	{	
		//Heading
		$data['heading'] = 'Postal Codes';
		
		//View
		$data['content']= 'regional/postal_codes_view';
		
		//Module Navigation
		$data['modnav'] = 'includes/modnav/master_nav_view';
		
		//Pagination
		$offset =  $this->uri->segment(3,0);
		
		$config['base_url'] = site_url('job_orders/index');
		$config['total_rows'] = count($this->Regional_model->postal_code_select($_POST));
		$config['per_page'] = 15;
		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//Retrevies Data from Model
		$data['results'] = $this->Regional_model->postal_code_select($_POST,$config['per_page'],$offset);
		
		//Display
		$this->load->view('template',$data);
	}
	
}
