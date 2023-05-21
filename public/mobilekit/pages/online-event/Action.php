<?php

/** 
 * Edit profile page action
 * 
 */
class PageAction {

	public function __construct()
	{
		
	}

	// This method handle get request
	public function render()
	{
		$perpage = ci()->input->get('perpage', true) ?? 10;
		$page = ci()->input->get('page', true) ?? 1;

		$type = 'reguler';
		if(ci()->input->get('premium', true)){
			ci()->shared['module'] = 'premium';
			ci()->shared['page_title'] = 'Event Premium';
			$type = 'premium';
		}

        return compact('type');
	}


	// This method handle POST request
	public function process()
	{

	}

}