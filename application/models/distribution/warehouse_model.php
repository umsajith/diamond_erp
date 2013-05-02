<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Warehouse_model extends MY_Model {
	
	protected $_table = 'exp_cd_warehouse';

	public $before_create = ['setDefaults','currentStock'];

	protected $_location;

	public function __construct()
	{
		parent::__construct();

		/**
		 * Stores default location ID from session data
		 * @var integer
		 */
		$this->_location = $this->session->userdata('location');
    }
	
	public function select($options = array())
	{
		$this->db->select('w.*,p.prodname,u.uname,e.fname,e.lname,p.id as pid');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		
		$this->db->order_by('w.dateofentry','desc');
		
		/*
		 * Retreives either inbound or outbound
		 * entries into the warehouse
		 */
		if(isset($options['is_out']))
			$this->db->where('w.is_out',$options['is_out']);
		
		return $this->db->get($this->_table.' AS w')->result();
	}
	
	public function select_all_inbound($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname,
				CONCAT(e.fname,' ',e.lname) AS operator",false);
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.inserted_by','LEFT');
		
		/*
		 * Search Filters
		 */
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('w.prodname_fk',$query_array['prodname_fk']);

		/*
		 * If sorting by product, changes from sorting
		 * by foreign key, to sorting by product name. (alphabeticaly)
		 */
		if($sort_by == 'prodname_fk')
			$sort_by = 'p.prodname';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_out',null);
		$this->db->where('w.is_return',null);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location) $this->db->where('w.location_id',$this->_location);
		
		$data['results'] = $this->db->get($this->_table.' AS w')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
			
		$this->db->where('is_out',null);
		$this->db->where('is_return',null);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location) $this->db->where('location_id',$this->_location);
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_all_outbound($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname,
				CONCAT(e.fname,' ',e.lname) AS distributor,
				CONCAT(em.fname,' ',em.lname) AS operator",false);
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		$this->db->join('exp_cd_employees AS em','em.id = w.inserted_by','LEFT');
		
		/*
		 * Search Filters
		 */
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('w.prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('w.distributor_fk',$query_array['distributor_fk']);

		/*
		 * If sorting by product, changes from sorting
		 * by foreign key, to sorting by product name. (alphabeticaly)
		 */
		if($sort_by == 'prodname_fk')
			$sort_by = 'p.prodname';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_out',1);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location) $this->db->where('w.location_id',$this->_location);
		
		$data['results'] = $this->db->get($this->_table.' AS w')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('distributor_fk',$query_array['distributor_fk']);
			
		$this->db->where('is_out',1);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location) $this->db->where('location_id',$this->_location);
		
		$temp = $this->db->get($this->_table.' AS w')->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_all_returns($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname, 
				CONCAT(e.fname,' ',e.lname) AS distributor,
				CONCAT(em.fname,' ',em.lname) AS operator",false);
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		$this->db->join('exp_cd_employees AS em','em.id = w.inserted_by','LEFT');
		
		/*
		 * Search Filters
		 */
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('w.prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('w.distributor_fk',$query_array['distributor_fk']);

		/*
		 * If sorting by product, changes from sorting
		 * by foreign key, to sorting by product name. (alphabeticaly)
		 */
		if($sort_by == 'prodname_fk')
			$sort_by = 'p.prodname';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_return',1);
		$this->db->where('is_out',null);
		
		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location)
			$this->db->where('w.location_id',$this->_location);
		
		$data['results'] = $this->db->get($this->_table.' AS w')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('distributor_fk',$query_array['distributor_fk']);
			
		$this->db->where('is_out',null);
		$this->db->where('is_return',1);
		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location) $this->db->where('location_id',$this->_location);
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid,
						e.fname,e.lname,emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		$this->db->join('exp_cd_employees AS emp','emp.id = w.inserted_by','LEFT');
		
		$this->db->where('w.id',$id);
		
		$this->db->limit(1);
		
		return $this->db->get($this->_table.' AS w')->row();
	}
	
	public function select_item($id, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid,
						emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');	
		$this->db->join('exp_cd_employees AS emp','emp.id = w.inserted_by','LEFT');
		
		$this->db->where('w.prodname_fk',$id);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location)
			$this->db->where('w.location_id',$this->_location);

		//Order
		$this->db->order_by('w.dateofentry','desc');
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		$data['results'] = $this->db->get($this->_table.' AS w')->result();
		
		if(empty($data['results']))
			return false;
				
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		$this->db->select('COUNT(id) as count',false);

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location)
			$this->db->where('location_id',$this->_location);
		
		$this->db->where('prodname_fk',$id);
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows	
		return $data;
	}
	
	public function levels()
	{
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid');
		$this->db->select_sum('w.quantity');

		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');

		/**
		 * If user has specific location set,
		 * display warehouse entries for that location only!
		 */
		if($this->_location)
			$this->db->where('w.location_id',$this->_location);
		
		$this->db->group_by('w.prodname_fk');

		$this->db->order_by('w.dateofentry','desc');
		
		return $this->db->get($this->_table.' AS w')->result();
	}

	////////////////
	// OBSERVERS //
	////////////////
	protected function setDefaults($row)
    {
    	if(!isset($row['dateoforigin'])) $row['dateoforigin'] = mdate('%Y-%m-%d');

		if(!strlen($row['distributor_fk'])) $row['distributor_fk'] = null;

		/*
		 * Sets all outbound warehouse entries to
		 * have negative values, hence when making
		 * warehouse leves, same are deducted from total
		 * quantity
		 */
		if(isset($row['is_out']) AND $row['is_out'])
		{
			if($row['quantity'] > 0)
			{
				$row['quantity'] = $row['quantity'] * -1;
			}
		}
		
		/**
		 * If location is specified, insert into that location
		 */
		if($this->_location) $row['location_id'] = $this->_location;

		return $row;
    }

    /**
     * Calculates current Stock before inserting Warehouse entry.
     * New Current stock is currentStock + new Quantity.
     * @param  Object $row
     * @return Object
     */
    protected function currentStock($row)
    {
    	$this->db->select_sum('quantity');

		if($this->_location)
		{
			$this->db->where('location_id',$this->_location);
		}

    	$this->db->where('prodname_fk',$row['prodname_fk']);

    	$result	= $this->db->get($this->_table)->row();

    	$row ['qty_current'] = ($result->quantity) ? $result->quantity : 0;

    	return $row;
    }
}