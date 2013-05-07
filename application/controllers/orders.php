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

class Orders extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/co_model','co');
		$this->load->model('orders/cod_model','cod');
		$this->load->model('partners/partners_model','par');
		$this->load->model('products/products_model','prod');
		$this->load->model('financial/paymentmode_model','pmm');
		$this->load->model('hr/employees_model','emp');
		$this->load->model('regional/postalcode_model','pcode');	
	}
	
	public function index($query_id = 0,$sort_by = 'dateshipped', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = "Налози за Продажба";
		
		//Generate dropdown menu data
		$this->data['postalcodes'] = $this->pcode->generateDropdown();	
		$this->data['customers']     = $this->par->dropdown('id','company');
		$this->data['distributors']  = $this->emp->generateDropdown(['is_distributer' => 1]);
		$this->data['modes_payment'] = $this->pmm->dropdown('id','name');

		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateshipped'     =>'Датум',
			'partner_fk'      =>'Купувач',
			'distributor_fk'  =>'Дистрибутер',
			'payment_mode_fk' =>'Плаќање',
			'dateofentry'     =>'Внес',
			'order_list_id'   =>'Извештај'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'partner_fk'      => $this->input->get('partner_fk'),
			'postalcode_fk'   => $this->input->get('postalcode_fk'),
			'distributor_fk'  => $this->input->get('distributor_fk'),
			'payment_mode_fk' => $this->input->get('payment_mode_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateshipped','partner_fk','distributor_fk','payment_mode_fk',
								'dateofentry','order_list_id');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->co->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("orders/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	public function search()
	{
		$query_array = array(
			'partner_fk'      => $this->input->post('partner_fk'),
			'postalcode_fk'   => $this->input->post('postalcode_fk'),
			'distributor_fk'  => $this->input->post('distributor_fk'),
			'payment_mode_fk' => $this->input->post('payment_mode_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("orders/index/$query_id");
	}
	
	public function insert()
	{	
		if(!$_POST) show_404();

		//Prepare Master Data (Order)
		$master = [
			'order_list_id' => $_POST['order_list_id'],
			'dateshipped'   => $_POST['dateshipped'],
		 	'partner_fk'    => $_POST['partner_fk'],
			'distributor_fk'  => $_POST['distributor_fk'],
			'payment_mode_fk' => $_POST['payment_mode_fk'],
			'inserted_by' => $this->session->userdata('userid')
		];
		
		//Decode Products from JSON to Assosiative Array (Order Details)
		$products = json_decode($_POST['products'],true);

		$this->db->trans_start();
		
		$order_fk = $this->co->insert($master);

		foreach ($products as $product)
		{
			//Inserts all products records into the database
			$this->cod->insert([
				'order_fk'          => $order_fk,
				'prodname_fk'       => $product['id'],
				'quantity'          => $product['quantity'],
				'returned_quantity' => $product['returned_quantity']
			],true); //Skip Validation flag is TRUE
		}

		// $lastRecord = $this->co->select_single($order_fk);
		// $out = [
		// 	'id'          => $lastRecord->id,
		// 	'company'     => $lastRecord->company,
		// 	'payment'     => $lastRecord->name,
		// 	'dateshipped' => $lastRecord->dateshipped,
		// 	'dateofentry' => $lastRecord->dateofentry
		// ];
		//header('Content-Type: application/json');

		$this->db->trans_complete();

		if($this->db->trans_status() === false)
		{
			$this->output->set_status_header(500);
		}
		else
		{
			$this->output->set_status_header(201);
			air::flash('add');
		}

		exit;
	}
	
	public function edit($id = false)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['master'] = $this->co->select_single($id);

		if(!$this->data['master']) air::flash('void');
			
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['master']->locked) air::flash('deny','orders');

		if($_POST)
		{	
			//Defining Validation Rules
			$this->form_validation->set_rules('partner_fk','partner','trim|required');
			$this->form_validation->set_rules('distributor_fk','distributor','trim|required');
			$this->form_validation->set_rules('dateshipped','date shipped','trim|required');
			$this->form_validation->set_rules('comments','comments','trim');
			$this->form_validation->set_rules('payment_mode_fk','payment mode','trim|required');

			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				//If Successfull, runs Model public function
				if($this->co->update($_POST['id'],$_POST))
					air::flash('update','orders');
				else
					air::flash('error','orders');
			}
		}

		//Heading
		$this->data['heading'] = "Корекција на Налог за Продажба";
		
		//Dropdown Menus
		$this->data['customers'] = $this->par->dropdown('id','company');
		$this->data['distributors']  = $this->emp->generateDropdown(['is_distributer' => 1]);
		$this->data['modes_payment'] = $this->pmm->dropdown('id','name');
	}
	
	public function view($id = false)
	{	
		$this->data['master'] = $this->co->select_single($id);

		if(!$this->data['master']) air::flash('void','orders');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->cod->select(['id'=>$id]);

		//Heading
		$this->data['heading'] = "Налог за Продажба";
	}

	public function reportByOrderList($id = '')
	{
		if(!$id) air::flash('void');
		
		$this->data['results'] = $this->co->report(['order_list_id'=>$id]);

		$this->data['order_list_id'] = $id;

		//Dropdown Menus
		$this->data['modes_payment'] = $this->pmm->dropdown('id','name');
		$this->data['customers']     = $this->par->dropdown('id','company');
		$this->data['distributors']  = $this->emp->generateDropdown(['is_distributer' => 1]);
		
		//Heading
		$this->data['heading'] = 'Рипорт на Продажба';

		$this->view = 'orders/report';
	}
	
	public function report()
	{
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('datefrom','date from','trim|required');
			$this->form_validation->set_rules('dateto','date to','trim|required');
			
			if ($this->form_validation->run())
			{
				//Log the report
				$this->input->log_report($_POST);
				
				$this->data['results'] = $this->co->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto'] = $_POST['dateto'];
			}			
		}
		
		//Dropdown Menus
		$this->data['modes_payment'] = $this->pmm->dropdown('id','name');
		$this->data['customers']     = $this->par->dropdown('id','company');
		$this->data['distributors']  = $this->emp->generateDropdown(['is_distributer' => 1]);
		
		//Heading
		$this->data['heading'] = 'Рипорт на Продажба';
	}
	
	public function report_pdf()
	{	
		if(!$_POST) show_404();

		$this->load->helper('dompdf');
		$this->load->helper('file');
		
		$report_data['results']  = $this->co->report($_POST);
		$report_data['datefrom'] = $_POST['datefrom'];
		$report_data['dateto']   = $_POST['dateto'];

		if(strlen($_POST['distributor_fk']))
		{
			$report_data['distributer'] = $this->emp->get($_POST['distributor_fk']);	
		}
		if(strlen($_POST['partner_fk']))
		{
			$report_data['partner'] = $this->par->get($_POST['partner_fk']);	
		}
		if(strlen($_POST['payment_mode_fk']))
		{
			$report_data['payment'] = $this->pmm->get($_POST['payment_mode_fk']);	
		}
		
		if($report_data['results'])
		{
			$html = $this->load->view('orders/report_pdf',$report_data, true);
		
			$file_name = random_string();
			
			header("Content-type: application/pdf");
			header("Content-Disposition: attachment; filename='{$file_name}'");
			
			mkpdf($html,$file_name);
		}
		exit;
	}
	
	public function delete($id = false)
	{
		$order = $this->co->get($id);
		/*
		 * Prevents from editing locked record
		 */
		if($order->locked) air::flash('deny','orders');

		if($this->co->delete($id))
			air::flash('delete','orders');
		else
			air::flash('error','orders');
	}
}