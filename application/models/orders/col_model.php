<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Col_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_orders_list';

	public $before_create = ['setDefaults'];

	public $validate = [
        [ 'field' => 'date', 'label' => 'date','rules' => 'trim|required'],
		[ 'field' => 'distributor_id', 'label' => 'distributor','rules' => 'required'],
		[ 'field' => 'ext_doc', 'label' => '','rules' => 'trim'],
		[ 'field' => 'locked', 'label' => '','rules' => 'trim'],
		[ 'field' => 'note', 'label' => '','rules' => 'trim']
    ];

	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects and returns all records from table----------------------------------------------------
		$this->db->select("ol.*, CONCAT(e.fname,' ',e.lname) AS distributor,
			CONCAT(em.fname,' ',em.lname) AS operator",false)
			->join('exp_cd_employees AS e','e.id = ol.distributor_id','LEFT')
			->join('exp_cd_employees AS em','em.id = ol.inserted_by','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['distributor_id']))
			$this->db->where_in('ol.distributor_id',$query_array['distributor_id']);

		//Search Query
		if(strlen($query_array['q']))
		{
			$this->db->like('ol.ext_doc',$query_array['q']);
			$this->db->or_like('ol.code',$query_array['q']);
		}

		//Sort
		if($sort_by == 'distributor_id')
			$sort_by = 'e.lname';
			
		$this->db->order_by($sort_by,$sort_order);
			
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$data['results'] = $this->db->get($this->_table.' AS ol')->result();
		
		//Counts the TOTAL rows in the Table------------------------------------------------------------
		
		$this->db->select("COUNT(ol.id) as count",false)
			->join('exp_cd_employees AS e','e.id = ol.distributor_id','LEFT');
		
		//Filter Qualifications
		if(strlen($query_array['distributor_id']))
			$this->db->where_in('ol.distributor_id',$query_array['distributor_id']);
		//Search Query
		if(strlen($query_array['q']))
		{
			$this->db->like('ol.ext_doc',$query_array['q']);
			$this->db->or_like('ol.code',$query_array['q']);
		}	
		
		$temp = $this->db->get($this->_table.' AS ol')->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}

	public function select_one($id)
	{
		$this->db->select("ol.*, CONCAT(e.fname,' ',e.lname) AS distributor,
			CONCAT(em.fname,' ',em.lname) AS operator",false)
			->join('exp_cd_employees AS e','e.id = ol.distributor_id','LEFT')
			->join('exp_cd_employees AS em','em.id = ol.inserted_by','LEFT')
			->where('ol.id',$id);

		return $this->db->get($this->_table.' AS ol')->row();
	}
	////////////////
	// OBSERVERS //
	////////////////
	protected function setDefaults($row)
	{
		$row['inserted_by'] = $this->session->userdata('userid');
		return $row;
	}
}