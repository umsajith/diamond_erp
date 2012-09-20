<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Joborders_model extends CI_Model {
	
	//Database table of the Model
	protected $table = 'exp_cd_job_orders';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects results by supplied criteria----------------------------------------------------------------
		$this->db->select('j.*,t.taskname,e.fname,e.lname,u.uname');
		$this->db->from($this->table.' AS j');
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
		if(strlen($query_array['job_order_status']))
			$this->db->where_in('j.job_order_status',$query_array['job_order_status']);
		if(strlen($query_array['shift']))
			$this->db->where_in('j.shift',$query_array['shift']);
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		$this->db->from($this->table);
		
		if(strlen($query_array['task_fk']))
			$this->db->where_in('task_fk',$query_array['task_fk']);
		if(strlen($query_array['assigned_to']))
			$this->db->where_in('assigned_to',$query_array['assigned_to']);
		if(strlen($query_array['job_order_status']))
			$this->db->where_in('job_order_status',$query_array['job_order_status']);
		if(strlen($query_array['shift']))
			$this->db->where_in('shift',$query_array['shift']);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_by_payroll($payroll_id)
	{
		$this->db->select('j.*,t.*,e.fname,e.lname,u.uname');
		
		$this->db->select_sum('j.final_quantity');
		
		$this->db->select('count(j.id) as count');
		
		$this->db->from($this->table.' AS j');
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('j.payroll_fk',$payroll_id);
		$this->db->where('j.locked',1);
		
		$this->db->group_by('j.assigned_to');
		$this->db->group_by('j.task_fk');
		
		return $this->db->get()->result();
	}
	
	function select_single($id)
	{
		$this->db->select('j.*,t.taskname,e.id as eid,e.fname,e.lname,u.uname');
		$this->db->from($this->table.' AS j');
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');

		$this->db->where('j.id',$id);
		
		return $this->db->get()->row();
	}
	
	function get_last()
	{
		$this->db->select('j.*,t.taskname,e.fname,e.lname,u.uname');
		$this->db->join('exp_cd_tasks AS t','t.id = j.task_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = j.assigned_to','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->from('exp_cd_job_orders AS j');
		
		return $this->db->get()->last_row();
	}
	
	function insert ($data = array())
	{
		$data['job_order_status'] = 'pending';
		
		$data['assigned_by'] = $this->session->userdata('userid');

		if(!strlen($data['work_hours']))
			 $data['work_hours'] = null;
		if(!strlen($data['final_quantity']))
			 $data['final_quantity'] = null;
		if(!strlen($data['defect_quantity']))
			 $data['defect_quantity'] = null;
			 
		$this->db->insert($this->table,$data);
		
		return $this->db->insert_id();
	}
	
	function update($id,$data = array())
	{
		/*
		 * Checks whats the Job Order Status Update,
		 * and sets other values accordingly
		 */
		if(strlen($data['job_order_status']))
		{
			switch ($data['job_order_status']) 
			{
			    case $data['job_order_status']=='completed':
					if(strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Updated Final Qty.
			    		 */
			    		$data['is_completed'] = 1;
						$data['datecompleted'] = mdate('%Y-%m-%d');
						$data['job_order_status'] = 'completed';
						break;
			    	}
			    	if(strlen($data['final_quantity']) AND !$this->has_final($id))
			    	{
			    		/*
			    		 * Inserted Final Qty.
			    		 */
			    		$data['is_completed'] = 1;
						$data['datecompleted'] = mdate('%Y-%m-%d');
						$data['job_order_status'] = 'completed';
						break;
			    	}
					if(!strlen($data['final_quantity']) AND !$this->has_final($id))
			    	{
			    		/*
			    		 * Final Qty not supplied by Job Order completed,
			    		 * Final Qty set to Assigned Qty.
			    		 */
			    		$data['is_completed'] = 1;
						$data['datecompleted'] = mdate('%Y-%m-%d');					
						$data['final_quantity'] = $data['assigned_quantity'];
						$data['job_order_status'] = 'completed';
						break;
			    	}
			    	if(!strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Deleted Final Qty.
			    		 */
			    		$data['is_completed'] = 0;
						$data['datecompleted'] = null;
						$data['final_quantity'] = null;
						$data['job_order_status'] = 'canceled';
			    		break;
			    	}
			    case $data['job_order_status']=='pending':
			    	if(strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Status changed from Completed to
			    		 * Pending
			    		 */
			    		$data['is_completed'] = 0;
			    		$data['datecompleted'] = null;
			    		$data['final_quantity'] = null;
			    		$data['job_order_status'] = 'pending';
			    		break;
			    	}
					if(!strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Deleted Final Qty.
			    		 */
			    		$data['is_completed'] = 0;
						$data['datecompleted'] = null;
						$data['final_quantity'] = null;
						$data['job_order_status'] = 'pending';
			    		break;
			    	}
					if(strlen($data['final_quantity']) AND !$this->has_final($id))
			    	{
			    		/*
			    		 * Inserted Final Qty.
			    		 */
			    		$data['is_completed'] = 1;
						$data['datecompleted'] = mdate('%Y-%m-%d');			
						$data['job_order_status'] = 'completed';
						break;
			    	}
				case $data['job_order_status']=='canceled':
			    	if(strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Status changed from Completed to
			    		 * Canceled
			    		 */
			    		$data['is_completed'] = 0;
			    		$data['datecompleted'] = null;
			    		$data['final_quantity'] = null;
			    		$data['job_order_status'] = 'canceled';
			    		break;
			    	}
					if(!strlen($data['final_quantity']) AND $this->has_final($id))
			    	{
			    		/*
			    		 * Deleted Final Qty.
			    		 */
			    		$data['is_completed'] = 0;
						$data['datecompleted'] = null;
						$data['final_quantity'] = null;
						$data['job_order_status'] = 'canceled';
			    		break;
			    	}
					if(strlen($data['final_quantity']) AND !$this->has_final($id))
			    	{
			    		/*
			    		 * Inserted Final Qty.
			    		 */
			    		$data['is_completed'] = 1;
						$data['datecompleted'] = mdate('%Y-%m-%d');			
						$data['job_order_status'] = 'completed';
						break;
			    	}
			}	
		}
		
		/*
		 * If Defect Qty is not set,
		 * sets it to NULL
		 */
		if(!strlen($data['defect_quantity']))
			$data['defect_quantity'] = null;
		/*
		 * If Work Hrs is not set,
		 * sets it to NULL
		 */		
		if(!strlen($data['work_hours']))
			 $data['work_hours'] = null;
			 
		/*
		 * If Final Quantity is not set,
		 * sets it to NULL
		 */		
		if(!strlen($data['final_quantity']))
			 $data['final_quantity'] = null;
		
		$this->db->where('id',$id);

		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	function complete($id,$qty)
	{
		$this->db->set('is_completed',1);
		$this->db->set('datecompleted',mdate('%Y-%m-%d'));
		$this->db->set('job_order_status','completed');
		$this->db->set('final_quantity',$qty);
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table);	
		
		return $this->db->affected_rows();	
	}
	
	function get_qty($id)
	{
		$this->db->select('assigned_quantity');
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query)
			return $query->row();
		else
			return false;
	}
	
	function has_fqty($id)
	{
		$this->db->select('final_quantity, is_completed');
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query)
			return $query->row();
		else
			return false;	
	}
	
	function has_final($id)
	{
		$this->db->select('final_quantity');
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get()->row();
		
		if($query->final_quantity != 0 || $query->final_quantity != null)
			return true;
		else
			return false;	
	}
	
	function get_task($id)
	{
		$this->db->select('task_fk');
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query)
			return $query->row();
		else
			return false;	
	}
	
	function payroll($options=array())
	{
		$this->db->select('jo.id,jo.final_quantity,jo.assigned_to,
							t.taskname,t.rate_per_unit,e.fname,e.lname,u.uname');
		
		$this->db->select_sum('jo.final_quantity');
		
		$this->db->select('count(jo.id) as count');
		
		$this->db->from('exp_cd_job_orders as jo');
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
		
		return $this->db->get()->result();
	}
	
	function report($options = array())
	{
		$this->db->select('
			SUM(jo.final_quantity) as sum,
			SUM(jo.defect_quantity) as sum_defect,
			AVG(jo.final_quantity) as avg,
			MIN(jo.final_quantity) as min,
			MAX(jo.final_quantity) as max,
			COUNT(jo.final_quantity) as count,
			t.taskname,u.uname');
		
		$this->db->from('exp_cd_job_orders as jo');
		
		$this->db->join('exp_cd_tasks AS t','t.id = jo.task_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('jo.datedue >=',$options['datefrom']);
		$this->db->where('jo.datedue <=',$options['dateto']);
		
		if(strlen($options['assigned_to']))
			$this->db->where('jo.assigned_to',$options['assigned_to']);
		if(strlen($options['task_fk']))
			$this->db->where('jo.task_fk',$options['task_fk']);
		if(strlen($options['shift']))
			$this->db->where('jo.shift',$options['shift']);
		
		$this->db->where('jo.is_completed',1);
		$this->db->where('t.status','active');
		
		$this->db->group_by('jo.task_fk');
		
		$this->db->order_by('t.taskname','asc');
		
		return $this->db->get()->result();
	}
	
	function delete($id)
	{	
		$this->db->where('id',$id);
		$this->db->delete($this->table);

		return $this->db->affected_rows();	
	}
}