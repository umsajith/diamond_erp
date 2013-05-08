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

class Roles extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('acl/roles_model','rl');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.rl_rls');

		$this->data['columns'] = array (	
			'name'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('name');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'name';

		$this->data['results'] = $this->rl->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->rl->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$this->data['pagination'] = 
		paginate("roles/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5);

		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
	/**
	 * Creates new entry
	 * @return redirects with success/error message
	 */
	public function insert()
	{
		//Heading
		$this->data['heading'] = uif::lng('app.rl_new');

		//Defining Validation Rules
		$this->form_validation->set_rules('name','user group name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->rl->insert($_POST))
				air::flash('add','roles');
			else
				air::flash('error','roles');
		}

		$this->data['parents'] = $this->rl->dropdown_master();
	}
	/**
	 * Edits entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message     
	 */
	public function edit($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.rl_edit');

		$this->data['result'] = $this->rl->get($id);
		if(!$this->data['result'])
			air::flash('void','roles');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('name','user group name','trim|required');
				
		if ($this->form_validation->run())
		{
			if($this->rl->update($_POST['id'],$_POST))
				air::flash('update','roles');
			else
				air::flash('error','roles');
		}

		$this->data['parents'] = $this->rl->dropdown_master();
	}

	public function view($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.rl_rl');

		$this->data['result'] = $this->rl->get($id);
		
		if(!$this->data['result'])
			air::flash('void','roles');

		$this->data['resources'] = $this->Permissions_model->get_resources_by_role_id($id);

		$this->data['dd_permissions'] = ['allow'=>'Allow','deny'=>'Deny'];

		$this->data['dd_resources'] = $this->Resources_model->dropdown_all();
	}

	public function assign_resource()
	{
		$this->form_validation->set_rules('role_id','role','trim|required');
		$this->form_validation->set_rules('resource_id','resource','trim|required');
		$this->form_validation->set_rules('permission','permission','trim|required');

		if($this->form_validation->run())
		{
			$result = $this->Permissions_model->insert_role_resource(
				$_POST['role_id'],$_POST['resource_id'],$_POST['permission']
			);

			if($result)
				air::flash('add',"roles/view/{$_POST['role_id']}");
			else
				air::flash('error',"roles/view/{$_POST['role_id']}");
		}
		else
			air::flash('error',"roles/view/{$_POST['role_id']}");
	}

	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->rl->get($id);
		if(!$this->data['result'])
			air::flash('void','roles');
		
		if($this->rl->delete($this->data['result']->id))
			air::flash('delete','roles');
		else
			air::flash('error','roles');

	}
}