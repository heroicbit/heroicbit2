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
        $slug = ci()->uri->segment(3);
        ci()->load->model('post/Post_model');
        $posts = ci()->Post_model->getPosts('post','array','publish');

        return compact('posts');
	}


	// This method handle POST request
	public function process()
	{

	}

}