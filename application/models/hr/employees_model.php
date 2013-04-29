<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employees_model extends MY_Model {
	
	protected $_table = 'exp_cd_employees';

	private static $algo = '$2a';

	private static $cost = '$10';
	
	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('e.*,r.name as role_name,c.name,pc.postalcode,d.department,p.position');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_roles AS r','r.id = e.role_id','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['poss_fk']))
			$this->db->where_in('e.poss_fk',$query_array['poss_fk']);
		if(strlen($query_array['role_id']))
			$this->db->where_in('e.role_id',$query_array['role_id']);

		//Sort
		if($sort_by == 'employee')
			$sort_by = "e.fname";
			
		$this->db->order_by($sort_by,$sort_order);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('e.status !=','deleted');
		
		$data['results'] = $this->db->get($this->_table.' AS e')->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select('COUNT(e.id) AS count');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_roles AS r','r.id = e.role_id','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');

		//Filter Qualifications
		if(strlen($query_array['poss_fk']))
			$this->db->where_in('e.poss_fk',$query_array['poss_fk']);
		if(strlen($query_array['role_id']))
			$this->db->where_in('e.role_id',$query_array['role_id']);
		
		$this->db->where('e.status !=','deleted');
		
		$temp = $this->db->get($this->_table.' AS e')->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('e.*,r.name AS role_name,c.name,pc.postalcode,d.department,p.position');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_roles AS r','r.id = e.role_id','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
	
		$this->db->where('e.id',$id);
			
		$this->db->where('e.status !=','deleted');

		return  $this->db->get($this->_table.' AS e')->row();
	}
	
	public function insert($data = array())
	{
		//Hash the password
		if(isset($data['password']) AND trim($data['password']!=''))
			$data['password'] = self::hash($data['password']);
		else
			$data['password'] = null;

		if(isset($data['role_id']) AND $data['role_id']=='')
			$data['role_id'] = null;	

		if(isset($data['location_id']) AND $data['location_id']=='')
			$data['location_id'] = null;	
		/*
		 * @TODO: If username set, and password set then:
		 *  - flag 'can_login' to 1
		 *  - set 'role_id' to 4 (default)
		 */
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);

		return $this->db->insert_id();
	}

	public function update($id,$data = array())
	{	
		
		/*
		 * If the entry has been edited, and password not changed,
		 * hence, stays '', the password is unset
		 * 
		 * If new password has been provided, hashes it
		 * and stores it to the same variable
		 */
		if(strlen($data['password']))
			$data['password'] = self::hash($data['password']);
		else 
			unset($data['password']);
		
		//If values are set and empty, sets them to null
		if(isset($data['fixed_wage']) AND $data['fixed_wage']=='')
			$data['fixed_wage'] = null;
		if(isset($data['social_cont']) AND $data['social_cont']=='')
			$data['social_cont'] = null;
		if(isset($data['comp_mobile_sub']) AND $data['comp_mobile_sub']=='')
			$data['comp_mobile_sub'] = null;		
		if(isset($data['start_date']) AND $data['start_date']=='')
			$data['start_date'] = null;
		if(isset($data['stop_date']) AND $data['stop_date']=='')
			$data['stop_date'] = null;	
		if(isset($data['manager_fk']) AND $data['manager_fk']=='')
			$data['manager_fk'] = null;

		if(isset($data['role_id']) AND $data['role_id']=='')
			$data['role_id'] = null;	
		
		//If the checkboxes are not checks, sets them to 0
		if(!isset($data['is_manager']))
			$data['is_manager'] = 0;	
		if(!isset($data['is_distributer']))
			$data['is_distributer'] = 0;	
		if(!isset($data['fixed_wage_only']))
			$data['fixed_wage_only'] = 0;
		if(!isset($data['can_login']))
			$data['can_login'] = 0;

		if($data['location_id']=='')
			$data['location_id'] = null;
			
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
	
	public function last_login($id)
	{
		//Sets the Last_login time to now
		$this->db->set('last_login',date('Y-m-d H:m:s',now()));
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->_table);
		
		return $this->db->affected_rows();
	}
	
	public function get_attr($id,$field)
	{
		$this->db->select($field);
		$this->db->where('id',$id);
		$this->db->limit(1);
		
		return $this->db->get($this->_table)->row();
	}
	
	public function delete($id)
	{
		//Updates the status to 'deleted'
		$this->db->where('id',$id);
		$data['status'] = 'deleted';
		$this->db->update($this->_table,$data);

		return $this->db->affected_rows();	
	}
	public function generateDropdown($options = [])
	{
		$this->db->select("id, CONCAT(fname,' ',lname) AS name",false)
			 ->from($this->_table)
			 ->where('status','active');

		if(!empty($options))
		{
			foreach ($options as $key => $value) 
			{
				$this->db->where($key,$value);
			}
		}

		$this->db->order_by('fname');
			 
		$result = $this->db->get()->result();

		$data = [];

        foreach ($result as $row)
        {
            $data[$row->id] = $row->name;
        }

        return $data;
	}
	////////////////////////////
	// Password Manipulation //
	////////////////////////////
	public static function hash($password) 
	{
		return crypt($password,self::$algo .self::$cost .'$'.self::unique_salt());
	}

	public static function unique_salt() 
	{
		return substr(sha1(mt_rand()),0,22);
	}
}