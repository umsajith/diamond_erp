<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices_model extends CI_Model {
	
	//Database table of the Model
	var $table = 'exp_cd_invoices';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function select()
	{
		$this->db->select('i.*, p.company AS partner')
				 ->from($this->table.' AS i')
				 ->join('exp_cd_partners AS p','p.id=i.partner_id')
				 ->where('i.status !=','deleted');
		return $this->db->get()->result();		
	}
	
	function select_single($id)
	{

		return $this->db->get()->row();
	}

	function insert ($data = array())
	{
		$this->db->insert($this->table,$data);
		
		if($id = $this->db->insert_id())
		{
			/*
			 * Generates the Invoice Number
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
		 * Generated unique Invoice Number
		 * Pattern: 
		 */
		$this->db->set('number',$id.'/'.mdate('%y%m%d', time()).$this->session->userdata('userid'));
		$this->db->where('id',$id);
		$this->db->update($this->table);
		
		return $this->db->affected_rows();	
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
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update($this->table,$data);

		return $this->db->affected_rows();	
	}
}