<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Diamond ERP Extension of CI_Input
 */
class MY_Input extends CI_Input {

    /**
     * Saves query string into database
     * @param  Array $query_array
     * @return integer
     */
    public function save_query($query_array)
    {
        $CI =& get_instance();

        $CI->db->insert('ci_query',array('query_string' => http_build_query($query_array)));

        return $CI->db->insert_id();
    }
    /**
     * Loads query string by given query id,
     * and stores it in $_GET variable
     * @param  integer $query_id
     */
    public function load_query($query_id)
    {
        $CI =& get_instance();

        $query = $CI->db->get_where('ci_query',array('id' => $query_id))->row();

        if($query)
        {
            parse_str($query->query_string, $_GET);
        }
    }
    /**
    * Logs each report made withing the application
    * @param  $_POST $report_data The whole $_POST array as submitted to report
    * @return integer
    */
    public function log_report($report_data)
    {
        $CI =& get_instance();

        $CI->db->insert('ci_report_logs',[
            'employee_id'  => $CI->session->userdata('userid'),
            'report_url'   => $CI->uri->uri_string(),
            'query_string' => http_build_query($report_data)
        ]);

        return $CI->db->insert_id();
    }
}