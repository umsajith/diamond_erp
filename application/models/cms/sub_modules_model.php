<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sub_modules_model extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select($options = array())
	{
		$this->db->select('s.*, m.title AS mtitle');
		$this->db->from('exp_cd_sub_modules AS s');
		$this->db->join('exp_cd_modules AS m','m.id = s.module_id','LEFT');
		
		if(isset($options['module_id']))
		{
			$this->db->where('s.module_id',$options['module_id']);
			$this->db->order_by('s.order','asc');
		}
		else
		{
			$this->db->order_by('m.order','asc');
			$this->db->order_by('s.order','asc');
		}

		$this->db->where('s.status !=','deleted');
			
		return $this->db->get()->result();
	}
	
	function select_by_module($module)
	{
		$this->db->select('sm.*');
		$this->db->from('exp_cd_sub_modules AS sm');
        $this->db->join('exp_cd_modules AS m','m.id = sm.module_id','LEFT');
		
		$this->db->where('m.controller',$module);
		
		$this->db->where('m.status','active');
		
		$this->db->where('sm.status','active');
		
		$this->db->where('sm.is_visible',1);
		
		$this->db->order_by('sm.order','asc');
			
		return $this->db->get()->result();
	}
	
	function select_by_module_id($module_id)
	{
		$this->db->select('*');
		$this->db->from('exp_cd_sub_modules');
		
		$this->db->where('module_id',$module_id);
		
		$this->db->where('status','active');
		
		$this->db->order_by('order','asc');
			
		return $this->db->get()->result();
	}
	
	function select_by_controller($controller)
	{
		$this->db->select('module_id');
		
		$this->db->from('exp_cd_sub_modules');
		
		$this->db->where('controller',$controller);
		
		$this->db->where('status','active');
		
		$module_id = $this->db->get()->row();
		
		return $this->select_by_module_id($module_id->module_id);
	}

	function select_single($id)
	{
		$this->db->select('*');
		$this->db->from('exp_cd_sub_modules');
		
		$this->db->where('id',$id);

		$this->db->where('status !=','deleted');
		
		return $this->db->get()->row();
	}
	
	function insert ($data = array())
	{
		if(isset($data['order']) && $data['order'] == '')
			$data['order'] = null;
			
		// Inserts the whole data array into the database table
		$this->db->insert('exp_cd_sub_modules',$data);
		
		return $this->db->insert_id();
	}
	
	function update($id,$data = array())
	{
		if(isset($data['method']) && $data['method'] == '')
			$data['method'] = null;
		if(isset($data['permalink']) && $data['permalink'] == '')
			$data['permalink'] = null;
		if(isset($data['order']) && $data['order'] == '')
			$data['order'] = null;
			
		if(!isset($data['is_visible']))
			$data['is_visible'] = 0;
			
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update('exp_cd_sub_modules',$data);
		
		return $this->db->affected_rows();
	}
	
	function delete($id)
	{
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update('exp_cd_sub_modules',$data);

		return $this->db->affected_rows();
	}	
}