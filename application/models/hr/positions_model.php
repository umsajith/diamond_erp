<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Positions_model extends CI_Model {
	
	//Database table of the Model
	protected $table = 'exp_cd_positions';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function select($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
							p.requirements,d.department,p.dept_fk,p.commision * 100 commision,p.status,p.description');
		$this->db->from($this->table.' AS p');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');

		//Sort
		if (isset($options['sory_by']) && isset($options['sort_direction']))
			$this->db->order_by($options['sort_by'],$options['sort_direction']);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		if(!isset($options['status'])) 
			$this->db->where('p.status','active');
			
		return $this->db->get()->result();
	}
	
	function select_single($id)
	{
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
							p.requirements,p.dept_fk,p.commision * 100 commision,p.status,p.description,d.department');
		$this->db->from($this->table.' AS p');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		
		$this->db->where('p.id',$id);
	
		$this->db->where('p.status !=','deleted');
		
		return $this->db->get()->row();
	}
	
	function insert ($data = array())
	{
		if(isset($data['bonus']))
			$data['bonus'] = $data['bonus']/100;
		if(isset($data['commision']))
			$data['commision'] = $data['commision']/100;
			
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
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update($this->table,$data);

		return $this->db->affected_rows();	
	}
	
}