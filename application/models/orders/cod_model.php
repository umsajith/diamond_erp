<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cod_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_order_details';
	
	public function select($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.prodname,p.id AS pid,pc.pcname,u.uname');
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Qualifications
		if (isset($options['id']))
			$this->db->where('o.order_fk',$options['id']);

		//Sort
		if (isset($options['sory_by']) AND isset($options['sort_direction']))
			$this->db->order_by($options['sort_by'],$options['sort_direction']);

		return $this->db->get($this->_table.' AS o')->result();
	}
	
	public function total_distributed($ids)
	{
		$this->db->select('o.id,o.commision_rate,p.prodname,p.commision,pc.pcname,u.uname');
		$this->db->select_sum('o.quantity');
		
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');

		$this->db->where_in('o.order_fk',$ids);
		
		$this->db->group_by('o.prodname_fk');

		return $this->db->get($this->_table.' AS o')->result();
	}
	
	public function insert ($data = array())
	{		
		// Inserts the whole data array into the database table
		if($this->product_exist($data['order_fk'],$data['prodname_fk']))
			return false;
			
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	public function product_exist($order_id,$product_id)
	{		
		/*
		 * Checks if an entry with a supplied ORDER_ID
		 * has already product entry with supplied PRODUCT_ID
		 * Prevents entering same products on same order
		 */
		$this->db->select('id');
		$this->db->where('order_fk',$order_id);
		$this->db->where('prodname_fk',$product_id);
		
		if($this->db->get($this->_table)->row())
			return true;
		else
			return false;
	}
	
	public function update($id,$data = array())
	{
		if(isset($data['returned_quantity']))
		{
			if($data['returned_quantity'] == 0 OR $data['returned_quantity'] == '')
				$data['returned_quantity'] = null;
		}

		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
}