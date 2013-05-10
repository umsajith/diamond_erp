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

class Orders_list extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/col_model','col');
		$this->load->model('orders/cod_model','cod');
		$this->load->model('hr/employees_model','emp');
	}

	public function index($query_id = 0,$sort_by = 'date', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.ol_ols');

		//Dropdown Menus
		$this->data['distributors'] = $this->emp->generateDropdown(['is_distributer' => 1]);

		//Columns which can be sorted by
		$this->data['columns'] = [
			'date'           => uif::lng('attr.date'),
			'distributor_id' => uif::lng('attr.distributor'),
			'ext_doc'        => uif::lng('attr.document'),
			'code'           => uif::lng('attr.code'),
			'dateofentry'    => uif::lng('attr.doe')
		];

		$this->input->load_query($query_id);
		
		$query_array = [
			'distributor_id' => $this->input->get('distributor_id'),
			'q'              => $this->input->get('q')
		];

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = ['date','distributor_id','ext_doc',
								'dateofentry','code'];
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'date';

		//Retreive data from Model
		$temp = $this->col->select($query_array, $sort_by, $sort_order, $this->limit, $offset);

		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];

		$this->data['pagination'] = 
		paginate("orders_list/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}

	public function search()
	{
		//(strlen($_POST['q'])) ? $_POST['distributor_id'] = '' : '';
		//(strlen($_POST['distributor_id'])) ? $_POST['q'] = '' : '';

		$query_array = [
			'distributor_id' => $this->input->post('distributor_id'),
			'q'              => $this->input->post('q')
		];	
		$query_id = $this->input->save_query($query_array);
		redirect("orders_list/index/{$query_id}");
	}

	public function insert()
	{
		$this->data['heading'] = uif::lng('app.ol_new');
		//Dropdown Menus
		$this->data['distributors'] = $this->emp->generateDropdown(['is_distributer' => 1]);
		
		$this->form_validation->set_rules('date',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('distributor_id',uif::lng('attr.distributor'),'trim|required');
		$this->form_validation->set_rules('ext_doc','','trim');
		$this->form_validation->set_rules('note','','trim');
		
		if($this->form_validation->run())
		{	
			if($order_id = $this->col->insert($_POST))
			{
				air::flash('add',"orders_list/view/{$order_id}");
			}
			air::flash('error',"orders_list/insert");
		}
	}

	public function edit($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.ol_edit');

		//Dropdown Menus
		$this->data['distributors'] = $this->emp->generateDropdown(['is_distributer' => 1]);

		$this->data['master'] = $this->col->select_one($id);
		
		if(!$this->data['master']) air::flash('void','orders_list');

		/*
		 * Prevents from editing locked record
		 */
		if($this->data['master']->locked) air::flash('deny','orders_list');
		
		$this->form_validation->set_rules('date',uif::lng('attr.date'),'trim|required');
		$this->form_validation->set_rules('distributor_id',uif::lng('attr.distributor'),'trim|required');
		$this->form_validation->set_rules('ext_doc','','trim');
		$this->form_validation->set_rules('note','','trim');
		
		if($this->form_validation->run())
		{
			if($this->col->update($_POST['id'],$_POST))
			{
				air::flash('update','orders_list');
			}
			air::flash('error',"orders_list/edit/{$id}");
		}
	}

	public function view($id)
	{
		//Heading
		$this->data['heading'] =  uif::lng('app.ol_ol');

		$this->data['master'] = $this->col->select_one($id);

		if(!$this->data['master']) air::flash('void');

		$this->data['results'] = $this->co->select_by_order_list($id);
		
		//Dropdown Menus
		$this->load->model('financial/paymentmode_model','pmm');
		$this->data['pmodes'] = $this->pmm->dropdown('id','name');
	}

	public function delete($id)
	{
		$this->data['master'] = $this->col->get($id);
		
		if(!$this->data['master']) air::flash('void');

		if($this->col->delete($id))
			air::flash('delete','orders_list');
		else
			air::flash('error','orders_list');
	}

	public function ajxLock()
	{
		if($this->col->update_many(json_decode($_POST['ids']), ['locked'=>1],true))
			echo 1;
		exit;	
	}

	public function ajxUnlock()
	{
		if($this->col->update_many(json_decode($_POST['ids']), ['locked'=>0],true))
			echo 1;
		exit;	
	}
}