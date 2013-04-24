<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles_model extends MY_Model {
	protected $_table = 'exp_cd_roles';

	public $before_create = array('set_nulls');
	public $before_update = array('set_nulls');

	public function dropdown_master()
	{
       $this->db->select('id,name')
	       	->order_by('name','asc');
	       	
       $results = $this->db->get('exp_cd_roles')->result();
       
       $data = array();
       $data[''] =  '- Корисничка Група -';
  
       foreach ($results as $row)
            $data[$row->id]= $row->name;
        
        return $data;
    }

	protected function set_nulls($role)
    {
        if(isset($role['parent_id']) AND trim($role['parent_id'] == ''))
        	$role['parent_id'] = null;
        return $role;
    }
}