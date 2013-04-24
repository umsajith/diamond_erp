<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bomdetails_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_bom_details';

	public function select_by_bom_id($bom_id)
	{
		//Selects and returns all records from table
		$this->db->select('b.id,b.bom_fk,b.quantity,b.prodname_fk,p.prodname,pc.pcname,u.uname');
		$this->db->join('exp_cd_products AS p','p.id = b.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Qualifications
		$this->db->where('b.bom_fk',$bom_id);
		
		$this->db->order_by('b.quantity','desc');
		
		return $this->db->get($this->_table.' AS b')->result();
	}
}