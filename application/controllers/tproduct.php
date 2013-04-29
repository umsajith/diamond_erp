<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tproduct extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('products/type_model','tpr');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'ptname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Tипови на Артикли';

		$this->data['columns'] = array (	
			'ptname'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('ptname');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'ptname';

		$this->data['results'] = $this->tpr->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->tpr->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$this->data['pagination'] = 
		paginate("tproduct/index/{$sort_by}/{$sort_order}",
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
		$this->data['heading'] = 'Нов Тип на Артикл';

		//Defining Validation Rules
		$this->form_validation->set_rules('ptname','product type name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->tpr->insert($_POST))
				air::flash('add','tproduct');
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
		$this->data['heading'] = 'Корекција на Тип на Артикл';

		$this->data['result'] = $this->tpr->get($id);
		if(!$this->data['result'])
			air::flash('void','tproduct');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('ptname','product type name','trim|required');
				
		if ($this->form_validation->run())
		{
			$this->tpr->update($_POST['id'],['ptname'=>$_POST['ptname']]);
				air::flash('update','tproduct');
		}
	}
	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->tpr->get($id);
		if(!$this->data['result'])
			air::flash('void','tproduct');

		if($this->tpr->delete($this->data['result']->id))
			air::flash('delete','tproduct');
		else
			air::flash('error','tproduct');
	}
}