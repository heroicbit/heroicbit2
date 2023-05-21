<?php namespace App\Pages\home;

use App\Pages\AdminAction;

class PageAction extends AdminAction {

	// This method handle GET request
	public function render()
	{
		$this->data['page_title'] = 'The Excelent Page';
		return $this->data;
	}

	// This method handle GET request via AJAX
	public function supply(){}

	// This method handle POST request
	public function process(){}

}