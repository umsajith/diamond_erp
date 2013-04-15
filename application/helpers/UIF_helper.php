<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * UI Components Factory
 * CodeIgniter Twitter Bootstrap
 * @author Marko Aleksic <psybaron@gmail.com>
 */
class UIF {

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public static function contentHeader($mainTitle = '', $meta = '')
	{
		$product = "<div class='row-fluid'><div class='span6' id='content-main-title'>";
		$product .= "<h4>{$mainTitle}</h4>";
		$product .= "</div>";

		if($meta != '')
		{
			$product .= "<div class='span6 text-right' id='content-main-info'>";
			$product .= "<p class='muted'>#{$meta->id} @{$meta->dateofentry}</p>";
			$product .= "</div>";
		}

		$product .= "</div><hr>"; 
		
		return $product;
	}

	public static function linkButton($uri = '', $icon = '', $type = 'primary')
	{
		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}

		return '<a href="'.$site_url.'" class="btn btn-'.$type.'"><i class="'.$icon.'"></i></a>';
	}

	public static function linkDeleteButton($uri = '')
	{
		return self::linkButton($uri, 'icon-trash confirm-delete', 'danger');
	}

	public static function linkInsertButton($uri = '')
	{
		return self::linkButton($uri, 'icon-file');
	}

	public static function button($icon = '', $type = '', $attributes = '')
	{
		$icon = (string) $icon;
		$type = (string) $type;

		if ($type != '')
		{
			$type = 'btn-'.$type;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<button class="btn '.$type.'"'.$attributes.'><i class="'.$icon.'"></i></button>';
	}

	public static function submitButton()
	{
		return self::button('icon-save','primary','type="submit"');
	}

	public static function linkIcon($uri = '', $icon = '', $attributes = '')
	{
		$icon = (string) $icon;

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}

		if ($icon == '')
		{
			$icon = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.'class="'.$icon.'"'.$attributes.'>'.'&nbsp;'.'</a>';
	}

	public static function staticIcon($icon = '', $attributes = '')
	{
		$icon = (string) $icon;

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<i class="'.$icon.'"'.$attributes.'>'.''.'</i>';
	}

	// public function deleteButton($uri = '',$id = '')
	// {
	// 	$out = form_open(site_url('job_orders/delete'),'class="delete-form"');
	// 	$out .= form_hidden('id',$id);
	// 	$out .= '<button type="submit" class="btn btn-link icon-trash confirm-delete"></button>';
	// 	$out .= form_close();
	// 	return $out;
	// }

	public static function actionGroup($controller = '', $id = '', $edit = 'edit', $delete = 'delete')
	{
		$edit = $controller.'/'.$edit.'/'.$id;
		$editIcon = self::linkIcon($edit,'icon-edit');

		$delete = $controller.'/'.$delete.'/'.$id;
		$deleteIcon = self::linkIcon($delete,'icon-trash confirm-delete');

		return '<div class="action-group">'.$editIcon.''.$deleteIcon.'</div>';
	}

	public static function controlGroup($type = '', $label = '', $name = '', $value = false, $attributes = '')
	{
		$out  = '<div class="control-group">';
		$out .= '<label class="control-label">'.$label.'</label>';
		$out .= '<div class="controls">';

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		switch ($type) {
			case 'text':
				$out .= form_input($name,set_value($name,($value) ? $value->$name : ''),$attributes);
				break;
			case 'dropdown':
				$out .= form_dropdown($name,(isset($value[0])) ? $value[0] : [],
					set_value($name,(isset($value[1])) ? $value[1]->$name : ''),$attributes);
				break;
			case 'textarea':
				$out .= form_textarea($name,set_value($name,($value) ? $value->$name : ''),$attributes);
				break;
			default:
				$out .= '';
				break;
		}

		$out .= '</div></div>';

		return $out;
	}
	/**
	 * Resource Loader
	 * - loads partials from views/includes folder by default
	 */
	public static function load($resource = '', $folder = 'includes')
	{
		$CI =& get_instance();
		return $CI->load->view($folder.'/'.$resource);
	}
}
