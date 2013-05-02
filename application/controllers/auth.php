<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth/auth_model','auth');
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
			redirect($this->session->userdata('default_module'));
		}
		$this->load->view('login_view');
	}
	
	public function login()
	{	
		if($this->session->userdata('logged_in'))
		{
			redirect($this->session->userdata('default_module'));
		}	
		//Load Models
		$this->load->model('hr/employees_model','emp');
		
		//Chekcs if the Login is NOT done through AJAX
		$isAjax = true;

		if(!$this->input->is_ajax_request()) $isAjax = false;			

		//Defining Validation Rules
		$this->form_validation->set_rules('username','username','trim|required');
		$this->form_validation->set_rules('password','password','required');
		
		if($this->form_validation->run())
		{
			//Login
			$user = $this->auth->check_login($this->input->post('username'),
				$this->input->post('password'));
			
			if(!$user)
			{
				$this->output->set_status_header(401);
				($isAjax) ? exit : redirect('login');
			}
			
			$this->authenticate($user);

			//Updates the last login for this user
			$this->emp->last_login($user->id);

			//AJAX Login
			if($isAjax)
			{
				$this->output->set_status_header(200);
				$this->output->set_content_type('application/json');
				echo json_encode(['redirect'=>base_url( $this->session->userdata('next') ?
					$this->session->userdata('next') : $this->session->userdata('default_module') )]);
				exit;
			}
			else
			{
				redirect($this->session->userdata('next') ? 
					$this->session->userdata('next') : $this->session->userdata('default_module') );	
			
			}		
		}

		$this->load->view('login_view');
	}

	private function authenticate($user)
	{
		//Get permissions based on user role_id
		$allow_resources = $this->Permissions_model->get_permissions($user->role_id,'allow');
		$deny_resources = $this->Permissions_model->get_permissions($user->role_id,'deny');
		
		//Retreive master resources (main modules) based on resource_id
		$modules = $this->Resources_model->get_resources($allow_resources);

		$this->auth->set_session($user,$modules,$allow_resources,$deny_resources);
	}
	
	public function logout()
	{
		$this->auth->logout();
		redirect('login');	
	}
}