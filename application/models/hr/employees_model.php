<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employees_model extends MY_Model {
	
	protected $_table = 'exp_cd_employees';

	private static $algo = '$2a';

	private static $cost = '$10';

	public $before_create = ['setNull','processPassword'];

	public $before_update = ['setNull','processPassword'];
	
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
		if($sort_by == 'employee') $sort_by = "e.fname";
			
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

	/**
	 * Triggered when user logs in.
	 * Updates the last_login DATETIME
	 * @param  integer $id employee PK
	 */
	public function lastLogin($id)
	{
		$this->db->set('last_login',date('Y-m-d H:i:s'));
		$this->db->where('id',$id);
		$this->db->update($this->_table);
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

	////////////////
	// OBSERVERS //
	////////////////
	protected function setNull($row)
	{
		if(!strlen($row['start_date'])) $row['start_date'] = null;
		if(!strlen($row['stop_date'])) $row['stop_date'] = null;	
		
		if(!strlen($row['can_login'])) $row['can_login'] = 0;
		if(!strlen($row['is_manager'])) $row['is_manager'] = 0;	
		if(!strlen($row['is_distributer'])) $row['is_distributer'] = 0;	
		if(!strlen($row['fixed_wage_only'])) $row['fixed_wage_only'] = 0;

		if(!strlen($row['manager_fk'])) $row['manager_fk'] = null;
		if(!strlen($row['role_id'])) $row['role_id'] = null;	
		if(!strlen($row['location_id'])) $row['location_id'] = null;

		return $row;
	}

	protected function processPassword($row)
	{
		if(isset($row['password']) AND strlen($row['password']))
		{
			$row['password'] = self::hash($row['password']);
		}
		else
		{
			unset($row['password']);
		}

		return $row; 
	}
}