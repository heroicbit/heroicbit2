<?php

use CodeIgniter\Config\Factories;
use Symfony\Component\Yaml\Yaml;

// Modules helpers, you can move this functions to a true helper file

/**
 * Return the CodeIgniter modules list
 * @param bool $with_location
 * @return array
 */
if (!function_exists('modules_list')) 
{
    function modules_list($with_location = TRUE)
    {
        // Get from config if already defined
        if(ci()->config->item('modules'))
            return ci()->config->item('modules');

        // Get enabled modules
        $enabled = Yaml::parseFile(SITEPATH.'configs/enabled_modules.yml');

        $module_paths = config_item('module_paths');
        if(!$with_location) return array_keys($module_paths);

        // Get module list
        $modules = [];
        foreach ($module_paths as $module => $path)
        {
            if (is_dir($path[0]) && file_exists($path[0] . 'module.yml'))
            {
                $modules[$module] = [
                    0 => $path[0],
                    1 => $module,
                    'path' => $path[0],
                    'enable' => in_array($module, $enabled)
                ];

                // get module.yml
                $parsed = [];
                if(file_exists($path[0].'module.yml')){
                    $parsed = Yaml::parseFile($path[0].'module.yml');
                    
                }

                // Set default module details
                $default = [
                    "name" => ucfirst($module),
                    "icon" => "file-o",
                    "description" => "",
                    "author" => "unknown",
                    "author_url" => "" ,
                    "custom_url" => "",
                    "show_admin_menu" => false,
                    "menu_position" => 90,
                    "parent_menu" => "",
                    "custom_menu" => "",
                    "setting" => false
                ];
                
                $modules[$module] = array_merge($modules[$module], $default, $parsed);
            }
        }

        return $modules;
    }
}

function register_all_modules()
{
    $modules = cache()->get('modules') ?? '';
    
    if(!$modules)
    {
        $modules = modules_list();

        if($_ENV['CI_ENV'] == 'production')
            cache()->set('modules', $modules, 24*60*60);
    }

    ci()->config->set_item('modules', $modules);
}

/**
 * Check if a CodeIgniter module with the given name exists
 * @param $module_name
 * @return bool
 */
if (!function_exists('module_exists'))
{
    function module_exists($module_name)
    {
        return in_array($module_name, modules_list(FALSE));
    }
}

/**
 * Get module setting value
 * @param $module_name
 * @return bool
 */
if (!function_exists('setting_item'))
{
    function setting_item($setting_name, $default_null = '')
    {
        $Setting = Factories::libraries('Heroic\Libraries\Setting');
        return $Setting->get($setting_name);
    }
}

/**
 * Get module setting value
 * @param $module_name
 * @return bool
 */
if (!function_exists('setting_items'))
{
    function setting_items($module)
    {
        $settings_key = array_keys(ci()->shared['settings']);
        $m_array = preg_grep('/^'.$module.'\..*/', $settings_key);
        $result = [];
        foreach ($m_array as $key) {
            $result[substr($key, strlen($module)+1)] = ci()->shared['settings'][$key];
        }
        
        return $result;
    }
}

/**
 * Get setting item from core system
 * @return array
 */
if (!function_exists('core_setting')) 
{
    function core_setting()
    {
        $settingFolder = APPPATH.'settings/';

        helper('filesystem');
        $files = directory_map($settingFolder, 1);
        $settings = [];
        foreach ($files as $file) {
            $fileinfo = pathinfo($file);
            $settings[$fileinfo['filename']] = Yaml::parseFile($settingFolder.$file);
        }

        return $settings;
    }
}



/**
 * Get setting item from module list YAML
 * @return array
 */
if (!function_exists('module_setting')) 
{
    function module_setting($checkPermission = true)
    {
        $modules = get_instance()->config->config['modules'];
        
        // Eliminate module without setting
        foreach($modules as $module => $module_value)
        {
            if(empty($modules[$module]['setting']) || ($checkPermission && !isPermitted('settings', $module)))
                unset($modules[$module]);
        }
        
        return $modules;
    }
}

/**
 * Get setting item from module list YAML
 * @return array
 */
if (!function_exists('entry_setting')) 
{
    function entry_setting($checkPermission = true)
    {
        $modules = get_instance()->config->config['entries'];
        
        // Eliminate module without setting
        foreach($modules as $module => $module_value)
        {
            if(empty($modules[$module]['setting']) || ($checkPermission && !isPermitted('settings', $module)))
                unset($modules[$module]);
        }
        
        return $modules;
    }
}

/**
 * Get setting item from module list YAML
 * @return array
 */
if (!function_exists('site_setting')) 
{
    function site_setting()
    {
        $settingFolder = SITEPATH.'settings/';

        $files = directory_map($settingFolder, 1);
        $settings = [];
        foreach ($files as $file) {
            $fileinfo = pathinfo($file);
            $settings[$fileinfo['filename']] = Yaml::parseFile($settingFolder.$file);
        }

        return $settings;
    }
}

if (!function_exists('normalizePath')) {

    /**
     * Remove the ".." from the middle of a path string
     * @param string $path
     * @return string
     */
    function normalizePath($path)
    {
        $parts    = array(); // Array to build a new path from the good parts
        $path     = str_replace('\\', '/', $path); // Replace backslashes with forwardslashes
        $path     = preg_replace('/\/+/', '/', $path); // Combine multiple slashes into a single slash
        $segments = explode('/', $path); // Collect path segments
        foreach ($segments as $segment) {
            if ($segment != '.') {
                $test = array_pop($parts);
                if (is_null($test))
                    $parts[] = $segment;
                else if ($segment == '..') {
                    if ($test == '..')
                        $parts[] = $test;

                    if ($test == '..' || $test == '')
                        $parts[] = $segment;
                } else {
                    $parts[] = $test;
                    $parts[] = $segment;
                }
            }
        }

        return implode('/', $parts);
    }

}

if (!function_exists('isPermitted')) {
    
    function isPermitted($permission, $module = null, $whitelist = [])
    {
        return ci()->ci_auth->isPermitted($permission, $module, $whitelist);
    }
}

if (!function_exists('checkPermission')) {
    
    /** $module_permission   with format module:permission
     *  $whitelist  user id which allowed to access directly, i.e. post owner
     */
    function checkPermission($module_permission, $whitelist = [], $heading = null, $message = null)
    {
        if(! strpos($module_permission,':'))
            throw new Exception('Malfomed module permission name "' . $module_permission . '". It should be written with format module:permission');

        list($module,$permission) = explode(":",$module_permission);

        if(! ci()->ci_auth->isPermitted($permission, $module, $whitelist))
            show_error($message ?? "You don't have permission $permission in module $module.", 401, $heading ?? 'Unauthorized');
    }
}
