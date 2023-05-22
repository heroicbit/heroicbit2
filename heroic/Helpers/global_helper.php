<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global Helper 
 */

if (!function_exists('ci')) {
	/**
	 * Get CodeIgniter instance
	 */
	function &ci()
	{
		return get_instance();
	}
}

if ( ! function_exists('t'))
{
	// Return caption with translated text if exists
    function t($variable)
    {
		if($translated = ci()->lang->line($variable))
	    	return $translated;
		return $variable;
    }
}

if ( ! function_exists('json_response'))
{
    function json_response($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;  
    }
}

if ( ! function_exists('firstParagraph'))
{
	function firstParagraph($str) 
	{
		return strip_tags(substr($str, 0, strpos($str, "\n")));
	}
}

if ( ! function_exists('get_excerpt'))
{
	function get_excerpt($str, $startPos=0, $maxLength=100) 
	{
		if(strlen($str) > $maxLength) {
			$excerpt   = substr($str, $startPos, $maxLength-3);
			$lastSpace = strrpos($excerpt, ' ');
			$excerpt   = substr($excerpt, 0, $lastSpace);
			$excerpt  .= ' ...';
		} else {
			$excerpt = $str;
		}

		return $excerpt;
	}
}

if ( ! function_exists('make_label'))
{
	function make_label($string) 
	{
		$string = str_replace('-',' ', $string);
		$string = ucwords(str_replace('_',' ', $string));
		return $string;
	}
}

if ( ! function_exists('time_ago'))
{
	function time_ago($date = null)
	{
		if(!$date) return '';

		$now = time();
		$seconds = strtotime($date);
		$selisih = $now - $seconds;

		// kalo lebih dari 7 hari yang lalu
		if(date('d', $selisih) > 7)
			return PHP81_BC\strftime("%d %B %Y, %H:%M", $seconds, ci()->config->config['locale']);
		else
			return strtolower(timespan($seconds, $now, 1)).' yang lalu';
	}
}

if ( ! function_exists('days_left'))
{
	function days_left($date)
	{
		$future = strtotime($date);
		$timefromdb = time();
		$timeleft = $future-$timefromdb;
		$daysleft = round((($timeleft/24)/60)/60); 
		return $daysleft;
	}
}


if ( ! function_exists('slugify'))
{
	function slugify($text){

		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}
}

if ( ! function_exists('site_config'))
{
	function site_config($item)
	{
		return ci()->config->config[$item];
	}
}

if(! function_exists('view')){
	function view($view, $data = [])
	{
		return ci()->load->view($view, $data, true);
	}
}

if(! function_exists('session')){
	function session($key)
	{
		return ci()->session->userdata($key);
	}
}

if (!function_exists('isLoggedIn')) {
	function isLoggedIn($key = null)
	{
		return ci()->ci_auth->isLoggedIn($key);
	}
}

if(! function_exists('cache')){
	function cache()
	{
		if(! isset(ci()->tinyCache)){
			$cacheFactory = new Gemblue\TinyCache\CacheFactory;
	        $cacheConfig = config_item('cache_config');
	        $cacheDriver = $_ENV['CACHE_DRIVER'] ?? 'File';
	        ci()->tinyCache = $cacheFactory->getInstance($cacheDriver, $cacheConfig[$cacheDriver]);
		}
        
        return ci()->tinyCache;
	}
}

if(! function_exists('catchGetVar')){
	function catchGetVar($key = null)
	{
		return ci()->input->get($key, true);
	}
}

if(! function_exists('catchServerVar')){
	function catchServerVar($key = null)
	{
		return $_ENV[$key] ?? '';
	}
}

if(! function_exists('flashdata')){
	function flashdata($key)
	{
		return ci()->session->flashdata($key);
	}
}
if(! function_exists('set_flashdata')){
	function set_flashdata($key, $message)
	{
		return ci()->session->set_flashdata($key, $message);
	}
}

if(! function_exists('parsedown')){
	function parsedown($content)
	{
		$Parsedown = new ParsedownExtra();
		return $Parsedown->text(html_entity_decode($content));
	}
}

if(! function_exists('sum_times')){
	function sum_times($times) {
	    $seconds = 0;

	    foreach ($times as $time) {
	    	if(strpos($time, ':') === false) continue;
	        sscanf($time, "%d:%d", $minute, $second);
			$seconds += $minute * 60 + $second;
	    }

	    $minutes = floor($seconds / 60);
	    $result['hours'] = floor($minutes / 60);
	    $result['minutes'] = $minutes % 60;
	    
	    return $result;
	}
}

if(! function_exists('print_code')){
	function print_code($data) {
	    App\libraries\Console::log($data);
	}
}

if(! function_exists('print_debug')){
	function print_debug($data, $die = true) {
		echo "<pre>";
	    print_r($data);
		echo "</pre>";
		if($die) die;
		return true;
	}
}

if(! function_exists('gravatar')){
	function gravatar($email, $size = 80){
		$url = "https://www.gravatar.com/avatar/{md5($email)}?s=$size&default=mm";
		return $url;
	}
}

if(! function_exists('compare_with_symbol')){
	function compare_with_symbol($a, $b, $char) {
	    switch($char) {
	        case '==': return $a == $b;
	        case '!=': return $a != $b;
	        case '>': return $a > $b;
	        case '>=': return $a >= $b;
	        case '<': return $a < $b;
	        case '<=': return $a <= $b;
	        case '===': return $a === $b;
	        case '!==': return $a !== $b;
	    }
    }
}

if (!function_exists('camelize')) {
	function camelize($input, $separator = '_')
	{
		return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
	}
}