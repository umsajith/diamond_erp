<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cproduct extends MY_Controller {

	protected $limit = 25;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('products/cproduct_model','cpr');
	}
    
    /**
     * Retreives whole list of entries
     * @param  string  $sort_by    default sorting filed
     * @param  string  $sort_order default sort order
     * @param  integer $offset
     */
	public function index($sort_by = 'pcname', $sort_order = 'asc', $offset = 0)
	{	
		//Heading
		$this->data['heading'] = 'Категории на Артикли';

		$this->data['columns'] = array (	
			'pcname'=>'Назив'
		);

		//Validates Sort by and Sort Order
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_by_array = array('pcname');
		$sort_by = (in_array($sort_by, $sort_by_array)) ? $sort_by : 'pcname';

		$this->data['results'] = $this->cpr->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->get_all();

		$this->data['num_rows'] = $this->cpr->limit($this->limit, $offset)
									->order_by($sort_by,$sort_order)->count_all();

		//Pagination
		$config['base_url'] = site_url("cproduct/index/$sort_by/$sort_order");
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
		$this->data['heading'] = 'Внес на Категорија на Артикли';

		//Defining Validation Rules
		$this->form_validation->set_rules('pcname','product type name','trim|required');
		
		//Check if form has been submited
		if ($this->form_validation->run())
		{
			if($this->cpr->insert($_POST))
				$this->utilities->flash('add','cproduct');
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
		$this->data['heading'] = 'Корекција на Категорија на Артикли';

		$this->data['result'] = $this->cpr->get($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','cproduct');
	
		//Defining Validation Rules
		$this->form_validation->set_rules('pcname','product type name','trim|required');
				
		if ($this->form_validation->run())
		{
			$this->cpr->update($_POST['id'],array('pcname'=>$_POST['pcname']));
				$this->utilities->flash('update','cproduct');
		}
	}
	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$this->data['result'] = $this->cpr->get($id);
		if(!$this->data['result'])
			$this->utilities->flash('void','cproduct');

		if($this->cpr->delete($this->data['result']->id))
			$this->utilities->flash('delete','cproduct');
		else
			$this->utilities->flash('error','cproduct');
	}
	
	// public function grid()
	// {
	// 	$options = array(
	// 		'sortname' => $_POST['sortname'],
	// 		'sortorder' => $_POST['sortorder'],
	// 		'qtype' => $_POST['qtype'],
	// 		'query' => $_POST['query'],
	// 		'limit' => $_POST['rp'],
	// 		'offset' => ($_POST['page']-1)*$_POST['rp']
	// 	);
	
	// 	$results = $this->Cproduct_model->select($options);
	// 	$responce->total =$results['count'];
 //        $responce->page = $_POST['page'];
	// 	$i = 0;
	// 	foreach($results['results'] as $row) 
	// 	{
	// 		$responce->rows[$i]['id']=$row->id;
	// 		$responce->rows[$i]['cell']=array($row->id,$row->pcname);
	// 		$i++;
	// 	}  

 //      	header('Content-Type: application/json',true);     
 //      	echo json_encode($responce);
 //      	exit;
	// }
}