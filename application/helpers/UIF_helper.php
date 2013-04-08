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

	public static function createGenericButton($link, $type = 'primary', $size = 'default')
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
	public static function createLockButton($link, $onClick = false, $extra = false)
	{
		$destination = site_url($link);
		if($onClick)
			$onClick = "onClick='$onClick'";
		return "<a href='$link' class='btn btn-success' $onClick $extra><i class='icon-lock'></i></a>";
	}
}
