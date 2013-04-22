<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products_model extends MY_Model {
	
	protected $_table = 'exp_cd_products';
	
	public function select($query_array, $sort_by, $sort_order, $limit=null, $offset=null)
	{
		//Selects results by supplied criteria----------------------------------------------------------------
		$this->db->select('p.*,u.uname,pc.pcname,pt.ptname,w.wname,tr.rate');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_tax_rates AS tr','tr.id = p.tax_rate_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_product_type AS pt','pt.id = p.ptname_fk','LEFT');
		$this->db->join('exp_cd_warehouses AS w','w.id = p.wname_fk','LEFT');

		/*
		 * Search Filters
		 */
		if(strlen($query_array['ptname_fk']))
			$this->db->where_in('p.ptname_fk',$query_array['ptname_fk']);
		if(strlen($query_array['wname_fk']))
			$this->db->where_in('p.wname_fk',$query_array['wname_fk']);
		if(strlen($query_array['pcname_fk']))
			$this->db->where_in('p.pcname_fk',$query_array['pcname_fk']);

		//Sort by and Sort Order
		$this->db->order_by($sort_by ,$sort_order);
		
		//Pagination Limit and Offset
		$this->db->limit($limit , $offset);
	
		$this->db->where('p.status','active');
		
		$data['results'] = $this->db->get($this->_table.' AS p')->result();
		
		//Counts the TOTAL selected rows in the Table ---------------------------------------------------------
		
		$this->db->select('COUNT(*) as count',false);
		
		if(strlen($query_array['ptname_fk']))
			$this->db->where_in('ptname_fk',$query_array['ptname_fk']);
		if(strlen($query_array['wname_fk']))
			$this->db->where_in('wname_fk',$query_array['wname_fk']);
		if(strlen($query_array['pcname_fk']))
			$this->db->where_in('pcname_fk',$query_array['pcname_fk']);
			
		$this->db->where('status','active');
		
		$temp = $this->db->get($this->_table)->row();
		$data['num_rows'] = $temp->count;
		//--------------------------------------------------------------------------------------------
		
		//Returns the whole data array containing $results and $num_rows
		return $data;
	}
	
	public function select_single($id)
	{
		//Query
		$this->db->select('p.*,u.uname,pc.pcname,pt.ptname,w.wname,tr.rate');
		$this->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->db->join('exp_cd_tax_rates AS tr','tr.id = p.tax_rate_fk','LEFT');
		$this->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');
		$this->db->join('exp_cd_product_type AS pt','pt.id = p.ptname_fk','LEFT');
		$this->db->join('exp_cd_warehouses AS w','w.id = p.wname_fk','LEFT');
		
		$this->db->where('p.id',$id);
		
		$this->db->limit(1);

		$this->db->where('p.status','active');
		
		return $this->db->get($this->_table.' AS p')->row();
	}
	
	public function insert ($data = array())
	{		
		$this->db->insert($this->_table,$data);
		return $this->db->insert_id();
	}
	
	public function update ($id,$data = array())
	{
		if(!$data['salable'])
				$data['salable'] = 0;			
		if(!$data['purchasable'])
				$data['purchasable'] = 0;
		if(!$data['stockable'])
				$data['stockable'] = 0;
				
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

	public function generateDropdown($options = [])
	{
		$this->db->select('p.id,p.prodname,u.uname,pc.pcname')
			->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT')
			->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');

		if(isset($options['salable']))
			$this->db->where('p.salable',$options['salable']);
		if(isset($options['purchasable']))
			$this->db->where('p.purchasable',$options['purchasable']);
		if(isset($options['stockable']))
			$this->db->where('p.stockable',$options['stockable']);
				
		$this->db->where('p.status','active');

		$this->db->order_by('p.prodname','asc');
		
		return $this->db->get($this->_table.' AS p')->result();
	}
	
	public function get_products($type = '',$stockable = false,$dropdown = false,$empty = '--')
	{
		if(!in_array($type,array('salable','purchasable'))) die();
			
		//Query
		$this->db->select('p.id,p.prodname,u.uname,pc.pcname')
			->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT')
			->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');

		if($type == 'salable')
		{
			$this->db->where('p.salable',1);
			$empty = '- Производ -';
		}
			
		if($type == 'purchasable')
		{
			$this->db->where('p.purchasable',1);
			$empty = '- Артикл -';
		}
			
		if($stockable == true)
			$this->db->where('p.stockable',1);
				
		$this->db->where('p.status','active');

		$this->db->order_by('p.prodname','asc');
		
		$results = $this->db->get($this->_table.' AS p')->result();
		
		/*
		 * If the option DROPDOWN (third paramenter) has
		 * been set to TRUE, automatically generates data
		 * for dropdown menue generation (KEY=>VALUE) pairs
		 */
		if($dropdown)
		{     
	        $data[''] =  $empty;
	       
	        //Creating Assosiative Array
	        foreach ($results as $row)
	            $data[$row->id]= $row->prodname;
	        
	        return $data;
		}
		return $results;
	}
}