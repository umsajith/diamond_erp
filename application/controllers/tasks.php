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

class Tasks extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/task_model','tsk');
		$this->load->model('production/boms_model','bom');
		$this->load->model('uom/uom_model','uom');
	}
    
	public function index($sort_by = 'taskname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.tsk_tsks');

		//Columns which can be sorted by
		$this->data['columns'] = [	
			'taskname'            => uif::lng('attr.name'),
			'is_production'       => uif::lng('attr.production'),
			'base_unit'           => uif::lng('attr.base_unit'),
			'rate_per_unit'       => uif::lng('attr.price_per_uom'),
			'rate_per_unit_bonus' => uif::lng('attr.price_plus_per_uom')
		];
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array_keys($this->data['columns']);
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'taskname';
		
		//Retreive data from Model
		$temp = $this->tsk->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("tasks/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
    
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('taskname',uif::lng('attr.name'),'trim|required');
		$this->form_validation->set_rules('rate_per_unit',uif::lng('attr.price_per_uom'),'trim|required|numeric');
		$this->form_validation->set_rules('rate_per_unit_bonus',uif::lng('attr.price_plus_per_uom'),'trim|numeric');
		$this->form_validation->set_rules('base_unit',uif::lng('attr.base_unit'),'trim|required|numeric');
		$this->form_validation->set_rules('uname_fk',uif::lng('attr.uom'),'trim|required|numeric');
		$this->form_validation->set_rules('description','','trim|xss_clean');
		
		///Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->tsk->insert($_POST))
				air::flash('add','tasks');
			else
				air::flash('error','tasks');
		}	

		// Generating dropdown menu's
		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
		$this->data['boms'] = $this->bom->dropdown('id','name');

		//Heading
		$this->data['heading'] = uif::lng('app.tsk_new');
	}
    
	public function edit($id)
	{
		$this->data['task'] = $this->tsk->select_single($id);

		if(!$this->data['task']) air::flash('void','tasks');

		//Defining Validation Rules
		$this->form_validation->set_rules('taskname',uif::lng('attr.name'),'trim|required');
		$this->form_validation->set_rules('rate_per_unit',uif::lng('attr.price_per_uom'),'trim|required|numeric');
		$this->form_validation->set_rules('rate_per_unit_bonus',uif::lng('attr.price_plus_per_uom'),'trim|numeric');
		$this->form_validation->set_rules('base_unit',uif::lng('attr.base_unit'),'trim|required|numeric');
		$this->form_validation->set_rules('uname_fk',uif::lng('attr.uom'),'trim|required|numeric');
		$this->form_validation->set_rules('description','','trim|xss_clean');
			
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->tsk->update($id,$_POST))
				air::flash('update','tasks');
			else
				air::flash('error','tasks');
		}
		
		// Generating dropdown menu's
		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
		$this->data['boms'] = $this->bom->dropdown('id','name');

		//Heading
		$this->data['heading'] = uif::lng('app.tsk_edit');
	}
	
	public function view($id)
	{
		$this->data['master'] = $this->tsk->select_single($id);

		if(!$this->data['master']) air::flash('void','tasks');

		//Heading
		$this->data['heading'] = uif::lng('app.tsk_tsk');
	}
    
	public function delete($id)
	{
		if(!$this->tsk->get($id)) air::flash('void','tasks');

		if($this->tsk->delete($id))
			air::flash('delete','tasks');
		else
			air::flash('error','tasks');
	}

	public function ajxUOM()
	{
		if(!$_GET['task']) exit;
		header('Content-Type: application/json',true); 
		echo json_encode($this->tsk->getUOM($_GET['task']));
		exit;
	}
	
	public function dropdown()
	{
		header('Content-Type: application/json',true); 
		echo json_encode($this->tsk->dropdown());
		exit;
	}	
}