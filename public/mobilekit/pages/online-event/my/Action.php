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

		$ParticipantModel = setup_entry_model('event_participant');
		$events = $ParticipantModel->with_event()->where('user_id', ci()->session->user_id)->order_by('created_at','desc')->getAll();

        return compact('events');
	}


	// This method handle POST request
	public function process()
	{

	}

}