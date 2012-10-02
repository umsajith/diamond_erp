<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Positions_model extends MY_Model {
	
	protected $_table = 'exp_cd_positions';
	
	public function select($sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
			p.requirements,d.department,p.dept_fk,p.commision * 100 commision,p.status,p.description');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');

		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit, $offset);
	
		$this->db->where('p.status','active');
		
		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
			
		$this->db->where('status','active');
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
							p.requirements,p.dept_fk,p.commision * 100 commision,p.status,p.description,d.department');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		
		$this->db->where('p.id',$id);
	
		$this->db->where('p.status !=','deleted');
		
		return $this->db->get($this->_table.' AS p')->row();
	}
	
	public function insert ($data = array())
	{
		if(isset($data['bonus']))
			$data['bonus'] = $data['bonus']/100;
		if(isset($data['commision']))
			$data['commision'] = $data['commision']/100;
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	public function update($id,$data = array())
	{	
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
	
	public function delete($id)
	{
		//Updates the status to 'deleted'
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update($this->_table,$data);

		return $this->db->affected_rows();	
	}
	
}