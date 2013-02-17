<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		
		$this->load->model('auth/Auth_model','auth');
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
			redirect($this->session->userdata('default_module'));
		
		$this->load->view('login_view');
	}
	
	public function login()
	{
		//If already logged in, redirects to Dashboard
		if($this->session->userdata('logged_in'))
			redirect($this->session->userdata('default_module'));
					
		//Load Models
		$this->load->model('hr/employees_model','emp');
		
		//Chekcs if the Login is NOT done through AJAX
		$is_ajax = true;
		if(!$this->input->is_ajax_request())
			$is_ajax = false;

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
					redirect('login');
			}
			
			$this->authenticate($user);

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
					redirect($this->session->userdata('default_module')); //Redirects to first open module
			}		
		}
		else
			//Validation did not pass
			$this->index();	
	}

	private function authenticate($user)
	{
		$this->load->model('acl/Permissions_model','perm');
		$this->load->model('acl/Resources_model','res');

		//Get permissions based on user role_id
		$allow_resources = $this->perm->get_permissions($user->role_id,'allow');
		$deny_resources = $this->perm->get_permissions($user->role_id,'deny');
		
		//Retreive master resources (main modules) based on resource_id
		$modules = $this->res->get_resources($allow_resources);

		$this->auth->set_session($user,$modules,$allow_resources,$deny_resources);
	}
	
	public function logout()
	{
		$this->auth->logout();
		redirect ('login');	
	}
}