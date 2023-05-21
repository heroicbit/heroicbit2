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
        $id = ci()->uri->segment(3);
		$VideoModel = setup_entry_model('video');
        $video = $VideoModel->get($id);

        return compact('video');
	}


	// This method handle POST request
	public function process()
	{

	}

}