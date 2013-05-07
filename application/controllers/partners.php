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

class Partners extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('partners/partners_model','par');	
		$this->load->model('regional/postalcode_model','pcode');	
	}
    
	public function index($query_id = 0,$sort_by = 'company', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Партнери';
		
		$this->data['postalcodes'] = $this->pcode->generateDropdown();	
		
		//------------
		//Columns which can be sorted by
		$this->data['columns'] = array (
			'id'            =>'Код',	
			'company'       =>'Фирма',
			'contperson'    =>'Контакт Лице',
			'postalcode_fk' => 'Град'
		);

		$this->input->load_query($query_id);
		
		$query_array = [
			'partner_type'  => $this->input->get('partner_type'),
			'postalcode_fk' => $this->input->get('postalcode_fk'),
			'q'             => $this->input->get('q')
		];
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_by_array = array('id','company','contperson','postalcode_fk');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'company';

		//Retreive data from Model
		$temp = $this->par->select($query_array, $sort_by, $sort_order, $this->limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$this->data['pagination'] = 
		paginate("partners/index/{$query_id}/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,6);
		
		$this->data['sort_by']    = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id']   = $query_id;
	}
	
	public function search()
	{
		// (strlen($_POST['q'])) ? $_POST['partner_type'] = '' : '';
		// (strlen($_POST['q'])) ? $_POST['postalcode_fk'] = '' : '';
		// (strlen($_POST['partner_type'])) ? $_POST['q'] = '' : '';
		// (strlen($_POST['postalcode_fk'])) ? $_POST['q'] = '' : '';

		$query_array = array(
			'partner_type'  => $this->input->post('partner_type'),
			'postalcode_fk' => $this->input->post('postalcode_fk'),
			'q'             => $this->input->post('q')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("partners/index/{$query_id}");
	}
	
	public function insert()
	{
		if($_POST)
		{				
			//Defining Validation Rules
			$this->form_validation->set_rules('company','company','trim|required');
			$this->form_validation->set_rules('contperson','contact person','trim');
			$this->form_validation->set_rules('postalcode_fk','city','trim|required');
			$this->form_validation->set_rules('code','code','trim');
			$this->form_validation->set_rules('email','email','trim|valid_email');
			$this->form_validation->set_rules('web','web','trim|valid_website');
			$this->form_validation->set_rules('phone1','phone 1','trim|numeric');
			$this->form_validation->set_rules('phone2','phone 2','trim|numeric');
			$this->form_validation->set_rules('mobile','mobile','trim|numeric');
			$this->form_validation->set_rules('fax','fax','trim|numeric');
			$this->form_validation->set_rules('bank','bank','trim');
			$this->form_validation->set_rules('account_no','account number','trim|numeric');
			$this->form_validation->set_rules('address','address','trim');
			$this->form_validation->set_rules('is_mother','','trim');
			$this->form_validation->set_rules('is_vendor','','trim');
			$this->form_validation->set_rules('is_customer','','trim');
			
			//Check if form has passed validation
			if ($this->form_validation->run())
			{						
				if($this->par->insert($_POST))
					air::flash('add','partners');
				else
					air::flash('error','partners');	
			}
		}
		
		// Generating dropdown menu's
		$this->data['postalcodes'] = $this->pcode->generateDropdown();
		$this->data['customers'] = $this->par->generateDropdown(['is_mother' => 1]);

		//Heading
		$this->data['heading'] = 'Внес на Партнер';
	}
	
	public function edit($id)
	{
		$this->data['partner'] = $this->par->select_single($id);	

		if(!$this->data['partner']) air::flash('void','partners');

		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('company','company','trim|required');
			$this->form_validation->set_rules('contperson','contact person','trim');
			$this->form_validation->set_rules('code','code','trim');
			$this->form_validation->set_rules('email','email','trim|valid_email');
			$this->form_validation->set_rules('web','web','trim|valid_website');
			$this->form_validation->set_rules('phone','phone','trim|numeric');
			$this->form_validation->set_rules('phone2','phone 2','trim|numeric');
			$this->form_validation->set_rules('mobile','mobile','trim|numeric');
			$this->form_validation->set_rules('fax','fax','trim|numeric');
			$this->form_validation->set_rules('bank','bank','trim');
			$this->form_validation->set_rules('account_no','account number','trim|numeric');
			$this->form_validation->set_rules('address','address','trim');
			
			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				//If Successfull, runs Model function	
				if($this->par->update($_POST['id'],$_POST))
					air::flash('update','partners');
				else
					air::flash('error','partners');
			}
		}
		
		// Generating dropdown menu's	
		$this->data['postalcodes'] = $this->pcode->generateDropdown();
		$this->data['customers'] = $this->par->generateDropdown(['is_mother' => 1]);

		//Heading
		$this->data['heading'] = 'Корекција на Партнер';
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Партнер';

		//Load Models
		$this->load->model('orders/co_model','co');
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->par->select_single($id);
		if(!$this->data['master']) air::flash('void','partners');
		/**
		 * If partner is Mother(has subsidiaries),
		 * get all the subsidiaries
		 */
		if($this->data['master']->is_mother == 1)
			$this->data['subs'] = $this->par->select_sub($id);
		/**
		 * If partner is also marked as customer (is_customer==1),
		 * get last 10 sales orders
		 */
		if($this->data['master']->is_customer == 1)
			$this->data['orders'] = $this->co->last_partner_orders($id);	
	}

	/**
	 * Delete partner by provided ID.
	 * @param  integer $id primary_key
	 * @return redirect     redirect wit success/error message
	 */
	public function delete($id)
	{
		$this->data= $this->par->get($id);
		if(!$this->data)
			air::flash('void','partners');
			
		if($this->par->delete($id))
			air::flash('delete','partners');
		else
			air::flash('error','partners');	
	}

	public function ajxAllPartners()
	{
		$this->par->order_by('company');
		$rows = $this->par->get_many_by([
			'is_customer' => 1,
			'is_mother'   => 0
		]);

		$json_array = [];

		foreach ($rows as $row)
		{
			 array_push($json_array,['id'=>$row->id,'name'=>$row->company]); 
		}

		header('Content-Type: application/json');
		echo json_encode($json_array);
		exit;
	}
}