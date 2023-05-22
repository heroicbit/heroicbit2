<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\libraries\Mein_Twig_Extension;

if ( ! function_exists('view'))
{
	function view($view, $data = [], $return = false, $noparse = false)
	{
		$data = array_merge($data, ci()->shared);

		// Set default meta title and description
		if(!isset($data['page_title']) || empty($data['page_title']))
			$data['page_title'] = site_config('current_modules')['name'] ?? site_config('current_modules');

		if(!isset($data['meta_description']) || empty($data['meta_description']))
			$data['meta_description'] = site_config('current_modules')['description'] ?? '';

		// Check view in custom theme first 
		// if(file_exists(ci()->shared['theme_path'].'/'.$view.'.php'))
		// 	$data['content'] = ci()->shared['theme'].'/'.$view.'.php';
		
		// // Check view in default theme 
		// elseif(file_exists(realpath('themes/default').'/'.$view.'.php'))
		// 	$data['content'] = realpath('themes/default').'/'.$view.'.php';
		
		// // Show view from module itself or default view 
		// elseif(! is_null($view))
		$data['content'] = $view.'.php';

		// else
		// if $view is null, it means that $data['content'] has already defined :)

		if(isset($data['layout'])) ci()->layout = $data['layout'];

		if(! $noparse) {
			for($i = 1; $i < 10; $i++) $data['seg_'.$i] = ci()->uri->segment($i);

			$loader = new Twig_Loader_Filesystem([
				ci()->shared['theme_path'],
				realpath('themes/default'),
				ci()->config->config['current_modules']['path'].'views'
			]);
			// $loader = new Twig_Loader_Array([
			// 	'page' => $final,
			// 	'coba' => '<h1>Oyeee</h1>'
			// ]);

			$opt = [];
			if($_ENV['CACHE_TWIG']){
				$opt = ['cache' => './resources/cache/twig/'];
			}

			$twig = new Twig_Environment($loader, $opt);
			$twig->addExtension(new Mein_Twig_Extension());

			// $data['content'] = 'pages/percobaan/content.html';

			if($return)
				return $twig->render('layouts/percobaan.php', $data);
			else
				echo $twig->render('layouts/percobaan.php', $data);
		} else {
			if($return)
				return $final;
			else
				echo $final;
		}
	}
}

/**
	* set and get the partial for templating
	*
	* @param String $file 	name
	* @return String css url or tag
	*/
if ( ! function_exists('get_partial'))
{
	function get_partial($name) {
		echo ci()->template->load_view('partials/'.$name);
	}
}

/**
	* set and get theme url + url of asset in theme
	*
	* @param String $url 	url of asset
	* @param String $source 'theme', or 'jooglo', 'dev' and any asset location set in template config
	* @return String url
	*/
if ( ! function_exists('get_source_url'))
{
	function get_source_url($url = false) {
		return base_url(SOURCEPATH.$url);
	}
}

/**
	* set and get theme url + url of asset in theme
	*
	* @param String $url 	url of asset
	* @param String $source 'theme', or 'jooglo', 'dev' and any asset location set in template config
	* @return String url
	*/
if ( ! function_exists('get_source_path'))
{
	function get_source_path($url = false) {
		return SOURCEPATH.$url;
	}
}

if ( ! function_exists('shortcode'))
{
	function shortcode($shortcode, $args = [])
	{
    	if(!strpos($shortcode, '.'))
    		show_error('"'. $shortcode .'" as shortcode must contain dot "." as separator between module name and shortcode function name.');

    	list($module, $method) = explode('.', $shortcode);
        $className = ucfirst($module).'Shortcode';

        if(!isset(ci()->shortcodeClasses[$className]))
        {
            $modules =& ci()->config->config['modules'];
            
            // check shortcode from shortcode application folder first
            if(file_exists(APPPATH.'shortcodes/'.$className.'.php'))
            {
                if(!class_exists($className, false))
                    include_once APPPATH.'shortcodes/'.$className.'.php';

                ci()->shortcodeClasses[$className] = new $className();
            }

            // check shortcode from module if exist
            else if(isset($modules[$module])
                    && file_exists($modules[$module]['path'].'Shortcodes.php'))
            {
                if(!class_exists($className, false))
                    include_once $modules[$module]['path'].'Shortcodes.php';

                ci()->shortcodeClasses[$className] = new $className();
            } 

            else {
                show_error('"'. $shortcode .'" shortcode not found in any module.');
            }

            unset($modules);
        }

        ci()->shortcodeClasses[$className]->setAttributes($args);
    	return ci()->shortcodeClasses[$className]->$method();
	}
}

if ( ! function_exists('library'))
{
	function library($libraryName, $args = [])
	{
    	if(!strpos($libraryName, '.'))
    		show_error('"'. $libraryName .'" name must contain dot "." as separator between library name and method name.');

    	list($library, $method) = explode('.', $libraryName);

    	return ci()->library->$method($args);
	}
}

if ( ! function_exists('getProfilePicture'))
{
    function getProfilePicture($filename)
	{
        return ci()->ci_auth->getProfilePicture($filename);
    }
}