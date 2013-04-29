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

		if(!$obj) air::flash('void',$_SERVER['HTTP_REFERER']);
		
		if($this->Permissions_model->delete($obj->id))
			air::flash('delete',$_SERVER['HTTP_REFERER']);
		else
			air::flash('error',$_SERVER['HTTP_REFERER']);
	}
 	
}