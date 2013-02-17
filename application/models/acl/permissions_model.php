<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permissions_model extends MY_Model {

	protected $_table = 'exp_cd_permissions';

	public function get_permissions($role_id,$type)
	{
		$this->db->select('resource_id')
			->from($this->_table)
			->where('role_id',$role_id);

		if(!in_array($type,array('allow','deny')))
			return false;

		$this->db->where('permission',$type);

		$results = $this->db->get()->result();

		$data = array();
		foreach ($results as $resource) {
			array_push($data, $resource->resource_id);
		}

		return $data;
	}
	/**
	 * Display resources from permissions table
	 * based on supplied role id 
	 * Used in roles/view.
	 * @param  Integer $role_id
	 * @return Object
	 */
	public function get_resources_by_role_id($role_id)
	{
		$this->db->select('pm.id,pm.permission,p.title AS ptitle,c.title AS ctitle,c.controller')
			->from('exp_cd_permissions AS pm')
			->join('exp_cd_resources AS c','c.id = pm.resource_id','LEFT')
			->join('exp_cd_resources AS p','p.id = c.parent_id','LEFT')
			->where('pm.role_id',$role_id)
			->order_by('c.parent_id')
			->order_by('c.order');
		return $this->db->get()->result();
	}

	public function insert_role_resource($role_id,$resource_id,$permission)
	{
		if($this->check_duplicate_role_resource($role_id,$resource_id))
			return false;

		$this->db->set('role_id',$role_id);
		$this->db->set('resource_id',$resource_id);
		$this->db->set('permission',$permission);

		$this->db->insert($this->_table);

		return $this->db->insert_id();
	}

	private function check_duplicate_role_resource($role_id,$resource_id)
	{
		$this->db->select()
			->from($this->_table)
			->where('role_id',$role_id)
			->where('resource_id',$resource_id);
		return $this->db->get()->result();
	}
}