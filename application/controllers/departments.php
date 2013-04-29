<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departments extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('hr/department_model','dpt');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'department', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Сектори';

		$this->data['columns'] = array (	
			'department'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('department');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'department';

		$this->data['results'] = $this->dpt->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->dpt->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$this->data['pagination'] = 
		paginate("departments/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5);
		
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
		$this->data['heading'] = 'Нов на Сектор';

		//Defining Validation Rules
		$this->form_validation->set_rules('department','department','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->dpt->insert($_POST))
				air::flash('add','departments');
			else
				air::flash('error','departments');
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
		$this->data['heading'] = 'Корекција на Сектор';

		$this->data['result'] = $this->dpt->get($id);
		if(!$this->data['result'])
			air::flash('void','departments');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('department','department','trim|required');
				
		if ($this->form_validation->run())
		{
			if($this->dpt->update($_POST['id'],$_POST))
				air::flash('update','departments');
			else
				air::flash('error','departments');
		}
	}
	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->dpt->get($id);
		if(!$this->data['result'])
			air::flash('void','departments');
		
		if($this->dpt->delete($this->data['result']->id))
			air::flash('delete','departments');
		else
			air::flash('error','departments');

	}
}