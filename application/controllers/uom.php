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

class Uom extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('uom/uom_model','uom');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'uname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = uif::lng('app.uom_uoms');

		$this->data['columns'] = array (	
			'uname'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('uname');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'uname';

		$this->data['results'] = $this->uom->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->uom->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$this->data['pagination'] = 
		paginate("uom/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5); 
		
		$this->data['pagination'] = $this->pagination->create_links();

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
		$this->data['heading'] = uif::lng('app.uom_new');

		//Defining Validation Rules
		$this->form_validation->set_rules('uname','UOM name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->uom->insert($_POST))
				air::flash('add','uom');
		}
	}
	/**
	 * Edits entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message     
	 */
	public function edit($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.uom_edit');

		$this->data['result'] = $this->uom->get($id);
		if(!$this->data['result'])
			air::flash('void','uom');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('uname','UOM name','trim|required');
				
		if ($this->form_validation->run())
		{
			$this->uom->update($_POST['id'],['uname'=>$_POST['uname']]);
				air::flash('update','uom');
		}
	}
	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->uom->get($id);
		if(!$this->data['result'])
			air::flash('void','uom');
		
		if($this->uom->delete($this->data['result']->id))
			air::flash('delete','uom');
		else
			air::flash('error','uom');
	}
}