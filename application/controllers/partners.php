<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('partners/Partners_model');	
	}
    
	function index($query_id = 0,$sort_by = 'company', $sort_order = 'asc', $offset = 0)
	{	

		//Heading
		$this->data['heading'] = 'Партнери';
		
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();	
		
		//Limit Per Page
		$limit = 25;
		
		//------------
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'company'=>'Фирма',
			'contperson'=>'Контакт Лице',
			'postalcode_fk' => 'Град'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'partner_type' => $this->input->get('partner_type'),
			'postalcode_fk' => $this->input->get('postalcode_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_by_array = array('company','contperson','postalcode_fk');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'company';

		//Retreive data from Model
		$temp = $this->Partners_model->select($query_array, $sort_by, $sort_order, $limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("partners/index/$query_id/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $limit;
		$config['uri_segment'] = 6;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
		$this->data['pagination'] = $this->pagination->create_links(); 
		
		$this->data['sort_by'] = $sort_by;
		$this->data['sort_order'] = $sort_order;
		$this->data['query_id'] = $query_id;
	}
	
	function search()
	{
		$query_array = array(
			'partner_type' => $this->input->post('partner_type'),
			'postalcode_fk' => $this->input->post('postalcode_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("partners/index/$query_id");
	}
	
	function insert()
	{
		if($_POST)
		{
			//Load Validation Library
			$this->load->library('form_validation');
					
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
			
			//Cheks if the Partner has been created through AJAX post
			$ajax = false;
			if(isset($_POST['ajax']) && $_POST['ajax'] == 1)
			{
				$ajax = true;
				unset($_POST['ajax']);
			}
			
			//Check if form has passed validation
			if ($this->form_validation->run())
			{						
				//Returns TRUE(id) if insertion successfull
				if($this->Partners_model->insert($_POST))
				{
					if($ajax)
					{
						echo 1;
						exit;
					}
						
					$this->utilities->flash('add','partners');
				}
				else
				{
					if($ajax)
						exit;
						
					$this->utilities->flash('error','partners');
				}	
			}
			else
			{
				if($ajax)
					exit;
			}
		}
		
		// Generating dropdown menu's
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();	
		$this->data['customers'] = $this->Partners_model->dropdown('customers',true);

		//Heading
		$this->data['heading'] = 'Внес на Партнер';
	}
	
	function edit($id = false)
	{
		$this->data['partner'] = $this->Partners_model->select_single($id);	
		if(!$this->data['partner'])
			$this->utilities->flash('void','partners');

		if($_POST)
		{
			//Load Validation Library
			$this->load->library('form_validation');
		
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
				if($this->Partners_model->update($_POST['id'],$_POST))
					$this->utilities->flash('update','partners');
				else
					$this->utilities->flash('error','partners');
			}
		}
		
		// Generating dropdown menu's	
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();
		$this->data['customers'] = $this->Partners_model->dropdown('customers',true);

		//Heading
		$this->data['heading'] = 'Корекција на Партнер';
	}
	
	function view($id = false)
	{
		//Load Models
		$this->load->model('orders/Co_model');
		
		//Retreives data from MASTER Model
		$this->data['master'] = $this->Partners_model->select_single($id);
		
		if(!$this->data['master'])
			$this->utilities->flash('void','partners');
		
		if($this->data['master']->is_customer == 1)
			$this->data['orders'] = $this->Co_model->last_partner_orders($id);
			
		//Heading
		$this->data['heading'] = 'Партнер';
	}
	
	function delete($id = false)
	{
		$this->data= $this->Partners_model->select_single($id);
		if(!$this->data)
			$this->utilities->flash('void','partners');
			
		if($this->Partners_model->delete($id))
			$this->utilities->flash('delete','partners');
		else
			$this->utilities->flash('error','partners');	
	}
	
	function live_search() 
	{
		if(isset($_POST['q']) && $_POST['q']!='')
			$pid = $_POST['q'];
		else
			return false;
		
		$rows = $this->Partners_model->search($pid);
		
		echo "<ul id=\"autocomplete\">";
		foreach($rows as $row)
		{
			echo "<li><a href=\"#\" title=\"".$row['company']."\" id=\"".$row['id']."\" onClick=fill(this.id,this.title);>" . $row['company'] . "</a></li>";	
		}
		echo "</ul>";
	}
}