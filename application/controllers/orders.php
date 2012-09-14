<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//Load Models
		$this->load->model('orders/Co_model');
		$this->load->model('orders/Cod_model');
		$this->load->model('partners/Partners_model');
	}
	
	function index($query_id = 0,$sort_by = 'dateshipped', $sort_order = 'desc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = "Извештаи";
		
		//Generate dropdown menu data
		$this->data['customers'] = $this->Partners_model->dropdown('customers');
		$this->data['postalcodes'] = $this->utilities->get_postalcodes();	
		$this->data['distributors'] = $this->utilities->get_distributors();
		$this->data['modes_payment'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payment_modes','- Плаќање -');
		
		$limit = 25;
		
		//Columns which can be sorted by
		$this->data['columns'] = array (	
			'dateshipped'=>'Датум',
			'partner_fk'=>'Купувач',
			'distributor_fk'=>'Дистрибутер',
			'payment_mode_fk'=>'Плаќање',
			'dateofentry'=>'Внес',
			'оstatus'=>'Статус'
		);

		$this->input->load_query($query_id);
		
		$query_array = array(
			'partner_fk' => $this->input->get('partner_fk'),
			'postalcode_fk' => $this->input->get('postalcode_fk'),
			'distributor_fk' => $this->input->get('distributor_fk'),
			'payment_mode_fk' => $this->input->get('payment_mode_fk')
		);
		
		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('dateshipped','partner_fk','distributor_fk','payment_mode_fk',
								'dateofentry','оstatus');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'dateofentry';

		//Retreive data from Model
		$temp = $this->Co_model->select($query_array, $sort_by, $sort_order, $limit, $offset);
		
		//Results
		$this->data['results'] = $temp['results'];
		//Total Number of Rows in this Table
		$this->data['num_rows'] = $temp['num_rows'];
		
		//Pagination
		$config['base_url'] = site_url("orders/index/$query_id/$sort_by/$sort_order");
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
			'partner_fk' => $this->input->post('partner_fk'),
			'postalcode_fk' => $this->input->post('postalcode_fk'),
			'distributor_fk' => $this->input->post('distributor_fk'),
			'payment_mode_fk' => $this->input->post('payment_mode_fk')
		);	
		$query_id = $this->input->save_query($query_array);
		redirect("orders/index/$query_id");
	}
	
	function insert()
	{	
		//Defining Validation Rules
		$this->form_validation->set_rules('partner_fk','partner','trim|required');
		$this->form_validation->set_rules('dateshipped','date shipped','trim|required');
		$this->form_validation->set_rules('distributor_fk','distributer','trim|required');
		$this->form_validation->set_rules('payment_mode_fk','payment mode','trim');
		$this->form_validation->set_rules('comments','comments','trim');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			//Inserts Master details
			$master = array('dateshipped'=>$_POST['dateshipped'],
						 	'partner_fk'=>$_POST['partner_fk'],
							'distributor_fk'=>$_POST['distributor_fk'],
							'payment_mode_fk'=>$_POST['payment_mode_fk'],
							'comments'=>$_POST['comments'],
							'inserted_by'=>$this->session->userdata('userid'));
			
			$order_fk = $this->Co_model->insert($master);
			
			if($order_fk)
			{
				//Decode the JSON object int Ass.array and loop through detail records
				foreach (json_decode($_POST['components'],true) as $detail)
				{
					//Inserts all Detail records into the database
					$this->Cod_model->insert(array(
							'order_fk'=>$order_fk,
							'prodname_fk'=>$detail['id'],
							'quantity'=>$detail['quantity'],
							'returned_quantity'=>$detail['returned_quantity']));
				}
				$this->utilities->flash('add','',false);
				echo 1;
				exit;
			}
			else
			{
				$this->utilities->flash('error','',false);
				exit;	
			}	
		}

		//Dropdown Menus
		$this->data['customers'] = $this->Partners_model->dropdown('customers');
		$this->data['cities'] = $this->utilities->get_postalcodes();
		$this->data['distributors'] = $this->utilities->get_distributors();
		$this->data['modes_payment'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payment_modes','- Плаќање -');	

		//Heading
		$this->data['heading'] = "Внес на Извештај";
	}
	
	function edit($id = false)
	{
		/*
		 * Retreives the record from the database, if
		 * does not exists, reports void error and redirects
		 */
		$this->data['master'] = $this->Co_model->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders');
			
		/*
		 * Prevents from editing locked record
		 */
		if($this->data['master']->locked == 1)
			$this->utilities->flash('deny','orders');

		if($_POST)
		{	
			//Defining Validation Rules
			$this->form_validation->set_rules('partner_fk','partner','trim|required');
			$this->form_validation->set_rules('dateshipped','date shipped','trim|required');
			$this->form_validation->set_rules('comments','comments','trim');
			$this->form_validation->set_rules('payment_mode_fk','payment mode','trim|required');

			//Check if updated form has passed validation
			if ($this->form_validation->run())
			{
				//If Successfull, runs Model function
				if($this->Co_model->update($_POST['id'],$_POST))
					$this->utilities->flash('update','orders');
				else
					$this->utilities->flash('error','orders');
			}
		}

		//Heading
		$this->data['heading'] = "Корекција на Извештај";
			
		$this->load->model('products/Products_model');
		
		//Retreives data from DETAIL Model
		$this->data['details'] = $this->Cod_model->select(array('id'=>$id));
		
		//Dropdown Menus
		$this->data['customers'] = $this->Partners_model->dropdown('customers');
		$this->data['products'] = $this->Products_model->get_products('salable',false,true);
		$this->data['distributors'] = $this->utilities->get_distributors();
		$this->data['modes_payment'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payment_modes','- Плаќање -');
	}
	
	//AJAX - Locks Orders
	function lock()
	{
		$data['ids'] = json_decode($_POST['ids']);
		
		if($this->Co_model->lock($data))
			echo 1;

		exit;	
	}

	//AJAX - Unlock Orders
	function unlock()
	{
		$data['ids'] = json_decode($_POST['ids']);
		
		if($this->Co_model->unlock($data))
			echo 1;

		exit;	
	}
	
	//AJAX - Adds New Product in Order Details
	function add_product()
	{
		$data['order_fk'] = $_POST['order_fk'];
		$data['prodname_fk'] = $_POST['prodname_fk'];
		$data['quantity'] = $_POST['quantity'];

		if($this->Cod_model->insert($data))
			echo 1;
			
		exit;		
	}
	
	//AJAX - Removes Products from an Order
	function remove_product()
	{
		if($this->Cod_model->delete(json_decode($_POST['id'])))
			echo json_encode(array('message'=>'Производот е успешно избришан'));
		
		exit;
	}
	
	//AJAX - Edits the Quantity of Products from an Order
	function edit_qty()
	{
		$id = json_decode($_POST['id']);
		$data['quantity'] = json_decode($_POST['quantity']);
		
		if($this->Cod_model->update($id,$data))
		{
			echo json_encode($data['quantity']);
			exit;
		}
		else
			exit;	
	}
	
	//AJAX - Edits the Returned Quantity of Products from an Order
	function edit_ret_qty()
	{	
		$id = json_decode($_POST['id']);
		$data['returned_quantity'] = json_decode($_POST['returned_quantity']);
		
		if($this->Cod_model->update($id,$data))
		{
			echo json_encode($data['returned_quantity']);
			exit;
		}
		else
			exit;		
	}
	
	function view($id = false)
	{	
		$this->data['master'] = $this->Co_model->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders');

		//Retreives data from DETAIL Model
		$this->data['details'] = $this->Cod_model->select(array('id'=>$id));

		//Heading
		$this->data['heading'] = "Преглед на Извештај";
	}
	
	public function report()
	{
		$this->data['submited'] = 0;
		
		if($_POST)
		{
			//Defining Validation Rules
			$this->form_validation->set_rules('datefrom','date from','trim|required');
			$this->form_validation->set_rules('dateto','date to','trim|required');
			
			if ($this->form_validation->run())
			{
				$this->data['results'] = $this->Co_model->report($_POST);
				$this->data['datefrom'] = $_POST['datefrom'];
				$this->data['dateto'] = $_POST['dateto'];
				$this->data['submited'] = 1;	
			}			
		}
		
		//Dropdown Menus
		$this->data['distributors'] = $this->utilities->get_distributors();
		$this->data['modes_payment'] = $this->utilities->get_dropdown('id', 'name','exp_cd_payment_modes','- Плаќање -');
		$this->data['customers'] = $this->Partners_model->dropdown('customers');
		
		//Heading
		$this->data['heading'] = 'Рипорт на Продажба';
	}
	
	public function report_pdf()
	{	
		if($_POST)
		{
			$this->load->helper('dompdf');
			$this->load->helper('file');
			
			$report_data['results'] = $this->Co_model->report($_POST);
			$report_data['datefrom'] = $_POST['datefrom'];
			$report_data['dateto'] = $_POST['dateto'];
			
			if(strlen($_POST['distributor_fk']))
			{
				$this->load->model('hr/Employees_model');
				$report_data['distributer'] = $this->Employees_model->select_single($_POST['distributor_fk']);	
			}
			if(strlen($_POST['partner_fk']))
			{
				$this->load->model('partners/Partners_model');
				$report_data['partner'] = $this->Partners_model->select_single($_POST['partner_fk']);	
			}
			if(strlen($_POST['payment_mode_fk']))
			{
				$report_data['payment'] = $this->utilities->get_single($_POST['payment_mode_fk'],'exp_cd_payment_modes');	
			}
				
			
			if($report_data['results'])
			{
				$html = $this->load->view('orders/report_pdf',$report_data, true);
			
				$file_name = random_string();
				
				header("Content-type: application/pdf");
				header("Content-Disposition: attachment; filename='{$file_name}'");
				
				pdf_create($html,$file_name);
				exit;
			}
			else
				exit;
		}
	}
	
	function delete($id = false)
	{
		$this->data['master'] = $this->Co_model->select_single($id);
		if(!$this->data['master'])
			$this->utilities->flash('void','orders');

		if($this->Co_model->delete($id))
			$this->utilities->flash('delete','orders');
		else
			$this->utilities->flash('error','orders');
	}
}