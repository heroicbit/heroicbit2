<?php namespace App\Views\Pages\components;

use App\Views\Pages\FrontAction;

class PageAction extends FrontAction {

	// This method handle GET request
	public function render()
	{
		$data['themeURL'] = service('settings')->get('Theme.frontendThemeURL'); 
		$data['themePath'] = service('settings')->get('Theme.frontendThemePath'); 
		$data['title'] = service('settings')->get('Site.siteName');
		return $data;
	}

	// This method handle GET request via AJAX
	public function supply(){}

	// This method handle POST request
	public function process(){}

}