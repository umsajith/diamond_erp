<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Boms_model extends CI_Model {
	
	//Database table of the Model
	var $table = 'exp_cd_bom';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function select($options = array())
	{
		
		//Selects and returns all records from table
		$this->db->select('b.*,p.prodname,u.uname, u2.uname as uname2');
		$this->db->from('exp_cd_bom AS b');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u2','u2.id = b.uname_fk','LEFT');
		
		//Qualifications
		if (isset($options['id']))
		{
			$this->db->where('b.id',$options['id']);
			$this->db->limit(1);
		}
		
		if (isset($options['prodname_fk']))
		{
			$this->db->where('b.prodname_fk',$options['prodname_fk']);
			$this->db->limit(1);
		}
		
		return $this->db->get()->result();
	}
	
	function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('b.*,p.prodname,u.uname,u2.uname as uname2');
		$this->db->from('exp_cd_bom AS b');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u2','u2.id = b.uname_fk','LEFT');
		
		//Qualifications
		$this->db->where('b.id',$id);
		$this->db->limit(1);
		
		return $this->db->get()->row();
	}
	
	function select_by_product($product_id)
	{
		//Selects and returns all records from table
		$this->db->select('id')
				->from($this->table)
				->where('prodname_fk',$product_id)
				->limit(1);
		
		$row =  $this->db->get()->row();
		
		return $row->id;
	}
	
	function insert ($data = array())
	{			
		if(!strlen($data['prodname_fk']))
			$data['prodname_fk'] = null;
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->table,$data);
		
		return $this->db->insert_id();
	}
	
	function update($id,$data = array())
	{
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	function delete($id)
	{
		//Updates the status to 'deleted'
		$this->db->where('id',$id);
		$this->db->delete($this->table);

		return $this->db->affected_rows();	
	}
}