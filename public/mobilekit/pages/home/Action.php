<?php

/** 
 * Home Page Action
 * 
 */
class PageAction {

	public function __construct()
	{
		
	}

	// This method handle get request
	public function render()
	{
		$data['title'] = setting_item('site.site_title')  . ' - ' . setting_item('site.site_desc');
		return $data;
	}


	// This method handle POST request
	public function process()
	{

	}

}