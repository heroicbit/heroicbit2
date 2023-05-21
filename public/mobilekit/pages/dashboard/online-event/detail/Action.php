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
        $id = ci()->uri->segment(4);
        $PembinaanModel = setup_entry_model('pembinaan');
        $pembinaan = $PembinaanModel->get($id);

        return compact('pembinaan');
	}


	// This method handle POST request
	public function process()
	{

	}

}