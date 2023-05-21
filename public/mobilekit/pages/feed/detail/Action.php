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
        ci()->load->model('post/Post_model');
        $post = ci()->Post_model->getPost('publish','id',$id,'array');
        if(!$post) show_404();

        ci()->load->model('post/Taxonomy_model');
        $category = ci()->Taxonomy_model->get_category($post['id']);

        return compact('post','category');
	}


	// This method handle POST request
	public function process()
	{

	}

}