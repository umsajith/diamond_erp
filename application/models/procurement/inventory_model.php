<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventory_model extends MY_Model {
	
	protected $_table = 'exp_cd_inventory';

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
	
	public function select($options = array(),$limit=null,$offset=null)
	{
		$this->db->select('i.*,p.prodname,pc.pcname,u.uname,t.company,p.code,p.id AS pid,
							e.fname,e.lname,emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->from($this->_table.' AS i');
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_partners AS t','t.id = i.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = i.received_by','LEFT');
		$this->db->join('exp_cd_employees AS emp','emp.id = i.assigned_to','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');

		//Filter Qualifications
		if(isset($options['prodname_fk']) AND $options['prodname_fk'] != '')
			$this->db->where_in('i.prodname_fk',$options['prodname_fk']);
		if(isset($options['partner_fk']) AND $options['partner_fk'] != '')
			$this->db->where_in('i.partner_fk',$options['partner_fk']);
		if(isset($options['pcname_fk']) AND $options['pcname_fk'] != '')
			$this->db->where_in('p.pcname_fk',$options['pcname_fk']);
			
		if(isset($options['job_order_fk']))
		{
			$this->db->where('i.job_order_fk',$options['job_order_fk']);
			$this->db->where('i.is_use',1);
		}
		
		//Retreives Purchase Orders if Requested
		if (isset($options['type']))
			$this->db->where('i.type',$options['type']);
				
		//Retreives deductions from Inventory if requested	
		if (isset($options['is_use']))
			$this->db->where('i.is_use',$options['is_use']);
			
		//Sort and Direction
		if (!isset($options['sory_by']) AND !isset($options['sort_direction']))
			$this->db->order_by('i.dateofentry','desc');
		else
			$this->db->order_by($options['sort_by'],$options['sort_direction']);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		return $this->db->get()->result();
	}
	
	public function select_all($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects results by supplied criteria----------------------------------------------------------------
		$this->db->select("i.*,p.prodname,pc.pcname,u.uname,t.company,p.code,p.id AS pid,
			CONCAT(e.fname,' ',e.lname) AS assigned",false);	
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_partners AS t','t.id = i.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = i.assigned_to','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		/*
		 * Search Filters
		 */
		if(strlen($query_array['prodname_fk']))
			$this->db->where('i.prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['pcname_fk']))
			$this->db->where('p.pcname_fk',$query_array['pcname_fk']);
		if(strlen($query_array['partner_fk']))
			$this->db->where('i.partner_fk',$query_array['partner_fk']);
			
		if($sort_by == 'partner_fk')
			$sort_by = 't.company';
			
		if($sort_by == 'prodname_fk')
			$sort_by = 'p.prodname';
			
		if($sort_by == 'pcname_fk')
			$sort_by = 'pc.pcname';
			
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);

		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('i.location_id',$this->_location);
		
		$this->db->where('i.type',$query_array['type']);
		
		$data['results'] = $this->db->get($this->_table.' AS i')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		$this->db->select('COUNT(i.id) as count',false);
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where('prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['pcname_fk']))
			$this->db->where('pcname_fk',$query_array['pcname_fk']);
		if(strlen($query_array['partner_fk']))
			$this->db->where('partner_fk',$query_array['partner_fk']);
			
		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('location_id',$this->_location);

		$this->db->where('type',$query_array['type']);
		
		$temp = $this->db->get($this->_table.' AS i')->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		$this->db->select('i.*,p.prodname,pc.pcname,u.uname,t.company,p.code,p.id AS pid,
			tr.rate,e.fname,e.lname,emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_partners AS t','t.id = i.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = i.received_by','LEFT');
		$this->db->join('exp_cd_employees AS emp','emp.id = i.assigned_to','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_tax_rates AS tr','tr.id = p.tax_rate_fk','LEFT');

		$this->db->where('i.id',$id);
		
		return $this->db->get($this->_table.' AS i')->row();
	}
	
	public function levels()
	{
		$this->db->select('i.id,p.prodname,pc.pcname,u.uname,p.alert_quantity,p.id AS pid');
		
		$this->db->select_sum('i.quantity');
		$this->db->select_max('i.dateofentry');
		$this->db->select_avg('i.price');
		$this->db->select_max('i.price','maxprice');
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//All active products
		$this->db->where('p.status','active');
		
		//Only for products that are stockable
		$this->db->where('p.stockable',1);

		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('i.location_id',$this->_location);
		
		//All entries but the Purchase Orders
		$this->db->where_in('i.type',array('gr','adj',0));
		
		$this->db->group_by('i.prodname_fk');
		$this->db->order_by('i.dateofentry','desc');

		return $this->db->get($this->_table.' AS i')->result();
	}
	
	public function insert ($data = array())
	{	
		/*
		 * If Goods Receipt has been inserted,
		 * marks Date Received NOW
		 */
		if($data['type']=='gr')
			$data['datereceived'] = mdate("%Y-%m-%d",now());
		/*
		 * If Purchase Order has been inserted,
		 * marks Date Ordered NOW
		 */
		if($data['type']=='po')
			$data['dateoforder'] = mdate("%Y-%m-%d",now());
		
		/*
		 * Sets Date of Order, Date of Expiration
		 * and Price to NULL if the value has not
		 * been set
		 */
		if(isset($data['dateoforder']) AND !strlen($data['dateoforder']))
			$data['dateoforder'] = null;
		if(isset($data['dateofexpiration']) AND !strlen($data['dateofexpiration']))
			$data['dateofexpiration'] = null;
		if(isset($data['price']) AND !strlen($data['price']))
			$data['price'] = null;

		/**
		 * Inserts default working location ID
		 */
		if($this->_location)
			$data['location_id'] = $this->_location;
				
		/*
		 * Calculates the Quantity at Hand of product
		 * before change,and saves it 
		 * in attribute - qty_current
		 */
		$data['qty_current'] = $this->current_qty($data['prodname_fk']);
			
		$data['received_by'] = $this->session->userdata('userid');
		
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	private function current_qty($product_id)
	{
		$this->db->select_sum('quantity');

		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('location_id',$this->_location);
		
		$this->db->where('prodname_fk',$product_id);
		
		$result = $this->db->get($this->_table)->row();
		
		if(!is_null($result->quantity))
			return $result->quantity;
		else
			return false;
	}
	
	public function update($id,$data = array())
	{	
		/*
		 * Sets Date of Order, Date of Expiration
		 * ,Price, Partner and Assigned To to NULL
		 * if the value has not been set
		 */		
		if(!strlen($data['dateoforder']))
			$data['dateoforder'] = null;
		if(!strlen($data['dateofexpiration']))
			$data['dateofexpiration'] = null;
		if(!strlen($data['price']))
			$data['price'] = null;		
		if(!strlen($data['partner_fk']))
			$data['partner_fk'] = null;	
		if(!strlen($data['assigned_to']))
			$data['assigned_to'] = null;
				
		$this->db->where('id',$id);		

		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
	
	public function receive_po($options = array())
	{
		/*
		 * Transfers the Purchase Order into
		 * Goods Receipt Note, and Inserts
		 * DateReceived to be NOW
		 */
		$this->db->set('type','gr');
		$this->db->set('datereceived', mdate("%Y-%m-%d",now()));
		$this->db->set('received_by',$this->session->userdata('userid'));

		$this->db->where_in('id',$options['ids']);
		
		$this->db->update($this->_table);
		
		return $this->db->affected_rows();
	}
	
	public function select_use($key, $value)
	{
		
		//Selects and returns all records from table
		$this->db->select('i.id,i.quantity,i.prodname_fk,i.dateofentry,
							p.prodname,pc.pcname,u.uname');
		
		$this->db->from($this->_table.' AS i');
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Selects this specific Job Order Inventory Entries
		$this->db->where('i.'.$key,$value);
		
		//Retreives only the INVENTORY DEDUCTION records
		$this->db->where('i.is_use',1);
		
		return $this->db->get()->result();
	}
	
	public function select_item($id,$limit=null,$offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('i.*,u.uname,t.company,p.id AS pid,e.fname,e.lname');
		
		$this->db->from($this->_table.' AS i');
		
		$this->db->join('exp_cd_products AS p','p.id = i.prodname_fk','LEFT');
		$this->db->join('exp_cd_partners AS t','t.id = i.partner_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = i.received_by','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		$this->db->where_not_in('type','po');
		
		//Qualifications
		$this->db->where('i.prodname_fk',$id);

		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('i.location_id',$this->_location);

		//Order
		$this->db->order_by('i.dateofentry','desc');
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		$data['results'] = $this->db->get()->result();
		
		if(empty($data['results']))
			return false;
				
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		$this->db->select('prodname_fk, type, COUNT(id) as count',false);
		$this->db->from($this->_table);

		/**
		 * If user has specific location set,
		 * display inventory for that location only!
		 */
		if($this->_location)
			$this->db->where('location_id',$this->_location);
		
		$this->db->where_not_in('type','po');
		
		$this->db->where('prodname_fk',$id);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows	
		return $data;
	}

	public function has_deducation($jo_id)
	{
		//Checks if there are already Inventory Deductions for this Job Order
		$this->db->select('id');
		$this->db->from($this->_table);
		$this->db->where('job_order_fk',$jo_id);
		
		return $this->db->get()->result_array();
	}
}