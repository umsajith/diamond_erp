<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends CI_Model {

	protected $_table = 'users';

	private static $algo = '$2a';

	private static $cost = '$10';

	public function check_login($username, $password)
	{	
		$user = $this->db->select('id,location_id,ugroup_fk,fname,lname,username,is_admin,password')
	                ->where('username',$username)
	                ->limit(1)
	                ->get('exp_cd_employees')->row();

	    if($user)
	    {
	    	if(self::check_password($user->password,$password))
	    	{
	    		$permission = $this->_permissions($user->ugroup_fk);
	    		if($permission)
	    		{
	    			$this->_set_userdata($user,$permission['open_modules'],$permission['nav_modules']);	
	    			return $user;
	    		}
	    		else
	    			return false;
	    	}
	    }	               
        return false;
	}

	public function logout()
	{
		$this->session->sess_destroy();
	}

	public static function unique_salt() 
	{
		return substr(sha1(mt_rand()),0,22);
	}

	public static function hash($password) 
	{
		return crypt($password,self::$algo .self::$cost .'$'.self::unique_salt());
	}

	public static function check_password($hash, $password) 
	{
		$full_salt = substr($hash, 0, 29);
		$new_hash = crypt($password, $full_salt);
		return ($hash == $new_hash);
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

		if($modules)
		{

			$open_modules = array();
            foreach($modules as $module)
				array_push($open_modules,$module->controller);
			
			$data = array();
			$data['open_modules'] = $open_modules;
			$data['nav_modules'] = $modules;
			
			return $data;
		}

		return false;
	}
	
	private function _set_userdata($user,$open_modules,$nav_modules)
	{
		 $data = array
				(
					'username'  => $user->username,
                	'name'     => $user->fname.' '.$user->lname,
					'userid' => $user->id,
					'location' => $user->location_id,
					'admin' => $user->is_admin,
					'default_module' => $open_modules[0],
                    'open_modules' => $open_modules,
                    'nav_modules' => $nav_modules,
               		'logged_in' => true
				);
				
		$this->session->set_userdata($data);
	}
}