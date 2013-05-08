<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Application notifiction system
 * @author Marko Aleksic <psybaron@gmail.com>
 */
class Air {
	/**
	 * Notifications using CodeIgniter's flash session
	 * @param  string  $type        Type of notification (info,success,error)
	 * @param  string  $redirect_to Redirect destination
	 */
	public static function flash($type = '', $redirect_to = '', $redirect = true)
	{
		$CI =& get_instance();

        $language = $CI->config->item('language');

        $CI->lang->load('air', $language);

        switch ($type) {
            case 'add':
                $message = $CI->lang->line('air_add');
                $class = 'success';
                break;
            case 'update':
                $message = $CI->lang->line('air_update');
                $class = 'success';
                break;
            case 'delete':
                $message = $CI->lang->line('air_delete');
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
                $mesage = $CI->lang->line('air_deny');
                show_error($mesage, 403);
                break;
            case 'error':
            default:
                $message = $CI->lang->line('air_error');
                $class = 'error';
                break;
            }
		
		//Sets the Message
		$CI->session->set_flashdata('message',$message);
		$CI->session->set_flashdata('type',$class);

		//Redirect
		if($redirect_to !== '')
		{
			redirect($redirect_to);
		}
	}
}