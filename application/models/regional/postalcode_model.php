<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PostalCode_model extends MY_Model {
	protected $_table = 'exp_cd_postalcode';

	public function generateDropdown($options = [])
	{
		$this->db->select('p.id,c.name')
		    	->from($this->_table.' AS p')
		    	->join('exp_cd_cities AS c', 'c.id = p.city_fk')
		    	->order_by('c.name','asc');
    	
    	$this->db->order_by('c.name');
			 
		$result = $this->db->get()->result();

		$data = [];

        foreach ($result as $row)
        {
            $data[$row->id] = $row->name;
        }

        return $data;
	}
}