<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *   Diamond ERP - Complete ERP for SMBs
 *   
 *   @author Marko Aleksic <psybaron@gmail.com>
 *   @copyright Copyright (C) 2013  Marko Aleksic
 *   @link https://github.com/psybaron/diamond_erp
 *   @license http://opensource.org/licenses/GPL-3.0
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth/auth_model','auth');
		$this->load->model('hr/employees_model','emp');
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
		if(!$_POST) redirect('/');

		//Chekcs if the Login is NOT done through AJAX
		$isAjax = true;

		if(!$this->input->is_ajax_request()) $isAjax = false;			

		//Defining Validation Rules
		$this->form_validation->set_rules('username','username','trim|required|xss_clean');
		$this->form_validation->set_rules('password','password','trim|required|xss_clean');
		
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
			$this->emp->lastLogin($user->id);

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
		redirect('/');	
	}
}