<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Co_model extends CI_Model {
	
	//Database table of the Model
	protected $table = 'exp_cd_orders';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table----------------------------------------------------
		$this->db->select('o.*,p.company,e.fname,e.lname,pm.name');
		$this->db->from('exp_cd_orders AS o');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = o.distributor_fk','LEFT');
		$this->db->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['partner_fk']))
			$this->db->where_in('o.partner_fk',$query_array['partner_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('o.distributor_fk',$query_array['distributor_fk']);
		if(strlen($query_array['payment_mode_fk']))
			$this->db->where_in('o.payment_mode_fk',$query_array['payment_mode_fk']);
		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('p.postalcode_fk',$query_array['postalcode_fk']);

		//Sort
		if($sort_by == 'partner_fk')
			$sort_by = 'p.company';
			
		$this->db->order_by($sort_by,$sort_order);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('o.status','active');
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select('COUNT(o.id) as count',false);
		$this->db->from('exp_cd_orders AS o');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		
		if(strlen($query_array['partner_fk']))
			$this->db->where_in('o.partner_fk',$query_array['partner_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('o.distributor_fk',$query_array['distributor_fk']);	
		if(strlen($query_array['payment_mode_fk']))
			$this->db->where_in('o.payment_mode_fk',$query_array['payment_mode_fk']);
		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('p.postalcode_fk',$query_array['postalcode_fk']);
		
		$this->db->where('o.status','active');
		
		$temp = $this->db->get()->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_single($id)
	{
		
		//Selects and returns all records from table
		$this->db->select('o.*,p.company, p.id as pid, e.fname,e.lname,pm.name');
		$this->db->from('exp_cd_orders AS o');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = o.distributor_fk','LEFT');
		$this->db->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('o.status','active');
		$this->db->where('o.id',$id);
		
		return $this->db->get()->row();
	}
	
	function last_partner_orders($id,$limit = 10)
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.company, e.fname,e.lname,pm.name');
		$this->db->from('exp_cd_orders AS o');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = o.distributor_fk','LEFT');
		$this->db->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('o.status','active');
		$this->db->where('o.partner_fk',$id);
		$this->db->limit($limit);
		$this->db->order_by('id','desc');
		
		return $this->db->get()->result();
	}
	
	function insert ($data = array())
	{
		/*
		 * By default, all inserted Orders are
		 * Dispatch Note, hence they are Complete(Delivered)
		 */
		$data['ostatus'] = 'completed';

		// Inserts the whole data array into the database table
		$this->db->insert($this->table,$data);
		
		if($id = $this->db->insert_id())
		{
			/*
			 * Generates the Order Code
			 */
			if($this->_insert_code($id))
				return $id;
			else
				return false;
		}
		else
			return false;
	}
	
	private function _insert_code($id)
	{
		/*
		 * Generated unique Order number
		 * Pattern: OrderID/YearMonthDay.InsertedByUserID
		 */
		$this->db->set('code',$id.'/'.mdate('%y%m%d', time()).$this->session->userdata('userid'));
		$this->db->where('id',$id);
		$this->db->update($this->table);
		
		return $this->db->affected_rows();	
	}
	
	function update($id,$data = array())
	{
		/*
		 * If Order Status is updated, Continues with
		 * the corresponding actions for each Order Status
		 * 
		 */
		/*
		if ($data['ostatus'] == 'pending' || $data['ostatus'] == 'rejected')
		{
			$data['completed'] = 0;
			$data['dateshipped'] = null;
		}
		
		if($data['ostatus'] == 'completed' && isset($data['dateshipped']) && ($data['dateshipped'] == ''))
		{
			$data['completed'] = 0;
			$data['dateshipped'] = null;
			$data['ostatus'] = 'pending';
		}
	
		if($data['ostatus'] == 'completed' && !isset($data['dateshipped']))
		{
			$data['completed'] = 1;
			$data['dateshipped'] = mdate('%Y-%m-%d');
			$data['ostatus'] = 'completed';
		}

		if($data['ostatus'] == 'completed' && isset($data['dateshipped']))
		{
			$data['dateshipped'] = $data['dateshipped'];
		}
		*/
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	/*
	 * Gets all the Order delivered within 
	 * a date (from-to) by specific Distributor
	 */
	function get_by_distributor($id,$datefrom,$dateto)
	{
		$this->db->select('id');
		
		$this->db->from($this->table);
		
		$this->db->where('distributor_fk',$id);
		$this->db->where('dateshipped >=',$datefrom);
		$this->db->where('dateshipped <=',$dateto);
		$this->db->where('status','active');
		
		$results =  $this->db->get()->result();	
		
		$ids = array();
		
		foreach($results as $id)
			array_push($ids, $id->id);
			
		return $ids;
	}
	
	function get_by_payroll($id)
	{
		$this->db->select('id');
		
		$this->db->from($this->table);
		
		$this->db->where('payroll_fk',$id);
		$this->db->where('status','active');
		
		$results =  $this->db->get()->result();	
		
		$ids = array();
		
		foreach($results as $id)
			array_push($ids, $id->id);
			
		return $ids;
	}
	
	function lock($options = array())
	{
		//Locks the Entries
		$this->db->set('locked',1);
		
		$this->db->set('locked_by',$this->session->userdata('userid'));

		$this->db->where_in('id',$options['ids']);
			
		//Affects only the ACTIVE records	
		$this->db->where('status','active');
		
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}
	
	function unlock($options = array())
	{
		//Locks the Entries
		$this->db->set('locked',0);
		$this->db->set('locked_by',null);

		$this->db->where_in('id',$options['ids']);
			
		//Affects only the ACTIVE records	
		$this->db->where('status','active');
		
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}
	
	function report($options = array())
	{
		$this->db->select('id');	
		$this->db->from('exp_cd_orders');
		
		$this->db->where('dateshipped >=',$options['datefrom']);
		$this->db->where('dateshipped <=',$options['dateto']);
		
		if(strlen($options['distributor_fk']))
			$this->db->where('distributor_fk',$options['distributor_fk']);
		if(strlen($options['payment_mode_fk']))
			$this->db->where('payment_mode_fk',$options['payment_mode_fk']);
		if(strlen($options['partner_fk']))
			$this->db->where('partner_fk',$options['partner_fk']);
		
		//$this->db->join('exp_cd_tasks AS t','t.id = jo.task_fk','LEFT');
		//$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		$this->db->where('status','active');
		$this->db->where('ostatus','completed');
		
		$data = $this->db->get()->result_array();
		$orders = array();
		foreach ($data AS $id)
			array_push($orders, $id['id']);
		if(!empty($orders))
		{
			$this->db->select('cod.*,p.prodname,u.uname');
		
			$this->db->select_sum('cod.quantity');
			$this->db->select_sum('cod.returned_quantity');
			
			$this->db->from('exp_cd_order_details AS cod');
			
			$this->db->join('exp_cd_products AS p','p.id = cod.prodname_fk','LEFT');
			$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
			
			$this->db->where_in('cod.order_fk',$orders);
			
			$this->db->group_by('cod.prodname_fk');
			
			return $this->db->get()->result();	
		}
		else
			return false;
	}
	
	function delete($id)
	{
		/*
		 * If the supplied ID for deletion
		 * does not exist, returs false
		 */
		if(!$this->select_single($id))
			return false;
			
		//Updates the status to 'deleted'
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update($this->table,$data);

		return $this->db->affected_rows();	
	}
}