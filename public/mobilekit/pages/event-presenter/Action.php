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
		$ref = ci()->input->get('ref', true) ?? 'dashboard';

		$presenter = ci()->uri->segment(2);
		if(!$presenter) redirect('online-event');

		$PresenterModel = setup_entry_model('presenter');
		$presenterData = $PresenterModel->get($presenter);
		if(!$presenterData) redirect('online-event');

		ci()->shared['page_title'] = 'Event oleh '.$presenterData['name'];

        return compact('presenter','ref');
	}


	// This method handle POST request
	public function process()
	{

	}

}