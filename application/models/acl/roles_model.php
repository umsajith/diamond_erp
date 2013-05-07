<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles_model extends MY_Model {
	protected $_table = 'exp_cd_roles';

	public $before_create = ['setNull'];

	public $before_update = ['setNull'];

	public function dropdown_master()
	{
       $this->db->select('id,name')
	       	->order_by('name','asc');
	       	
       $results = $this->db->get('exp_cd_roles')->result();
       
       $data = [];
       $data[''] =  '- Корисничка Група -';
  
       foreach ($results as $row)
       {
            $data[$row->id]= $row->name;
       }
        
        return $data;
    }

	protected function setNull($row)
    {
        if(isset($row['parent_id']) AND trim($row['parent_id'] == '')) $row['parent_id'] = null;

        return $row;
    }
}