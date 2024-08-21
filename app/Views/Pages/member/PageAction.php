<?php namespace App\Views\Pages\member;

use App\Views\Pages\FrontAction;

class PageAction extends FrontAction {

	public $data;

	public function __construct()
	{
		$this->data['themeURL'] = service('settings')->get('Theme.frontendThemeURL'); 
		$this->data['themePath'] = service('settings')->get('Theme.frontendThemePath'); 
		$this->data['title'] = service('settings')->get('Site.siteName');
	}

	// This method handle GET request
	public function render()
	{
		return $this->data;
	}

	// This method handle GET request via AJAX
	public function supply(){}

	// This method handle POST request
	public function process(){}

}