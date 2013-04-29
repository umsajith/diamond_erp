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
		$CI->session->set_flashdata('message',$message);
		$CI->session->set_flashdata('type',$class);

		//Redirect
		if($redirect_to !== '')
		{
			redirect($redirect_to);
		}
	}
}