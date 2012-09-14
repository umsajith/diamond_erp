<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bomdetails_model extends CI_Model {
	
	//Database table of the Model
	var $table = 'exp_cd_bom_details';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function select($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('b.id,b.bom_fk,b.quantity,b.prodname_fk,p.prodname,pc.pcname,u.uname');
		$this->db->from('exp_cd_bom_details AS b');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Qualifications
		if (isset($options['id']))
			$this->db->where('b.bom_fk',$options['id']);
		
		$this->db->order_by('b.quantity','desc');
		
		return $this->db->get()->result();
	}
	
	function insert ($data = array())
	{		
		// Inserts the whole data array into the database table
		$this->db->insert($this->table,$data);
		
		//return $this->db->insert_id();
	}
	
	function update($data = array())
	{
		//Qualifications
		//Qualifications
		if(isset($data['quantity']))
			$this->db->set('quantity',$data['quantity']);
		
		//This ID
		$this->db->where('id',$data['id']);
		
		//Updating
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}
	
	function delete($id)
	{
		//Updates the status to 'deleted'
		$this->db->where('id', $id);
		$this->db->delete($this->table); 
		return $this->db->affected_rows();	
	}
}