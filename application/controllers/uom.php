<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		$this->data['heading'] = 'Единици Мерки';

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
		$config['base_url'] = site_url("uom/index/$sort_by/$sort_order");
		$config['total_rows'] = $this->data['num_rows'];
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = 5;
		$config['num_links'] = 3;
		$config['first_link'] = 'Прва';
		$config['last_link'] = 'Последна';
			$this->pagination->initialize($config);
		
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
		$this->data['heading'] = 'Внес на ЕМ';

		//Defining Validation Rules
		$this->form_validation->set_rules('uname','UOM name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->uom->insert($_POST))
				$this->utilities->flash('add','uom');
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
		$this->data['heading'] = 'Корекција на ЕМ';

		$this->data['result'] = $this->uom->get($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','uom');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('uname','UOM name','trim|required');
				
		if ($this->form_validation->run())
		{
			$this->uom->update($_POST['id'],array('uname'=>$_POST['uname']));
				$this->utilities->flash('update','uom');
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
			$this->utilities->flash('void','uom');
		
		if($this->uom->delete($this->data['result']->id))
			$this->utilities->flash('delete','uom');
		else
			$this->utilities->flash('error','uom');
	}
}