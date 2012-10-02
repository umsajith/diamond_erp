<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Boms_model extends MY_Model {
	
	protected $_table = 'exp_cd_bom';
	
	public function select($sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('b.*,p.prodname,u.uname, u2.uname as uname2');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u2','u2.id = b.uname_fk','LEFT');
		
		//Sort by and Sort Order
		$this->db->order_by($sort_by,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit,$offset);
		
		$data['results'] = $this->db->get($this->_table.' AS b')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		$this->db->select('COUNT(*) as count',false);
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('b.*,p.prodname,u.uname,u2.uname as uname2');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u2','u2.id = b.uname_fk','LEFT');
		
		//Qualifications
		$this->db->where('b.id',$id);
		$this->db->limit(1);
		
		return $this->db->get($this->_table.' AS b')->row();
	}
	
	public function select_by_product($product_id)
	{
		//Selects and returns all records from table
		$this->db->select('id')
				->where('prodname_fk',$product_id)
				->limit(1);
		
		$row =  $this->db->get($this->_table)->row();
		
		return $row->id;
	}
	
	public function insert ($data = array())
	{			
		if(!strlen($data['prodname_fk']))
			$data['prodname_fk'] = null;
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
}