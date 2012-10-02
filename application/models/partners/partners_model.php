<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partners_model extends MY_Model {
	
	//Database table of the Model
	protected $_table = 'exp_cd_partners';
	
	public function select($query_array,$sort_by,$sort_order,$limit = null, $offset = null)
	{
		//Selects and returns all records from table-----------------------------------------------
		$this->db->select('p.*,u.name as ugroup,c.name,pc.postalcode, pt.company as mother_name, pt.id as mother_id');
		$this->db->join('exp_cd_partners AS pt','pt.id = p.mother_fk','LEFT');
		$this->db->join('exp_cd_user_groups AS u','u.id = p.ugroup_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
		//Filters
		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('p.postalcode_fk',$query_array['postalcode_fk']);
		if(isset($query_array['partner_type']))
		{
			if($query_array['partner_type'] == 'cus')
			{
				$this->db->where('p.is_customer',1);
				$this->db->where('p.is_vendor',0);
			}
				
			elseif ($query_array['partner_type'] == 'ven')
			{
				$this->db->where('p.is_customer',0);
				$this->db->where('p.is_vendor',1);	
			}
			elseif ($query_array['partner_type'] == 'cus_ven')
			{
				$this->db->where('p.is_customer',1);
				$this->db->where('p.is_vendor',1);
			}	
		}
		//Search Query
		if(strlen($query_array['q']))
		{
			$this->db->like('p.id',$query_array['q']);
			$this->db->or_like('p.company',$query_array['q']);
		}

		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
			
		//Retreives only the ACTIVE records	
		$this->db->where('p.status','active');
		
		$this->db->order_by($sort_by,$sort_order);

		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
		//Counts the TOTAL rows in the Table-------------------------------------------------------
		$this->db->select('COUNT(*) as count',false);
		$this->db->where('status','active');

		if(strlen($query_array['postalcode_fk']))
			$this->db->where_in('postalcode_fk',$query_array['postalcode_fk']);
		if(isset($query_array['partner_type']))
		{
			if($query_array['partner_type'] == 'cus')
			{
				$this->db->where('is_customer',1);
				$this->db->where('is_vendor',0);
			}
				
			elseif ($query_array['partner_type'] == 'ven')
			{
				$this->db->where('is_customer',0);
				$this->db->where('is_vendor',1);	
			}
			elseif ($query_array['partner_type'] == 'cus_ven')
			{
				$this->db->where('is_customer',1);
				$this->db->where('is_vendor',1);
			}		
		}
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
	
	public function dropdown($partner_type = null, $mothers = false)
	{
		$this->db->select('p.id,p.company,c.name as city');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
		
		if($partner_type != null)
		{
			if(!in_array($partner_type,array('vendors','customers')))
				return false;
				
			if($partner_type == 'vendors')
			{
				$this->db->where_in('p.is_vendor',1);
				$empty = '- Добавувач -';
			}
				
			if($partner_type == 'customers')
			{
				$this->db->where_in('p.is_customer',1);
				$empty = '- Купувач -';
			}
				
		}
		
		if($mothers)
		{
			$this->db->where('p.is_mother',1);
			$empty = '- Седиште -';
		}
			
					
		$this->db->where('p.status','active');		
		$this->db->order_by('p.postalcode_fk','asc');
		$this->db->order_by('p.company','asc');
		
		$query = $this->db->get($this->_table.' AS p');
		
		$options = array();
		$options[''] = $empty;  // first item in list is 'empty'
		$prevState = '';
		
		foreach ($query->result_array() as $row)
		{
		    if($prevState == $row['city'])
		    {
		        ${$prevState}[$row['id']]='&nbsp;&nbsp;'.$row['company'].'&nbsp;&nbsp;';
		    }
		    else
		    {
		        if($prevState!='')
		        	{$options[$prevState]=$$prevState;};
		        
		        $prevState = $row['city'];
		        $$prevState = array();
		        ${$prevState}[$row['id']]='&nbsp;&nbsp;'.$row['company'].'&nbsp;&nbsp;';
		    }
		}
		return $options;
	}
	/**
	 * Searches for partners match by term provided.
	 * Limit search results by options restrictions.
	 * @param  string $term    search term
	 * @param  Array  $options restriction options
	 * @return Array of Objects          
	 */
	public function partners_search($term, Array $options)
	{
		$this->db->select('id, company')
	    	->like('company', $term, 'after');

	    if(isset($options['is_vendor']))
	    	$this->db->where('is_vendor',$options['is_vendor']);
	   	if(isset($options['is_mother']))
	    	$this->db->where('is_mother',$options['is_mother']);
	    if(isset($options['is_customer']))
	    	$this->db->where('is_customer',$options['is_customer']);

   		return $this->db->get($this->_table)->result();
	}			
	
	public function select_single($id)
	{
		//Selects and returns all records from table
		$this->db->select('p.*,u.name as ugroup,c.name,pc.postalcode');
		$this->db->join('exp_cd_user_groups AS u','u.id = p.ugroup_fk','LEFT');
		$this->db->join('exp_cd_postalcode AS pc','pc.id = p.postalcode_fk','LEFT');
		$this->db->join('exp_cd_cities AS c','c.id = pc.city_fk','LEFT');
			
		//Retreives only the record where ID=$ID
		$this->db->where('p.id',$id);
		//Retreives only the ACTIVE records	
		$this->db->where('p.status','active');
		
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

		//Retreives only the ACTIVE records	
		$this->db->where('p.status','active');
		
		return $this->db->get($this->_table.' AS p')->result();
	}
	
	public function insert ($data = array())
	{	
		if(!isset($data['postalcode_fk']))
			$data['postalcode_fk'] = 1;
			
		if(isset($data['mother_fk']) AND $data['mother_fk'] == '')
			$data['mother_fk'] = null;
			
		// Inserts the whole data array into the database table
		$this->db->insert($this->_table,$data);
		
		return $this->db->insert_id();
	}
	
	public function update($id,$data = array())
	{	
		/*
		 * If after update, is_customer, is_vendor, is_mother
		 * flags has been unset, sets them back to 0.
		 */
		if(!isset($data['is_customer']))
				$data['is_customer'] = 0;	
				
		if(!isset($data['is_vendor']))
				$data['is_vendor'] = 0;
				
		if(!isset($data['is_mother']))
				$data['is_mother'] = 0;

		/*
		 *  If Mother_fk has been set, and its empty,
		 *  sets the corresponding attribute to null (default)
		 */
		if(isset($data['mother_fk']) AND $data['mother_fk'] == '')
				$data['mother_fk'] = null;
		
		//This ID
		$this->db->where('id',$id);
		
		//Updating
		$this->db->update($this->_table,$data);
		
		return $this->db->affected_rows();
	}
	
	public function delete($id)
	{
		//Updates the status to 'deleted'
		$data['status'] = 'deleted';
		$this->db->where('id',$id);
		$this->db->update($this->_table,$data);

		return $this->db->affected_rows();
	}	
}