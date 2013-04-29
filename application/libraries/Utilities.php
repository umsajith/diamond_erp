<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Utilities {

	protected $CI;
	
    function __construct()
    {
        $this->CI =& get_instance();
    }
    
	//Generates standard dropdown menu
    function get_dropdown($key, $value, $from, $empty = '--')
	{
       $data = array();

       //Prepare query
       $this->CI->db->select($key.','.$value);
       $this->CI->db->order_by($value,'asc');
       
       //Retreive data from database
       $results = $this->CI->db->get($from)->result();
       
       $data[''] =  $empty;
       
       //Creating Assosiative Array
       foreach ($results as $row)
            $data[$row->$key]= $row->$value;
        
        return $data;
    }
    
	function get_single($id, $from)
	{
       $this->CI->db->select();
       $this->CI->db->from($from);
       $this->CI->db->where('id',$id);
       
       return $this->CI->db->get()->row();
    }
    
    function get_products($type = '',$stockable = false,$dropdown = false,$empty = '--')
    {  
        if(!in_array($type,array('salable','purchasable')))
			die();
			
		//Query
		$this->CI->db->select('p.id,p.prodname,u.uname,pc.pcname');
		$this->CI->db->from('exp_cd_products AS p');
		$this->CI->db->join('exp_cd_uom AS u','u.id = p.uname_fk','LEFT');
		$this->CI->db->join('exp_cd_product_category AS pc','pc.id = p.pcname_fk','LEFT');

		if($type == 'salable')
		{
			$this->CI->db->where('p.salable',1);
			$empty = '- Производ -';
		}
			
		if($type == 'purchasable')
		{
			$this->CI->db->where('p.purchasable',1);
			$empty = '- Артикл -';
		}
			
		if($stockable == true)
			$this->CI->db->where('p.stockable',1);
				
		$this->CI->db->where('p.status','active');

		$this->CI->db->order_by('p.prodname','asc');
		
		$results = $this->CI->db->get()->result();
		
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
    
    //Creates a dropdown of Cities using postal code keys
    function get_postalcodes()
    {
    	$key = 'id';
    	$value = 'name';
    	
    	//Generating Querry
    	$this->CI->db->select('p.id,c.name');
    	$this->CI->db->from('exp_cd_postalcode AS p');
    	$this->CI->db->join('exp_cd_cities AS c', 'c.id = p.city_fk');
    	$this->CI->db->order_by('c.name','asc');
    	
    	$array_keys_values = $this->CI->db->get();
    	
    	$data =  [];
    	
    	//Creating Assosiative Array
       foreach ($array_keys_values->result() as $row)
        {
            $data[$row->$key]= $row->$value;
        }
        
        return $data;
    }
    
	// function get_employees($type = 'all', $empty = '')
 //    {	
 //    	//Generating Querry
 //    	$this->CI->db->select('e.id,e.fname,e.lname');
 //    	$this->CI->db->from('exp_cd_employees AS e');
 //    	$this->CI->db->order_by('e.fname');
    	
 //    	if($type == 'fixed')
 //        {
 //    		$this->CI->db->where('fixed_wage_only',1);
 //            $this->CI->db->where('is_distributer',0);
 //        }
 //    	if($type == 'variable')
	// 	{
 //            $this->CI->db->where('fixed_wage_only',0);
 //            $this->CI->db->where('is_distributer',0);
 //        }
    	
 //    	$this->CI->db->where('status','active');
    	
 //    	$array_keys_values = $this->CI->db->get()->result();
        
 //    	$data =  []; 

 //        if($empty != '')
 //           $data[''] =  $empty; 
    	
 //    	//Creating Assosiative Array
 //       foreach ($array_keys_values as $row)
 //            $data[$row->id]= $row->fname . ' ' . $row->lname;
        
 //        return $data;
 //    }
	
	function get_boms()
    {	
    	$key = 'id';
    	$value = 'name';
    	
    	//Generating Querry
    	$this->CI->db->select('id,name');
    	$this->CI->db->from('exp_cd_bom');
    	
    	$array_keys_values = $this->CI->db->get();
    	
    	$data[''] =  '- Норматив -';
    	
    	//Creating Assosiative Array
       foreach ($array_keys_values->result() as $row)
        {
            $data[$row->$key]= $row->$value;
        }
        
        return $data;
    }
    
	function get_managers()
    {
    	$key = 'id';
    	$value1 = 'lname';
    	$value2 = 'fname';
    	$data = array();
    	
    	//Generating Querry
    	$this->CI->db->select('id,fname,lname');
    	$this->CI->db->from('exp_cd_employees');
    	$this->CI->db->order_by('lname','asc');
    	$this->CI->db->where('status','active');
    	$this->CI->db->where('is_manager',1);
    	
    	$results = $this->CI->db->get()->result();
    	
    	$data[''] =  '- Менаџер -';
    	
    	//Creating Assosiative Array
       foreach ($results as $row)
            $data[$row->$key]= $row->$value1 . ' ' . $row->$value2;
   
        return $data;
    }
    
	function get_distributors()
    {
    	$key = 'id';
    	$value1 = 'lname';
    	$value2 = 'fname';
    	$data = array();
    	
    	//Generating Querry
    	$this->CI->db->select('id,fname,lname');
    	$this->CI->db->from('exp_cd_employees');
    	//$this->CI->db->join('exp_cd_cities AS c', 'c.id = p.city_fk');
    	$this->CI->db->order_by('lname','asc');
    	$this->CI->db->where('status','active');
    	$this->CI->db->where('is_distributer',1);
    	
    	$results = $this->CI->db->get()->result();
    	
    	$data[''] =  '- Дистрибутер -';
    	
    	//Creating Assosiative Array
       foreach ($results as $row)
            $data[$row->$key]= $row->$value1 . ' ' . $row->$value2;
        
        return $data;
    }
    
	function flash($type,$redirect_to = '',$redirect = true)
	{
        switch ($type) {
            case 'add':
                $message = 'Ставката е успешно внесена!';
                $class = 'success';
                break;
            case 'update':
                $message = 'Ставката е успешно ажурирана!';
                $class = 'success';
                break;
            case 'delete':
                $message = 'Ставката е успешно избришана!';
                $class = 'success';
                break;
            case 'void':
                // $message = 'Ставката не постои!';
                // $class = 'alert';
                show_404();
                break;
            case 'deny':
                //$message = 'Забранет пристап!';
                //$class = 'error';
                $mesage = "<h1>403 Forbidden</h1><p>The action you tried to perform is forbidden.</p>";
                show_error($mesage, 403);
                break;
            case 'error':
            default:
                $message = 'Неуспешно извршена операција!';
                $class = 'error';
                break;
            }
		
		//Sets the Message
		$this->CI->session->set_flashdata('message',$message);
		$this->CI->session->set_flashdata('type',$class);

		//Redirects
		if($redirect != '')
			redirect($redirect_to);
	}
}

