<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Task_model extends CI_Model {

	protected $_table = 'exp_cd_tasks';
	
	public function select($sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('t.*,u.uname,b.name');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		$this->db->join('exp_cd_bom AS b','b.id = t.bom_fk','LEFT');
			
		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit, $offset);
	
		$this->db->where('t.status','active');
		
		$data['results'] = $this->db->get($this->_table.' AS t')->result();
		
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
		//Selects and returns all records from table
		$this->db->select('t.*,u.uname,b.name');
		$this->db->from('exp_cd_tasks AS t');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		$this->db->join('exp_cd_bom AS b','b.id = t.bom_fk','LEFT');
		
		//Qualifications
		$this->db->where('t.id',$id);

		$this->db->where('t.status','active');
		
		return $this->db->get()->row();
	}
	
	public function insert ($data = array())
	{
		//Sets Bom_fk to NULL if this task does not produce
		if(!isset($data['bom_fk']) OR $data['bom_fk'] == '')
			$data['bom_fk'] = null;
		else
			$data['is_production'] = 1;
			
		// Inserts the whole data array into the database table
		$this->db->insert('exp_cd_tasks',$data);
		
		return $this->db->insert_id();
	}
	
	public function update($id,$data = array())
	{
		//If Bom_Fk is unset, or empty, deletes BOM_FK in db, and sets production bit to 0
		if(isset($data['bom_fk']) && $data['bom_fk'] == '')
		{
			$data['bom_fk'] = NULL;
			$data['is_production'] = 0;
		}
		
		if(isset($data['bom_fk']) && $data['bom_fk'] != '')
		{
			$data['bom_fk'] = $data['bom_fk'];
			$data['is_production'] = 1;
		}
			
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update('exp_cd_tasks',$data);
		
		return $this->db->affected_rows();
	}
	
	public function delete($id)
	{
		//Updates the status to 'deleted'
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update('exp_cd_tasks',$data);

		return $this->db->affected_rows();
		
	}
	
	public function dropdown()
	{
		//Query
		$this->db->select('t.id,t.taskname,u.uname');
		$this->db->from('exp_cd_tasks AS t');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		$this->db->where('t.status','active');	
		$this->db->order_by('t.taskname');
		
		return $this->db->get()->result();	 
	}
	
	public function find_bom($id)
	{
		//Selects and returns all records from table
		$this->db->select('bom_fk');
		$this->db->where('id',$id);
		$this->db->where('is_production',1);
		$this->db->where('status','active');
		$this->db->limit(1);
		
		$result = $this->db->get($this->_table)->row();
		return $result->bom_fk;
	}
}