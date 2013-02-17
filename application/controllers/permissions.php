<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends MY_Controller {

	/**
	 * Deletes entry by passed primary_key
	 * @param  integer $id primary_key
	 * @return redirects with success/error message
	 */
	public function delete($id)
	{
		$obj = $this->Permissions_model->get($id);

		if(!$obj)
			$this->utilities->flash('void',$_SERVER['HTTP_REFERER']);
		
		if($this->Permissions_model->delete($obj->id))
			$this->utilities->flash('delete',$_SERVER['HTTP_REFERER']);
		else
			$this->utilities->flash('error',$_SERVER['HTTP_REFERER']);
	}
 	
}