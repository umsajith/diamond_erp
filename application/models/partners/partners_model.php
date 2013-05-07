<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partners_model extends MY_Model {
	
	protected $_table = 'exp_cd_partners';

	public $before_create = ['setNull'];

	public $before_update = ['setNull'];
	
	public function select($query_array,$sort_by,$sort_order,$limit = null, $offset = null)
	{
		//Selects and returns all records from table-----------------------------------------------
		$this->db->select('p.*,c.name,pc.postalcode, pt.company as mother_name, pt.id as mother_id');
		$this->db->join('exp_cd_partners AS pt','pt.id = p.mother_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
		//Filters
		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('p.postalcode_fk',$query_array['postalcode_fk']);

		//Search Query
		if(strlen($query_array['q']))
		{
			$this->db->like('p.id',$query_array['q']);
			$this->db->or_like('p.company',$query_array['q']);
		}

		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
		
		$this->db->order_by($sort_by,$sort_order);

		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
		//Counts the TOTAL rows in the Table-------------------------------------------------------
		$this->db->select('COUNT(*) as count',false);

		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('postalcode_fk',$query_array['postalcode_fk']);

		//Search Query
		if(strlen($query_array['q']))
		{
			$this->db->like('id',$query_array['q']);
			$this->db->or_like('company',$query_array['q']);
		}
		
		$temp = $this->db->get($this->_table)->row();
		
		$data['num_rows'] = $temp->count;
		//-----------------------------------------------------------------------------------------
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	/**
	 * Generates data for dropdown menu
	 * @param  Array $options Array of options
	 * @return Array
	 */
	public function generateDropdown($options = [])
	{
		$this->db->select('id, company')
			 ->from($this->_table);

		if(!empty($options))
		{
			foreach ($options as $key => $value) 
			{
				$this->db->where($key,$value);
			}
		}

		$this->db->order_by('company');
			 
		$result = $this->db->get()->result();

		$data = [];

        foreach ($result as $row)
        {
            $data[$row->id] = $row->company;
        }

        return $data;
	}
	
	// public function dropdown($partner_type = null, $mothers = false)
	// {
	// 	$this->db->select('p.id,p.company,c.name as city');
	// 	$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
	// 	$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
	// 	if($partner_type != null)
	// 	{
	// 		if(!in_array($partner_type,array('vendors','customers')))
	// 			return false;
				
	// 		if($partner_type == 'vendors')
	// 		{
	// 			$this->db->where_in('p.is_vendor',1);
	// 			$empty = '- Добавувач -';
	// 		}
				
	// 		if($partner_type == 'customers')
	// 		{
	// 			$this->db->where_in('p.is_customer',1);
	// 			$empty = '- Купувач -';
	// 		}
				
	// 	}
		
	// 	if($mothers)
	// 	{
	// 		$this->db->where('p.is_mother',1);
	// 		$empty = '- Седиште -';
	// 	}
				
	// 	$this->db->order_by('p.postalcode_fk','asc');
	// 	$this->db->order_by('p.company','asc');
		
	// 	$query = $this->db->get($this->_table.' AS p');
		
	// 	$options = array();
	// 	$options[''] = $empty;  // first item in list is 'empty'
	// 	$prevState = '';
		
	// 	foreach ($query->result_array() as $row)
	// 	{
	// 	    if($prevState == $row['city'])
	// 	    {
	// 	        ${$prevState}[$row['id']]='&nbsp;&nbsp;'.$row['company'].'&nbsp;&nbsp;';
	// 	    }
	// 	    else
	// 	    {
	// 	        if($prevState!='')
	// 	        	{$options[$prevState]=$$prevState;};
		        
	// 	        $prevState = $row['city'];
	// 	        $$prevState = array();
	// 	        ${$prevState}[$row['id']]='&nbsp;&nbsp;'.$row['company'].'&nbsp;&nbsp;';
	// 	    }
	// 	}
	// 	return $options;
	// }
	/**
	 * Searches for partners match by term provided.
	 * Limit search results by options restrictions.
	 * @param  string $term    search term
	 * @param  Array  $options restriction options
	 * @return Array of Objects          
	 */
	// public function partners_search($term, $options = [])
	// {
	// 	$this->db->select('id, company')
	//     	->like('company', $term, 'after');

	//     if(isset($options['is_vendor']))
	//     	$this->db->where('is_vendor',$options['is_vendor']);
	//    	if(isset($options['is_mother']))
	//     	$this->db->where('is_mother',$options['is_mother']);
	//     if(isset($options['is_customer']))
	//     	$this->db->where('is_customer',$options['is_customer']);

 //   		return $this->db->get($this->_table)->result();
	//}			
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,c.name,pc.postalcode');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
			
		//Retreives only the record where ID=$ID
		$this->db->where('p.id',$id);
		
		return $this->db->get($this->_table.' AS p')->row();
	}

	public function select_sub($mother_id)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,c.name,pc.postalcode');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
			
		//Retreives only the record where MOTHER_FK=$ID
		$this->db->where('p.mother_fk',$mother_id);
		
		return $this->db->get($this->_table.' AS p')->result();
	}

	////////////////
	// OBSERVERS //
	////////////////
	protected function setNull($row)
	{
		if(!strlen($row['mother_fk'])) $row['mother_fk'] = null;

		// if(!isset($row['is_customer'])) $row['is_customer'] = 0;	
				
		// if(!isset($row['is_vendor'])) $row['is_vendor'] = 0;
				
		if(!isset($row['is_mother'])) $row['is_mother'] = 0;

		return $row;
	}
}