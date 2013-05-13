<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom Pagination Helper w/ Twitter Bootrap template
 * @param  string $url        
 * @param  integer $rows       
 * @param  integer $limit      
 * @param  integer $uriSegment
 * @return Object             Pagination Object
 */
function paginate($url = '', $rows = 0, $limit = 0, $uriSegment = 0)
{
    $CI =& get_instance();

    $config['full_tag_open'] = '<div class="pagination pagination-right"><ul>';
    $config['full_tag_close'] = '</ul></div><!--pagination-->';
    
    $config['first_tag_open'] = '<li class="prev page">';
    $config['first_tag_close'] = '</li>';
    
    $config['last_tag_open'] = '<li class="next page">';
    $config['last_tag_close'] = '</li>';
    
    $config['next_tag_open'] = '<li class="next page">';
    $config['next_tag_close'] = '</li>';
    
    $config['prev_tag_open'] = '<li class="prev page">';
    $config['prev_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';

    $config['num_tag_open'] = '<li class="page">';
    $config['num_tag_close'] = '</li>';

    $config['base_url'] = site_url($url);
    $config['total_rows'] = $rows;
    $config['per_page'] = $limit;
    $config['uri_segment'] = $uriSegment;
    $config['num_links'] = 3;
    $config['first_link'] = '';
    $config['last_link'] = '';

    $config['first_link'] = '&laquo; ' . uif::lng('common.first');
    $config['last_link'] = uif::lng('common.last') . ' &raquo;';
    $config['next_link'] = uif::lng('common.next') . ' &rarr;';
    $config['prev_link'] = '&larr; ' . uif::lng('common.previous');

    $CI->pagination->initialize($config);
    
    return $CI->pagination->create_links(); 
}