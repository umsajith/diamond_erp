<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employees_model extends CI_Model {
	
	//Database table of the Model
	var $table = 'exp_cd_employees';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table
		$this->db->select('e.*,u.name as ugroup,c.name,pc.postalcode,d.department,p.position');
		$this->db->from('exp_cd_employees AS e');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_user_groups AS u','u.id = e.ugroup_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['poss_fk']))
			$this->db->where_in('e.poss_fk',$query_array['poss_fk']);
		if(strlen($query_array['ugroup_fk']))
			$this->db->where_in('e.ugroup_fk',$query_array['ugroup_fk']);

		//Sort
		if($sort_by == 'employee')
			$sort_by = "e.fname";
			
		$this->db->order_by($sort_by,$sort_order);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records, unless otherwise set	
		$this->db->where('e.status !=','deleted');
		
		$data['results'] = $this->db->get()->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select('COUNT(e.id) AS count');
		$this->db->from('exp_cd_employees AS e');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_user_groups AS u','u.id = e.ugroup_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');

		//Filter Qualifications
		if(strlen($query_array['poss_fk']))
			$this->db->where_in('e.poss_fk',$query_array['poss_fk']);
		if(strlen($query_array['ugroup_fk']))
			$this->db->where_in('e.ugroup_fk',$query_array['ugroup_fk']);
		
		$this->db->where('e.status !=','deleted');
		
		$temp = $this->db->get()->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('e.*,u.name as ugroup,c.name,pc.postalcode,d.department,p.position');
		$this->db->from('exp_cd_employees AS e');
		$this->db->join('exp_cd_positions AS p','p.id = e.poss_fk','LEFT');
		$this->db->join('exp_cd_departments AS d','d.id = p.dept_fk','LEFT');
		$this->db->join('exp_cd_user_groups AS u','u.id = e.ugroup_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = e.postcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
	
		$this->db->where('e.id',$id);
			
		$this->db->where('e.status !=','deleted');

		return  $this->db->get()->row();
	}
	
	function insert ($data = array())
	{
		//Hash the password
		if(isset($data['password']) && trim($data['password']!=''))
			$data['password'] = $this->_hash_password($data['password']);
		else
			$data['password'] = null;
			
		/*
		 * @TODO: If username set, and password set then:
		 *  - flag 'can_login' to 1
		 *  - set 'ugroup_fk' to 4 (default)
		 */
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->table,$data);
		
		return $this->db->insert_id();
	}
	
	private function _hash_password($password)
	{
		return sha1($password);
	}

	function update($id,$data = array())
	{	
		
		/*
		 * If the entry has been edited, and password not changed,
		 * hence, stays '', the password is unset
		 * 
		 * If new password has been provided, hashes it
		 * and stores it to the same variable
		 */
		if(strlen($data['password']))
			$data['password'] = $this->_hash_password($data['password']);
		else 
			unset($data['password']);
		
		//If values are set and empty, sets them to null
		if(isset($data['fixed_wage']) && $data['fixed_wage']=='')
			$data['fixed_wage'] = null;
		if(isset($data['social_cont']) && $data['social_cont']=='')
			$data['social_cont'] = null;
		if(isset($data['comp_mobile_sub']) && $data['comp_mobile_sub']=='')
			$data['comp_mobile_sub'] = null;		
		if(isset($data['start_date']) && $data['start_date']=='')
			$data['start_date'] = null;
		if(isset($data['stop_date']) && $data['stop_date']=='')
			$data['stop_date'] = null;	
		if(isset($data['manager_fk']) && $data['manager_fk']=='')
			$data['manager_fk'] = null;

		if(isset($data['ugroup_fk']) && $data['ugroup_fk']=='')
			$data['ugroup_fk'] = null;	
		
		//If the checkboxes are not checks, sets them to 0
		if(!isset($data['is_manager']))
			$data['is_manager'] = 0;	
		if(!isset($data['is_distributer']))
			$data['is_distributer'] = 0;	
		if(!isset($data['fixed_wage_only']))
			$data['fixed_wage_only'] = 0;
		if(!isset($data['can_login']))
			$data['can_login'] = 0;
			
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table,$data);
		
		return $this->db->affected_rows();
	}
	
	function last_login($id)
	{
		//Sets the Last_login time to now
		$this->db->set('last_login',date('Y-m-d H:m:s',now()));
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}
	
	function get_attr($id,$field)
	{
		$this->db->select($field);
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$this->db->limit(1);
		
		return $this->db->get()->row();
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