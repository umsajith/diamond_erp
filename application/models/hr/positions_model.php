<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Positions_model extends MY_Model {
	
	protected $_table = 'exp_cd_positions';

	public $before_create = array('calc_bonus_commision');
	public $before_update = array('calc_bonus_commision');
	
	public function select($sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
			p.requirements,d.department,p.dept_fk,p.commision * 100 commision,p.description');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');

		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit, $offset);
		
		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
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
		$this->db->select('p.id,p.position,p.base_salary,p.bonus * 100 bonus,p.dateofentry,
							p.requirements,p.dept_fk,p.commision * 100 commision,p.description,d.department');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		
		$this->db->where('p.id',$id);
		
		return $this->db->get($this->_table.' AS p')->row();
	}

	protected function calc_bonus_commision($position)
    {
        if(isset($position['bonus']))
			$position['bonus'] = $position['bonus']/100;

		if(isset($position['commision']))
			$position['commision'] = $position['commision']/100;

        return $position;
    }
}