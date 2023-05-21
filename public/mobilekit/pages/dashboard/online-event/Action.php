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
        $PembinaanModel = setup_entry_model('pembinaan');
        $total = $PembinaanModel->count_rows();
        $pembinaan = $PembinaanModel->paginate($perpage, $total, $page, false, [
        	'page_query_string' => true
        ]);
        $pagination = $PembinaanModel->all_pages;

        return compact('pembinaan','pagination');
	}


	// This method handle POST request
	public function process()
	{

	}

}