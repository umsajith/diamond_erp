<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payroll_model extends MY_Model {
	
	protected $_table = 'exp_cd_payroll';

	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,e.fname,e.lname,e.id as eid');
		$this->db->join('exp_cd_employees AS e','e.id = p.employee_fk','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['employee_fk']))
			$this->db->where_in('p.employee_fk',$query_array['employee_fk']);

		//Sort
		if($sort_by == 'employee')
			$sort_by = "e.fname";
			
		$this->db->order_by($sort_by,$sort_order);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('p.status','active');
		
		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select('COUNT(p.id) as count',false);
		$this->db->join('exp_cd_employees AS e','e.id = p.employee_fk','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['employee_fk']))
			$this->db->where_in('p.employee_fk',$query_array['employee_fk']);;
		
		$this->db->where('p.status','active');
		
		$temp = $this->db->get($this->_table.' AS p')->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,e.fname,e.lname,e.id as eid');
		$this->db->join('exp_cd_employees AS e','e.id = p.employee_fk','LEFT');
		
		$this->db->where('p.id',$id);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('p.status','active');

		return $this->db->get($this->_table.' AS p')->row();
	}
	/**
	 * Create new payroll. Depending on the employee status,
	 * locks job_orders or orders. Payroll extras locked by defualt.
	 * Controlled by TRANSACTION!
	 * @param  array  $data 
	 * @return integer       returns payroll new id if successfull
	 */
	public function insert ($data = array())
	{
		$this->db->trans_start();
		/*
		 * Inserts the operators ID
		 * based on session value
		 */
		$data['inserted_by'] = $this->session->userdata('userid');
		
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		$data['payroll_fk'] = $this->db->insert_id();
		
		/*
		 * Locks all Job Order and Orders which
		 * have been included in this Payroll
		 */
		if($data['fixed_wage_only'] == 0 AND $data['is_distributer'] == 0)
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
		$this->_payroll_code($data['payroll_fk'], $data);
		
		$this->db->trans_complete();

		if($this->db->trans_status() === false)
			return false;

		return $data['payroll_fk'];
	}
		
	public function find_payroll($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('p.id');
	
		//NOT FINISHED
		$this->db->where('p.employee_fk',$options['employee']);
		$this->db->where('p.date_from',$options['datefrom']);
		$this->db->where('p.date_to',$options['dateto']);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		if(!isset($options['status'])) 
			$this->db->where('p.status','active');
		
		$query = $this->db->get($this->_table.' AS p')->result();
	}
	
	private function _alter_job_orders($options = array(),$action = false)
	{
		$actions = array('lock','unlock');
		
		if(!in_array($action, $actions))
			return false;
			
		if($action == 'lock')
		{	
			if(!$this->_insert_calculation_rate($options))
				return false; 

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
			$this->db->set('calculation_rate',null);
			$this->db->where('payroll_fk',$options['payroll_fk']);
			$this->db->where('locked',1);
		}
		
		$this->db->update('exp_cd_job_orders');
		
		return $this->db->affected_rows();
	}

	private function _insert_calculation_rate($options = array())
	{
		$this->db->select('id, task_fk')
			->where('assigned_to',$options['employee_fk'])
			->where('datedue >=',$options['date_from'])
			->where('datedue <=',$options['date_to'])
			->where('is_completed',1)
			->where('payroll_fk',null)
			->where('locked',0);
		$ids = $this->db->get('exp_cd_job_orders')->result();

		foreach($ids as $row)
		{
			$this->db->select('id,rate_per_unit')
				 ->where('id',$row->task_fk);
			$rate = $this->db->get('exp_cd_tasks')->row();

			$this->db->set('calculation_rate',$rate->rate_per_unit)
				 ->where('id',$row->id)
				 ->where('task_fk',$rate->id)
				 ->update('exp_cd_job_orders');

			if(!$this->db->affected_rows())
				return false;
		}

		return true;
	}
	
	private function _alter_orders($options = array(),$action = false)
	{
		$actions = array('lock','unlock');
		
		if(!in_array($action, $actions))
			return false;
		
		if($action == 'lock')
		{
			$this->_insert_commision($options);

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
			$this->_null_commision($options['payroll_fk']);

			$this->db->set('locked',0);
			$this->db->set('payroll_fk',null);
			$this->db->where('payroll_fk',$options['payroll_fk']);
			$this->db->where('locked',1);
		}			
		
		$this->db->update('exp_cd_orders');

		return $this->db->affected_rows();
	}
	private function _insert_commision($options = array())
	{
		$this->db->select('id')
				->where('distributor_fk',$options['employee_fk'])
				->where('dateshipped >=',$options['date_from'])
				->where('dateshipped <=',$options['date_to'])
				->where('payroll_fk',null)
				->where('locked',0);
		$ids = $this->db->get('exp_cd_orders')->result();

		foreach($ids as $row)
		{
			$this->db->select('id, prodname_fk')
				->where('order_fk',$row->id);
			$dids = $this->db->get('exp_cd_order_details')->result();

			foreach($dids as $did)
			{
				$this->db->select('id,commision')
				 ->where('id',$did->prodname_fk)
				 ->where('status','active');
				$rate = $this->db->get('exp_cd_products')->row();

				$this->db->set('commision_rate',$rate->commision)
					 ->where('id',$did->id)
					 ->update('exp_cd_order_details');

				if(!$this->db->affected_rows())
					return false;
			}
		}
		return true;
	}
	private function _null_commision($payroll_id)
	{
		$this->db->select('id')->where('payroll_fk',$payroll_id);
		$orders = $this->db->get('exp_cd_orders')->result();

		foreach ($orders as $row) 
		{
			$this->db->where('order_fk',$row->id);
			$this->db->set('commision_rate',null);
			$this->db->update('exp_cd_order_details');
		}

		return true;
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
			$this->db->where('for_date >=',$options['date_from']);
			$this->db->where('for_date <=',$options['date_to']);
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
		 *  Pattern: EmployeeID.ForMonth.Year.PayrollID eg.12 2011 234 (no spaces)
		 */
		$this->db->set('code',$data['employee_fk'].substr($data['date_to'],0,4).$id);
		$this->db->where('id',$id);
		$this->db->update($this->_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Deletes(soft) payroll by provided pk.
	 * Also, runns functions to unlock job_orders, orders
	 * and paytoll extras.
	 * Controlled by TRANSACTION!
	 * @param  integer $id primary_key
	 * @return boolean     success/fail return
	 */
	public function delete($id)
	{
		$this->db->trans_start();
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
		$this->db->update($this->_table);

		$this->db->trans_complete();

		if($this->db->trans_status() === false)
			return false;

		return true;	
	}

	public function report($options = array())
	{
		$this->db->select('
			SUM(pr.acc_wage) AS sum_acc_wage,
			AVG(pr.acc_wage) AS avg_acc_wage,
			MAX(pr.acc_wage) AS max_acc_wage,
			MIN(pr.acc_wage) AS min_acc_wage,
			SUM(pr.social_cont) AS sum_social_cont,
			AVG(pr.social_cont) AS avg_social_cont,
			MAX(pr.social_cont) AS max_social_cont,
			MIN(pr.social_cont) AS min_social_cont,
			SUM(pr.bonuses) AS sum_bonuses,
			AVG(pr.bonuses) AS avg_bonuses,
			MAX(pr.bonuses) AS max_bonuses,
			MIN(pr.bonuses) AS min_bonuses,
			SUM(pr.gross_wage) AS sum_gross_wage,
			AVG(pr.gross_wage) AS avg_gross_wage,
			MAX(pr.gross_wage) AS max_gross_wage,
			MIN(pr.gross_wage) AS min_gross_wage,
			SUM(pr.expenses) AS sum_expenses,
			AVG(pr.expenses) AS avg_expenses,
			MAX(pr.expenses) AS max_expenses,
			MIN(pr.expenses) AS min_expenses,
			SUM(pr.fixed_wage) AS sum_fixed_wage,
			AVG(pr.fixed_wage) AS avg_fixed_wage,
			MAX(pr.fixed_wage) AS max_fixed_wage,
			MIN(pr.fixed_wage) AS min_fixed_wage,
			SUM(pr.comp_mobile_sub) AS sum_comp_mobile_sub,
			AVG(pr.comp_mobile_sub) AS avg_comp_mobile_sub,
			MAX(pr.comp_mobile_sub) AS max_comp_mobile_sub,
			MIN(pr.comp_mobile_sub) AS min_comp_mobile_sub,
			SUM(pr.paid_wage) AS sum_paid_wage,
			AVG(pr.paid_wage) AS avg_paid_wage,
			MAX(pr.paid_wage) AS max_paid_wage,
			MIN(pr.paid_wage) AS min_paid_wage');

		$this->db->where('pr.date_from >=',$options['date_from']);
		$this->db->where('pr.date_to <=',$options['date_to']);

		$this->db->where('pr.status','active');

		if(strlen($options['employee_fk']))
			$this->db->where_in('pr.employee_fk',$options['employee_fk']);
		
		return $this->db->get($this->_table.' AS pr')->row();
	}
}