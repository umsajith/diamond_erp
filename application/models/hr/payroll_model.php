<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payroll_model extends CI_Model {
	
	protected $table = 'exp_cd_payroll';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function select($options = array(),$limit=null,$offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,e.fname,e.lname,e.id as eid');
		$this->db->from('exp_cd_payroll AS p');
		$this->db->join('exp_cd_employees AS e','e.id = p.employee_fk','LEFT');
			
		//Filter Qualifications
		if(isset($options['employee_fk']) && $options['employee_fk'] != '')
			$this->db->where_in('p.employee_fk',$options['employee_fk']);
		if(isset($options['for_month']) && $options['for_month'] != '')
			$this->db->where_in('p.for_month',$options['for_month']);

		//Sort
		if (isset($options['sory_by']) && isset($options['sort_direction']))
			$this->db->order_by($options['sort_by'],$options['sort_direction']);
		else
			$this->db->order_by('p.dateofentry','desc');
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		if(!isset($options['status'])) 
			$this->db->where('p.status','active');

		return $query = $this->db->get()->result();
	}
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,YEAR(p.date_to) AS year,e.fname,e.lname,e.id as eid');
		$this->db->from('exp_cd_payroll AS p');
		$this->db->join('exp_cd_employees AS e','e.id = p.employee_fk','LEFT');
		
		//Qualifications
		$this->db->where('p.id',$id);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('p.status','active');

		return $this->db->get()->row();
	}
	
	public function insert ($data = array())
	{
		/*
		 * Inserts the operators ID
		 * based on session value
		 */
		$data['inserted_by'] = $this->session->userdata('userid');
		
		// Inserts the whole data array into the database table
		$this->db->insert($this->table,$data);
		
		$data['payroll_fk'] = $this->db->insert_id();
		
		/*
		 * Locks all Job Order and Orders which
		 * have been included in this Payroll
		 */
		if($data['fixed_wage_only'] == 0 && $data['is_distributer'] == 0)
		{
			$this->_alter_job_orders($data,'lock');
		}
		if($data['is_distributer'] == 1)
		{
			$this->_alter_orders($data,'lock');
		}
		
		/*
		 * Locks all payroll extras which
		 * have been included in this Payroll
		 */
		$this->_alter_payroll_extras($data,'lock');
		
		/*
		 * Generates Payroll code
		 * and return Payroll ID if success
		 * or False if failed
		 */	
		if($this->_payroll_code($data['payroll_fk'], $data))
			return $data['payroll_fk'];
		else
			return false;
	}
		
	public function find_payroll($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('p.id');
		$this->db->from('exp_cd_payroll AS p');
	
		//NOT FINISHED
		$this->db->where('p.employee_fk',$options['employee']);
		$this->db->where('p.date_from',$options['datefrom']);
		$this->db->where('p.date_to',$options['dateto']);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		if(!isset($options['status'])) 
			$this->db->where('p.status','active');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	private function _alter_job_orders($options = array(),$action = false)
	{
		$actions = array('lock','unlock');
		
		if(!in_array($action, $actions))
			return false;
			
		if($action == 'lock')
		{	
			$this->db->set('locked',1);
			$this->db->set('payroll_fk',$options['payroll_fk']);
	
			$this->db->where('assigned_to',$options['employee_fk']);
			$this->db->where('datedue >=',$options['date_from']);
			$this->db->where('datedue <=',$options['date_to']);
			
			$this->db->where('is_completed',1);
			$this->db->where('payroll_fk',null);
			$this->db->where('locked',0);
		}
		
		if($action == 'unlock')
		{
			$this->db->set('locked',0);
			$this->db->set('payroll_fk',null);
			$this->db->where('payroll_fk',$options['payroll_fk']);
			$this->db->where('locked',1);
		}
		
		$this->db->where('status','active');
		
		$this->db->update('exp_cd_job_orders');
		
		return $this->db->affected_rows();
	}
	
	private function _alter_orders($options = array(),$action = false)
	{
		$actions = array('lock','unlock');
		
		if(!in_array($action, $actions))
			return false;
		
		if($action == 'lock')
		{
			$this->db->set('locked',1);
			$this->db->set('payroll_fk',$options['payroll_fk']);
	
			$this->db->where('distributor_fk',$options['employee_fk']);
			$this->db->where('dateshipped >=',$options['date_from']);
			$this->db->where('dateshipped <=',$options['date_to']);
			$this->db->where('payroll_fk',null);
			$this->db->where('locked',0);
		}
		
		if($action == 'unlock')
		{
			$this->db->set('locked',0);
			$this->db->set('payroll_fk',null);
			$this->db->where('payroll_fk',$options['payroll_fk']);
			$this->db->where('locked',1);
		}			
		
		$this->db->where('status','active');
		
		$this->db->update('exp_cd_orders');
		
		return $this->db->affected_rows();
	}
	/**
	 * 
	 * Alters Payroll Extras based on supplied
	 * parameters. Two actions availabe (lock and unlock)
	 * - Lock: When new payroll is created, the function
	 * locks all payroll extras included in this payroll
	 * - Unlock: If payroll is deleted, releases all payroll
	 * extras which haven been included in the deleted payroll
	 * 
	 * @param array $options
	 * @param string $action
	 */
	private function _alter_payroll_extras($options = array(),$action = false)
	{
		$actions = array('lock','unlock');
		
		if(!in_array($action, $actions))
			return false;
			
		if($action == 'lock')
		{
			$this->db->set('locked',1);
			$this->db->set('payroll_fk',$options['payroll_fk']);
			$this->db->where('employee_fk',$options['employee_fk']);
			$this->db->where('for_month',$options['for_month']);
			$this->db->where('payroll_fk',null);
			$this->db->where('locked',0);
		}
		
		if($action == 'unlock')
		{
			$this->db->set('locked',0);
			$this->db->set('payroll_fk',null);
			$this->db->where('payroll_fk',$options['payroll_fk']);
			$this->db->where('locked',1);
		}

		$this->db->where('status','active');
		
		$this->db->update('exp_cd_payroll_extra');
		
		return $this->db->affected_rows();
	}
	
	/**
	 * 
	 * Generates Payroll Code based on supplied
	 * paramenters.
	 * 
	 * @param integer $id
	 * @param array $data
	 */
	private function _payroll_code($id,$data = array())
	{
		/*
		 *  Generate unique payroll code
		 *  Pattern: EmployeeID.ForMonth.Year.PayrollID eg.12 3 2011 234 (no spaces)
		 */
		$this->db->set('code',$data['employee_fk'].$data['for_month'].substr($data['date_to'],0,4).$id);
		$this->db->where('id',$id);
		$this->db->update($this->table);
		return $this->db->affected_rows();
	}
	
	public function delete($id)
	{
		
		/*
		 * Unlocks all Job Order and Orders
		 * from which this payroll has been
		 * calculated
		 */
		$this->_alter_job_orders(array('payroll_fk'=>$id),'unlock');
		$this->_alter_orders(array('payroll_fk'=>$id),'unlock');
		/*
		 * Unlocks all Payroll Extras which
		 * have been included in this payroll
		 */
		$this->_alter_payroll_extras(array('payroll_fk'=>$id),'unlock');
		
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$this->db->update($this->table);
		return $this->db->affected_rows();	
	}
}