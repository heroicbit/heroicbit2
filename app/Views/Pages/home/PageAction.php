<?php namespace App\Views\Pages\home;

use App\Views\Pages\FrontAction;

class PageAction extends FrontAction {

	// This method handle GET request
	public function render()
	{
		$ThemeConfig = config('Heroic\\Config\\Theme'); 
		$data['themeURL'] = $ThemeConfig->frontendThemeURL; 
		$data['themePath'] = $ThemeConfig->frontendThemePath; 

		$data['title'] = service('settings')->get('Site.siteName');
		return $data;
	}

	// This method handle GET request via AJAX
	public function supply(){}

	// This method handle POST request
	public function process(){}

}