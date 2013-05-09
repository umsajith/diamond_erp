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

class Resources extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
    
	public function index()
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.res_ress');
		
		//Retreive data from Model
		$this->data['results'] = $this->Resources_model->get_all_resources();	
	}
    
	public function insert()
	{
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('controller','controller','trim|required');
		$this->form_validation->set_rules('order','order','trim|integer|required');
		$this->form_validation->set_rules('visible','visible','integer');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->Resources_model->insert($_POST))
				air::flash('add','resources');
			else
				air::flash('error','resources');
		}	
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = uif::lng('app.res_new');
	}
    
	public function edit($id)
	{
		$this->data['resource'] = $this->Resources_model->get($id);

		if(!$this->data['resource'])
			air::flash('void','resources');
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('title','title','trim|required');
			$this->form_validation->set_rules('controller','controller','trim|required');
			$this->form_validation->set_rules('order','order','trim|integer');
			$this->form_validation->set_rules('visible','visible','integer');
				
			if ($this->form_validation->run())
			{
				if($this->Resources_model->update($_POST['id'],$_POST))
					air::flash('add','resources');
				else
					air::flash('error','resources');
			}
		}
		
		$this->data['parents'] = $this->Resources_model->dropdown_master();

		//Heading
		$this->data['heading'] = uif::lng('app.res_edit');
	}
    
	public function delete($id)
	{
		if(!$this->Resources_model->get($id)) air::flash('void');
			
		if($this->Resources_model->delete($id))
			air::flash('delete','resources');
		else
			air::flash('error','resources');
	}	
}