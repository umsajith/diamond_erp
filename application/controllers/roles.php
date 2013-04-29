<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('acl/roles_model','rl');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Кориснички Групи';

		$this->data['columns'] = array (	
			'name'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('name');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'name';

		$this->data['results'] = $this->rl->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->rl->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$this->data['pagination'] = 
		paginate("roles/index/{$sort_by}/{$sort_order}",
			$this->data['num_rows'],$this->limit,5);

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
		$this->data['heading'] = 'Нова Корисничка Група';

		//Defining Validation Rules
		$this->form_validation->set_rules('name','user group name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->rl->insert($_POST))
				$this->utilities->flash('add','roles');
			else
				$this->utilities->flash('error','roles');
		}

		$this->data['parents'] = $this->rl->dropdown_master();
	}
	/**
	 * Edits entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message     
	 */
	public function edit($id)
	{
		//Heading
		$this->data['heading'] = 'Корекција на Корисничка Група';

		$this->data['result'] = $this->rl->get($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','roles');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('name','user group name','trim|required');
				
		if ($this->form_validation->run())
		{
			if($this->rl->update($_POST['id'],$_POST))
				$this->utilities->flash('update','roles');
			else
				$this->utilities->flash('error','roles');
		}

		$this->data['parents'] = $this->rl->dropdown_master();
	}

	public function view($id)
	{
		//Heading
		$this->data['heading'] = 'Преглед на Корисничка Група';

		$this->data['result'] = $this->rl->get($id);
		
		if(!$this->data['result'])
			$this->utilities->flash('void','roles');

		$this->data['resources'] = $this->Permissions_model->get_resources_by_role_id($id);

		$this->data['dd_permissions'] = ['allow'=>'Allow','deny'=>'Deny'];

		$this->data['dd_resources'] = $this->Resources_model->dropdown_all();
	}

	public function assign_resource()
	{
		$this->form_validation->set_rules('role_id','role','trim|required');
		$this->form_validation->set_rules('resource_id','resource','trim|required');
		$this->form_validation->set_rules('permission','permission','trim|required');

		if($this->form_validation->run())
		{
			$result = $this->Permissions_model->insert_role_resource(
				$_POST['role_id'],$_POST['resource_id'],$_POST['permission']
			);

			if($result)
				$this->utilities->flash('add',"roles/view/{$_POST['role_id']}");
			else
				$this->utilities->flash('error',"roles/view/{$_POST['role_id']}");
		}
		else
			$this->utilities->flash('error',"roles/view/{$_POST['role_id']}");
	}

	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->rl->get($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','roles');
		
		if($this->rl->delete($this->data['result']->id))
			$this->utilities->flash('delete','roles');
		else
			$this->utilities->flash('error','roles');

	}
}