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
		$perpage = ci()->input->get('perpage', true) ?? 9;
		$page = ci()->input->get('page', true) ?? 1;
		$offset = ($page-1)*$perpage;

		$VideoModel = setup_entry_model('video');
		$videos = $VideoModel->order_by('created_at','desc')->limit($perpage,$offset)->getAll();

        return compact('videos','page','perpage');
	}


	// This method handle POST request
	public function process()
	{

	}

}