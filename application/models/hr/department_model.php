<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Department_model extends CI_Model {
	
	protected $table = 'exp_cd_departments';
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select($options = array())
	{
		$this->db->select()
				 ->from($this->table)
				 ->limit($options['limit'],$options['offset'])
				 ->order_by($options['sortname'],$options['sortorder']);
			
		if(strlen($options['qtype']) && strlen($options['query']))
			$this->db->like($options['qtype'],$options['query']);
		
		$data['results'] = $this->db->get()->result();
		
		$data['count'] = $this->db->count_all($this->table);
		
		return $data;
	}
	
	function insert ($name)
	{
		$this->db->set('department',$name);
		$this->db->insert($this->table);
		return $this->db->insert_id();
	}
	
	function update($id,$name)
	{
		$this->db->where('id',$id);
		$this->db->set('department',$name);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}
	
	function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->delete($this->table);  
		return $this->db->affected_rows();
	}	
}