<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Joborders_model extends MY_Model {
	
	protected $_table = 'exp_cd_job_orders';

	protected $_location;

	public $before_create = ['setNull','setDefaults'];

	public $before_update = ['setNull','processUpdate'];

	public function __construct()
	{
		parent::__construct();

		/**
		 * Stores default location ID from session data
		 * @var integer
		 */
		$this->_location = $this->session->userdata('location');
    }
	
	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects results by supplied criteria----------------------------------------------------------------
		$this->db->select('j.*,t.taskname,e.fname,e.lname,u.uname');
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');

		/*
		 * Search Filters
		 */
		if(strlen($query_array['task_fk']))
			$this->db->where_in('j.task_fk',$query_array['task_fk']);
		if(strlen($query_array['assigned_to']))
			$this->db->where_in('j.assigned_to',$query_array['assigned_to']);
		if(strlen($query_array['shift']))
			$this->db->where_in('j.shift',$query_array['shift']);

		if($sort_by == 'assigned_to') $sort_by = 'e.fname';
		if($sort_by == 'task_fk') $sort_by = 't.taskname';

		/**
		 * If user has specific location set,
		 * display job orders for that location only!
		 */
		if($this->_location) $this->db->where('j.location_id',$this->_location);
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$data['results'] = $this->db->get($this->_table.' AS j')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		
		if(strlen($query_array['task_fk']))
			$this->db->where_in('task_fk',$query_array['task_fk']);
		if(strlen($query_array['assigned_to']))
			$this->db->where_in('assigned_to',$query_array['assigned_to']);
		if(strlen($query_array['shift']))
			$this->db->where_in('shift',$query_array['shift']);

		/**
		 * If user has specific location set,
		 * display job orders for that location only!
		 */
		if($this->_location) $this->db->where('location_id',$this->_location);
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_by_payroll($payroll_id)
	{
		$this->db->select('j.*,t.*,e.fname,e.lname,u.uname');
		
		$this->db->select_sum('j.final_quantity');
		
		$this->db->select('count(j.id) as count');
		
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('j.payroll_fk',$payroll_id);
		$this->db->where('j.locked',1);
		
		$this->db->group_by('j.assigned_to');
		$this->db->group_by('j.task_fk');
		
		return $this->db->get($this->_table.' AS j')->result();
	}
	
	public function select_single($id)
	{
		$this->db->select("j.*,t.taskname,e.id as eid,e.fname,e.lname,u.uname, 
			CONCAT(em.fname,' ',em.lname) AS operator",false);
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_employees AS em','em.id = j.assigned_by','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');

		$this->db->where('j.id',$id);
		
		return $this->db->get($this->_table.' AS j')->row();
	}

	public function get_last()
	{
		$this->db->select('j.*,t.taskname,e.fname,e.lname,u.uname');
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');

		/**
		 * If user has specific location set,
		 * display job orders for that location only!
		 */
		if($this->_location) $this->db->where('j.location_id',$this->_location);
		
		$this->db->from($this->_table.' AS j');
		
		return $this->db->get()->last_row();
	}
	
	public function get_task($id)
	{
		$this->db->select('task_fk');
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get($this->_table);
		
		if($query)
			return $query->row();
		
		return false;	
	}
	
	public function payroll($options=array())
	{
		$this->db->select('jo.id,jo.final_quantity,jo.assigned_to,
							t.taskname,t.rate_per_unit,e.fname,e.lname,u.uname');
		
		$this->db->select_sum('jo.final_quantity');
		
		$this->db->select('count(jo.id) as count');
		
		$this->db->join('exp_cd_tasks AS t','t.id = jo.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = jo.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('jo.assigned_to',$options['assigned_to']);
		$this->db->where('jo.datedue >=',$options['datefrom']);
		$this->db->where('jo.datedue <=',$options['dateto']);
		$this->db->where('jo.is_completed',1);
		$this->db->where('jo.locked',0);

		$this->db->group_by('jo.assigned_to');
		$this->db->group_by('jo.task_fk');
		
		return $this->db->get($this->_table.' AS jo')->result();
	}
	
	public function report($options = array())
	{
		$this->db->select('
			SUM(jo.final_quantity) as sum,
			SUM(jo.defect_quantity) as sum_defect,
			AVG(jo.final_quantity) as avg,
			MIN(jo.final_quantity) as min,
			MAX(jo.final_quantity) as max,
			COUNT(jo.final_quantity) as count,
			t.taskname,u.uname');
		
		$this->db->join('exp_cd_tasks AS t','t.id = jo.task_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('jo.datedue >=',$options['datefrom']);
		$this->db->where('jo.datedue <=',$options['dateto']);
		
		if(strlen($options['assigned_to']))
			$this->db->where('jo.assigned_to',$options['assigned_to']);
		if(strlen($options['task_fk']))
			$this->db->where('jo.task_fk',$options['task_fk']);
		if(isset($options['shift']))
		{
			if(count($options['shift']) < 3)
				$this->db->where_in('jo.shift',$options['shift']);
		}
		
		$this->db->where('jo.is_completed',1);
		
		$this->db->group_by('jo.task_fk');
		
		$this->db->order_by('t.taskname','asc');
		
		return $this->db->get($this->_table.' AS jo')->result();
	}

	////////////////
	// OBSERVERS //
	////////////////
	protected function setNull($row)
	{
		// is_completed is done only through Ajax Call,
		// so if it not set, then process the values within -
		// not overwrtiting them witn NULL
		if(!isset($row['is_completed']))
		{
			if(empty($row['work_hours'])) $row['work_hours'] = null;
			if(empty($row['defect_quantity'])) $row['defect_quantity'] = null;	
		}

		return $row;
	}

	protected function setDefaults($row)
	{
		$row['final_quantity'] = $row['assigned_quantity'];

		$row['assigned_by'] = $this->session->userdata('userid');

		if($this->_location) $row['location_id'] = $this->_location;

		return $row;
	}

	protected function processUpdate($row)
	{
		/**
		 * @todo Add updated_by DB field, and get from session before update
		 */
		/**
		 * By default, final quantity is assigned quanity,
		 * since as requested, job orders are not inserted
		 * prior to completion.
		 */
		if(!isset($row['is_completed']))
		{
			$row['final_quantity'] = $row['assigned_quantity'];
			$row['is_completed'] = 0;
		} 

		return $row;
	}

	// protected function deleteInventoryEntries($row)
	// {
	// 	$this->db->delete('exp_cd_inventory',['job_order_fk'=>$row['id']]);
	// 	return $row;
	// }
}