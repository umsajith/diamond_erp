<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cod_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_order_details';

	public $before_update = ['setNull'];

	public $validate = [
        [ 'field' => 'order_fk', 	'label' => '','rules' => 'required'],
		[ 'field' => 'prodname_fk', 'label' => '','rules' => 'required'],
		[ 'field' => 'quantity', 	'label' => '','rules' => 'trim|required|greater_than[0]'],
		[ 'field' => 'returned_quantity', 'label' => '','rules' => 'trim|greater_than[0]']
    ];
	
	public function select($options = array())
	{
		//Selects and returns all records from table
		$this->db->select('o.*,p.prodname,p.id AS pid,pc.pcname,u.uname');
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		
		//Qualifications
		if (isset($options['id']))
			$this->db->where('o.order_fk',$options['id']);

		//Sort
		if (isset($options['sory_by']) AND isset($options['sort_direction']))
			$this->db->order_by($options['sort_by'],$options['sort_direction']);

		return $this->db->get($this->_table.' AS o')->result();
	}
	
	public function total_distributed($ids)
	{
		$this->db->select('o.id,o.commision_rate,p.prodname,p.commision,pc.pcname,u.uname');
		$this->db->select_sum('o.quantity');
		
		$this->db->join('exp_cd_products AS p','p.id = o.prodname_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');

		$this->db->where_in('o.order_fk',$ids);
		
		$this->db->group_by('o.prodname_fk');

		return $this->db->get($this->_table.' AS o')->result();
	}

	////////////////
	// OBSERVERS //
	////////////////
	protected function setNull($row)
	{
		// Default returned value is 0
		if(empty($row['returned_quantity'])) $row['returned_quantity'] = 0;

		return $row;
	}
} 