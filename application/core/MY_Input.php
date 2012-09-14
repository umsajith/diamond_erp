<?php
class MY_Input extends CI_Input {

   function save_query($query_array)
   {
   		$CI =& get_instance();
   		
   		$CI->db->insert('ci_query',array('query_string' => http_build_query($query_array)));
   		
   		return $CI->db->insert_id();
   }
   
   function load_query($query_id)
   {
   		$CI =& get_instance();
   		
   		$query = $CI->db->get_where('ci_query',array('id' => $query_id))->row();
   		
   		if($query)
   		{
   			parse_str($query->query_string, $_GET);
   		}
   }
}