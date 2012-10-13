<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('auth/auth_model','auth');
	}

	public function index()
	{
		//If already logged in, redirects to Dashboard
		if(($this->session->userdata('logged_in') == true))
			redirect($this->session->userdata('default_module'));
		
		//Load Main View
		$this->load->view('login_view');
	}
	
	public function login()
	{
		//If already logged in, redirects to Dashboard
		if(($this->session->userdata('logged_in') == true))
			redirect($this->session->userdata('default_module'));
					
		//Load Models
		$this->load->model('hr/employees_model','emp');
		
		//Chekcs if the Login is done through AJAX
		if(isset($_POST['ajax']) AND json_decode($_POST['ajax'])=="1")
			$is_ajax = true;
		
		//Load Validation Library
		$this->load->library('form_validation');
		
		//Defining Validation Rules
		$this->form_validation->set_rules('username','username','trim|required');
		$this->form_validation->set_rules('password','password','required');
		
		if($this->form_validation->run())
		{
			//Login
			$user = $this->auth->check_login($_POST['username'],$_POST['password']);
			
			if(!$user)
			{
				if($is_ajax)
					exit();
				else
				{
					//Non-AJAX Error
					$this->session->set_flashdata('flash','No Modules Enabled! Contact Admin');
					redirect('login');
				}	
			} 

			//Updates the last login for this user
			$this->emp->last_login($user->id);

			//AJAX Login
			if($is_ajax)
			{
				if($this->session->userdata('next'))
					echo site_url($this->session->userdata('next'));
				else
					echo site_url($this->session->userdata('default_module')); //Redirects to first open module
				
				exit();
			}
			else
			{
				if($this->session->userdata('next'))
					redirect($this->session->userdata('next'));
				else
					echo site_url($this->session->userdata('default_module')); //Redirects to first open module
			}		
		}
		else
			//Validation did not pass
			$this->index();	
	}
	
	public function logout()
	{
		$this->auth->logout();
		redirect ('login');	
	}
}