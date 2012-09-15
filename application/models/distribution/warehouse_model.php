<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Warehouse_model extends CI_Model {
	
	//Database table of the Model
	protected $table = 'exp_cd_warehouse';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select($options = array())
	{
		$this->db->select('w.*,p.prodname,u.uname,e.fname,e.lname,p.id as pid');
		
		$this->db->from('exp_cd_warehouse as w');
		
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
		
		return $this->db->get()->result();
	}
	
	function select_all_inbound($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname,
				CONCAT(e.fname,' ',e.lname) AS operator",false);
		
		$this->db->from('exp_cd_warehouse as w');
		
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
		/*
		 * If sorting by is by new quantity (novo saldo),
		 * since qty_new does not exist, and its calculated
		 * by adding quantity to the qty_current, sorting
		 * is done in same fashion
		 */
		if($sort_by == 'qty_new')
			$sort_by = 'qty_current + quantity';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_out',null);
		$this->db->where('w.is_return',null);
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		$this->db->from($this->table);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
			
		$this->db->where('is_out',0);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_all_outbound($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname,
				CONCAT(e.fname,' ',e.lname) AS distributor,
				CONCAT(em.fname,' ',em.lname) AS operator",false);
		
		$this->db->from('exp_cd_warehouse as w');
		
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
		/*
		 * If sorting by is by new quantity (novo saldo),
		 * since qty_new does not exist, and its calculated
		 * by adding quantity to the qty_current, sorting
		 * is done in same fashion
		 */
		if($sort_by == 'qty_new')
			$sort_by = 'qty_current + quantity';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_out',1);
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		$this->db->from($this->table);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
		if(strlen($query_array['distributor_fk']))
			$this->db->where_in('distributor_fk',$query_array['distributor_fk']);
			
		$this->db->where('is_out',1);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_all_returns($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		$this->db->select("w.*,p.prodname,u.uname, 
				CONCAT(e.fname,' ',e.lname) AS distributor,
				CONCAT(em.fname,' ',em.lname) AS operator",false);
		
		$this->db->from('exp_cd_warehouse as w');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		$this->db->join('exp_cd_employees AS em','em.id = w.inserted_by','LEFT');
		
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
		/*
		 * If sorting by is by new quantity (novo saldo),
		 * since qty_new does not exist, and its calculated
		 * by adding quantity to the qty_current, sorting
		 * is done in same fashion
		 */
		if($sort_by == 'qty_new')
			$sort_by = 'qty_current + quantity';
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->where('w.is_return',1);
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		$this->db->from($this->table);
		
		if(strlen($query_array['prodname_fk']))
			$this->db->where_in('prodname_fk',$query_array['prodname_fk']);
			
		$this->db->where('is_out',0);
		$this->db->where('is_return',1);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_single($id)
	{
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid,
						e.fname,e.lname,emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->from('exp_cd_warehouse as w');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_employees AS e','e.id = w.distributor_fk','LEFT');
		$this->db->join('exp_cd_employees AS emp','emp.id = w.inserted_by','LEFT');
		
		$this->db->where('w.id',$id);
		
		$this->db->limit(1);
		
		return $this->db->get()->row();
	}
	
	function select_item($id, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid,
						emp.fname AS assignfname,emp.lname AS assignlname');
		
		$this->db->from($this->table.' AS w');
		
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');	
		$this->db->join('exp_cd_employees AS emp','emp.id = w.inserted_by','LEFT');
		
		$this->db->where('w.prodname_fk',$id);

		//Order
		$this->db->order_by('w.dateofentry','desc');
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		$data['results'] = $this->db->get()->result();
		
		if(empty($data['results']))
			return false;
				
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		$this->db->select('COUNT(id) as count',false);
		$this->db->from($this->table);
		
		$this->db->where('prodname_fk',$id);
		
		$temp = $this->db->get()->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows	
		return $data;
	}
	
	function levels($options = array(),$limit=NULL,$offset=NULL)
	{
		$this->db->select('w.*,p.prodname,u.uname,p.id as pid');
		$this->db->select_sum('w.quantity');
		$this->db->from('exp_cd_warehouse as w');
		$this->db->join('exp_cd_products AS p','p.id = w.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		$this->db->group_by('w.prodname_fk');
		
		return $this->db->get()->result();
	}
	
	function insert ($data = array())
	{	
		/*
		 * Sets all outbound warehouse entries to
		 * have negative values, hence when making
		 * warehouse leves, same are deducted from total
		 * quantity
		 */
		if(isset($data['is_out']) && $data['is_out'] == 1)
			$data['quantity'] = $data['quantity'] * -1;
			
		if(!strlen($data['dateoforigin']))
			$data['dateoforigin'] = mdate('%Y-%m-%d');
			
		/*
		 * Calculates the Quantity at Hand of product
		 * before change,and saves it 
		 * in attribute - qty_current
		 */
		$data['qty_current'] = $this->current_qty($data['prodname_fk']);
			
		$this->db->insert($this->table,$data);
		
		return $this->db->insert_id();
	}
	
	private function current_qty($product_id)
	{
		$this->db->select_sum('quantity');
		
		$this->db->from($this->table);
		
		$this->db->where('prodname_fk',$product_id);
		
		$result = $this->db->get()->row();
		
		if(!is_null($result->quantity))
			return $result->quantity;
		else
			return false;
	}
	
	function update($id,$data = array(),$page)
	{	
		/*
		 * If an outbound entry has been modified,
		 * this makes sure negative quantity (deducation)
		 * is inserted
		 */
		if($page == 'out')
		{
			if($data['quantity'] > 0)
				$data['quantity'] = $data['quantity'] * -1;
			
			if(isset($data['quantity']) AND $data['quantity'] > 0)
				$data['qty_current'] = $this->current_qty($data['prodname_fk']);
			
			$data['is_out'] = 1;
			$data['is_return'] = null;			
		}
		/*
		 * Deletes all raw materials deductions for this inbound entry,
		 * so the new ones will be inserted according to new quantity
		 */
		if($page == 'in')
		{
			if(isset($data['quantity']) AND $data['quantity'] > 0)
				$this->_delete_inventory_ids($id);
				
			if(isset($data['quantity']) AND $data['quantity'] > 0)
				$data['qty_current'] = $this->current_qty($data['prodname_fk']);
				
			$data['is_out'] = null;
			$data['is_return'] = null;
		}
		
		/*
		 * Updated 'updated_at' field
		 */
		$data['updated_at'] = date("Y-m-d H:i:s", time());
		
		
		/*
		 * If dateoforigin has been unset (deleted)
		 * sets it to null
		 */
		if(!strlen($data['dateoforigin']))
			$data['dateoforigin'] = null;
			
		/*
		 * Update the following ID
		 */
		$this->db->where('id',$id);
		
		/*
		 * Data array passed contained
		 * new updated data
		 */
		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	private function _delete_inventory_ids($id)
	{
		$this->db->where('warehouse_fk',$id);
		$results = $this->db->delete('exp_cd_inventory');
		
		return $this->db->affected_rows();
	}

	function delete($id)
	{
		/*
		 * Deletes raw materials inventory deductions if
		 * they exist, if not returns "affected rows" = 0
		 */

		
		/*
		 * Deletes an entry with passed ID
		 */
		$this->db->delete($this->table, array('id' => $id)); 	
		
		return $this->db->affected_rows();	
	}
}