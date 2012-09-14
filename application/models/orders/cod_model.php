<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cod_model extends CI_Model {
	
	//Database table of the Model
	protected $table = 'exp_cd_order_details';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.prodname,p.id AS pid,pc.pcname,u.uname');
		$this->db->from('exp_cd_order_details AS o');
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Qualifications
		if (isset($options['id']))
			$this->db->where('o.order_fk',$options['id']);

		//Sort
		if (isset($options['sory_by']) && isset($options['sort_direction']))
			$this->db->order_by($options['sort_by'],$options['sort_direction']);

		return $this->db->get()->result();
	}
	
	function total_distributed($ids)
	{
		$this->db->select('o.id,p.prodname,p.commision,pc.pcname,u.uname');
		$this->db->select_sum('o.quantity');
		
		$this->db->from($this->table.' AS o');
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');

		$this->db->where_in('o.order_fk',$ids);
		
		$this->db->group_by('o.prodname_fk');

		return $this->db->get()->result();
	}
	
	function insert ($data = array())
	{		
		// Inserts the whole data array into the database table
		if($this->product_exist($data['order_fk'],$data['prodname_fk']))
			return false;
			
		$this->db->insert($this->table,$data);
		
		return $this->db->insert_id();
	}
	
	function product_exist($order_id,$product_id)
	{		
		/*
		 * Checks if an entry with a supplied ORDER_ID
		 * has already product entry with supplied PRODUCT_ID
		 * Prevents entering same products on same order
		 */
		$this->db->select('id');
		$this->db->from($this->table);
		$this->db->where('order_fk',$order_id);
		$this->db->where('prodname_fk',$product_id);
		
		if($this->db->get()->row())
			return true;
		else
			return false;
	}
	
	function update($id,$data = array())
	{
		if(isset($data['returned_quantity']))
		{
			if($data['returned_quantity'] ==0 || $data['returned_quantity'] == '')
				$data['returned_quantity'] = null;
		}

		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete($this->table);
		return $this->db->affected_rows();	
	}
}