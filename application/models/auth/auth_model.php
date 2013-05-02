<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends MY_Model {

	protected $_table = 'exp_cd_employees';

	private static $algo = '$2a';

	private static $cost = '$10';

	public function check_login($username, $password)
	{	
		$user = $this->db->select("id,location_id,role_id,
				CONCAT(fname,' ',lname) AS name,username,is_admin,password",FALSE)
	                ->where('username',$username)
	                ->limit(1)
	                ->get($this->_table)->row();

	    if($user)
	    {
	    	if(self::check_password($user->password,$password))	
				return $user;
	    }	               
		return false;
	}

	public function set_session($user,$modules,$allow_resources,$deny_resources)
	{
		 $data = [
					'logged_in'      => true,
					'username'       => $user->username,
					'name'           => $user->name,
					'userid'         => $user->id,
					'role_id'        => $user->role_id,
					'location'       => $user->location_id,
					'admin'          => $user->is_admin,
					'modules'        => $modules,
					'allow_res'      => $allow_resources,
					'deny_res'       => $deny_resources,
					'default_module' => $modules[0]->controller
				];
				
		$this->session->set_userdata($data);
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

	public function logout()
	{
		$this->session->sess_destroy();
	}	
}