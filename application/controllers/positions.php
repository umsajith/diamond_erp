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

class Positions extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('hr/positions_model','pos');
		$this->load->model('hr/department_model','dept');
	}
	
	public function index($sort_by = 'position', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Работни Места';
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'position'    =>'Назив',
			'department'  =>'Сектор',
			'base_salary' =>'Основна Плата',
			'bonus'       =>'Бонсу',
			'commision'   =>'Провизија'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('position','department','base_salary',
						'bonus','commision');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'position';
		
		//Retreive data from Model
		$temp = $this->pos->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("positions/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
	
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('position','position name','trim|required');
		$this->form_validation->set_rules('dept_fk','department','required');
		$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
		$this->form_validation->set_rules('bonus','bonus','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('requirements','requirements','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pos->insert($_POST))
				air::flash('add','positions');
			else
				air::flash('error','positions');	
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = $this->dept->dropdown('id', 'department');

		//Heading
		$this->data['heading'] = 'Ново Работно Место';
	}
	
	public function edit($id)
	{
		$this->data['position'] = $this->pos->select_single($id);
		if(!$this->data['position'])
			air::flash('void','positions');

		//Defining Validation Rules
		$this->form_validation->set_rules('position','position name','trim|required');
		$this->form_validation->set_rules('base_salary','base salary','trim|numeric');
		$this->form_validation->set_rules('bonus','bonus','trim|numeric');
		$this->form_validation->set_rules('commision','commision','trim|numeric');
		$this->form_validation->set_rules('requirements','requirements','trim');
		$this->form_validation->set_rules('description','description','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->pos->update($_POST['id'],$_POST))
				air::flash('update','positions');
			else
				air::flash('error','positions');
		}
		
		//Generate dropdown menu data
		$this->data['departments'] = $this->dept->dropdown('id', 'department');

		//Heading
		$this->data['heading'] = 'Корекција на Работно Место';
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Работно Место';

		$this->data['master'] = $this->pos->select_single($id);
		if(!$this->data['master'])
			air::flash('void','positions');
	}

	public function delete($id)
	{
		if(!$this->pos->get($id))
			air::flash('void','positions');

		if($success = $this->pos->delete($id))
			air::flash('delete','positions');
		else
			air::flash('error','positions');
	}
}