<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Co_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_orders';
	
	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table----------------------------------------------------
		$this->db->select('o.*,p.company,e.fname,e.lname,pm.name');
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
		
		$data['results'] = $this->db->get($this->_table.' AS o')->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select('COUNT(o.id) as count',false);
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		
		if(strlen($query_array['partner_fk']))
			$this->db->where_in('o.partner_fk',$query_array['partner_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('o.distributor_fk',$query_array['distributor_fk']);	
		if(strlen($query_array['payment_mode_fk']))
			$this->db->where_in('o.payment_mode_fk',$query_array['payment_mode_fk']);
		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('p.postalcode_fk',$query_array['postalcode_fk']);
		
		$temp = $this->db->get($this->_table.' AS o')->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}

	public function select_by_order_list($order_list_id)
	{
		//Selects and returns all records from table----------------------------------------------------
		$this->db->select("o.*,p.company,pm.name")
			->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT')
			->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
			
		$this->db->order_by('dateofentry','desc');
			
		$this->db->where('o.order_list_id',$order_list_id);
		
		return $this->db->get($this->_table.' AS o')->result();
	}
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.company, p.id as pid, e.fname,e.lname,pm.name');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = o.distributor_fk','LEFT');
		$this->db->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
			
		$this->db->where('o.id',$id);
		
		return $this->db->get($this->_table.' AS o')->row();
	}
	
	public function last_partner_orders($id,$limit = 10)
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.company, e.fname,e.lname,pm.name');
		$this->db->join('exp_cd_partners AS p','p.id = o.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = o.distributor_fk','LEFT');
		$this->db->join('exp_cd_payment_modes AS pm','pm.id = o.payment_mode_fk','LEFT');
			
		$this->db->where('o.partner_fk',$id);
		$this->db->limit($limit);
		$this->db->order_by('id','desc');
		
		return $this->db->get($this->_table.' AS o')->result();
	}
	
	public function insert ($data = array())
	{
		/*
		 * By default, all inserted Orders are
		 * Dispatch Note, hence they are Complete(Delivered)
		 */
		$data['ostatus'] = 'completed';

		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	public function update($id,$data = array())
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
		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
	
	/*
	 * Gets all the Order delivered within 
	 * a date (from-to) by specific Distributor
	 */
	public function get_by_distributor($id,$datefrom,$dateto)
	{
		$this->db->select('id');
		$this->db->where('distributor_fk',$id);
		$this->db->where('dateshipped >=',$datefrom);
		$this->db->where('dateshipped <=',$dateto);
		
		$results =  $this->db->get($this->_table)->result();	
		
		$ids = array();
		
		foreach($results as $id)
			array_push($ids, $id->id);
			
		return $ids;
	}
	
	public function get_by_payroll($id)
	{
		$this->db->select('id');
		$this->db->where('payroll_fk',$id);
		
		$results = $this->db->get($this->_table)->result();	
		
		$ids = array();
		
		foreach($results as $id)
			array_push($ids, $id->id);
			
		return $ids;
	}
	
	public function lock($options = array())
	{
		//Locks the Entries
		$this->db->set('locked',1);
		$this->db->set('locked_by',$this->session->userdata('userid'));

		$this->db->where_in('id',$options['ids']);
		
		$this->db->update($this->_table);
		
		return $this->db->affected_rows();
	}
	
	public function unlock($options = array())
	{
		//Locks the Entries
		$this->db->set('locked',0);
		$this->db->set('locked_by',null);

		$this->db->where_in('id',$options['ids']);
		
		$this->db->update($this->_table);
		
		return $this->db->affected_rows();
	}
	
	public function report($options = array())
	{
		$this->db->select('id');
		
		$this->db->where('dateshipped >=',$options['datefrom']);
		$this->db->where('dateshipped <=',$options['dateto']);
		
		if(strlen($options['distributor_fk']))
			$this->db->where('distributor_fk',$options['distributor_fk']);
		if(strlen($options['payment_mode_fk']))
			$this->db->where('payment_mode_fk',$options['payment_mode_fk']);
		if(strlen($options['partner_fk']))
			$this->db->where('partner_fk',$options['partner_fk']);

		$this->db->where('ostatus','completed');
		
		$data = $this->db->get($this->_table)->result_array();
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
}