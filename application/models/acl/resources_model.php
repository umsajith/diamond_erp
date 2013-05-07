<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Resources_model extends MY_Model {
	
	protected $_table = 'exp_cd_resources';

 	public $before_create = ['setNull'];

	public $before_update = ['setNull'];

	public function get_all_resources()
	{
		$this->db->select('c.id, p.title AS ptitle,c.title AS ctitle,
			c.permalink,c.controller,c.method,c.order,c.master')
			->from('exp_cd_resources AS c')
			->join('exp_cd_resources AS p','p.id = c.parent_id','LEFT')
			->order_by('c.parent_id')
			->order_by('c.order');
		return $this->db->get()->result();
	}

	public function get_all_resources_by_ids($ids = [])
	{
		$this->db->select('c.id, p.title AS ptitle,c.title AS ctitle,
			c.permalink,c.controller,c.method,c.order,c.master')
			->from('exp_cd_resources AS c')
			->join('exp_cd_resources AS p','p.id = c.parent_id','LEFT')
			->where_in('c.id',$ids)
			->order_by('c.parent_id')
			->order_by('c.order');
		return $this->db->get()->result();
	}
	
	public function dropdown_master()
	{
       $this->db->select('id,title')
	       	->order_by('title','asc')
	       	->where('master',1);

       
       $results = $this->db->get('exp_cd_resources')->result();
       
       $data = [];
       $data[''] =  '- Masters -';
  
       foreach ($results as $row)
            $data[$row->id]= $row->title;
        
        return $data;
    }

    public function dropdown_all()
	{
       $this->db->select('c.id,c.title,c.master,p.title AS ptitle')
       		->from('exp_cd_resources AS c')
       		->join('exp_cd_resources AS p','p.id = c.parent_id','LEFT')
       		->order_by('c.master','desc')
       		->order_by('c.parent_id','asc')
	       	->order_by('c.order','asc');

       $results = $this->db->get()->result();
       
       $data = [];
       $data[''] =  ' ';
  
       foreach ($results as $row)
       {
       		if($row->master == 1)
            	$data[$row->id]= '&raquo; '.$row->title;
            else
            	$data[$row->id]= $row->ptitle. ' &raquo; ' .$row->title;	       	
       }

        return $data;
    }

    ///////////////
    // OBSERVERS //
    ///////////////
    protected function setNull($resource)
    {
        if(trim($resource['parent_id'] == '')) $resource['parent_id'] = null;

        if(!isset($resource['visible'])) $resource['visible'] = 0;

        return $resource;
    }

    //------------- Methods used for ACL ------------------
    
    public function get_resources($permissions)
    {
		$this->db->select()->from($this->_table)
			->where_in('id',$permissions)
			->where('master',1);
		return $this->db->get()->result();
    }

    public function get_sub_modules_by_parent($id)
	{
		$this->db->select()->from($this->_table)
			->where('parent_id',$id)
			->where_in('id',$this->get_allowed_resources())
			->where('visible',1)
			->where('active',1)
			->where('master',0)
			->order_by('order','asc');

		$data = $this->db->get()->result();

		if($data)
			return $data;

		return false;
	}

	public function get_sub_modules_by_class($class,$method)
	{
		//If method is supplied - Check if its in the list of
		//Permission that are denied
		if($method)
		{
			if($this->check_if_permission_deny($class,$method))
				return false;
		}

		$this->db->select('parent_id')->from($this->_table)
			->where('controller',$class)
			->where_in('id',$this->get_allowed_resources())
			->where('active',1)
			->where('master',0);

		$master = $this->db->get()->row();

		if($master)
			return $this->get_sub_modules_by_parent($master->parent_id);

		return false;
	}

	private function check_if_permission_deny($class,$method)
	{
		$this->db->select('id')->from($this->_table)
			->where('controller',$class)
			->where('method',$method)
			->where('active',1)
			->where('master',0);

		$denied_resource = $this->db->get()->row();

		if($denied_resource)
			return in_array($denied_resource->id, $this->get_denied_resources());

		return false;
	}

    private function get_allowed_resources()
	{
		return $this->session->userdata('allow_res');
	}

	private function get_denied_resources()
	{
		return $this->session->userdata('deny_res');
	}
}