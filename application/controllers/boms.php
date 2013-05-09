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

class Boms extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('production/boms_model','bom');
		$this->load->model('production/bomdetails_model','bomd');
		$this->load->model('uom/uom_model','uom');
	}
	
	public function index($sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{			
		//Heading
		$this->data['heading'] = uif::lng('app.bom_boms');
		
		//Columns which can be sorted by
		$this->data['columns'] = [	
			'name'       => uif::lng('attr.name'),
			'quantity'   => uif::lng('attr.quantity'),
			'prodname'   => uif::lng('attr.item'),
			'conversion' => uif::lng('attr.conversion')
		];
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('name','quantity','prodname','conversion');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'name';
		
		//Retreive data from Model
		$temp = $this->bom->select($sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("boms/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
	}
	
	public function insert()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('name',uif::lng('attr.name'),'trim|required');
		$this->form_validation->set_rules('quantity',uif::lng('attr.quantity'),'trim|required');
		$this->form_validation->set_rules('prodname_fk',uif::lng('attr.item'),'trim');
		$this->form_validation->set_rules('uname_fk',uif::lng('attr.uom'),'trim|required');
		$this->form_validation->set_rules('conversion',uif::lng('attr.conversion'),'trim');
		$this->form_validation->set_rules('description',uif::lng('attr.description'),'trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			$id = $this->bom->insert($_POST);
			
			if($id)
				air::flash('add',"boms/view/{$id}");
			else
				air::flash('error','boms');
		}

		//Heading
		$this->data['heading'] = uif::lng('app.bom_new');
		
		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
	}
	
	public function edit($id)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->bom->select_single($id);
		if(!$this->data['master']) 
			air::flash('void','boms');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->bomd->select_by_bom_id($id);
		
		if($_POST)
		{
			$this->form_validation->set_rules('name',uif::lng('attr.name'),'trim|required');
			$this->form_validation->set_rules('quantity',uif::lng('attr.quantity'),'trim|required');
			$this->form_validation->set_rules('prodname_fk',uif::lng('attr.item'),'trim');
			$this->form_validation->set_rules('uname_fk',uif::lng('attr.uom'),'trim|required');
			$this->form_validation->set_rules('conversion',uif::lng('attr.conversion'),'trim');
			$this->form_validation->set_rules('description',uif::lng('attr.description'),'trim');
			
			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				if($this->bom->update($id,$_POST))
					air::flash('add','boms');
				else
					air::flash('error','boms');
			}
		}
		
		//Heading
		$this->data['heading'] = uif::lng('app.bom_edit');

		$this->data['uoms'] = $this->uom->dropdown('id', 'uname');
	}
	
	//AJAX - Adds New Product in Bom Details
	public function addProduct()
	{
		$this->form_validation->set_rules('bom_fk',uif::lng('attr.bom'),'trim|required');
		$this->form_validation->set_rules('prodname_fk',uif::lng('attr.item'),'trim|required');
		$this->form_validation->set_rules('quantity',uif::lng('attr.quantity'),'trim|required');

		if ($this->form_validation->run())
		{
			if($this->bomd->insert($_POST))
				air::flash('add',"boms/view/".$_POST['bom_fk']);
		}

		air::flash('error',"boms/view/".$_POST['bom_fk']);
	}

	public function removeProduct($id)
	{
		if(!$id) show_404();

		if($this->bomd->delete($id))
			air::flash('delete',$_SERVER['HTTP_REFERER']);

		air::flash('error',$_SERVER['HTTP_REFERER']);
	}
	
	//AJAX - Edits the Quantity of Products from a Bom
	public function ajxEditQty()
	{
		$this->form_validation->set_rules('value','','required|numeric');

		if (($_POST['value'] < 0) OR (!$this->form_validation->run()))
		{
			$this->output->set_status_header(500,uif::lng('air.insert_valid_quantity'));
		}
		else
		{
			if(!$this->bomd->update($_POST['pk'],[$_POST['name']=>$_POST['value']]))
				$this->output->set_status_header(500);
		}
		exit;
	}

	public function view($id = false)
	{
		//Retreives data from MASTER Model
		$this->data['master'] = $this->bom->select_single($id);

		if(!$this->data['master']) air::flash('void');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->bomd->select_by_bom_id($id);

		//Heading
		$this->data['heading'] = uif::lng('app.bom_bom');
	}
	
	public function delete($id = false)
	{
		if(!$this->bom->get($id)) air::flash('void','boms');
		
		if($this->bom->delete($id))
			air::flash('delete','boms');
		else
			air::flash('error','boms');
	}
}