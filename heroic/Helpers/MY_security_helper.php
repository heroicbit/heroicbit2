<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(! function_exists('xss_clean'))
{
	function xss_clean($content, $safe_level = 0)
	{
		$xss = new App\libraries\XssClean();
		return $xss->clean_input($content, $safe_level);
	}
}

