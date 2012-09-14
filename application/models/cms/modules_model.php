<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Modules_model extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	function select()
	{
		/*
		 * Selects all records which are
		 * not set as DELETED
		 */
		$this->db->select('c.*,p.title as parent_title');
		$this->db->from('exp_cd_modules as c');
		$this->db->join('exp_cd_modules as p','p.id = c.parent_id','left');
		
		$this->db->where('c.status !=','deleted');

		$this->db->order_by('c.order','asc');
			
		return $this->db->get()->result();
	}
	
	function select_active()
	{	
		/*
		 * Selects all ACTIVE records,
		 * for creating main nav. module
		 */
		$this->db->select('*');
		$this->db->from('exp_cd_modules');

		$this->db->where('status','active');
		
		$this->db->order_by('order','asc');
			
		return $this->db->get()->result();
	}
	
	function select_single($id)
	{
		/*
		 * Selects single record by ID
		 */
		$this->db->select('*');
		$this->db->from('exp_cd_modules');
		
		$this->db->where('id',$id);

		$this->db->where('status !=','deleted');
		
		return $this->db->get()->row();
	}
	
	function select_id($controller)
	{
		/*
		 * Selects single record by CONTROLLER
		 */
		$this->db->select('id');
		$this->db->from('exp_cd_modules');
		
		$this->db->where('controller',$controller);

		$this->db->where('status !=','deleted');
		
		return $this->db->get()->row();
	}
	
	function insert ($data = array())
	{
		if(isset($data['order']) && $data['order'] == '')
			$data['order'] = null;
			
		// Inserts the whole data array into the database table
		$this->db->insert('exp_cd_modules',$data);
		
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
		if(isset($data['parent_id']) && $data['parent_id'] == '')
			$data['parent_id'] = null;
			
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update('exp_cd_modules',$data);
		
		return $this->db->affected_rows();
	}
	
	function dropdown()
	{
       $data = array();
       $this->db->select('id,title');
       $this->db->order_by('title','asc');
       $this->db->where('status','active');
       
       $results = $this->db->get('exp_cd_modules')->result();
       
       $data[''] =  '--';
       
       foreach ($results as $row)
        {
            $data[$row->id]= $row->title;
        }
        
        return $data;
    }
	
	function delete($id)
	{
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update('exp_cd_modules',$data);

		return $this->db->affected_rows();
	}	
}