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

class Inventory extends MY_Controller {
	
	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('procurement/inventory_model','inv');
		$this->load->model('partners/partners_model','par');
		$this->load->model('products/products_model','prod');
		$this->load->model('products/category_model','cat');
		$this->load->model('hr/employees_model','emp');
    }
	
	public function index()
	{	
		//Heading
		$this->data['heading'] = 'Магацин: Сировини';
		
		//Retreive data from Model
		$this->data['results'] = $this->inv->levels();
	}
	
	public function purchase_orders($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Нарачки';
		
		//Generate dropdown menu data
		$this->data['products'] = $this->prod->generateDropdown(['purchasable'=>1],true);
		$this->data['categories'] = $this->cat->dropdown('id', 'pcname');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateoforder'     =>'Нарачано',
			'prodname_fk'     =>'Артикл',
			'qty_current'     =>'Лагер',
			'quantity'        =>'Количина',
			'partner_fk'      =>'Добавувач',
			'purchase_method' =>'Начин',
			'po_status'       =>'Статус',
			'dateofentry'     =>'Внес'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'pcname_fk'   => $this->input->get('pcname_fk'),
			'partner_fk'  => '',
			'type'        => 'po'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateoforder','prodname_fk','partner_fk','quantity','qty_current',
								'assigned_to','purchase_method','po_status','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];

		//Pagination
		$this->data['pagination'] = 
		paginate("inventory/purchase_orders/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function po_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'pcname_fk'   => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/purchase_orders/{$query_id}");
	}
	
	public function goods_receipts($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Приемници';
				
		//Generate dropdown menu data
		$this->data['products'] = $this->prod->generateDropdown(['purchasable'=>1],true);
		$this->data['categories'] = $this->cat->dropdown('id', 'pcname');
		$this->data['vendors'] = $this->par->generateDropdown();
		
		//Columns which can be sorted by
		$this->data['columns'] = [
			'datereceived'    =>'Примено',
			'prodname_fk'     =>'Артикл',
			'partner_fk'      =>'Добавувач',
			'quantity'        =>'Количина',
			'purchase_method' =>'Начин',
			'price'           =>'Цена(без ДДВ)',	
			'dateoforder'     =>'Нарачано',
			'dateofentry'     =>'Внес'
		];
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'partner_fk'  => $this->input->get('partner_fk'),
			'pcname_fk'   => $this->input->get('pcname_fk'),
			'type'        => 'gr'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('datereceived','prodname_fk','partner_fk','quantity',
								'price','purchase_method','dateoforder','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("inventory/goods_receipts/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function gr_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'partner_fk'  => $this->input->post('partner_fk'),
			'pcname_fk'   => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/goods_receipts/{$query_id}");
	}
	
	public function adjustments($query_id = 0,$sort_by = 'dateofentry', $sort_order = 'desc', $offset = 0)
	{
		//Heading
		$this->data['heading'] = 'Порамнување';
		
		//Generate dropdown menu data
		$this->data['products']   = $this->prod->generateDropdown(['purchasable'=>1],true);
		$this->data['categories'] = $this->cat->dropdown('id', 'pcname');
		
		//Columns which can be sorted by
		$this->data['columns'] = array (
			'dateofentry' =>'Внес',	
			'prodname_fk' =>'Артикл',
			'pcname_fk'   =>'Категорија',
			'quantity'    =>'Количина'
		);
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'prodname_fk' => $this->input->get('prodname_fk'),
			'pcname_fk'   => $this->input->get('pcname_fk'),
			'partner_fk'  => '',
			'type'        => 'adj'
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('prodname_fk','pcname_fk','quantity','dateofentry');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';
		
		//Retreive data from Model
		$temp = $this->inv->select_all($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("inventory/adjustments/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
				
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function adj_search()
	{
		$query_array = array(
			'prodname_fk' => $this->input->post('prodname_fk'),
			'pcname_fk'   => $this->input->post('pcname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("inventory/adjustments/{$query_id}");
	}
	
	public function digg($id, $offset = null)
	{
		//Heading
		$this->data['heading'] = 'Картица';
		/*
		 * If $id is not supplied, or does not exist
		 * redirect to this controllers index
		 */		
		$temp = $this->inv->select_item($id,$this->limit,$offset);
		if(!$temp)
			air::flash('void','inventory');
				
		//Retreive data from Model
		$this->data['product'] = $this->prod->select_single($id);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("inventory/digg/{$id}",
			$this->data['num_rows'],$this->limit,4);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
	}
	
	public function insert_gr()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('partner_fk','vendor','trim|required');
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('purchase_method','purchase method','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('datereceived','date received','trim|required');
		$this->form_validation->set_rules('dateoforder','date of order','trim');
		$this->form_validation->set_rules('dateofexpiration','date of expiration','trim');
		$this->form_validation->set_rules('comments','comments','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{	
			//Inserts a Goods Receipt
			$_POST['type'] = 'gr';
				
			//Inserts into databse and reports outcome
			if($this->inv->insert($_POST))
				air::flash('add','goods_receipts');
			else
				air::flash('error','goods_receipts');
		}
		//Load Partner model for Dropdown creation
		$this->data['vendors'] = $this->par->generateDropdown();

		//Heading
		$this->data['heading'] = 'Внес на Приемница';
	}
	
	public function insert_po()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('dateoforder','date of order','trim');
		$this->form_validation->set_rules('comments','comments','trim');

		//Check if form has been submited
		if ($this->form_validation->run())
		{			
			//Inserts a Purchase Order
			$_POST['type'] = 'po';
			
			//Successful validation
			if($this->inv->insert($_POST))
				air::flash('add','purchase_orders');
			else	
				air::flash('error','purchase_orders');
		}

		$this->data['products'] = $this->prod->generateDropdown(['purchasable'=>1],true);

		//Heading
		$this->data['heading'] = 'Внес на Нарачка';
	}
	
	public function insert_adj()
	{
		//Defining Validation Rules
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		$this->form_validation->set_rules('comments','comment','trim|required');
		$this->form_validation->set_rules('is_use','is use','trim');

		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Inserts an adjustment
			$_POST['type'] = 'adj';
			
			//Successful validation
			if($this->inv->insert($_POST))
				air::flash('add','adjustments');
			else
				air::flash('error','adjustments');
		}

		//Heading
		$this->data['heading'] = 'Внес на Порамнување';
	}
	
	public function edit($page,$id)
	{
		
		//if(!in_array($page,['po','gr','adj']))
		//	$page = 'po';
			
		$this->data['page'] = $page;		
		
		if($page == 'po')
		{
			$heading = 'Нарачка';
			$redirect = 'purchase_orders';
			$this->view = 'inventory/edit_po';
			
			$this->data['employees'] = $this->emp->generateDropdown();
		}	
		if($page == 'gr')
		{
			$heading = 'Приемница';
			$redirect = 'goods_receipts';
			$this->view = 'inventory/edit_gr';

			$this->form_validation->set_rules('partner_fk','vendor','trim|required');
			$this->form_validation->set_rules('quantity','quantity','greater_than[0]|required');
		}
		
		//Retreives ONE product from the database
		$this->data['result'] = $this->inv->select_single($id);

		if(!$this->data['result']) show_404();
					
		//Defining Validation Rules	
		$this->form_validation->set_rules('prodname_fk','product','trim|required');
		$this->form_validation->set_rules('price','price','trim|numeric');
		$this->form_validation->set_rules('dateoforder','date of order','trim');
		$this->form_validation->set_rules('dateofexpiration','date of expiration','trim');
		$this->form_validation->set_rules('comments','comment','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Successful validation
			if($this->inv->update($id,$_POST))
				air::flash('update',$redirect);
			else	
				air::flash('error',$redirect);
		}
		
		//Heading
		$this->data['heading'] = 'Корекција на ' . $heading;
		
		$this->data['vendors'] = $this->par->generateDropdown();
	}
	
	//AJAX - Marks the Purchase Order into Good Receipts and adds to inventory
	public function receive_po() 
	{
		if($this->inv->update_many(json_decode($_POST['ids']),['type'=>'gr'],true))
			echo 1;	
		exit;
	}
	
	public function view($page, $id)
	{		
		if(!in_array($page, array('po','gr','adj')))
			$page = 'po';
			
		if($page == 'po')
		{
			$heading = 'Нарачка';
			$redirect = 'purchase_orders';
			$this->view = 'inventory/purchase_order';
		}
		if($page == 'gr')
		{
			$heading = 'Приемница';
			$redirect = 'goods_receipts';
			$this->view = 'inventory/goods_receipt';
		}
		if($page == 'adj')
		{
			$heading = 'Порамнување';
			$redirect = 'adjustments';
			$this->view = 'inventory/adjustment';
		}
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->inv->select_single($id);
		if(!$this->data['master'])
			air::flash('void',$redirect);

		//Heading
		$this->data['heading'] = $heading;
	}
	
	public function delete($page = 'po',$id)
	{
		if(!in_array($page, array('po','gr','adj')))
			$page = 'po';	

		if($page == 'po')
			$redirect = 'purchase_orders';
		if($page == 'gr')
			$redirect = 'goods_receipts';
		if($page == 'adj')
			$redirect = 'adjustments';
			
		//Retreives data from MASTER Model
		$this->data['master'] = $this->inv->select_single($id);
		if(!$this->data['master'])
			air::flash('void',$redirect);
			
		if($this->inv->delete($id))
			air::flash('delete',$redirect);
		else
			air::flash('error',$redirect);
	}	
}