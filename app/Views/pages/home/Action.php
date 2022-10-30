<?php

class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function run()
	{
		$data['page_title'] = 'The Excelent Page';
		return $data;
	}


	// This method handle POST request
	public function process(){}

}