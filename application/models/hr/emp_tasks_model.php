<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Emp_tasks_model extends MY_Model {
	
	protected $_table = 'exp_cd_employees_tasks';
	
	public function select($emp_id)
	{
		//Query
		$this->db->select('et.id,t.taskname');
		$this->db->join('exp_cd_tasks AS t','t.id = et.task_fk','LEFT');

		$this->db->where('et.employee_fk',$emp_id);
		
		$this->db->order_by('t.taskname','asc');

		return $this->db->get($this->_table.' AS et')->result();	 
	}
	
	public function insert ($data = array())
	{
		/*
		 * if an employee has been assigned a task,
		 * disables the ability to enter the same
		 * employee for the same task
		 */
		if($this->duplicate($data['employee_fk'], $data['task_fk']))
			return FALSE;
		
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	public function dropdown($employee_id)
	{
		/*
		 * Generates data for dropdown menu, VALUE=>KEY pairs
		 * based on employee ID passed, JSON Returned
		 */
		$this->db->select('t.id,t.taskname,u.uname');
		$this->db->join('exp_cd_tasks AS t','t.id = et.task_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = t.uname_fk','LEFT');
		
		$this->db->where('et.employee_fk',$employee_id);

		$this->db->order_by('t.taskname');
		
		$data = $this->db->get($this->_table.' AS et')->result();	 

		return json_encode($data);
	}
	
	public function duplicate($employee_fk,$task_fk)
	{
		/*
		 * Function that checks if certain employee
		 * has already been assigned certain task
		 */
		$this->db->select('id');
		$this->db->where('employee_fk',$employee_fk);
		$this->db->where('task_fk',$task_fk);
		return $this->db->get($this->_table)->result();	 
	}	
}