<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function login($username, $password)
	{
		if($user = $this->_check_login($username,$password))
		{
			$permission = $this->_permissions($user->ugroup_fk);
			if(!$permission)
				return false;
			else
				$this->_set_userdata($user,$permission['open_modules'],$permission['nav_modules']);
		}
		else
			return false;
		
		return $user;
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
	}
	
	private function _check_login ($username = false,$password = false)
	{
		$this->db->select('e.id,e.ugroup_fk,e.fname,e.lname,e.username,e.is_admin');
		$this->db->from('exp_cd_employees AS e');
			
		$this->db->where('e.username',trim($username));
		$this->db->where('e.password',sha1($password));
		$this->db->where('e.status','active');
		$this->db->where('e.can_login',1);
		$this->db->limit(1);

		return $this->db->get()->row();
	}	
	
	private function _permissions($id = false)
	{   
		$this->db->select('m.folder,m.controller, m.title');
		$this->db->from('exp_cd_permissions AS p');
        $this->db->join('exp_cd_modules as m','m.id = p.module_id', 'LEFT');
			
		$this->db->where('p.user_group_id',$id);
        $this->db->where('m.status','active');
        $this->db->where('m.parent_id',null);
        
        $this->db->order_by('m.order','asc');

		$modules = $this->db->get()->result();
		if(!$modules)
			return false;
		else
		{
			$open_modules = array();
            foreach($modules as $module)
				array_push($open_modules,$module->controller);
			
			$data = array();
			$data['open_modules'] = $open_modules;
			$data['nav_modules'] = $modules;
			
			return $data;
		}
	}
	
	private function _set_userdata($user,$open_modules,$nav_modules)
	{
		 $data = array
				(
					'username'  => $user->username,
                	'name'     => $user->fname.' '.$user->lname,
					'userid' => $user->id,
					'admin' => $user->is_admin,
					'default_module' => $open_modules[0],
                    'open_modules' => $open_modules,
                    'nav_modules' => $nav_modules,
               		'logged_in' => true
				);
				
		$this->session->set_userdata($data);
	}
}