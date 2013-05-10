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
		$this->data['heading'] = uif::lng('app.par_pars');
		
		$this->data['postalcodes'] = $this->pcode->generateDropdown();	
		
		//------------
		//Columns which can be sorted by
		$this->data['columns'] = [
			'id'            => uif::lng('attr.code'),	
			'company'       => uif::lng('attr.company'),
			'contperson'    => uif::lng('attr.contact_person'),
			'postalcode_fk' => uif::lng('attr.city')
		];

		$this->input->load_query($query_id);
		
		$query_array = [
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

		$query_array = [
			'postalcode_fk' => $this->input->post('postalcode_fk'),
			'q'             => $this->input->post('q')
		];	
		$query_id = $this->input->save_query($query_array);
		redirect("partners/index/{$query_id}");
	}
	
	public function insert()
	{
		if($_POST)
		{				
			//Defining Validation Rules
			$this->form_validation->set_rules('company',uif::lng('attr.company'),'trim|required');
			$this->form_validation->set_rules('contperson',uif::lng('attr.contact_person'),'trim');
			$this->form_validation->set_rules('postalcode_fk',uif::lng('attr.city'),'trim|required');
			$this->form_validation->set_rules('email',uif::lng('attr.email'),'trim|valid_email');
			$this->form_validation->set_rules('web',uif::lng('attr.web'),'trim|valid_website');
			$this->form_validation->set_rules('phone1',uif::lng('attr.phone'),'trim|numeric');
			$this->form_validation->set_rules('phone2',uif::lng('attr.phone'),'trim|numeric');
			$this->form_validation->set_rules('mobile',uif::lng('attr.mobile'),'trim|numeric');
			$this->form_validation->set_rules('fax',uif::lng('attr.fax'),'trim|numeric');
			$this->form_validation->set_rules('code','','trim');
			$this->form_validation->set_rules('bank','','trim');
			$this->form_validation->set_rules('account_no','','trim');
			$this->form_validation->set_rules('address','','trim');
			$this->form_validation->set_rules('is_mother','','trim');
			
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

		//Mothers
		$this->data['customers'] = $this->par->generateDropdown(['is_mother' => 1]);

		//Heading
		$this->data['heading'] = uif::lng('app.par_new');
	}
	
	public function edit($id)
	{
		$this->data['partner'] = $this->par->select_single($id);	

		if(!$this->data['partner']) air::flash('void','partners');

		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('company',uif::lng('attr.company'),'trim|required');
			$this->form_validation->set_rules('contperson',uif::lng('attr.contact_person'),'trim');
			$this->form_validation->set_rules('postalcode_fk',uif::lng('attr.city'),'trim|required');
			$this->form_validation->set_rules('email',uif::lng('attr.email'),'trim|valid_email');
			$this->form_validation->set_rules('web',uif::lng('attr.web'),'trim|valid_website');
			$this->form_validation->set_rules('phone1',uif::lng('attr.phone'),'trim|numeric');
			$this->form_validation->set_rules('phone2',uif::lng('attr.phone'),'trim|numeric');
			$this->form_validation->set_rules('mobile',uif::lng('attr.mobile'),'trim|numeric');
			$this->form_validation->set_rules('fax',uif::lng('attr.fax'),'trim|numeric');
			$this->form_validation->set_rules('code','','trim');
			$this->form_validation->set_rules('bank','','trim');
			$this->form_validation->set_rules('account_no','','trim');
			$this->form_validation->set_rules('address','','trim');
			$this->form_validation->set_rules('is_mother','','trim');
			
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

		//Mothers
		$this->data['customers'] = $this->par->generateDropdown(['is_mother' => 1]);

		//Heading
		$this->data['heading'] = uif::lng('app.par_edit');
	}
	
	public function view($id)
	{
		//Heading
		$this->data['heading'] = uif::lng('app.par_par');

		//Load Models
		$this->load->model('orders/co_model','co');
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->par->select_single($id);

		if(!$this->data['master']) air::flash('void','partners');
		/**
		 * If partner is Mother(has subsidiaries),
		 * get all the subsidiaries
		 */
		$this->data['subs'] = $this->par->select_sub($id);
		/**
		 * Get last 10 sales orders
		 */
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

		if(!$this->data) air::flash('void','partners');
			
		if($this->par->delete($id))
			air::flash('delete','partners');
		else
			air::flash('error','partners');	
	}

	public function ajxAllPartners()
	{
		$this->par->order_by('company');
		$rows = $this->par->get_all();

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