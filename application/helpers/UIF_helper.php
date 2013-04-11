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

	public static function createContentHeader($mainTitle, $infoText = false)
	{
		$product = "<div class='row-fluid'><div class='span6' id='content-main-title'>";
		$product .= "<h4>{$mainTitle}</h4>";
		$product .= "</div>";
		if($infoText)
		{
			$product .= "<div class='span6 text-right' id='content-main-info'>";
			$product .= "<p class='muted'>{$infoText}</p>";
			$product .= "</div>";
		}
		$product .= "</div><hr>"; 
		return $product;
	}
	/**
	 * TODO Change function to createLinkButton
	 */
	public static function createLinkButton($link, $type = 'primary', $size = 'default')
	{
		//Add link prep and check if full link is already supplied
		$destination = site_url($link);
		return "<a href='$destination' class='btn btn-$type btn-$size'><i class='icon-file'></i></a>";
	}

	public static function createInsertButton($link, $type = 'primary', $size = 'default')
	{
		$destination = site_url($link);
		return "<a href='$destination' class='btn btn-$type btn-$size'><i class='icon-file'></i></a>";
	}
	public static function createButton($icon = '', $attributes = '', $type = '')
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

	public static function createLinkIcon($uri = '', $icon = '', $attributes = '')
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

		return '<a href="'.$site_url.'"'.'class="'.$icon.'"'.$attributes.'>'.''.'</a>';
	}

	public static function createStaticIcon($icon = '', $attributes = '')
	{
		$icon = (string) $icon;

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<i class="'.$icon.'"'.$attributes.'>'.''.'</i>';
	}
}
