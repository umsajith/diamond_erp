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

class Products extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		//Load Models
		$this->load->model('products/products_model','prod');
		$this->load->model('products/type_model','type');
		$this->load->model('products/category_model','category');
		$this->load->model('warehouses/warehouses_model','warehouse');
		$this->load->model('uom/uom_model','uom');
		$this->load->model('financial/taxrates_model','tr');
	}

	public function index($query_id = 0,$sort_by = 'prodname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Артикли';

		// Generating dropdown menu's
		$this->data['warehouses'] = $this->warehouse->dropdown('id', 'wname');
		$this->data['types']      = $this->type->dropdown('id', 'ptname'); 
		$this->data['categories'] = $this->category->dropdown('id', 'pcname');
		
		//Columns which can be sorted by
		$this->data['columns'] = [
			'prodname'       =>'Назив',
			'ptname_fk'      =>'Тип',
			'pcname_fk'      =>'Категорија',
			'wname_fk'       =>'Магацин',
			'base_unit'      =>'Осн.ЕМ',
			'alert_quantity' =>'Мин.Кол.',
			'retail_price'   =>'МПЦ',
			'whole_price1'   =>'ГПЦ1',
			'commision'      =>'Рабат',
			'tax_rate_fk'    =>'ДДВ'
		];
		
		$this->input->load_query($query_id);
		
		$query_array = array(
			'ptname_fk' => $this->input->get('ptname_fk'),
			'pcname_fk' => $this->input->get('pcname_fk'),
			'wname_fk'  => $this->input->get('wname_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('prodname','ptname_fk','pcname_fk','wname_fk',
								'base_unit','alert_quantity','retail_price','whole_price1',
								'whole_price2','commision','tax_rate_fk');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'prodname';
		
		//Retreive data from Model
		$temp = $this->prod->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("products/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
				
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}
	
	public function search()
	{
		$query_array = array(
			'ptname_fk' => $this->input->post('ptname_fk'),
			'pcname_fk' => $this->input->post('pcname_fk'),
			'wname_fk'  => $this->input->post('wname_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("products/index/{$query_id}");
	}
	
	public function insert()
	{
		//Successful validation insets into the DB
		if($this->prod->insert($_POST)) air::flash('add','products');
		
		// Generating dropdown menu's
		$this->data['warehouses']    = $this->warehouse->dropdown('id','wname');
		$this->data['product_types'] = $this->type->dropdown('id','ptname');
		$this->data['product_cates'] = $this->category->dropdown('id','pcname');
		$this->data['uoms']          = $this->uom->dropdown('id','uname');
		$this->data['tax_rates']     = $this->tr->dropdown('id','rate');

		//Heading
		$this->data['heading'] = 'Нов Артикл';
	}
	
	public function edit($id)
	{
		//Retreives ONE product from the database
		$this->data['product'] = $this->prod->get($id);
		
		//If there is nothing, redirects
		if(!$this->data['product']) air::flash('void','products');
		
		//Proccesses the form with the new updated data
		if($_POST)
		{
			if($this->prod->update($id,$_POST)) air::flash('update','products');
		}
		
		// Generating dropdown menu's
		$this->data['warehouses']    = $this->warehouse->dropdown('id','wname');
		$this->data['product_types'] = $this->type->dropdown('id','ptname');
		$this->data['product_cates'] = $this->category->dropdown('id','pcname');
		$this->data['uoms']          = $this->uom->dropdown('id','uname');
		$this->data['tax_rates']     = $this->tr->dropdown('id','rate');		

		//Heading
		$this->data['heading'] = 'Корекција на Артикл';
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Артикл';

		//Retreives data from MASTER Model
		$this->data['master'] = $this->prod->select_single($id);
		
		if(!$this->data['master']) air::flash('void','products');
	}
	
	public function delete($id)
	{
		if($this->prod->delete($id))
			air::flash('delete','products');
		else
			air::flash('error','products');
	}

	public function ajxGetProducts()
	{
		$this->data = $this->prod->generateDropdown($_GET);
		header('Content-Type: application/json',true);     
		echo json_encode($this->data);
		exit;
	}
	/////////////////
	// DEPRICATED //
	/////////////////
	public function dropdown($type)
	{
		$this->data = $this->prod->get_products($type);
		header('Content-Type: application/json',true);     
		echo json_encode($this->data);
		exit;
	}
}